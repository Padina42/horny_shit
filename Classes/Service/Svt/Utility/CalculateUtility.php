<?php

namespace Porth\HornyShit\Service\Svt\Utility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017 Dr. Dieter Porth <info@mobger.de>
 *
 *  All rights reserved
 *
 *  This script is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**  allwoed ar arithmetisch calculationstring
 * with the operators
 *      + -  *  /
 *      \(floorerd interger-quoptient-dividend-produc / resulted shifted in negative direction) % (Modulo as differenz between result and floorerd interger-quoptient-dividend-product)
 * and the unitär operators/functions
 *      -, ln, log, sin, cos, tan, abs
 * and the unitär integermaking-tools
 *      round, floor, ceil
 * the decimal-seperator ist the dot
 * and the scientific notation 1.01245E+12 is not allowed
 */
class CalculateUtility
{
//    @todo strategy: make this class to an singleton or to an static class

    const MATH_DECIMALS_PRECISION = 14;
    const PRECISION_DELTA_EXPSILON = 0.0000001;
    const INTERNAL_MARKER_FOR_ERROR = '###Error###';
    /**
     * @var bool
     */
    protected $flagOkay = true;

    /**
     * @var int
     */
    protected $pointNext = 0;

    /**
     * @var array
     */
    protected $calcByteArray = [];


    /**
     * @var int
     */
    protected $calcByteLength = 0;

    /**
     * @var string
     */
    protected $pointByte = '';

    /**
     * @var string
     */
    protected $errorNear = '';


    const RELATION_OPERATORS_TO_PRIORITY = [
        self::OP_MIN => self::OP_PRIO_MIN,
        self::OP_MAX => self::OP_PRIO_MAX,
        self::OP_PLUS => self::OP_PRIO_PLUS,
        self::OP_MINUS => self::OP_PRIO_MINUS,
        self::OP_MULTIPLICATION => self::OP_PRIO_MULTIPLICATION,
        self::OP_DIVISION => self::OP_PRIO_DIVISION,
        self::OP_FLOOR_DIVISION => self::OP_PRIO_FLOOR_DIVISION,
        self::OP_FRACTIONAL_CUT => self::OP_PRIO_FRACTIONAL_CUT,
        self::OP_POWER => self::OP_PRIO_POWER,

    ];
    const STRING_OF_DIGITS_AND_POINT = self::STRING_OF_DIGITS . self::STRING_POINT;

    const PREDEFINED_ARITHMETIC_SHORTCUTS = [
        '/mod/ui' => '#',  // scientific notification of float-values
        '/div/ui' => '\\',  // scientific notification of float-values
        '/([\.|\d]+)e([-|\d]+)/ui' => '($1*10^$2)',  // scientific notification of float-values
        '/([\.|\d]+)e\+(\d+)/ui' => '($1*10^$2)',   // scientific notification of float-values
        '/([\.|\d]+)\%/ui' => '($1/100)',
        '/([\.|\d]+)\°/ui' => '($1*' . M_PI . '/180)',
        '/(\++)\+/ui' => '+',  // doubled ++
        '/^\+/ui' => '',  // remove the obsolete foresign + at the start of the term
        '/(\+|-|\*|\/|\^|\#|\\|↑|↓|\()\+/ui' => '$1',  // remove the obsolete foresign + before every operator
        '/('.self::FUNC_ABS.'|'.self::FUNC_CEIL.'|'.self::FUNC_COS.'|'.self::FUNC_EXP.'|'.self::FUNC_FLOOR.'|'.
        self::FUNC_LN.'|'.self::FUNC_LOG.'|'.self::FUNC_ROUND.'|'.self::FUNC_SIN.'|'.
        self::FUNC_TAN.'|'.self::FUNC_MINUS.')\+/ui' => '$1',  // remove + at the start of numbers
    ];


    // cot is not defined
    const RELATION_OPERANTS_TO_TOKEN = [
        'abs' => self::FUNC_ABS,       //  'a'
        'ceil' => self::FUNC_CEIL,     //  'b'
        'cos' => self::FUNC_COS,       //  'c'
        'exp' => self::FUNC_EXP,       //  'e'
        'floor' => self::FUNC_FLOOR,   //  'f'
        'ln' => self::FUNC_LN,         //  'l' log to base e
        'log' => self::FUNC_LOG,       //  'z' log to base 10
        'round' => self::FUNC_ROUND,   //  'r' rount to integer
        'sin' => self::FUNC_SIN,       //  's'
        'tan' => self::FUNC_TAN,       //  't'
        '-' => self::FUNC_MINUS,       //  '-'
//        'log' => 'z',
    ];
    const WRAP_FOR_SEARCH_IN_PREG = 'wrapForSearchInPreg';


    const FUNC_PLUS = self::OP_PLUS; // '+'
    const FUNC_MINUS = self::OP_MINUS; // '-'
    const FUNC_ABS = 'a'; // Abs(5.5) = 5.5 // Abs (-5.5) = 5.5
    const FUNC_CEIL = 'b'; // round to next higher integer 5,5 -> 6 // -5,5 -> -5
    const FUNC_COS = 'c';
    const FUNC_EXP = 'e';
    const FUNC_FLOOR = 'f'; // round to next lower integer 5,5 -> 5 // -5,5 -> -6
    const FUNC_ROUND = 'r'; // round to nenearest integer int(5.5) = 6; int(5) = 5 // int (-5.5) = -6; int (-5.4) = -5
    const FUNC_LN = 'l';
    const FUNC_LOG = 'z';
    const FUNC_SIN = 's';
    const FUNC_TAN = 't';

    const OP_MAX = '↑'; // alt-24
    const OP_MIN = '↓'; // alt-25
    const OP_PLUS = '+';
    const OP_MINUS = '-';
    const OP_MULTIPLICATION = '*';
    const OP_DIVISION = '/';
    const OP_FLOOR_DIVISION = '\\'; // definition allows float values // divdie to maximu integer // parere gebären, erzeugen, erfinden => das Erzeugendes
    const OP_FRACTIONAL_CUT = '#';  // Allows float values
    const OP_POWER = '^';
    const OP_PRIO_LOWEST = 1; // currently has self::OP_PRIO_MAX the lowest priority;
    const OP_PRIO_MAX = 1; // alt-24
    const OP_PRIO_MIN = 2; // alt-25
    const OP_PRIO_PLUS = 3;
    const OP_PRIO_MINUS = 3;
    const OP_PRIO_MULTIPLICATION = 4;
    const OP_PRIO_DIVISION = 4;
    const OP_PRIO_FLOOR_DIVISION = 5; // definition allows float values // divdie to maximu integer // parere gebären, erzeugen, erfinden => das Erzeugendes
    const OP_PRIO_FRACTIONAL_CUT = 6;  // Allows float values
    const OP_PRIO_POWER = 7;
    const STRING_OF_DIGITS = '0123456789';
    const STRING_POINT = '.';


    const STRING_STARTBRACKET_NORMAL = '(';
    const STRING_ENDBRACKET_NORMAL = ')';


    /**
     * incudes the $input-parameter in /slashe for the preg_replacve-Operation, which will work in preg_replace.-actions
     *
     * unittest 20170827
     *
     * @param string $item
     * @return string
     */
    public function wrapForSearchInPreg($item)
    {
        $newItem = str_replace('/', '\/', $item);
        return '/' . $newItem . '/ui';
    }

    /**
     * Checks with respect to internal registers, if the end of the tokenstring is arrived
     *
     * unittest 20170828
     *
     * @return bool
     */
    public function testEndOfCalculationArrived()
    {
        return ($this->calcByteLength <= $this->pointNext);
    }

    /**
     * detect for various operators diffrent priority-classes
     *
     * unittest
     *
     * @param string $operator
     * @return int
     */
    public function definePriorityOperator($operator)
    {
        $priority = 0;
        switch ($operator) {
            case self::OP_MAX :
                $priority = self::OP_PRIO_MAX;
                break;
            case self::OP_MIN :
                $priority = self::OP_PRIO_MIN;
                break;
            case self::OP_PLUS :
                $priority = self::OP_PRIO_PLUS;
                break;
            case self::OP_MINUS :
                $priority = self::OP_PRIO_MINUS;
                break;

            case self::OP_MULTIPLICATION :
                $priority = self::OP_PRIO_MULTIPLICATION;
                break;

            case self::OP_DIVISION :
                $priority = self::OP_PRIO_FLOOR_DIVISION;
                break;

            case self::OP_FRACTIONAL_CUT :
                $priority = self::OP_PRIO_FRACTIONAL_CUT;
                break;

            case self::OP_FLOOR_DIVISION :
                $priority = self::OP_PRIO_FLOOR_DIVISION;
                break;

            case self::OP_POWER :
                $priority = self::OP_PRIO_POWER;
                break;

            default :
                $this->flagOkay = $this->flagOkay && false;
                break;
        }
        return $priority;
    }


    /**
     * check, if the current character is a ending bracket
     *
     * unittest 20170828
     * test constant + // test with reflected results +
     *
     * @return bool
     */
    public function testForEndBracket()
    {
        return self::STRING_ENDBRACKET_NORMAL === $this->pointByte;
    }

    /**
     * check, if the current character is a ending bracket
     *
     * unittest 20170831
     * test of internal settings
     *
     * @return bool
     */
    public function testForFlagOkay()
    {
        return ($this->flagOkay === true);
    }

    /**
     * remove whitespaces and rebuild the function to tokens
     *
     * unittest 20170828
     * + test for Whitespace // + fixate constant in length and content // + test for token // + test for shotcut
     *
     * @param string $calculation
     * @return string
     */
    public function calculatePrepare($calculation = '')
    {
        $toNormalize = $calculation;
        // shortcuts like % e °
        $arrayFunctions =
            array_keys(self::PREDEFINED_ARITHMETIC_SHORTCUTS);
        $arrayTokens = array_values(
            self::PREDEFINED_ARITHMETIC_SHORTCUTS
        );
        $toNormalize = preg_replace($arrayFunctions, $arrayTokens, $toNormalize);
        // functions like sin, cos, ln
        $arrayFunctions = array_map(
            __CLASS__ . '::' . self::WRAP_FOR_SEARCH_IN_PREG,
            array_keys(self::RELATION_OPERANTS_TO_TOKEN)
        );
        $arrayTokens = array_values(
            self::RELATION_OPERANTS_TO_TOKEN
        );
        $toNormalize = preg_replace($arrayFunctions, $arrayTokens, $toNormalize);
        // unused whitespaces(bytes) like ' ', \n \t \r ...
        $toNormalize = preg_replace('/\s/ui', '', $toNormalize);

        return $toNormalize;
    }

    /**
     * prepare the internl representation for the next calulation regardless to prior calculation or resets
     *
     * unittest
     * old 20170828
     * + test of internal start-values // + test of array-index without hole from 0 to ($this->calcByteLength-1)
     *
     * @param string $normalized
     */
    public function resetForCalculation($normalized = '')
    {
        $this->pointNext = 0;
        $this->pointByte = '';
        $this->errorNear = '';
        $this->calcByteArray = array_merge(
            array_filter(
                str_split($normalized),
                'strlen'
            )
        );
        $this->calcByteLength = count($this->calcByteArray);
        $this->flagOkay = ($this->calcByteLength > 0);
        if ($this->flagOkay) {
            $this->pointByte = $this->calcByteArray[$this->pointNext];
        }
    }


    /**
     * define the next step in the internal calculation-token-array
     *
     * unittest 20170828
     * + test of internal start-values //
     *
     */
    public function nextToken()
    {
        $this->pointNext++;
        $this->flagOkay = $this->flagOkay && ($this->pointNext < $this->calcByteLength);
        if ($this->flagOkay) {
            $this->pointByte = $this->calcByteArray[$this->pointNext];
        } else {
            $this->pointByte = '';
        }
    }

    /**
     * define the next step in the internal calculation-token-array
     *
     * unittest 20170831
     * + test of internal start-values //
     *
     */
    public function prevToken()
    {
        $this->flagOkay = $this->flagOkay && ($this->pointNext > 0);
        if ($this->flagOkay) {
            $this->pointNext--;
            $this->pointByte = $this->calcByteArray[$this->pointNext];
        } else {
            $this->pointByte = '';
        }
    }


    /**
     * The numberarray should contain a list of allowed Unitary operators lin sin, cos, tan, -, ln, ... and after that a list of numbers
     * bracktets are noch avaiablbe
     * values near the undefinded parts of ln,log and tan will cause an stop of calculation
     *
     * unittest 20170829
     * + test of normal number-detection and theri failure // + check function-call (unitary operator-Call)
     *
     * @param array $numberArray
     * @return float|int
     */
    public function analyseNumber($numberArray = [])
    {
        $result = 0;
        if (count($numberArray) > 0) {
            $start = array_shift($numberArray);
            if ((is_string($start)) &&
                (strlen($start) === 1) &&
                (strpos('0123456789.', $start) !== false)
            ) {
                $value = $start . implode($numberArray);
                $checker = is_numeric($value) &&
                    (strlen($value) === (count($numberArray) + 1));
                $this->flagOkay = ($this->flagOkay && $checker);
                $checkNumber = ($this->flagOkay ||
                    (($this->pointNext >= $this->calcByteLength) && $checker)
                );
                $result = (($checkNumber) ?
                    (float)$value :
                    0);
            } elseif (
            (
                (is_float($start)) ||
                (is_int($start)) ||
                strlen($start) > 1
            )
            ) {
                if ((count($numberArray) === 0)) {
                    $result = (float)$start;
                } else {
                    $result = 0;
                    $this->flagOkay = $this->flagOkay && false;
                }
            } else {
                switch (strtolower($start)) {
                    case self::FUNC_PLUS :
                        $result = +$this->analyseNumber($numberArray);
                        break;
                    case self::FUNC_MINUS :
                        $result = -$this->analyseNumber($numberArray);
                        break;
                    case self::FUNC_ABS :
                        $result = abs(
                            $this->analyseNumber($numberArray)
                        );
                        break;

                    case self::FUNC_CEIL :
                        $result = ceil(
                            $this->analyseNumber($numberArray)
                        );
                        break;

                    case self::FUNC_COS :
                        $result = cos(
                            $this->analyseNumber($numberArray)
                        );
                        break;

                    case self::FUNC_EXP :
                        $result = exp(
                            $this->analyseNumber($numberArray)
                        );
                        break;

                    case self::FUNC_FLOOR :
                        $result = floor(
                            $this->analyseNumber($numberArray)
                        );
                        break;

                    case self::FUNC_LN :
                        $number = $this->analyseNumber($numberArray);
                        if ($number > 0) {
                            $result = log($number);
                        } else {
                            $result = 0;
                            $this->flagOkay = $this->flagOkay && false;
                        }
                        break;

                    case self::FUNC_LOG :
                        $number = $this->analyseNumber($numberArray);
                        if ($number > 0) {
                            $result = log10($number);
                        } else {
                            $result = 0;
                            $this->flagOkay = $this->flagOkay && false;
                        }
                        break;

                    case self::FUNC_ROUND :
                        $result = round(
                            $this->analyseNumber($numberArray)
                        );
                        break;

                    case self::FUNC_SIN :
                        $result = sin(
                            $this->analyseNumber($numberArray)
                        );
                        break;

                    case self::FUNC_TAN :

                        $number = $this->analyseNumber($numberArray);
                        $checkModuloToPi = abs(0.5 - abs(
                                $number / (pi()) - round($number / pi())
                            )
                        );
                        if ($checkModuloToPi > self::PRECISION_DELTA_EXPSILON) {
                            $result = tan($number);
                        } else {
                            $result = 0;
                            $this->flagOkay = $this->flagOkay && false;
                        }
                        break;

                    default :
                        $this->flagOkay = $this->flagOkay && false;
                        break;
                }
            }
        }
        return $result;
    }

    /**
     * checks, if the current token is defined in the operator-list and return it, if it is defined
     *
     * unittest 20170829
     *
     * @return string
     */
    public function detectOperator()
    {
        $result = '';
        // check if the operator-token exists
        $this->flagOkay = $this->flagOkay &&
            (isset(self::RELATION_OPERATORS_TO_PRIORITY[$this->pointByte]));
        if ($this->flagOkay &&
            ($this->pointNext < $this->calcByteLength)
        ) {
            $result = $this->pointByte;
            $this->nextToken();
        }
        return $result;
    }

    /**
     * This method checks, if at the current position in the calculationstring exists an  opening bracket.
     *If it exist, den local viewpointer ist shift to the next tokebn
     *
     * unittest 20170829
     * + check result and nextToken-call
     *
     * @return bool
     */
    public function testForStartBracket()
    {
        $flag = false;
        if (self::STRING_STARTBRACKET_NORMAL === $this->pointByte) {

            $flag = true;
            $this->nextToken();
        }
        return $flag;
    }


    /**
     * the closing bracket must be at this place - otherwise the internal flag will removed to false
     *
     * unittest 20170829
     *
     */
    public function detectEndBracket()
    {
        $this->flagOkay = $this->flagOkay &&
            (self::STRING_ENDBRACKET_NORMAL === $this->pointByte);
        if ($this->flagOkay) {
            $this->nextToken();
            $this->flagOkay = true;
        }
    }


    /**
     * calculatie a new number from a numberpair and its operator
     *
     * unittest 20170829
     * + individuel pars-pro-toto-tests for each operator
     *
     * @param float|int $first
     * @param string $operator
     * @param float|int $second
     * @return float|int
     */
    public function calculatePair($first, $operator, $second)
    {
        $result = 0;
        switch ($operator) {
            case self::OP_MIN :
                $result = (($first <= $second) ? $first : $second);
                break;
            case self::OP_MAX :
                $result = (($first >= $second) ? $first : $second);
                break;
            case self::OP_PLUS :
                $result = $first + $second;
                break;
            case self::OP_MINUS :
                $result = $first - $second;
                break;
            case self::OP_MULTIPLICATION :
                $result = $first * $second;
                break;
            case self::OP_DIVISION :
                if (
                    ($second > self::PRECISION_DELTA_EXPSILON) ||
                    ($second < -self::PRECISION_DELTA_EXPSILON)
                ) {
                    $result = $first / $second;
                } else {
                    $this->flagOkay = $this->flagOkay && false;
                    $result = 0;
                }
                break;
            case self::OP_FRACTIONAL_CUT :
                if (
                    ($second > self::PRECISION_DELTA_EXPSILON) ||
                    ($second < -self::PRECISION_DELTA_EXPSILON)
                ) {
                    $result = ($first / $second -
                            floor($first / $second)) * $second;
                } else {
                    $this->flagOkay = $this->flagOkay && false;
                    $result = 0;
                }
                break;
            case self::OP_POWER :
                if ($first >= 0) {

                    $result = pow($first, $second);
                } else {
                    $result = 0;
                    $this->flagOkay = $this->flagOkay && false;
                }
                break;
            case self::OP_FLOOR_DIVISION :
                if (
                    ($second > self::PRECISION_DELTA_EXPSILON) ||
                    ($second < -self::PRECISION_DELTA_EXPSILON)
                ) {
                    $result = floor($first / $second);
                    $result = (($result < 0) ?
                        $result++ :
                        $result
                    );
                } else {
                    $this->flagOkay = $this->flagOkay && false;
                    $result = 0;
                }
                break;
            default :
                $this->flagOkay = $this->flagOkay && false;
                $result = 0;
                break;
        }
        return $result;
    }


    /**
     * This methods comes every time after an operator. It search first for following unitary operator.
     * Then it look for the numbers or for brackets, where a term will define a number. (rekursive call of term-analyser.
     *
     * unittest 20170830
     * testscenarios with various number // fails with double dot
     *
     * @return array
     */
    public function detectOperantiveNumber()
    {
        $result = [];
        $this->flagOkay = $this->flagOkay && ($this->pointNext < $this->calcByteLength);
        while ($this->flagOkay &&
            (array_search($this->pointByte, self::RELATION_OPERANTS_TO_TOKEN) !== false)
        ) {
            $result[] = $this->pointByte;
            $this->nextToken();
        }
        if ($this->flagOkay) {
            $testDigit = $this->pointByte;
            if ($this->testForStartBracket() === true) {
                // Test (5+3)....
                $result[] = $this->calculateOperator(self::OP_PRIO_LOWEST );
                $this->detectEndBracket();
            } else {
                // throw an error if the following part did not contain one or more degits following sequenze
                $this->flagOkay = $this->flagOkay &&
                    ($testDigit !== '') &&
                    (strpos(self::STRING_OF_DIGITS_AND_POINT, $testDigit) !== false);
                $this->flagOkay = $this->flagOkay && (
                        (
                            ($testDigit !== self::STRING_POINT) &&
                            (strpos(self::STRING_OF_DIGITS, $testDigit) !== false)
                        ) || (
                            ($testDigit === self::STRING_POINT) &&
                            (isset($this->calcByteArray[($this->pointNext + 1)])) &&
                            (strpos(self::STRING_OF_DIGITS, $this->calcByteArray[($this->pointNext + 1)]) !== false)
                        )
                    ); // at least one or more degits in the following digit-sequenze
                $countPoint = 0;
                while ($this->flagOkay &&
                    (strpos(self::STRING_OF_DIGITS_AND_POINT, $this->pointByte) !== false) && // $this->$this->pointByte, self::RELATION_OPERANTS_TO_TOKEN)  !== false)
                    ($this->pointNext < $this->calcByteLength)
                ) {
                    $result[] = $this->pointByte;
                    if ($this->calcByteArray[$this->pointNext] === self::STRING_POINT) {
                        $countPoint++;
                        $this->flagOkay = $this->flagOkay && ($countPoint < 2); // at least only one point in the digit-sequenze
                    }
                    $this->nextToken();
                    if (!$this->flagOkay) {
                        $this->flagOkay = ($this->pointNext >= $this->calcByteLength);
                        break;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * the firstnumber in a term can defined
     *      a) by lazy-recursion (because the next oparator has a higher priority than the operator above) or
     *      b) by a term defined by brackets or
     *      c) by a squenzs of unitary operator-token and a number which is defined by digits oder bei a term in Brackets
     *
     * unittest 20170830
     * + check for Call of defineSecondNumberAfterOperatorInTerm // + check given $firstValue
     *
     * @param null|float|int $firstValue
     * @return float|int
     */
    public function defineFirstNumberInTerm($firstValue = null)
    {
        $firstNumber = (int)$firstValue;
        if ($firstValue === null) {
            $firstNumber = $this->defineSecondNumberAfterOperatorInTerm();
        } else {
            // first value 5 in second method-call because of back-influence of operator-priority in example 17+5*3
            $firstNumber = $firstValue;
        }
        return $firstNumber;
    }

    /**
     * the methode detect the seconde number, which can defined
     *      a) by a Term in brackets or
     *      b) by a sequenze of unitary operators and a number, wich ist diefined by digits or an bracket-term
     *
     * unittest 20170830
     * + check the correkt detection
     *
     * @return float|int
     */
    public function defineSecondNumberAfterOperatorInTerm()
    {
        $secondNumber = 0;
        if ($this->testForStartBracket() === true) {
            $secondNumber = $this->calculateOperator(self::OP_PRIO_LOWEST );
            $this->detectEndBracket();
        } else {
            $secondOperantiveNumberArray = $this->detectOperantiveNumber();
            $secondNumber = $this->analyseNumber($secondOperantiveNumberArray);
        }
        return $secondNumber;
    }

    /**
     * The method calculate the pair,
     * 1. if the end of the term is reached or
     * 2. if the ending bracket is reached or
     * 3. if the priority of the following operator ist lower or equal to the priority of the current operator.
     * 4. Exceptional reaction: If the priority of the following operator is higher the current oüperator, a lazy termrecursion is induced.
     * The result of the lazy termrecursion will calculated with the current first valuet The method call the method
     *
     * unittest 20170830
     * + mochs for the diffeerent situation // [only structurell-tests - should fully logical valid, if the tests are fully valid to the problem]
     *
     * @param int|float $firstNumber
     * @param string $operator
     * @param int $priority,
     * @param int|float $secondNumber
     *
     * @return float|int
     */
    public function calculatePairAndInitPerhapsLazyRecursion($firstNumber, $operator, $priority, $secondNumber)
    {
        if (
            ($this->testEndOfCalculationArrived()) ||
            ($this->testForEndBracket())
        ) {
            $firstNumber = $this->calculatePair($firstNumber, $operator, $secondNumber);
        } else { // next sign exists and is not a endbracket => it must an operator
            // the following sign is an operator and exist definitivly
            $testPriority = $this->definePriorityOperator($this->pointByte);
            if ($testPriority === $priority) {
                $firstNumber = $this->calculatePair($firstNumber, $operator, $secondNumber);
            } elseif ($testPriority > $priority) {
                // lazyRecursion because of opartator with higher priority
                $secondNumber = $this->calculateOperator($priority+1,$secondNumber);
                $firstNumber = $this->calculatePair($firstNumber, $operator, $secondNumber);
            } elseif ($testPriority < $priority) {
                $firstNumber = $this->calculatePair($firstNumber, $operator, $secondNumber);
            }
        }
        return $firstNumber;
    }


    /**
     * it detects the first number in the arra and then the operator and the second nummber.
     * the current method operate only on a definite level of priority
     * If the second number at its end is connected to an operator with higher priority, the second number must become part of an
     * lazy recursion, before the result between firstnumber and seondnumber can be calculated
     * if the operator after the first number ist higher than the
     *
     * if the internal falgOkay is set to false, the result is every time 0
     * if while loop will stop, if the courrent sign is an closing bracket or if the end of the internal sequenze is arrived,
     *
     * unittest
     *
     * @param int $priority
     * @param null|float|int $firstValue
     * @return float|int
     */
    public function calculateOperator($priority = self::OP_PRIO_LOWEST , $firstValue = null )
    {
        $result = 0;
        if ($this->testForFlagOkay()) {
            // if $firstValue is set, the method defineFirstNumberInTerm will return it as first number without doing anything else
            $firstNumber = $this->defineFirstNumberInTerm($firstValue);

            while (($this->testForFlagOkay()) &&
                (!$this->testEndOfCalculationArrived()) &&
                (!$this->testForEndBracket())
            ) {
                $operator = $this->detectOperator();
                $newPriority = $this->definePriorityOperator($operator);
                // allow the correct recursiv structure for calculations like 3+2*2+4=3
                if ($newPriority < $priority) {
                    $this->prevToken();
                    break;
                } elseif ($newPriority > $priority) {
                    $this->prevToken();
                    $firstNumber  = $this->calculateOperator($priority+1, $firstNumber);
                } else {
                    if ($this->testForFlagOkay()) {
                        $secondNumber = $this->defineSecondNumberAfterOperatorInTerm();
                        // problem example 17+5*3 is there a 'lazy-recursion'
                        $firstNumber = $this->calculatePairAndInitPerhapsLazyRecursion(
                            $firstNumber,
                            $operator,
                            $priority,
                            $secondNumber
                        );
                    } else {
                        $firstNumber = 0;
                        break;
                    }
                }
            }
            $result = $firstNumber;
        }
        return $result;
    }


    /**
     *
     * unittest
     *
     * @return string
     */
    public function getErrorNear()
    {
        return $this->errorNear;
    }


    public function generateErrorMessage()
    {
        for ($i = 0; $i < $this->calcByteLength; $i++) {
            if (strpos('0123456789.()+-/*#\\',$this->calcByteArray[$i]) === false) {
                foreach (self::RELATION_OPERANTS_TO_TOKEN as $func => $token) {
                    if ($this->calcByteArray[$i] === $token) {
                        $this->calcByteArray[$i] = ' ' . $func;
                    }
                }
            }
        }
        $myArray = str_replace(
            ['(', ')','#','\\'],
            ["\n<br />( ", " )\n<br />", ' mod ', ' div '],
            $this->calcByteArray
        );
        if ($this->pointNext >= $this->calcByteLength) {
            $this->calcByteArray[] = ' '.self::INTERNAL_MARKER_FOR_ERROR .' ';
        } else {
            $this->calcByteArray[$this->pointNext] = $this->calcByteArray[$this->pointNext] . ' '.self::INTERNAL_MARKER_FOR_ERROR .' ';
        };
        $nearErrorRawString = implode('',$this->calcByteArray);

        $nearErrorNormalized = str_replace(' '.self::INTERNAL_MARKER_FOR_ERROR .' ','',$nearErrorRawString);

        $nearError = explode(self::INTERNAL_MARKER_FOR_ERROR , $nearErrorRawString );
        $firstPart = substr($nearError[0],-10);
        $secondPart = substr($nearError[1],0, 10);
        $nearPart = trim(
            ($firstPart ? $firstPart : $nearError[0]) .
            ' | '.
            ($secondPart ? $secondPart : $nearError[1])
        );

        $base = '<strong>The error is near this place ´' . $nearPart. '`. ' . "</strong>\n<br />";
        $base .= 'Your calculationstring has the following normalized form: ' . "\n<br />" .$nearErrorNormalized . "\n<br />" ;
        $base .= '<em>A possible reason is perhaps a missing bracket. Others reason could be unknown operators or an unallowed cascade of operators. 
        Although the usage of an floating comma can cause an error. You have to use a dot for the floatingpoint. And one number contain at least only one floating-point.</em>';
        $this->errorNear = $base;
    }

    /**
     * formate a string to an byte-Array an call the recursive analysing-function
     *
     * unittest 20170830
     *
     * @param string $calculation
     * @param bool $error
     * @return bool|float|int
     */
    public function calculate($calculation = '', $error = false)
    {
        $result = 0;
        $normalized = $this->calculatePrepare($calculation);
        $this->resetForCalculation($normalized);
        if ($this->flagOkay) {
            $result = $this->calculateOperator(self::OP_PRIO_LOWEST );
        }
        if (($this->flagOkay) &&
            ($this->pointNext >= $this->calcByteLength)) {
            $result = floatval($result);
        } else {
            if ($error) {
                $this->generateErrorMessage();
            }
            $result = false;
        }
        return $result;
    }

}
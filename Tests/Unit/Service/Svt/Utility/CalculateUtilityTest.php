<?php
/**
 * Created by PhpStorm.
 * User: dporth
 * Date: 20.08.2017
 * Time: 11:25
 */

namespace Porth\HornyShit\Service\Svt\Utility;

use PHPUnit\Framework\TestCase;

class CalculateUtilityTest extends TestCase
{


    /**
     * @var CalculateUtility
     */
    protected $subject = NULL;

    public function setUp()
    {
        $this->subject = new CalculateUtility();
    }

    public function tearDown()
    {
        unset($this->subject);
    }


    public function dataProviderVariateItemsForWrapForSearchInPregReturnPregSearchStringforGivenItemString()
    {
        return [
            [
                '1. a empty Item will cause an entry in every space and an antry at the start and end ',
                [
                    'wrapResult' => '//ui',
                    'pregResult' => 'xhxaxlxlxox',
                ],
                [
                    'item' => '',
                    'replace' => 'x',
                    'test' => 'hallo',
                ]
            ],
            [
                '2. a Item with a single char, which is part of teststring',
                [
                    'wrapResult' => '/a/ui',
                    'pregResult' => 'hxllo',
                ],
                [
                    'item' => 'a',
                    'replace' => 'x',
                    'test' => 'hallo',
                ]
            ],
            [
                '3. a Item with u single char, which is not part of teststring',
                [
                    'wrapResult' => '/u/ui',
                    'pregResult' => 'hallo',
                ],
                [
                    'item' => 'u',
                    'replace' => 'x',
                    'test' => 'hallo',
                ]
            ],
            [
                '4. a Item with a three chars, which is part of teststring',
                [
                    'wrapResult' => '/all/ui',
                    'pregResult' => 'hxo',
                ],
                [
                    'item' => 'all',
                    'replace' => 'x',
                    'test' => 'hallo',
                ]
            ],
            [
                '4. a Item with a three case-different chars, which is part of teststring',
                [
                    'wrapResult' => '/aLl/ui',
                    'pregResult' => 'hxo',
                ],
                [
                    'item' => 'aLl',
                    'replace' => 'x',
                    'test' => 'hallo',
                ]
            ],
            [
                '5. b Item with two chars, which is not part of teststring',
                [
                    'wrapResult' => '/ull/ui',
                    'pregResult' => 'hallo',
                ],
                [
                    'item' => 'ull',
                    'replace' => 'x',
                    'test' => 'hallo',
                ]
            ],
        ];

    }

    /**
     *
     * @dataProvider dataProviderVariateItemsForWrapForSearchInPregReturnPregSearchStringforGivenItemString
     * @test
     */
    public function wrapForSearchInPregReturnPregSearchStringforGivenItemString($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {

            $result = $this->subject->wrapForSearchInPreg($params['item']);
            $preg = preg_replace($result, $params['replace'], $params['test']);

            $this->assertSame(
                $expects['wrapResult'],
                $result,
                'test wrap: ' . $message
            );
            $this->assertSame(
                $expects['pregResult'],
                $preg,
                'test preg: ' . $message
            );
        }
    }


    public function datatProviderCalculatePrepareReturnStringWithoutWhitespaceGivenStringsWithWithespace()
    {
        return [
            [
                '1.a. an empty string',
                [
                    'result' => '',
                ],
                [
                    'test' => '',
                ]
            ],
            [
                '1.b. an string only with spaces ',
                [
                    'result' => '',
                ],
                [
                    'test' => "        ",
                ]
            ],
            [
                '1.c. an string only with spaces, tabs and return ',
                [
                    'result' => '',
                ],
                [
                    'test' => " \t\n\r  ",
                ]
            ],
            [
                '2.a. a string without wihitespaces',
                [
                    'result' => 'hallo(Ohne))Whitespace',
                ],
                [
                    'test' => 'hallo(Ohne))Whitespace',
                ]
            ],
            [
                '2.b. a string with spaces',
                [
                    'result' => 'hallo(Ohne))Whitespace',
                ],
                [
                    'test' => 'hallo (Ohne) ) Whitespace',
                ]
            ],
            [
                '2.c. a string with various types of whitespace',
                [
                    'result' => 'hallo(Ohne))Whitespace',
                ],
                [
                    'test' => "hallo\t(Ohne) \n)\r Whitespace",
                ]
            ],
        ];

    }

    /**
     * @dataProvider datatProviderCalculatePrepareReturnStringWithoutWhitespaceGivenStringsWithWithespace
     * @test
     */
    public function calculatePrepareReturnStringWithoutWhitespaceGivenString($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {
            $result = $this->subject->calculatePrepare($params['test']);
            $this->assertSame(
                $expects['result'],
                $result,
                $message
            );
        }
    }

    /**
     * @test
     */
    public function calculatePrepareUsedADefindeListOfTokenFunctionArray()
    {
        $result = $this->subject::RELATION_OPERANTS_TO_TOKEN;
        $keys = array_keys($this->subject::RELATION_OPERANTS_TO_TOKEN);
        $values = array_values($this->subject::RELATION_OPERANTS_TO_TOKEN);
        $maxListCount = 10;

        $this->assertSame(
            11,
            count($result),
            '1. The constant is changed. It`s a hit, that the class may not workl like expected. (10 plus 1 [Minus-operator] plus 1 (plus-operator)) '
        );
        $maxIndividualToken = 11;
        $tokenList = ',' .
            ',' . $this->subject::FUNC_ABS . ',' .
            ',' . $this->subject::FUNC_CEIL . ',' .
            ',' . $this->subject::FUNC_COS . ',' .
            ',' . $this->subject::FUNC_EXP . ',' .
            ',' . $this->subject::FUNC_FLOOR . ',' .
            ',' . $this->subject::FUNC_LN . ',' .
            ',' . $this->subject::FUNC_LOG . ',' .
            ',' . $this->subject::FUNC_ROUND . ',' .
            ',' . $this->subject::FUNC_SIN . ',' .
            ',' . $this->subject::FUNC_TAN . ',' .
            ',' . $this->subject::FUNC_MINUS . ',' .
            ' ,';
        $tokenArray = array_filter(
            array_unique(
                array_map(
                    'trim',
                    explode(',', $tokenList)
                )
            )
        );
        $this->assertSame(
            $maxIndividualToken,
            count($tokenArray),
            '2. The counts of token are defined. A failure indicates, that the structure of the class has changed.'
        );
        $this->assertGreaterThanOrEqual(
            $maxListCount,
            $maxIndividualToken,
            '3.The counts of tokens are equal or smaler than the number of defined function-keys and alias-functionnames.'
        );
        $count = 0;
        foreach ($this->subject::RELATION_OPERANTS_TO_TOKEN as $funcNameAsKey => $funcNameAsTokenChar) {
            $this->assertGreaterThan(
                0,
                strpos(',,abs,ceil,cos,exp,floor,ln,log,round,sin,tan,-,+', ',' . $funcNameAsKey . ','),
                '4.' . $count . '. The function name is not defined in the constant. `'.$funcNameAsKey .'`'
            );
            $this->assertGreaterThan(
                0,
                strpos($tokenList, ',' . $funcNameAsTokenChar . ','),
                '5.' . $count . '. The function token is not defined in the constants-list. '
            );
            $count++;
        }
    }

    public function datatProviderCalculatePrepareReturnStringWithTokenGivenStringWIthRandmUsedFunctions()
    {
        return [
            [
                '1. an empty string',
                [
                    'result' => '',
                ],
                [
                    'test' => '',
                ]
            ],
            [
                '2.a. A string, which contains one function and a number and spaces.',
                [
                    'result' => 's23',
                ],
                [
                    'test' => 'sin 23',
                ]
            ],
            [
                '2.b. A string, which contains one function and a number.',
                [
                    'result' => 's23',
                ],
                [
                    'test' => 'sin23',
                ]
            ],
            [
                '3.a. A string, which contains a list of functions and a number.',
                [
                    'result' => 'aabs23',
                ],
                [
                    'test' => 'abs abs ceil sin 23',
                ]
            ],
            [
                '3.b. A string, which contains a list of functions and a number and spaces.',
                [
                    'result' => 'aabs23',
                ],
                [
                    'test' => 'absabsceilsin23',
                ]
            ],
            [
                '3.a. A string, which contains a list of functions and a number and brackets.',
                [
                    'result' => 'a(a(b(s(23))))',
                ],
                [
                    'test' => 'abs(abs(+ceil(sin(23))))',
                ]
            ],
            [
                '3.b. A string, which contains a list of functions and a number and brackets and spaces.',
                [
                    'result' => 'a(a(b(s(23))))',
                ],
                [
                    'test' => 'abs(abs(ceil(sin(23))))',
                ]
            ],
            [
                '4.a. A string, which contains the function abs and a number.',
                [
                    'result' => 'a23',
                ],
                [
                    'test' => 'abs 23',
                ]
            ],
            [
                '4.b. A string, which contains the function ceil and a number.',
                [
                    'result' => 'b23',
                ],
                [
                    'test' => 'ceil 23',
                ]
            ],
            [
                '4.c. A string, which contains the function cos and a number.',
                [
                    'result' => 'c23',
                ],
                [
                    'test' => 'cos 23',
                ]
            ],
            [
                '4.d. A string, which contains the function exp and a number.',
                [
                    'result' => 'e23',
                ],
                [
                    'test' => 'exp 23',
                ]
            ],
            [
                '4.e. A string, which contains the function ln and a number.',
                [
                    'result' => 'l23',
                ],
                [
                    'test' => 'ln 23',
                ]
            ],
            [
                '4.f. A string, which contains the function log and a number.',
                [
                    'result' => 'z23',
                ],
                [
                    'test' => 'log 23',
                ]
            ],
            [
                '4.g. A string, which contains the function round and a number.',
                [
                    'result' => 'r23',
                ],
                [
                    'test' => 'round 23',
                ]
            ],
            [
                '4.h. A string, which contains the function floor and a number.',
                [
                    'result' => 'f23',
                ],
                [
                    'test' => 'floor 23',
                ]
            ],
            [
                '4.i. A string, which contains the function sin and a number.',
                [
                    'result' => 's23',
                ],
                [
                    'test' => 'sin 23',
                ]
            ],
            [
                '4.j. A string, which contains the function tan and a number.',
                [
                    'result' => 't23',
                ],
                [
                    'test' => 'tan 23',
                ]
            ],
            [
                '5. A string, which contains the operator mod .',
                [
                    'result' => '4#7',
                ],
                [
                    'test' => '4mod7',
                ]
            ],
            [
                '5.b A string, which contains the operator mod with one uppercase .',
                [
                    'result' => '4#7',
                ],
                [
                    'test' => '4Mod7',
                ]
            ],
            [
                '6. A string, which contains the operator div .',
                [
                    'result' => '23\\23',
                ],
                [
                    'test' => '23 div 23',
                ]
            ],
            [
                '6. A string, which contains the operator div with one uppercase .',
                [
                    'result' => '23\\23',
                ],
                [
                    'test' => '23 diV 23',
                ]
            ],

        ];

    }

    /**
     * @dataProvider datatProviderCalculatePrepareReturnStringWithTokenGivenStringWIthRandmUsedFunctions
     * @test
     */
    public function calculatePrepareReturnStringWithTokenGivenString($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {
            $result = $this->subject->calculatePrepare($params['test']);
            $this->assertSame(
                $expects['result'],
                $result,
                $message
            );
        }
    }


    public function datatProviderCalculatePrepareReturnStringWithRemovedShortcutsGivenStringInDifferentVariations()
    {
        return [
            [
                '1. an empty string',
                [
                    'result' => '',
                ],
                [
                    'test' => '',
                ]
            ],
            [
                '2.a. A string, which contains the scientific e-Function without any additional signs.',
                [
                    'result' => '(23*10^23)',
                ],
                [
                    'test' => '23e23',
                ]
            ],
            [
                '2.b. A string, which contains the scientific e-Function without any additional signs in uppercase.',
                [
                    'result' => '(23*10^23)',
                ],
                [
                    'test' => '23E23',
                ]
            ],
            [
                '2.c. A string, which contains the scientific e-Function without any additional signs with plus.',
                [
                    'result' => '(23*10^23)',
                ],
                [
                    'test' => '23e+23',
                ]
            ],
            [
                '2.d. A string, which contains the scientific e-Function without any additional signs in uppercase with plus.',
                [
                    'result' => '(23*10^23)',
                ],
                [
                    'test' => '23E+23',
                ]
            ],
            [
                '2.e. A string, which contains the scientific e-Function without any additional signs with minus.',
                [
                    'result' => '(23*10^-23)',
                ],
                [
                    'test' => '23e-23',
                ]
            ],
            [
                '2.f. A string, which contains the scientific e-Function without any additional signs in uppercase with minus.',
                [
                    'result' => '(23*10^-23)',
                ],
                [
                    'test' => '23E-23',
                ]
            ],
            [
                '3.a. A string, which contains the scientific e-Function without any additional signs and a floting-number.',
                [
                    'result' => '(2.456*10^23)',
                ],
                [
                    'test' => '2.456e23',
                ]
            ],
            [
                '3.b. A string, which contains the scientific e-Function without any additional signs in uppercase and a floting-number.',
                [
                    'result' => '(2.456*10^23)',
                ],
                [
                    'test' => '2.456E23',
                ]
            ],
            [
                '3.c. A string, which contains the scientific e-Function without any additional signs with plus and a floting-number.',
                [
                    'result' => '(2.456*10^23)',
                ],
                [
                    'test' => '2.456e+23',
                ]
            ],
            [
                '3.d. A string, which contains the scientific e-Function without any additional signs in uppercase with plus and a floting-number.',
                [
                    'result' => '(2.456*10^23)',
                ],
                [
                    'test' => '2.456E+23',
                ]
            ],
            [
                '3.e. A string, which contains the scientific e-Function without any additional signs with minus and a floting-number.',
                [
                    'result' => '(2.456*10^-23)',
                ],
                [
                    'test' => '2.456e-23',
                ]
            ],
            [
                '3.f. A string, which contains the scientific e-Function without any additional signs in uppercase with minus and a floting-number.',
                [
                    'result' => '(2.456*10^-23)',
                ],
                [
                    'test' => '2.456E-23',
                ]
            ],
            [
                '4. A string, which contains the shortcut `%`.',
                [
                    'result' => '(2.456/100)',
                ],
                [
                    'test' => '2.456%',
                ]
            ],
            [
                '5. A string, which contains the shortcut `°`.',
                [
                    'result' => '(2.456*'.M_PI.'/180)',
                ],
                [
                    'test' => '2.456°',
                ]
            ],

        ];

    }

    /**
     * @dataProvider datatProviderCalculatePrepareReturnStringWithRemovedShortcutsGivenStringInDifferentVariations
     * @test
     */
    public function calculatePrepareReturnStringWithRemovedShortcutsGivenString($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {
            $result = $this->subject->calculatePrepare($params['test']);
            $this->assertSame(
                $expects['result'],
                $result,
                $message
            );
        }
    }


    public function dataProviderVariateItemsForCalculate()
    {
        return [
//            [
//                '1. an empty string give false',
//                false,
//                '',
//            ],
//            [
//                '1.b a simple number',
//                '8.0',
//                ' 8.0 ',
//            ],
//            [
//                '1.c a simple float number',
//                '8.2',
//                ' 8.2 ',
//            ],
//            [
//                '1.d a simple float number in brackets',
//                '8.2',
//                ' ( 8.2 )',
//            ],
            [
                '2. a simple number',
                '8',
                ' 5 + 3',
            ],
            [
                '3. a simple number',
                '9.3',
                ' 5.4 + 3.9',
            ],
            [
                '4. a simple calculation with + and *',
                '18',
                ' 5 + 3 * 5 - 2 ',
            ],
            [
                '5. a simple calculation with + and * and brackets',
                '27',
                ' 5 + 3 * (1+7) - 2 ',
            ],
            [
                '10.a. a spacy calculation - part one ',
                1,
                ' sin(90°) ',
            ],
            [
                '10.b. a spacy calculation - part two ',
                -1,
                ' cos(180°) * ln(exp( 1))',
            ],
            [
                '10.c. a spacy calculation - part two ',
                '2',
                ' log(100) /abs(-1) ',
            ],
            [
                '10.d. a spacy calculation - part four',
                '-2',
                ' floor(-140%)',
            ],
            [
                '10.e. a spacy calculation - part five',
                '0',
                ' ceil(-0.8) * round(-0.5) ',
            ],
            [
                '10.f. a spacy calculation - part six ',
                '0',
                ' 5 \8 ',
            ],
            [
                '10.g. a spacy calculation - part seven ',
                '0',
                ' 2^10 modulo 2',
            ],
            [
                '10.h. a spacy calculation - part one to seven ',
                '0',
                ' sin(90°) + cos(180°) * ln(exp( 1))+  log(100) /abs(-1)+  2^10 modulo 2 +( floor(-140%) + ceil(-0.8) * round(-0.5) - 5 \8 ) - 2^10 modulo 2 ',
            ],

        ];
    }

    /**
     *
     * @dataProvider dataProviderVariateItemsForCalculate
     * @test
     */
    public function calculateReturnFloatGivenAnCalculationString($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {
            $result = $this->subject->calculate($params);
            $this->assertSame(
                intval($expects),
                intval($result),
                'test result on integer-level: ' . $message . ' (' . $expects . ' <-> ' . $params.  ' = ' . $result .')'
            );
            $this->assertSame(
                floatval($expects),
                floatval($result),
                'test result on float-level: ' . $message . ' (' . $expects . ' <-> ' . $params. ')'
            );
        }
    }

    public function dataProviderTestEndOfCalculationArrivedReturnBooleanGivenOfInternalSettings()
    {
        return [
            [
                '1. Test a simple pair: length = 0, pointer = 0 . The end is arrived.',
                true,
                ['pointer' => 0, 'length' => 0],
            ],
            [
                '2. Test a simple pair: length = 0, pointer = 1 . The end is behind the pointer.',
                true,
                ['pointer' => 1, 'length' => 0],
            ],
            [
                '2. Test a simple pair: length = 0, pointer = 0.  The end is not arrived.',
                false,
                ['pointer' => 0, 'length' => 1],
            ],
            [
                '3. Test a simple pair: length = 1000, pointer = 500.  The end is not arrived.',
                false,
                ['pointer' => 500, 'length' => 1000],
            ],
            [
                '4. Test a simple pair: length = 1000, pointer = 999.  The end is not arrived.',
                false,
                ['pointer' => 999, 'length' => 1000],
            ],
            [
                '5. Test a simple pair: length = 1000, pointer = 1000.  The end is arrived.',
                true,
                ['pointer' => 1000, 'length' => 1000],
            ],
            [
                '6. Test a simple pair: length = 1000, pointer = 9999.  The end is behind the pointer.',
                true,
                ['pointer' => 9999, 'length' => 1000],
            ],
        ];
    }

    /**
     *
     * @dataProvider dataProviderTestEndOfCalculationArrivedReturnBooleanGivenOfInternalSettings
     * @test
     */
    public function testEndOfCalculationArrivedReturnBooleanGivenOfInternalSettings($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {
            $calculateUtility = new CalculateUtility();
            $reflection = new \ReflectionClass($calculateUtility);
            $reflection_calcByteLength = $reflection->getProperty('calcByteLength');
            $reflection_calcByteLength->setAccessible(true);
            $reflection_calcByteLength->setValue($calculateUtility, $params['length']);
            $reflection_pointNext = $reflection->getProperty('pointNext');
            $reflection_pointNext->setAccessible(true);
            $reflection_pointNext->setValue($calculateUtility, $params['pointer']);

            $result = $calculateUtility->testEndOfCalculationArrived();

            $this->assertSame(
                $expect,
                $result,
                $message
            );
        }
    }


    /**
     *
     * @test
     */
    public function testForEndBracketReturnBooleanGivenOfInternalSettings()
    {
        $calculateUtility = new CalculateUtility();

        $this->assertSame(
            ')',
            $calculateUtility::STRING_ENDBRACKET_NORMAL,
            '1. The constant `STRING_ENDBRACKET_NORMAL` contains an `)`.'
        );

        $reflection = new \ReflectionClass($calculateUtility);
        $reflection_calcByteLength = $reflection->getProperty('pointByte');
        $reflection_calcByteLength->setAccessible(true);
        $reflection_calcByteLength->setValue($calculateUtility, 'h');

        $result = $calculateUtility->testForEndBracket();
        $this->assertSame(
            false,
            $result,
            '2. The current-value `h` is not identical to `)`.'
        );
        $reflection_calcByteLength->setValue($calculateUtility, ')');

        $result = $calculateUtility->testForEndBracket();
        $this->assertSame(
            true,
            $result,
            '3. The current reflected value `)` is identical to `)`.'
        );
    }

    /**
     *
     * @test
     */
    public function testForFlagOkayReturnBooleanGivenOfInternalSettings()
    {
        $calculateUtility = new CalculateUtility();


        $reflection = new \ReflectionClass($calculateUtility);
        $reflection_flagOkay = $reflection->getProperty('flagOkay');
        $reflection_flagOkay->setAccessible(true);
        $reflection_flagOkay->setValue($calculateUtility, false);

        $result = $calculateUtility->testForFlagOkay();
        $this->assertSame(
            false,
            $result,
            '1. The current reflected value in flagOkay `false` is identical to the result `false`.'
        );
        $reflection_flagOkay->setValue($calculateUtility, true);

        $result = $calculateUtility->testForFlagOkay();
        $this->assertSame(
            true,
            $result,
            '2. The current reflected value in flagOkay `true` is identical to the result `true`.'
        );
    }


    public function dataProviderResetForCalculationConfigureTheCorrectInternalConfiguration()
    {
        return [
            [
                '1. Insert initially a empty string to the reset-procedure.',
                [
                    'pointNext' => 0,
                    'calcByteArray' => [],
                    'calcByteLength' => 0,
                    'flagOkay' => false,
                    'pointByte' => '',
                ],
                ['start' => null, 'test' => '']
            ],
            [
                '2. Insert initially a filled string to the reset-procedure, which must not be numeric.',
                [
                    'pointNext' => 0,
                    'calcByteArray' => ['f','i','l','l','e','d'],
                    'calcByteLength' => 6,
                    'flagOkay' => true,
                    'pointByte' => 'f',
                ],
                ['start' => null, 'test' => 'filled']
            ],
            [
                '11. Insert - after reseting with an empty string - a empty string to the reset-procedure.',
                [
                    'pointNext' => 0,
                    'calcByteArray' => [],
                    'calcByteLength' => 0,
                    'flagOkay' => false,
                    'pointByte' => '',
                ],
                ['start' => '', 'test' => '']
            ],
            [
                '12. Insert - after reseting with an empty string - a filled string to the reset-procedure, which must not be numeric.',
                [
                    'pointNext' => 0,
                    'calcByteArray' => ['f','i','l','l','e','d'],
                    'calcByteLength' => 6,
                    'flagOkay' => true,
                    'pointByte' => 'f',
                ],
                ['start' => null, 'test' => 'filled']
            ],
            [
                '21. Insert - after reseting to the string `bullying´ - a empty string to the reset-procedure.',
                [
                    'pointNext' => 0,
                    'calcByteArray' => [],
                    'calcByteLength' => 0,
                    'flagOkay' => false,
                    'pointByte' => '',
                ],
                ['start' => '', 'test' => '']
            ],
            [
                '22. Insert - after reseting to the string `bullying´ - a filled string to the reset-procedure, which must not be numeric.',
                [
                    'pointNext' => 0,
                    'calcByteArray' => ['f','i','l','l','e','d'],
                    'calcByteLength' => 6,
                    'flagOkay' => true,
                    'pointByte' => 'f',
                ],
                ['start' => null, 'test' => 'filled']
            ],
        ];
    }
    /**
     * @dataProvider dataProviderResetForCalculationConfigureTheCorrectInternalConfiguration
     * @test
     */
    public function resetForCalculationConfigureTheCorrectInternalConfiguration($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {

            /** @var CalculateUtility $calculateUtility */
            $calculateUtility = new CalculateUtility();

            if ($params['start'] !== null) {
                $calculateUtility->resetForCalculation($params['start']);
            }
            $calculateUtility->resetForCalculation($params['test']);

            $reflection = new \ReflectionClass($calculateUtility);
            $reflection_pointNext = $reflection->getProperty('pointNext');
            $reflection_pointNext->setAccessible(true);
            $pointNext = $reflection_pointNext->getValue($calculateUtility);

            $reflection_pointByte = $reflection->getProperty('pointByte');
            $reflection_pointByte->setAccessible(true);
            $pointByte = $reflection_pointByte->getValue($calculateUtility);

            $reflection_calcByteArray = $reflection->getProperty('calcByteArray');
            $reflection_calcByteArray->setAccessible(true);
            $calcByteArray = $reflection_calcByteArray->getValue($calculateUtility);

            $reflection_calcByteLength = $reflection->getProperty('calcByteLength');
            $reflection_calcByteLength->setAccessible(true);
            $calcByteLength = $reflection_calcByteLength->getValue($calculateUtility);

            $reflection_flagOkay = $reflection->getProperty('flagOkay');
            $reflection_flagOkay->setAccessible(true);
            $flagOkay = $reflection_flagOkay->getValue($calculateUtility);

            $this->assertSame(
                $expects['pointNext'],
                $pointNext,
                ' Test pointNext for:'.$message
            );
            $this->assertSame(
                $expects['calcByteArray'],
                $calcByteArray,
                ' Test calcByteArray for:'.$message
            );
            $this->assertSame(
                $expects['calcByteLength'],
                $calcByteLength,
                ' Test calcByteLength for:'.$message
            );
            $this->assertSame(
                $expects['flagOkay'],
                $flagOkay,
                ' Test flagOkay for:'.$message
            );
            $this->assertSame(
                $expects['pointByte'],
                $pointByte,
                ' Test pointByte for:'.$message
            );
        }
    }

    /**
     * @test
     */
    public function resetForCalculationConfigureANArrayWithOngoingIndexing()
    {

        /** @var CalculateUtility $calculateUtility */
        $calculateUtility = new CalculateUtility();

        $testString = '012340567890';
        $expectedArray = ['0', '1', '2', '3', '4', '0', '5', '6', '7', '8', '9', '0'];
        $calculateUtility->resetForCalculation($testString);

        $message = "1. The unsage of the value '0' can cause problems in `array_filter`, because `0` is interpreted as false.";


        $reflection = new \ReflectionClass($calculateUtility);
        $reflection_pointNext = $reflection->getProperty('pointNext');
        $reflection_pointNext->setAccessible(true);
        $pointNext = $reflection_pointNext->getValue($calculateUtility);

        $reflection_pointByte = $reflection->getProperty('pointByte');
        $reflection_pointByte->setAccessible(true);
        $pointByte = $reflection_pointByte->getValue($calculateUtility);

        $reflection_calcByteArray = $reflection->getProperty('calcByteArray');
        $reflection_calcByteArray->setAccessible(true);
        $calcByteArray = $reflection_calcByteArray->getValue($calculateUtility);

        $reflection_calcByteLength = $reflection->getProperty('calcByteLength');
        $reflection_calcByteLength->setAccessible(true);
        $calcByteLength = $reflection_calcByteLength->getValue($calculateUtility);

        $reflection_flagOkay = $reflection->getProperty('flagOkay');
        $reflection_flagOkay->setAccessible(true);
        $flagOkay = $reflection_flagOkay->getValue($calculateUtility);

        $this->assertSame(
            0,
            $pointNext,
            ' Test pointNext for:' . $message
        );
        $this->assertSame(
            0,
            count(array_diff($expectedArray, $calcByteArray)),
            ' Test calcByteArray with diff (I) :' . $message
        );
        $this->assertSame(
            0,
            count(array_diff($calcByteArray, $expectedArray)),
            ' Test calcByteArray with diff (II) :' . $message
        );
        $this->assertSame(
            count($expectedArray),
            count(array_intersect($calcByteArray, $expectedArray)),
            ' Test calcByteArray with intersect (I) :' . $message
        );
        $this->assertSame(
            count($expectedArray),
            $calcByteLength,
            ' Test calcByteLength for:' . $message
        );
        $this->assertSame(
            true,
            $flagOkay,
            ' Test flagOkay for:' . $message
        );
        $this->assertSame(
            '0',
            $pointByte,
            ' Test pointByte for:' . $message
        );
        for ($i = 0; $i < $calcByteLength; $i++) {
            $this->assertSame(
                substr($testString, $i, 1),
                (isset($calcByteArray[$i]) ? $calcByteArray[$i] : 'ups'),
                ' check indexing in loop for:' . $message
            );

        }
    }

    public function dataProviderNextTokenConfigureTheCorrectInternalConfiguration()
    {
        return [
            [
                '1. Reset for an startstring with all digits from 0-9 and position the pointer on the first token ´0´. '.
                'After the call of `nextToken` it will point internally on the `1`.',
                [
                    'pointNext' => 1,
                    'flagOkay' => true,
                    'pointByte' => '1',
                ],
                [
                    'start' => '0123456789',
                    'pointNext' => 0,
                    'pointByte' => '0',
                    'flagOkay' => true,
                ]
            ],
            [
                '2. Reset for an startstring with all digits from 0-9 and position the pointer on the ´4´. '.
                'After the call of `nextToken` it will point internally on the `5`.',
                [
                    'pointNext' => 5,
                    'flagOkay' => true,
                    'pointByte' => '5',
                ],
                [
                    'start' => '0123456789',
                    'pointNext' => 4,
                    'pointByte' => '4',
                    'flagOkay' => true,
                ]
            ],
            [
                '3. Reset for an startstring with all digits from 0-9 and position the pointer on the ´8´. '.
                'After the call of `nextToken` it will point internally on the `9`.',
                [
                    'pointNext' => 9,
                    'flagOkay' => true,
                    'pointByte' => '9',
                ],
                [
                    'start' => '0123456789',
                    'pointNext' => 8,
                    'pointByte' => '8',
                    'flagOkay' => true,
                ]
            ],
            [
                '4. Reset for an startstring with all digits from 0-9 and position the pointer on the ´9´. '.
                'After the call of `nextToken` it will point internally to the value 10, the `flagOkay` is set to `false`'.
                'and the current `pointByte` will contain an empty string.',
                [
                    'pointNext' => 10,
                    'flagOkay' => false,
                    'pointByte' => '',
                ],
                [
                    'start' => '0123456789',
                    'pointNext' => 9,
                    'pointByte' => '9',
                    'flagOkay' => true,
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderNextTokenConfigureTheCorrectInternalConfiguration
     * @test
     */
    public function nextTokenConfigureTheCorrectInternalConfiguration($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {

            /** @var CalculateUtility $calculateUtility */
            $calculateUtility = new CalculateUtility();

            $calculateUtility->resetForCalculation($params['start']);
            $reflection = new \ReflectionClass($calculateUtility);
            $reflection_pointNext = $reflection->getProperty('pointNext');
            $reflection_pointNext->setAccessible(true);
            $reflection_pointNext->setValue($calculateUtility,$params['pointNext']);

            $reflection_pointByte = $reflection->getProperty('pointByte');
            $reflection_pointByte->setAccessible(true);
            $reflection_pointByte->setValue($calculateUtility, $params['pointByte']);

            $reflection_flagOkay = $reflection->getProperty('flagOkay');
            $reflection_flagOkay->setAccessible(true);
            $reflection_flagOkay->setValue($calculateUtility,$params['flagOkay']);

            $calculateUtility->nextToken();
            $pointNext = $reflection_pointNext->getValue($calculateUtility);
            $pointByte = $reflection_pointByte->getValue($calculateUtility);
            $flagOkay = $reflection_flagOkay->getValue($calculateUtility);

            $this->assertSame(
                $expects['pointNext'],
                $pointNext,
                ' Test pointNext for:'.$message
            );
            $this->assertSame(
                $expects['flagOkay'],
                $flagOkay,
                ' Test flagOkay for:'.$message
            );
            $this->assertSame(
                $expects['pointByte'],
                $pointByte,
                ' Test pointByte for:'.$message
            );
        }
    }


    public function dataProviderPrevTokenConfigureTheCorrectInternalConfiguration()
    {
        return [
            [
                '1. Reset for an startstring with all digits from 0-9 and position the pointer on the first token ´0´. '.
                'After the call of `prevToken` it will point internally on the `1`.',
                [
                    'pointNext' => 0,
                    'flagOkay' => false,
                    'pointByte' => '',
                ],
                [
                    'start' => '0123456789',
                    'pointNext' => 0,
                    'pointByte' => '0',
                    'flagOkay' => true,
                ]
            ],
            [
                '2. Reset for an startstring with all digits from 0-9 and position the pointer on the ´4´. '.
                'After the call of `prevToken` it will point internally on the `3`.',
                [
                    'pointNext' => 3,
                    'flagOkay' => true,
                    'pointByte' => '3',
                ],
                [
                    'start' => '0123456789',
                    'pointNext' => 4,
                    'pointByte' => '4',
                    'flagOkay' => true,
                ]
            ],
            [
                '3. Reset for an startstring with all digits from 0-9 and position the pointer on the ´8´. '.
                'After the call of `prevToken` it will point internally on the `7`.',
                [
                    'pointNext' => 7,
                    'flagOkay' => true,
                    'pointByte' => '7',
                ],
                [
                    'start' => '0123456789',
                    'pointNext' => 8,
                    'pointByte' => '8',
                    'flagOkay' => true,
                ]
            ],
            [
                '4. Reset for an startstring with all digits from 0-9 and position the pointer on the ´9´. '.
                'After the call of `prevToken` it will point internally to the value 8, the `flagOkay` is set to `true`.',
                [
                    'pointNext' => 8,
                    'flagOkay' => true,
                    'pointByte' => '8',
                ],
                [
                    'start' => '0123456789',
                    'pointNext' => 9,
                    'pointByte' => '9',
                    'flagOkay' => true,
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderPrevTokenConfigureTheCorrectInternalConfiguration
     * @test
     */
    public function prevTokenConfigureTheCorrectInternalConfiguration($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {

            /** @var CalculateUtility $calculateUtility */
            $calculateUtility = new CalculateUtility();

            $calculateUtility->resetForCalculation($params['start']);
            $reflection = new \ReflectionClass($calculateUtility);
            $reflection_pointNext = $reflection->getProperty('pointNext');
            $reflection_pointNext->setAccessible(true);
            $reflection_pointNext->setValue($calculateUtility,$params['pointNext']);

            $reflection_pointByte = $reflection->getProperty('pointByte');
            $reflection_pointByte->setAccessible(true);
            $reflection_pointByte->setValue($calculateUtility, $params['pointByte']);

            $reflection_flagOkay = $reflection->getProperty('flagOkay');
            $reflection_flagOkay->setAccessible(true);
            $reflection_flagOkay->setValue($calculateUtility,$params['flagOkay']);

            $calculateUtility->prevToken();
            $pointNext = $reflection_pointNext->getValue($calculateUtility);
            $pointByte = $reflection_pointByte->getValue($calculateUtility);
            $flagOkay = $reflection_flagOkay->getValue($calculateUtility);

            $this->assertSame(
                $expects['pointNext'],
                $pointNext,
                ' Test pointNext for:'.$message
            );
            $this->assertSame(
                $expects['flagOkay'],
                $flagOkay,
                ' Test flagOkay for:'.$message
            );
            $this->assertSame(
                $expects['pointByte'],
                $pointByte,
                ' Test pointByte for:'.$message
            );
        }
    }


    public function dataProviderAnalyseNumberReturnNumberForGivenTokenArray()
    {
        return [
            [
                '1. The array contain the digital representation of an integer.',
                [
                    'flagOkay' => true,
                    'result' => 123,
                ],
                [
                    'numberTokenArray' => ['1','2','3'],
                ]
            ],
            [
                '1.b. The array contain the digital representation of an negative integer.',
                [
                    'flagOkay' => true,
                    'result' => -123,
                ],
                [
                    'numberTokenArray' => ['-','1','2','3'],
                ]
            ],
            [
                '1.c. The array contain the digital representation of an negative integer beginning with a token for the abs-operator.',
                [
                    'flagOkay' => true,
                    'result' => 123,
                ],
                [
                    'numberTokenArray' => ['a','-','1','2','3'],
                ]
            ],
            [
                '1.d. The array contain a single element, which is an integer.',
                [
                    'flagOkay' => true,
                    'result' => 123,
                ],
                [
                    'numberTokenArray' => [123],
                ]
            ],
            [
                '1.e. The array contain the negqative-operator and a single integer.',
                [
                    'flagOkay' => true,
                    'result' => -123,
                ],
                [
                    'numberTokenArray' => ['-',123],
                ]
            ],
            [
                '1.f. The array contain the Token of the abs-operator and the neqative-operator and a single integer.',
                [
                    'flagOkay' => true,
                    'result' => 123,
                ],
                [
                    'numberTokenArray' => ['a','-', 123],
                ]
            ],
            [
                '2. The array contain the digital representation of a float.',
                [
                    'flagOkay' => true,
                    'result' => 123.45,
                ],
                [
                    'numberTokenArray' => ['1','2','3','.','4','5'],
                ]
            ],
            [
                '2.b. The array contain the digital representation of a negative float.',
                [
                    'flagOkay' => true,
                    'result' => -123.45,
                ],
                [
                    'numberTokenArray' => ['-','1','2','3','.','4','5'],
                ]
            ],
            [
                '2.c. The array contain the digital representation of a negative float.',
                [
                    'flagOkay' => true,
                    'result' => 123.45,
                ],
                [
                    'numberTokenArray' => ['a','-','1','2','3','.','4','5'],
                ]
            ],
            [
                '2.d. The array contain a single element, which is a float-number.',
                [
                    'flagOkay' => true,
                    'result' => 123.45,
                ],
                [
                    'numberTokenArray' => [123.45],
                ]
            ],
            [
                '2.e. The array contain the negqative-operator and a single float number.',
                [
                    'flagOkay' => true,
                    'result' => -123.45,
                ],
                [
                    'numberTokenArray' => ['-',123.45],
                ]
            ],
            [
                '2.f. The array contain the Token of the abs-operator and the neqative-operator and a single float number.',
                [
                    'flagOkay' => true,
                    'result' => 123.45,
                ],
                [
                    'numberTokenArray' => ['a','-', 123.45],
                ]
            ],
            [
                '2.f. The array contain the Token of the abs-operator and the neqative-operator and a single float number in stringrepresentation at the end.',
                [
                    'flagOkay' => true,
                    'result' => 123.45,
                ],
                [
                    'numberTokenArray' => ['a','-', '123.45'],
                ]
            ],
            [
                '3. The analysing of the nummer will fail, if the array contain two dots.',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['1','2','3','.','4','5','.','5'],
                ]
            ],
            [
                '3.b.1. The analysing of the will fail, if the array contain tokens, which are longer than one.',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['123','.','4','5'],
                ]
            ],
            [
                '3.b.2. The analysing of the will fail, if the array contain tokens, which are longer than one.',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['1','23','.','4','5'],
                ]
            ],
            [
                '3.b.3. The analysing of the will fail, if the array contain tokens, which are longer than one.',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['1','2','3','.','45'],
                ]
            ],
            [
                '3.c.1. The analysing of the will fail, if the array contain tokens, which are longer than one and ist starts with unitäry-operator tokens.',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['a','-','123','.','4','5'],
                ]
            ],
            [
                '3.c.2. The analysing of the will fail, if the array contain tokens, which are longer than one and ist starts with unitäry-operator tokens.',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['a','-','1','23','.','4','5'],
                ]
            ],
            [
                '3.c.3. The analysing of the will fail, if the array contain tokens, which are longer than one and ist starts with unitäry-operator tokens.',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['a','-','1','2','3','.','45'],
                ]
            ],

            [
                '3.d. The analysing of the will fail, if the array contain a undefined token `x`.',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['a','x','-','1','2','3','.','4','5'],
                ]
            ],

        ];
    }

    /**
     * @dataProvider dataProviderAnalyseNumberReturnNumberForGivenTokenArray
     * @test
     */
    public function analyseNumberReturnNumberForGivenTokenArray($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {

            // prepäre internal-settings for usage of analyseNUmber
            $this->subject->resetForCalculation(implode('',$params['numberTokenArray']));

            $number = $this->subject->analyseNumber($params['numberTokenArray']);

            $reflection = new \ReflectionClass($this->subject);
            $reflection_flagOkay = $reflection->getProperty('flagOkay');
            $reflection_flagOkay->setAccessible(true);
            $flagOkay = $reflection_flagOkay->getValue($this->subject);

            $this->assertSame(
                (int)$expects['result'],
                (int)$number,
                'integer-test: '.$message.' ('.implode('',$params['numberTokenArray']).')'
            );
            $this->assertSame(
                $expects['flagOkay'],
                $flagOkay,
                'internal flagokay: '.$message.' ('.implode('',$params['numberTokenArray']).')'
            );
            if (abs($number) < 0.0000000001) {
                $normalizeValue = 1;
            } else {
                $normalizeValue = abs($number);
            }
            $this->assertLessThanOrEqual(
                1/100000,
                (abs(($expects['result'] - $number))/$normalizeValue),
                'd-e-Test: '.$message.' ('.implode('',$params['numberTokenArray']).')'
            );
        }
    }

    public function dataProviderAnalyseNumberReturnNumberForGivenTokenInTheArray()
    {
        return [
            [
                '1.a. The token of for minus will be analysed on an integer.',
                [
                    'flagOkay' => true,
                    'result' => -123,
                ],
                [
                    'numberTokenArray' => ['-', '1', '2', '3'],
                ]
            ],
            [
                '1.b. The token of for minus will be analysed on a float number.',
                [
                    'flagOkay' => true,
                    'result' => -123.45,
                ],
                [
                    'numberTokenArray' => ['-', '1', '2', '3', '.', '4', '5'],
                ]
            ],

            [
                '1.c. The token of for minus will be analysed on a float number with starting dot.',
                [
                    'flagOkay' => true,
                    'result' => -0.45123,
                ],
                [
                    'numberTokenArray' => ['-', '.', '4', '5', '1', '2', '3'],
                ]
            ],
            [
                '1.d. The token of for minus will be analysed on a float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => -0.45123,
                ],
                [
                    'numberTokenArray' => ['-', 0.45123],
                ]
            ],
            [
                '2.a. The combinations of tokens abs-minus will be analysed on an integer.',
                [
                    'flagOkay' => true,
                    'result' => 123,
                ],
                [
                    'numberTokenArray' => ['a', '-', '1', '2', '3'],
                ]
            ],
            [
                '2.b. The combinations of tokens abs-minus will be analysed on a float number.',
                [
                    'flagOkay' => true,
                    'result' => 123.45,
                ],
                [
                    'numberTokenArray' => ['a', '-', '1', '2', '3', '.', '4', '5'],
                ]
            ],

            [
                '2.c. The combinations of tokens abs-minus will be analysed on a float number with starting dot.',
                [
                    'flagOkay' => true,
                    'result' => 0.45123,
                ],
                [
                    'numberTokenArray' => ['a', '-', '.', '4', '5', '1', '2', '3'],
                ]
            ],
            [
                '2.d. The combinations of tokens abs-minus will be analysed on a float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => 0.45123,
                ],
                [
                    'numberTokenArray' => ['a', '-', 0.45123],
                ]
            ],
            [
                '3.a. The token of `ceil` will be analysed on an integer.',
                [
                    'flagOkay' => true,
                    'result' => 123,
                ],
                [
                    'numberTokenArray' => ['b', '1', '2', '3'],
                ]
            ],
            [
                '3.b. The token of `ceil` will be analysed on a float number.',
                [
                    'flagOkay' => true,
                    'result' => 124,
                ],
                [
                    'numberTokenArray' => ['b', '1', '2', '3', '.', '4', '5'],
                ]
            ],

            [
                '3.c. The token of `ceil` will be analysed on a float number with starting dot.',
                [
                    'flagOkay' => true,
                    'result' => 1,
                ],
                [
                    'numberTokenArray' => ['b', '.', '4', '5', '1', '2', '3'],
                ]
            ],
            [
                '3.d. The token of `ceil` will be analysed on a float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => 1,
                ],
                [
                    'numberTokenArray' => ['b', 0.45123],
                ]
            ],
            [
                '3.e. The token of `ceil` will be analysed on a negative float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['b', -0.45123],
                ]
            ],
            [
                '3.f. The token of `ceil` will be analysed on a negative float number.',
                [
                    'flagOkay' => true,
                    'result' => -123,
                ],
                [
                    'numberTokenArray' => ['b', '-', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '3.g. The token of `ceil` will be analysed on a float number and than it is put to negative. unitäry operators are not always commutative).',
                [
                    'flagOkay' => true,
                    'result' => -124,
                ],
                [
                    'numberTokenArray' => ['-', 'b', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '4.a. The token of `cos` will be analysed on an integer.',
                [
                    'flagOkay' => true,
                    'result' => -0.88796890669185542897832256944262,
                ],
                [
                    'numberTokenArray' => ['c', '1', '2', '3'],
                ]
            ],
            [
                '4.b. The token of `cos` will be analysed on a float number.',
                [
                    'flagOkay' => true,
                    'result' => -0.59952686154253851967340040484045,
                ],
                [
                    'numberTokenArray' => ['c', '1', '2', '3', '.', '4', '5'],
                ]
            ],

            [
                '4.c. The token of `cos` will be analysed on a float number with starting dot.',
                [
                    'flagOkay' => true,
                    'result' => 0.89991141373749756684299439715728,
                ],
                [
                    'numberTokenArray' => ['c', '.', '4', '5', '1', '2', '3'],
                ]
            ],
            [
                '4.d. The token of `cos` will be analysed on a float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => 0.89991141373749756684299439715728,
                ],
                [
                    'numberTokenArray' => ['c', 0.45123],
                ]
            ],
            [
                '4.e. The token of `cos` will be analysed on a negative float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => 0.89991141373749756684299439715728,
                ],
                [
                    'numberTokenArray' => ['c', -0.45123],
                ]
            ],
            [
                '4.f. The token of `cos` will be analysed on a negative float number.',
                [
                    'flagOkay' => true,
                    'result' => -0.59952686154253851967340040484045,
                ],
                [
                    'numberTokenArray' => ['c', '-', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '4.g. The token of `cos` will be analysed on a float number and than it is put to negative. unitäry operators are not always commutative).',
                [
                    'flagOkay' => true,
                    'result' => 0.59952686154253851967340040484045,
                ],
                [
                    'numberTokenArray' => ['-', 'c', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '5.a. The token of `exp` will be analysed on an integer.',
                [
                    'flagOkay' => true,
                    'result' => 2.6195173e+53,
                ],
                [
                    'numberTokenArray' => ['e', '1', '2', '3'],
                ]
            ],
            [
                '5.b. The token of `exp` will be analysed on a float number.',
                [
                    'flagOkay' => true,
                    'result' => 4.1082209e+53,
                ],
                [
                    'numberTokenArray' => ['e', '1', '2', '3', '.', '4', '5'],
                ]
            ],

            [
                '5.c. The token of `exp` will be analysed on a float number with starting dot.',
                [
                    'flagOkay' => true,
                    'result' => 1.57024239631,
                ],
                [
                    'numberTokenArray' => ['e', '.', '4', '5', '1', '2', '3'],
                ]
            ],
            [
                '5.d. The token of `exp` will be analysed on a float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => 1.57024239631,
                ],
                [
                    'numberTokenArray' => ['e', 0.45123],
                ]
            ],
            [
                '5.e. The token of `exp` will be analysed on a negative float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => 0.63684435113,
                ],
                [
                    'numberTokenArray' => ['e', -0.45123],
                ]
            ],
            [
                '5.f. The token of `exp` will be analysed on a negative float number.',
                [
                    'flagOkay' => true,
                    'result' => 2.4341437e-54,
                ],
                [
                    'numberTokenArray' => ['e', '-', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '5.g. The token of `exp` will be analysed on a float number and than it is put to negative. unitäry operators are not always commutative).',
                [
                    'flagOkay' => true,
                    'result' => -4.1082209e+53,
                ],
                [
                    'numberTokenArray' => ['-', 'e', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '6.a. The token of `floor` will be analysed on an integer.',
                [
                    'flagOkay' => true,
                    'result' => 123,
                ],
                [
                    'numberTokenArray' => ['f', '1', '2', '3'],
                ]
            ],
            [
                '6.b. The token of `floor` will be analysed on a float number.',
                [
                    'flagOkay' => true,
                    'result' => 123,
                ],
                [
                    'numberTokenArray' => ['f', '1', '2', '3', '.', '4', '5'],
                ]
            ],

            [
                '6.c. The token of `floor` will be analysed on a float number with starting dot.',
                [
                    'flagOkay' => true,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['f', '.', '4', '5', '1', '2', '3'],
                ]
            ],
            [
                '6.d. The token of `floor` will be analysed on a float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['f', 0.45123],
                ]
            ],
            [
                '6.e. The token of `floor` will be analysed on a negative float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => -1,
                ],
                [
                    'numberTokenArray' => ['f', -0.45123],
                ]
            ],
            [
                '6.f. The token of `floor` will be analysed on a negative float number.',
                [
                    'flagOkay' => true,
                    'result' => -124,
                ],
                [
                    'numberTokenArray' => ['f', '-', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '6.g. The token of `floor` will be analysed on a float number and than it is put to negative. unitäry operators are not always commutative).',
                [
                    'flagOkay' => true,
                    'result' => -123,
                ],
                [
                    'numberTokenArray' => ['-', 'f', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '7.a. The token of `ln` (base e) will be analysed on an integer.',
                [
                    'flagOkay' => true,
                    'result' => 4.81218435537,
                ],
                [
                    'numberTokenArray' => ['l', '1', '2', '3'],
                ]
            ],
            [
                '7.b. The token of `ln` (base e) will be analysed on a float number.',
                [
                    'flagOkay' => true,
                    'result' => 4.81583621579,
                ],
                [
                    'numberTokenArray' => ['l', '1', '2', '3', '.', '4', '5'],
                ]
            ],

            [
                '7.c. The token of `ln` (base e) will be analysed on a float number with starting dot.',
                [
                    'flagOkay' => true,
                    'result' => -0.79577809164,
                ],
                [
                    'numberTokenArray' => ['l', '.', '4', '5', '1', '2', '3'],
                ]
            ],
            [
                '7.d. The token of `ln` (base e) will be analysed on a float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => -0.79577809164,
                ],
                [
                    'numberTokenArray' => ['l', 0.45123],
                ]
            ],
            [
                '7.e. The token of `ln` (base e) will be analysed on a negative float at the end of array.',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['l', -0.45123],
                ]
            ],
            [
                '7.f. The token of `ln` (base e) will be analysed on a negative float number.',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['l', '-', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '7.g. The token of `ln` (base e) will be analysed on a float number and than it is put to negative. unitäry operators are not always commutative).',
                [
                    'flagOkay' => true,
                    'result' => -4.81583621579,
                ],
                [
                    'numberTokenArray' => ['-', 'l', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '8.a. The token of `log`(base 10) will be analysed on an integer.',
                [
                    'flagOkay' => true,
                    'result' => 2.08990511144,
                ],
                [
                    'numberTokenArray' => ['z', '1', '2', '3'],
                ]
            ],
            [
                '8.b. The token of `log`(base 10) will be analysed on a float number.',
                [
                    'flagOkay' => true,
                    'result' => 2.09149109427,
                ],
                [
                    'numberTokenArray' => ['z', '1', '2', '3', '.', '4', '5'],
                ]
            ],

            [
                '8.c. The token of `log`(base 10) will be analysed on a float number with starting dot.',
                [
                    'flagOkay' => true,
                    'result' => -0.34560203402,
                ],
                [
                    'numberTokenArray' => ['z', '.', '4', '5', '1', '2', '3'],
                ]
            ],
            [
                '8.d. The token of `log`(base 10) will be analysed on a float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => -0.34560203402,
                ],
                [
                    'numberTokenArray' => ['z', 0.45123],
                ]
            ],
            [
                '8.e. The token of `log`(base 10) will be analysed on a negative float at the end of array.',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['z', -0.45123],
                ]
            ],
            [
                '8.f. The token of `log`(base 10) will be analysed on a negative float number.',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['z', '-', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '8.g. The token of `log`(base 10) will be analysed on a float number and than it is put to negative. unitäry operators are not always commutative).',
                [
                    'flagOkay' => true,
                    'result' => -2.09149109427,
                ],
                [
                    'numberTokenArray' => ['-', 'z', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '9.a. The token of `round` will be analysed on an integer.',
                [
                    'flagOkay' => true,
                    'result' => 123,
                ],
                [
                    'numberTokenArray' => ['r', '1', '2', '3'],
                ]
            ],
            [
                '9.b. The token of `round` will be analysed on a float number.',
                [
                    'flagOkay' => true,
                    'result' => 123,
                ],
                [
                    'numberTokenArray' => ['r', '1', '2', '3', '.', '4', '5'],
                ]
            ],

            [
                '9.b.2. The token of `round` will be analysed on a float number.',
                [
                    'flagOkay' => true,
                    'result' => 124,
                ],
                [
                    'numberTokenArray' => ['r', '1', '2', '3', '.', '5', '5'],
                ]
            ],

            [
                '9.c. The token of `round` will be analysed on a float number with starting dot.',
                [
                    'flagOkay' => true,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['r', '.', '4', '5', '1', '2', '3'],
                ]
            ],
            [
                '9.c.2. The token of `round` will be analysed on a float number with starting dot.',
                [
                    'flagOkay' => true,
                    'result' => 1,
                ],
                [
                    'numberTokenArray' => ['r', '.', '5', '5', '1', '2', '3'],
                ]
            ],
            [
                '9.d. The token of `round` will be analysed on a float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['r', 0.45123],
                ]
            ],
            [
                '9.e. The token of `round` will be analysed on a negative float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['r', -0.45123],
                ]
            ],
            [
                '9.e. The token of `round` will be analysed on a negative float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => -1,
                ],
                [
                    'numberTokenArray' => ['r', -0.55123],
                ]
            ],
            [
                '9.f. The token of `round` will be analysed on a negative float number.',
                [
                    'flagOkay' => true,
                    'result' => -123,
                ],
                [
                    'numberTokenArray' => ['r', '-', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '9.f. 2.The token of `round` will be analysed on a negative float number.',
                [
                    'flagOkay' => true,
                    'result' => -124,
                ],
                [
                    'numberTokenArray' => ['r', '-', '1', '2', '3', '.', '5', '5'],
                ]
            ],
            [
                '9.g. The token of `round` will be analysed on a float number and than it is put to negative. unitäry operators are not always commutative).',
                [
                    'flagOkay' => true,
                    'result' => -123,
                ],
                [
                    'numberTokenArray' => ['-', 'r', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '9.g.2. The token of `round` will be analysed on a float number and than it is put to negative. unitäry operators are not always commutative).',
                [
                    'flagOkay' => true,
                    'result' => -124,
                ],
                [
                    'numberTokenArray' => ['-', 'r', '1', '2', '3', '.', '5', '5'],
                ]
            ],
            [
                '10.a. The token of `sin` will be analysed on an integer.',
                [
                    'flagOkay' => true,
                    'result' => -0.45990349069,
                ],
                [
                    'numberTokenArray' => ['s', '1', '2', '3'],
                ]
            ],
            [
                '10.b. The token of `sin` will be analysed on a float number.',
                [
                    'flagOkay' => true,
                    'result' => -0.80035463532,
                ],
                [
                    'numberTokenArray' => ['s', '1', '2', '3', '.', '4', '5'],
                ]
            ],

            [
                '10.c. The token of `sin` will be analysed on a float number with starting dot.',
                [
                    'flagOkay' => true,
                    'result' => 0.43607275473,
                ],
                [
                    'numberTokenArray' => ['s', '.', '4', '5', '1', '2', '3'],
                ]
            ],
            [
                '10.d. The token of `sin` will be analysed on a float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => 0.43607275473,
                ],
                [
                    'numberTokenArray' => ['s', 0.45123],
                ]
            ],
            [
                '10.e. The token of `sin` will be analysed on a negative float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => -0.43607275473,
                ],
                [
                    'numberTokenArray' => ['s', -0.45123],
                ]
            ],
            [
                '10.f. The token of `sin` will be analysed on a negative float number.',
                [
                    'flagOkay' => true,
                    'result' => 0.80035463532,
                ],
                [
                    'numberTokenArray' => ['s', '-', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '10.g. The token of `sin` will be analysed on a float number and than it is put to negative. unitäry operators are not always commutative).',
                [
                    'flagOkay' => true,
                    'result' => 0.80035463532,
                ],
                [
                    'numberTokenArray' => ['-', 's', '1', '2', '3', '.', '4', '5'],
                ]
            ],

            [
                '11.a. The token of `tan` will be analysed on an integer.',
                [
                    'flagOkay' => true,
                    'result' => 0.51792747158,
                ],
                [
                    'numberTokenArray' => ['t', '1', '2', '3'],
                ]
            ],
            [
                '11.b. The token of `tan` will be analysed on a float number.',
                [
                    'flagOkay' => true,
                    'result' => 1.33497710723,
                ],
                [
                    'numberTokenArray' => ['t', '1', '2', '3', '.', '4', '5'],
                ]
            ],

            [
                '11.c. The token of `tan` will be analysed on a float number with starting dot.',
                [
                    'flagOkay' => true,
                    'result' =>0.48457297916,
                ],
                [
                    'numberTokenArray' => ['t', '.', '4', '5', '1', '2', '3'],
                ]
            ],
            [
                '11.d. The token of `tan` will be analysed on a float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => 0.48457297916,
                ],
                [
                    'numberTokenArray' => ['t', 0.45123],
                ]
            ],
            [
                '11.e. The token of `tan` will be analysed on a negative float at the end of array.',
                [
                    'flagOkay' => true,
                    'result' => -0.48457297916,
                ],
                [
                    'numberTokenArray' => ['t', -0.45123],
                ]
            ],
            [
                '11.f. The token of `tan` will be analysed on a negative float number.',
                [
                    'flagOkay' => true,
                    'result' => -1.33497710723,
                ],
                [
                    'numberTokenArray' => ['t', '-', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '11.g. The token of `tan` will be analysed on a float number and than it is put to negative. unitäry operators are not always commutative).',
                [
                    'flagOkay' => true,
                    'result' => -1.33497710723,
                ],
                [
                    'numberTokenArray' => ['-', 't', '1', '2', '3', '.', '4', '5'],
                ]
            ],
            [
                '11.h.1 The token of `tan` will be analysed on a float number and than it is put near to pi/2).',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['t', 1.57079632679],
                ]
            ],
            [
                '11.h.2 The token of `tan` will be analysed on a float number and than it is put near to -pi/2).',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['t', -1.57079632679],
                ]
            ],
            [
                '11.h.3 The token of `tan` will be analysed on a float number and than it is put near to pi/2+5*pi).',
                [
                    'flagOkay' => false,
                    'result' => 0,
                    // google will calculate 22797606976.3 for this. the windows calculate
                    // 22798356165,212823871606156989621
                ],
                [
                    'numberTokenArray' => ['t', 17.2787595947],
                ]

            ],
            [
                '11.i.1 The token of `tan` will be analysed on a float number and than it is put to pi/2).',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['t', (pi()/2)],
                ]
            ],
            [
                '11.i.2 The token of `tan` will be analysed on a float number and than it is put to -pi/2).',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['t', (-pi()/2)],
                ]
            ],
            [
                '11.i.3 The token of `tan` will be analysed on a float number and than it is put to pi/2+5*pi).',
                [
                    'flagOkay' => false,
                    'result' => 0,
                ],
                [
                    'numberTokenArray' => ['t', (11*pi()/2)],
                ]
            ],

        ];
    }

    /**
     * @dataProvider dataProviderAnalyseNumberReturnNumberForGivenTokenInTheArray
     * @test
     */
    public function analyseNumberReturnNumberForGivenTokenInTheArray($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {

            // prepäre internal-settings for usage of analyseNUmber
            $this->subject->resetForCalculation(implode('', $params['numberTokenArray']));

            $number = $this->subject->analyseNumber($params['numberTokenArray']);

            $reflection = new \ReflectionClass($this->subject);
            $reflection_flagOkay = $reflection->getProperty('flagOkay');
            $reflection_flagOkay->setAccessible(true);
            $flagOkay = $reflection_flagOkay->getValue($this->subject);

            $this->assertSame(
                (int)$expects['result'],
                (int)$number,
                'integer-test: ' . $message . ' (' . implode('', $params['numberTokenArray']) . ')'
            );
            $this->assertSame(
                $expects['flagOkay'],
                $flagOkay,
                'internal flagokay: ' . $message . ' (' . implode('', $params['numberTokenArray']) . ')'
            );
            if (abs($number) < 0.0000000001) {
                $normalizeValue = 1;
            } else {
                $normalizeValue = abs($number);
            }
            $this->assertLessThanOrEqual(
                1 / 100000,
                (abs(($expects['result'] - $number)) / $normalizeValue),
                'd-e-Test: ' . $message . ' (' . implode('', $params['numberTokenArray']) . ')'
            );
        }
    }



    /**
     * mock the internal method nextToken and its influence on the global class-variable flagOkay
     * @test
     */
    public function detectOperatorReturnEmptyStringAndSetInternalConfigurationToFalseGivenOperatorDoesNotExistOnCurrentPlace()
    {
        /** @var CalculateUtility $calculateUtility */
        $calculateUtility = new CalculateUtility();

        $testString = '0123456789';
        $arrayStartWithOperator = ['0', '1', '2', '3', '4', '0', '5', '6', '7', '8', '9', '0'];

        $calculateUtility = $this->getMockBuilder(CalculateUtility::class)
            ->setMethods(['nextToken'])
            ->getMock();

        $calculateUtility->expects($this->never())
            ->method('nextToken');
        // reset to default-Values
        $calculateUtility->resetForCalculation($testString);

        $operator = $calculateUtility->detectOperator();

        $message = "2. Teststring don't contain an operator and will give back an empty string and set internal `flagOkay` to false.";

        $reflection = new \ReflectionClass($calculateUtility);
        $reflection_pointNext = $reflection->getProperty('pointNext');
        $reflection_pointNext->setAccessible(true);
        $pointNext = $reflection_pointNext->getValue($calculateUtility);

        $reflection_pointByte = $reflection->getProperty('pointByte');
        $reflection_pointByte->setAccessible(true);
        $pointByte = $reflection_pointByte->getValue($calculateUtility);

        $reflection_calcByteArray = $reflection->getProperty('calcByteArray');
        $reflection_calcByteArray->setAccessible(true);
        $calcByteArray = $reflection_calcByteArray->getValue($calculateUtility);

        $reflection_calcByteLength = $reflection->getProperty('calcByteLength');
        $reflection_calcByteLength->setAccessible(true);
        $calcByteLength = $reflection_calcByteLength->getValue($calculateUtility);

        $reflection_flagOkay = $reflection->getProperty('flagOkay');
        $reflection_flagOkay->setAccessible(true);
        $flagOkay = $reflection_flagOkay->getValue($calculateUtility);

        $this->assertSame(
            0,
            $pointNext,
            ' Test pointNext for:' . $message
        );
        $this->assertSame(
            false,
            $flagOkay,
            ' Test flagOkay for:' . $message
        );
        $this->assertSame(
            '0',
            $pointByte,
            ' Test pointByte for:' . $message
        );
        $this->assertSame(
            '',
            $operator,
            ' Test operator-result for:' . $message
        );
    }


    /**
     * mock the internal method nextToken and its influence on the global class-variable flagOkay
     * @test
     */
    public function detectOperatorReturnOperatoAndSetInternalConfigurationGivenOperatorExist()
    {

        /** @var CalculateUtility $calculateUtility */
        $calculateUtility = new CalculateUtility();

        $testString = '+123456789';
        $arrayStartWithOperator = ['+', '1', '2', '3', '4', '0', '5', '6', '7', '8', '9', '0'];

        $calculateUtility = $this->getMockBuilder(CalculateUtility::class)
            ->setMethods(['nextToken'])
            ->getMock();

        $calculateUtility->expects($this->once())
            ->method('nextToken');
        // reset to default-Values
        $calculateUtility->resetForCalculation($testString);

        $operator = $calculateUtility->detectOperator();

        $message = "1. Teststring beginns with an Operator, which will returned by detectOperator. (the internal Value won't be changed via one Call of nextToken).";

        $reflection = new \ReflectionClass($calculateUtility);
        $reflection_pointNext = $reflection->getProperty('pointNext');
        $reflection_pointNext->setAccessible(true);
        $pointNext = $reflection_pointNext->getValue($calculateUtility);

        $reflection_pointByte = $reflection->getProperty('pointByte');
        $reflection_pointByte->setAccessible(true);
        $pointByte = $reflection_pointByte->getValue($calculateUtility);

        $reflection_calcByteArray = $reflection->getProperty('calcByteArray');
        $reflection_calcByteArray->setAccessible(true);
        $calcByteArray = $reflection_calcByteArray->getValue($calculateUtility);

        $reflection_calcByteLength = $reflection->getProperty('calcByteLength');
        $reflection_calcByteLength->setAccessible(true);
        $calcByteLength = $reflection_calcByteLength->getValue($calculateUtility);

        $reflection_flagOkay = $reflection->getProperty('flagOkay');
        $reflection_flagOkay->setAccessible(true);
        $flagOkay = $reflection_flagOkay->getValue($calculateUtility);

        $this->assertSame(
            0,
            $pointNext,
            ' Test pointNext for:' . $message . ' - not changed because of mocking `nextToken`.'
        );
        $this->assertSame(
            true,
            $flagOkay,
            ' Test flagOkay for:' . $message
        );
        $this->assertSame(
            '+',
            $pointByte,
            ' Test pointByte for:' . $message . ' - not changed because of mocking `nextToken`.'
        );
        $this->assertSame(
            '+',
            $operator,
            ' Test operator-result for:' . $message
        );
    }

    /**
     * mock the internal method nextToken and its influence on the global class-variable flagOkay
     * @test
     */
    public function testForStartBracketReturnFalseAndLeavedInternalConfigurationGivenTheCurrentPositionDoesNotContainAnOpeningBracket()
    {
        /** @var CalculateUtility $calculateUtility */
        $calculateUtility = new CalculateUtility();

        $testString = '0123456789';
        $arrayStartWithOperator = ['0', '1', '2', '3', '4', '0', '5', '6', '7', '8', '9', '0'];

        $calculateUtility = $this->getMockBuilder(CalculateUtility::class)
            ->setMethods(['nextToken'])
            ->getMock();

        $calculateUtility->expects($this->never())
            ->method('nextToken');
        // reset to default-Values
        $calculateUtility->resetForCalculation($testString);

        $flagTest = $calculateUtility->testForStartBracket();

        $message = "1. There is no opening bracket at the current position. The method returns false and nothing internal will be changed.";

        $reflection = new \ReflectionClass($calculateUtility);
        $reflection_pointNext = $reflection->getProperty('pointNext');
        $reflection_pointNext->setAccessible(true);
        $pointNext = $reflection_pointNext->getValue($calculateUtility);

        $reflection_pointByte = $reflection->getProperty('pointByte');
        $reflection_pointByte->setAccessible(true);
        $pointByte = $reflection_pointByte->getValue($calculateUtility);

        $reflection_calcByteArray = $reflection->getProperty('calcByteArray');
        $reflection_calcByteArray->setAccessible(true);
        $calcByteArray = $reflection_calcByteArray->getValue($calculateUtility);

        $reflection_calcByteLength = $reflection->getProperty('calcByteLength');
        $reflection_calcByteLength->setAccessible(true);
        $calcByteLength = $reflection_calcByteLength->getValue($calculateUtility);

        $reflection_flagOkay = $reflection->getProperty('flagOkay');
        $reflection_flagOkay->setAccessible(true);
        $flagOkay = $reflection_flagOkay->getValue($calculateUtility);

        $this->assertSame(
            0,
            $pointNext,
            ' Test pointNext for:' . $message
        );
        $this->assertSame(
            true,
            $flagOkay,
            ' Test flagOkay for:' . $message
        );
        $this->assertSame(
            '0',
            $pointByte,
            ' Test pointByte for:' . $message
        );
        $this->assertSame(
            false,
            $flagTest,
            ' Check Flag of the Test for:' . $message
        );
    }


    /**
     * mock the internal method nextToken and its influence on the global class-variable flagOkay
     * @test
     */
    public function testForStartBracketReturnTrueAndUseNextTokenGivenThatTheCurrentPositionContainAnOpeningBracket()
    {

        /** @var CalculateUtility $calculateUtility */
        $calculateUtility = new CalculateUtility();

        $testString = '(123456789';
        $arrayStartWithOperator = ['(', '1', '2', '3', '4', '0', '5', '6', '7', '8', '9', '0'];

        $calculateUtility = $this->getMockBuilder(CalculateUtility::class)
            ->setMethods(['nextToken'])
            ->getMock();

        $calculateUtility->expects($this->once())
            ->method('nextToken');
        // reset to default-Values
        $calculateUtility->resetForCalculation($testString);

        $flagTest = $calculateUtility->testForStartBracket();

        $message = "1. There is an opening bracket at the current position. The method returns true and the method nextToken will be called.";

        $reflection = new \ReflectionClass($calculateUtility);
        $reflection_pointNext = $reflection->getProperty('pointNext');
        $reflection_pointNext->setAccessible(true);
        $pointNext = $reflection_pointNext->getValue($calculateUtility);

        $reflection_pointByte = $reflection->getProperty('pointByte');
        $reflection_pointByte->setAccessible(true);
        $pointByte = $reflection_pointByte->getValue($calculateUtility);

        $reflection_calcByteArray = $reflection->getProperty('calcByteArray');
        $reflection_calcByteArray->setAccessible(true);
        $calcByteArray = $reflection_calcByteArray->getValue($calculateUtility);

        $reflection_calcByteLength = $reflection->getProperty('calcByteLength');
        $reflection_calcByteLength->setAccessible(true);
        $calcByteLength = $reflection_calcByteLength->getValue($calculateUtility);

        $reflection_flagOkay = $reflection->getProperty('flagOkay');
        $reflection_flagOkay->setAccessible(true);
        $flagOkay = $reflection_flagOkay->getValue($calculateUtility);

        $this->assertSame(
            0,
            $pointNext,
            ' Test pointNext for:' . $message . ' - not changed because of mocking `nextToken`.'
        );
        $this->assertSame(
            true,
            $flagOkay,
            ' Test flagOkay for:' . $message
        );
        $this->assertSame(
            '(',
            $pointByte,
            ' Test pointByte for:' . $message . ' - not changed because of mocking `nextToken`.'
        );
        $this->assertSame(
            true,
            $flagTest,
            ' Test operator-result for:' . $message
        );
    }
    /**
     * mock the internal method nextToken and its influence on the global class-variable flagOkay
     * @test
     */
    public function detectEndBracketReturnNothingAndLeavedInternalConfigurationUnchangedGivenTheCurrentPositionDoesNotContainAnOpeningBracket()
    {
        /** @var CalculateUtility $calculateUtility */
        $calculateUtility = new CalculateUtility();

        $testString = '0123456789';
        $arrayStartWithOperator = ['0', '1', '2', '3', '4', '0', '5', '6', '7', '8', '9', '0'];

        $calculateUtility = $this->getMockBuilder(CalculateUtility::class)
            ->setMethods(['nextToken'])
            ->getMock();

        $calculateUtility->expects($this->never())
            ->method('nextToken');
        // reset to default-Values
        $calculateUtility->resetForCalculation($testString);

        $calculateUtility->detectEndBracket();

        $message = "1. There is no closing bracket at the current position. The method returns false and nothing internal will be changed.";

        $reflection = new \ReflectionClass($calculateUtility);
        $reflection_pointNext = $reflection->getProperty('pointNext');
        $reflection_pointNext->setAccessible(true);
        $pointNext = $reflection_pointNext->getValue($calculateUtility);

        $reflection_pointByte = $reflection->getProperty('pointByte');
        $reflection_pointByte->setAccessible(true);
        $pointByte = $reflection_pointByte->getValue($calculateUtility);

        $reflection_calcByteArray = $reflection->getProperty('calcByteArray');
        $reflection_calcByteArray->setAccessible(true);
        $calcByteArray = $reflection_calcByteArray->getValue($calculateUtility);

        $reflection_calcByteLength = $reflection->getProperty('calcByteLength');
        $reflection_calcByteLength->setAccessible(true);
        $calcByteLength = $reflection_calcByteLength->getValue($calculateUtility);

        $reflection_flagOkay = $reflection->getProperty('flagOkay');
        $reflection_flagOkay->setAccessible(true);
        $flagOkay = $reflection_flagOkay->getValue($calculateUtility);

        $this->assertSame(
            0,
            $pointNext,
            ' Test pointNext for:' . $message
        );
        $this->assertSame(
            false,
            $flagOkay,
            ' Test flagOkay for:' . $message
        );
        $this->assertSame(
            '0',
            $pointByte,
            ' Test pointByte for:' . $message
        );
    }


    /**
     * mock the internal method nextToken and its influence on the global class-variable flagOkay
     * @test
     */
    public function detectEndBracketReturnNothingAndUseNextTokenGivenThatTheCurrentPositionContainAnOpeningBracket()
    {

        /** @var CalculateUtility $calculateUtility */
        $calculateUtility = new CalculateUtility();

        $testString = ')123456789';
        $arrayStartWithOperator = [')', '1', '2', '3', '4', '0', '5', '6', '7', '8', '9', '0'];

        $calculateUtility = $this->getMockBuilder(CalculateUtility::class)
            ->setMethods(['nextToken'])
            ->getMock();

        $calculateUtility->expects($this->once())
            ->method('nextToken');
        // reset to default-Values
        $calculateUtility->resetForCalculation($testString);

        $calculateUtility->detectEndBracket();

        $message = "1. There is an closing bracket at the current position. The method returns true and the method nextToken will be called.";

        $reflection = new \ReflectionClass($calculateUtility);
        $reflection_pointNext = $reflection->getProperty('pointNext');
        $reflection_pointNext->setAccessible(true);
        $pointNext = $reflection_pointNext->getValue($calculateUtility);

        $reflection_pointByte = $reflection->getProperty('pointByte');
        $reflection_pointByte->setAccessible(true);
        $pointByte = $reflection_pointByte->getValue($calculateUtility);

        $reflection_calcByteArray = $reflection->getProperty('calcByteArray');
        $reflection_calcByteArray->setAccessible(true);
        $calcByteArray = $reflection_calcByteArray->getValue($calculateUtility);

        $reflection_calcByteLength = $reflection->getProperty('calcByteLength');
        $reflection_calcByteLength->setAccessible(true);
        $calcByteLength = $reflection_calcByteLength->getValue($calculateUtility);

        $reflection_flagOkay = $reflection->getProperty('flagOkay');
        $reflection_flagOkay->setAccessible(true);
        $flagOkay = $reflection_flagOkay->getValue($calculateUtility);

        $this->assertSame(
            0,
            $pointNext,
            ' Test pointNext for:' . $message . ' - not changed because of mocking `nextToken`.'
        );
        $this->assertSame(
            true,
            $flagOkay,
            ' Test flagOkay for:' . $message
        );
        $this->assertSame(
            ')',
            $pointByte,
            ' Test pointByte for:' . $message . ' - not changed because of mocking `nextToken`.'
        );
    }


    public function dataProviderCalculatePairForGivenCalculationScenarios()
    {
        return [
            [
                '0.a. `↑`-Check. Max-Operator: There is never  eastimated a change of the value of `flagOkay`.',
                [
                    [true, '3', '↑', '4', '4'],
                    [true, '-3', '↑', '4', '4'],
                    [true, '3', '↑', '-4', '3'],
                    [true, '-3', '↑', '-4', '-3'],
                    [true, '-4', '↑', '-4', '-4'],
                    [true, '3.456', '↑', '4.547', '4.547'],
                    [true, '-3.456', '↑', '4.547', '4.547'],
                    [true, '3.456', '↑', '-4.547', '3.456'],
                    [true, '-3.456', '↑', '-4.547', '-3.456'],
                    [true, '-4.547', '↑', '-4.547', '-4.547'],
                ]
            ],
            [
                '0.b. `↓`-Check. Plus-Operator: There is never  eastimated a change of the value of `flagOkay`.',
                [
                    [true, '3', '↓', '3', '3'],
                    [true, '3', '↓', '4', '3'],
                    [true, '-3', '↓', '4', '-3'],
                    [true, '3', '↓', '-4', '-4'],
                    [true, '-3', '↓', '-4', '-4'],
                    [true, '-4', '↓', '-4', '-4'],
                    [true, '3.456', '↓', '4.547', '3.456'],
                    [true, '-3.456', '↓', '4.547', '-3.456'],
                    [true, '3.456', '↓', '-4.547', '-4.547'],
                    [true, '-3.456', '↓', '-4.547', '-4.547'],
                    [true, '-4.547', '↓', '-4.547', '-4.547'],
                ]
            ],
            [
                '1. `+`-Check. Plus-Operator: There is never  eastimated a change of the value of `flagOkay`.',
                [
                    [true, '3', '+', '4', '7'],
                    [true, '-3', '+', '4', '1'],
                    [true, '3', '+', '-4', '-1'],
                    [true, '-3', '+', '-4', '-7'],
                    [true, '3.456', '+', '4.547', '8.003'],
                    [true, '-3.456', '+', '4.547', '1.091'],
                    [true, '3.456', '+', '-4.547', '-1.091'],
                    [true, '-3.456', '+', '-4.547', '-8.003'],
                ]
            ],
            [
                '2. `-`-Check. Minus-Operator: There is never  eastimated a change of the value of `flagOkay`.',
                [
                    [true, '3', '-', '4', '-1'],
                    [true, '-3', '-', '4', '-7'],
                    [true, '3', '-', '-4', '7'],
                    [true, '-3', '-', '-4', '1'],
                    [true, '3.456', '-', '4.547', '-1.091'],
                    [true, '-3.456', '-', '4.547', '-8.003'],
                    [true, '3.456', '-', '-4.547', '8.003'],
                    [true, '-3.456', '-', '-4.547', '1.091'],
                ]
            ],
            [
                '3. `*`-Check. Multiply-Operator: There is never  eastimated a change of the value of `flagOkay`.',
                [
                    [true, '3', '*', '4', '12'],
                    [true, '-3', '*', '4', '-12'],
                    [true, '3', '*', '-4', '-12'],
                    [true, '-3', '*', '-4', '12'],
                    [true, '3.456', '*', '4.547', '15.714432'],
                    [true, '-3.456', '*', '4.547', '-15.714432'],
                    [true, '3.456', '*', '-4.547', '-15.714432'],
                    [true, '-3.456', '*', '-4.547', '15.714432'],
                ]
            ],
            [
                '4. `/`-Check. Division-Operator: There is eastimated a change of the value of `flagOkay`, '. "\n".
                'if the divisor ist near zero (between ] -'.CalculateUtility::PRECISION_DELTA_EXPSILON .' , ' .
                CalculateUtility::PRECISION_DELTA_EXPSILON.' [',
                [
                    [true, '5', '/', '4', '1.25'],
                    [true, '5', '/', '-4', '-1.25'],
                    [true, '-5', '/', '4', '-1.25'],
                    [true, '-5', '/', '-4', '1.25'],
                    [true, '5.8', '/', '4', '1.45'],
                    [true, '5.8', '/', '-4', '-1.45'],
                    [true, '-5.8', '/', '4', '-1.45'],
                    [true, '-5.8', '/', '-4', '1.45'],
                    [true, '24.94', '/', '4.3', '5.8'],
                    [true, '24.94', '/', '-4.3', '-5.8'],
                    [true, '-24.94', '/', '4.3', '-5.8'],
                    [true, '-24.94', '/', '-4.3', '5.8'],
                    [true, '-3', '/', '0.1', '-30'],
                    [true, '3', '/', '0.1', '30'],
                    [true, '3', '/', '0.01', '300'],
                    [true, '3', '/', '0.001', '3000'],
                    [true, '3', '/', '0.0001', '30000'],
                    [false, '3', '/', CalculateUtility::PRECISION_DELTA_EXPSILON/2, '0'],
                    [false, '-3', '/', CalculateUtility::PRECISION_DELTA_EXPSILON/2, '0'],
                    [false, '3', '/', -CalculateUtility::PRECISION_DELTA_EXPSILON/2, '0'],
                    [false, '-3', '/', -CalculateUtility::PRECISION_DELTA_EXPSILON/2, '0'],
                ]
            ],
            [
                '5. `§`-Check. Modulo- oder Frational-Cut-Operator: It means the fractional part multiplyed with the divisor. ' . "\n" .
                'It is similiar to the modulo-operator for integers. It is eastimated a change of the value of `flagOkay`, ' . "\n" .
                'if the divisor is near zero (between ] -' . CalculateUtility::PRECISION_DELTA_EXPSILON . ' , ' .
                CalculateUtility::PRECISION_DELTA_EXPSILON . ' [' . "\n" .
                'caclulation-scheme: $result = ($dividend / $divisor - floor($dividend / $divisor)) * $divisor;',
                [
                    [true, '5', '#', '4', '1'],
                    [true, '5', '#', '-4', '-3'],
                    [true, '-5', '#', '4', '3'],
                    [true, '-5', '#', '-4', '-1'],
                    [true, '5.8', '#', '4', '1.8'],
                    [true, '5.8', '#', '-4', '-2.2'],
                    [true, '-5.8', '#', '4', '2.2'],
                    [true, '-5.8', '#', '-4', '-1.8'],
                    [true, '5.8', '#', '4.3', '1.5'],
                    [true, '5.8', '#', '-4.3', '-2.8'],
                    [true, '-5.8', '#', '4.3', '2.8'],
                    [true, '-5.8', '#', '-4.3', '-1.5'],
                    [true, '-3', '#', '0.1', '0'],
                    [true, '3', '#', '0.1', '0'],
                    [true, '3', '#', '0.01', '0'],
                    [true, '3', '#', '0.001', '0'],
                    [true, '3', '#', '0.0001', '0'],
                    [false, '3', '#', CalculateUtility::PRECISION_DELTA_EXPSILON / 2, '0'],
                    [false, '-3', '#', CalculateUtility::PRECISION_DELTA_EXPSILON / 2, '0'],
                    [false, '3', '#', -CalculateUtility::PRECISION_DELTA_EXPSILON / 2, '0'],
                    [false, '-3', '#', -CalculateUtility::PRECISION_DELTA_EXPSILON / 2, '0'],
                ]
            ],

            [
                '6. `**` or '^'-Check. Power-Operator: There is eastimated a change of the value of `flagOkay`, '. "\n".
                  'if the first valuse is negative. (internalt-token is ^)',
                [
                    [true, '3', '^', '4', '81'],
                    [true, '3', '^', '3', '27'],
                    [false, '-3', '^', '3', '0'], // in general a Power-
                    [false, '-3.5', '^', '-3.5', '0'], // in general a Power-
                    [true, '16', '^', '0.25', '2'],
                    [true, '16', '^', '0.5', '4'],
                    [true, '16', '^', '-0.5', '0.25'],
                    [true, '16', '^', '-0.25', '0.5'],
                    [true, '6.25', '^', '0.5', '2.5'],
                    [true, '6.25', '^', '-0.5', '0.4'],
                ]
            ],
            [
                '7. `\`-Check. Floor-Devision Integer-Devision-Operator: It means the integer-part of the quotzient. ' . "\n" .
                'It is similiar to the integer-devision-operator for integers. It is eastimated a change of the value of `flagOkay`, ' . "\n" .
                'if the divisor is near zero (between ] -' . CalculateUtility::PRECISION_DELTA_EXPSILON . ' , ' .
                CalculateUtility::PRECISION_DELTA_EXPSILON . ' [' . "\n" .
                'caclulation-scheme: $result = ($dividend / $divisor - floor($dividend / $divisor)) * $divisor;',
                [
                    [true, '5', '\\', '4', '1'],
                    [true, '5', '\\', '-4', '-2'],
                    [true, '-5', '\\', '4', '-2'],
                    [true, '-5', '\\', '-4', '1'],
                    [true, '5.8', '\\', '4', '1'],
                    [true, '5.8', '\\', '-4', '-2'],
                    [true, '-5.8', '\\', '4', '-2'],
                    [true, '-5.8', '\\', '-4', '1'],
                    [true, '5.8', '\\', '4.3', '1'],
                    [true, '5.8', '\\', '-4.3', '-2'],
                    [true, '-5.8', '\\', '4.3', '-2'],
                    [true, '-5.8', '\\', '-4.3', '1'],
                    [true, '-3', '\\', '0.1', '-30'],
                    [true, '3', '\\', '0.1', '30'],
                    [true, '3', '\\', '0.01', '300'],
                    [true, '3', '\\', '0.001', '3000'],
                    [true, '3', '\\', '0.0001', '30000'],
                    [false, '3', '\\', CalculateUtility::PRECISION_DELTA_EXPSILON / 2, '0'],
                    [false, '-3', '\\', CalculateUtility::PRECISION_DELTA_EXPSILON / 2, '0'],
                    [false, '3', '\\', -CalculateUtility::PRECISION_DELTA_EXPSILON / 2, '0'],
                    [false, '-3', '\\', -CalculateUtility::PRECISION_DELTA_EXPSILON / 2, '0'],
                ]
            ],
            [
                '8. `#`-Check. Unknown operant should fail every time and the value of `flagOkay` should ever be false.',
                [
                    [false, '3', '?', '4', '0'],
                    [false, '-3', '?', '4', '0'],
                    [false, '3', '?', '-4', '0'],
                    [false, '-3', '?', '-4', '0'],
                    [false, '3.456', '?', '4.547', '0'],
                    [false, '-3.456', '?', '4.547', '0'],
                    [false, '3.456', '?', '-4.547', '0'],
                    [false, '-3.456', '?', '-4.547', '0'],
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderCalculatePairForGivenCalculationScenarios
     * @test
     */
    public function calculatePairForGivenCalculationScenarios($message, $expectsParams)
    {
        if (!isset($expectsParams) && empty($expectsParams)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {
            $reflection = new \ReflectionClass($this->subject);
            $reflection_flagOkay = $reflection->getProperty('flagOkay');
            $reflection_flagOkay->setAccessible(true);
            $count = 1;
            foreach ($expectsParams as $expectParamItem) {
                // reinit default-testvalue
                $reflection_flagOkay->setValue($this->subject, true);

                $result = $this->subject->calculatePair($expectParamItem[1], $expectParamItem[2], $expectParamItem[3]);

                $this->assertSame(
                    (int)$expectParamItem[4],
                    (int)$result,
                    'integer-test [' . $count . ']: ' . $message .
                    ' (' . $expectParamItem[1] . ' ' . $expectParamItem[2] . ' ' . $expectParamItem[3] . ')'
                );
                $this->assertSame(
                    round($expectParamItem[4], 6),
                    round($result, 6),
                    'round-test [' . $count . ']: ' . $message .
                    ' (' . $expectParamItem[1] . ' ' . $expectParamItem[2] . ' ' . $expectParamItem[3] . ')'
                );
                $this->assertSame(
                    (float)$expectParamItem[4],
                    (float)$result,
                    'float-test [' . $count . ']: ' . $message
                );

                $flagOkay = $reflection_flagOkay->getValue($this->subject);
                $this->assertSame(
                    $expectParamItem[0],
                    $flagOkay,
                    'test `flagOkay`: ' . $message .
                    ' (' . $expectParamItem[1] . ' ' . $expectParamItem[2] . ' ' . $expectParamItem[3] . ')'
                );
                $count++;
            }
        }
    }

    public function dataProviderDetectOperantiveNumberReturnNumberArrayFromMainStructureGivenVariousAllowedAndUnallowedNumbers()
    {
        return [
            [
                '1.a The teststring is empty. ',
                [
                    'resultArrayString' => '',
                    'flagOkay' => false,
                    'pointNext' => 0,
                    'pointByte' => '',
                ],
                [
                    'testString' => '',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '',
                ]
            ],
            [
                '2.a The teststring contains only a digit. ',
                [
                    'resultArrayString' => '6',
                    'flagOkay' => true,
                    'pointNext' => 1,
                    'pointByte' => '',
                ],
                [
                    'testString' => '6',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '6',
                ]
            ],
            [
                '3.a The teststring contains a integer. ',
                [
                    'resultArrayString' => '65',
                    'flagOkay' => true,
                    'pointNext' => 2,
                    'pointByte' => '',
                ],
                [
                    'testString' => '65',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '6',
                ]
            ],
            [
                '4.a The teststring contains a float number. ',
                [
                    'resultArrayString' => '6.458',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '',
                ],
                [
                    'testString' => '6.458',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '6',
                ]
            ],
            [
                '5.a The teststring contains a float number less than one. ',
                [
                    'resultArrayString' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '',
                ],
                [
                    'testString' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '0',
                ]
            ],
            [
                '6.a The teststring contains a float number with missing zero. ',
                [
                    'resultArrayString' => '.458',
                    'flagOkay' => true,
                    'pointNext' => 4,
                    'pointByte' => '',
                ],
                [
                    'testString' => '.458',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '.',
                ]
            ],
            [
                '1.b The teststring is empty. After the Number is a not numberd sign following',
                [
                    'resultArrayString' => '',
                    'flagOkay' => false,
                    'pointNext' => 0,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '~',
                ]
            ],
            [
                '2.b The teststring contains only a digit. After the Number is a not numberd sign following',
                [
                    'resultArrayString' => '6',
                    'flagOkay' => true,
                    'pointNext' => 1,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '6~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '6',
                ]
            ],
            [
                '3.b The teststring contains a integer. After the Number is a not numberd sign following',
                [
                    'resultArrayString' => '65',
                    'flagOkay' => true,
                    'pointNext' => 2,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '65~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '6',
                ]
            ],
            [
                '4.b The teststring contains a float number. After the Number is a not numberd sign following',
                [
                    'resultArrayString' => '6.458',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '6.458~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '6',
                ]
            ],
            [
                '5.b The teststring contains a float number less than one. After the Number is a not numberd sign following',
                [
                    'resultArrayString' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '0.458~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '0',
                ]
            ],
            [
                '6.b The teststring contains a float number with missing zero. After the Number is a not numberd sign following',
                [
                    'resultArrayString' => '.458',
                    'flagOkay' => true,
                    'pointNext' => 4,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '.458~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '.',
                ]
            ],
            [
                '1.c The teststring is empty. After the Number is a not numberd sign following. The term begins negative.',
                [
                    'resultArrayString' => '-',
                    'flagOkay' => false,
                    'pointNext' => 1,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '-~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '2.c The teststring contains only a digit. After the Number is a not numberd sign following. The term begins negative.',
                [
                    'resultArrayString' => '-6',
                    'flagOkay' => true,
                    'pointNext' => 2,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '-6~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '3.c The teststring contains a integer. After the Number is a not numberd sign following. The term begins negative.',
                [
                    'resultArrayString' => '-65',
                    'flagOkay' => true,
                    'pointNext' => 3,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '-65~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '4.c The teststring contains a float number. After the Number is a not numberd sign following. The term begins negative.',
                [
                    'resultArrayString' => '-6.458',
                    'flagOkay' => true,
                    'pointNext' => 6,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '-6.458~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '5.c The teststring contains a float number less than one. After the Number is a not numberd sign following. The term begins negative.',
                [
                    'resultArrayString' => '-0.458',
                    'flagOkay' => true,
                    'pointNext' => 6,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '-0.458~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '6.c The teststring contains a float number with missing zero. After the Number is a not numberd sign following. The term begins negative.',
                [
                    'resultArrayString' => '-.458',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '-.458~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '1.d The teststring is empty. The term begins negative.',
                [
                    'resultArrayString' => '-',
                    'flagOkay' => false,
                    'pointNext' => 1,
                    'pointByte' => '',
                ],
                [
                    'testString' => '-',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '2.d The teststring contains only a digit. The term begins negative.',
                [
                    'resultArrayString' => '-6',
                    'flagOkay' => true,
                    'pointNext' => 2,
                    'pointByte' => '',
                ],
                [
                    'testString' => '-6',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '3.d The teststring contains a integer. The term begins negative.',
                [
                    'resultArrayString' => '-65',
                    'flagOkay' => true,
                    'pointNext' => 3,
                    'pointByte' => '',
                ],
                [
                    'testString' => '-65',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '4.d The teststring contains a float number. The term begins negative.',
                [
                    'resultArrayString' => '-6.458',
                    'flagOkay' => true,
                    'pointNext' => 6,
                    'pointByte' => '',
                ],
                [
                    'testString' => '-6.458',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '5.d The teststring contains a float number less than one. The term begins negative.',
                [
                    'resultArrayString' => '-0.458',
                    'flagOkay' => true,
                    'pointNext' => 6,
                    'pointByte' => '',
                ],
                [
                    'testString' => '-0.458',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '6.d The teststring contains a float number with missing zero. The term begins negative.',
                [
                    'resultArrayString' => '-.458',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '',
                ],
                [
                    'testString' => '-.458',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '1.e The teststring is empty. The term begins negative.Add two more token at the beginning',
                [
                    'resultArrayString' => 'ac-',
                    'flagOkay' => false,
                    'pointNext' => 3,
                    'pointByte' => '+',
                ],
                [
                    'testString' => 'ac-+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => 'a',
                ]
            ],
            [
                '2.e The teststring contains only a digit. The term begins negative.Add two more token at the beginning',
                [
                    'resultArrayString' => 'ac-6',
                    'flagOkay' => true,
                    'pointNext' => 4,
                    'pointByte' => '+',
                ],
                [
                    'testString' => 'ac-6+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => 'a',
                ]
            ],
            [
                '3.e The teststring contains a integer. The term begins negative.Add two more token at the beginning',
                [
                    'resultArrayString' => 'ac-65',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '+',
                ],
                [
                    'testString' => 'ac-65+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => 'a',
                ]
            ],
            [
                '4.e The teststring contains a float number. The term begins negative.Add two more token at the beginning',
                [
                    'resultArrayString' => 'ac-6.458',
                    'flagOkay' => true,
                    'pointNext' => 8,
                    'pointByte' => '+',
                ],
                [
                    'testString' => 'ac-6.458+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => 'a',
                ]
            ],
            [
                '5.e The teststring contains a float number less than one. The term begins negative.Add two more token at the beginning',
                [
                    'resultArrayString' => 'ac-0.458',
                    'flagOkay' => true,
                    'pointNext' => 8,
                    'pointByte' => '+',
                ],
                [
                    'testString' => 'ac-0.458+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => 'a',
                ]
            ],
            [
                '6.e The teststring contains a float number with missing zero. The term begins negative.Add two more token at the beginning',
                [
                    'resultArrayString' => 'ac-.458',
                    'flagOkay' => true,
                    'pointNext' => 7,
                    'pointByte' => '+',
                ],
                [
                    'testString' => 'ac-.458+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => 'a',
                ]
            ],
            [
                '1.f The teststring is empty. The term begins negative.Add two more token at the beginning. Start in the middle of the array.',
                [
                    'resultArrayString' => 'ac-',
                    'flagOkay' => false,
                    'pointNext' => 8,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '~~~~~ac-+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '2.f The teststring contains only a digit. The term begins negative.Add two more token at the beginning. Start in the middle of the array.',
                [
                    'resultArrayString' => 'ac-6',
                    'flagOkay' => true,
                    'pointNext' => 9,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '~~~~~ac-6+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '3.f The teststring contains a integer. The term begins negative.Add two more token at the beginning. Start in the middle of the array.',
                [
                    'resultArrayString' => 'ac-65',
                    'flagOkay' => true,
                    'pointNext' => 10,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '~~~~~ac-65+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '4.f The teststring contains a float number. The term begins negative.Add two more token at the beginning. Start in the middle of the array.',
                [
                    'resultArrayString' => 'ac-6.458',
                    'flagOkay' => true,
                    'pointNext' => 13,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '~~~~~ac-6.458+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '5.f The teststring contains a float number less than one. The term begins negative.Add two more token at the beginning. Start in the middle of the array.',
                [
                    'resultArrayString' => 'ac-0.458',
                    'flagOkay' => true,
                    'pointNext' => 13,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '~~~~~ac-0.458+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '6.f The teststring contains a float number with missing zero. The term begins negative.Add two more token at the beginning. Start in the middle of the array.',
                [
                    'resultArrayString' => 'ac-.458',
                    'flagOkay' => true,
                    'pointNext' => 12,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '~~~~~ac-.458+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '10 The teststring contains a float number two dots and a missing zero. The term begins negative.Add two more token at the beginning',
                [
                    'resultArrayString' => 'ac-.4.',
                    'flagOkay' => false,
                    'pointNext' => 11,
                    'pointByte' => '',
                ],
                [
                    'testString' => '~~~~~ac-.4.58+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '11 The teststring contains a float number two dots. The term begins negative.Add two more token at the beginning',
                [
                    'resultArrayString' => 'ac-0.4.',
                    'flagOkay' => false,
                    'pointNext' => 12,
                    'pointByte' => '',
                ],
                [
                    'testString' => '~~~~~ac-0.4.58+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '12 The teststring contains a pure float number two dots. ',
                [
                    'resultArrayString' => '0.4.',
                    'flagOkay' => false,
                    'pointNext' => 4,
                    'pointByte' => '',
                ],
                [
                    'testString' => '0.4.58',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '0',
                ]
            ],
            [
                '13 The teststring contains a pure float number two dots and no starting digit. ',
                [
                    'resultArrayString' => '.4.',
                    'flagOkay' => false,
                    'pointNext' => 3,
                    'pointByte' => '',
                ],
                [
                    'testString' => '.4.58',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '.',
                ]
            ],
            [
                '13. The teststring contains a pure float number one dots and no starting digit. ',
                [
                    'resultArrayString' => '.458',
                    'flagOkay' => true,
                    'pointNext' => 4,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '.458+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '.',
                ]
            ],

            // Fehler ..
            //

        ];
    }

    /**
     * @dataProvider dataProviderDetectOperantiveNumberReturnNumberArrayFromMainStructureGivenVariousAllowedAndUnallowedNumbers
     * @test
     */
    public function detectOperantiveNumberReturnNumberArrayFromMainStructure($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {

            // prepäre internal-settings for usage of analyseNUmber
            $this->subject->resetForCalculation($params['testString']);

            $reflection = new \ReflectionClass($this->subject);
            $reflection_flagOkay = $reflection->getProperty('flagOkay');
            $reflection_flagOkay->setAccessible(true);
            $flagOkay = $reflection_flagOkay->setValue($this->subject, $params['flagOkay']);
            $reflection_pointNext = $reflection->getProperty('pointNext');
            $reflection_pointNext->setAccessible(true);
            $pointNext = $reflection_pointNext->setValue($this->subject, $params['pointNext']);
            $reflection_pointByte = $reflection->getProperty('pointByte');
            $reflection_pointByte->setAccessible(true);
            $pointByte = $reflection_pointByte->setValue($this->subject, $params['pointByte']);

            $tokenNumberResult = $this->subject->detectOperantiveNumber();

            $flagOkay = $reflection_flagOkay->getValue($this->subject);
            $pointNext = $reflection_pointNext->getValue($this->subject);
            $pointByte = $reflection_pointByte->getValue($this->subject);

            $this->assertSame(
                true,
                is_array($tokenNumberResult),
                'test array-type of result: ' . $message . ' (' . $params['testString']  . ')'
            );
            $this->assertSame(
                $expects['resultArrayString'],
                implode('',$tokenNumberResult),
                'test array-type of result: ' . $message . ' (' . $params['testString']  .' => '.implode('',$tokenNumberResult). ')'
            );
            $this->assertSame(
                $expects['pointNext'],
                $pointNext,
                'internal pointNext: ' . $message . ' (' . $params['pointNext'] . ' -> ' .$expects['pointNext']. ')'
            );
            $this->assertSame(
                $expects['flagOkay'],
                $flagOkay,
                'internal flagOkay: ' . $message . ' (' . $params['flagOkay'] . ' -> ' .$expects['flagOkay']. ')'
            );
            $this->assertSame(
                $expects['pointByte'],
                $pointByte,
                'internal pointByte: ' . $message . ' (' . $params['pointByte'] . ' -> ' .$expects['pointByte']. ')'
            );
        }
    }

    public function dataProviderDefineSecondNumberAfterOperatorInTermReturnNumberArrayFromMainStructureGivenVariousAllowedAndUnallowedNumbers()
    {
        return [
            [
                '1.a The teststring is empty. ',
                [
                    'numberResult' => '',
                    'flagOkay' => false,
                    'pointNext' => 0,
                    'pointByte' => '',
                ],
                [
                    'testString' => '',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '',
                ]
            ],
            [
                '2.a The teststring contains only a digit. ',
                [
                    'numberResult' => '6',
                    'flagOkay' => true,
                    'pointNext' => 1,
                    'pointByte' => '',
                ],
                [
                    'testString' => '6',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '6',
                ]
            ],
            [
                '3.a The teststring contains a integer. ',
                [
                    'numberResult' => '65',
                    'flagOkay' => true,
                    'pointNext' => 2,
                    'pointByte' => '',
                ],
                [
                    'testString' => '65',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '6',
                ]
            ],
            [
                '4.a The teststring contains a float number. ',
                [
                    'numberResult' => '6.458',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '',
                ],
                [
                    'testString' => '6.458',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '6',
                ]
            ],
            [
                '5.a The teststring contains a float number less than one. ',
                [
                    'numberResult' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '',
                ],
                [
                    'testString' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '0',
                ]
            ],
            [
                '6.a The teststring contains a float number with missing zero. ',
                [
                    'numberResult' => '.458',
                    'flagOkay' => true,
                    'pointNext' => 4,
                    'pointByte' => '',
                ],
                [
                    'testString' => '.458',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '.',
                ]
            ],
            [
                '1.b The teststring is empty. After the Number is a not numberd sign following',
                [
                    'numberResult' => '',
                    'flagOkay' => false,
                    'pointNext' => 0,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '~',
                ]
            ],
            [
                '2.b The teststring contains only a digit. After the Number is a not numberd sign following',
                [
                    'numberResult' => '6',
                    'flagOkay' => true,
                    'pointNext' => 1,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '6~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '6',
                ]
            ],
            [
                '3.b The teststring contains a integer. After the Number is a not numberd sign following',
                [
                    'numberResult' => '65',
                    'flagOkay' => true,
                    'pointNext' => 2,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '65~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '6',
                ]
            ],
            [
                '4.b The teststring contains a float number. After the Number is a not numberd sign following',
                [
                    'numberResult' => '6.458',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '6.458~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '6',
                ]
            ],
            [
                '5.b The teststring contains a float number less than one. After the Number is a not numberd sign following',
                [
                    'numberResult' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '0.458~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '0',
                ]
            ],
            [
                '6.b The teststring contains a float number with missing zero. After the Number is a not numberd sign following',
                [
                    'numberResult' => '.458',
                    'flagOkay' => true,
                    'pointNext' => 4,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '.458~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '.',
                ]
            ],
            [
                '1.c The teststring is empty. After the Number is a not numberd sign following. The term begins negative.',
                [
                    'numberResult' => '-',
                    'flagOkay' => false,
                    'pointNext' => 1,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '-~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '2.c The teststring contains only a digit. After the Number is a not numberd sign following. The term begins negative.',
                [
                    'numberResult' => '-6',
                    'flagOkay' => true,
                    'pointNext' => 2,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '-6~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '3.c The teststring contains a integer. After the Number is a not numberd sign following. The term begins negative.',
                [
                    'numberResult' => '-65',
                    'flagOkay' => true,
                    'pointNext' => 3,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '-65~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '4.c The teststring contains a float number. After the Number is a not numberd sign following. The term begins negative.',
                [
                    'numberResult' => '-6.458',
                    'flagOkay' => true,
                    'pointNext' => 6,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '-6.458~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '5.c The teststring contains a float number less than one. After the Number is a not numberd sign following. The term begins negative.',
                [
                    'numberResult' => '-0.458',
                    'flagOkay' => true,
                    'pointNext' => 6,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '-0.458~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '6.c The teststring contains a float number with missing zero. After the Number is a not numberd sign following. The term begins negative.',
                [
                    'numberResult' => '-.458',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '~',
                ],
                [
                    'testString' => '-.458~',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '1.d The teststring is empty. The term begins negative.',
                [
                    'numberResult' => '-',
                    'flagOkay' => false,
                    'pointNext' => 1,
                    'pointByte' => '',
                ],
                [
                    'testString' => '-',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '2.d The teststring contains only a digit. The term begins negative.',
                [
                    'numberResult' => '-6',
                    'flagOkay' => true,
                    'pointNext' => 2,
                    'pointByte' => '',
                ],
                [
                    'testString' => '-6',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '3.d The teststring contains a integer. The term begins negative.',
                [
                    'numberResult' => '-65',
                    'flagOkay' => true,
                    'pointNext' => 3,
                    'pointByte' => '',
                ],
                [
                    'testString' => '-65',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '4.d The teststring contains a float number. The term begins negative.',
                [
                    'numberResult' => '-6.458',
                    'flagOkay' => true,
                    'pointNext' => 6,
                    'pointByte' => '',
                ],
                [
                    'testString' => '-6.458',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '5.d The teststring contains a float number less than one. The term begins negative.',
                [
                    'numberResult' => '-0.458',
                    'flagOkay' => true,
                    'pointNext' => 6,
                    'pointByte' => '',
                ],
                [
                    'testString' => '-0.458',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '6.d The teststring contains a float number with missing zero. The term begins negative.',
                [
                    'numberResult' => '-.458',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '',
                ],
                [
                    'testString' => '-.458',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '-',
                ]
            ],
            [
                '1.e The teststring is empty. The term begins negative.Add two more token at the beginning',
                [
                    'numberResult' => '',
                    'flagOkay' => false,
                    'pointNext' => 2,
                    'pointByte' => '+',
                ],
                [
                    'testString' => 'a-+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => 'a',
                ]
            ],
            [
                '2.e The teststring contains only a digit. The term begins negative.Add two more token at the beginning',
                [
                    'numberResult' => '6',
                    'flagOkay' => true,
                    'pointNext' => 3,
                    'pointByte' => '+',
                ],
                [
                    'testString' => 'a-6+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => 'a',
                ]
            ],
            [
                '3.e The teststring contains a integer. The term begins negative.Add two more token at the beginning',
                [
                    'numberResult' => '65',
                    'flagOkay' => true,
                    'pointNext' => 4,
                    'pointByte' => '+',
                ],
                [
                    'testString' => 'a-65+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => 'a',
                ]
            ],
            [
                '4.e The teststring contains a float number. The term begins negative.Add two more token at the beginning',
                [
                    'numberResult' => '6.458',
                    'flagOkay' => true,
                    'pointNext' => 7,
                    'pointByte' => '+',
                ],
                [
                    'testString' => 'a-6.458+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => 'a',
                ]
            ],
            [
                '5.e The teststring contains a float number less than one. The term begins negative.Add two more token at the beginning',
                [
                    'numberResult' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 7,
                    'pointByte' => '+',
                ],
                [
                    'testString' => 'a-0.458+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => 'a',
                ]
            ],
            [
                '6.e The teststring contains a float number with missing zero. The term begins negative.Add two more token at the beginning',
                [
                    'numberResult' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 6,
                    'pointByte' => '+',
                ],
                [
                    'testString' => 'a-.458+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => 'a',
                ]
            ],
            [
                '1.f The teststring is empty. The term begins negative.Add two more token at the beginning. Start in the middle of the array.',
                [
                    'numberResult' => '0',
                    'flagOkay' => false,
                    'pointNext' => 7,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '~~~~~a-+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '2.f The teststring contains only a digit. The term begins negative.Add two more token at the beginning. Start in the middle of the array.',
                [
                    'numberResult' => '6',
                    'flagOkay' => true,
                    'pointNext' => 8,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '~~~~~a-6+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '3.f The teststring contains a integer. The term begins negative.Add two more token at the beginning. Start in the middle of the array.',
                [
                    'numberResult' => '65',
                    'flagOkay' => true,
                    'pointNext' => 9,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '~~~~~a-65+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '4.f The teststring contains a float number. The term begins negative.Add two more token at the beginning. Start in the middle of the array.',
                [
                    'numberResult' => '6.458',
                    'flagOkay' => true,
                    'pointNext' => 12,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '~~~~~a-6.458+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '5.f The teststring contains a float number less than one. The term begins negative.Add two more token at the beginning. Start in the middle of the array.',
                [
                    'numberResult' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 12,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '~~~~~a-0.458+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '6.f The teststring contains a float number with missing zero. The term begins negative.Add two more token at the beginning. Start in the middle of the array.',
                [
                    'numberResult' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 11,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '~~~~~a-.458+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '1.g The teststring is empty. The term begins negative.Add two more token at the beginning. Start in the middle of the array. The number is included in brackets.',
                [
                    'numberResult' => '0',
                    'flagOkay' => false,
                    'pointNext' => 8,
                    'pointByte' => ')',
                ],
                [
                    'testString' => '~~~~~(a-)+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '2.g The teststring contains only a digit. The term begins negative.Add two more token at the beginning. Start in the middle of the array. The number is included in brackets.',
                [
                    'numberResult' => '6',
                    'flagOkay' => true,
                    'pointNext' => 9,
                    'pointByte' => ')',
                ],
                [
                    'testString' => '~~~~~(a-6)+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '3.g The teststring contains a integer. The term begins negative.Add two more token at the beginning. Start in the middle of the array. The number is included in brackets.',
                [
                    'numberResult' => '65',
                    'flagOkay' => true,
                    'pointNext' => 10,
                    'pointByte' => ')',
                ],
                [
                    'testString' => '~~~~~(a-65)+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '4.g The teststring contains a float number. The term begins negative.Add two more token at the beginning. Start in the middle of the array. The number is included in brackets.',
                [
                    'numberResult' => '6.458',
                    'flagOkay' => true,
                    'pointNext' => 13,
                    'pointByte' => ')',
                ],
                [
                    'testString' => '~~~~~(a-6.458)+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '5.g The teststring contains a float number less than one. The term begins negative.Add two more token at the beginning. Start in the middle of the array. The number is included in brackets.',
                [
                    'numberResult' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 13,
                    'pointByte' => ')',
                ],
                [
                    'testString' => '~~~~~(a-0.458)+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '6.g The teststring contains a float number with missing zero. The term begins negative.Add two more token at the beginning. Start in the middle of the array. The number is included in brackets.',
                [
                    'numberResult' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 12,
                    'pointByte' => ')',
                ],
                [
                    'testString' => '~~~~~(a-.458)+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '10 The teststring contains a float number two dots and a missing zero. The term begins negative.Add two more token at the beginning',
                [
                    'numberResult' => '',
                    'flagOkay' => false,
                    'pointNext' => 10,
                    'pointByte' => '',
                ],
                [
                    'testString' => '~~~~~a-.4.58+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '11 The teststring contains a float number two dots. The term begins negative.Add two more token at the beginning',
                [
                    'numberResult' => '0',
                    'flagOkay' => false,
                    'pointNext' => 11,
                    'pointByte' => '',
                ],
                [
                    'testString' => '~~~~~a-0.4.58+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => 'a',
                ]
            ],
            [
                '12 The teststring contains a pure float number two dots. ',
                [
                    'numberResult' => '0',
                    'flagOkay' => false,
                    'pointNext' => 4,
                    'pointByte' => '',
                ],
                [
                    'testString' => '0.4.58',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '0',
                ]
            ],
            [
                '13 The teststring contains a pure float number two dots and no starting digit. ',
                [
                    'numberResult' => '0',
                    'flagOkay' => false,
                    'pointNext' => 3,
                    'pointByte' => '',
                ],
                [
                    'testString' => '.4.58',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '.',
                ]
            ],
            [
                '13. The teststring contains a pure float number one dots and no starting digit. ',
                [
                    'numberResult' => '0.458',
                    'flagOkay' => true,
                    'pointNext' => 4,
                    'pointByte' => '+',
                ],
                [
                    'testString' => '.458+',
                    'flagOkay' => true,
                    'pointNext' => 0,
                    'pointByte' => '.',
                ]
            ],
            [
                '14. The teststring contains a pure float number one dots with som prefix text and only an opening-bracket, what cause an error. ',
                [
                    'numberResult' => '0',
                    'flagOkay' => false,
                    'pointNext' => 11,
                    'pointByte' => '',
                ],
                [
                    'testString' => '~~~~~(.458+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '(',
                ]
            ],
            [
                '15. The teststring contains a pure float number one dots with som prefix text and only an opening-bracket, what cause an error. ',
                [
                    'numberResult' => '0',
                    'flagOkay' => false,
                    'pointNext' => 12,
                    'pointByte' => '',
                ],
                [
                    'testString' => '~~~~~(0.458+',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '(',
                ]
            ],


        ];
    }

    /**
     * @dataProvider dataProviderDefineSecondNumberAfterOperatorInTermReturnNumberArrayFromMainStructureGivenVariousAllowedAndUnallowedNumbers
     * @test
     */
    public function defineSecondNumberAfterOperatorInTermReturnNumberArrayFromMainStructure($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {

            // prepäre internal-settings for usage of analyseNUmber
            $this->subject->resetForCalculation($params['testString']);

            $reflection = new \ReflectionClass($this->subject);
            $reflection_flagOkay = $reflection->getProperty('flagOkay');
            $reflection_flagOkay->setAccessible(true);
            $flagOkay = $reflection_flagOkay->setValue($this->subject, $params['flagOkay']);
            $reflection_pointNext = $reflection->getProperty('pointNext');
            $reflection_pointNext->setAccessible(true);
            $pointNext = $reflection_pointNext->setValue($this->subject, $params['pointNext']);
            $reflection_pointByte = $reflection->getProperty('pointByte');
            $reflection_pointByte->setAccessible(true);
            $pointByte = $reflection_pointByte->setValue($this->subject, $params['pointByte']);

            $numberResult = $this->subject->defineSecondNumberAfterOperatorInTerm();

            $flagOkay = $reflection_flagOkay->getValue($this->subject);
            $pointNext = $reflection_pointNext->getValue($this->subject);
            $pointByte = $reflection_pointByte->getValue($this->subject);

            $this->assertSame(
                (int)$expects['numberResult'],
                (int)$numberResult,
                'test resultnumber converted to integer: ' . $message . ' (' . $params['testString']  .' => '.$numberResult. ')'
            );
            $this->assertSame(
                (float)$expects['numberResult'],
                (float)$numberResult,
                'test resultnumber converted to float: ' . $message . ' (' . $params['testString']  .' => '.$numberResult. ')'
            );
            $this->assertSame(
                $expects['pointNext'],
                $pointNext,
                'internal pointNext: ' . $message . ' (' . $params['pointNext'] . ' -> ' .$expects['pointNext']. ')'
            );
            $this->assertSame(
                $expects['flagOkay'],
                $flagOkay,
                'internal flagOkay: ' . $message . ' (' . $params['flagOkay'] . ' -> ' .$expects['flagOkay']. ')'
            );
            $this->assertSame(
                $expects['pointByte'],
                $pointByte,
                'internal pointByte: ' . $message . ' (' . $params['pointByte'] . ' -> ' .$expects['pointByte']. ')'
            );
        }
    }

    public function dataProviderDefineFirstNumberInTermReturnNumberArrayFromMainStructureGivenVariousAllowedAndUnallowedNumbersTwo()
    {
        return [
            [
                '1. The teststring contains a pure float number one dots with som prefix text and only an opening-bracket, what cause an error. ',
                [
                    'numberResult' => '3.4567',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '(',
                ],
                [
                    'testString' => '~~~~~(.458+',
                    'testParameter' => '3.4567',
                    'flagOkay' => true,
                    'pointNext' => 5,
                    'pointByte' => '(',
                ]
            ],


        ];
    }

    /**
     * @dataProvider dataProviderDefineFirstNumberInTermReturnNumberArrayFromMainStructureGivenVariousAllowedAndUnallowedNumbersTwo
     * @test
     */
    public function defineFirstNumberInTermReturnNumberArrayFromMainStructureTwo($message, $expects, $params)
    {
        if (!isset($expects) && empty($expects)) {
            $this->assertSame(true, true, 'empty-data at the end of the provider');
        } else {

            // prepäre internal-settings for usage of analyseNUmber
            $this->subject->resetForCalculation($params['testString']);

            $reflection = new \ReflectionClass($this->subject);
            $reflection_flagOkay = $reflection->getProperty('flagOkay');
            $reflection_flagOkay->setAccessible(true);
            $flagOkay = $reflection_flagOkay->setValue($this->subject, $params['flagOkay']);
            $reflection_pointNext = $reflection->getProperty('pointNext');
            $reflection_pointNext->setAccessible(true);
            $pointNext = $reflection_pointNext->setValue($this->subject, $params['pointNext']);
            $reflection_pointByte = $reflection->getProperty('pointByte');
            $reflection_pointByte->setAccessible(true);
            $pointByte = $reflection_pointByte->setValue($this->subject, $params['pointByte']);

            $numberResult = $this->subject->defineFirstNumberInTerm($params['testParameter']);

            $flagOkay = $reflection_flagOkay->getValue($this->subject);
            $pointNext = $reflection_pointNext->getValue($this->subject);
            $pointByte = $reflection_pointByte->getValue($this->subject);

            $this->assertSame(
                intval($expects['numberResult']),
                intval($numberResult),
                'test resultnumber converted to integer: ' . $message . ' (' . $params['testString']  .' => '.$numberResult. ')'
            );
            $this->assertSame(
                floatval($expects['numberResult']),
                floatval($numberResult),
                'test resultnumber converted to float: ' . $message . ' (' . $params['testString']  .' => '.$numberResult. ')'
            );
            $this->assertSame(
                $expects['pointNext'],
                $pointNext,
                'internal pointNext: ' . $message . ' (' . $params['pointNext'] . ' -> ' .$expects['pointNext']. ')'
            );
            $this->assertSame(
                $expects['flagOkay'],
                $flagOkay,
                'internal flagOkay: ' . $message . ' (' . $params['flagOkay'] . ' -> ' .$expects['flagOkay']. ')'
            );
            $this->assertSame(
                $expects['pointByte'],
                $pointByte,
                'internal pointByte: ' . $message . ' (' . $params['pointByte'] . ' -> ' .$expects['pointByte']. ')'
            );
        }
    }


    /**
     * mock the internal method nextToken and its influence on the global class-variable flagOkay
     * @test
     */
    public function defineFirstNumberInTermCallMockedMethodDefineSecondNumberAfterOperatorInTermIfNoParameterIsDefined()
    {
        /** @var CalculateUtility $calculateUtility */
        $calculateUtility = new CalculateUtility();

        $testString = '0123456789';
        $arrayStartWithOperator = ['0', '1', '2', '3', '4', '0', '5', '6', '7', '8', '9', '0'];

        $calculateUtility = $this->getMockBuilder(CalculateUtility::class)
            ->setMethods(['defineSecondNumberAfterOperatorInTerm'])
            ->getMock();

        $calculateUtility->expects($this->once())
            ->method('defineSecondNumberAfterOperatorInTerm');
        // reset to default-Values
        $calculateUtility->resetForCalculation($testString);

        $operator = $calculateUtility->defineFirstNumberInTerm();

        $message = "1. The method `defineSecondNumberAfterOperatorInTerm` is called from `defineFirstNumberInTerm`, '.
        ' which is called with no parameter in the test.";

    }


    public function dataProviderDefineFirstNumberInTermCallMockedMethodDefineSecondNumberAfterOperatorInTermIfNoParameterIsDefined()
    {
        return [
            [
                '1.a The Term ended at the last element',
                ['result' => 15],
                [
                    'first' => 5,
                    'operator' => '*',
                    'priority' => CalculateUtility::OP_PRIO_MULTIPLICATION,
                    'second' => 3,
                    'priorCount' => 0,
                    'priorFirst' => 2,
                    'priorSecond' => null,
                    'arriveEndCount' => 1,
                    'arriveEnd' => true,
                    'arriveBracketCount' => 0,
                    'arriveBracket' => false,
                    'calculateOperatorCount' => 0,
                    'calculateOperatorValue' => null,
                ],
            ],
            [
                '1.b The Term ended an a closing bracket.',
                ['result' => 15],
                [
                    'first' => 5,
                    'operator' => '*',
                    'priority' => CalculateUtility::OP_PRIO_MULTIPLICATION,
                    'second' => 3,
                    'priorCount' => 0,
                    'priorFirst' => 2,
                    'priorSecond' => null,
                    'arriveEndCount' => 1,
                    'arriveEnd' => false,
                    'arriveBracketCount' => 1,
                    'arriveBracket' => true,
                    'calculateOperatorCount' => 0,
                    'calculateOperatorValue' => null,
                ],
            ],
            [
                '2.a The Term ended with an new operator with equal priority.',
                ['result' => 15],
                [
                    'first' => 5,
                    'operator' => '*',
                    'priority' => CalculateUtility::OP_PRIO_MULTIPLICATION,
                    'second' => 3,
                    'priorCount' => 1,
                    'priorFirst' => 2,
                    'priorSecond' => 2,
                    'arriveEndCount' => 1,
                    'arriveEnd' => false,
                    'arriveBracketCount' => 1,
                    'arriveBracket' => false,
                    'calculateOperatorCount' => 0,
                    'calculateOperatorValue' => null,
                ],
            ],
            [
                '2.b The Term ended with an new operator with lowerpriority.',
                ['result' => 15],
                [
                    'first' => 5,
                    'operator' => '*',
                    'priority' => CalculateUtility::OP_PRIO_MULTIPLICATION,
                    'second' => 3,
                    'priorCount' => 1,
                    'priorFirst' => 2,
                    'priorSecond' => 1,
                    'arriveEndCount' => 1,
                    'arriveEnd' => false,
                    'arriveBracketCount' => 1,
                    'arriveBracket' => false,
                    'calculateOperatorCount' => 0,
                    'calculateOperatorValue' => null,
                ],
            ],
            [
                '2.c The Term ended with an new operator with higher priority and mocked call of `calculateOperator `.',
                ['result' => 8],
                [
                    'first' => 5,
                    'operator' => '+',
                    'priority' => CalculateUtility::OP_PRIO_PLUS,
                    'second' => 3,
                    'priorCount' => 1,
                    'priorFirst' => 1,
                    'priorSecond' => 2,
                    'arriveEndCount' => 1,
                    'arriveEnd' => false,
                    'arriveBracketCount' => 1,
                    'arriveBracket' => false,
                    'calculateOperatorCount' => 0,
                    'calculateOperatorValue' => null,
                ],
            ],
        ];
    }

    /**
     * remark: test is only conditionally reliable, because the structure is fully mocked
     *
     * mock the internal methods for teh first case
     * @dataProvider dataProviderDefineFirstNumberInTermCallMockedMethodDefineSecondNumberAfterOperatorInTermIfNoParameterIsDefined
     * @test
     */
    public function calculatePairAndInitPerhapsLazyRecursionCallMockedMethodToCheckDifferentSituations($message, $expects, $params)
    {
        /** @var CalculateUtility $calculateUtility */
        $calculateUtility = new CalculateUtility();


        $calculateUtility = $this->getMockBuilder(CalculateUtility::class)
            ->setMethods(['definePriorityOperator', 'testEndOfCalculationArrived', 'testForEndBracket','calculateOperator'])
            ->getMock();

        // the dataprovider had some cases, where two different return-values are needed.
        $calculateUtility->expects($this->exactly($params['priorCount']))
            ->method('definePriorityOperator')
            ->with($this->anything())
            ->will($this->onConsecutiveCalls($params['priorFirst'],$params['priorSecond']));

        $calculateUtility->expects($this->exactly($params['arriveEndCount']))
            ->method('testEndOfCalculationArrived')
            ->willReturn($params['arriveEnd']);
        $calculateUtility->expects($this->exactly($params['arriveBracketCount']))
            ->method('testForEndBracket')
            ->willReturn($params['arriveBracket']);

        $calculateUtility->expects($this->exactly($params['calculateOperatorCount']))
            ->method('calculateOperator')
            ->willReturn($params['calculateOperatorValue']);



        $result = $calculateUtility->calculatePairAndInitPerhapsLazyRecursion(
            $params['first'],
            $params['operator'],
            $params['priority'],
            $params['second']
        );

        $this->assertSame(
            $expects['result'],
            $result,
            $message
        );

    }


    public function dataProviderCalculateReturnResultIfFlagOkayTrueIfNoErrorDetectedAndEverythingAnalysedOtherwiseRetunrnFalse()
    {
        return [
            [
                '1.a The flag is okay and the other parameters sign the end of the calculationstring The method will return the estimated result.',
                ['result' => 15],
                [
                    'flagOkay' => true,
                    'calculateOperatorCount' => 1,
                    'calculateOperatorValue' => 15,
                    'pointNext' => 15,
                    'calcByteLength' => 15,
                ],
            ],
            [
                '2.a The flag is false and the other parameters sign the end of the calculationstring The method will return a `false`.',
                ['result' => false],
                [
                    'flagOkay' => false,
                    'calculateOperatorCount' => 0,
                    'calculateOperatorValue' => 15,
                    'pointNext' => 15,
                    'calcByteLength' => 15,
                ],
            ],
            [
                '2.a The flag is okay, but the other parameters sign NOT the end of the calculationstring. (one byte is missing) The method will return a `false`.',
                ['result' => false],
                [
                    'flagOkay' => true,
                    'calculateOperatorCount' => 1,
                    'calculateOperatorValue' => 15,
                    'pointNext' => 14,
                    'calcByteLength' => 15,
                ],
            ],
        ];
    }


    /**
     * mock the internal methods for teh first case
     * @dataProvider dataProviderCalculateReturnResultIfFlagOkayTrueIfNoErrorDetectedAndEverythingAnalysedOtherwiseRetunrnFalse
     * @test
     */
    public function calculateReturnResultIfFlagOkayTrueIfNoErrorDetectedAndEverythingAnalysedOtherwiseRetunrnFalse($message, $expects, $params)
    {
        /** @var CalculateUtility $calculateUtility */
        $calculateUtility = new CalculateUtility();

        $calculateUtility = $this->getMockBuilder(CalculateUtility::class)
            ->setMethods(['calculatePrepare', 'resetForCalculation', 'calculateOperator'])
            ->getMock();

        $calculateUtility->expects($this->once())
            ->method('calculatePrepare')
            ->willReturn('normalized');
        $calculateUtility->expects($this->once())
            ->method('resetForCalculation');

        $calculateUtility->expects($this->exactly($params['calculateOperatorCount']))
            ->method('calculateOperator')
            ->willReturn($params['calculateOperatorValue']);

        $reflection = new \ReflectionClass($calculateUtility);
        $reflection_flagOkay = $reflection->getProperty('flagOkay');
        $reflection_flagOkay->setAccessible(true);
        $reflection_flagOkay->setValue($calculateUtility, $params['flagOkay']);

        $reflection_pointNext = $reflection->getProperty('pointNext');
        $reflection_pointNext->setAccessible(true);
        $reflection_pointNext->setValue($calculateUtility, $params['pointNext']);

        $reflection_calcByteLength = $reflection->getProperty('calcByteLength');
        $reflection_calcByteLength->setAccessible(true);
        $reflection_calcByteLength->setValue($calculateUtility, $params['calcByteLength']);

        $result = $calculateUtility->calculate('DummyStringForMockedMethod');
        $this->assertSame(
            floatval($expects['result']),
            floatval($result),
            $message
        );

    }


    public function dataProviderCalculateOperatorReturnResultIfFlagOkayTrueIfNoErrorDetectedAndEverythingAnalysedOtherwiseRetunrnFalse()
    {
        return [
            [
                '1 The flagOkay is set for some reason to false. So the result is zero.',
                [
                    'result' => 0,
                    'flagOkay' => false,
                    'pointByte' => '',
                ],
                [
                    'calculateOperatorParam' => null,
                    'priority' => null,
                    'start' => '',
                    'flagOkay' => false,
                ],
            ],
            [
                '2 The flagOkay is set for some reason to true and the array is empty. So the result is zero and the flag is set to fales.',
                [
                    'result' => 0,
                    'flagOkay' => false,
                    'pointByte' => '',
                ],
                [
                    'calculateOperatorParam' => null,
                    'priority' => null,
                    'start' => '',
                    'flagOkay' => true,
                ],
            ],
            [
                '3 The flagOkay is set for some reason to true and the array contasins zero. So the result is zero and the flag is set to true.',
                [
                    'result' => 0,
                    'flagOkay' => true,
                    'pointByte' => '',
                ],
                [
                    'calculateOperatorParam' => null,
                    'start' => '0',
                    'priority' => null,
                    'flagOkay' => true,
                ],
            ],
            [
                '3.b. The flagOkay is set for some reason to true and the array contains a number. So the result is zero and the flag is set to true.',
                [
                    'result' => 25,
                    'flagOkay' => true,
                    'pointByte' => '',
                ],
                [
                    'calculateOperatorParam' => null,
                    'start' => '25',
                    'priority' => null,
                    'flagOkay' => true,
                ],
            ],
            [
                '3.c. The flagOkay is set for some reason to true and the first number is given, but the array contains no number. '.
                'So the result is calculated to 25 and the flag is set to true.',
                [
                    'result' => 25,
                    'flagOkay' => true,
                    'pointByte' => ''
                ],
                [
                    'calculateOperatorParam' => 25,
                    'start' => '',
                    'priority' => CalculateUtility::OP_PRIO_LOWEST,
                    'flagOkay' => true,
                ],
            ],
            [
                '3.d. The flagOkay is set for some reason to true and no first number is given, but the array contains no number, ended by a closing bracket. '.
                'So the result is calculated to 25  and the flag is set to true.',
                [
                    'result' => 25,
                    'flagOkay' => true,
                    'pointByte' => ')'
                ],
                [
                    'calculateOperatorParam' => null,
                    'start' => '25)',
                    'priority' => null,
                    'flagOkay' => true,
                ],
            ],
            [
                '4.a The flagOkay is set to true and no first number is given, but the array contains a simple calculation'.
                'So the result is calculated to 25  and the flag is set to true.',
                [
                    'result' => 25,
                    'flagOkay' => true,
                    'priority' => CalculateUtility::OP_PRIO_LOWEST,
                    'pointByte' => ''
                ],
                [
                    'calculateOperatorParam' => null,
                    'start' => '13+12',
                    'priority' => CalculateUtility::OP_PRIO_LOWEST,
                    'flagOkay' => true,
                ],
            ],
            [
                '4.b The flagOkay is set to true and the first number is given with 12, but the array contains a simple calculation'.
                'So the result is calculated to 25 and the flag is set to true.',
                [
                    'result' => 25,
                    'flagOkay' => true,
                    'pointByte' => ''
                ],
                [
                    'calculateOperatorParam' => 12,
                    'start' => '+13',
                    'priority' => CalculateUtility::OP_PRIO_LOWEST,
                    'flagOkay' => true,
                ],
            ],
            [
                '4.c The flagOkay is set to true and the first number is given with 12, but the array contains a simple calculation'.
                'So the result is calculated to 25 and the flag is set to true.',
                [
                    'result' => 25,
                    'flagOkay' => true,
                    'pointByte' => ')'
                ],
                [
                    'calculateOperatorParam' => 12,
                    'start' => '+13)',
                    'priority' => CalculateUtility::OP_PRIO_LOWEST,
                    'flagOkay' => true,
                ],
            ],
            [
                '4.d The flagOkay is set to true and the first number is given with 12, and array contains a more complex calculation'.
                'So the result is calculated to 25 and the flag is set to true.',
                [
                    'result' => 25,
                    'flagOkay' => true,
                    'pointByte' => ')'
                ],
                [
                    'calculateOperatorParam' => 12,
                    'start' => '+(7+9-2*2+1))',
                    'priority' => CalculateUtility::OP_PRIO_LOWEST,
                    'flagOkay' => true,
                ],
            ],
        ];
    }



    /**
     * mock the internal methods for teh first case
     * @dataProvider dataProviderCalculateOperatorReturnResultIfFlagOkayTrueIfNoErrorDetectedAndEverythingAnalysedOtherwiseRetunrnFalse
     * @test
     */
    public function calculateOperatorReturnResultIfFlagOkayTrueIfNoErrorDetectedAndEverythingAnalysedOtherwiseRetunrnFalse($message, $expects, $params)
    {

        $this->subject->resetForCalculation($params['start']);

        $reflection = new \ReflectionClass($this->subject);
        $reflection_flagOkay = $reflection->getProperty('flagOkay');
        $reflection_flagOkay->setAccessible(true);
        $reflection_flagOkay->setValue($this->subject, $params['flagOkay']);
        $reflection_pointByte = $reflection->getProperty('pointByte');
        $reflection_pointByte->setAccessible(true);

        $result = $this->subject->calculateOperator($params['priority'],$params['calculateOperatorParam']);
        $this->assertSame(
            intval($expects['result']),
            intval($result),
            'test integer value: '.$message
        );

        $this->assertSame(
            floatval($expects['result']),
            floatval($result ),
            'test float value: '.$message
        );


        $flagOkay = $reflection_flagOkay->getValue($this->subject);
        $this->assertSame(
            $expects['flagOkay'],
            $flagOkay,
            'test inernal `flagOkay`: '.$message
        );
        $pointByte = $reflection_pointByte->getValue($this->subject);
        $this->assertSame(
            $expects['pointByte'],
            $pointByte,
            'test inernal `pointByte`: '.$message
        );


    }

}
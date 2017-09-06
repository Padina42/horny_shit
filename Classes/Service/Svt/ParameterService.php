<?php
namespace Porth\HornyShit\Service\Svt;

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

/**
 * class StringReplaceService
 *
 * param-validation:
 * - set:       ?
 * - unitest:   ?
 * error-handling:
 * - set:       ?
 * - unitest:   ?
 *
 * @author Dr. Dieter Porth <info@mobger.de>
 */

use Porth\HornyShit\Service\Svt\Utility\ValidateNotifyService;

class ParameterService
{

    const PARAM_CALCULATE = 'calculate';
    const PARAM_CALCULATE_LAST = 'calculateLast';
    const PARAM_PLACEHOLDER = 'placeholder';
    const PARAM_REGISTER = 'register';

    const PARAM_LIST = [
        self::PARAM_CALCULATE,
        self::PARAM_CALCULATE_LAST,
        self::PARAM_PLACEHOLDER,
        self::PARAM_REGISTER
    ];

    const SUB_PARAM_CALCULATE_START = 'start';
    const SUB_PARAM_CALCULATE_END = 'end';
    const SUB_PARAM_CALCULATE_INPUTS = 'registerInputs';
    const SUB_PARAM_CALCULATE_OUTPUT = 'registerOutput';
    const SUB_PARAM_CALCULATE_START_DEFAULT = '###calcStart###';
    const SUB_PARAM_CALCULATE_END_DEFAULT = '###calcEnd###';
    const SUB_PARAM_CALCULATE_INPUTS_DEFAULT = '';
    const SUB_PARAM_CALCULATE_OUTPUT_DEFAULT = '';
    const SUB_PARAM_CALCULATE_LIST = [
        self::SUB_PARAM_CALCULATE_START,
        self::SUB_PARAM_CALCULATE_END,
        self::SUB_PARAM_CALCULATE_INPUTS,
        self::SUB_PARAM_CALCULATE_OUTPUT,
    ];
    const SUB_PARAM_CALCULATE_LIST_DEFAULT = [
        self::SUB_PARAM_CALCULATE_START_DEFAULT,
        self::SUB_PARAM_CALCULATE_END_DEFAULT,
        self::SUB_PARAM_CALCULATE_INPUTS_DEFAULT,
        self::SUB_PARAM_CALCULATE_OUTPUT_DEFAULT,
    ];


    const SUB_PARAM_PLACEHOLDER_MARKER = 'marker';
    const SUB_PARAM_PLACEHOLDER_REPLACER = 'replacer';
    const SUB_PARAM_PLACEHOLDER_LIST = [
        self::SUB_PARAM_PLACEHOLDER_REPLACER,
        self::SUB_PARAM_PLACEHOLDER_MARKER
    ];
    // must equal to count(SUB_PARAM_PLACEHOLDER_LIST)
    const COUNT_SUB_PARAM_PLACEHOLDER_NAMED_ENTRY = 2;

    const SUB_PARAM_REGISTER_MARKER = 'marker';
    const SUB_PARAM_REGISTER_ACTION = 'action';
    const SUB_PARAM_REGISTER_VALUE = 'value';
    const SUB_PARAM_REGISTER_ACTION_APPEND = 'append';
    const SUB_PARAM_REGISTER_ACTION_PREPEND = 'prepend';
    const SUB_PARAM_REGISTER_ACTION_ADD = 'add';
    const SUB_PARAM_REGISTER_ACTION_SUB = 'sub';
    const SUB_PARAM_REGISTER_ACTION_ADD_INT = 'addInt';
    const SUB_PARAM_REGISTER_ACTION_SUB_INT = 'subInt';
    const SUB_PARAM_REGISTER_ACTION_INC = 'inc';
    const SUB_PARAM_REGISTER_ACTION_DEC = 'dec';
    const SUB_PARAM_REGISTER_ACTION_RENEW = 'renew';
    const SUB_PARAM_REGISTER_ACTION_CALCULATE = 'calculate';
    const SUB_PARAM_REGISTER_ACTION_LIST = [
        self::SUB_PARAM_REGISTER_ACTION_APPEND,
        self::SUB_PARAM_REGISTER_ACTION_PREPEND,
        self::SUB_PARAM_REGISTER_ACTION_ADD,
        self::SUB_PARAM_REGISTER_ACTION_SUB,
        self::SUB_PARAM_REGISTER_ACTION_ADD_INT,
        self::SUB_PARAM_REGISTER_ACTION_SUB_INT,
        self::SUB_PARAM_REGISTER_ACTION_INC,
        self::SUB_PARAM_REGISTER_ACTION_DEC,
        self::SUB_PARAM_REGISTER_ACTION_RENEW,
        self::SUB_PARAM_REGISTER_ACTION_CALCULATE,
    ];
    const SUB_PARAM_REGISTER_LIST = [
        self::SUB_PARAM_REGISTER_MARKER,
        self::SUB_PARAM_REGISTER_ACTION,
        self::SUB_PARAM_REGISTER_VALUE,
    ];
    // must equal to count(SUB_PARAM_REGISTER_LIST)
    const COUNT_SUB_PARAM_REGISTER_NAMED_ENTRY = 3;

    const RECURSIV_ARRAY_ITEM_REBUILD = [
        'replace',
        'search',
        'clean',
        'new',
        'value',
        'xml',
        'href',
    ];

    /**
     * @var ValidateNotifyService
     */
    protected $validateNotifyService =  null;

    /**
     * @var string
     */
    protected $markerCalcuclationStart = self::SUB_PARAM_CALCULATE_START_DEFAULT;

    /**
     * @var string
     */
    protected $markerCalcuclationEnd = self::SUB_PARAM_CALCULATE_END_DEFAULT;

    /**
     * @var string
     */
    protected $markerCalcuclationInRegister = self::SUB_PARAM_CALCULATE_INPUTS_DEFAULT;

    /**
     * @var string
     */
    protected $markerCalcuclationOutRegister = self::SUB_PARAM_CALCULATE_OUTPUT_DEFAULT;

    /**
     * StringReplaceService constructor.
     *
     * unittest ?
     *
     * @param string $languageKey
     */
    public function __construct($languageKey = '')
    {
        $this->validateNotifyService = ValidateNotifyService::getInstance();
        $this->validateNotifyService->changeLanguage($languageKey);
    }

    /**
     * check, if a array contains a minimum of optional items (every part can be optional, but you have to define at least one element)
     * similiar to paramValidateArrayWithOptionalItemContainPredictedCountOfItems in rebuildParamValidateService
     *
     * unittest ?
     *
     * @param array $paramArray
     * @param int $predictedCountOfItems
     * @return bool
     */
    public function paramValidateArrayWithOptionalItemContainPredictedCountOfItems(array $paramArray = [], $predictedCountOfItems = 0)
    {
        $valid = (count($paramArray) === (int)$predictedCountOfItems);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $paramArray);
        return $valid;
    }

    /**
     * Will give true, if an string does not exist in the parent-structue or if the string is valid
     * if the parameter is valid, a counter will be incremented.
     *
     * unittest ?
     *
     * @param int $count
     * @param array $parentArray
     * @param string $childName
     * @return bool
     */
    public function paramValidateStringOptionalExist(&$count, $parentArray = [], $childName = '')
    {
        $valid = (
            (!isset($parentArray, $parentArray[$childName])) ||
            $this->paramValidateStringNotEmpty($count, $parentArray[$childName])
        );

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $parentArray);
        return $valid;
    }

    /**
     * Check, if a parameter exists and is not empty after trimming
     * if the parameter is valid, a counter will be incremented.
     *
     * unittest ?
     *
     * @param mixed $param
     * @return bool
     */
    public function paramValidateStringNotEmpty($param = '')
    {
        $valid = false;
        if (
            ($param !== null) &&
            is_string($param) &&
            !empty(trim($param))
        ) {
            $valid = true;
        };

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;
    }

    /**
     * Check, if the parameter in a single record  are corrct defined for the mainparameters 'prepare' oder 'overWork'
     *
     * unittest
     *
     * @param array $param
     * @return bool
     */
    public function paramValidate($param = [])
    {
        $valid = true;
        $countOptionals = 0;
        if (isset($param[self::PARAM_CALCULATE])) {
            $countOptionals++;#
            $valid  = $valid  && $this->paramValidateCalculate($param[self::PARAM_CALCULATE]);
        }
        if (isset($param[self::PARAM_CALCULATE_LAST])) {
            $countOptionals++;#
            $valid  = $valid  && $this->paramValidateCalculateLast($param[self::PARAM_CALCULATE_LAST]);
        }
        if (isset($param[self::PARAM_PLACEHOLDER])) {
            $countOptionals++;
            $valid  = $valid  && $this->paramValidatePlaceholder($param[self::PARAM_PLACEHOLDER]);
        }
        if (isset($param[self::PARAM_REGISTER])) {
            $countOptionals++;
            $valid  = $valid  && $this->paramValidateRegister($param[self::PARAM_REGISTER]);
        }

        $valid = $valid &&
            (count($param) >= $countOptionals) &&
            ($countOptionals >0);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $paramArray);
        return $valid;

    }


    /**
     * Check, if the parameter-array contain the string variables 'marker' and 'value' or
     * if it contains an array with item, which contains the string variable 'marker' and 'value'
     *   *
     * unittest ?
     *
     * @param array $param
     * @return bool
     */
    public function paramValidateRegister($param = [])
    {
        $valid = false;
        $valid = $valid ||
            (self::paramValidateRegisterItem($param));
        if (!$valid &&
            (is_array($param))
        ) {
            $valid = true;
            foreach ($param as $paramItem) {
                $valid = $valid && self::paramValidateRegisterItem($paramItem);
            }
        }
        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;
    }

    /**
     * Checks,  if the parameter contain the string variables 'marker' and 'value'
     *
     * unittest ?
     *
     * @param array $param
     * @return bool
     */
    public function paramValidateRegisterItem($param = [])
    {
        $valid = true;
        $valid = $valid && isset($param[self::SUB_PARAM_REGISTER_VALUE], $param[self::SUB_PARAM_REGISTER_MARKER]);
        $valid = $valid && $this->paramValidateStringNotEmpty($param[self::SUB_PARAM_REGISTER_MARKER]);
        $valid = $valid && $this->paramValidateStringNotEmpty($param[self::SUB_PARAM_REGISTER_VALUE]);
        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems($param, self::COUNT_SUB_PARAM_REGISTER_NAMED_ENTRY);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;

    }


    /**
     * Check, if the parameter-array contain the string variables 'marker' and 'value' or
     * if it contains an array with item, which contains the string variable 'marker' and 'value'
     *   *
     * unittest ?
     *
     * @param array $param
     * @return bool
     */
    public function paramValidatePlaceholder($param = [])
    {
        $valid = false;
        $valid = $valid ||
            (self::paramValidatePlaceholderItem($param));
        if (!$valid &&
            (is_array($param))
        ) {
            $valid = true;
            foreach ($param as $paramItem) {
                $valid = $valid && self::paramValidatePlaceholderItem($paramItem);
            }
        }
        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;
    }

    /**
     * Checks,  if the parameter contain the string variables 'marker' and 'value'
     *
     * unittest ?
     *
     * @param array $param
     * @return bool
     */
    public function paramValidatePlaceholderItem($param = [])
    {
        $valid = true;
        $valid = $valid && isset($param[self::SUB_PARAM_PLACEHOLDER_REPLACER], $param[self::SUB_PARAM_PLACEHOLDER_MARKER]);
        $valid = $valid && $this->paramValidateStringNotEmpty($param[self::SUB_PARAM_PLACEHOLDER_MARKER]);
        $valid = $valid && $this->paramValidateStringNotEmpty($param[self::SUB_PARAM_PLACEHOLDER_REPLACER]);
        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems($param, self::COUNT_SUB_PARAM_PLACEHOLDER_NAMED_ENTRY);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;

    }



    /**
     * Check the same as the method paramValidateCalculate and has an own error-reporting
     *   *
     * unittest ?
     *
     * @param array $param
     * @return bool
     */
    public function paramValidateCalculateLast($param = [])
    {
        $valid = true;
        $valid = $valid && $this->paramValidateCalculate($param );
        $this->validateNotifyService->calculateValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;
    }

    /**
     * Check, if the parameter-array contain the string variables 'marker' and 'value' or
     * if it contains an array with item, which contains the string variable 'marker' and 'value'
     *   *
     * unittest ?
     *
     * @param array $param
     * @return bool
     */
    public function paramValidateCalculate($param = [])
    {
        $valid = false;
        $valid = $valid ||
            (self::paramValidateCalculateItem($param));
        if (!$valid &&
            (is_array($param))
        ) {
            $valid = true;
            foreach ($param as $paramItem) {
                $valid = $valid && self::paramValidateCalculateItem($paramItem);
            }
        }
        $this->validateNotifyService->calculateValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;
    }

    /**
     * Checks,  if the parameter contain the string variables 'marker' and 'value'
     *
     * unittest ?
     *
     * @param array $param
     * @return bool
     */
    public function paramValidateCalculateItem($param = [])
    {
        $valid = true;
        $valid = $valid && isset($param[self::SUB_PARAM_CALCULATE_END], $param[self::SUB_PARAM_CALCULATE_START]);
        $valid = $valid && $this->paramValidateStringNotEmpty($param[self::SUB_PARAM_CALCULATE_START]);
        $valid = $valid && $this->paramValidateStringNotEmpty($param[self::SUB_PARAM_CALCULATE_END]);
        $optionalCounts = 0;
        $valid = $valid && $this->paramValidateStringOptionalExist($optionalCounts,$param,self::SUB_PARAM_CALCULATE_INPUTS);
        $valid = $valid && $this->paramValidateStringOptionalExist($optionalCounts,$param,self::SUB_PARAM_CALCULATE_OUTPUT);

        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems(
            $param,
                (self::COUNT_SUB_PARAM_CALCULATE_NAMED_ENTRY + $optionalCounts)
            );

        $this->validateNotifyService->calculateValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;

    }

    /**
     * Replace alle Markers in an item of an string
     * @param string $item
     * @param string $key
     * @param array $checks
     */
    public static function replacePlaceholderItemCheck(&$item, $key, $checks)
    {
        if (array_search($key,self::RECURSIV_ARRAY_ITEM_REBUILD ) !== false) {
            $item = str_replace($checks['search'],$checks['replace'],$item);
        }
    }

    /**
     * call the php-function for leaf-manipulations in multidimensional arrays
     *
     * @param string $marker
     * @param string $value
     * @param string $stream
     * @return string
     */
    public function replacePlaceholderItem(&$arguments, $checkPairs)
    {
        array_walk_recursive($arguments, ['Porth\\HornyShit\\Service\\Svt\\ParameterService','replacePlaceholderItemCheck'], $checkPairs);
    }

    /**
     * replace placeholders in the argumentsarray
     *
     * @param array $param
     * @param array $arguments
     */
    public function replacePlaceholder($param=[], $arguments = [])
    {
        $resultByRef = $arguments;
        $search = [];
        $replace = [];
        if (isset($param[self::SUB_PARAM_PLACEHOLDER_MARKER], $param[self::SUB_PARAM_PLACEHOLDER_REPLACER])) {

            $search[] = $param[self::SUB_PARAM_PLACEHOLDER_MARKER];
            $replace[] = $param[self::SUB_PARAM_PLACEHOLDER_REPLACER];
        } else {
            foreach($param as $paramItem) {
                $search[] = $param[self::SUB_PARAM_PLACEHOLDER_MARKER];
                $replace[] = $param[self::SUB_PARAM_PLACEHOLDER_REPLACER];
            }
        }

        $this->replacePlaceholderItem($resultByRef ,['search' => $search, 'replace' =>$replace]);
        return $resultByRef;
    }


    /**
     * transform the parameter for the register an other service (additional layer make the service independ to the configuration
     *
     * @param array $param
     * @param string $stream
     */
    public function readRegisterForService($param=[], $markerKey, $valueKey, $actionKey)
    {
        $result = [];
        if (isset($param[self::SUB_PARAM_REGISTER_MARKER], $param[self::SUB_PARAM_REGISTER_ACTION])) {
            $item = [];
            $item[$markerKey] = $param[self::SUB_PARAM_REGISTER_MARKER];
            $item[$valueKey] =  (isset($param[self::SUB_PARAM_REGISTER_VALUE]) ? $param[self::SUB_PARAM_REGISTER_VALUE] : '');
            $item[$actionKey] =  $param[self::SUB_PARAM_REGISTER_ACTION] ;
            $result[] = $item;
        } else {
            foreach($param as $paramItem) {
                $item = [];
                $item[$markerKey] = $param[self::SUB_PARAM_REGISTER_MARKER];
                $item[$valueKey] = (isset($param[self::SUB_PARAM_REGISTER_VALUE]) ? $param[self::SUB_PARAM_REGISTER_VALUE] : '');
                $item[$actionKey] =  $param[self::SUB_PARAM_REGISTER_ACTION] ;
                $result[] = $item;
            }
        }
        return $result;
    }

}


?>

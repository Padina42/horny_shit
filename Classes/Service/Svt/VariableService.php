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

class VariableService
{

    const PARAM_MARKER = 'marker';
    const PARAM_VALUE = 'value';
    const PARAM_LIST = [
        self::PARAM_VALUE,
        self::PARAM_MARKER
    ];
    // must equal to count(PARAM_LIST)
    const COUNT_PARAM_NAMED_ENTRY = 2;

    /**
     * @var ValidateNotifyService
     */
    protected $validateNotifyService =  null;

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
     * Check, if the parameter-array contain the string variables 'marker' and 'value' or
     * if it contains an array with item, which contains the string variable 'marker' and 'value'
     *   *
     * unittest ?
     *
     * @param array $subParam
     * @return bool
     */
    public function paramValidate($param = [])
    {
        $valid = false;
        $valid = $valid ||
            (self::paramValidateVariableItem($param));
        if (!$valid &&
            (is_array($param))
        ) {
            $valid = true;
            foreach ($param as $paramItem) {
                $valid = $valid && self::paramValidateVariableItem($paramItem);
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
     * @param array $subParam
     * @return bool
     */
    public function paramValidateVariableItem($param = [])
    {
        $valid = true;
        $valid = $valid && isset($param[self::PARAM_VALUE], $param[self::PARAM_MARKER]);
        $valid = $valid && $this->paramValidateStringNotEmpty($param[self::PARAM_MARKER]);
        $valid = $valid && $this->paramValidateStringNotEmpty($param[self::PARAM_VALUE]);
        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems($param, self::COUNT_PARAM_NAMED_ENTRY);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;

    }

    /**
     * replace one marker in the svg-Stream
     *
     * @param string $marker
     * @param string $value
     * @param string $stream
     * @return string
     */
    public function replaceVariableItem($marker, $value, $stream)
    {
        return str_replace($marker,$value,$stream);
    }

    /**
     * replace markers in the svg-Stream
     *
     * @param array $param
     * @param string $stream
     */
    public function replaceVariable($param=[], $stream = '')
    {
        $result = $stream;
        if (isset($aparm[self::PARAM_MARKER], $param[self::PARAM_VALUE])) {
            $result =  $self::replaceVariableItem(
                $aparm[self::PARAM_MARKER],
                $param[self::PARAM_VALUE],
                $stream
            );
        } else {
            foreach($param as $paramItem) {
                $result =  $self::replaceVariableItem(
                    $paramItem[self::PARAM_MARKER],
                    $paramItem[self::PARAM_VALUE],
                    $stream
                );
            }
        }
        return $result;
    }

}


?>

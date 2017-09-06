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

use Porth\HornyShit\Service\Svt\Utility\ValidateNotifyService;

/**
 * class RebuildParamValidateService
 *
 * param-validation:
 * - set:       20170802
 * - unitest:   ?
 *
 * @author Dr. Dieter Porth <info@mobger.de>
 *
 *
 */

class RebuildParamValidateService extends RebuildBaseService
{

    /**
     * =============================
     * Base-Funktions
     * =============================
     */
    /**
     * Check, if a parameter exists and is not empty after trimming
     * if the parameter is valid, a counter will be incremented.
     *
     * unittest ?
     * old 20170701
     *
     * @param int $count
     * @param mixed $param
     * @return bool
     */
    public function paramValidateStringNotEmpty(&$count, $param = '')
    {
        $valid = false;
        if (
            ($param !== null) &&
            is_string($param) &&
            !empty(trim($param))
        ) {
            $valid = true;
            $count++;
        };

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;
    }

    /**
     * Will give true, if an string does not exist in the parent-structue or if the string is valid
     * if the parameter is valid, a counter will be incremented.
     *
     * unittest ?
     * old 20170701
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
     * old 20170707
     *
     * @param int $count
     * @param mixed $param
     * @return bool
     */
    public function paramValidateIntegerNotEmpty(&$count, $param = '')
    {
        $valid = false;
        if (
            isset($param) &&
            (
                (
                    is_numeric(trim($param)) &&
                    (((int)trim($param)) == (trim($param))) &&
                    (!empty(trim($param)))
                ) ||
                ($param === 0) ||
                (trim($param) === '0')
            )
        ) {
            $valid = true;
            $count++;
        };

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;
    }

    /**
     * Will give true, if the paremter ist not set in the parent-structure or if the existing parameter is interpretable as an integer
     *
     * unittest ?
     * old 20170707
     *
     * @param int $count
     * @param array $parentArray
     * @param string $childName
     * @return bool
     */
    public function paramValidateIntegerOptionalExist(&$count, $parentArray = [], $childName = '')
    {
        $valid = (
            (!isset($parentArray, $parentArray[$childName])) ||
            $this->paramValidateIntegerNotEmpty($count, $parentArray[$childName])
        );

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $parentArray);
        return $valid;

    }

    /**
     *  Check, if a parameter exists, is an array and has one or more items
     * if the parameter is valid, a counter will be incremented.
     *
     * unittest ?
     * old 20170701
     *
     * @param int $count
     * @param array $param
     * @return bool
     */
    public function paramValidateArrayNotEmpty(&$count, $param = [])
    {
        $valid = false;

        if (
            isset($param) &&
            is_array($param) &&
            (count($param) > 0)
        ) {
            $count++;
            $valid = true;
        };

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;
    }

    /**
     *  Check, if a parameter exists, is a string or integer and has entry for true and false
     * if the parameter is valid, a counter will be incremented.
     *
     * unittest ?
     * old 20170701
     *
     * @param int $count
     * @param mixed $param
     * @return bool
     */
    public function paramValidateBooleanExist(&$count, $param = '')
    {
        $valid = false;

        if (
            (isset($param)) &&
            (is_string($param) || is_int($param) || is_bool($param)) &&
            (!(is_float($param))) &&
            (
                (trim(strtolower($param)) === 'true') ||
                (trim(strtolower($param)) === 'false') ||
                ($param === 0) ||
                ($param === 1) ||
                ($param === true) ||
                ($param === false)
            )
        ) {
            $valid = true;
            $count++;
        };

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;
    }

    /**
     * Will give true, if a boolean parameter does not exist or if the boolean parameter is valid
     * if the parameter is valid, a counter will be incremented.
     *
     * unittest ?
     * old 20170701
     *
     * @param int $count
     * @param array $parentArray
     * @param string $childName
     * @return bool
     */
    public function paramValidateBooleanOptionalExist(&$count, $parentArray = [], $childName = '')
    {
        $valid = (
            (!isset($parentArray, $parentArray[$childName])) ||
            $this->paramValidateBooleanExist($count, $parentArray[$childName])
        );

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $parentArray);
        return $valid;
    }

    /**
     *  Check, if a parameter exists, is a string and contains a value of a List of trimmed entities, which are case-sensitive
     * if the parameter is valid, a counter will be incremented
     *
     * unittest ?
     * old 20170702
     *
     * @param int $count
     * @param string $value
     * @param string $testList
     * @return bool
     */
    public function paramValidateStringInTypeList(&$count, $value = '', $testList = '')
    {

        $valid = false;

        if ((isset($value, $testList)) &&
            (is_string($value)) &&
            (is_string($testList)) &&
            (!empty(trim($value))) &&
            (!empty(trim($testList)))
        ) {
            $testArray = array_filter(array_map('trim', explode(',',$testList)));
            if (array_search($value, $testArray) !== false) {
                $valid = true;
                $count++;
            };
        }

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $testList);
        return $valid;
    }

    /**
     *  Check, if a parameter exists, is a string and contains a value of a List of trimmed entities, which are case-sensitive
     * if the parameter is valid, a counter will be incremented
     *
     * unittest ?
     *
     * @param int $count
     * @param string $value
     * @param string $testList
     * @return bool
     */
    public function paramValidateStringInTypeArray(&$count, $value = '', $testArray = [])
    {

        $valid = false;

        if ((isset($value, $testArray)) &&
            (is_string($value)) &&
            (is_array($testArray)) &&
            (!empty(trim($value))) &&
            (count($testArray)>0)
        ) {
            if (array_search($value, $testArray) !== false) {
                $valid = true;
                $count++;
            };
        }

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            implode(', ',$testArray));
        return $valid;
    }

    /**
     * Will give true, if a parameter does not exist or if the parameter contain a value, which is item in the testarray of entities
     *
     * unittest ?
     * old 20170702
     *
     * @param $count
     * @param array $parentArray
     * @param string $childName
     * @param string $testList
     * @return bool
     */
    public function paramValidateOptionalStringInTypeList(&$count, $parentArray = [], $childName = '', $testList = '')
    {
        $valid = (
            (!isset($parentArray, $parentArray[$childName])) ||
            $this->paramValidateStringInTypeList(
                $count,
                    $parentArray[$childName],
                    $testList)
        );

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $parentArray);
        return $valid;
    }

    /**
     * Will give true, if a parameter does not exist or if the parameter contain a value, which is item in the testarray of entities
     *
     * unittest ?
     * old 20170702
     *
     * @param $count
     * @param array $parentArray
     * @param string $childName
     * @param array $testArray
     * @return bool
     */
    public function paramValidateOptionalStringInTypeArray(&$count, $parentArray = [], $childName = '', $testArray = '')
    {
        $valid = (
            (!isset($parentArray, $parentArray[$childName])) ||
            $this->paramValidateStringInTypeList(
                $count,
                $parentArray[$childName],
                $testArray)
        );

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $parentArray);
        return $valid;
    }

    /**
     * check, if a array contains a minimum of optional items (every part cann be optional, but you have to define at least one element)
     *
     * unittest ?
     * old 20170702
     *
     * @param array $paramArray
     * @param int $start
     * @return bool
     */
    public function paramValidateArrayWithOptionalItemContainMoreThanStartItems(array $paramArray = [], $start = 0)
    {
        $valid = (count($paramArray) > (int)$start);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $paramArray);
        return $valid;
    }

    /**
     * check, if a array contains a minimum of optional items (every part can be optional, but you have to define at least one element)
     *
     * unittest ?
     * old 20170602
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
     * ===========================================================
     *  special validations for the special Types of params
     * ===========================================================
     */

    /**
     * Checks the needed and optional parameter, if the type should provide the rebuild of attributes
     *
     * unittest ?
     * old 20170714
     *
     * @param array $subParam
     * @return bool
     */
    public function paramValidateSecondLevelCheckNamedAttributeItem($subParam = [])
    {
        $valid = true;
        $countOfAllowedItems = 0;
        $valid = $valid && isset($subParam[self::SUB_PARAM_ATTRIBUTE_NEW], $subParam[self::SUB_PARAM_ATTRIBUTE_KEY]);
        $valid = $valid && $this->paramValidateStringNotEmpty($countOfAllowedItems, $subParam[self::SUB_PARAM_ATTRIBUTE_KEY]);
        $valid = $valid && (
            $this->paramValidateStringNotEmpty($countOfAllowedItems, $subParam[self::SUB_PARAM_ATTRIBUTE_NEW]) ||
            $this->paramValidateIntegerNotEmpty($countOfAllowedItems, $subParam[self::SUB_PARAM_ATTRIBUTE_NEW])
        );

        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems($subParam, $countOfAllowedItems);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $subParam);
        return $valid;

    }

    /**
     * Checks the needed and optional parameter, The Array provide a set of definitions to rebuild the attributes or
     * it is  an array with many sets, which all define the rebuild of attributes
     *
     * unittest ?
     * old 20170714
     *
     * @param array $subParam
     * @return bool
     */
    public function paramValidateSecondLevelCheckNamedAttribute($subParam = [])
    {

        $valid = false;
        $valid = $valid ||
            (self::paramValidateSecondLevelCheckNamedAttributeItem($subParam));
        if (!$valid &&
            (is_array($subParam))
        ) {
            $valid = true;
            foreach ($subParam as $subParamItem) {
                $valid = $valid && self::paramValidateSecondLevelCheckNamedAttributeItem($subParamItem);
            }
        }

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $subParam);
        return $valid;
    }

    /**
     * Will give true, if an string does not exist or if the string is valid
     * if the parameter is valid, a counter will be incremented.
     *
     * unittest ?
     * old 20170708
     *
     * @param int $count
     * @param array $parentArray
     * @param string $childName
     * @return bool
     */
    public function paramValidateSecondLevelCheckNamedAttributeOptional(&$count, $parentArray = [], $childName = '')
    {
        $valid = true;
        if (isset($parentArray, $parentArray[$childName])) {
            if (isset($parentArray[$childName][self::SUB_PARAM_ATTRIBUTE_KEY], $parentArray[$childName][self::SUB_PARAM_ATTRIBUTE_NEW])) {
                $valid = $this->paramValidateSecondLevelCheckNamedAttribute($parentArray[$childName]);
                $count++;
            } else {
                $valid = false;
                $count++;
            }
        }

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $parentArray);
        return $valid;
    }

    /**
     * test the xml-fragment, if it is well formed
     *
     * unittest ?
     * old ?
     *
     * @param string $xmlFragment
     * @return bool
     */
    public function paramValidateXmlStringCorrectFormed($xmlFragment = '')
    {
        $valid = (
            (!empty($xmlFragment))
        );
//        $valid = (
//            (!empty($xmlFragment)) &&
//            (simplexml_load_string($xmlFragment) !== false)
//        );
        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $xmlFragment);
        return $valid;
    }

    /**
     * Checks the needed and optional parameter, if the type should provide the rebuild of childes
     *
     * unittest ?
     * old ? (part of wellformed XML is missing
     * old 20170702
     *
     * @param array $subParam
     * @return bool
     */
    public function paramValidateSecondLevelCheckNamedChild($subParam = [])
    {

        $valid = true;
        $countOfAllowedItems = 0;

        $valid = $valid && isset($subParam[self::SUB_PARAM_CHILD_XML], $subParam[self::SUB_PARAM_CHILD_TYPE]);

        $valid = $valid && $this->paramValidateStringNotEmpty($countOfAllowedItems, $subParam[self::SUB_PARAM_CHILD_XML]);
        $valid = $valid && $this->paramValidateXmlStringCorrectFormed($subParam[self::SUB_PARAM_CHILD_XML]);
        $valid = $valid && $this->paramValidateStringInTypeArray(
                $countOfAllowedItems,
                $subParam[self::SUB_PARAM_CHILD_TYPE],
                self::SUB_PARAM_NODE_TYPE_LIST
            );

        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems($subParam, $countOfAllowedItems);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $subParam);
        return $valid;
    }

    /**
     * Checks the needed and optional parameter, if the type should provide the rebuild of classes
     *
     * unittest ?
     * old 20170702
     *
     * @param array $subParam
     * @return bool
     */
    public function paramValidateSecondLevelCheckNamedClass($subParam = [])
    {

        $valid = true;
        $countOfAllowedItems = 0;
        $ignorableEntries = $countOfAllowedItems;
        $valid = $valid && $this->paramValidateStringOptionalExist($countOfAllowedItems, $subParam, self::SUB_PARAM_CLASS_ADD);
        $valid = $valid && $this->paramValidateStringOptionalExist($countOfAllowedItems, $subParam, self::SUB_PARAM_CLASS_OVERRIDE);
        $valid = $valid && $this->paramValidateStringOptionalExist($countOfAllowedItems, $subParam, self::SUB_PARAM_CLASS_REMOVE);

        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainMoreThanStartItems($subParam, $ignorableEntries);
        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems($subParam, $countOfAllowedItems);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $subParam);
        return $valid;
    }

    /**
     * Checks the needed and optional parameter, if the type should provide the delte of nodes and attriobutes
     *
     * unittest ?
     *
     * @param array $subParam
     * @return bool
     */
    public function paramValidateSecondLevelCheckNamedDelete($subParam = [])
    {

        $valid = true;
        $countOfAllowedItems = 0;
        $ignorableEntries = $countOfAllowedItems;

        $valid = $valid && isset($subParam[self::SUB_PARAM_DELETE_TYPE]);
        $valid = $valid && $this->paramValidateStringInTypeArray(
                $countOfAllowedItems,
                $subParam[self::SUB_PARAM_DELETE_TYPE],
                self::SUB_PARAM_DELETE_TYPUS_LIST
            );
        $valid = $valid && $this->paramValidateStringOptionalExist($countOfAllowedItems, $subParam, self::SUB_PARAM_DELETE_ATTRIBUTE);

        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems($subParam, $countOfAllowedItems);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $subParam);
        return $valid;
    }


    /**
     * Checks the needed and optional parameter, if the type should provide the rebuild of image-attribute
     *
     * unittest ?
     * old 20170708
     *
     * @param array $subParam
     * @return bool
     */
    public function paramValidateSecondLevelCheckNamedImage($subParam = [])
    {
        $valid = true;
        $countOfAllowedItems = 0;

        $valid = $valid && isset($subParam[self::SUB_PARAM_IMAGE_HREF]);
        $valid = $valid && $this->paramValidateStringNotEmpty(
                $countOfAllowedItems,
                $subParam[self::SUB_PARAM_IMAGE_HREF]
            );
        $valid = $valid && $this->paramValidateOptionalStringInTypeArray(
                $countOfAllowedItems,
                $subParam,
                self::SUB_PARAM_IMAGE_TYPE,
                self::SUB_PARAM_NODE_TYPE_LIST
            );


        $valid = $valid && $this->paramValidateSecondLevelCheckNamedAttributeOptional(
                $countOfAllowedItems,
                $subParam,
                self::SUB_PARAM_IMAGE_ATTRIBUTES
            );

        $valid = $valid && $this->paramValidateBooleanOptionalExist(
                $countOfAllowedItems,
                $subParam,
                self::SUB_PARAM_IMAGE_EXTERNAL_RESOURCES_REQUIRED
            );

        $valid = $valid && $this->paramValidateOptionalStringInTypeList(
                $countOfAllowedItems,
                $subParam,
                self::SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO,
                self::SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO_LISTING
            );

        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems($subParam, $countOfAllowedItems);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $subParam);
        return $valid;
    }

    /**
     * Checks the needed and optional parameter, if the type should provide the change of register values
     *
     * unittest ?
     *
     * @param array $subParam
     * @return bool
     */
    public function paramValidateSecondLevelCheckNamedRegister($subParam = [])
    {

        $valid = true;
        $countOfAllowedItems = 0;
        $ignorableEntries = $countOfAllowedItems;

        $valid = $valid && isset($subParam[self::SUB_PARAM_REGISTER_ACTION], $subParam[self::SUB_PARAM_REGISTER_MARKER]);
        $valid = $valid && $this->paramValidateStringNotEmpty($countOfAllowedItems, $subParam[self::SUB_PARAM_REGISTER_MARKER]);
        $valid = $valid && $this->paramValidateStringInTypeArray(
                $countOfAllowedItems,
                $subParam,
                self::SUB_PARAM_REGISTER_ACTION,
                self::SUB_PARAM_REGISTER_ACTION_LIST
            );
        $valid = $valid && $this->paramValidateStringOptionalExist($countOfAllowedItems, $subParam, self::SUB_PARAM_REGISTER_VALUE);

        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems($subParam, $countOfAllowedItems);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $subParam);
        return $valid;
    }


    /**
     * paradigm:
     * Checks the needed and optional parameter, if the type should provide the rebuild of styles
     *
     * unittest ?
     * old 20170702
     *
     * @param array $subParam
     * @return bool
     */
    public function paramValidateSecondLevelCheckNamedStyle($subParam = [])
    {

        $valid = true;
        $countOfAllowedItems = 0;

        $valid = $valid && isset($subParam[self::SUB_PARAM_STYLE_KEY], $subParam[self::SUB_PARAM_STYLE_NEW]);
        $valid = $valid && $this->paramValidateStringNotEmpty($countOfAllowedItems, $subParam[self::SUB_PARAM_STYLE_KEY]);
        $valid = $valid && $this->paramValidateStringNotEmpty($countOfAllowedItems, $subParam[self::SUB_PARAM_STYLE_NEW]);
        $valid = $valid && $this->paramValidateOptionalStringInTypeList(
                $countOfAllowedItems,
                $subParam,
                self::SUB_PARAM_STYLE_TYPE,
                self::SUB_PARAM_STYLE_TYPE_LISTING
            );

        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems($subParam, $countOfAllowedItems);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $subParam);
        return $valid;
    }

    /**
     * Checks the needed and optional parameter, if the type should provide the rebuild of use-attribute
     *
     * unittest ?
     * old 20170708
     *
     * @param array $subParam
     * @return bool
     */
    public function paramValidateSecondLevelCheckNamedUse($subParam = [])
    {
        $valid = true;
        $countOfAllowedItems = 0;

        $valid = $valid && isset($subParam[self::SUB_PARAM_USE_HREF]);
        $valid = $valid && $this->paramValidateStringNotEmpty(
                $countOfAllowedItems,
                $subParam[self::SUB_PARAM_USE_HREF]
            );
        $valid = $valid && $this->paramValidateOptionalStringInTypeArray(
                $countOfAllowedItems,
                $subParam,
                self::SUB_PARAM_USE_TYPE,
                self::SUB_PARAM_NODE_TYPE_LIST
            );


        $valid = $valid && $this->paramValidateSecondLevelCheckNamedAttributeOptional(
                $countOfAllowedItems,
                $subParam,
                self::SUB_PARAM_USE_ATTRIBUTES
            );


        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems($subParam, $countOfAllowedItems);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $subParam);
        return $valid;
    }

    /**
     * Checks the needed and optional parameter, if the type should provide the rebuild of values
     *
     * unittest ?
     * old 20170702
     *
     * @param array $subParam
     * @return bool
     */
    public function paramValidateSecondLevelCheckNamedValue($subParam = [])
    {

        $valid = true;
        $countOfAllowedItems = 0;

        $valid = $valid && isset($subParam[self::SUB_PARAM_VALUE_NEW]);
        $valid = $valid && $this->paramValidateStringOptionalExist($countOfAllowedItems, $subParam, self::SUB_PARAM_VALUE_MARKER);
        $valid = $valid && $this->paramValidateIntegerOptionalExist($countOfAllowedItems, $subParam, self::SUB_PARAM_VALUE_MAX_LENGTH);
        $valid = $valid && $this->paramValidateStringNotEmpty($countOfAllowedItems, $subParam[self::SUB_PARAM_VALUE_NEW]);

        $valid = $valid && $this->paramValidateOptionalStringInTypeArray(
                $countOfAllowedItems,
                $subParam,
                self::SUB_PARAM_VALUE_TYPE,
                self::SUB_PARAM_VALUE_TYPE_LIST
            );

        $valid = $valid && $this->paramValidateArrayWithOptionalItemContainPredictedCountOfItems($subParam, $countOfAllowedItems);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $subParam);
        return $valid;
    }


    /**
     * resolve the name of the validationmathod for the specuial rebuildtype
     *
     * unittest ?
     * old 20170708
     *
     * @param array $subParam
     * @param string $subParamName
     * @return bool
     */
    public function paramValidateSecondLevelCheckSwitchToNamedMethod($subParamArray = [], $subParamName = '')
    {
        $valid = true;

        $valid = $valid && is_array($subParamArray);

        $testMethod = 'paramValidateSecondLevelCheckNamed' . ucfirst($subParamName);
        $valid = $valid && method_exists($this, $testMethod);
        if ($valid) {
            $valid = $valid && self::$testMethod($subParamArray);
        }

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $testMethod);
        return $valid;
    }

    /**
     * check the existens of subarray and an after trimming defines method-name and call a further depper method check
     *
     * unittest ?
     * old 20170708
     *
     * @param array $subParam
     * @param string $subParamName
     * @return bool
     */
    public function paramValidateSecondLevelCheck($subParam = [], $subMethodName = '')
    {
        $valid = false;
        $subMethodName = trim($subMethodName);
        if ((!empty(trim($subMethodName))) &&
            (is_string($subMethodName)) &&
            (isset($subParam[$subMethodName]))
        ) {
            $valid = $this->paramValidateSecondLevelCheckSwitchToNamedMethod($subParam[$subMethodName], $subMethodName);
        }

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $subMethodName);
        return $valid;
    }

    /**
     * extrakt the missing items-key, which can have one of a list of names
     *
     * unittest ?
     * old 20170708
     *
     *
     * @param array $subParam
     * @return bool
     */
    public function paramValidateOneOfSecondLevelOfRebuild($subParam = [])
    {
        $valid = false;
        if (count($subParam) > 1) {
            foreach ($subParam as $subParamKey => $subParamItem) {
                if (array_search($subParamKey, RebuildBaseService::SUB_PARAM_ARRAY) !== false) {
                    $valid = $valid || $this->paramValidateSecondLevelCheck($subParam, $subParamKey);
                    break;
                }
            }
        }

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $subParam);
        return $valid;
    }

    /**
     * analyse the correctness of the subarray for items
     *
     * unittest ?
     * old 20170709
     *
     * @param int $count
     * @param array $param
     * @return bool
     */
    public function paramValidateSecondLevelOfRebuild(&$count, $param = [])
    {
        $valid = true;

        $valid = $valid && is_array($param);
        $valid = $valid && $this->paramValidateArrayNotEmpty($count, $param);
        if ($valid) {
            foreach ($param as $typeItem) {
                $oneItemsArrayProXpath = 1;
                $additionalArrayItems = 0;

                $valid = $valid && isset($typeItem, $typeItem[self::ITEM_X_PATH]);

                $valid = $valid && $this->paramValidateStringNotEmpty($additionalArrayItems, $typeItem[self::ITEM_X_PATH]);
                $valid = $valid && $this->paramValidateIntegerOptionalExist($additionalArrayItems, $typeItem, self::ITEM_REPEAT_MAX);

                $valid = $valid && (($oneItemsArrayProXpath + $additionalArrayItems) <= count($typeItem));
                $valid = $valid && $this->paramValidateOneOfSecondLevelOfRebuild($typeItem);
                if ($valid === false) {
                    break;
                }
            }
        }

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;
    }

    /**
     * analyse the correctness of the main rebuild array
     *
     * unittest ?
     * old 20170709
     * tested in RebuildParamValidateServicePartTwoTest
     *
     * @param array $param
     * @return bool
     */
    public function paramValidateStartLevelOfRebuild($param = [])
    {
        $count = 0;
        $valid = true;

        $valid = $valid && $this->paramValidateIntegerOptionalExist($count, $param, self::PARAM_REPEAT_MAX);

        $valid = $valid && $this->paramValidateSecondLevelOfRebuild($count, $param[self::PARAM_ITEMS]);

        $valid = $valid && ($count === count($param));

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;
    }

    /**
     * make first general test for the parameter-array of configuration
     *
     * unittest ?
     * old ?
     *
     * @param array $param
     * @return bool
     */
    public function paramValidate($param = [])
    {
        $valid = is_array($param);
        if (!empty($param)) {
            $valid = $valid && $this->paramValidateStartLevelOfRebuild($param);
        }

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;
    }


}

?>
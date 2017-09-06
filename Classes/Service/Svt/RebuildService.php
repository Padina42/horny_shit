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

use Porth\HornyShit\Service\Svt\RebuildBaseService;
use Porth\HornyShit\Service\Svt\Utility\ValidateNotifyService;
use Porth\HornyShit\Service\Svt\Utility\CalculateUtility;

/**
 * class RebuildService
 *
 * error-handling:
 * - set:       ?
 * - unitest:   ?
 *
 * @author Dr. Dieter Porth <info@mobger.de>
 */
class RebuildService extends RebuildBaseService
{
    const GENERIC_NAME_FOR_REBUILD_ACTIONS = 'RebuildDetailDoIt';
    const GENERIC_NAME_FOR_REBUILD_DETAIL_ACTIONS = 'RebuildDetailActions';

    /**
     * =============================
     * Predefined attributes
     * =============================
     *
     * ... many
     *
     *
     */

    /**
     * register
     * @var array
     */
    protected $register = [];

    /**
     * calculate
     * @var CalculateUtility
     */
    protected $calculate = null;

    public function __construct()
    {
        $this->calculate = new CalculateUtility();

    }

    /**
     *
     * unittest ?
     *
     * @param array $register
     */
    public function setRegister($register = [])
    {
        $this->register = $register;
    }

    /**
     *
     * unittest ?
     *
     * @param array $register
     */
    public function getRegister()
    {
        return $this->register;
    }

    /**
     *
     * unittest ?
     *
     * @param string $name
     * @return string
     */
    public function getNamedRegister($name = '')
    {
        return ((isset($this->register[$name]) && is_string($this->register[$name])) ? $this->register[$name] : '');
    }

    /**
     *
     * unittest ?
     *
     * @param string $name
     * @param string $value
     * @return boolean
     */
    public function setNamedRegister($name = '', $value = '')
    {
        $flag = false;
        if (isset($this->register[$name]) &&
            is_string($this->register[$name])
        ) {
            $this->register[$name] = $value;
            $flag = true;
        }
        return $flag;
    }

    /**
     * read the register-Parameter an integrate them to the rebuild-servce
     *
     * unittest
     *
     * @param array $param
     */
    public function initializeRegister($param = [])
    {
        $register = $this->parameter->readRegisterForService(
            $param,
            self::SUB_PARAM_REGISTER_MARKER,
            self::SUB_PARAM_REGISTER_VALUE,
            self::SUB_PARAM_REGISTER_ACTION
        );
        $this->rebuild->setRegister($register);
    }

    /**
     * check the stream, if ther an register in it and replace the value
     *
     * @param string $stream
     * @return string
     */
    public function registerUseOn($stream = '')
    {
        $register = $this->getRegister();
        $countRegister = count($register);
        if (count($register) > 0) {
            $registerKey = array_keys($register);
            $registerValues = array_values($register);
        }
        return (($countRegister > 0) ?
            str_replace($registerKey, $registerValues, $stream) :
            $stream
        );
    }

    /**
     * Set the global rebuild-parameter in this object
     *
     * unittest 20170708
     * tested in RebuildServicePartOneTest
     *
     * @param array $dataArray
     * @return void
     */
    public function defineParameter($dataArray = [])
    {
        if (isset($dataArray[self::PARAM_REPEAT_MAX]) &&
            (!empty($dataArray[self::PARAM_REPEAT_MAX]))
        ) {
            $this->setRepeatMax($dataArray[self::PARAM_REPEAT_MAX]);
        }
    }

    /**
     *
     * unittest ?
     *
     * @param \DOMElement $element
     * @param array $attribute
     */
    public function attributeRebuildDetailOnceOptional(\DOMElement $element, $attribute = [])
    {
        if (isset($attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW], $attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY]) &&
            is_string($attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW]) &&
            is_string($attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY])
        ) {
            $attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW] = $this->registerUseOn($attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW]);
            if (empty($attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW])) {
                $element->removeAttribute($attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY]);
            } else {
                if ($element->hasAttribute($attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY])) {
                    $element->removeAttribute($attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY]);
                }
                $element->setAttribute(
                    $attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY],
                    $attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW]
                );
            }
        }
    }

    /**
     *
     * unittest ?
     *
     * @param \DOMElement $element
     * @param array $attributesOrAttribute
     */
    public function attributeRebuildDetailMultiOrOnceOptional(\DOMElement $element, $attributesOrAttribute = [])
    {
        if (isset($attributesOrAttribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW], $attributesOrAttribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY])) {
            $attributesOrAttribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW] = $this->registerUseOn($attributesOrAttribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW]);
            $this->attributeRebuildDetailOnceOptional($element, $attributesOrAttribute);
        } else if (is_array($attributesOrAttribute)) {
            foreach ($attributesOrAttribute as $attribute) {
                $attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW] = $this->registerUseOn($attribute[RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW]);
                $this->attributeRebuildDetailOnceOptional($element, $attribute);
            }
        }
    }

    /**
     * change the value in the register with easy-functions or with self defined calculations
     *
     * @param string $action
     * @param string $marker
     * @param string $value
     */
    public function registerRebuildDetailSwitchToAction($action = '', $marker = '', $value = '')
    {
        if (!empty($marker)) {
            $oldValue = $this->getNamedRegister($marker);
            $flagCalculate = true;
            switch ($action) {
                case self::SUB_PARAM_REGISTER_ACTION_APPEND :
                    $newValue = $oldValue . $value;
                    $flagCalculate = false;
                    break;
                case self::SUB_PARAM_REGISTER_ACTION_PREPEND :
                    $newValue = $value . $oldValue;
                    $flagCalculate = false;
                    break;
                case self::SUB_PARAM_REGISTER_ACTION_ADD :
                    $newValue = $oldValue . '+' . $value;
                    break;
                case self::SUB_PARAM_REGISTER_ACTION_SUB :
                    $newValue = $oldValue . '-' . $value;
                    break;
                case self::SUB_PARAM_REGISTER_ACTION_ADD_INT :
                    $newValue = 'round (' . $oldValue . '+' . $value . ')';
                    break;
                case self::SUB_PARAM_REGISTER_ACTION_SUB_INT :
                    $newValue = 'round (' . $oldValue . '-' . $value . ')';
                    break;
                case self::SUB_PARAM_REGISTER_ACTION_INC :
                    $newValue = $oldValue . '+ 1';
                    break;
                case self::SUB_PARAM_REGISTER_ACTION_DEC :
                    $newValue = $oldValue . '- 1';
                    break;
                case self::SUB_PARAM_REGISTER_ACTION_RENEW :
                    $flagCalculate = false;
                    $newValue = $oldValue;
                    break;
                case self::SUB_PARAM_REGISTER_ACTION_CALCULATE :
                    $newValue = $oldValue;
                    break;
                default :
                    break;
            }
            if ($flagCalculate) {
                $newValue = $this->calculate->calculate($newValue);
            }
            $this->setNamedRegister($marker, $newValue);
        }
    }

    /**
     * the DOM will be uncvhange - only the internal register would be changed regarding to the values in the array $param
     *
     * unittest ?
     *
     * @param array $param
     * @param \DOMDocument|null $xmlDom
     * @return \DOMDocument
     */
    public function registerRebuildDetailDoIt($param, \DOMDocument $xmlDom = null)
    {
        $items = $param[self::ITEM_TYPE_PARAM];
        $marker = $item[self::SUB_PARAM_REGISTER_MARKER];
        $value = '';
        if (isset($item[self::SUB_PARAM_REGISTER_VALUE])) {
            $value = $item[self::SUB_PARAM_REGISTER_VALUE];
        }
        $action = $item[self::SUB_PARAM_REGISTER_ACTION];
        self::registerRebuildDetailSwitchToAction($action, $marker, $value);

        return $xmlDom;
    }

    /**
     * the delete-Action will delete the the DOM will be unchange - only the internal register would be changed regarding to the values in the array $param
     *
     * unittest ?
     *
     * @param array $param
     * @param \DOMDocument|null $xmlDom
     * @return \DOMDocument
     */
    public function deleteRebuildDetailDoIt($param, \DOMDocument $xmlDom = null)
    {
        $xmlPath = new \DOMXpath($xmlDom);
        $count = $param[self::ITEM_REPEAT_MAX];
        // generic construct - contain the parameter of delete
        $items = $param[self::ITEM_TYPE_PARAM];
        $elements = $xmlPath->query($param[self::ITEM_X_PATH]);
        if ($elements->length > 0) {
            /** @var \DOMNode|\DOMElement $element */
            foreach ($elements as $element) {
                if ($count === 0) {
                    $this->validateNotifyService->registerValidationMistakes(
                        false,
                        ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                        [
                            'errorXPath' => $param[self::ITEM_X_PATH],
                            'emptyElement' => $element
                        ]
                    );
                    break;
                }
                switch ($items[self::SUB_PARAM_DELETE_TYPE]) {
                    case self::SUB_PARAM_DELETE_TYPUS_ATTRIBUTE :
                        if ((isset($items[SUB_PARAM_DELETE_ATTRIBUTE])) &&
                            (!empty($items[SUB_PARAM_DELETE_ATTRIBUTE])) &&
                            $element->hasAttribute($items[SUB_PARAM_DELETE_ATTRIBUTE])
                        ) {
                            $element->removeAttribute($items[SUB_PARAM_DELETE_ATTRIBUTE]);
                        }
                        break;
                    case self::SUB_PARAM_DELETE_TYPUS_NODE :
                        // I hope, that this work and that the loop does not make any problems?
                        $element->parentNode->removeChild($element);
                        break;
                    default :
                        break;
                }
            }
        }
        return $xmlDom;
    }

    /**
     * override or add attributes in tags defined by xPath and repeatMax
     * if the new value is empty, then the attribute will he removed
     *
     * unittest ?
     *
     * @param array $param
     * @param \DOMDocument|null $xmlDom
     * @return \DOMDocument
     */
    public function attributeRebuildDetailDoIt($param, \DOMDocument $xmlDom = null)
    {
        $xmlPath = new \DOMXpath($xmlDom);
        $count = $param[self::ITEM_REPEAT_MAX];
        $items = $param[self::ITEM_TYPE_PARAM];
        $elements = $xmlPath->query($param[self::ITEM_X_PATH]);
        if ($elements->length > 0) {
            /** @var \DOMNode|\DOMElement $element */
            foreach ($elements as $element) {
                if ($count === 0) {
                    $this->validateNotifyService->registerValidationMistakes(
                        false,
                        ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                        [
                            'errorXPath' => $param[self::ITEM_X_PATH],
                            'emptyElement' => $element
                        ]
                    );
                    break;
                }
                if ($element->nodeType === XML_ELEMENT_NODE) {
                    self::attributeRebuildDetailMultiOrOnceOptional($element, $items);
                    $count--;
                }
            }
        }
        return $xmlDom;
    }

    /**
     * add child in oder after tags defined by xPath and repeatMax
     * restore the Quote-Redefinition
     *
     * unittest ?
     *
     * @param array $param
     * @param \DOMDocument $xmlDom
     *
     * @return \DOMDocument
     */
    public function childRebuildDetailDoIt($param, \DOMDocument $xmlDom)
    {
        $xmlPath = new \DOMXpath($xmlDom);
        $count = $param[self::ITEM_REPEAT_MAX];
        $childArray = $param[self::ITEM_TYPE_PARAM];

        $elements = $xmlPath->query($param[self::ITEM_X_PATH]);

        if ($elements->length > 0) {
            $childArray[self::SUB_PARAM_CHILD_XML] = $this->registerUseOn($childArray[self::SUB_PARAM_CHILD_XML] );

            /** @var \DOMNode|\DOMElement $element */
            foreach ($elements as $element) {
                if (
                ($count === 0)
                ) {
                    $this->validateNotifyService->registerValidationMistakes(
                        false,
                        ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                        [
                            'errorXPath' => $param[self::ITEM_X_PATH],
                            'emptyElement' => $element
                        ]
                    );
                    break;
                }

                if ($element->nodeType === XML_ELEMENT_NODE) {
                    $fragment = $xmlDom->createDocumentFragment();
                    $fragment->appendXML($childArray[self::SUB_PARAM_CHILD_XML]);

                    switch ($childArray[self::SUB_PARAM_CHILD_TYPE]) {
                        case RebuildBaseService::SUB_PARAM_CHILD_TYPUS_PREPEND:
                            $element->insertBefore($fragment, $element->firstChild);
                            break;
                        case RebuildBaseService::SUB_PARAM_CHILD_TYPUS_APPEND:
                            $element->appendChild($fragment);
                            break;
                        case RebuildBaseService::SUB_PARAM_CHILD_TYPUS_BEFORE:
                            if (!is_null($element->parentNode)) {
                                $firstSibling = $element->parentNode->firstChild;
                                $element->parentNode->insertBefore($fragment, $firstSibling);
                            }
                            break;
                        case RebuildBaseService::SUB_PARAM_CHILD_TYPUS_AFTER:
                            if (!is_null($element->parentNode)) {
                                $element->parentNode->appendChild($fragment);
                            }
                            break;
                        default:
                            $this->validateNotifyService->registerValidationMistakes(
                                false,
                                ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                                [
                                    'errorXPath' => $param[self::ITEM_X_PATH],
                                    'emptyElement' => $element
                                ]
                            );
                            if (!is_null($element->parentNode)) {
                                $element->parentNode->appendChild($fragment);
                            }
                            break;
                    }
                    $count--;
                }
            }
        }


        return $xmlDom;
    }


    /**
     *
     * unittest ?
     *
     * @param string $list
     * @return array|ø
     */
    public function classArrayFromList($list = '')
    {
        return array_filter(
            array_map(
                'trim',
                explode(
                    ' ',
                    $list
                )
            )
        );

    }

    /**
     *
     * unittest ?
     *
     * @param array $base
     * @param array $override
     * @return array
     */
    public function arrayRebuildOverride($base = [], $override = [])
    {
        return array_merge(
            array_intersect($base, $override),
            array_diff($base, $override),
            array_diff($override, $base)
        );
    }

    /**
     *
     * unittest ?
     *
     * @param array $base
     * @param array $removables
     * @return array
     */
    public function arrayRebuildRemove($base = [], $removables = [])
    {
        return array_diff($base, $removables);
    }

    /**
     *
     * unittest ?
     *
     * @param array $base
     * @param array $addables
     * @return array
     */
    public function arrayRebuildAdd($base = [], $addables = [])
    {
        return array_merge($base, $addables);
    }

    /**
     * use array_opration to override, remove and add classes to the current array of classes
     *
     * paradima
     * - every class is unique in the list of classes of the tag
     * - the name can gericly defined
     *
     * unittest ?
     *
     * @param array $classActionArray
     * @param string $classList
     * @return string
     */
    public function classRebuildDetailActions($classActionArray = [], $classList = '')
    {
        $classList = trim($classList);
        if (!empty($classList)) {
            $classArray = self::classArrayFromList($classList);
        } else {
            $classArray = [];
        }
        if ((isset($classActionArray[RebuildService::SUB_PARAM_CLASS_OVERRIDE])) &&
            (!empty($classOverride = self::classArrayFromList(
                $classActionArray[RebuildService::SUB_PARAM_CLASS_OVERRIDE]
            )))
        ) {
            $classArray = self::arrayRebuildOverride(
                $classArray,
                $classOverride
            );
        }
        if ((isset($classActionArray[RebuildService::SUB_PARAM_CLASS_REMOVE])) &&
            (!empty($classRemove = self::classArrayFromList(
                $classActionArray[RebuildService::SUB_PARAM_CLASS_REMOVE]
            )))
        ) {
            $classArray = self::arrayRebuildRemove(
                $classArray,
                $classRemove
            );
        }
        if ((isset($classActionArray[RebuildService::SUB_PARAM_CLASS_ADD])) &&
            (!empty($classAdd = self::classArrayFromList(
                $classActionArray[RebuildService::SUB_PARAM_CLASS_ADD]
            )))
        ) {
            $classArray = self::arrayRebuildAdd(
                $classArray,
                $classAdd
            );
        }
        return implode(' ', $classArray);
    }

    /**
     * use array_opration to ...
     *
     * paradima
     * - every style is unique in the list of style-definition
     * - the name can gericly defined
     *
     * restrictions
     * - the method don't check single enties against aggregated entities
     *
     * unittest ?
     *
     * @param array $classActionArray
     * @param string $classList
     * @return string
     */
    public function styleRebuildDetailActions($styleActionArray = [], $styleList = '')
    {
//        $styleList .= "; color:#FF00FF;";
        $styleList = $this->registerUseOn($styleList);

        $styleArray = array_filter(explode(';', $styleList));
        $styleKeyItem = [];
        foreach ($styleArray as $styleItem) {
            $cssPart = explode(':', $styleItem, 2);
            $styleKeyItem[$cssPart[0]] = $cssPart[1];
        }
        $styleActionKeyItem = [];
        foreach ($styleActionArray as $styleActionItem) {
            if (is_array($styleActionItem)) {
                $flagType = RebuildService::SUB_PARAM_STYLE_TYPUS_OVERRIDE;
                if ((isset($styleActionItem[RebuildService::SUB_PARAM_STYLE_TYPE])) &&
                    (!empty(trim($styleActionItem[RebuildService::SUB_PARAM_STYLE_TYPE])))
                ) {
                    $flagType = $styleActionItem[RebuildService::SUB_PARAM_STYLE_TYPE];
                }
                if (!isset($styleActionKeyItem[$flagType])) {
                    $styleActionKeyItem[$flagType] = [];
                }
                $styleActionKeyItem[$flagType][$styleActionItem[RebuildService::SUB_PARAM_STYLE_KEY]] =
                    $styleActionItem[RebuildService::SUB_PARAM_STYLE_NEW];
            } else {
                $flagType = RebuildService::SUB_PARAM_STYLE_TYPUS_OVERRIDE;
                if ((isset($styleActionArray[RebuildService::SUB_PARAM_STYLE_TYPE])) &&
                    (!empty(trim($styleActionArray[RebuildService::SUB_PARAM_STYLE_TYPE])))
                ) {
                    $flagType = $styleActionArray[RebuildService::SUB_PARAM_STYLE_TYPE];
                }
                $styleActionKeyItem[$flagType] = [];
                $styleActionKeyItem[$flagType][$styleActionArray[RebuildService::SUB_PARAM_STYLE_KEY]] =
                    $styleActionArray[RebuildService::SUB_PARAM_STYLE_NEW];;
            }
        }
        // do the different action
        if (
            (isset($styleActionKeyItem[RebuildService::SUB_PARAM_STYLE_TYPUS_OVERRIDE])) &&
            (!empty($styleActionKeyItem[RebuildService::SUB_PARAM_STYLE_TYPUS_OVERRIDE]))
        ) {
            $styleKeyItem = self::arrayRebuildOverride(
                $styleKeyItem,
                $styleActionKeyItem[RebuildService::SUB_PARAM_STYLE_TYPUS_OVERRIDE]
            );
        }
        if (
            (isset($styleActionKeyItem[RebuildService::SUB_PARAM_STYLE_TYPUS_REMOVE])) &&
            (!empty($styleActionKeyItem[RebuildService::SUB_PARAM_STYLE_TYPUS_REMOVE]))
        ) {
            $styleKeyItem = self::arrayRebuildRemove(
                $styleKeyItem,
                $styleActionKeyItem[RebuildService::SUB_PARAM_STYLE_TYPUS_REMOVE]
            );
        }
        if (
            (isset($styleActionKeyItem[RebuildService::SUB_PARAM_STYLE_TYPUS_ADD])) &&
            (!empty($styleActionKeyItem[RebuildService::SUB_PARAM_STYLE_TYPUS_ADD]))
        ) {
            $styleKeyItem = self::arrayRebuildAdd(
                $styleKeyItem,
                $styleActionKeyItem[RebuildService::SUB_PARAM_STYLE_TYPUS_ADD]
            );
        }
        $styleArray = [];
        foreach ($styleKeyItem as $styleKey => $styleValue) {
            $styleArray[] = $styleKey . ':' . $styleValue;
        }

        return implode(';', $styleArray);

    }

    /**
     * add, override or remove classe / styles of tags defined by xPath and repeatMax
     *
     * unittest ?
     *
     * @param array $param
     * @param \DOMDocument|null $xmlDom
     * @return \DOMDocument
     */
    public function generalAttributeRebuildDetail($attributeName, $param, \DOMDocument $xmlDom = null)
    {
        $xmlPath = new \DOMXpath($xmlDom);
        $count = $param[self::ITEM_REPEAT_MAX];
        $itemParamArray = $param[self::ITEM_TYPE_PARAM];
        $generalNameFunktion = $attributeName . self::GENERIC_NAME_FOR_REBUILD_DETAIL_ACTIONS;
        $elements = $xmlPath->query($param[self::ITEM_X_PATH]);
        if ($elements->length > 0) {
            /** @var \DOMNode|\DOMElement $element */
            foreach ($elements as $element) {
                if ($count === 0) {
                    $this->validateNotifyService->registerValidationMistakes(
                        false,
                        ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                        [
                            'errorXPath' => $param[self::ITEM_X_PATH],
                            'emptyElement' => $element
                        ]
                    );
                    break;
                }
                if ($element->nodeType === XML_ELEMENT_NODE) {
                    $currentValue = (($element->hasAttribute($attributeName)) ?
                        $element->getAttribute($attributeName) :
                        '');
                    $element->setAttribute(
                        $attributeName,
                        self::$generalNameFunktion(
                            $itemParamArray,
                            $currentValue
                        )
                    );
                    $count--;
                }
            }
        }
        return $xmlDom;
    }


    /**
     * add, override or remove classes in tags defined by xPath and repeatMax
     *
     * unittest ?
     *
     * @param array $param
     * @param \DOMDocument|null $xmlDom
     * @return \DOMDocument
     */
    public function classRebuildDetailDoIt($param, \DOMDocument $xmlDom = null)
    {
        $generalAttributeName = 'class';
        $xmlDom = self::generalAttributeRebuildDetail(
            $generalAttributeName,
            $param,
            $xmlDom
        );
        return $xmlDom;
    }

    /**
     * add, override or remove styles of tags defined by xPath and repeatMax
     *
     * unittest
     *
     * @param array $param
     * @param \DOMDocument|null $xmlDom
     * @return \DOMDocument
     */
    public function styleRebuildDetailDoIt($param, \DOMDocument $xmlDom = null)
    {
        $generalAttributeName = 'style';
        $xmlDom = self::generalAttributeRebuildDetail(
            $generalAttributeName,
            $param,
            $xmlDom
        );
        return $xmlDom;
    }


    /**
     * reduce the length of text to an defined numbers of characters, if the parameter maxLength-Parameter is defined
     *
     * unittest 20170709
     * tested in RebuildServicePartTwoValueTest
     *
     * @param $param
     * @param string $newText
     * @return string
     */
    public function valueRebuildDetailActionTextToMaxLength($param = [], $newText = '')
    {
        if (isset($param[RebuildService::SUB_PARAM_VALUE_MAX_LENGTH]) &&
            ($param[RebuildService::SUB_PARAM_VALUE_MAX_LENGTH] < mb_strlen($newText))
        ) {
            $newText = mb_substr($newText, 0, $param[RebuildService::SUB_PARAM_VALUE_MAX_LENGTH]);
        }
        return $newText;
    }


    /**
     * Append/add the new-text at the end of the Text of the Node and reduce to the length, if an parameter is set
     * !!! injected tags will be overwritten everytime by the value-action !!!
     *
     * unittest 20170709
     * tested in RebuildServicePartTwoValueTest
     *
     * @param $param
     * @param \DOMNode $element
     * @return void
     */
    public function valueRebuildDetailActionAppend($param, \DOMNode $element)
    {
        $appendText = $element->textContent;
        $appendText .= $param[RebuildService::SUB_PARAM_VALUE_NEW];
        $appendText = self::valueRebuildDetailActionTextToMaxLength($param, $appendText);
        $element->textContent = $appendText;
    }

    /**
     * replace the text-part defined in 'old' via normal replacement with the text-part´defined in 'new', if 'old' is set with ean not empty value,
     * If 'old' is not defined or empty, the method will override the content
     * !!! injected tags will be overwritten everytime by the value-action !!!
     *
     * unittest ?
     *
     * @param $param
     * @param \DOMNode $element
     * @return void
     */
    public function valueRebuildDetailActionNormal($param, \DOMNode $element)
    {
        if ((!isset($param[RebuildService::SUB_PARAM_VALUE_MARKER])) ||
            (!is_string($param[RebuildService::SUB_PARAM_VALUE_MARKER])) ||
            (empty($param[RebuildService::SUB_PARAM_VALUE_MARKER]))
        ) {
            self::valueRebuildDetailActionOverride($param, $element);
        } else {
            $normalReplaceText = $element->textContent;
            $normalReplaceText = str_replace(
                $param[RebuildService::SUB_PARAM_VALUE_MARKER],
                $param[RebuildService::SUB_PARAM_VALUE_NEW],
                $normalReplaceText
            );
            $normalReplaceText = self::valueRebuildDetailActionTextToMaxLength($param, $normalReplaceText);
            $element->textContent = $normalReplaceText;
        }
    }

    /**
     * override the text-value with a new entry
     * !!! injected tags will be overwritten everytime by the value-action !!!
     *
     * unittest ?
     *
     * @param $param
     * @param \DOMNode $element
     * @return void
     */
    public function valueRebuildDetailActionOverride($param, \DOMNode $element)
    {
        $overrideText = self::valueRebuildDetailActionTextToMaxLength($param, $param[self::SUB_PARAM_VALUE_NEW]);
        $element->textContent = $overrideText;

    }

    /**
     * Prepend/add the new-text at the start of the Text of the Node and reduce to the length, if an parameter is set
     * !!! injected tags will be overwritten everytime by the value-action !!!
     *
     * unittest 20170709
     * tested in RebuildServicePartTwoValueTest
     *
     * @param $param
     * @param \DOMNode $element
     * @return void
     */
    public function valueRebuildDetailActionPrepend($param, \DOMNode $element)
    {
        $prependText = $element->textContent;
        $prependText = $param[RebuildService::SUB_PARAM_VALUE_NEW] . $prependText;
        $prependText = self::valueRebuildDetailActionTextToMaxLength($param, $prependText);
        $element->textContent = $prependText;
    }

    /**
     * !!!Attention NOT Multi-Byte-Snensitive!!!
     * replace the text-part defined in 'old' via regular replacement with the text-part´defined in 'new', if 'old' is set with ean not empty value,
     * If 'old' is not defined or empty, the method will override the content
     * !!! injected tags will be overwritten everytime by the value-action !!!
     *
     * unittest ?
     *
     * @param $param
     * @param \DOMNode $element
     * @return void
     */
    public function valueRebuildDetailActionRegular($param, \DOMNode $element)
    {
        if ((!isset($param[RebuildService::SUB_PARAM_VALUE_MARKER])) ||
            (!is_string($param[RebuildService::SUB_PARAM_VALUE_MARKER])) ||
            (empty($param[RebuildService::SUB_PARAM_VALUE_MARKER]))
        ) {
            self::valueRebuildDetailActionOverride($param, $element);
        } else {
            $regularReplaceText = $element->textContent;
            $pattern = '/' . str_replace(
                    "'",
                    "\\'",
                    str_replace('/', '\/', $param[RebuildService::SUB_PARAM_VALUE_MARKER])
                ) . '/u';

            $regularReplaceText = preg_replace(
                $pattern,
                $param[RebuildService::SUB_PARAM_VALUE_NEW],
                $regularReplaceText
            );
            $regularReplaceText = self::valueRebuildDetailActionTextToMaxLength($param, $regularReplaceText);
            $element->textContent = $regularReplaceText;
        }

    }

    /**
     * replace the text-part defined in 'old' via regular replacement with the text-part´defined in 'new', if 'old' is set with ean not empty value,
     * If 'old' is not defined or empty, the method will override the content
     * !!! injected tags will be overwritten everytime by the value-action !!!
     *
     * unittest ?
     *
     * @param $param
     * @param \DOMNode $element
     * @return void
     */
    public function valueRebuildDetailActionMbRegular($param, \DOMNode $element)
    {
        if ((!isset($param[RebuildService::SUB_PARAM_VALUE_MARKER])) ||
            (!is_string($param[RebuildService::SUB_PARAM_VALUE_MARKER])) ||
            (empty($param[RebuildService::SUB_PARAM_VALUE_MARKER]))
        ) {
            self::valueRebuildDetailActionOverride($param, $element);
        } else {
            $mbRegularReplaceText = $element->textContent;
            $pattern = '/' . str_replace(
                    "'",
                    "\\'",
                    str_replace('/', '\/', trim($param[RebuildService::SUB_PARAM_VALUE_MARKER]))
                ) . '/u';

            $mbRegularReplaceText = mb_ereg_replace(
                $pattern,
                $param[RebuildService::SUB_PARAM_VALUE_NEW],
                $mbRegularReplaceText
            );
            $mbRegularReplaceText = self::valueRebuildDetailActionTextToMaxLength($param, $mbRegularReplaceText);
            $element->textContent = $mbRegularReplaceText;
        }
    }

    /**
     * switch to the different actiontype of replaceing the content
     * !!! injected tags will be overwritten everytime by the value-action !!!
     *
     * unittest ?
     *
     * @param $param
     * @param \DOMNode $element
     */
    public function valueRebuildDetailActions($param, \DOMNode $element)
    {
        $param[RebuildBaseService::SUB_PARAM_VALUE_NEW] = $this->registerUseOn($param[RebuildBaseService::SUB_PARAM_VALUE_NEW]);

        switch ($param[self::SUB_PARAM_VALUE_TYPE]) {
            case self::SUB_PARAM_VALUE_TYPUS_APPEND:
                self::valueRebuildDetailActionAppend($param, $element);
                break;
            case self::SUB_PARAM_VALUE_TYPUS_NORMAL:
                self::valueRebuildDetailActionNormal($param, $element);
                break;
            case self::SUB_PARAM_VALUE_TYPUS_OVERRIDE:
                self::valueRebuildDetailActionOverride($param, $element);
                break;
            case self::SUB_PARAM_VALUE_TYPUS_PREPEND:
                self::valueRebuildDetailActionPrepend($param, $element);
                break;
            case self::SUB_PARAM_VALUE_TYPUS_REGULAR:
                self::valueRebuildDetailActionRegular($param, $element);
                break;
            case self::SUB_PARAM_VALUE_TYPUS_MB_REGULAR:
                self::valueRebuildDetailActionMbRegular($param, $element);
                break;
            default :
                $this->validateNotifyService->registerValidationMistakes(
                    false,
                    ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                    $param[self::SUB_PARAM_VALUE_TYPE]
                );
                // do nothing
                break;
        }
    }

    /**
     * override or change values of tags defined by xPath and repeatMax
     * paradigm: The param-array is valid
     * !!! injected tags will be overwritten everytime by the value-action !!!
     *
     * unittest ?
     *
     * @param array $param
     * @param \DOMDocument|null $xmlDom
     * @return \DOMDocument
     */
    public function valueRebuildDetailDoIt($param, \DOMDocument $xmlDom = null)
    {
        $xmlPath = new \DOMXpath($xmlDom);
        $count = $param[self::ITEM_REPEAT_MAX];
        $valueArray = $param[self::ITEM_TYPE_PARAM];
        $elements = $xmlPath->query($param[self::ITEM_X_PATH]);
        if ($elements->length > 0) {
            /** @var \DOMNode|\DOMElement $element */
            foreach ($elements as $element) {
                if (
                ($count === 0)
                ) {
                    $this->validateNotifyService->registerValidationMistakes(
                        false,
                        ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                        [
                            'errorXPath' => $param[self::ITEM_X_PATH],
                            'emptyElement' => $element
                        ]
                    );
                    break;
                }
                if ($element->nodeType === XML_ELEMENT_NODE) {
                    self::valueRebuildDetailActions($valueArray, $element);
                    $count--;
                }
            }
        }

        return $xmlDom;
    }

    /**
     * restriction:
     * all parameter must be not null
     *
     * unittest ?
     *
     * @param $name
     * @param $value
     * @param \DOMElement $element
     */
    public function generalRebuildDetailOverrideAttribute($name, $value, \DOMElement $element)
    {

        if ($element->hasAttribute($name)) {
            $element->removeAttribute($name);
        }
        $element->setAttribute($name, $value);
    }


    /**
     * @param $name
     * @param $value
     * @param \DOMElement $element
     */
    public function generalRebuildDetailOverrideAttributeOptional($key, $param, \DOMElement $element)
    {
        if (isset($param[$key])) {
            self::generalRebuildDetailOverrideAttribute($key, $param[$key], $element);
        }
    }

    /**
     * add use-tags in tags defined by xPath and repeatMax - allow building of fractals
     *
     * There is no check to validate the href-definition
     *
     * unittest ?
     *
     * @param array $param
     * @param \DOMElement $element
     */
    public function useRebuildDetailActions($param, \DOMElement $element)
    {
        $newUse = new \DOMElement('use');
        $param[RebuildBaseService::SUB_PARAM_USE_HREF] = $this->registerUseOn($param[RebuildBaseService::SUB_PARAM_USE_HREF]);

        self::generalRebuildDetailOverrideAttribute(
            RebuildBaseService::SUB_PARAM_USE_HREF,
            $param[RebuildBaseService::SUB_PARAM_USE_HREF],
            $newUse);

        if (isset($param[RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES])) {
            self::attributeRebuildDetailMultiOrOnceOptional(
                $newUse,
                $param[RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES]);
        }

        $type = (isset($param[RebuildBaseService::SUB_PARAM_USE_TYPE])) ?
            $param[RebuildBaseService::SUB_PARAM_USE_TYPE] :
            RebuildBaseService::SUB_PARAM_USE_TYPUS_APPEND;
        switch ($type) {
            case RebuildBaseService::SUB_PARAM_USE_TYPUS_APPEND :
                $element->appendChild($newUse);
                break;
            case RebuildBaseService::SUB_PARAM_USE_TYPUS_PREPEND :
                $element->insertBefore($newUse, $element->firstChild);
                break;
            case RebuildBaseService::SUB_PARAM_USE_TYPUS_BEFORE :
                if (!is_null($element->parentNode)) {
                    $firstSibling = $element->parentNode->firstChild;
                    $element->parentNode->insertBefore($newUse, $firstSibling);
                }
                break;
            case RebuildBaseService::SUB_PARAM_USE_TYPUS_AFTER :
                if (!is_null($element->parentNode)) {
                    $element->parentNode->appendChild($newUse);
                }
                break;
            default:
                $this->validateNotifyService->registerValidationMistakes(
                    false,
                    ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                    $type
                );
                if (!is_null($element->parentNode)) {
                    $element->parentNode->appendChild($newUse);
                }
                break;
        }
    }

    /**
     * add use-tags in tags defined by xPath and repeatMax - allow building of fractals
     *
     * unittest ?
     *
     * @param array $param
     * @param \DOMDocument|null $xmlDom
     * @return \DOMDocument
     */
    public function useRebuildDetailDoIt($param, \DOMDocument $xmlDom = null)
    {
        $xmlPath = new \DOMXpath($xmlDom);
        $count = $param[self::ITEM_REPEAT_MAX];
        $useArray = $param[self::ITEM_TYPE_PARAM];
        $elements = $xmlPath->query($param[self::ITEM_X_PATH]);
        if ($elements->length > 0) {
            /** @var \DOMNode|\DOMElement $element */
            foreach ($elements as $element) {
                if (
                ($count === 0)
                ) {
                    $this->validateNotifyService->registerValidationMistakes(
                        false,
                        ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                        [
                            'errorXPath' => $param[self::ITEM_X_PATH],
                            'emptyElement' => $element
                        ]
                    );
                    break;
                }
                if ($element->nodeType === XML_ELEMENT_NODE) {
                    self::useRebuildDetailActions($useArray, $element);
                    $count--;
                }
            }
        }

        return $xmlDom;
    }

    /**
     * add use-tags in tags defined by xPath and repeatMax - allow building of fractals
     *
     * There is no check to validate the href-definition
     *
     * unittest ?
     *
     * @param array $param
     * @param \DOMElement $element
     */
    public function imageRebuildDetailActions($param, \DOMElement $element)
    {
        $newImage = new \DOMElement('image');

        $param[RebuildBaseService::SUB_PARAM_USE_HREF] = $this->registerUseOn($param[RebuildBaseService::SUB_PARAM_USE_HREF]);
        self::generalRebuildDetailOverrideAttribute(
            RebuildBaseService::SUB_PARAM_USE_HREF,
            $param[RebuildBaseService::SUB_PARAM_USE_HREF],
            $newImage);
        if (isset($param[RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES])) {
            self::attributeRebuildDetailMultiOrOnceOptional(
                $newImage,
                $param[RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES]);
        }
        if (isset($param[RebuildBaseService::SUB_PARAM_IMAGE_EXTERNAL_RESOURCES_REQUIRED])) {
            self::attributeRebuildDetailOnceOptional(
                $element,
                [
                    RebuildBaseService::SUB_PARAM_IMAGE_EXTERNAL_RESOURCES_REQUIRED =>
                        $param[RebuildBaseService::SUB_PARAM_IMAGE_EXTERNAL_RESOURCES_REQUIRED]
                ]
            );
        }
        if ((isset($param[RebuildBaseService::SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO])) &&
            (array_search($param[RebuildBaseService::SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO],
                    RebuildBaseService::SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO_LIST) !== false)
        ) {
            self::attributeRebuildDetailOnceOptional(
                $element,
                [
                    RebuildBaseService::SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO =>
                        $param[RebuildBaseService::SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO]
                ]
            );
        }
        $type = (isset($param[RebuildBaseService::SUB_PARAM_USE_TYPE])) ?
            $param[RebuildBaseService::SUB_PARAM_USE_TYPE] :
            RebuildBaseService::SUB_PARAM_USE_TYPUS_APPEND;
        switch ($type) {
            case RebuildBaseService::SUB_PARAM_USE_TYPUS_APPEND :
                $element->appendChild($newImage);
                break;
            case RebuildBaseService::SUB_PARAM_USE_TYPUS_PREPEND :
                $element->insertBefore($newImage, $element->firstChild);
                break;
            case RebuildBaseService::SUB_PARAM_USE_TYPUS_BEFORE :
                if (!is_null($element->parentNode)) {
                    $firstSibling = $element->parentNode->firstChild;
                    $element->parentNode->insertBefore($newImage, $firstSibling);
                }
                break;
            case RebuildBaseService::SUB_PARAM_USE_TYPUS_AFTER :
                if (!is_null($element->parentNode)) {
                    $element->parentNode->appendChild($newImage);
                }
                break;
            default:
                $this->validateNotifyService->registerValidationMistakes(
                    false,
                    ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                    $type
                );
                if (!is_null($element->parentNode)) {
                    $element->parentNode->appendChild($newImage);
                }
                break;
        }

    }

    public function imageRebuildDetailDoIt($param, \DOMDocument $xmlDom = null)
    {
        $xmlPath = new \DOMXpath($xmlDom);
        $count = $param[self::ITEM_REPEAT_MAX];
        $imageArray = $param[self::ITEM_TYPE_PARAM];
        $elements = $xmlPath->query($param[self::ITEM_X_PATH]);
        if ($elements->length > 0) {
            /** @var \DOMNode|\DOMElement $element */
            foreach ($elements as $element) {
                if (
                ($count === 0)
                ) {
                    $this->validateNotifyService->registerValidationMistakes(
                        false,
                        ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                        [
                            'errorXPath' => $param[self::ITEM_X_PATH],
                            'emptyElement' => $element
                        ]
                    );
                    break;
                }
                if ($element->nodeType === XML_ELEMENT_NODE) {
                    self::imageRebuildDetailActions($imageArray, $element);
                    $count--;
                }
            }
        }

        return $xmlDom;

    }

    /**
     *
     * unittest ?
     *
     * @param \DOMDocument|null $xmlDom
     * @param array $rebuildArray
     * @param string $rebuildNmae
     * @return \DOMDocument
     */
    public function itemRebuildProcess(array $item, \DOMDocument $xmlDom = null)
    {
        $param = [
            self::ITEM_TYPE_PARAM => [],
            self::ITEM_REPEAT_MAX => $this->getRepeatMax(),
            self::ITEM_X_PATH => ''
        ];
        $typeFunc = 'dummy' . self::GENERIC_NAME_FOR_REBUILD_ACTIONS;
        foreach ($item as $key => $part) {
            switch ($key) {
                case RebuildService::ITEM_X_PATH :
                    $param[self::ITEM_X_PATH] = trim($part);
                    break;
                case RebuildService::ITEM_REPEAT_MAX :
                    $param[self::ITEM_REPEAT_MAX] = (int)$part;
                    break;
                default:
                    if ((empty($param[self::ITEM_TYPE_PARAM])) &&
                        (array_search($key, RebuildBaseService::SUB_PARAM_ARRAY) !== false)
                    ) {
                        $typeFunc = trim($key) . self::GENERIC_NAME_FOR_REBUILD_ACTIONS;
                        $param[self::ITEM_TYPE_PARAM] = array_filter($part);
                    }
                    break;
            }
        }
        if (is_callable([$this, $typeFunc])) {
            $xmlDom = $this->$typeFunc($param, $xmlDom);
        } else {
            $this->validateNotifyService->registerValidationMistakes(
                false,
                ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                $typeFunc
            );
        }
        return $xmlDom;
    }

    /**
     *
     * unittest ?
     *
     * @param array $items
     * @param \DOMDocument|null $xmlDom
     * @return \DOMDocument
     */
    public function itemListRebuildProcess($items = [], \DOMDocument $xmlDom = null)
    {
        foreach ($items as $item) {
            $xmlDom = self::itemRebuildProcess(
                $item,
                $xmlDom
            );
        }

        return $xmlDom;
    }


}

?>
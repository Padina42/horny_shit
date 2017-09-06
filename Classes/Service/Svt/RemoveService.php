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
 * class RemoveService
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

class RemoveService
{

    protected $validationError = '';

    const PARAM_ATTRIBUTES = 'attributes';
    const PARAM_TAGS = 'tags';
    const PARAM_SET = 'addSet';
    const REPLACE_PARAM_SEPARATOR = ',';

    const PARAM_DEFAULT_ATTRIBUTES = 'defAttributes';
    const PARAM_DEFAULT_TAGS = 'defTags';

    const INITIATIVE_DEFAULT_ATTRIBUTE_LIST = 'requiredExtensions, requiredFeatures, systemLanguage, ' . // Conditional processing attributes
    'requiredExtensions, requiredFeatures, ' . // Document event attributes
    'onactivate, onclick, onfocusin, onfocusout, onload, onmousedown, onmousemove, onmouseout, onmouseover, onmouseup';
    const INITIATIVE_DEFAULT_LIST_TAGS = 'audio, canvas, clipPath, color-profile, desc, filter, foreignObject, frame, ' .
    'hatch, hatchpath, html, iframe, linearGradient, marker, mesh, meshgradient, meshpatch, meshrow, metadata, ' .
    'pattern, radialGradient, script, svg, symbol, title, unknown, video, view';

    // see categorisation in https://developer.mozilla.org/de/docs/Web/SVG/Attribute 20170618
    protected $defaultAttributes = self::INITIATIVE_DEFAULT_ATTRIBUTE_LIST; // Graphical event attributes


//    // see https://developer.mozilla.org/de/docs/Web/SVG/Attribute
//    protected $defaultTags = 'svg, unknown, frame, iframe, html,' . //Container element
//    'desc, metadata, title,' . //Descriptive elements
//    'audio, canvas, iframe, video, ' . // HTML elements
//    'clipPath, defs, hatch, linearGradient, marker, mask, meshgradient, metadata, pattern, radialGradient, script, style, symbol, title,' . // Never-rendered elements
//    'audio, canvas, foreignObject, iframe, mesh, svg,  unknown, video, ' .
//    'clipPath, color-profile, cursor, filter, foreignObject, hatchpath, meshpatch, meshrow, script, style, view';
    protected $defaultTags = self::INITIATIVE_DEFAULT_LIST_TAGS;


    /**
     * @var ValidateNotifyService
     */
    protected $validateNotifyService =  null;

    /**
     * RemoveService constructor.
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
     * unittest 20170624
     *
     * @return string
     */
    public function getDefaultAttributes()
    {
        return $this->defaultAttributes;
    }

    /**
     * unittest 20170624
     *
     * @param  string $attributeList
     */
    public function setDefaultAttributes(string $attributeList)
    {
        $this->defaultAttributes = $attributeList;
    }

    /**
     * unittest 20170624
     *
     * @return string
     */
    public function getDefaultTags()
    {
        return $this->defaultTags;
    }

    /**
     * unittest 20170624
     *
     * @param  string $tagList
     */
    public function setDefaultTags(string $tagList)
    {
        $this->defaultTags = $tagList;
    }



    /**
     * replace the default-definition of replaceble attributes
     *
     * unittest ?
     *
     * @param array $param
     * @return void
     */
    public function overrideDefaultParams($param = [])
    {
        if (
            isset($param[self::PARAM_DEFAULT_ATTRIBUTES]) &&
            is_string($param[self::PARAM_DEFAULT_ATTRIBUTES]) &&
            (!empty(trim($param[self::PARAM_DEFAULT_ATTRIBUTES])))
        ) {
            $this->setDefaultAttributes($param[self::PARAM_DEFAULT_ATTRIBUTES]);
        }
        if (
            isset($param[self::PARAM_DEFAULT_TAGS]) &&
            is_string($param[self::PARAM_DEFAULT_TAGS]) &&
            (!empty(trim($param[self::PARAM_DEFAULT_TAGS])))
        ) {
            $this->setDefaultTags( $param[self::PARAM_DEFAULT_TAGS]);
        }
    }

    /**
     * Check, if the parameter in a single record  are corrct defined for the mainparameters 'prepare' oder 'overWork'
     *
     * unittest ?
     * old 20170618
     *
     * @param array $param
     * @return bool
     */
    public function paramValidate($param = [])
    {
        $count = 0;
        $result = is_array($param) && (!empty($param));
        $optional = false;
        if (
            isset($param[self::PARAM_ATTRIBUTES]) &&
            is_string($param[self::PARAM_ATTRIBUTES])
        ) {
            $optional = true;
            $count++;
        }
        if (
            isset($param[self::PARAM_TAGS]) &&
            is_string($param[self::PARAM_TAGS])
        ) {
            $optional = true;
            $count++;
        }
        if (
            isset($param[self::PARAM_DEFAULT_ATTRIBUTES]) &&
            is_string($param[self::PARAM_DEFAULT_ATTRIBUTES])
        ) {
            $optional = true;
            $count++;
        }
        if (
            isset($param[self::PARAM_DEFAULT_TAGS]) &&
            is_string($param[self::PARAM_DEFAULT_TAGS])
        ) {
            $optional = true;
            $count++;
        }
        $helpWat = (($result) &&
            ($optional) &&
            (count($param) === $count));

        $this->validateNotifyService->registerValidationMistakes(
            $helpWat,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            [
                'param' => $param,
                'estimatedCountOfitems' => $count,
            ]
        );
        return $helpWat;
    }

    /**
     * remove black-listed attributes
     *
     * unitest 20170617
     *
     * @param string $atrrinbuteList
     * @param \DOMDocument $xmlDom
     * @return \DOMDocument
     */
    public function removeAllAttributes($attributeList = '', $xmlDom = null)
    {
        if (!empty($attributeList) || !empty(self::getDefaultAttributes())) {
            $attributeArray = array_map('trim',
                explode(
                    self::REPLACE_PARAM_SEPARATOR,
                    $attributeList . self::REPLACE_PARAM_SEPARATOR . self::getDefaultAttributes()
                )
            );
            $xpath = new \DOMXPath($xmlDom);
            foreach ($attributeArray as $attribute) {
                if (!empty($attribute)) {
                    /** @var \DOMNodeList $elements */
                    if (false !== ($elements = $xpath->query("//*[@" . $attribute .']'))) {
                        /** @var \DOMElement $element */
                        foreach ($elements as $element) {
                            if ($element->hasAttribute($attribute)) {
                                $element->removeAttribute($attribute);
                            }
                        }
                    }
                }
            }
        }
        return $xmlDom;
    }

    /**
     * remove black-listed tags
     * unittest 170617
     *
     * @param string $atrrinbuteList
     * @param \DOMDocument $xmlDom
     * @return \DOMDocument
     */
    public function removeAllTags($tagsList = '', $xmlDom = null)
    {
        if (!empty($tagsList)) {
            $tagsArray = array_map('trim',
                explode(self::REPLACE_PARAM_SEPARATOR,
                    $tagsList . self::REPLACE_PARAM_SEPARATOR . $this->defaultTags
                )
            );
            $xpath = new \DOMXPath($xmlDom);
            foreach ($tagsArray as $tag) {
                if (!empty($tag)) {
                    /** @var \DOMNodeList $elements */
                    if (false !== ($elements = $xpath->query("//" . trim($tag)))) {
                        /** @var \DOMElement $element */
                        foreach ($elements as $element) {
                            if (!is_null($element->parentNode)) {
                                $element->parentNode->removeChild($element);
                            } else {
                                $xmlDom = null;
                                break 2;
                            }
                        }
                    }
                }
            }
        }
        return $xmlDom;
    }
}


?>

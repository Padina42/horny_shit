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
 * class NamespaceService
 *
 * remove all namespaces, which are not defined
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
class NamespaceService
{
    const INITIATIVE_DEFAULT_LINKS_VALUE = [
        'http://www.w3.org/2000/svg',
        'http://www.w3.org/1999/xlink',
        'http://www.w3.org/1999/xhtml',
        'http://creativecommons.org/ns#',
        'http://purl.org/dc/elements/1.1/',
        'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
    ];

    const INITIATIVE_LIMITER_COMMA = ',';

    const PARAM_LIST_URL = 'listUrl';
    const PARAM_TYPE = 'type';
    const VALUE_ADD = 'add';
    const VALUE_DEFINE = 'define';


    /**
     * @var array
     */
    protected $defaultLinks = self::INITIATIVE_DEFAULT_LINKS_VALUE;

    /**
     * @var ValidateNotifyService
     */
    protected $validateNotifyService = null;

    /**
     * NamespaceService constructor.
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
     * unittest 20170623
     *
     * @return array
     */
    public function getDefaultLinks()
    {
        return $this->defaultLinks;
    }

    /**
     * unittest 20170623
     *
     * @param  array $linkArray
     */
    public function setDefaultLinks(array $linkArray)
    {
        $this->defaultLinks = $linkArray;
    }


    /**
     * Check, if the parameter Array is empty  or if it contains an list-item
     * Check, if the optional type-item contzains the key 'add' or 'define'.
     *
     * unittest 20170623
     *
     * @param array $param
     * @return bool
     */
    public function paramValidate($param = [])
    {
        $count = 0;
        $result = is_array($param);
        $optional = true;
        $paramNeeded = empty($param);
        if (
            isset($param[self::PARAM_LIST_URL]) &&
            is_string($param[self::PARAM_LIST_URL])
        ) {
            $count++;
            $paramNeeded = true;
        }
        if (
            isset($param[self::PARAM_TYPE]) &&
            is_string($param[self::PARAM_TYPE])
        ) {
            $optional = (trim($param[self::PARAM_TYPE]) === self::VALUE_ADD) ||
                (trim($param[self::PARAM_TYPE]) === self::VALUE_DEFINE);
            $count++;
        }
        $valid = (
            ($result) &&
            ($optional) &&
            ($paramNeeded) &&
            (count($param) === $count)
        );

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param
        );
        return $valid;

    }

    /**
     * add additional default-definition of replaceble attributes
     *
     * unittest 20170624
     *
     * @param array $param
     * @return void
     */
    public function rebuildParamArray($param = [])
    {
        $valueArray = [];
        if (!empty($param[self::PARAM_LIST_URL])) {
            // missing callback removed all empty items
            $valueArray = array_filter(
                array_map(
                    'trim',
                    explode(self::INITIATIVE_LIMITER_COMMA, $param[self::PARAM_LIST_URL])
                )
            );
        }
        if (
            !isset($param[self::PARAM_TYPE])
            || (trim($param[self::PARAM_TYPE]) === self::VALUE_ADD)
        ) {
            self::setDefaultLinks(array_merge($this->defaultLinks, $valueArray));
        } else if (
            trim($param[self::PARAM_TYPE]) === self::VALUE_DEFINE
        ) {
            self::setDefaultLinks($valueArray);
        }
    }


    /**
     * some remarks
     *
     * Links
     * - 20170626 https://stackoverflow.com/questions/1307275/simplexml-error-handling-php
     * - 20170626 https://stackoverflow.com/questions/4137645/php-catching-a-simplexmlelement-parse-error
     * - 20170626 https://stackoverflow.com/questions/27345711/catch-simplexml-exception-only
     *
     */
    /**
     * Parse all namesspaces in an valid SVG/XML-Stream, which are not part of the allowed namespaces
     *
     * unittest 20170624
     * unittest 20170626 (extended with simplexml-generation and exception-handling)
     *
     *
     * @param string $xmlStream
     * @throws \UnexpectedValueException
     * @return array
     *
     */
    public function detectRemovableNamesspaces($xmlStream)
    {
        /** @var \SimpleXMLElement $xmlSimple */
        if (!is_string($xmlStream) || !strlen($xmlStream)) {
            throw new \UnexpectedValueException("String with length is required For the remove of namespaces in the xml or svg", 1498378240);
        }
        $currentErrorSetting = libxml_use_internal_errors(true);
//      not opp-varioation because of php-error in php 7.0
//      20170626 https://stackoverflow.com/questions/27345711/catch-simplexml-exception-only
//        $xmlSimple = new \SimpleXMLElement($xmlStream);
        $xmlSimple = simplexml_load_string($xmlStream);
        if ($xmlSimple === false) {
            $errorMessage = 'String could not be parsed as XML';
            foreach (libxml_get_errors() as $error) {
                $errorMessage .= "\t" . $error->message;
            }
            throw new \UnexpectedValueException($errorMessage, 1498378340);
        }
        libxml_clear_errors();
        libxml_use_internal_errors($currentErrorSetting);
        $currentNamespaces = $xmlSimple->getNamespaces(true);

        unset($xmlSimple);
        return array_diff($currentNamespaces, $this->getDefaultLinks());
    }

    /**
     * remove the namespaces in xpath nodes of a namespace from the Dom
     *
     * unittest ?
     *
     * @param array $namespace
     * @param \DOMDocument $svgDom
     */
    public function removeAttributesWithNamespace($namespace = [], \DOMDocument $svgDom)
    {
        if (is_array($namespace) &&
            !empty($namespace)
        ) {
            $xpath = new \DOMXPath($svgDom);
            foreach ($namespace as $prefix => $uri) {
                if ($prefix !== '') {
                    $xpath->registerNamespace($prefix, $uri);
                    /** @var \DOMNodeList $elements */
                    if (false !== ($elements = $xpath->query(".//*[@" . trim($prefix) . ':*]'))) {
                        /** @var \DOMElement $element */
                        foreach ($elements as $element) {
                            /** @var  \DOMNode  $attributeNode*/
                            foreach($element->attributes as $attributeNodeName => $attributeNode) {
                                if (strpos($attributeNode->nodeName,$prefix.':')!==false) {
                                    $element->removeAttributeNode($attributeNode);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $svgDom;
    }

    /**
     * remove the namespaces in xpath nodes of a namespace from the Dom
     *
     * unittest 20170624
     *
     * @param array $namespace
     * @param \DOMDocument $svgDom
     */
    public function removeTagsWithNamespace($namespace = [], \DOMDocument $svgDom)
    {
        if (is_array($namespace) &&
            !empty($namespace)
        ) {
            $xpath = new \DOMXPath($svgDom);
            foreach ($namespace as $prefix => $uri) {
                $xpath->registerNamespace($prefix, $uri);
                if (false !== ($elements = $xpath->query("//" . trim($prefix) . ':*'))) {
                    /** @var \DOMElement $element */
                    foreach ($elements as $element) {
                        if (!is_null($element->parentNode)) {
                            $element->parentNode->removeChild($element);
                        } else {
                            $this->validateNotifyService->registerValidationMistakes(
                                false,
                                ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                                [
                                    'key' => $prefix,
                                    'xPathValue' => $uri,
                                    'elementFail' => $element
                                ]
                            );

                            $xmlDom = null;
                            break 2;
                        }
                    }
                }
            }
        }
        return $svgDom;
    }


    /**
     * remove obsolte namespace-declarations
     *
     * unittest 20170624
     *
     * remove the namespaces in xpath nodes of a namespace from the Dom
     * @param array $namespaces
     * @param \DOMDocument $svgDom
     */
    public function removeSingleNamespaceDeclaration($namespaces, \DOMDocument $svgDom)
    {
        $finder = new \DOMXPath($svgDom);
        foreach ($namespaces as $prefix => $uri) {
            /** @var \DOMNodeList $nodes */
            $nodes = $finder->query("//*[namespace::{$prefix} and not(../namespace::{$prefix})]");
            /** @var \DOMElement $node */
            foreach ($nodes as $node) {
                $node->removeAttributeNS($uri, $prefix);
            }
        }
        return $svgDom;
    }


    /**
     * Remove all namespaces from the SVG
     *
     * unittest 20170625
     *
     * @param array $removeNamespaces
     * @param \\DOMDocument $svgDom
     * @return \DOMDocument
     */
    public
    function removeNodesAndAttributesWithNamespaces($removeNamespaces = [], \DOMDocument $svgDom = null)
    {
        if (!is_null($svgDom) &&
            is_array($removeNamespaces) &&
            (!empty($removeNamespaces))
        ) {
            $svgDom = self::removeTagsWithNamespace($removeNamespaces, $svgDom);
            $svgDom = self::removeAttributesWithNamespace($removeNamespaces, $svgDom);
            $svgDom = self::removeSingleNamespaceDeclaration($removeNamespaces, $svgDom);
        }
        return $svgDom;
    }


    /**
     * remove black-listed attributes
     *
     *
     * unittest ?
     *
     * old 2017026 check with valid svgs and param-variation
     * old 2017026 check exception
     *
     * @param array $params
     * @param string $xmlStream
     * @return \DOMDocument
     */
    public function allowedNamespacesInGeneratedSvgDom($params = [], $xmlStream = '', $flagRemove = true)
    {
        if (!empty($params)) {
            self::rebuildParamArray($params);
        }
        $removableNamespaces = self::detectRemovableNamesspaces($xmlStream);
        /** @var \DOMDocument $svgDom */
        $svgDom = new \DOMDocument();
        if ($svgDom->loadXML($xmlStream) === false) {
            throw new \UnexpectedValueException('The parsing of the SVG fails. Perhaps the SVG-file doesn´t exist, is empty or contains an error. This exception should not be thrown, because will parse the xml.',
                1492412535);
        }

        if ($flagRemove) {
            $svgDom = self::removeNodesAndAttributesWithNamespaces($removableNamespaces, $svgDom);
        }

        return $svgDom;
    }
}


?>

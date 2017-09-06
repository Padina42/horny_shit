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


use function GuzzleHttp\Psr7\try_fopen;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * class ErrorLocalizationUtility
 *
 * @author Dr. Dieter Porth <info@mobger.de>
 *
 */
class ErrorLocalizationUtility
{

    const LOCALISATION_EXTENSION_NAME = 'horny_shit';
    const ERROR_LOCALISATION_DEFAULT_ERROR = 'Unexpected error: there is not defined a key for the error-message.';
    const TRANSLATOR_OBJECT_TRANS_UNIT = 'trans-unit';
    const TRANSLATOR_CHILD_SOURCE = 'source';
    const TRANSLATOR_CHILD_TARGET = 'target';

    /**
     * @var string
     */
    protected $extensionPath = 'typo3conf/ext/';

    /**
     * @var string
     */
    protected $extensionName = self::LOCALISATION_EXTENSION_NAME . '/';

    /**
     * @var string
     */
    protected $languagePath = 'Resources/Private/Language/';

    /**
     * @var string
     */
    protected $languageKey = '';

    /**
     * @var string
     */
    protected $languageBaseName = 'locallang.xlf';

    /**
     * @var array
     */
    protected $localLanguageErrorMessages = [];


    /**
     * load the default arror-message or throw an exception
     *
     * unittest ?
     *
     * @param string $languageKey
     * @param string $extensionPath
     * @param string $languagePath
     * @param string $extensionName
     * @param string $languageBaseName
     *
     */
    public function __construct($languageKey = '')
    {
        $xmlFiles = self::detectTranlatedAndFallbackPath($languageKey);
        try {
            $xml = self::getTranlatorFileOrFallback($xmlFiles);
            self::buildTranslatorArray($xml);
        } catch (\InvalidArgumentException $e) {
            // thrown if a file has ibvalid XML
        }
    }

    /**
     * cunstructor of passible filenames for the translationfile of errors
     *
     * unittest ?
     *
     * @param string $languageKey
     * @return array
     */
    public function detectTranlatedAndFallbackPath($languageKey = '')
    {
        $result = [];
        $path = [];
        $path[] = $_SERVER["DOCUMENT_ROOT"];
        $path[] = $this->extensionPath;
        $path[] = $this->extensionName;
        $path[] = $this->languagePath;
        $preFix = (($languageKey !== null) ? $this->languageKey : $languageKey);
        $path[] = $preFix . (((empty($this->languageKey)) || (substr($this->languageKey, -1, 1) === '.')) ? '' : '.');
        $path[] = $this->languageBaseName;
        $result[] = implode('', array_filter($path));
        unset($path[3]);
        $result[]= implode('', array_filter($path));
        return $result;

    }
    /**
     * override the conventional name-definitions
     *
     * unittest ?
     *
     * @param string|null $languageKey
     * @param string|null $extensionPath
     * @param string|null $languagePath
     * @param string|null $extensionName
     * @param string|null $languageBaseName
     */
    public function changePathPartsForTranalatorFile($languageKey = null, $extensionPath = null, $languagePath = null, $extensionName = null, $languageBaseName = null)
    {
        $this->extensionPath  = (($extensionPath !== null) ? $this->extensionPath : $extensionPath);
        $this->extensionName  = (($extensionName !== null) ? $this->extensionName : $extensionName);
        $this->languagePath  = (($languagePath !== null) ? $this->languagePath : $languagePath);
        $preFix = (($languageKey !== null) ? $this->languageKey : $languageKey);
        $this->languageKey  = $preFix . (((empty($prefix)) || (substr($prefix, -1, 1) === '.')) ? '' : '.');
        $this->languageBaseName  = (($languageBaseName !== null) ? $this->languageBaseName : $languageBaseName);

    }

    /**
     * detect the existing files
     *
     * unittest ?
     *
     * @param string $xmlFile
     * @param string $xmlFileDefault
     * @return SimpleXMLElement
     *
     * @throws \InvalidArgumentException
     */
    public function getTranlatorFileOrFallback($xmlFiles = [])
    {
        if (empty($xmlFiles)) {
            throw new \InvalidArgumentException('There is no filepath defined.',1502606806);
        }
        $checked = false;
        foreach($xmlFiles as $xmlFile){
            if (!file_exists($xmlFile)) {
                throw new \InvalidArgumentException('The file "' . $xmlFile . '" does not exist.',1502604756);
            } else {
                $checked = true;
                break;
            }
        }
        if (!$checked) {
            throw new \InvalidArgumentException('No file of the list "' . implode(',',$xmlFiles) . '" exist.',1502607207);
        }
        $myXml = simplexml_load_file($xmlFile);
        if (($myXml === false) ||
            (!isset($myXml->file, $myXml->file->body))
        ) {
            throw new InvalidArgumentException('The file "' . $xmlFile . '" contains an invalid xml-definition.',1502602603);
        }
        return $myXml;
    }

    /**
     * reformate an simpleXML-Object  for translations into an array
     *
     * unittest ?
     *
     * @param SimpleXMLElement $xml
     */
    public function buildTranslatorArray($xml)
    {
        $transUnits = $xml->file->body->xPath('trans-unit');
        /** @var \SimpleXMLElement $translator */
        foreach ($transUnits as $translator) {
                if (isset($translator->source, $translator->attributes()->id)) {
                    $id = trim((string)$translator->attributes()->id);
                    $this->localLanguageErrorMessages[$id][self::TRANSLATOR_CHILD_SOURCE] = $translator->source;
                    $this->localLanguageErrorMessages[$id][self::TRANSLATOR_CHILD_TARGET] = (isset($translator->target) ?
                        $translator->target :
                        $translator->source
                    );
                }
        }
    }

    /**
     * Returns the localized label of the LOCAL_LANG key, $key.
     *
     * unittest ?
     *
     * @param string $key The key from the LOCAL_LANG array for which to return the value.
     */
    public function translateSimple($key)
    {
        return ((isset($this->localLanguageErrorMessages[$key])) ?
            $this->localLanguageErrorMessages[$key][self::TRANSLATOR_CHILD_TARGET]:
            self::ERROR_LOCALISATION_DEFAULT_ERROR
        );

    }

}

?>
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

use In2code\Powermail\Utility\LocalizationUtility;
use Porth\HornyShit\Service\Svt\Utility\ErrorLocalizationUtility;
use Porth\HornyShit\Service\Svt\Utility\SingletonFactory;

/**
 * class ValidateNotifyService
 *
 * @author Dr. Dieter Porth <info@mobger.de>
 *
 *
 */
class ValidateNotifyService extends SingletonFactory
{

    const LOCALISATION_PREFIX_FOR_VALIDATION = 'param.validate.';
    const PARAM_VALID = 'valid';
    const PARAM_ADD = 'add';
    const PARAM_MESSAGE = 'message';
    const PARAM_WRAP_DEFAULT = [
        'all' => 'div',
        'allParam' => 'style="background: gold none repeat scroll 0 0;padding: 3%;position: absolute;width: 90%;z-index: 1000;" draggable="true" onclick="this.style.display = \'none\';"',
        'title' => 'h2',
        'list' => 'ul',
        'item' => 'li',
        'infoValid' => 'em',
        'infoMessage' => 'strong',
        'infoAdd' => 'sup',
        'infoSeparation' => '<br />' . "\n",

    ];

    /**
     * @var ErrorLocalizationUtility|null
     */
    protected $errorLocalizationUtility = null;

    /**
     * akctivate the registration of errormessages
     *
     * @var bool
     */
    protected $activate = false;

    /**
     * @var array
     */
    protected $listOfErrorNotes = [];

    /**
     * @var array
     */
    protected $localization = null;

    /**
     * change the language of errormessage by using the conventional struture of pathes.
     *
     * @param string $languageKey
     */
    public function changeLanguage($languageKey = '')
    {
        if ($this->errorLocalizationUtility  === null) {
            /** @var ErrorLocalizationUtility $errorLocalizationUtility */
            $this->errorLocalizationUtility = new ErrorLocalizationUtility($languageKey);
        }
    }

    /**
     * getter activate
     *
     * unittest ?
     *
     * @return bool
     */
    public function getActivate()
    {
        return $this->activate;
    }

    /**
     * setter activate
     *
     * unittest ?
     *
     * @param bool $activate
     */
    public function setActivate($activate)
    {
        $this->activate = $activate;
    }

    /**
     * getter listOfErrorNotes
     *
     * unittest ?
     *
     * @return array
     */
    public function getListOfErrorNotes()
    {
        return $this->listOfErrorNotes;
    }

    /**
     * setter listOfErrorNotes
     *
     * unittest ?
     *
     * @param array $listOfErrorNotes
     */
    public function setListOfErrorNotes($listOfErrorNotes)
    {
        $this->listOfErrorNotes = $listOfErrorNotes;
    }


    public function translateMe($translationId)
    {
        return $this->errorLocalizationUtility->translateSimple($translationId);
    }

    /**
     *
     * unittest ?
     *
     * @param bool $valid
     * @param string $method
     * @param mixed $additional
     * @return bool
     */
    public function registerValidationMistakes(
        $valid = true,
        $location = ['method' => '', 'class' => '', 'line' => ''],
        $additional = null
    ) {
        if (($this->activate)) {
            $classParts = array_filter(explode('\\', $location['class']));
            $lastPartClass = array_pop($classParts);
            $translationId = self::LOCALISATION_PREFIX_FOR_VALIDATION . $lastPartClass . '.' . $location['method'];
            $item = [];
            $item[self::PARAM_MESSAGE] = self::translateMe($translationId) .
                "\n." . ' => ' . $location['class'] . ' - ' . $location['line'] . '.';
            $item[self::PARAM_VALID] = $valid;
            $item[self::PARAM_ADD] = print_r($additional, true);
            $this->listOfErrorNotes = $item;
            $valid = true;
        }
        return $valid;
    }

    /**
     * unittest ?
     *
     * @param $item
     * @param $tag
     * @return string
     */
    public static function wrap($item, $tag, $style = '')
    {
        return '<' . $tag . (!$style ? '' : ' ' . $style) . '>' . $item . '</' . $tag . '>';
    }

    /**
     * unittest ?
     *
     * @param $item
     * @param $tag
     * @return string
     */
    public static function wrapItem($item)
    {
        return '<pre>' . (!$item ? '[false / empty]' : $item) . '</pre>';
    }

    /**
     * unittest ?
     *
     * @param $item
     * @param $tag
     * @return string
     */
    public static function wrapList($list, $tag)
    {
        return '<' . $tag . '>' . implode('</' . $tag . ">\n<" . $tag . '>',
                $list) . '</' . $tag . '>';
    }

    /**
     * unittest ?
     *
     * @param $item
     * @param $tag
     * @return string
     */
    public static function wrapTagReplace($stream, $replaceFourItems)
    {
        $search = ['xxx', 'yyy', 'zzz', '<ZYX />'];
        return str_replace($search, $replaceFourItems, $stream);
    }

    /**
     * unittest ?
     *
     * @param $item
     * @param $tag
     * @return string
     */
    public static function wrapItemAll($item)
    {
        return '<xxx>' . $item[self::PARAM_VALID] . '</xxx><ZYX />' .
            '<yyy>' . $item[self::PARAM_MESSAGE] . '</yyy><ZYX />' .
            '<zzz>' . $item[self::PARAM_ADD] . '</zzz>';
    }

    /**
     * unittest ?
     *
     * @param $item
     * @param $tag
     * @return string
     */
    public static function wrapItemMistakes($item)
    {
        return '<xxx>' . $item[self::PARAM_VALID] . '</xxx><ZYX />' .
            '<yyy>' . $item[self::PARAM_MESSAGE] . '</yyy><ZYX />' .
            '<zzz>' . $item[self::PARAM_ADD] . '</zzz>';
    }

    /**
     * unittest
     *
     * @param bool $onlyMistakess
     * @param array $wrap
     * @return string
     */
    public function listValidationMistakes($onlyMistakess = true, $wrap = self::PARAM_WRAP_DEFAULT)
    {
        $addInfo = '';
        if ($this->getActivate()) {
            $list = $this->getListOfErrorNotes();
            if (!empty($list)) {
                $addTitle = self::wrap(
                    self::translateMe('general.title.' . ($onlyMistakess ? 'mistakes' : 'all')),
                    $wrap['title']
                );
                $newList = array_map(
                    ['Porth\\HornyShit\\Service\\Svt\\Utility\\ValidateNotifyService', 'wrapItem'],
                    $list
                );
                $addStream = self::wrapList($newList, $wrap['item']);
                $addStream = self::wrapTagReplace(
                    $addStream,
                    [
                        $wrap['infoValid'],
                        $wrap['infoMessage'],
                        $wrap['infoAdd'],
                        $wrap['infoSeparation']
                    ]
                );
                $addList = self::wrap($addStream, $wrap['list']);
                $addInfo = $addTitle . "\n" . $addList;
                $addInfo = self::wrap($addInfo, $wrap['all'], $wrap['allParam']);
            }
        }
        return $addInfo;
    }
}

?>
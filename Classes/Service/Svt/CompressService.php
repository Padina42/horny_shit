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
 * class CompressService
 *
 * get the configuration for the svt-viewhelpt from an JSON, a serialized php-array or XML-'array'
 *
 * param-validation:
 * - set:       20170802
 * - unitest:   ?
 * error-handling:
 * - set:       20170802
 * - unitest:   ?
 *
 * @author Dr. Dieter Porth <info@mobger.de>
 */

class CompressService
{

    const TYPUS_ALL = 'all';
    const TYPUS_SPACELESS = 'spaceless';
    const TYPUS_TRIM = 'trim';
    const TYPUS_SINGLE = 'single';
    const TYPUS_NONE = 'none';
    const TYPE_LIST = [
        self::TYPUS_ALL,
        self::TYPUS_NONE,
        self::TYPUS_SPACELESS,
        self::TYPUS_SINGLE,
        self::TYPUS_TRIM,
    ];
    // the compression must hold in the right order to get optimal results
    const TYPE_COMPRESS = [
        self::TYPUS_SPACELESS,
        self::TYPUS_SINGLE,
        self::TYPUS_TRIM,
    ];


    /**
     * @var ValidateNotifyService
     */
    protected $validateNotifyService = null;

    /**
     * CompressService constructor.
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
     * check, if the parameter ist valid
     *
     * unittest ?
     *
     * @param $param
     * @return bool
     */
    public function paramValidate($param)
    {
        $valid = is_string($param) &&
            (array_search($param, self::TYPE_LIST) !== false);
        if ((!$valid) &&
            (!empty($param))
        ) {
            $list = array_filter(
                array_map(
                    'trim',
                        explode(',', $param)
                )
            );
            $diff = array_diff($list, self::TYPE_LIST);
            $valid = $valid ||
                (count($diff) === 0);
        }
        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            [
                'search' => $param,
                'check'=> self::TYPE_LIST
            ]
        );
        return $valid;
    }


    /**
     *
     * unittest ?
     *
     * @param $stream
     * @return mixed
     */
    public function compressSpaceless($stream)
    {
        return preg_replace('/\\>\\s+\\</', '><', $stream);
    }

    /**
     *
     * unittest ?
     *
     * @param $stream
     * @return mixed
     */
    public function compressSingle($stream)
    {
        return preg_replace('/(\\s+)/', ' ', $stream);
    }

    /**
     *
     * unittest ?
     *
     * @param $stream
     * @return mixed
     */
    public function compressTrim($stream)
    {
        $dummy = $stream;
        $dummy = preg_replace('/\\>(\\s+)([^\\s])/', '>$2', $dummy);
        $dummy = preg_replace('/([^\\s])(\\s+)\\</', '$1<', $dummy);
        return $dummy;
    }

    /**
     * should not be used, defined because of generated function
     *
     * unittest ?
     *
     * @throws \RuntimeException
     */
    public function compressNone($stream)
    {
        throw new \RuntimeException('The generative method `compressNone` in class `compressservice` should not be called.', 1500140345);
    }

    /**
     * should not be used, defined because of generated function
     *
     * unittest ?
     *
     * @throws \RuntimeException
     *
     */
    public function compressAll($stream)
    {
        throw new \RuntimeException('The generative method `compressAll` in class `compressservice` should not be called.', 1500140335);
    }

    /**
     * extract from a configuration-array a new configuration-array
     *
     * unittest ?
     *
     * @param $param
     * @return mixed
     */
    public function compress($param, $svgStream)
    {
        $result = $svgStream;
        $list = array_filter(array_map('trim', explode(',', $param)));
        if (array_search(self::TYPUS_ALL, $list) !== false) {
            $list = self::TYPE_COMPRESS;
        }
        foreach (self::TYPE_COMPRESS as $item) {
            if (array_search($item, $list) !== false) {
                $extractMethod = 'compress' . ucfirst($item);
                if (is_callable([$this, $extractMethod])) {
                    $result = $this->$extractMethod($result);
                } else {
                    $this->validateNotifyService->registerValidationMistakes(
                        $valid,
                        ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                        $extractMethod
                    );
                    break;
                }
            }
        }
        return $result;
    }


}
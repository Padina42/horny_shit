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
use Porth\HornyShit\Service\SvtService;

/**
 * class ExtractService
 *
 * get the configuration for the svt-viewhelpt from an JSON or serialitzed php-array
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
class ExtractService
{

    const EXTRACT_ENTRY = 'entry';
    const EXTRACT_TYPE = 'type';

    const EXTRACT_TYPE_LIST = [
        self::EXTRACT_TYPUS_JSON,
        self::EXTRACT_TYPUS_SERIALIZED,
        self::EXTRACT_TYPUS_YAML,
    ];
    const EXTRACT_TYPUS_JSON = 'json';
    const EXTRACT_TYPUS_SERIALIZED = 'serialized';
    const EXTRACT_TYPUS_YAML = 'yaml';

    const EXTRACT_REBUILD_FLAG_BEFORE = true;
    const EXTRACT_REBUILD_FLAG_NO_BEFORE = false;

    /**
     * @var ValidateNotifyService
     */
    protected $validateNotifyService = null;

    /**
     * ExtractService constructor.
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
     *
     * unittest ?
     *
     * @param $stream
     * @return mixed
     * @throws \UnexpectedValueException
     */
    public function extractJson($stream)
    {
        $result = json_decode($stream, true);
        if (($result === false) ||
            (!is_array($result))
        ) {
            throw new \UnexpectedValueException('The JSON-stream for the configuration failt to be converted. Check, if the JSON of the configuration-stream is valid for the conversion of the svg.', 1500187119);
            // throw
        }
        return $result;
    }

    /**
     *
     * unittest ?
     *
     * @param $stream
     * @return mixed
     * @throws \RuntimeException
     */
    public function extractYaml($stream)
    {
        $result = [];
        if (is_callable(yaml_parse)) {
            $result = yaml_parse($yaml);
        } else {
            throw new \RuntimeException('The YAML-stream for the configuration failt to be converted. Check, if the YAML-extension is part of your php-system.', 1504551788);
        }
        return $result;
    }

    /**
     * @param array $param
     * @return string
     */
    public function buildYaml($param = [])
    {
        if (is_callable(yaml_emit())) {
            $result = yaml_emit($param);
        } else {
            throw new \RuntimeException('The YAML-stream for the configuration failt to be converted. Check, if the YAML-extension is part of your php-system.', 1504551788);
        }
        return $result;
    }

    /**
     *
     * unittest ?
     *
     * @param $stream
     * @return mixed
     * @throws \UnexpectedValueException
     */
    public function extractSerialized($stream)
    {

        $dummyIni = ini_get('unserialize_callback_func');
        ini_set('unserialize_callback_func', '');
        $result = unserialize($stream, [true]);
        ini_set('unserialize_callback_func', $dummyIni);
        if ($result === false) {
            throw new \UnexpectedValueException('The serialized-php-stream for the configuration failt to be converted. Check, if the serialized php of the configuration-stream is valid for the conversion of the svg.', 1500187089);
        }
        return $result;
    }

    /**
     * @param array $param
     * @return string
     */
    public function buildSerialized($param = [])
    {
        return serialize($param);
    }

    /**
     *
     * unittest ?
     *
     * extract from a configuration-array a new configuration-array
     * @param $configArray
     * @return mixed
     */
    public function getNewConfigArray($configArray)
    {
        $extractMethod = 'extract' . ucfirst(
                ((isset($configArray[self::EXTRACT_TYPE])) ?
                    $configArray[self::EXTRACT_TYPE] :
                    self::EXTRACT_TYPUS_JSON
                )
            );
        if (is_callable([$this, $extractMethod])) {
            $result = $this->$extractMethod($stream);
        }
        return $result;
    }

    /**
     * Check, if the parameter Array is empty  or if it contains an list-item
     * Check, if the optional type-item contzains the key 'add' or 'define'.
     *
     * unittest
     *
     * @param array $param
     * @return bool
     */
    public function paramValidate($param = [])
    {
        $result = is_array($param);
        $setType = false;
        $setStream = false;
        if ($result) {
            $count = 0;
            if (
                (isset($param[self::EXTRACT_ENTRY])) &&
                (is_string($param[self::EXTRACT_ENTRY])) &&
                (!empty($param[self::EXTRACT_ENTRY]))
            ) {
                $setStream = true;
                $count++;
            };
            if (
                (isset($param[self::EXTRACT_TYPE])) &&
                (is_string($param[self::EXTRACT_TYPE])) &&
                (array_search($param[self::EXTRACT_TYPE], self::EXTRACT_TYPE_LIST) !== false)
            ) {
                $setType = true;
                $count++;
            }
            $result = $result &&
                (count($param) === $count) &&
                $setStream &&
                $setType;
        }
        return $result;
    }

    /**
     * remove the parameters for the last two steps
     *
     * @param array $param
     * @return array
     */
    public function rebuildArgumentsBefore($param = []) {
        if (isset($param[SvtService::ATTRIBUTE_VARIABLE] )){
            unset($param[SvtService::ATTRIBUTE_VARIABLE]);
        }
        if (isset($param[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE_LAST] )){
            unset($param[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE_LAST] );
        }
        return $param;
    }

    /**
     * extract only the Parameters for the last two steps
     *
     * @param array $param
     */
    public function rebuildArgumentsNoBefore($param = []) {
        $result = [];
        if (isset($param[SvtService::ATTRIBUTE_VARIABLE] )){
            $result = $param[SvtService::ATTRIBUTE_VARIABLE];
        }
        if (isset($param[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE_LAST] )){
            if (!isset($result[SvtService::ATTRIBUTE_PARAMETER])) {
                $result[SvtService::ATTRIBUTE_PARAMETER] = [];
            }
            $result[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE_LAST] =
                $param[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE_LAST];
        }
        return $result;

    }

    /**
     * rebuild the parameter-array to special restrictions
     * @param array $param
     * @param bool $flag
     */
    public function rebuildArguments($param = [], $flag = ExtractService::EXTRACT_REBUILD_FLAG_BEFORE)
    {
        $param = [];
        if ($flag === ExtractService::EXTRACT_REBUILD_FLAG_BEFORE) {
            $result = $this->rebuildArgumentsBefore($param);
        } elseif ($flag === ExtractService::EXTRACT_REBUILD_FLAG_NO_BEFORE) {
            $result = $this->rebuildArgumentsNoBefore($param);
        }
        return $result;
    }
}
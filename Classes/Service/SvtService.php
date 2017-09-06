<?php
namespace Porth\HornyShit\Service;

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

use Porth\HornyShit\Service\Svt\CheckService;
use Porth\HornyShit\Service\Svt\ConfigService;
use Porth\HornyShit\Service\Svt\CompressService;
use Porth\HornyShit\Service\Svt\ExtractService;
use Porth\HornyShit\Service\Svt\NamespaceService;
use Porth\HornyShit\Service\Svt\RemoveService;
use Porth\HornyShit\Service\Svt\RebuildService;
use Porth\HornyShit\Service\Svt\RebuildParamValidateService;
use Porth\HornyShit\Service\Svt\StringReplaceService;

use Porth\HornyShit\Service\Svt\ParameterService;
use Porth\HornyShit\Service\Svt\VariableService;

use Porth\HornyShit\Service\Svt\Utility\SvtServiceSingleton;
use Porth\HornyShit\Service\Svt\Utility\ValidateNotifyService;
use Porth\HornyShit\Service\Svt\Utility\CalculateUtility;


/**
 * package  SvtService
 * class    SvtService
 *
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

/**
 * class ValidateNotifyService
 *
 * @author Dr. Dieter Porth <info@mobger.de>
 *
 *
 */
class SvtService extends SvtServiceSingleton
{

    const ATTRIBUTE_IGNORE = 'ignore';
    const ATTRIBUTE_IGNORE_lIST = [
        ParameterService::PARAM_PLACEHOLDER,
        ParameterService::PARAM_REGISTER,
        self::ATTRIBUTE_CHECK_SVG,
        self::ATTRIBUTE_PREPARE,
        self::ATTRIBUTE_NAMESPACE,
        self::ATTRIBUTE_REMOVE,
        self::ATTRIBUTE_REBUILD,
        self::ATTRIBUTE_WORK_OVER,
        self::ATTRIBUTE_COMPRESS,
        ParameterService::PARAM_CALCULATE,
//        self::ATTRIBUTE_VARIABLE,             // the part variable could never be ignored
//        ParameterService::PARAM_CALCULATE_LAST, // the calculate after the settings of values could never be ignored
    ];
    const ATTRIBUTE_ADDITIONAL_lIST = [
        self::ATTRIBUTE_PARAMETER,
        self::ATTRIBUTE_INFO_CONFIG_ERROR,
    ];
    const ATTRIBUTE_NOT_EXTRACT_lIST = [
        self::ATTRIBUTE_EXTRACT, // no recursion wished
        self::ATTRIBUTE_VARIABLE, // dynamic- variable, which can come from outside
    ];

    const ATTRIBUTE_INFO_CONFIG_ERROR = 'infoConfigError';
    const ATTRIBUTE_INFO_SPECIAL = 'infoSpecial';
    const ATTRIBUTE_INFO_CALCULATE_ERROR = 'infoCalculateError';
    const ATTRIBUTE_CHECK_SVG = 'checkSvg';
    const ATTRIBUTE_PREPARE = 'prepare';
    const ATTRIBUTE_NAMESPACE = 'namespace';
    const ATTRIBUTE_REMOVE = 'remove';
    const ATTRIBUTE_REBUILD = 'rebuild';
    const ATTRIBUTE_WORK_OVER = 'workOver';
    const ATTRIBUTE_COMPRESS = 'compress';
    const ATTRIBUTE_EXTRACT = 'extract';

    const ATTRIBUTE_PARAMETER = 'parameter';
    const ATTRIBUTE_VARIABLE = 'variable';

    const ATTRIBUTE_INFO_SPECIAL_LIST = [
        self::PARAM_INFO_SPECIAL_SVG_BEFORE_LAST,
        self::PARAM_INFO_SPECIAL_SVG_ONLY_LAST,
        self::PARAM_INFO_SPECIAL_SER_BEFORE,
        self::PARAM_INFO_SPECIAL_SER_NO_BEFORE,
        self::PARAM_INFO_SPECIAL_JSON_BEFORE,
        self::PARAM_INFO_SPECIAL_JSON_NO_BEFORE,
        self::PARAM_INFO_SPECIAL_YAML_BEFORE,
        self::PARAM_INFO_SPECIAL_YAML_NO_BEFORE,
    ];
    const PARAM_INFO_SPECIAL_SVG_BEFORE_LAST = 'svgBeforeLastSteps';
    const PARAM_INFO_SPECIAL_SVG_ONLY_LAST = 'svgOnlyLastSteps';
    const PARAM_INFO_SPECIAL_SER_BEFORE = 'paramIgnoredSerialized';
    const PARAM_INFO_SPECIAL_SER_NO_BEFORE = 'paramNotIgnoredSerialized';
    const PARAM_INFO_SPECIAL_JSON_BEFORE = 'paramIgnoredJson';
    const PARAM_INFO_SPECIAL_JSON_NO_BEFORE = 'paramNotIgnoredJson';
    const PARAM_INFO_SPECIAL_YAML_BEFORE = 'paramIgnoredYaml';
    const PARAM_INFO_SPECIAL_YAML_NO_BEFORE = 'paramNotIgnoredYaml';


    /**
     * CalculateUtility
     *
     * @var CalculateUtility
     */
    protected $calculate = NULL;

    /**
     * CheckService
     *
     * @var CheckService
     */
    protected $check = NULL;

    /**
     * ParameterService
     *
     * @var ParameterService
     */
    protected $parameter = NULL;

    /**
     * VariableService
     *
     * @var VariableService
     */
    protected $variable = NULL;

    /**
     * CompressService
     *
     * @var CompressService
     */
    protected $compress = NULL;

    /**
     * ExtractService
     *
     * @var ExtractService
     */
    protected $extract = NULL;

    /**
     * StringReplaceService
     *
     * @var StringReplaceService
     */
    protected $stringReplace = NULL;

    /**
     * NamespaceService
     *
     * @var NamespaceService
     */
    protected $namespace = NULL;

    /**
     * RemoveService
     *
     * @var RemoveService
     */
    protected $remove = NULL;

    /**
     * RebuildService
     *
     * @var RebuildParamValidateService
     */
    protected $rebuildParamValidate = NULL;

    /**
     * RebuildService
     *
     * @var RebuildService
     */
    protected $rebuild = NULL;

    /**
     * languageKey
     *
     * @var string
     */
    protected $languageKey = '';


    /**
     * @var ValidateNotifyService
     */
    protected $validateNotifyService = null;

    /**
     * SvtService constructor.
     *
     * use a space-character to switch with $SvtService->buildService(' ') to the fallback-language;
     *
     * unittest ?
     *
     * @param string $languageKey
     */
    public function buildService($languageKey = '')
    {
        $trimedLangugaeKey = trim($languageKey);
        if ((!$this->check !== null) &&
            ($languageKey !== $trimedLangugaeKey) &&
            ($this->languageKey !== $trimedLangugaeKey)
        ) {
            $this->validateNotifyService = ValidateNotifyService::getInstance();
            $this->validateNotifyService->changeLanguage($languageKey);
            $this->languageKey = trim($languageKey);

        } elseif ($this->check === null) {
            $this->calculate = new CalculateUtility();
            $this->check = new CheckService();
            $this->compress = new CompressService();
            $this->extract = new ExtractService();
            $this->namespace = new NamespaceService();
            $this->remove = new RemoveService();
            $this->rebuild = new RebuildService();
            $this->rebuildParamValidate = new RebuildParamValidateService();
            $this->stringReplace = new StringReplaceService();

            $this->variable = new VariableService();
            $this->parameter = new ParameterService();

            $this->validateNotifyService = ValidateNotifyService::getInstance();
            $this->validateNotifyService->changeLanguage($trimedLangugaeKey);
            $this->languageKey = $trimedLangugaeKey;
        }
    }

    /**
     * ===========================================================
     *    handle the valitationinformations of the param-checks
     * ===========================================================
     */

    /**
     * init the singleton ValidateNotifyService and set the activation or unset it
     *
     * unittest
     *
     * @param $activateValidation
     */
    public function activateValidationNotifyService($activateValidation)
    {
        $this->validateNotifyService->setActivate($activateValidation);
    }

    /**
     * unittest ?
     *
     * @return string
     */
    public function resultValidationNotifyService()
    {
        $addInfo = '';
        if ($this->validateNotifyService->getActivate()) {
            $addInfo = $this->validateNotifyService->listValidationMistakes();
        }
        return $addInfo;

    }

    /**
     * @param mixed $arguments
     * @param string $name
     * @return bool
     */
    public function paramValidateOptionalFlagValue($arguments, $name)
    {
        $valid = true;
        $param = null;
        if (!isset($arguments, $arguments[$name])) {
            $param = $arguments[$name];
            $valid = false;
        };

        if (
            ($param !== null)
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
        };
        return $valid;
    }

    /**
     * validation the flag for the information about configuration-errors
     *
     * unittest ?
     *
     * @return string
     */
    public function paramValidateValidationNotifyService($arguments)
    {
        $valid = true;
        $valid = $valid && self::paramValidateOptionalFlagValue($arguments, self::ATTRIBUTE_INFO_CONFIG_ERROR);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;

    }

    /**
     * validation the key for the type of output, which is controlled by the viewhelper
     *
     * unittest ?
     *
     * @return string
     */
    public function paramValidateValidationOutputService($arguments)
    {
        $valid = true;
        $param = null;
        if (!isset($arguments, $arguments[self::ATTRIBUTE_INFO_SPECIAL])) {
            $param = $arguments[self::ATTRIBUTE_INFO_SPECIAL];
            $valid = $valid &&
                is_string($param) &&
                (array_search($param, self::ATTRIBUTE_INFO_SPECIAL_LIST) !== 0);
        };

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;

    }

    /**
     * validation the key for the type of error-handling for the calculate process
     *
     * unittest ?
     *
     * @return string
     */
    public function paramValidateValidationInfoCalculateError($arguments)
    {
        $valid = true;
        $valid = $valid && self::paramValidateOptionalFlagValue($arguments, self::ATTRIBUTE_INFO_CALCULATE_ERROR);

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);
        return $valid;

    }

    /**
     * ===========================================================
     *            handle the valitation of arguments
     * ===========================================================
     */

    /**
     * validate the ignore_list
     *
     * unittest ?
     *
     * @param mixed $param
     * @return bool
     */
    public function paramValidateIgnore($param)
    {
        $valid = is_string($param);
        if ($valid) {
            $check = array_filter(array_map('trim', explode(',', $param)));
            $arrayDiff = array_diff($check, self::ATTRIBUTE_IGNORE_lIST);
            $valid = $valid && (count($arrayDiff) === 0);
        }
        return $valid;
    }

    /**
     *
     * unittest ?
     *
     * @param array $param
     * @return array
     */
    public function paramRemoveIgnored($param = [])
    {
        $result = $param;
        if ((isset($param[self::ATTRIBUTE_IGNORE]))) {
            $list = array_filter(array_map('trim', explode(',', $param[self::ATTRIBUTE_IGNORE])));
            foreach ($list as $item) {
                if (isset($result[$item])) {
                    unset($result[$item]);
                } elseif (isset($result[self::ATTRIBUTE_PARAMETER][$item])) {
                    unset($result[self::ATTRIBUTE_PARAMETER][$item]);
                }
            }
        }
        return $result;
    }

    /**
     * Activate errorhandling for validation of parameters
     *
     * @param array $arguments - referenz
     */
    public function activateConfigValidation(array $arguments = [])
    {
        if (isset($arguments[SvtService::ATTRIBUTE_INFO_CONFIG_ERROR])) {
            $this->validateNotifyService->setActivate((!(!$arguments[SvtService::ATTRIBUTE_INFO_CONFIG_ERROR])));
        }
    }

    /**
     * call the chaecks for the different main-paramtyles
     *
     * unittest ?
     * old 20170603
     * extend unittest (ATTRIBUTE_REBUILD):
     *
     * @param mixed $param
     * @param string $paramtype
     * @return bool
     */
    public function paramValidate($param = null, string $paramtype = '')
    {
        $result = false;
        switch ($paramtype) {
            case self::ATTRIBUTE_IGNORE :
                // unittest
                $result = self::paramValidateIgnore($param);
                break;
            case self::ATTRIBUTE_PARAMETER :
                // unittest
                $result = $this->parameter->paramValidate($param);
                break;
            case self::ATTRIBUTE_VARIABLE :
                // unittest
                $result = $this->variable->paramValidate($param);
                break;
            case self::ATTRIBUTE_EXTRACT :
                // unittest
                $result = $this->extract->paramValidate($param);
                break;
            case self::ATTRIBUTE_CHECK_SVG :
                // unittest 20170603
                $result = $this->check->paramValidate($param);
                break;
            case self::ATTRIBUTE_INFO_CONFIG_ERROR :
                // unittest
                $result = self::paramValidateValidationNotifyService($param);
                break;
            case self::ATTRIBUTE_INFO_SPECIAL :
                // unittest
                $result = self::paramValidateValidationOutputService($param);
                break;
            case self::ATTRIBUTE_INFO_CALCULATE_ERROR :
                // unittest
                $result = self::paramValidateValidationInfoCalculateError($param);
                break;
            case self::ATTRIBUTE_PREPARE :
                // unittest 20170603
                $result = $this->stringReplace->paramValidateSwitch($param);
                break;
            case self::ATTRIBUTE_NAMESPACE :
                // unittest
                $result = $this->namespace->paramValidate($param);
                break;
            case self::ATTRIBUTE_REMOVE :
                // unittest
                $result = $this->remove->paramValidate($param);
                break;
            case self::ATTRIBUTE_REBUILD :
                // unittest
                $result = $this->rebuildParamValidate->paramValidate($param);
                break;
            case self::ATTRIBUTE_WORK_OVER :
                // unittest 20170603
                $result = $this->stringReplace->paramValidateSwitch($param);
                break;
            case self::ATTRIBUTE_COMPRESS :
                // unittest 20170603
                $result = $this->compress->paramValidate($param);
                break;
            default:
                break;
        }
        return $result;
    }

    /**
     * call of main function of first validation of xml-stream
     *
     * unittest ?
     *
     * @param mixed $checkSvg
     * @param mixed $xmlStream
     * @return bool     *
     */
    public function checkSvgStream($checkSvg = '', $xmlStream = '')
    {
        $valid = false;
        if (isset($xmlStream) &&
            is_string($xmlStream) &&
            !empty(trim($xmlStream))
        ) {
            $valid = $this->check->checkSpecification(
                (
                (empty($checkSvg)) ?
                    CheckService::KEYS_CHECK_SVG__XML_SVG :
                    $checkSvg
                ),
                $xmlStream
            );
        }
        return $valid;
    }

    /**
     *
     * unittest ?
     *
     * @param array $param
     * @param string $svg
     * @return \DOMDocument
     */
    public function allowedNamespacesInGeneratedSvgDom($param = [], $svg = '', $flagRemove = true)
    {
        return $this->namespace->allowedNamespacesInGeneratedSvgDom($param, $svg, $flagRemove = true);
    }

    /**
     * Precondition: The parameter are validated, that they usable by the service
     *
     * Use the parameters to do the search-actions-clean, which are defined in the reformate-array
     *
     * unittest ?
     * old 20170617
     *
     * @param array $reformateArray
     * @param string $xmlStream
     * @return string
     */
    public function reformateStream($reformateArray = [], $xmlStream = '')
    {
        $svg = $xmlStream;
        if (isset($reformateArray, $reformateArray[StringReplaceService::PARAM_REPLACE], $reformateArray[StringReplaceService::PARAM_SEARCH])) {
            $svg = $this->stringReplace->reformate($reformateArray, $svg);
        } else {
            foreach ($reformateArray as $item) {
                $svg = $this->stringReplace->reformate($item, $svg);
            }
        }
        return $svg;
    }

    /**
     * remove all tags and/or all attributes in every-tag from the dom
     *
     * unittest
     *
     * @param array $reformateArray
     * @param \DOMDocument $xmlDom
     * @return string
     */
    public function removeFromSvgDom($reformateArray = [], \DOMDocument $xmlDom = null)
    {

        $this->remove->overrideDefaultParams($reformateArray);
        if ((isset($reformateArray, $reformateArray[RemoveService::PARAM_TAGS])) &&
            (!empty($reformateArray[RemoveService::PARAM_TAGS]))
        ) {
            $xmlDom = $this->remove->removeAllTags($reformateArray[RemoveService::PARAM_TAGS], $xmlDom);
        }
        if ((isset($reformateArray, $reformateArray[RemoveService::PARAM_ATTRIBUTES])) &&
            (!empty($reformateArray[RemoveService::PARAM_ATTRIBUTES]))
        ) {
            $xmlDom = $this->remove->removeAllAttributes($reformateArray[RemoveService::PARAM_ATTRIBUTES], $xmlDom);
        }
        return $xmlDom;
    }

    /**
     * rebuild tags in the dom in the order of the definition of the items.
     * paradigm: The paramArray mzust be valid
     *
     * unittest ?
     *
     * @param array $reformateArray
     * @param \DOMDocument $xmlDom
     * @return string
     */
    public function rebuildInSvgDom($rebuildArray = [], \DOMDocument $xmlDom = null)
    {
        $this->rebuild->defineParameter($rebuildArray);

        $this->rebuild->itemListRebuildProcess($rebuildArray[RebuildService::PARAM_ITEMS], $xmlDom);

        return $xmlDom;
    }


    /**
     * reuduce a general configuration-asray to the array-parts needed by the svt-service
     *
     * unittest ?
     *
     * @param array $arguments
     * @return array
     */
    public function paramExtract(array $arguments = [])
    {
        $result = [];
        $checkout = array_merge(self::ATTRIBUTE_IGNORE_lIST, self::ATTRIBUTE_ADDITIONAL_lIST);
        foreach ($checkout as $item) {
            if (isset($arguments[$item])) {
                $result[$item] = $arguments[$item];
            }
        }
        return $result;

    }

    /**
     * reuduce a general configuration-asray to the array-parts needed by the svt-service
     *
     * unittest ?
     *
     * @param array $arguments
     * @return array
     */
    public function paramExtractIgnored(array $arguments = [])
    {
        $result = [];
        $checkout = array_merge(self::ATTRIBUTE_IGNORE_lIST, self::ATTRIBUTE_ADDITIONAL_lIST);
        foreach ($checkout as $item) {
            if (isset($arguments[$item])) {
                $result[$item] = $arguments[$item];
            }
        }
        return $result;

    }


    /**
     * reuduce a general configuration-asray to the array-parts needed by the svt-service
     *
     * unittest ?
     *
     * @param array $arguments
     * @return array
     */
    public function paramVariable(array $arguments = [])
    {
        $result = [];
        $checkout = array_merge(self::ATTRIBUTE_IGNORE_lIST, self::ATTRIBUTE_ADDITIONAL_lIST);
        foreach ($checkout as $item) {
            if (isset($arguments[$item])) {
                $result[$item] = $arguments[$item];
            } elseif (isset($arguments[self::ATTRIBUTE_PARAMETER][$item])) {
                if (isset($result[self::ATTRIBUTE_PARAMETER])) {
                    $result[self::ATTRIBUTE_PARAMETER] = [];
                }
                $result[self::ATTRIBUTE_PARAMETER][$item] = $arguments[self::ATTRIBUTE_PARAMETER][$item];
            }
        }
        return $result;

    }

    /**
     * reuduce a general configuration-asray to the array-parts needed by the svt-service
     *
     * unittest ?
     *
     * @param array $arguments
     * @return array
     */
    public function extractparamOverrideByPhpOrJson(array $arguments = [])
    {
        if ((isset($arguments[self::ATTRIBUTE_EXTRACT])) && (!empty($arguments[self::ATTRIBUTE_EXTRACT]))) {
            if (!self::paramValidate($arguments[self::ATTRIBUTE_EXTRACT], self::ATTRIBUTE_EXTRACT)) {
                throw new \UnexpectedValueException('The configuration don`t fullfill the estimated format criteria.', 1500140235);
            }
            $arguments = $this->overrideByExtractConfig($arguments);
        }
        return $arguments;

    }

    /**
     *
     * unittest ?
     *
     * @param array $arguments
     * @return array
     */
    public function overrideByExtractConfig(array $arguments = [])
    {
        if (isset($arguments, $arguments[self::ATTRIBUTE_EXTRACT])) {
            $arguments = $this->extract->getNewConfigArray($arguments[self::ATTRIBUTE_EXTRACT]);
        }
        return $arguments;
    }

    /**
     *
     * unittest ?
     *
     * @param string $param
     * @param string $stream
     * @return mixed
     */
    public function compressStream($param = '', $stream = '')
    {
        return $this->compress->compress($param, $stream);
    }


    /**
     * there are Placeholder in the parameter, which should be replaced
     *
     * @param array $param
     * @param array $arguments
     * @return array
     */
    public function replacePlaceholder($param = [], $arguments = [])
    {
        return $this->parameter->replacePlaceholder($param, $arguments);
    }

    /**
     * there are Placeholder in the parameter, which should be replaced
     *
     * @param array $param
     * @param array $stream
     * @return array
     */
    public function replaceVariable($param = [], $stream = '')
    {
        return $this->variable->replaceVariable($param, $stream);
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
        $this->rebuild->initializeRegister($param);
    }


    /**
     * @param string $inputList
     * @param array $currentRegister
     * @param string $rawString
     * @return string
     */
    public function activateCalculateRegisterIntegrate($inputList='', $currentRegister=[], $rawString= '')
    {
        $inputKeys = array_filter(
            array_map(
                'trim',
                exploade(',', $inputList)
            )
        );
        $values = [];
        foreach ($inputKeys as $item) {
            $value[] = (isset($currentRegister[$inputKeys]) ?
                $currentRegister[$inputKeys] :
                0);
        }
        return str_replace($inputKeys, $values, $rawString);
    }

    /**
     * @param array $param
     * @param string $svg
     * @param bool $errorFlag
     * @return string
     */
    public function activateCalculate($param = [], $svg= '', $errorFlag = false)
    {
        $calculationCascade = $this->parameter->readCalculateFromParam($param);
        $currentRegister = $this->rebuild->getRegister();
        $result = $svg;
        foreach ($calculationCascade as $calculateItem) {

            if (strpos($result, $calculateItem[ParameterService::SUB_PARAM_CALCULATE_START]) !== false) {
                $parts = explode($calculateItem[ParameterService::SUB_PARAM_CALCULATE_START], $result);
                $countParts = count($parts);
                for ($i = 1; $i < $countParts; $i++) {
                    $calcs = explode($calculateItem[ParameterService::SUB_PARAM_CALCULATE_END], $parts[$i]);
                    if (isset($calculateItem[ParameterService::SUB_PARAM_CALCULATE_INPUTS])) {
                        $calcs[0] = $this->activateCalculateRegisterIntegrate($calculateItem[ParameterService::SUB_PARAM_CALCULATE_INPUTS], $currentRegister, $calcs[0]);
                    }
                    $calcs[0] = $this->calculate->calculate($calcs[0], $errorFlag);
                    if (isset($calculateItem[ParameterService::SUB_PARAM_CALCULATE_OUTPUT])) {
                        $currentRegister[$calculateItem[ParameterService::SUB_PARAM_CALCULATE_OUTPUT]] = $calcs[0];
                    }
                    $parts[$i] = implode('', $calcs);
                }
                $result = implode('', $parts);
            }
        }
        $this->rebuild->setRegister($currentRegister);
        return $result;
    }
}

?>

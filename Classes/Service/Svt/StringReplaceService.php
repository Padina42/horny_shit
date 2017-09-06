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

class StringReplaceService
{

    const PARAM_REPLACE = 'replace';
    const PARAM_SEARCH = 'search';
    const PARAM_MAX = 'max';
    const PARAM_CLEAN = 'clean';

    const PARAM_METHOD = 'method';
    const PARAM_METHOD_REGEX = 'regex';
    const PARAM_METHOD_REGEX_NO_CASES = 'regexNoCase';
    const PARAM_METHOD_NORMAL = 'normal';
    const PARAM_METHOD_NORMAL_NO_CASE = 'normalNoCase';
    const PARAM_METHOD_HTML_REGEX_NO_CASE = 'htmlRegexNoCase';
    const PARAM_METHOD_HTML_NORMAL_NO_CASE = 'htmlNormalNoCase';
    const PARAM_METHOD_HTML_REGEX = 'htmlRegex';
    const PARAM_METHOD_HTML_NORMAL = 'htmlNormal';
    const PARAM_METHOD_SPECIAL_REGEX_NO_CASE = 'specialRegexNoCase';
    const PARAM_METHOD_SPECIAL_NORMAL_NO_CASE = 'specialNormalNoCase';
    const PARAM_METHOD_SPECIAL_REGEX = 'specialRegex';
    const PARAM_METHOD_SPECIAL_NORMAL = 'specialNormal';

    //use in general not the slash as limiter (see  denis_truffaut a t hotmail d o t com  in http://php.net/manual/de/function.preg-replace.php
    const UTF8_REGEX_PREG_LIMITER = '/';
    const UTF8_REGEX_PREG_MODIFICATOR_UNICODE = 'u';
    const UTF8_REGEX_PREG_MODIFICATOR_UNICODE_NO_CASE = 'iu';

    const FUNC_NORM_REPLACE = 'str_replace';
    const FUNC_NORM_REPLACE_NO_CASE = 'str_ireplace';
    const FUNC_REGEX_REPLACE = 'preg_replace';

    const PREG_DEFAULT_ESCAPE_SEARCH = ['\\', "^", ".", "$", "|", "(", ")", "[", "]", "*", "+", "?", "{", "}", ","];
    const PREG_DEFAULT_ESCAPE_REPLACE = ['\\\\', "\\^", "\\.", "\\$", "\\|", "\\(", "\\)", "\\[", "\\]", "\\*", "\\+", "\\?", "\\{", "\\}", "\\,"];


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
     * Check, if the parameter in a single record  are corrct defined for the mainparameters 'prepare' oder 'overWork'
     *
     * unittest 20170611
     * old 20170610
     *
     * @param array $param
     * @return bool
     */
    public function paramValidate($param = [])
    {
        if (isset($param[self::PARAM_METHOD])) { // tested
            $methodList = [
                self::PARAM_METHOD_REGEX,
                self::PARAM_METHOD_REGEX_NO_CASES,
                self::PARAM_METHOD_NORMAL,
                self::PARAM_METHOD_NORMAL_NO_CASE,
                self::PARAM_METHOD_HTML_REGEX_NO_CASE,
                self::PARAM_METHOD_HTML_NORMAL_NO_CASE,
                self::PARAM_METHOD_HTML_REGEX,
                self::PARAM_METHOD_HTML_NORMAL,
                self::PARAM_METHOD_SPECIAL_REGEX_NO_CASE,
                self::PARAM_METHOD_SPECIAL_NORMAL_NO_CASE,
                self::PARAM_METHOD_SPECIAL_REGEX,
                self::PARAM_METHOD_SPECIAL_NORMAL,
            ];

            $methodOption = (
                (is_string($param[self::PARAM_METHOD])) &&
                (array_search($param[self::PARAM_METHOD], $methodList, true) !== false)
            );
            $methodAdd = 1;
        } else {
            $methodOption = true;
            $methodAdd = 0;
        }

        $helpWat =  (
            $methodOption &&
            is_string($param[self::PARAM_REPLACE]) && //tested
            is_string($param[self::PARAM_SEARCH]) && //tested
            (!empty($param[self::PARAM_REPLACE])) && //tested
            (!empty($param[self::PARAM_SEARCH])) && //tested
            (
                (
                (count($param) === (2 + $methodAdd)) //tested
                ) ||
                (
                    (count($param) === (3 + $methodAdd)) && //tested
                    ( // tested
                        (isset($param[self::PARAM_MAX])) &&
                        (is_numeric($param[self::PARAM_MAX])) &&
                        (((float)$param[self::PARAM_MAX] - (int)$param[self::PARAM_MAX]) === 0.0) &&
                        ((int)$param[self::PARAM_MAX] > 0)
                    )
                ) ||
                (
                    (count($param) === (4 + $methodAdd)) && //tested
                    ( // testet
                        (isset($param[self::PARAM_MAX])) &&
                        (is_numeric($param[self::PARAM_MAX])) &&
                        (((float)$param[self::PARAM_MAX] - (int)$param[self::PARAM_MAX]) === 0.0) &&
                        ((int)$param[self::PARAM_MAX] > 0)
                    ) &&
                    ( // tested
                        (isset($param[self::PARAM_CLEAN])) &&
                        (is_string($param[self::PARAM_CLEAN]))
                    )
                )
            )
        );
        $this->validateNotifyService->registerValidationMistakes(
            $helpWat,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param
        );
        return $helpWat;

    }

    /**
     * Check, if the parameter in a single record or array of single record are corrct defined
     * for the mainparameters 'prepare' oder 'overWork'
     *
     * unittest 20170610
     *
     * @param array $param
     * @return bool
     */
    public function paramValidateSwitch($param)
    {
        $result = false;
        if (isset($param, $param[StringReplaceService::PARAM_REPLACE], $param[StringReplaceService::PARAM_SEARCH])) {
            $result = self::paramValidate($param);
        } else if (is_array($param)) {
            $result = (count($param) > 0);
            foreach ($param as $item) {
                $result = $result &&
                    (isset($item, $item[StringReplaceService::PARAM_REPLACE], $item[StringReplaceService::PARAM_SEARCH])) &&
                    self::paramValidate($item);
                if (!$result) {
                    break;
                }
            }
        } // else false
        $this->validateNotifyService->registerValidationMistakes(
            $result,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param
        );
        return $result;
    }


    /**
     * requirements: The array paramas are validated and the xml-stream could be a Stream with xml-information an svg-tag
     * The search and replace-strings can contains Regex-markups
     * There will no Quotes will not  are not allowed
     *
     * unittest 20170608
     *
     * @param array $param
     * @param string $xmlStream
     * @return array
     */
    public function reformateRegex(array $param, $xmlStream = '')
    {
        return ((isset($param[self::PARAM_MAX]) && ((int)$param[self::PARAM_MAX] >= 0)) ?
            preg_replace($param[self::PARAM_SEARCH],
                $param[self::PARAM_REPLACE],
                $xmlStream,
                $param[self::PARAM_MAX]) :
            preg_replace($param[self::PARAM_SEARCH],
                $param[self::PARAM_REPLACE],
                $xmlStream)
        );
    }

    /**
     * requirements: The array paramas are validated and the xml-stream could be a Stream with xml-information an svg-tag
     * The earch-replace will done byte-wise
     * Quotes are not allowed
     *
     * unittest 20170605
     *
     * @param array $param
     * @param string $xmlStream
     * @param string $xmlStream
     * @return string
     */
    public function reformateCallback(array $param, string $xmlStream = '', string $phpReplaceFunction)
    {
        $result = $xmlStream;
        if ((isset($param[self::PARAM_MAX])) && ((int)$param[self::PARAM_MAX] > 0)) {
            $result = $phpReplaceFunction(
                $param[self::PARAM_SEARCH],
                $param[self::PARAM_REPLACE],
                $xmlStream,
                $param[self::PARAM_MAX]);
            if ((isset($param[self::PARAM_CLEAN])) && (is_string($param[self::PARAM_CLEAN]))) {
                $result = $phpReplaceFunction(
                    $param[self::PARAM_SEARCH],
                    $param[self::PARAM_CLEAN],
                    $result);
            }
        } else {
            $result = $phpReplaceFunction(
                $param[self::PARAM_SEARCH],
                $param[self::PARAM_REPLACE],
                $xmlStream);
        }
        return $result;
    }

    /**
     * reconvert specialchars with default-definitions into their normal entities
     *
     * unittest 20170604
     *
     * @param array $param
     * @param string $xmlStream
     * @return array
     */
    public function reformateParamSearchReplaceHtmlEntities(array $param)
    {
        $param[self::PARAM_SEARCH] = html_entity_decode($param[self::PARAM_SEARCH]);
        $param[self::PARAM_REPLACE] = html_entity_decode($param[self::PARAM_REPLACE]);
        if (isset($param[self::PARAM_CLEAN])) {
            $param[self::PARAM_CLEAN] = html_entity_decode($param[self::PARAM_CLEAN]);
        }
        return $param;
    }

    /**
     * reconvert specialchars with default-definitions into their normal entities
     *
     * unittest ?
     *
     * @param array $param
     * @param string $xmlStream
     * @return array
     */
    public function reformateParamSearchReplaceHtmlSpecial(array $param)
    {
        $param[self::PARAM_SEARCH] = htmlspecialchars_decode($param[self::PARAM_SEARCH]);
        $param[self::PARAM_REPLACE] = htmlspecialchars_decode($param[self::PARAM_REPLACE]);
        if (isset($param[self::PARAM_CLEAN])) {
            $param[self::PARAM_CLEAN] = htmlspecialchars_decode($param[self::PARAM_CLEAN]);
        }
        return $param;
    }

    /**
     * Rebuild the searchstring for the search with regular expressions
     *
     * unittest 20170604
     *
     * @param array $param
     * @param string $limiter
     * @param string $modificators
     * @return array
     */
    public function reformateParamSearchForRegular(array $param, string $limiter, string $modificators)
    {
        $param[self::PARAM_SEARCH] = $limiter . $param[self::PARAM_SEARCH] . $limiter . $modificators;
        return $param;
    }

    /**
     * escape all importeant chatrs in a string, which should be replaceed
     *
     * unittest 20170604
     *
     * @param array $param
     * @return array
     */
    public function escapeParamSearchForRegular($param)
    {
        $param[self::PARAM_SEARCH] = str_replace(self::PREG_DEFAULT_ESCAPE_SEARCH,
            self::PREG_DEFAULT_ESCAPE_REPLACE,
            $param[self::PARAM_SEARCH]);
        $param[self::PARAM_SEARCH] = str_replace(self::UTF8_REGEX_PREG_LIMITER,
            '\\' . self::UTF8_REGEX_PREG_LIMITER,
            $param[self::PARAM_SEARCH]);
        return $param;
    }

    /**
     * escape all $-character with following numbers, because regex will detect them as variables
     *
     * unittest 20170617
     *
     * @param array $param
     * @return array
     */
    public function escapeParamReplaceForRegular($param)
    {
        $param[self::PARAM_REPLACE] = preg_replace('/\$(\d+)/',
            '\\\$$1',
            $param[self::PARAM_REPLACE]);
        return $param;
    }

    /**
     * use regex_replace for regular replace with limits
     *
     * unittest 20170604
     *
     * @param array $param
     * @param string $xmlStream
     * @param string $modificator
     * @return string
     */
    public function reformateCallbackNormalLimit($param, $xmlStream, $modificator)
    {
        $param = self::escapeParamSearchForRegular($param);
        $param = self::escapeParamReplaceForRegular($param);
        $param = self::reformateParamSearchForRegular($param,
            self::UTF8_REGEX_PREG_LIMITER,
            $modificator);
        $a = $param[StringReplaceService::PARAM_SEARCH];
        return self::reformateCallback($param,
            $xmlStream,
            self::FUNC_REGEX_REPLACE);
    }

    /**
     *
     * unittest ?
     *
     * @param array $generalConfig
     * @param array $param
     * @return array
     */
    public function reformateParamToUnescapedChars($generalConfig = [], $param = [])
    {
        $result = $param;
        foreach (ConfigService::DEFAULT_LIST as $searchKey => $ignoreThisValue) {
            $result[self::PARAM_SEARCH] = str_replace(
                $generalConfig[$searchKey][ConfigService::PARAM_SUB_SEARCH],
                $generalConfig[$searchKey][ConfigService::PARAM_SUB_REPLACE],
                $result[self::PARAM_SEARCH]
            );
            $result[self::PARAM_REPLACE] = str_replace(
                $generalConfig[$searchKey][ConfigService::PARAM_SUB_SEARCH],
                $generalConfig[$searchKey][ConfigService::PARAM_SUB_REPLACE],
                $result[self::PARAM_REPLACE]
            );
            if (isset($result[self::PARAM_CLEAN])) {
                $result[self::PARAM_CLEAN] = str_replace(
                    $generalConfig[$searchKey][ConfigService::PARAM_SUB_SEARCH],
                    $generalConfig[$searchKey][ConfigService::PARAM_SUB_REPLACE],
                    $result[self::PARAM_CLEAN]
                );
            }

        }
        return $result;
    }

    /**
     * if the item for the method in the  Param-array contain one methodsname with the Html-Aspect,
     * the chars of the search-string will be reextract by the method reformateParamSearchReplaceHtmlEntities
     *
     * unittest ?
     * old 20170611
     *
     * @param array $param
     * @return array
     */
    public function reformateFreeParamFromHtmlCharCodes($param)
    {
        switch ($param[self::PARAM_METHOD]) {
            case self::PARAM_METHOD_HTML_NORMAL :
            case self::PARAM_METHOD_HTML_NORMAL_NO_CASE :
            case self::PARAM_METHOD_HTML_REGEX :
            case self::PARAM_METHOD_HTML_REGEX_NO_CASE :
                $param = self::reformateParamSearchReplaceHtmlEntities($param);
                break;
            case self::PARAM_METHOD_SPECIAL_REGEX_NO_CASE :
            case self::PARAM_METHOD_SPECIAL_NORMAL_NO_CASE :
            case self::PARAM_METHOD_SPECIAL_REGEX :
            case self::PARAM_METHOD_SPECIAL_NORMAL :
                $param = self::reformateParamSearchReplaceHtmlSpecial($param);
                break;
            default:
                $this->validateNotifyService->registerValidationMistakes(
                    true,
                    ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                    $param[self::PARAM_METHOD]
                );
                break;
        }
        return $param;
    }

    /**
     * If the option HTML is set in the method, the stream must be convertetd
     *
     * unittest ?
     *
     * @param string $xmlStream
     * @param array $param
     * @return string
     */
    public function reformateFreeXmlStreamFromHtmlCharCodes($xmlStream, $param)
    {
        switch ($param[self::PARAM_METHOD]) {
            case self::PARAM_METHOD_HTML_NORMAL :
            case self::PARAM_METHOD_HTML_NORMAL_NO_CASE :
            case self::PARAM_METHOD_HTML_REGEX :
            case self::PARAM_METHOD_HTML_REGEX_NO_CASE :
                $xmlStream = html_entity_decode($xmlStream);
                break;
            default:
                $this->validateNotifyService->registerValidationMistakes(
                    true,
                    ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                    $param[self::PARAM_METHOD]
                );
                break;
        }
        return $xmlStream;
    }

    /**
     * if the item for the method in the  Param-array contain one methodsname with an Regex-aspect,
     * the search-string will be inluded by limiterr-constants '/' and the options for unicode or case-insensitive unicode
     * will be added by the method reformateParamSearchForRegular
     *
     * unittest ?
     * old 20170611
     *
     * @param $param
     * @return array
     */
    public function reformateAddRegexParameterToSearchPart($param)
    {
        switch ($param[self::PARAM_METHOD]) {
            case self::PARAM_METHOD_REGEX :
            case self::PARAM_METHOD_HTML_REGEX :
            case self::PARAM_METHOD_SPECIAL_REGEX :
                $param = self::reformateParamSearchForRegular($param,
                    self::UTF8_REGEX_PREG_LIMITER,
                    self::UTF8_REGEX_PREG_MODIFICATOR_UNICODE);
                break;
            case self::PARAM_METHOD_REGEX_NO_CASES :
            case self::PARAM_METHOD_HTML_REGEX_NO_CASE :
            case self::PARAM_METHOD_SPECIAL_REGEX_NO_CASE :
                $param = self::reformateParamSearchForRegular($param,
                    self::UTF8_REGEX_PREG_LIMITER,
                    self::UTF8_REGEX_PREG_MODIFICATOR_UNICODE_NO_CASE);
                break;
            default:
                $this->validateNotifyService->registerValidationMistakes(
                    true,
                    ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                    $param[self::PARAM_METHOD]
                );
                break;
        }
        return $param;
    }

    /**
     * Switch between normal search-replace and a reguilare search_replace with limit-Information (case-sensitive)
     *
     * unittest 20170611
     *
     * @param array $param
     * @param string $xmlStream
     * @return string
     */
    public function reformateNormalFlagLimit($param, $xmlStream)
    {
        if ((isset($param[self::PARAM_MAX])) && ((int)$param[self::PARAM_MAX] > 0)) {
            $result = self::reformateCallbackNormalLimit($param,
                $xmlStream,
                self::UTF8_REGEX_PREG_MODIFICATOR_UNICODE);
        } else {
            $result = self::reformateCallback($param, $xmlStream,
                self::FUNC_NORM_REPLACE);
        }
        return $result;
    }

    /**
     * Switch between normal search-replace and a reguilare search_replace with limit-Information (case-IN-sensitive)
     *
     * unittest 20170611
     *
     * @param array $param
     * @param string $xmlStream
     * @return string
     */
    public function reformateNormalCaseInsensitiveFlagLimit($param, $xmlStream)
    {
        if ((isset($param[self::PARAM_MAX])) && ((int)$param[self::PARAM_MAX] > 0)) {
            $result = self::reformateCallbackNormalLimit($param,
                $xmlStream,
                self::UTF8_REGEX_PREG_MODIFICATOR_UNICODE_NO_CASE);
        } else {
            $result = self::reformateCallback($param,
                $xmlStream,
                self::FUNC_NORM_REPLACE_NO_CASE);
        }
        return $result;
    }


    /**
     * The regex will be called with predefined parameters
     *
     * unittest 20170611
     *
     * @param array $param
     * @param string $xmlStream
     * @return string
     */
    public function reformateRegexDefault($param, $xmlStream)
    {
        return self::reformateCallback($param,
            $xmlStream,
            self::FUNC_REGEX_REPLACE);
    }

    /**
     * Requirement: the params must be usable for the emthod
     * the method normal, normalInsensitive oder regex, which is defined within $param, is called
     *
     * unittest ?
     * partiell 20170617
     *
     * @param array $param
     * @param string $xmlStream
     * @return string
     */
    public function reformateSwitchMethodAfterParamPreparation($param, $xmlStream)
    {
        switch ($param[self::PARAM_METHOD]) {
            case self::PARAM_METHOD_HTML_NORMAL :
            case self::PARAM_METHOD_SPECIAL_NORMAL :
            case self::PARAM_METHOD_NORMAL :
                $result = self::reformateNormalFlagLimit($param, $xmlStream);
                break;
            case self::PARAM_METHOD_HTML_NORMAL_NO_CASE :
            case self::PARAM_METHOD_SPECIAL_NORMAL_NO_CASE :
            case self::PARAM_METHOD_NORMAL_NO_CASE :
                $result = $this->reformateNormalCaseInsensitiveFlagLimit($param, $xmlStream);
                break;
            case self::PARAM_METHOD_HTML_REGEX :
            case self::PARAM_METHOD_SPECIAL_REGEX :
            case self::PARAM_METHOD_REGEX :
            case self::PARAM_METHOD_HTML_REGEX_NO_CASE :
            case self::PARAM_METHOD_SPECIAL_REGEX_NO_CASE :
            case self::PARAM_METHOD_REGEX_NO_CASES :
                $result = self::reformateRegexDefault($param, $xmlStream);
                break;
            default:
                $this->validateNotifyService->registerValidationMistakes(
                    true,
                    ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
                    $param[self::PARAM_METHOD]
                );
                $result = self::reformateNormalFlagLimit($param, $xmlStream);
                break;
        }
        return $result;
    }

    /**
     * requirements: The array paramas are validated and the xml-stream could be a Stream with xml-information an svg-tag
     * The Parama will pre prepared for the Method and the the method is used
     *
     * unittest ?
     * old 20170617
     *
     * @param array $param
     * @param string $xmlStream
     * @return string
     */
    public function reformate(array $param, $xmlStream = '')
    {
        $result = $xmlStream;
        if (is_string($xmlStream) && !empty($xmlStream)) {
            if (isset($param[self::PARAM_METHOD])) {
                $param = self::reformateFreeParamFromHtmlCharCodes($param);
                $xmlStream = self::reformateFreeXmlStreamFromHtmlCharCodes($xmlStream, $param);
                $param = self::reformateAddRegexParameterToSearchPart($param);
                $result = self::reformateSwitchMethodAfterParamPreparation($param, $xmlStream);
            } else {
                $result = self::reformateNormalFlagLimit($param, $xmlStream);
            }
        }
        return $result;
    }
}


?>

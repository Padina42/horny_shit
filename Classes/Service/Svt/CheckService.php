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
 * initial valation-checks of a SVG-stream
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

class CheckService
{

    const KEYS_CHECK_SVG__NONE = 'none';
    const KEYS_CHECK_SVG__XML_SVG = 'xmlSvg';
    const KEYS_CHECK_SVG__SVG = 'svg';
    const KEYS_CHECK_SVG__XML = 'xml';

    // used in comment of svgViewhelper
    const KEYS_CHECK_LISTING_TEXT = self::KEYS_CHECK_SVG__XML . ', ' . self::KEYS_CHECK_SVG__SVG . ', ' .
    self::KEYS_CHECK_SVG__NONE . ' or ' . self::KEYS_CHECK_SVG__XML_SVG . ' (default)';


    /**
     * @var ValidateNotifyService
     */
    protected $validateNotifyService = null;

    /**
     * CheckService constructor with languagekey for errormessages
     *
     * @param string $languageKey
     */
    public function __construct($languageKey = '')
    {
        $this->validateNotifyService = ValidateNotifyService::getInstance();
        $this->validateNotifyService->changeLanguage($languageKey);
    }

    /**
     * checks, if only one correct svg-tag exists and has the correct encoding-definition
     *
     * unittest
     * old 20170603
     *
     * @param string $xmlStream
     * @return bool
     */
    public function hasOneValidSvgTag($xmlStream)
    {
        $valid = (
            (substr_count($xmlStream, '<svg') === 1) &&
            (substr_count($xmlStream, '</svg>') === 1) &&
            (strpos($xmlStream, '<svg')) < strpos($xmlStream, '</svg>')
        );

        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            ((strlen($xmlStream) > 100) ?
                substr($xmlStream, 0, 50) . ' ... ' . substr($xmlStream, -50) :
                $xmlStream
            )
        );
        return $valid;


    }

    /**
     * checks, if only one correct svg-tag exists and has the correct encoding-definition
     *
     * unittest ?
     * old 20170603
     *
     * @param string $xmlStream
     * @return bool
     */
    public function hasOneDocumentEntity($xmlStream)
    {
        $valid = (preg_match('/^<\?xml(.+)encoding=\"(UTF|utf)-8\"([^>]+)>/i', $xmlStream) === 1);
        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            ((strlen($xmlStream) > 100) ?
                substr($xmlStream, 0, 50) . ' ... ' . substr($xmlStream, -50) :
                $xmlStream
            )
        );
        return $valid;
    }

    /**
     * switch to different configurations of test-scenarios
     *
     * unittest 20170603
     *
     * @param string $flags
     * @param string $xmlStream
     * @return bool
     */
    public function checkSpecification($flag, $xmlStream)
    {
        $valid = is_string($flag) && ($flag !== '');
        if (is_string($flag)) {
            switch ($flag) {
                case self::KEYS_CHECK_SVG__SVG:
                    $valid = $valid && self::hasOneValidSvgTag($xmlStream);
                    break;
                case self::KEYS_CHECK_SVG__XML:
                    $valid = $valid && self::hasOneDocumentEntity($xmlStream);
                    break;
                case self::KEYS_CHECK_SVG__XML_SVG:
                    $valid = $valid && self::hasOneDocumentEntity($xmlStream);
                    $valid = $valid && self::hasOneValidSvgTag($xmlStream);
                    break;
                case self::KEYS_CHECK_SVG__NONE:
                    $valid = true;
                    break;
                default:
                    $valid = false;
                    break;
            }
        }

        return $valid;
    }

    /**
     * switch to different configurations of test-scenarios
     *
     * unittest ?
     * old 20170603
     *
     * @param string $flag
     * @return bool
     */
    public function validateSpecification($flag)
    {
        $valid = false;
        if (is_string($flag)) {
            switch ($flag) {
                case self::KEYS_CHECK_SVG__SVG:
                case self::KEYS_CHECK_SVG__XML:
                case self::KEYS_CHECK_SVG__XML_SVG:
                case self::KEYS_CHECK_SVG__NONE:
                    $valid = true;
                    break;
            }
        }
        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $flag);
        return $valid;
    }

    /**
     * Check, if the parameter are corrct defined for the main-parameter 'checkSvg'
     *
     * unittest ?
     *
     * @param mixed $param
     */
    public function paramValidate($param = null)
    {
        $valid = (
            is_string($param) &&
            self::validateSpecification($param)
        );
        $this->validateNotifyService->registerValidationMistakes(
            $valid,
            ['method' => __FUNCTION__, 'class' => __CLASS__, 'line' => __LINE__],
            $param);

        return $valid;
    }

}

?>

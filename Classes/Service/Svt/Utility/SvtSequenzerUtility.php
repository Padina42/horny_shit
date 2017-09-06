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

use Porth\HornyShit\Service\Svt\ParameterService;
use Porth\HornyShit\Service\SvtService;

use Porth\HornyShit\Service\Svt\Utility\ValidateNotifyService;
use Porth\HornyShit\Service\Svt\ConfigService;


/**
 * class SvtSequenzerUtility
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
class SvtSequenzerUtility
{

    /**
     * unittest
     *
     * @param array $arguments
     * @param $svg
     * @param SvtService $svtService
     * @return array
     */
    public static function sequenceOfSvtInternalPreparation(array $arguments, $svg, SvtService $svtService) {
        $svtService->activateConfigValidation($arguments);

        if (isset($arguments[SvtService::ATTRIBUTE_IGNORE])) {
            if (($svtService->paramValidate($arguments[SvtService::ATTRIBUTE_IGNORE], SvtService::ATTRIBUTE_IGNORE))
            ) {
                throw new \UnexpectedValueException('The ignorelist is no string or it contains a undefined casesensitive entry.', 1500140299);
            }
            $arguments = $svtService->paramRemoveIgnored($arguments);
        }
        return $arguments;
    }

    /**
     * unittest
     *
     * @param array $arguments
     * @param string $svg
     * @param SvtService $svtService
     */
    public static function sequenceOfSvtInternalBeforeLast(array $arguments, $svg, SvtService $svtService)
    {
        $flagOkayParameter = $svtService->paramValidate(
            $arguments[SvtService::ATTRIBUTE_PARAMETER],
            SvtService::ATTRIBUTE_PARAMETER
        );
        // operations on svg-string
        if ((isset($arguments[SvtService::ATTRIBUTE_PARAMETER], $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_PLACEHOLDER])) &&
            $flagOkayParameter
        ) {
            $arguments = $svtService->replacePlaceholder(
                $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_PLACEHOLDER],
                $arguments);
        }

        // operations on svg-string
        if ((isset($arguments[SvtService::ATTRIBUTE_PARAMETER], $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_REGISTER])) &&
            $flagOkayParameter
        ) {
            $svg = $svtService->initializeRegister(
                $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_REGISTER]
            );
        }


        if ((isset($arguments[SvtService::ATTRIBUTE_CHECK_SVG])) &&
            (!$svtService->paramValidate($arguments[SvtService::ATTRIBUTE_CHECK_SVG], SvtService::ATTRIBUTE_CHECK_SVG)) &&
            (!$svtService->checkSvgStream($arguments[SvtService::ATTRIBUTE_CHECK_SVG], $svg))
        ) {
            throw new \UnexpectedValueException('The svg don´t exist or don´t fullfill the estimated criteria. (one xml-tag or one svg-tag)', 1495291550);
        }
        // operations on svg-string
        if ((isset($arguments[SvtService::ATTRIBUTE_PREPARE])) &&
            ($svtService->paramValidate(
                $arguments[SvtService::ATTRIBUTE_PREPARE],
                SvtService::ATTRIBUTE_PREPARE
            ))
        ) {
            $svg = $svtService->reformateStream(
                $arguments[SvtService::ATTRIBUTE_PREPARE],
                $svg);
        }

        // transform the svg-string into an SVG-DOM-Document
        if ((isset($arguments[SvtService::ATTRIBUTE_NAMESPACE])) &&
            ($svtService->paramValidate($arguments[SvtService::ATTRIBUTE_NAMESPACE], SvtService::ATTRIBUTE_NAMESPACE))
        ) {
            $svgDom = $svtService->allowedNamespacesInGeneratedSvgDom(
                $arguments[SvtService::ATTRIBUTE_NAMESPACE],
                $svg,
                true);
        } else {
            $flagRemoveNamespace = (
                (!isset($arguments[SvtService::ATTRIBUTE_IGNORE])) ||
                (strpos($arguments[SvtService::ATTRIBUTE_IGNORE], SvtService::ATTRIBUTE_NAMESPACE) === false)
            );
            $svgDom = $svtService->allowedNamespacesInGeneratedSvgDom(
                [],
                $svg,
                $flagRemoveNamespace
            );
        }

        if ((isset($arguments[SvtService::ATTRIBUTE_REMOVE])) &&
            ($svtService->paramValidate($arguments[SvtService::ATTRIBUTE_REMOVE], SvtService::ATTRIBUTE_REMOVE))
        ) {
            $svtService->removeFromSvgDom(
                $arguments[SvtService::ATTRIBUTE_REMOVE],
                $svgDom);
        }

        if ((isset($arguments[SvtService::ATTRIBUTE_REBUILD])) &&
            ($svtService->paramValidate($arguments[SvtService::ATTRIBUTE_REBUILD], SvtService::ATTRIBUTE_REBUILD))
        ) {
            $svtService->rebuildInSvgDom(
                $arguments[SvtService::ATTRIBUTE_REBUILD],
                $svgDom);
        }


        $svg = $svgDom->saveXML();
        // operations on svg-string
        if ((isset($arguments[SvtService::ATTRIBUTE_WORK_OVER])) &&
            ($svtService->paramValidate($arguments[SvtService::ATTRIBUTE_WORK_OVER], SvtService::ATTRIBUTE_WORK_OVER))
        ) {
            $svg = $svtService->reformateStream(
                $arguments[SvtService::ATTRIBUTE_WORK_OVER],
                $svg);
        }
        if ((isset($arguments[SvtService::ATTRIBUTE_COMPRESS])) &&
            (!empty($arguments[SvtService::ATTRIBUTE_COMPRESS])) &&
            ($svtService->paramValidate($arguments[SvtService::ATTRIBUTE_COMPRESS], SvtService::ATTRIBUTE_COMPRESS))
        ) {
            $svg = $svtService->compressStream(
                $arguments[SvtService::ATTRIBUTE_COMPRESS],
                $svg);
        }

        // operations on svg-string
        if ((isset($arguments[SvtService::ATTRIBUTE_PARAMETER], $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE])) &&
            $flagOkayParameter
        ) {
            $showCalculateError = (isset($arguments[SvtService::ATTRIBUTE_INFO_CALCULATE_ERROR])?
                ($arguments[self::ATTRIBUTE_INFO_CALCULATE_ERROR] != false):
                false
            );
            $svg = $svtService->activateCalculate(
                $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE],
                $svg,
                $showCalculateError
            );
        }
        return $svg;

    }


    /**
     * unittest
     *
     * @param array $arguments
     * @param string $svg
     * @param SvtService $svtService
     * @return array|string
     */
    public static function sequenceOfSvtInternalOnlyLast(array $arguments, $svg, SvtService $svtService)
    {
        // This could not be usset by ignore
        // set dynamicllay operations on svg-string
        if ((isset($arguments[SvtService::ATTRIBUTE_VARIABLE]))) {
            $svg = $svtService->replaceVariable(
                $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_REGISTER]
            );
        }

        // This could not be usset by ignore
        // operations on svg-string
        if ((isset($arguments[SvtService::ATTRIBUTE_PARAMETER], $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE_LAST])) &&
            $flagOkayParameter
        ) {
            $showCalculateError = (isset($arguments[SvtService::ATTRIBUTE_INFO_CALCULATE_ERROR])?
                ($arguments[self::ATTRIBUTE_INFO_CALCULATE_ERROR] != false):
                false
            );
            $svg = $svtService->activateCalculate(
                $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE_LAST],
                $svg,
                $showCalculateError
            );
        }
        return $svg;
    }

    /**
     * unittest
     *
     * @param array $arguments
     * @param string $svg
     * @param SvtService $svtService
     * @return mixed|string|void
     */
    public static function sequenceOfSvtBeforeLast(array $arguments, $svg, SvtService $svtService)
    {
        $arguments  = self::sequenceOfSvtInternalPreparation( $arguments, $svg, $svtService);
        $svg = self::sequenceOfSvtInternalBeforeLast( $arguments, $svg, $svtService);
        return $svg;
    }

    /**
     * unittest
     *
     * @param array $arguments
     * @param string $svg
     * @param SvtService $svtService
     * @return array|string
     */
    public static function sequenceOfSvtOnlyLast(array $arguments, $svg, SvtService $svtService)
    {
        $arguments  = self::sequenceOfSvtInternalPreparation( $arguments, $svg, $svtService);
        $svg = self::sequenceOfSvtInternalOnlyLast( $arguments, $svg, $svtService);
        return $svg;
    }

    /**
     * start the sequence of the rebuild of a svg
     *
     * unittest ?
     *
     * @param array $arguments
     * @param string $svg
     * @param SvtService $svtService
     */
    public static function sequenceOfSvt( array $arguments, $svg, SvtService $svtService)
    {
        $arguments  = self::sequenceOfSvtInternalPreparation( $arguments, $svg, $svtService);
//        $svtService->activateConfigValidation($arguments);
//
//        if (isset($arguments[SvtService::ATTRIBUTE_IGNORE])) {
//            if (($svtService->paramValidate($arguments[SvtService::ATTRIBUTE_IGNORE], SvtService::ATTRIBUTE_IGNORE))
//            ) {
//                throw new \UnexpectedValueException('The ignorelist is no string or it contains a undefined casesensitive entry.', 1500140299);
//            }
//            $arguments = $svtService->paramRemoveIgnored($arguments);
//        }

        $svg = self::sequenceOfSvtInternalBeforeLast( $arguments, $svg, $svtService);
//        $flagOkayParameter = $svtService->paramValidate(
//            $arguments[SvtService::ATTRIBUTE_PARAMETER],
//            SvtService::ATTRIBUTE_PARAMETER
//        );
//        // operations on svg-string
//        if ((isset($arguments[SvtService::ATTRIBUTE_PARAMETER], $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_PLACEHOLDER])) &&
//            $flagOkayParameter
//        ) {
//            $arguments = $svtService->replacePlaceholder(
//                $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_PLACEHOLDER],
//                $arguments);
//        }
//
//        // operations on svg-string
//        if ((isset($arguments[SvtService::ATTRIBUTE_PARAMETER], $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_REGISTER])) &&
//            $flagOkayParameter
//        ) {
//            $svg = $svtService->initializeRegister(
//                $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_REGISTER]
//            );
//        }
//
//
//        if ((isset($arguments[SvtService::ATTRIBUTE_CHECK_SVG])) &&
//            (!$svtService->paramValidate($arguments[SvtService::ATTRIBUTE_CHECK_SVG], SvtService::ATTRIBUTE_CHECK_SVG)) &&
//            (!$svtService->checkSvgStream($arguments[SvtService::ATTRIBUTE_CHECK_SVG], $svg))
//        ) {
//            throw new \UnexpectedValueException('The svg don´t exist or don´t fullfill the estimated criteria. (one xml-tag or one svg-tag)', 1495291550);
//        }
//        // operations on svg-string
//        if ((isset($arguments[SvtService::ATTRIBUTE_PREPARE])) &&
//            ($svtService->paramValidate(
//                $arguments[SvtService::ATTRIBUTE_PREPARE],
//                SvtService::ATTRIBUTE_PREPARE
//            ))
//        ) {
//            $svg = $svtService->reformateStream(
//                $arguments[SvtService::ATTRIBUTE_PREPARE],
//                $svg);
//        }
//
//        // transform the svg-string into an SVG-DOM-Document
//        if ((isset($arguments[SvtService::ATTRIBUTE_NAMESPACE])) &&
//            ($svtService->paramValidate($arguments[SvtService::ATTRIBUTE_NAMESPACE], SvtService::ATTRIBUTE_NAMESPACE))
//        ) {
//            $svgDom = $svtService->allowedNamespacesInGeneratedSvgDom(
//                $arguments[SvtService::ATTRIBUTE_NAMESPACE],
//                $svg,
//                true);
//        } else {
//            $flagRemoveNamespace = (
//                (!isset($arguments[SvtService::ATTRIBUTE_IGNORE])) ||
//                (strpos($arguments[SvtService::ATTRIBUTE_IGNORE], SvtService::ATTRIBUTE_NAMESPACE) === false)
//            );
//            $svgDom = $svtService->allowedNamespacesInGeneratedSvgDom(
//                [],
//                $svg,
//                $flagRemoveNamespace
//            );
//        }
//
//        if ((isset($arguments[SvtService::ATTRIBUTE_REMOVE])) &&
//            ($svtService->paramValidate($arguments[SvtService::ATTRIBUTE_REMOVE], SvtService::ATTRIBUTE_REMOVE))
//        ) {
//            $svtService->removeFromSvgDom(
//                $arguments[SvtService::ATTRIBUTE_REMOVE],
//                $svgDom);
//        }
//
//        if ((isset($arguments[SvtService::ATTRIBUTE_REBUILD])) &&
//            ($svtService->paramValidate($arguments[SvtService::ATTRIBUTE_REBUILD], SvtService::ATTRIBUTE_REBUILD))
//        ) {
//            $svtService->rebuildInSvgDom(
//                $arguments[SvtService::ATTRIBUTE_REBUILD],
//                $svgDom);
//        }
//
//
//        $svg = $svgDom->saveXML();
//        // operations on svg-string
//        if ((isset($arguments[SvtService::ATTRIBUTE_WORK_OVER])) &&
//            ($svtService->paramValidate($arguments[SvtService::ATTRIBUTE_WORK_OVER], SvtService::ATTRIBUTE_WORK_OVER))
//        ) {
//            $svg = $svtService->reformateStream(
//                $arguments[SvtService::ATTRIBUTE_WORK_OVER],
//                $svg);
//        }
//        if ((isset($arguments[SvtService::ATTRIBUTE_COMPRESS])) &&
//            (!empty($arguments[SvtService::ATTRIBUTE_COMPRESS])) &&
//            ($svtService->paramValidate($arguments[SvtService::ATTRIBUTE_COMPRESS], SvtService::ATTRIBUTE_COMPRESS))
//        ) {
//            $svg = $svtService->compressStream(
//                $arguments[SvtService::ATTRIBUTE_COMPRESS],
//                $svg);
//        }
//
//        // operations on svg-string
//        if ((isset($arguments[SvtService::ATTRIBUTE_PARAMETER], $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE])) &&
//            $flagOkayParameter
//        ) {
//            $arguments = $svtService->activateCalculate(
//                $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE],
//                $arguments,
//                $showCalculateError
//            );
//        }

        $svg = self::sequenceOfSvtInternalOnlyLast( $arguments, $svg, $svtService);
//        // This could not be usset by ignore
//        // set dynamicllay operations on svg-string
//        if ((isset($arguments[SvtService::ATTRIBUTE_VARIABLE]))) {
//            $svg = $svtService->replaceVariable(
//                $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_REGISTER]
//            );
//        }
//
//        // This could not be usset by ignore
//        // operations on svg-string
//        if ((isset($arguments[SvtService::ATTRIBUTE_PARAMETER], $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE_LAST])) &&
//            $flagOkayParameter
//        ) {
//            $arguments = $svtService->activateCalculate(
//                $arguments[SvtService::ATTRIBUTE_PARAMETER][ParameterService::PARAM_CALCULATE_LAST],
//                $arguments,
//                $showCalculateError
//                );
//        }

        return $svg;
    }
}

?>

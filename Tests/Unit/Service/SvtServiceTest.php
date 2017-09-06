<?php

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
 * Created by PhpStorm.
 * User: dporth
 * Date: 21.05.2017
 * Time: 07:14
 */

namespace Porth\HornyShit\Service;

use PHPUnit\Framework\TestCase;

use Porth\HornyShit\Service\Svt\CheckService;
use Porth\HornyShit\Service\Svt\ConfigService;
use Porth\HornyShit\Service\Svt\StringReplaceService;

//use Porth\HornyShit\Service\SvtService;

class SvtServiceSecondTest extends TestCase
{

    /** Convention of Naming of Tests
     * Method Return Result IF Parameter WhenStubs/WhenEver-
     *
     * Convention of Naming of dataProvider
     * Method Result Boolean VariateBy Parameter Provider Given (restrictions)
     * A Data contains at first a message, at second the result and after that the Parameter-Variation
     *
     * Remark:
     * It is easier in some cases, to trabnsfer the problem systemmaticly in dataprovider-Arrays and use an general test-function
     */

    /**
     * @var SvtService
     */
    protected $subject = NULL;

    public function setUp()
    {
        $this->subject = SvtService::getInstance();
        $this->subject->buildService();

    }

    public function tearDown()
    {
        unset($this->subject);
    }


    public function dataProviderExtendArgumentsToDefaultArrayDeletResultArrayDletePartIfVariationsOfMainResultOfDeltePArtStringsAndSubtrahensArrayGiven()
    {
        return [
            ['empty array expected, if the list-array is empty and if the subpart-array is empty ', [],
                [], [],[]],
        ];
    }
    /**
     *
     */
    public function extendArgumentsToDefaultArrayDeleteReturnExpectedDeletePartOrfArrayIfInputContainMainArrrayWithDeletePartStringAndArrayWithSubtrahendElmenets(
        $message,
        $expect,
        $arrayOfsubPart,
        $deleteArray)
    {
        if (!isset($expect) && empty($expect))  {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->extendArgumentsToDefaultArrayDelete(
                    $arrayOfsubPart,
                    $deleteArray),
                $message
            );
        }

    }

    public function dataProviderCheckSvgStreamReturnExpectedBooleanIfTheSpefifiedFlagIsSet()
    {
        $allowedXmlStream = '<?xml ... encoding="UTF-8" ...><svg> hallo Test </svg>';
        $allowedFlag = CheckService::KEYS_CHECK_SVG__XML_SVG;
        return [
            ['False is expected, if the flag is set to null and if the stream is not a string.', false,
                ['flag' => null, 'xmlStream' => null]],
            ['False is expected, if the flag is set with an allowed string and if the stream is not a string', false,
                ['flag' => $allowedFlag, 'xmlStream' => null]],
            ['True is expected, if the flag is not a string and if the stream is an allowed string', true,
                ['flag' => null, 'xmlStream' => $allowedXmlStream]],
            ['false is expected, if the flag is not a string and if the stream is  not a string ', false,
                ['flag' => null, 'xmlStream' => 2.475]],
            ['False is expected, if the flag is not a string and if the stream is a allowed string', false,
                ['flag' => [$allowedFlag], 'xmlStream' => $allowedXmlStream]],
            ['False is expected, if the flag is a allowed string and if the stream is not a string', false,
                ['flag' => $allowedFlag, 'xmlStream' => [$allowedXmlStream]]],

            ['True is expected, if the flag is a allowed string and if the stream is a alloweed string', false,
                ['flag' => $allowedFlag, 'xmlStream' => [$allowedXmlStream]]],

        ];

    }

    /**
     * @dataProvider dataProviderCheckSvgStreamReturnExpectedBooleanIfTheSpefifiedFlagIsSet
     * @test
     */
    public function checkSvgStreamReturnExpectedBolleanIfTheSpecefiedFlagIsSet($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->checkSvgStream($params['flag'], $params['xmlStream']),
                $message
            );
        }
    }


    public function dataProviderParamValidateReturnExpectedBooleanIfVariationOfDetailedSpecifiedKeyIsAndIfSimpleArraysUsed()
    {

        return [

            ['False is expected, if the unknown paramtype XDFERGHT is used and a null is used.', false,
                ['params' => null, 'paramtype' => 'XDFERGHT']],

            ['False is expected, if the unknown paramtype XDFERGHT is used and a allowed parmeter for the method ´' . SvtService::ATTRIBUTE_CHECK_SVG . '´is used.', false,
                ['params' => CheckService::KEYS_CHECK_SVG__XML_SVG, 'paramtype' => 'XDFERGHT']],
            ['False is expected, if the unknown paramtype XDFERGHT is used and a allowed parameter for the method ´' . SvtService::ATTRIBUTE_PREPARE . '´is used.', false,
                ['params' => ['search' => 'Suche<tag>', 'replace' => 'Replace<Newtag>'], 'paramtype' => 'XDFERGHT']],
            ['False is expected, if the unknown paramtype XDFERGHT is used and a allowed parameter for the method ´' . SvtService::ATTRIBUTE_WORK_OVER . '´is used.', false,
                ['params' => ['search' => 'Suche<tag>', 'replace' => 'Replace<Newtag>'], 'paramtype' => 'XDFERGHT']],

            ['True is expected, if the know paramtype ' . SvtService::ATTRIBUTE_CHECK_SVG . ' is used and if the paramater is allowed for the selected method.',
                true,
                ['params' => CheckService::KEYS_CHECK_SVG__XML_SVG, 'paramtype' => SvtService::ATTRIBUTE_CHECK_SVG]],
            ['False is expected, if the know paramtype ' . SvtService::ATTRIBUTE_CHECK_SVG . ' is used and if the paramater is allowed for the selected method.',
                false,
                ['params' => null, 'paramtype' => SvtService::ATTRIBUTE_CHECK_SVG]],

            ['True is expected, if the know paramtype ' . SvtService::ATTRIBUTE_PREPARE . ' is used and if the paramater is allowed for the selected method.',
                true,
                ['params' => ['search' => 'Suche<tag>', 'replace' => 'Replace<Newtag>'], 'paramtype' => SvtService::ATTRIBUTE_PREPARE]],
            ['False is expected, if the know paramtype ' . SvtService::ATTRIBUTE_PREPARE . ' is used and if the paramater is allowed for the selected method.',
                false,
                ['params' => [], 'paramtype' => SvtService::ATTRIBUTE_PREPARE]],

            ['True is expected, if the know paramtype ' . SvtService::ATTRIBUTE_WORK_OVER . ' is used and if the paramater is allowed for the selected method.',
                true,
                ['params' => ['search' => 'Suche<tag>', 'replace' => 'Replace<Newtag>'], 'paramtype' => SvtService::ATTRIBUTE_WORK_OVER]],
            ['False is expected, if the know paramtype ' . SvtService::ATTRIBUTE_WORK_OVER . ' is used and if the paramater is allowed for the selected method.',
                false,
                ['params' => [], 'paramtype' => SvtService::ATTRIBUTE_WORK_OVER]],

        ];
    }

    /**
     * @dataProvider dataProviderParamValidateReturnExpectedBooleanIfVariationOfDetailedSpecifiedKeyIsAndIfSimpleArraysUsed
     * @test
     */
    public function paramValidateReturnExpectedBoleanIfTheSpecefiedParameterIsSetInSimpleWay($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider-part');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidate($params['params'], $params['paramtype']),
                $message
            );
        }
    }

    public function dataProviderReformateStreamReturnExpectedStringIfAValidParamArrayOrArrayOfParamArrayGiven()
    {
        return [
            ['1. The searchstring contains two regular parts and works case-sensitive. This shows, that the regular Search-Replace-Fuinction is used.',
                'TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.!',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX,
                        StringReplaceService::PARAM_SEARCH => 'Tä([^#]+)\](.+)Täch',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TÄchT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                ],
            ],
            ['2. The search string contains escpaed parts and the Limit-Function ist usable. This shows, that the regular Search-Replace-Fuinction is used.',
                'XXXX -#- XXXX -#- YYYY',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX_NO_CASES,
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;ch.üß&\(\)\[\]\{\}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 2,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                ],
            ],
            ['3. The search string contains escpaed parts and the Limit-Function ist usable. This shows, that the regular Search-Replace-Fuinction is used.',
                'XXXX -#- XXXX -#- YYYY',
                [
                    'param' => [
                        [
                            StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX_NO_CASES,
                            StringReplaceService::PARAM_SEARCH => 'TächT&auml;ch.üß&\(\)\[\]\{\}.!',
                            StringReplaceService::PARAM_REPLACE => 'XXXX',
                            StringReplaceService::PARAM_MAX => 1
                        ],
                        [
                            StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX_NO_CASES,
                            StringReplaceService::PARAM_SEARCH => 'TächT&auml;ch.üß&\(\)\[\]\{\}.!',
                            StringReplaceService::PARAM_REPLACE => 'XXXX',
                            StringReplaceService::PARAM_MAX => 1
                        ],
                        [
                            StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX_NO_CASES,
                            StringReplaceService::PARAM_SEARCH => 'TächT&auml;ch.üß&\(\)\[\]\{\}.!',
                            StringReplaceService::PARAM_REPLACE => 'YYYY',
                            StringReplaceService::PARAM_MAX => 1
                        ],
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderReformateStreamReturnExpectedStringIfAValidParamArrayOrArrayOfParamArrayGiven
     * @test
     */
    public function reformateStreamReturnExpectedStringIfAValidParamArrayOrArrayOfParamArrayGiven($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider-part');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->reformateStream(
                    $params['param'],
                    $params['svg']
                ),
                $message
            );
        }
    }

    /**
     * @test
     */
    public function helloWorldIsEverTrue()
    {
        $this->assertSame(true, true, 'empty-data at the End of the provider');
    }
}

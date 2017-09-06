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
 * Date: 03.06.2017
 * Time: 08:24
 */

namespace Porth\HornyShit\Service\Svt;

use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;
use Prophecy\Exception\Prophecy\MethodProphecyException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Porth\HornyShit\Service\Svt\StringReplaceService;

class StringReplaceServiceTest extends TestCase
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
     * @var StringReplaceService
     */
    protected $subject = NULL;

    public function setUp()
    {
        $this->subject = new StringReplaceService();
    }

    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function optionalHelloWorld()
    {
        $this->assertSame(true, true, 'Test of `Hello World`');
    }

    public function dataProviderParamValidateReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndALimitWillUsedAndProvoked()
    {
        return [
            ['10. The validation of the param is true, if it contains the needed string-parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                ' & ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the optional string-parmeter ´' . StringReplaceService::PARAM_CLEAN . '´ and the optional integer-parmeter ´'
                . StringReplaceService::PARAM_MAX . '´.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => '32',

                ],
            ],
            ['20. The validation of the param is true, if it contains the needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',

                ],
            ],
            ['30. The validation of the param is true, if it contains the needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the optional integer-parmeter  ´' . StringReplaceService::PARAM_MAX . '´.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_MAX => '32',
                ],
            ],
            ['31. The validation of the param is true, if it contains the needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the optional integer-parmeter  ´' . StringReplaceService::PARAM_MAX . '´.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_MAX => 32,
                ],
            ],
            ['40. The validation of the param is false, if it contains the needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the optional aparmeter ´' . StringReplaceService::PARAM_CLEAN . '´ and if the parameter ´' .
                StringReplaceService::PARAM_MAX . '´ is missing.',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                ],
            ],
            ['110. The validation of the param is false, if it contains the needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and if ´' . StringReplaceService::PARAM_REPLACE . '´ is empty. ',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => '',

                ],
            ],
            ['120. The validation of the param is false, if it contains the needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and if ´' . StringReplaceService::PARAM_REPLACE . '´ is null (not string). ',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => null,

                ],
            ],
            ['130. The validation of the param is false, if it contains the needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and if ´' . StringReplaceService::PARAM_REPLACE . '´ is an integer (not string). ',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 42,

                ],
            ],
            ['140. The validation of the param is false, if it contains the needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and if ´' . StringReplaceService::PARAM_REPLACE . '´ is an array (not string). ',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => ['hallo'],

                ],
            ],
            ['150. The validation of the param is false, if it contains the needed parameter ´' . StringReplaceService::PARAM_REPLACE . '´ ' .
                'and ´' . StringReplaceService::PARAM_SEARCH . '´ and if ´' . StringReplaceService::PARAM_SEARCH . '´ is empty. ',
                false,
                [
                    StringReplaceService::PARAM_REPLACE => 'This is some normal text.',
                    StringReplaceService::PARAM_SEARCH => '',

                ],
            ],
            ['160. The validation of the param is false, if it contains the needed parameter ´' . StringReplaceService::PARAM_REPLACE . '´ ' .
                'and ´' . StringReplaceService::PARAM_SEARCH . '´ and if ´' . StringReplaceService::PARAM_SEARCH . '´ is null (not string). ',
                false,
                [
                    StringReplaceService::PARAM_REPLACE => 'This is some normal text.',
                    StringReplaceService::PARAM_SEARCH => null,

                ],
            ],
            ['170. The validation of the param is false, if it contains the needed parameter ´' . StringReplaceService::PARAM_REPLACE . '´ ' .
                'and ´' . StringReplaceService::PARAM_SEARCH . '´ and if ´' . StringReplaceService::PARAM_SEARCH . '´ is an integer (not string). ',
                false,
                [
                    StringReplaceService::PARAM_REPLACE => 'This is some normal text.',
                    StringReplaceService::PARAM_SEARCH => 42,

                ],
            ],
            ['180. The validation of the param is false, if it contains the needed parameter ´' . StringReplaceService::PARAM_REPLACE . '´ ' .
                'and ´' . StringReplaceService::PARAM_SEARCH . '´ and if ´' . StringReplaceService::PARAM_SEARCH . '´ is an array (not string). ',
                false,
                [
                    StringReplaceService::PARAM_REPLACE => 'This is some normal text.',
                    StringReplaceService::PARAM_SEARCH => ['hallo'],

                ],
            ],
            ['210. The validation of the param is false, if it contains an an unallowed parameter addtional to ' .
                'the allowed needed string-parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                ' & ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the allowed optional integer-parmeter ´' . StringReplaceService::PARAM_CLEAN . '´ and the allowed optional string-parmeter ´'
                . StringReplaceService::PARAM_MAX . '´.',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => '32',
                    'notallwoed' => 'This is an additional parameter',

                ],
            ],
            ['220. The validation of the param is false, if it contains an an unallowed parameter addtional to ' .
                'the allowed needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´.',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    'notallwoed' => 'This is an additional parameter',
                ],
            ],
            ['230. The validation of the param is false, if it contains an an unallowed Parameter addtional to ' .
                'the allowed needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the allowed optional integer-parmeter  ´' . StringReplaceService::PARAM_MAX . '´.',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_MAX => '32',
                    'notallwoed' => 'This is an additional parameter',
                ],
            ],
            ['310. The validation of the param is false, if it contains ' .
                'the allowed needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the allowed optional integer-parmeter  ´' . StringReplaceService::PARAM_MAX . '´, ' .
                'which is not a integer.',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_MAX => 'This is an additional parameter',
                ],
            ],
            ['320. The validation of the param is false, if it contains ' .
                'the allowed needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the allowed optional integer-parmeter  ´' . StringReplaceService::PARAM_MAX . '´, ' .
                'which is a float (not a integer).',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_MAX => '32.123',
                ],
            ],
            ['321. The validation of the param is false, if it contains ' .
                'the allowed needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the allowed optional integer-parmeter  ´' . StringReplaceService::PARAM_MAX . '´, ' .
                'which is a float (not a integer).',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_MAX => '32,123',
                ],
            ],
            ['330. The validation of the param is false, if it contains ' .
                'the allowed needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the allowed optional integer-parmeter  ´' . StringReplaceService::PARAM_MAX . '´, ' .
                'which is not a integer.',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_MAX => 32.123,
                ],
            ],
            ['340. The validation of the param is true, if it contains ' .
                'the allowed needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the allowed optional integer-parmeter  ´' . StringReplaceService::PARAM_MAX . '´, ' .
                'which is a float , which can savely converted to an integer .',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_MAX => 32.0,
                ],
            ],
            ['350. The validation of the param is false, if it contains ' .
                'the allowed needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the allowed optional integer-parmeter  ´' . StringReplaceService::PARAM_MAX . '´, ' .
                'which is lower than zero.',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_MAX => -32,
                ],
            ],
            ['351. The validation of the param is false, if it contains ' .
                'the allowed needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the allowed optional integer-parmeter  ´' . StringReplaceService::PARAM_MAX . '´, ' .
                'which is lower than zero (string).',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_MAX => '-32',
                ],
            ],
            ['360. The validation of the param is false, if it contains ' .
                'the allowed needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the allowed optional integer-parmeter  ´' . StringReplaceService::PARAM_MAX . '´, ' .
                'which is zero.',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_MAX => 0,
                ],
            ],
            ['361. The validation of the param is false, if it contains ' .
                'the allowed needed parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                'and ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                'the allowed optional integer-parmeter  ´' . StringReplaceService::PARAM_MAX . '´, ' .
                'which is zero (string).',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_MAX => '0',
                ],
            ],
            ['410. The validation of the param is false, if ' . 'the optional string-parmeter ´' . StringReplaceService::PARAM_CLEAN .
                'is an integer and if it contains the allowed needed string-parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                ' & ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                '´ and the allowed needed integer-parmeter ´'
                . StringReplaceService::PARAM_MAX . '´.',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 32,
                    StringReplaceService::PARAM_MAX => '32',

                ],
            ],
            ['420. The validation of the param is true, if ' . 'the optional string-parmeter ´' . StringReplaceService::PARAM_CLEAN .
                'is a string with an integer number and if it contains the allowed needed string-parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                ' & ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                '´ and the allowed needed integer-parmeter ´'
                . StringReplaceService::PARAM_MAX . '´.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => '32',
                    StringReplaceService::PARAM_MAX => '32',

                ],
            ],
            ['430. The validation of the param is true, if ' . 'the optional string-parmeter ´' . StringReplaceService::PARAM_CLEAN .
                'is the value null and if it contains the allowed needed string-parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                ' & ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                '´ and the allowed needed integer-parmeter ´'
                . StringReplaceService::PARAM_MAX . '´.',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => null,
                    StringReplaceService::PARAM_MAX => '32',

                ],
            ],
            ['440. The validation of the param is false, if ' . 'the optional string-parmeter ´' . StringReplaceService::PARAM_CLEAN .
                'is an array qwith an string and if it contains the allowed needed string-parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                ' & ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                '´ and the allowed needed integer-parmeter ´'
                . StringReplaceService::PARAM_MAX . '´.',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => ['this is the claenString in an array'],
                    StringReplaceService::PARAM_MAX => '32',

                ],
            ],
            ['450. The validation of the param is true, if ' . 'the optional string-parmeter ´' . StringReplaceService::PARAM_CLEAN .
                'is an empty string and if it contains the allowed needed string-parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                ' & ´' . StringReplaceService::PARAM_REPLACE . '´ and ' .
                '´ and the allowed needed integer-parmeter ´'
                . StringReplaceService::PARAM_MAX . '´.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => '',
                    StringReplaceService::PARAM_MAX => '32',

                ],
            ],
            ['500. The testarray contains an allowed parameter-set and has no method defined (default case).',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => '',
                    StringReplaceService::PARAM_MAX => '32',
                ],
            ],
            ['510. The testarray contains an allowed parameter-set and the method ' .
                StringReplaceService::PARAM_METHOD_REGEX . ' is defined.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => '',
                    StringReplaceService::PARAM_MAX => '32',
                    StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX,
                ],
            ],
            ['520. The testarray contains an allowed parameter-set and the method ' .
                StringReplaceService::PARAM_METHOD_REGEX_NO_CASES . ' is defined.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => '',
                    StringReplaceService::PARAM_MAX => '32',
                    StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX_NO_CASES,
                ],
            ],
            ['530. The testarray contains an allowed parameter-set and the method ' .
                StringReplaceService::PARAM_METHOD_NORMAL . ' is defined.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => '',
                    StringReplaceService::PARAM_MAX => '32',
                    StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL,
                ],
            ],
            ['540. The testarray contains an allowed parameter-set and the method ' .
                StringReplaceService::PARAM_METHOD_NORMAL_NO_CASE . ' is defined.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => '',
                    StringReplaceService::PARAM_MAX => '32',
                    StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL_NO_CASE,
                ],
            ],
            ['550. The testarray contains an allowed parameter-set and the method ' .
                StringReplaceService::PARAM_METHOD_HTML_REGEX_NO_CASE . ' is defined.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => '',
                    StringReplaceService::PARAM_MAX => '32',
                    StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_REGEX_NO_CASE,
                ],
            ],
            ['560. The testarray contains an allowed parameter-set and the method ' .
                StringReplaceService::PARAM_METHOD_HTML_NORMAL_NO_CASE . ' is defined.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => '',
                    StringReplaceService::PARAM_MAX => '32',
                    StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_NORMAL_NO_CASE,
                ],
            ],
            ['570. The testarray contains an allowed parameter-set and the method ' .
                StringReplaceService::PARAM_METHOD_HTML_REGEX . ' is defined.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => '',
                    StringReplaceService::PARAM_MAX => '32',
                    StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_REGEX,
                ],
            ],
            ['580. The testarray contains an allowed parameter-set and the method ' .
                StringReplaceService::PARAM_METHOD_HTML_NORMAL . ' is defined.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => '',
                    StringReplaceService::PARAM_MAX => '32',
                    StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_NORMAL,
                ],
            ],
            ['590. The testarray contains an allowed parameter-set and the method ' .
                StringReplaceService::PARAM_METHOD_HTML_NORMAL . ' is defined.',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => '',
                    StringReplaceService::PARAM_MAX => '32',
                    StringReplaceService::PARAM_METHOD => 'wulle_Bulle_Mumpitz',
                ],
            ],
        ];
    }


    /**
     * @dataProvider dataProviderParamValidateReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndALimitWillUsedAndProvoked
     * @test
     */
    public function paramValidateReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndALimitWillUsedAndProvoked($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider-part');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidate($param),
                $message
            );
        }
    }


    public function dataProviderParamValidateSwitchReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndALimitWillUsedAndProvoked()
    {
        return [
            ['10. The validation of the param is true, if it contains the allowed aND needed string-parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                ' & ´' . StringReplaceService::PARAM_REPLACE . '´.',
                true,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                ],
            ],
            ['11. The validation of the param is true, if it contains arrray, which contains the allowed and needed string-parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                ' & ´' . StringReplaceService::PARAM_REPLACE . '´.',
                true,
                [
                    [
                        StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                        StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    ]
                ],
            ],
            ['12. The validation of the param is true, if it contains arrray, which contains the allowed and needed string-parameter ´' . StringReplaceService::PARAM_SEARCH . '´ ' .
                ' & ´' . StringReplaceService::PARAM_REPLACE . '´.',
                true,
                [
                    [
                        StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                        StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    ],
                    [
                        StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                        StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    ]
                ],
            ],
            ['20. The validation of the param is false, if it contains the needed string-parameter ´' .
                StringReplaceService::PARAM_SEARCH . '´  is missing and if the Parameters are not in a array of an array.' .
                'It is an example for fail of StringReplaceService->paramValidate().',
                false,
                [
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                ],
            ],
            ['21. The validation of the param is false, if it contains arrray, in which the needed string-parameter ´' .
                StringReplaceService::PARAM_SEARCH . '´  is missing.' .
                'It is an example for fail of StringReplaceService->paramValidate().',
                false,
                [
                    [
                        StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    ]
                ],
            ],
            ['22. The validation of the param is true, if it contains arrrays, where in one array the needed string-parameter ´' .
                StringReplaceService::PARAM_SEARCH . '´  are missing.' .
                'It is an example for failing of StringReplaceService->paramValidate().',
                false,
                [
                    [
                        StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                        StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    ],
                    [
                        StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    ]
                ],
            ],
            ['23. The validation of the param is true, if it contains arrrays, where in one array the needed string-parameter ´' .
                StringReplaceService::PARAM_SEARCH . '´  are missing.' .
                'It is an example for failing of StringReplaceService->paramValidate().',
                false,
                [
                    [
                        StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    ],
                    [
                        StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                        StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    ]
                ],
            ],
            ['30. The validation of the param is false, if it contains the needed string-parameter ´' .
                StringReplaceService::PARAM_REPLACE . '´ is missing and if the Parameters are not in a array of an array' .
                'It is an example for fail of StringReplaceService->paramValidate().',
                false,
                [
                    StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
                ],
            ],
            ['31. The validation of the param is false, if it contains arrray, in which the needed string-parameter ´' .
                StringReplaceService::PARAM_REPLACE . '´  is missing.' .
                'It is an example for fail of StringReplaceService->paramValidate().',
                false,
                [
                    [
                        StringReplaceService::PARAM_SEARCH => 'This is some other text.',
                    ]
                ],
            ],
            ['32. The validation of the param is true, if it contains arrrays, where in one array the needed string-parameter ´' .
                StringReplaceService::PARAM_REPLACE . '´  are missing.' .
                'It is an example for failing of StringReplaceService->paramValidate().',
                false,
                [
                    [
                        StringReplaceService::PARAM_REPLACE => 'This is some normal text.',
                        StringReplaceService::PARAM_SEARCH => 'This is some other text.',
                    ],
                    [
                        StringReplaceService::PARAM_SEARCH => 'This is some other text.',
                    ]
                ],
            ],

            ['33. The validation of the param is true, if it contains arrrays, where in one array the needed string-parameter ´' .
                StringReplaceService::PARAM_REPLACE . '´  are missing.' .
                'It is an example for failing of StringReplaceService->paramValidate().',
                false,
                [
                    [

                        StringReplaceService::PARAM_SEARCH => 'This is some other text.',
                    ],
                    [
                        StringReplaceService::PARAM_REPLACE => 'This is some normal text.',
                        StringReplaceService::PARAM_SEARCH => 'This is some other text.',
                    ]
                ],
            ],

        ];
    }

    /**
     * @dataProvider dataProviderParamValidateSwitchReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndALimitWillUsedAndProvoked
     * @test
     */
    public function paramValidateSwitchReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndALimitWillUsedAndProvoked($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider-part');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateSwitch($param),
                $message
            );
        }
    }


    public function dataProviderreformateParamSearchReplaceHtmlEntitiesReturnExpectedArrayAndArrayWhichIsToReformate()
    {

        return [
            ['The items ' . StringReplaceService::PARAM_SEARCH . ' . ' . StringReplaceService::PARAM_CLEAN . ' and ' . StringReplaceService::PARAM_REPLACE . ' have no encoded special chars. ' .
                'They won´t be converted bei the php-function htmlspecialchars_decode. Other items like `' . StringReplaceService::PARAM_MAX . '` will not be touched.',
                [
                    StringReplaceService::PARAM_SEARCH => 'This is söme normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
            ],
            ['The item ' . StringReplaceService::PARAM_SEARCH . ' will be decoded by ´htmlspecialchars_decode´.' .
                'The items ' . StringReplaceService::PARAM_CLEAN . ' and ' . StringReplaceService::PARAM_REPLACE . ' have no encoded special chars. ' .
                'They won´t be converted bei the php-function ´htmlspecialchars_decode`. Other items like `' .
                StringReplaceService::PARAM_MAX . '` will not be touched.',
                [
                    StringReplaceService::PARAM_SEARCH => 'This is söme <b>bold</b> text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me &lt;b&gt;bold&lt;/b&gt; text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
            ],
            ['The item ' . StringReplaceService::PARAM_REPLACE . ' will be decoded by ´htmlspecialchars_decode´.' .
                'The items ' . StringReplaceService::PARAM_CLEAN . ' and ' . StringReplaceService::PARAM_SEARCH . ' have no encoded special chars. ' .
                'They won´t be converted bei the php-function ´htmlspecialchars_decode`. Other items like `' .
                StringReplaceService::PARAM_MAX . '` will not be touched.',
                [
                    StringReplaceService::PARAM_SEARCH => 'This is söme text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some <b>bold</b> other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some &lt;b&gt;bold&lt;/b&gt; other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
            ],
            ['The item ' . StringReplaceService::PARAM_CLEAN . ' will be decoded by ´htmlspecialchars_decode´.' .
                'The items ' . StringReplaceService::PARAM_REPLACE . ' and ' . StringReplaceService::PARAM_SEARCH . ' have no encoded special chars. ' .
                'They won´t be converted bei the php-function ´htmlspecialchars_decode`. Other items like `' .
                StringReplaceService::PARAM_MAX . '` will not be touched.',
                [
                    StringReplaceService::PARAM_SEARCH => 'This is söme text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some <b>bold</b> restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some &lt;b&gt;bold&lt;/b&gt; restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
            ],
            ['All items ' . StringReplaceService::PARAM_SEARCH . ' . ' . StringReplaceService::PARAM_CLEAN . ' and ' .
                StringReplaceService::PARAM_REPLACE . ' will be decoded by ´htmlspecialchars_decode´. ' .
                'They won´t be converted bei the php-function htmlspecialchars_decode. Other items like `' . StringReplaceService::PARAM_MAX . '` will not be touched.',
                [
                    StringReplaceService::PARAM_SEARCH => 'This is söme <b>bold</b> normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some <b>bold</b> other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some <b>bold</b> restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me &lt;b&gt;bold&lt;/b&gt; normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some &lt;b&gt;bold&lt;/b&gt; other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some &lt;b&gt;bold&lt;/b&gt; restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
            ],
            ['The items ' . StringReplaceService::PARAM_SEARCH . ' and ' .
                StringReplaceService::PARAM_REPLACE . ' will be decoded by ´htmlspecialchars_decode´. ' .
                'The item ' . StringReplaceService::PARAM_CLEAN . 'is missing. ' .
                'They won´t be converted bei the php-function htmlspecialchars_decode. Other items like `' . StringReplaceService::PARAM_MAX . '` will not be touched.',
                [
                    StringReplaceService::PARAM_SEARCH => 'This is söme <b>bold</b> normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some <b>bold</b> other text.',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me &lt;b&gt;bold&lt;/b&gt; normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some &lt;b&gt;bold&lt;/b&gt; other text.',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderreformateParamSearchReplaceHtmlEntitiesReturnExpectedArrayAndArrayWhichIsToReformate
     * @test
     */
    public function reformateParamSearchReplaceHtmlEntitiesExspectedArrayIfATestArrayIsSet($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $result = $this->subject->reformateParamSearchReplaceHtmlEntities($params);
            $diffArray = array_diff_assoc($expect, $result);
            $this->assertSame(
                0,
                count($diffArray),
                'Test difference of exspected and caclulated array: must be 0. ' . "\n" . $message
            );
            $this->assertSame(
                count($expect),
                count($params),
                'Test length of result-array and expected array: must be equal. ' . "\n" . $message
            );
        }
    }


    public function dataProviderReformateParamSearchReplaceHtmlSpecialReturnExpectedArrayAndArrayWhichIsToReformate()
    {

        return [
            ['1 The items ' . StringReplaceService::PARAM_SEARCH . ' . ' . StringReplaceService::PARAM_CLEAN . ' and ' . StringReplaceService::PARAM_REPLACE . ' have no encoded special chars. ' .
                'They won´t be converted bei the php-function htmlspecialchars_decode. Other items like `' . StringReplaceService::PARAM_MAX . '` will not be touched.',
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
            ],
            ['2 The item ' . StringReplaceService::PARAM_SEARCH . ' will be decoded by ´htmlspecialchars_decode´.' .
                'The items ' . StringReplaceService::PARAM_CLEAN . ' and ' . StringReplaceService::PARAM_REPLACE . ' have no encoded special chars. ' .
                'They won´t be converted bei the php-function ´htmlspecialchars_decode`. Other items like `' .
                StringReplaceService::PARAM_MAX . '` will not be touched.',
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me <b>bold</b> text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me &lt;b&gt;bold&lt;/b&gt; text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
            ],
            ['3 The item ' . StringReplaceService::PARAM_REPLACE . ' will be decoded by ´htmlspecialchars_decode´.' .
                'The items ' . StringReplaceService::PARAM_CLEAN . ' and ' . StringReplaceService::PARAM_SEARCH . ' have no encoded special chars. ' .
                'They won´t be converted bei the php-function ´htmlspecialchars_decode`. Other items like `' .
                StringReplaceService::PARAM_MAX . '` will not be touched.',
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some <b>bold</b> other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some &lt;b&gt;bold&lt;/b&gt; other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
            ],
            ['4 The item ' . StringReplaceService::PARAM_CLEAN . ' will be decoded by ´htmlspecialchars_decode´.' .
                'The items ' . StringReplaceService::PARAM_REPLACE . ' and ' . StringReplaceService::PARAM_SEARCH . ' have no encoded special chars. ' .
                'They won´t be converted bei the php-function ´htmlspecialchars_decode`. Other items like `' .
                StringReplaceService::PARAM_MAX . '` will not be touched.',
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some <b>bold</b> restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some &lt;b&gt;bold&lt;/b&gt; restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
            ],
            ['5 All items ' . StringReplaceService::PARAM_SEARCH . ' . ' . StringReplaceService::PARAM_CLEAN . ' and ' .
                StringReplaceService::PARAM_REPLACE . ' will be decoded by ´htmlspecialchars_decode´. ' .
                'They won´t be converted bei the php-function htmlspecialchars_decode. Other items like `' . StringReplaceService::PARAM_MAX . '` will not be touched.',
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me <b>bold</b> normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some <b>bold</b> other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some <b>bold</b> restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me &lt;b&gt;bold&lt;/b&gt; normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some &lt;b&gt;bold&lt;/b&gt; other text.',
                    StringReplaceService::PARAM_CLEAN => 'This is some &lt;b&gt;bold&lt;/b&gt; restful text',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
            ],
            ['6 The items ' . StringReplaceService::PARAM_SEARCH . ' and ' .
                StringReplaceService::PARAM_REPLACE . ' will be decoded by ´htmlspecialchars_decode´. ' .
                'The item ' . StringReplaceService::PARAM_CLEAN . 'is missing. ' .
                'They won´t be converted bei the php-function htmlspecialchars_decode. Other items like `' . StringReplaceService::PARAM_MAX . '` will not be touched.',
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me <b>bold</b> normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some <b>bold</b> other text.',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
                [
                    StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me &lt;b&gt;bold&lt;/b&gt; normal text.',
                    StringReplaceService::PARAM_REPLACE => 'This is some &lt;b&gt;bold&lt;/b&gt; other text.',
                    StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',

                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderReformateParamSearchReplaceHtmlSpecialReturnExpectedArrayAndArrayWhichIsToReformate
     * @test
     */
    public function reformateParamSearchReplaceHtmlSpecialExspectedArrayIfATestArrayIsSet($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $result = $this->subject->reformateParamSearchReplaceHtmlSpecial($params);
            $diffArray = array_diff_assoc($expect, $result);
            $this->assertSame(
                0,
                count($diffArray),
                'Test difference of exspected and caclulated array: must be 0. ' . "\n" . $message
            );
            $this->assertSame(
                count($expect),
                count($params),
                'Test length of result-array and expected array: must be equal. ' . "\n" . $message
            );
        }
    }

    public function dataProviderReformateParamSearchForRegularReturnExpectedArrayGivenArrayWhichIsToReformateAndParameter()
    {
        $defaultArray = [
            StringReplaceService::PARAM_SEARCH => 'This is some normal text.',
            StringReplaceService::PARAM_REPLACE => 'This is some other text.',
        ];
        $testArray = [
            StringReplaceService::PARAM_SEARCH => StringReplaceService::UTF8_REGEX_PREG_LIMITER .
                'This is some normal text.' . StringReplaceService::UTF8_REGEX_PREG_LIMITER . 'u',
            StringReplaceService::PARAM_REPLACE => 'This is some other text.',
        ];
        $testArrayNoCase = [
            StringReplaceService::PARAM_SEARCH => StringReplaceService::UTF8_REGEX_PREG_LIMITER.
                'This is some normal text.'.StringReplaceService::UTF8_REGEX_PREG_LIMITER.'iu',
            StringReplaceService::PARAM_REPLACE => 'This is some other text.',
        ];

        return [
            ['a. The item ' . StringReplaceService::PARAM_SEARCH . ' of the array of the search_replace-param will be ' .
                'included by the Limiter' . StringReplaceService::UTF8_REGEX_PREG_LIMITER . '. ' .
                'A unicode-modificator `' . StringReplaceService::UTF8_REGEX_PREG_MODIFICATOR_UNICODE .
                '` will be added will be added too. ',
                $testArray,
                [
                    'param' => $defaultArray,
                    'limiter' => StringReplaceService::UTF8_REGEX_PREG_LIMITER,
                    'modificator' => StringReplaceService::UTF8_REGEX_PREG_MODIFICATOR_UNICODE
                ],
            ],
            ['b. The item ' . StringReplaceService::PARAM_SEARCH . ' of the array of the search_replace-param will be ' .
                'included by the Limiter' . StringReplaceService::UTF8_REGEX_PREG_LIMITER . '. ' .
                'A case-insensitive unicode modificator `' . StringReplaceService::UTF8_REGEX_PREG_MODIFICATOR_UNICODE_NO_CASE .
                '` will be added will be added too. ',
                $testArrayNoCase,
                [
                    'param' => $defaultArray,
                    'limiter' => StringReplaceService::UTF8_REGEX_PREG_LIMITER,
                    'modificator' => StringReplaceService::UTF8_REGEX_PREG_MODIFICATOR_UNICODE_NO_CASE
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderReformateParamSearchForRegularReturnExpectedArrayGivenArrayWhichIsToReformateAndParameter
     * @test
     */
    public function reformateParamSearchForRegularExspectedArrayIfParametersAreSet($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $result = $this->subject->reformateParamSearchForRegular($params['param'], $params['limiter'], $params['modificator']);
            $diffArray = array_diff_assoc($expect, $result);
            $this->assertSame(
                0,
                count($diffArray),
                'Test difference of exspected and caclulated array: must be 0. ' . "\n" . $message
            );
            $this->assertSame(
                count($expect),
                count($params['param']),
                'Test length of result-array and expected array: must be equal. ' . "\n" . $message
            );
        }
    }


    public function dataProviderEscapeParamSearchForRegularReturnExpectedArrayGivenArrayWithUnescapedChars()
    {
        $escapeFree = "this is an defaulttext with some UTF-8-chars like Üöäßµ!";
        $unescapedLine = 'Begin list of unescaped chars: ' .
            '\\' . "^" . "." . "$" . "|" . "(" . ")" . "[" . "]" . "*" . "+" . "?" . "{" . "}" . "," .
            StringReplaceService::UTF8_REGEX_PREG_LIMITER.':End of list';
        $escapedList = 'Begin list of unescaped chars: ' .
            '\\\\' . "\\^" . "\\." . "\\$" . "\\|" . "\\(" . "\\)" . "\\[" . "\\]" . "\\*" . "\\+" . "\\?" . "\\{" . "\\}" . "\\," .
            '\\'.StringReplaceService::UTF8_REGEX_PREG_LIMITER.':End of list';

        return [
            ['Some regular Chars of the param-item ' . StringReplaceService::PARAM_SEARCH . ' will be escaped. ',
                [
                    StringReplaceService::PARAM_SEARCH => $escapedList, //difference
                    StringReplaceService::PARAM_REPLACE => $unescapedLine,
                    StringReplaceService::PARAM_MAX => $unescapedLine,
                    StringReplaceService::PARAM_CLEAN => $unescapedLine
                ],
                [
                    StringReplaceService::PARAM_SEARCH => $unescapedLine,
                    StringReplaceService::PARAM_REPLACE => $unescapedLine,
                    StringReplaceService::PARAM_MAX => $unescapedLine,
                    StringReplaceService::PARAM_CLEAN => $unescapedLine
                ],
            ],
            ['No special Chars in the the param-item ' . StringReplaceService::PARAM_SEARCH . ' located. Nothing will be escaped. ',
                [
                    StringReplaceService::PARAM_SEARCH => $escapeFree, //equal
                    StringReplaceService::PARAM_REPLACE => $escapeFree,
                    StringReplaceService::PARAM_MAX => $escapeFree,
                    StringReplaceService::PARAM_CLEAN => $escapeFree
                ],
                [
                    StringReplaceService::PARAM_SEARCH => $escapeFree,
                    StringReplaceService::PARAM_REPLACE => $escapeFree,
                    StringReplaceService::PARAM_MAX => $escapeFree,
                    StringReplaceService::PARAM_CLEAN => $escapeFree
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderEscapeParamSearchForRegularReturnExpectedArrayGivenArrayWithUnescapedChars
     * @test
     */
    public function EscapeParamSearchForRegularExspectedArrayIfParamArrayIsGiven($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $result = $this->subject->escapeParamSearchForRegular($params);
            $diffArray = array_diff_assoc($expect, $result);
            $this->assertSame(
                0,
                count($diffArray),
                'Test difference of exspected and caclulated array: must be 0. ' . "\n" . $message
            );
            $this->assertSame(
                count($expect),
                count($params),
                'Test length of result-array and expected array: must be equal. ' . "\n" . $message
            );
        }
    }


    public function dataProviderEscapeParamReplaceForRegularReturnVarEscapedReplaceParameterIfTheParameterContaineAVariable()
    {

        return [
            ['1. the Replace-Parameter contains only two $ and nothing is changed.',
                [
                    StringReplaceService::PARAM_SEARCH => '$ $1 Tä([^#]+)\](.+)Täch',
                    StringReplaceService::PARAM_REPLACE => 'TÄ$ --#-- TÄCH$',
                ],
                [
                    StringReplaceService::PARAM_SEARCH => '$ $1 Tä([^#]+)\](.+)Täch',
                    StringReplaceService::PARAM_REPLACE => 'TÄ$ --#-- TÄCH$',
                ],
            ],
            ['2. the Replace-Parameter contains only two $ with numbers and the $ are escaped in the Replace-parameter',
                [
                    StringReplaceService::PARAM_SEARCH => '$ $1 Tä([^#]+)\](.+)Täch',
                    StringReplaceService::PARAM_REPLACE => '$TÄ\$1 --#-- TÄCH\$1234',
                ],
                [
                    StringReplaceService::PARAM_SEARCH => '$ $1 Tä([^#]+)\](.+)Täch',
                    StringReplaceService::PARAM_REPLACE => '$TÄ$1 --#-- TÄCH$1234',
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderEscapeParamReplaceForRegularReturnVarEscapedReplaceParameterIfTheParameterContaineAVariable
     * @test
     */
    public function escapeParamReplaceForRegularReturnVarEscapedReplaceParameterIfTheParameterContaineAVariable($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider-part');
        } else {
            $result = $this->subject->escapeParamReplaceForRegular($params);
            $diffArray = array_diff_assoc($expect, $result);
            $this->assertSame(
                0,
                count($diffArray),
                'Test difference of exspected and caclulated array: must be 0. ' . "\n" . $message
            );
            $this->assertSame(
                count($expect),
                count($params),
                'Test length of result-array and expected array: must be equal. ' . "\n" . $message
            );
        }
    }

    /**
     * @return array
     */
    public function dataProviderReformateCallbackReturnExpectedStreamGivenStreamParameterAndReplaceNormalFunction()
    {
        $functionName = StringReplaceService::FUNC_NORM_REPLACE;

        return [
            ['The subject string is empty',
                '',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => '',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string once and all will be replaced.',
                'XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string twice and all will be replaced.',
                'XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and all will be replaced.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderReformateCallbackReturnExpectedStreamGivenStreamParameterAndReplaceCaseInsensiveeFunction()
    {
        $functionName = StringReplaceService::FUNC_NORM_REPLACE_NO_CASE;

        return [
            ['The subject string is empty',
                '',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => '',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string once and all will be replaced.',
                'XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string twice and all will be replaced.',
                'XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and all will be replaced.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['Letter Case differ in search and original. The search string contains the search-string once and all will be replaced.',
                'XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'täCht&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['Letter Case differ in search and original. The search string contains the search-string twice and all will be replaced.',
                'XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'täCht&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['Letter Case differ in search and original. The search string contains the search-string three times and all will be replaced.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'täCht&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
        ];
    }


    public function dataProviderReformateCallbackReturnExpectedStreamGivenStreamParameterAndReplacefunctionName()
    {
        return array_merge(
            self::dataProviderReformateCallbackReturnExpectedStreamGivenStreamParameterAndReplaceNormalFunction(),
            self::dataProviderReformateCallbackReturnExpectedStreamGivenStreamParameterAndReplaceCaseInsensiveeFunction()
        );
    }


    /**
     * @dataProvider dataProviderReformateCallbackReturnExpectedStreamGivenStreamParameterAndReplacefunctionName
     * @test
     */
    public function reformateCallbackExspectedStreamGivenOriginalStreamAndParameter($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $result = $this->subject->reformateCallback($params['param'], $params['svg'], $params['function']);
            $this->assertSame(
                $expect,
                $result,
                $message
            );
        }
    }


    /**
     * @return array
     */
    public function dataProviderReformateCallbackNormalLimitReturnExpectedStreamGivenStreamParameterAndReplacePregFunctionAndLimit()
    {
        $functionName = StringReplaceService::FUNC_REGEX_REPLACE;

        return [
            ['The subject string is empty',
                '',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => '',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string once and all will be replaced.',
                'XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string twice and all will be replaced.',
                'XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and all will be replaced.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and all will be replaced, because the limit is four.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 4,
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and all will be replaced, because the limit is four. The Clean-parameter doesn´t matter.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 4,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and all will be replaced, because the limit is three.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 3,
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and all will be replaced, because the limit is three. The clean-parameter doesn`t matter',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 3,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and the first two will be replaced, because the limit is two and No clean is defined.',
                'XXXX -#- XXXX -#- TächT&auml;chÜ&()[]{}.!',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 2,
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and the first two will be replaced, because the limit is two. The last will be change to the definition of `clean´.',
                'XXXX -#- XXXX -#- YYYY',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 2,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and the first will be replaced, because the limit is one.',
                'XXXX -#- TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 1,
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and the first will be replaced, because the limit is one. The last two items will be change to the definition of `clean´.',
                'XXXX -#- YYYY -#- YYYY',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜ&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 1,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.! -#- TächT&auml;chÜ&()[]{}.!',
                    'function' => $functionName
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderReformateCallbackNormalLimitReturnExpectedStreamGivenStreamParameterAndReplacePregFunctionAndLimit
     * @test
     */
    public function reformateCallbackNormalLimitExspectedStreamGivenOriginalStreamAndParameterAndEscapeOfCharsInSearchParamAndSetCaseSensitive($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $result = $this->subject->reformateCallbackNormalLimit($params['param'],
                $params['svg'],
                StringReplaceService::UTF8_REGEX_PREG_MODIFICATOR_UNICODE);
            $this->assertSame(
                $expect,
                $result,
                $message
            );
        }
    }


    /**
     * @return array
     */
    public function dataProviderReformateCallbackNormalLimitReturnExpectedStreamGivenStreamParameterAndReplaceCaseInsensiveeFunctionAndLimit()
    {
        $functionName = StringReplaceService::FUNC_REGEX_REPLACE;

        return [
            ['The search string contains the search-string three times and all will be replaced, because the limit is four.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 4,
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and all will be replaced, because the limit is four. The Clean-parameter doesn´t matter.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 4,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and all will be replaced, because the limit is three.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 3,
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and all will be replaced, because the limit is three. The clean-parameter doesn`t matter',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 3,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and the first two will be replaced, because the limit is two and No clean is defined.',
                'XXXX -#- XXXX -#- TächT&auml;chÜüß&()[]{}.!',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 2,
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and the first two will be replaced, because the limit is two. The last will be change to the definition of `clean´.',
                'XXXX -#- XXXX -#- YYYY',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 2,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and the first will be replaced, because the limit is one.',
                'XXXX -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 1,
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['The search string contains the search-string three times and the first will be replaced, because the limit is one. The last two items will be change to the definition of `clean´.',
                'XXXX -#- YYYY -#- YYYY',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'TächT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 1,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['Letter Case differ in search and original. The search string contains the search-string three times and all will be replaced, because the limit is four.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'täChT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 4,
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['Letter Case differ in search and original. The search string contains the search-string three times and all will be replaced, because the limit is four. The Clean-parameter doesn´t matter.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'täChT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 4,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['Letter Case differ in search and original. The search string contains the search-string three times and all will be replaced, because the limit is three.',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'täChT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 3,
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['Letter Case differ in search and original. The search string contains the search-string three times and all will be replaced, because the limit is three. The clean-parameter doesn`t matter',
                'XXXX -#- XXXX -#- XXXX',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'täChT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 3,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['Letter Case differ in search and original. The search string contains the search-string three times and the first two will be replaced, because the limit is two and No clean is defined.',
                'XXXX -#- XXXX -#- TächT&auml;chÜüß&()[]{}.!',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'täChT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 2,
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['Letter Case differ in search and original. The search string contains the search-string three times and the first two will be replaced, because the limit is two. The last will be change to the definition of `clean´.',
                'XXXX -#- XXXX -#- YYYY',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'täChT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 2,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['Letter Case differ in search and original. The search string contains the search-string three times and the first will be replaced, because the limit is one.',
                'XXXX -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'täChT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 1,
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['Letter Case differ in search and original. The search string contains the search-string three times and the first will be replaced, because the limit is one. The last two items will be change to the definition of `clean´.',
                'XXXX -#- YYYY -#- YYYY',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'täChT&auml;chÜüß&()[]{}.!',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 1,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderReformateCallbackNormalLimitReturnExpectedStreamGivenStreamParameterAndReplaceCaseInsensiveeFunctionAndLimit
     * @test
     */
    public function reformateCallbackNormalLimitExspectedStreamGivenOriginalStreamAndParameterAndEscapeOfCharsInSearchParamAndSetNoCaseSensitive($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $result = $this->subject->reformateCallbackNormalLimit($params['param'],
                $params['svg'],
                StringReplaceService::UTF8_REGEX_PREG_MODIFICATOR_UNICODE_NO_CASE);
            $this->assertSame(
                $expect,
                $result,
                $message
            );
        }
    }


    public function dataProviderReformateRegexReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndNoLimitWillUsed()
    {
        $functionName = StringReplaceService::FUNC_REGEX_REPLACE;

        return [
            ['1. The search string contains two reagular searchgroup and a normal part, which will be replaced casesensitive. (montecarlo-Case)',
                'TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.!',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\](.+)Täch(.+)/u',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['2. The search string contains two reagular searchgroup and a normal part, which will be replaced casesensitive. (montecarlo-Case with undetected beginning an Ending)',
                'asgdasasd YYYTÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.!XXX dflgkösddgfklsögfsg',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/YYYTä([^#]+)\](.+)Täch(.+)XXX/u',
                        StringReplaceService::PARAM_REPLACE => 'YYYTÄ$1 --#-- TÄCH$3XXX',
                    ],
                    'svg' => 'asgdasasd YYYTächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!XXX dflgkösddgfklsögfsg',
                    'function' => $functionName
                ],
            ],
            ['3. The search string contains two reagular searchgroup and a normal part, which will be replaced casesensitive.',
                'TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.!',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\](.+)Täch(.+)/u',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['4. The search string contains two reagular searchgroup and a normal part, which will be replaced not casesensitive.',
                'TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.!',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\](.+)Täch(.+)/iu',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['5. The search string contains two reagular searchgroup and a normal part, which will be replaced by nothing in not casesensitive. The beginning will saveed, because it is not detected.',
                'abc',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\](.+)Täch(.+)/u',
                        StringReplaceService::PARAM_REPLACE => '',
                    ],
                    'svg' => 'abcTächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['6. The search string contains nothing in caseinsesitive Form ans replace a char. Each char-intersect will befill with the replace-char inclusive the start and the end.',
                'xaxbxcxTxäxcxhxTx&xaxuxmxlx;xcxhxÜxüxßx&x(x)x[x]x{x}x.x!x x-x#x-x xTxäxcxhxTx&xaxuxmxlx;xcxhxÜxüxßx&x(x)x[x]x{x}x.x!x x-x#x-x xTxäxcxhxTx&xaxuxmxlx;xcxhxÜxüxßx&x(x)x[x]x{x}x.x!x xTxÄxcxhxTx&xaxuxmxlx;xcxhxÜxüxßx&x(x)x[x x-x-x#x-x-x xTxÄxCxHxTx&xaxuxmxlx;xcxhxÜxüxßx&x(x)x[x]x{x}x.x!x',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '//u',
                        StringReplaceService::PARAM_REPLACE => 'x',
                    ],
                    'svg' => 'abcTächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],
            ['7. The same as the case-sensitive case. The search string contains nothing in caseinsesitive Form ans replace a char. Each char-intersect will befill with the replace-char inclusive the start and the end.',
                'xaxbxcxTxäxcxhxTx&xaxuxmxlx;xcxhxÜxüxßx&x(x)x[x]x{x}x.x!x x-x#x-x xTxäxcxhxTx&xaxuxmxlx;xcxhxÜxüxßx&x(x)x[x]x{x}x.x!x x-x#x-x xTxäxcxhxTx&xaxuxmxlx;xcxhxÜxüxßx&x(x)x[x]x{x}x.x!x xTxÄxcxhxTx&xaxuxmxlx;xcxhxÜxüxßx&x(x)x[x x-x-x#x-x-x xTxÄxCxHxTx&xaxuxmxlx;xcxhxÜxüxßx&x(x)x[x]x{x}x.x!x',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '//iu',
                        StringReplaceService::PARAM_REPLACE => 'x',
                    ],
                    'svg' => 'abcTächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.!',
                    'function' => $functionName
                ],
            ],

        ];
    }

    /**
     * @dataProvider dataProviderReformateRegexReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndNoLimitWillUsed
     * @test
     */
    public function reformateRegexReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndNoLimitWillUsed($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider-part');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->reformateRegex(
                    $params['param'],
                    $params['svg']
                ),
                $message
            );
        }
    }

    public function dataProviderReformateRegexReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndALimitWillUsedAndProvoked()
    {
        $functionName = StringReplaceService::FUNC_REGEX_REPLACE;
//        $generalSvg = 'TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- ';
        $generalSvg = 'TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- ';

        return [
            ['1. The search string has nine matches. One should replace and no clean-replace is defined. (case-INsensitive)',
                'TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- ',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\]/iu',
                        StringReplaceService::PARAM_REPLACE => 'xxxx',
                        StringReplaceService::PARAM_MAX => 1,
                    ],
                    'svg' => $generalSvg,
                    'function' => $functionName
                ],
            ],
            ['1.a. The search string has nine matches. five should replace and four are untouched. The cleanstring does not matter. (case-INsensitive)',
                'TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- ',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\]/iu',
                        StringReplaceService::PARAM_REPLACE => 'xxxx',
                        StringReplaceService::PARAM_MAX => 5,
                        StringReplaceService::PARAM_CLEAN => 'yyyy',
                    ],
                    'svg' => $generalSvg,
                    'function' => $functionName
                ],
            ],
            ['1.b. The search string has nine matches. nine should replace and nothing are untouched. The Cleanstring does not matter (case-INsensitive)',
                'TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- ',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\]/iu',
                        StringReplaceService::PARAM_REPLACE => 'xxxx',
                        StringReplaceService::PARAM_MAX => 9,
                        StringReplaceService::PARAM_CLEAN => 'yyyy',
                    ],
                    'svg' => $generalSvg,
                    'function' => $functionName
                ],
            ],
            ['1.c. The search string has nine matches. nine should replace (not neenteen). (case-INsensitive)',
                'TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- ',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\]/iu',
                        StringReplaceService::PARAM_REPLACE => 'xxxx',
                        StringReplaceService::PARAM_MAX => 19,
                        StringReplaceService::PARAM_CLEAN => 'yyyy',
                    ],
                    'svg' => $generalSvg,
                    'function' => $functionName
                ],
            ],
            ['1.d. The search string has nine matches. zero matches should replaced. Nine matches are untouched. Nothing has changed. (case-INsensitive)',
                $generalSvg,
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\]/iu',
                        StringReplaceService::PARAM_REPLACE => 'xxxx',
                        StringReplaceService::PARAM_MAX => 0,
                        StringReplaceService::PARAM_CLEAN => 'yyyy',
                    ],
                    'svg' => $generalSvg,
                    'function' => $functionName
                ],
            ],
            ['2. The search string has five matches. One should replace and four are untouched. The clean-replace does not matter. (case-sensitive)',
                'TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- ',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\]/u',
                        StringReplaceService::PARAM_REPLACE => 'xxxx',
                        StringReplaceService::PARAM_MAX => 1,
                        StringReplaceService::PARAM_CLEAN => 'yyyy',

                    ],
                    'svg' => $generalSvg,
                    'function' => $functionName
                ],
            ],
            ['2.a The search string has five matches. Three should replace and two are untouched. The clean-replace does not matter. (case-sensitive)',
                'TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- TäCHT&auml;chÜüß&()[]{}.! --#-- ',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\]/u',
                        StringReplaceService::PARAM_REPLACE => 'xxxx',
                        StringReplaceService::PARAM_MAX => 3,
                        StringReplaceService::PARAM_CLEAN => 'yyyy',

                    ],
                    'svg' => $generalSvg,
                    'function' => $functionName
                ],
            ],
            ['2.b The search string has five matches. five should replace and nothing is not untouched anymore. The clean-replace does not matter. (case-sensitive)',
                'TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- ',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\]/u',
                        StringReplaceService::PARAM_REPLACE => 'xxxx',
                        StringReplaceService::PARAM_MAX => 5,
                        StringReplaceService::PARAM_CLEAN => 'yyyy',

                    ],
                    'svg' => $generalSvg,
                    'function' => $functionName
                ],
            ],
            ['2.c The search string has five matches. five should replace and nothing is not untouched anymore. The clean-replace does not matter. (case-sensitive)',
                'TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- TächT&auml;chÜüß&()[ --#-- xxxx{}.! --#-- ',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\]/u',
                        StringReplaceService::PARAM_REPLACE => 'xxxx',
                        StringReplaceService::PARAM_MAX => 15,
                        StringReplaceService::PARAM_CLEAN => 'yyyy',

                    ],
                    'svg' => $generalSvg,
                    'function' => $functionName
                ],
            ],
            ['2.d The search string has five matches. nothing (zero) should replace and five matches are untouched. The clean-replace does not matter. (case-sensitive)',
                $generalSvg,
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\]/u',
                        StringReplaceService::PARAM_REPLACE => 'xxxx',
                        StringReplaceService::PARAM_MAX => 0,
                        StringReplaceService::PARAM_CLEAN => 'yyyy',

                    ],
                    'svg' => $generalSvg,
                    'function' => $functionName
                ],
            ],

        ];
    }


    /**
     * @dataProvider dataProviderReformateRegexReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndALimitWillUsedAndProvoked
     * @test
     */
    public function reformateRegexReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndALimitWillUsedAndProvoked($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider-part');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->reformateRegex(
                    $params['param'],
                    $params['svg']
                ),
                $message
            );
        }
    }


    public function dataProviderReformateFreeParamFromHtmlCharCodesReturnModifiedParamIfMethodWillProvokeHtmlSpecailCharDecoding()
    {
        $arrayWithSpecialChars = [
            StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me &lt;b&gt;bold&lt;/b&gt; text.',
            StringReplaceService::PARAM_REPLACE => 'This is s&ouml;me other text.',
            StringReplaceService::PARAM_CLEAN => 'This is s&ouml;me restful text',
            StringReplaceService::PARAM_MAX => 'This is s&ouml;me &lt;b&gt;bold&lt;/b&gt; text.',
        ];
        $arrayWithDecodedSpecialChars = [
            StringReplaceService::PARAM_SEARCH => 'This is s&ouml;me <b>bold</b> text.',
            StringReplaceService::PARAM_REPLACE => 'This is s&ouml;me other text.',
            StringReplaceService::PARAM_CLEAN => 'This is s&ouml;me restful text',
            StringReplaceService::PARAM_MAX => 'This is s&ouml;me &lt;b&gt;bold&lt;/b&gt; text.',
        ];
        $arrayWithDecodedSpecialEntities = [
            StringReplaceService::PARAM_SEARCH => 'This is söme <b>bold</b> text.',
            StringReplaceService::PARAM_REPLACE => 'This is söme other text.',
            StringReplaceService::PARAM_CLEAN => 'This is söme restful text',
            StringReplaceService::PARAM_MAX => 'This is s&ouml;me &lt;b&gt;bold&lt;/b&gt; text.',
        ];
        return [
            ['10. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_REGEX .
                '", which don`t decode special chars.',
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX,]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX,]),
            ],
            ['20. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_REGEX_NO_CASES .
                '", which don`t decode special chars.',
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX_NO_CASES,]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX_NO_CASES,]),
            ],
            ['30. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_NORMAL .
                '", which don`t decode special chars.',
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL,]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL,]),
            ],
            ['40. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_NORMAL_NO_CASE .
                '", which don`t decode special chars.',
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL_NO_CASE,]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL_NO_CASE,]),
            ],
            ['50. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_HTML_REGEX_NO_CASE .
                '", which WILL decode special chars.',
                array_merge($arrayWithDecodedSpecialEntities,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_REGEX_NO_CASE,]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_REGEX_NO_CASE,]),
            ],
            ['60. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_HTML_NORMAL_NO_CASE .
                '", which WILL  decode special chars.',
                array_merge($arrayWithDecodedSpecialEntities,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_NORMAL_NO_CASE,]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_NORMAL_NO_CASE,]),
            ],
            ['70. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_HTML_REGEX .
                '", which WILL decode special chars.',
                array_merge($arrayWithDecodedSpecialEntities,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_REGEX,]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_REGEX,]),
            ],
            ['80. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_HTML_NORMAL .
                '", which WILL decode special chars.',
                array_merge($arrayWithDecodedSpecialEntities,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_NORMAL,]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_NORMAL,]),
            ],
            ['51. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_SPECIAL_REGEX_NO_CASE .
                '", which WILL decode special chars.',
                array_merge($arrayWithDecodedSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_REGEX_NO_CASE,]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_REGEX_NO_CASE,]),
            ],
            ['61. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL_NO_CASE .
                '", which WILL  decode special chars.',
                array_merge($arrayWithDecodedSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL_NO_CASE,]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL_NO_CASE,]),
            ],
            ['71. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_SPECIAL_REGEX .
                '", which WILL decode special chars.',
                array_merge($arrayWithDecodedSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_REGEX,]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_REGEX,]),
            ],
            ['81. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL .
                '", which WILL decode special chars.',
                array_merge($arrayWithDecodedSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL,]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL,]),
            ],
            ['100. The array of parame is valid and contain the a (undefined and normally not allowed) method, which don`t decode special chars.',
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => 'Mir doch egal',]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => 'Mir doch egal',]),
            ],
            ['110. The array of parame is valid and contain the a (empty and normally not allowed) method, which don`t decode special chars.',
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => '',]),
                array_merge($arrayWithSpecialChars,
                    [StringReplaceService::PARAM_METHOD => '',]),
            ],

        ];
    }


    /**
     * @dataProvider dataProviderReformateFreeParamFromHtmlCharCodesReturnModifiedParamIfMethodWillProvokeHtmlSpecailCharDecoding
     * @test
     */
    public function reformateFreeParamFromHtmlCharCodesReturnModifiedParamWithRespectToDefinedMethod($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider-part');
        } else {
            $result = $this->subject->reformateFreeParamFromHtmlCharCodes($params);
            $diffArray = array_diff_assoc($expect, $result);
            $this->assertSame(
                0,
                count($diffArray),
                'Test difference of exspected and caclulated array: must be 0. ' . "\n" . $message
            );
            $this->assertSame(
                count($expect),
                count($params),
                'Test length of result-array and expected array: must be equal. ' . "\n" . $message
            );

        }
    }

    public function dataProviderReformateAddRegexParameterToSearchPartReturnModifiedSearchParamIfMethodIsARegexMethod()
    {
        $arrayWithoutLimeterAndOption = [
            StringReplaceService::PARAM_SEARCH => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',
            StringReplaceService::PARAM_REPLACE => 'This is some other text.',
            StringReplaceService::PARAM_CLEAN => 'This is some restful text',
            StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',
        ];
        $arrayWithLimiterAndNormalOption = [
            StringReplaceService::PARAM_SEARCH => StringReplaceService::UTF8_REGEX_PREG_LIMITER .
                'This is some &lt;b&gt;bold&lt;/b&gt; text.' . StringReplaceService::UTF8_REGEX_PREG_LIMITER . 'u',
            StringReplaceService::PARAM_REPLACE => 'This is some other text.',
            StringReplaceService::PARAM_CLEAN => 'This is some restful text',
            StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',
        ];
        $arrayWithLimiterAndCaseInsensitiveOption = [
            StringReplaceService::PARAM_SEARCH => StringReplaceService::UTF8_REGEX_PREG_LIMITER .
                'This is some &lt;b&gt;bold&lt;/b&gt; text.' . StringReplaceService::UTF8_REGEX_PREG_LIMITER . 'iu',
            StringReplaceService::PARAM_REPLACE => 'This is some other text.',
            StringReplaceService::PARAM_CLEAN => 'This is some restful text',
            StringReplaceService::PARAM_MAX => 'This is some &lt;b&gt;bold&lt;/b&gt; text.',
        ];
        return [
            ['10. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_REGEX .
                '". The search-parame will contain delimiter ' . StringReplaceService::UTF8_REGEX_PREG_LIMITER .
                'and the option `u`.',
                array_merge($arrayWithLimiterAndNormalOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX,]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX,]),
            ],
            ['11. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_HTML_REGEX .
                '". The search-parame will contain delimiter ' . StringReplaceService::UTF8_REGEX_PREG_LIMITER .
                'and the option `u`.',
                array_merge($arrayWithLimiterAndNormalOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_REGEX,]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_REGEX,]),
            ],

            ['11.b The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_SPECIAL_REGEX .
                '". The search-parame will contain delimiter ' . StringReplaceService::UTF8_REGEX_PREG_LIMITER .
                'and the option `u`.',
                array_merge($arrayWithLimiterAndNormalOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_REGEX,]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_REGEX,]),
            ],


            ['20. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_REGEX_NO_CASES .
                '". The search-parame will contain delimiter ' . StringReplaceService::UTF8_REGEX_PREG_LIMITER .
                'and the options `iu`.',
                array_merge($arrayWithLimiterAndCaseInsensitiveOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX_NO_CASES,]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX_NO_CASES,]),
            ],
            ['21. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_HTML_REGEX_NO_CASE .
                '". The search-parame will contain delimiter ' . StringReplaceService::UTF8_REGEX_PREG_LIMITER .
                'and the options `iu`.',
                array_merge($arrayWithLimiterAndCaseInsensitiveOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_REGEX_NO_CASE,]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_REGEX_NO_CASE,]),
            ],

            ['21.b. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_SPECIAL_REGEX_NO_CASE .
                '". The search-parame will contain delimiter ' . StringReplaceService::UTF8_REGEX_PREG_LIMITER .
                'and the options `iu`.',
                array_merge($arrayWithLimiterAndCaseInsensitiveOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_REGEX_NO_CASE,]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_REGEX_NO_CASE,]),
            ],

            ['30. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_NORMAL .
                '". The search-parame will not be changed.',
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL,]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL,]),
            ],
            ['40. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_NORMAL_NO_CASE .
                '". The search-parame will not be changed.',
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL_NO_CASE,]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL_NO_CASE,]),
            ],
            ['50. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_HTML_NORMAL_NO_CASE .
                '". The search-parame will not be changed.',
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_NORMAL_NO_CASE,]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_NORMAL_NO_CASE,]),
            ],
            ['60. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_HTML_NORMAL .
                '". The search-parame will not be changed.',
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_NORMAL,]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_NORMAL,]),
            ],
            ['50.b. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL_NO_CASE .
                '". The search-parame will not be changed.',
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL_NO_CASE,]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL_NO_CASE,]),
            ],
            ['60.b. The array of parame is valid and contain the the method "' . StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL .
                '". The search-parame will not be changed.',
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL,]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL,]),
            ],
            ['100. The array of parame is valid and contain the a (undefined and normally not allowed) method' .
                '. The search-parame will not be changed.',
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => 'Mir doch egal',]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => 'Mir doch egal',]),
            ],
            ['110. The array of parame is valid and contain the a (empty and normally not allowed) method' .
                '. The search-parame will not be changed.',
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => '',]),
                array_merge($arrayWithoutLimeterAndOption,
                    [StringReplaceService::PARAM_METHOD => '',]),
            ],

        ];
    }


    /**
     * @dataProvider dataProviderReformateAddRegexParameterToSearchPartReturnModifiedSearchParamIfMethodIsARegexMethod
     * @test
     */
    public function reformateAddRegexParameterToSearchPartReturnModifiedSearchParamIfMethodIsARegexMethod($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider-part');
        } else {
            $result = $this->subject->reformateAddRegexParameterToSearchPart($params);
            $diffArray = array_diff_assoc($expect, $result);
            $this->assertSame(
                0,
                count($diffArray),
                'Test difference of exspected and caclulated array: must be 0. ' . "\n" . $message
            );
            $this->assertSame(
                count($expect),
                count($params),
                'Test length of result-array and expected array: must be equal. ' . "\n" . $message
            );

        }
    }

    /**
     * @return array
     */
    public function dataProviderReformateNormalFlagLimitReturnExpectedStreamGivenTheValueOfLimit()
    {
        $normalParamWithLimit = [
            StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL,
            StringReplaceService::PARAM_SEARCH => 'TächT(....+)chÜ!',
            StringReplaceService::PARAM_REPLACE => 'XXXX',
            StringReplaceService::PARAM_MAX => 2,
            StringReplaceService::PARAM_CLEAN => 'YYYY',
        ];
        $normalParamLimitFree = [
            StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL,
            StringReplaceService::PARAM_SEARCH => 'TächT(....+)chÜ!',
            StringReplaceService::PARAM_REPLACE => 'XXXX',
        ];
        $rebuildPart = 'TächT(....+)chÜ! ';
        $rebuildPartCaseInsensitive = 'TäCHT(....+)CHÜ! ';
        $rebuildSearchFree = 'TächT(xxxx+)chÜ! ';

        return [
            ['N.10 The subject string is empty and no limit is defined',
                '',
                [
                    'param' => $normalParamLimitFree,
                    'svg' => '',
                ],
            ],
            ['N.11 The subject string is empty and no limit is defined',
                '',
                [
                    'param' => $normalParamWithLimit,
                    'svg' => '',
                ],
            ],
            ['N.20 The subject string contain five parts and is case-equal to the searchparameter and no limit is defined',
                'XXXX XXXX XXXX XXXX XXXX ',
                [
                    'param' => $normalParamLimitFree,
                    'svg' => $rebuildPart . $rebuildPart . $rebuildPart . $rebuildPart . $rebuildPart,
                ],
            ],
            ['N.21 The subject string contain five parts and is case-equal to the searchparameter and no limit is defined',
                'XXXX XXXX YYYY YYYY YYYY ',
                [
                    'param' => $normalParamWithLimit,
                    'svg' => $rebuildPart . $rebuildPart . $rebuildPart . $rebuildPart . $rebuildPart,
                ],
            ],
            ['N.22 The subject string contain five parts and is case-INSEENSITIVE to the searchparameter and no limit is defined',
                $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive,
                [
                    'param' => $normalParamLimitFree,
                    'svg' => $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive,
                ],
            ],
            ['N.23 The subject string contain five parts and is case-INSEENSITIVE to the searchparameter and no limit is defined',
                $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive,
                [
                    'param' => $normalParamWithLimit,
                    'svg' => $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive,
                ],
            ],
            ['N.31 The subject string contain five parts and the dots of the searchpart converted to x in the haystack',
                $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree,
                [
                    'param' => $normalParamLimitFree,
                    'svg' => $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree,
                ],
            ],
            ['N.32 The subject string contain five parts and is case-INSEENSITIVE to the searchparameter and no limit is defined',
                $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree,
                [
                    'param' => $normalParamWithLimit,
                    'svg' => $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree,
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderReformateNormalFlagLimitReturnExpectedStreamGivenTheValueOfLimit
     * @test
     */
    public function reformateNormalFlagLimitReturnExpectedStreamGivenTheValueOfLimit($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $result = $this->subject->reformateNormalFlagLimit($params['param'],
                $params['svg'],
                StringReplaceService::UTF8_REGEX_PREG_MODIFICATOR_UNICODE);
            $this->assertSame(
                $expect,
                $result,
                $message
            );
        }
    }

    /**
     * @return array
     */
    public function dataProviderReformateNormalCaseInsensitiveFlagLimitReturnExpectedStreamGivenTheValueOfLimit()
    {
        $normalParamWithLimit = [
            StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL,
            StringReplaceService::PARAM_SEARCH => 'TächT(....+)chÜ!',
            StringReplaceService::PARAM_REPLACE => 'XXXX',
            StringReplaceService::PARAM_MAX => 2,
            StringReplaceService::PARAM_CLEAN => 'YYYY',
        ];
        $normalParamLimitFree = [
            StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL,
            StringReplaceService::PARAM_SEARCH => 'TächT(....+)chÜ!',
            StringReplaceService::PARAM_REPLACE => 'XXXX',
        ];
        $rebuildPart = 'TächT(....+)chÜ! ';
        $rebuildPartCaseInsensitive = 'TäCHT(....+)CHÜ! ';
        $rebuildSearchFree = 'TächT(xxxx+)chÜ! ';

        return [
            ['NI.10 The subject string is empty and no limit is defined',
                '',
                [
                    'param' => $normalParamLimitFree,
                    'svg' => '',
                ],
            ],
            ['NI.11 The subject string is empty and no limit is defined',
                '',
                [
                    'param' => $normalParamWithLimit,
                    'svg' => '',
                ],
            ],
            ['NI.20 The subject string contain five parts and is case-equal to the searchparameter and no limit is defined',
                'XXXX XXXX XXXX XXXX XXXX ',
                [
                    'param' => $normalParamLimitFree,
                    'svg' => $rebuildPart . $rebuildPart . $rebuildPart . $rebuildPart . $rebuildPart,
                ],
            ],
            ['NI.21 The subject string contain five parts and is case-equal to the searchparameter and no limit is defined',
                'XXXX XXXX YYYY YYYY YYYY ',
                [
                    'param' => $normalParamWithLimit,
                    'svg' => $rebuildPart . $rebuildPart . $rebuildPart . $rebuildPart . $rebuildPart,
                ],
            ],
            ['NI.22 The subject string contain five parts and is case-INSEENSITIVE to the searchparameter and no limit is defined',
                'XXXX XXXX XXXX XXXX XXXX ',
                [
                    'param' => $normalParamLimitFree,
                    'svg' => $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive,
                ],
            ],
            ['NI.23 The subject string contain five parts and is case-INSEENSITIVE to the searchparameter and no limit is defined',
                'XXXX XXXX YYYY YYYY YYYY ',
                [
                    'param' => $normalParamWithLimit,
                    'svg' => $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive . $rebuildPartCaseInsensitive,
                ],
            ],
            ['NI.31 The subject string contain five parts and the dots of the searchpart converted to x in the haystack',
                $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree,
                [
                    'param' => $normalParamLimitFree,
                    'svg' => $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree,
                ],
            ],
            ['NI.32 The subject string contain five parts and is case-INSEENSITIVE to the searchparameter and no limit is defined',
                $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree,
                [
                    'param' => $normalParamWithLimit,
                    'svg' => $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree . $rebuildSearchFree,
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderReformateNormalCaseInsensitiveFlagLimitReturnExpectedStreamGivenTheValueOfLimit
     * @test
     */
    public function reformateNormalCaseInsensitiveFlagLimitReturnExpectedStreamGivenTheValueOfLimit($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $result = $this->subject->reformateNormalCaseInsensitiveFlagLimit($params['param'],
                $params['svg'],
                StringReplaceService::UTF8_REGEX_PREG_MODIFICATOR_UNICODE);
            $this->assertSame(
                $expect,
                $result,
                $message
            );
        }
    }


    public function dataProviderReformateRegexDefaultDefaultReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndNoLimitWillUsed()
    {

        return [
            ['1. The searchstring contains two regular parts and works case-sensitive. This shows, that the regular Search-Replace-Fuinction is used.',
                'TÄchT&auml;chÜüß&()[ --#-- TÄCHT&auml;chÜüß&()[]{}.!',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/Tä([^#]+)\](.+)Täch/u',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TÄchT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                ],
            ],
            ['2. The search string contains escpaed parts and the Limit-Function ist usable. This shows, that the regular Search-Replace-Fuinction is used.',
                'XXXX -#- XXXX -#- YYYY',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => '/TächT&auml;ch.üß&\(\)\[\]\{\}.!/iu',
                        StringReplaceService::PARAM_REPLACE => 'XXXX',
                        StringReplaceService::PARAM_MAX => 2,
                        StringReplaceService::PARAM_CLEAN => 'YYYY',
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                ],
            ],

        ];
    }

    /**
     * @dataProvider dataProviderReformateRegexDefaultDefaultReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndNoLimitWillUsed
     * @test
     */
    public function reformateRegexDefaultDefaultReturnExpectedBooleanIfVariationOfsearchIsUsedAndASimpleArraysIsUsedAndNoLimitWillUsed($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider-part');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->reformateRegexDefault(
                    $params['param'],
                    $params['svg']
                ),
                $message
            );
        }
    }

    public function dataProviderReformateReturnExpectedStreamIfXmlStreamAndParamsAreDefined()
    {

        return [
            ['1. if the xmlStream is empty, The method gives the empty stream back.',
                '',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'Tä([^#]+)\](.+)Täch',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                    ],
                    'svg' => '',
                ],
            ],
            ['2. If non method is given, The method will replace normal..',
                'TÄ$1 --#-- TÄCH$3 -#- TÄ$1 --#-- TÄCH$3',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'Tä([^#]+)\](.+)Täch',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                    ],
                    'svg' => 'Tä([^#]+)\](.+)Täch -#- Tä([^#]+)\](.+)Täch',
                ],
            ],
            ['3. If non method is given, The method will replace normal.It will respect the limit.',
                'TÄ$1 --#-- TÄCH$3 -#- Tä([^#]+)\](.+)Täch',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'Tä([^#]+)\](.+)Täch',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                        StringReplaceService::PARAM_MAX => '1',
                    ],
                    'svg' => 'Tä([^#]+)\](.+)Täch -#- Tä([^#]+)\](.+)Täch',
                ],
            ],
            ['4. If non method is given, The method will replace normal.It will respect the limit and the clean-aparameter.',
                'TÄ$1 --#-- TÄCH$3 -#- yyyy',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'Tä([^#]+)\](.+)Täch',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                        StringReplaceService::PARAM_MAX => '1',
                        StringReplaceService::PARAM_CLEAN => 'yyyy',
                    ],
                    'svg' => 'Tä([^#]+)\](.+)Täch -#- Tä([^#]+)\](.+)Täch',
                ],
            ],
            ['10. The if-condition-part, ibn a method isset, will be successful used.',
                'XXXX -#- XXXX -#- YYYY',
                [
                    'param' => [
                            StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX_NO_CASES,
                            StringReplaceService::PARAM_SEARCH => 'TächT&auml;ch.üß&\(\)\[\]\{\}.!',
                            StringReplaceService::PARAM_REPLACE => 'XXXX',
                            StringReplaceService::PARAM_MAX => '2',
                            StringReplaceService::PARAM_CLEAN => 'YYYY'
                    ],
                    'svg' => 'TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.! -#- TächT&auml;chÜüß&()[]{}.!',
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderReformateReturnExpectedStreamIfXmlStreamAndParamsAreDefined
     * @test
     */
    public function reformateReturnExpectedStreamIfXmlStreamAndParamsAreDefined($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider-part');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->reformate(
                    ConfigService::DEFAULT_LIST,
                    $params['param'],
                    $params['svg']
                ),
                $message
            );
        }
    }


    public function dataProviderReformateSwitchMethodAfterParamPreparationReturnExpectedStreamIfValidXmlStreamAndValidParamsAreDefinedVariatingTheMethod()
    {

        return [
            ['1. if the xmlStream is empty, The method gives the empty stream back.',
                '',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'Tä([^#]+)\](.+)Täch',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                    ],
                    'svg' => '',
                ],
            ],
            ['2. If non method is given, The method will replace normal.',
                'TÄ$1 --#-- TÄCH$3 -#- TÄ$1 --#-- TÄCH$3',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'Tä([^#]+)\](.+)Täch',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                    ],
                    'svg' => 'Tä([^#]+)\](.+)Täch -#- Tä([^#]+)\](.+)Täch',
                ],
            ],
            ['2.a If non method is given, The method will replace normal. Limit will be used',
                'TÄ$1 --#-- TÄCH$3 -#- Tä([^#]+)\](.+)Täch',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'Tä([^#]+)\](.+)Täch',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                        StringReplaceService::PARAM_MAX => '1',
                    ],
                    'svg' => 'Tä([^#]+)\](.+)Täch -#- Tä([^#]+)\](.+)Täch',
                ],
            ],
            ['2.b If non method is given, The method will replace normal. Limit and Clean will be used ',
                'TÄ$1 --#-- TÄCH$3 -#- yyyy',
                [
                    'param' => [
                        StringReplaceService::PARAM_SEARCH => 'Tä([^#]+)\](.+)Täch',
                        StringReplaceService::PARAM_REPLACE => 'TÄ$1 --#-- TÄCH$3',
                        StringReplaceService::PARAM_MAX => '1',
                        StringReplaceService::PARAM_CLEAN => 'yyyy',
                    ],
                    'svg' => 'Tä([^#]+)\](.+)Täch -#- Tä([^#]+)\](.+)Täch',
                ],
            ],
            ['10.a The method '.StringReplaceService::PARAM_METHOD_HTML_NORMAL.' is given,'.
                ' The svg is successful and selectiv for this method. ',
                'Ä ä Ä ä Ä ä A u A u A o o"',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_NORMAL,
                        StringReplaceService::PARAM_SEARCH => 'a',
                        StringReplaceService::PARAM_REPLACE => 'u',
                        StringReplaceService::PARAM_MAX => '2',
                        StringReplaceService::PARAM_CLEAN => 'o',
                    ],
                    'svg' => 'Ä ä &Auml; &auml; &Auml; &auml; A a A a A a a"',
                ],
            ],
            ['10.b The method '.StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL.' is given,'.
                ' The svg is successful and selectiv for this method. ',
                'Ä ä &Auml; &uuml; &Auml; &uuml; A o A o A o o"',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL,
                        StringReplaceService::PARAM_SEARCH => 'a',
                        StringReplaceService::PARAM_REPLACE => 'u',
                        StringReplaceService::PARAM_MAX => '2',
                        StringReplaceService::PARAM_CLEAN => 'o',
                    ],
                    'svg' => 'Ä ä &Auml; &auml; &Auml; &auml; A a A a A a a"',
                ],
            ],
            ['11 The method '.StringReplaceService::PARAM_METHOD_NORMAL.' is given,'.
                ' The svg is successful and selectiv for this method. ',
                'Ä ä &Auml; &uuml; &Auml; &uuml; A o A o A o o"',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL,
                        StringReplaceService::PARAM_SEARCH => 'a',
                        StringReplaceService::PARAM_REPLACE => 'u',
                        StringReplaceService::PARAM_MAX => '2',
                        StringReplaceService::PARAM_CLEAN => 'o',
                    ],
                    'svg' => 'Ä ä &Auml; &auml; &Auml; &auml; A a A a A a a"',
                ],
            ],
            ['12.a The method '.StringReplaceService::PARAM_METHOD_HTML_NORMAL_NO_CASE.' is given,'.
                ' The svg is successful and selectiv for this method. ',
                'Ä ä Ä ä Ä ä u u o o o o o"',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_NORMAL_NO_CASE,
                        StringReplaceService::PARAM_SEARCH => 'a',
                        StringReplaceService::PARAM_REPLACE => 'u',
                        StringReplaceService::PARAM_MAX => '2',
                        StringReplaceService::PARAM_CLEAN => 'o',
                    ],
                    'svg' => 'Ä ä &Auml; &auml; &Auml; &auml; A a A a A a a"',
                ],
            ],
            ['12.b The method '.StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL_NO_CASE.' is given,'.
                ' The svg is successful and selectiv for this method. ',
                'Ä ä &uuml; &uuml; &ouml; &ouml; o o o o o o o"',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_NORMAL_NO_CASE,
                        StringReplaceService::PARAM_SEARCH => 'a',
                        StringReplaceService::PARAM_REPLACE => 'u',
                        StringReplaceService::PARAM_MAX => '2',
                        StringReplaceService::PARAM_CLEAN => 'o',
                    ],
                    'svg' => 'Ä ä &Auml; &auml; &Auml; &auml; A a A a A a a"',
                ],
            ],
            ['13 The method '.StringReplaceService::PARAM_METHOD_NORMAL_NO_CASE.' is given,'.
                ' The svg is successful and selectiv for this method. ',
                'Ä ä &uuml; &uuml; &ouml; &ouml; o o o o o o o"',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_NORMAL_NO_CASE,
                        StringReplaceService::PARAM_SEARCH => 'a',
                        StringReplaceService::PARAM_REPLACE => 'u',
                        StringReplaceService::PARAM_MAX => '2',
                        StringReplaceService::PARAM_CLEAN => 'o',
                    ],
                    'svg' => 'Ä ä &Auml; &auml; &Auml; &auml; A a A a A a a"',
                ],
            ],
            ['14.a The method '.StringReplaceService::PARAM_METHOD_HTML_REGEX.' is given,'.
                ' The svg is successful and selectiv for this method. ',
                'Ä ä Ä ä Ä ä A u A u A oo',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_REGEX,
                        StringReplaceService::PARAM_SEARCH => 'a(.)',
                        StringReplaceService::PARAM_REPLACE => 'u$1',
                        StringReplaceService::PARAM_MAX => '2',
                        StringReplaceService::PARAM_CLEAN => 'o',
                    ],
                    'svg' => 'Ä ä &Auml; &auml; &Auml; &auml; A a A a A a a"',
                ],
            ],
            ['14.b The method '.StringReplaceService::PARAM_METHOD_SPECIAL_REGEX.' is given,'.
                ' The svg is successful and selectiv for this method. ',
                'Ä ä &Auml; &uuml; &Auml; &uuml; A oA oA oo',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_REGEX,
                        StringReplaceService::PARAM_SEARCH => 'a(.)',
                        StringReplaceService::PARAM_REPLACE => 'u$1',
                        StringReplaceService::PARAM_MAX => '2',
                        StringReplaceService::PARAM_CLEAN => 'o',
                    ],
                    'svg' => 'Ä ä &Auml; &auml; &Auml; &auml; A a A a A a a"',
                ],
            ],
            ['15 The method '.StringReplaceService::PARAM_METHOD_REGEX.' is given,'.
                ' The svg is successful and selectiv for this method. ',
                'Ä ä &Auml; &uuml; &Auml; &uuml; A oA oA oo',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX,
                        StringReplaceService::PARAM_SEARCH => 'a(.)',
                        StringReplaceService::PARAM_REPLACE => 'u$1',
                        StringReplaceService::PARAM_MAX => '2',
                        StringReplaceService::PARAM_CLEAN => 'o',
                    ],
                    'svg' => 'Ä ä &Auml; &auml; &Auml; &auml; A a A a A a a"',
                ],
            ],
            ['16.a The method '.StringReplaceService::PARAM_METHOD_HTML_REGEX_NO_CASE.' is given,'.
                ' The svg is successful and selectiv for this method. ',
                'Ä ä Ä ä Ä ä u u ooooo',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_HTML_REGEX_NO_CASE,
                        StringReplaceService::PARAM_SEARCH => 'a(.)',
                        StringReplaceService::PARAM_REPLACE => 'u$1',
                        StringReplaceService::PARAM_MAX => '2',
                        StringReplaceService::PARAM_CLEAN => 'o',
                    ],
                    'svg' => 'Ä ä &Auml; &auml; &Auml; &auml; A a A a A a a"',
                ],
            ],
            ['16.b The method '.StringReplaceService::PARAM_METHOD_SPECIAL_REGEX_NO_CASE.' is given,'.
                ' The svg is successful and selectiv for this method. ',
                'Ä ä &uuml; &uuml; &oml; &oml; ooooooo',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_SPECIAL_REGEX_NO_CASE,
                        StringReplaceService::PARAM_SEARCH => 'a(.)',
                        StringReplaceService::PARAM_REPLACE => 'u$1',
                        StringReplaceService::PARAM_MAX => '2',
                        StringReplaceService::PARAM_CLEAN => 'o',
                    ],
                    'svg' => 'Ä ä &Auml; &auml; &Auml; &auml; A a A a A a a"',
                ],
            ],
            ['17 The method '.StringReplaceService::PARAM_METHOD_REGEX_NO_CASES.' is given,'.
                ' The svg is successful and selectiv for this method. ',
                'Ä ä &uuml; &uuml; &oml; &oml; ooooooo',
                [
                    'param' => [
                        StringReplaceService::PARAM_METHOD => StringReplaceService::PARAM_METHOD_REGEX_NO_CASES,
                        StringReplaceService::PARAM_SEARCH => 'a(.)',
                        StringReplaceService::PARAM_REPLACE => 'u$1',
                        StringReplaceService::PARAM_MAX => '2',
                        StringReplaceService::PARAM_CLEAN => 'o',
                    ],
                    'svg' => 'Ä ä &Auml; &auml; &Auml; &auml; A a A a A a a"',
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderReformateSwitchMethodAfterParamPreparationReturnExpectedStreamIfValidXmlStreamAndValidParamsAreDefinedVariatingTheMethod
     * @test
     */
    public function reformateSwitchMethodAfterParamPreparationReturnExpectedStreamIfValidXmlStreamAndValidParamsAreDefinedVariatingTheMethod($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider-part');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->reformate(
                    $params['param'],
                    $params['svg']
                ),
                $message
            );
        }
    }

//reformateFreeXmlStreamFromHtmlCharCodes
    /**
     * @test
     */
    public function helloWorldIsEverTrue()
    {
        $this->assertSame(true, true, 'empty-data at the End of the provider');
    }
}

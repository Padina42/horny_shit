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
 * Date: 01.07.2017
 * Time: 16:41
 */

namespace Porth\HornyShit\Service\Svt;

use PHPUnit\Framework\TestCase;
use Porth\HornyShit\Service\Svt\RebuildBaseService;

class RebuildParamValidateServiceTest extends TestCase
{

    /**
     * @var RebuildParamValidateService
     */
    protected $subject = null;


    public function setUp()
    {
        $this->subject = new RebuildParamValidateService();
    }

    public function tearDown()
    {
        unset($this->subject);
    }


    public function dataProviderParamValidateStringNotEmptyReturnExpectedBooleanAndCountIfTheParamStringIsVariated()
    {
        return [
            [
                ['no' => 'a1', 'info' => 'False and 12 are expected, if the param is from type null.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => null],
            ],
            [
                ['no' => 'a2', 'info' => 'False and 12 are expected, if the param is from type integer with value 24.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => 24],
            ],
            [
                ['no' => 'a3', 'info' => 'False and 12 are expected, if the param is from type string and empty.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => ''],
            ],
            [
                [
                    'no' => 'a4',
                    'info' => 'False and 12 are expected, if the param is from type string and only contains space.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => '             '],
            ],

            [
                [
                    'no' => '10',
                    'info' => 'True and 13 are expected, if the param is from type string and contains at least one char.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => '    a         '],
            ],

        ];
    }

    /**
     * @dataProvider dataProviderParamValidateStringNotEmptyReturnExpectedBooleanAndCountIfTheParamStringIsVariated
     * @test
     */
    public function paramValidateStringNotEmptyReturnExpectedBooleanAndCountIfTheParamStringIsVariated($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $count = $param['count'];
            $this->assertSame(
                $expect['boolean'],
                $this->subject->paramValidateStringNotEmpty($count, $param['parameterString']),
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );

            $this->assertSame(
                $expect['count'],
                $count,
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );
        }
    }

    public function dataProviderParamValidateStringOptionalExistReturnExpectedBooleanAndCountIfTheParamStringIsVariated()
    {
        return [
            [
                ['no' => 'b2', 'info' => 'False and 12 are expected, if the named param of an array is from type integer with value 24.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parentArray' => ['test' => 24], 'childName' => 'test'],
            ],
            [
                ['no' => 'b3', 'info' => 'False and 12 are expected, if the named param of an array is from type string and empty.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parentArray' => ['test' => ''], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'b4',
                    'info' => 'False and 12 are expected, if the named param of an array is from type string and only contains space.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parentArray' => ['test' => '             '], 'childName' => 'test'],
            ],

            [
                [
                    'no' => 'b10',
                    'info' => 'True and 13 are expected, if the named param of an array is from type string and contains at least one char.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => '    a         '], 'childName' => 'test'],
            ],

            [
                [
                    'no' => 'b11',
                    'info' => 'True and 12 are expected, if the named param of an array is not set.'
                ],
                ['count' => 12, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => null], 'childName' => 'test'],
            ],

            [
                [
                    'no' => 'b20',
                    'info' => 'True and 12 are expected, if the named param of an array dont exist.'
                ],
                ['count' => 12, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => null], 'childName' => 'nix'],
            ],

        ];
    }

    /**
     * @dataProvider dataProviderParamValidateStringOptionalExistReturnExpectedBooleanAndCountIfTheParamStringIsVariated
     * @test
     */
    public function paramValidateStringOptionalExistReturnExpectedBooleanAndCountIfTheParamStringIsVariated($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $count = $param['count'];
            $this->assertSame(
                $expect['boolean'],
                $this->subject->paramValidateStringOptionalExist($count, $param['parentArray'], $param['childName']),
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );

            $this->assertSame(
                $expect['count'],
                $count,
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );
        }
    }


    public function dataProviderParamValidateIntegerNotEmptyReturnExpectedBooleanAndCountIfTheParamStringIsVariated()
    {
        return [
            [
                ['no' => 'a1', 'info' => 'False and 12 are expected, if the param is from type null.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => null],
            ],
            [
                ['no' => 'a2', 'info' => 'False and 12 are expected, if the param is from type string with value 24g.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => '24g'],
            ],
            [
                ['no' => 'a3', 'info' => 'False and 12 are expected, if the param is from type string and empty.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => ''],
            ],
            [
                ['no' => 'a4', 'info' => 'False and 12 are expected, if the param is from type float and 12.1.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => 12.1],
            ],
            [
                [
                    'no' => 'a5',
                    'info' => 'False and 12 are expected, if the param is from type string and only contains space.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => '             '],
            ],
            [
                [
                    'no' => 'a6',
                    'info' => 'True and 13 are expected, if the param is from type string and value raound(12.1) in a string surrounded by space.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => ' 12.1 '],
            ],

            [
                [
                    'no' => 'a7',
                    'info' => 'True and 13 are expected, if the param is from type string and value raound(12,0) in a string surrounded by space.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => ' 12,0 '],
            ],

            [
                [
                    'no' => '10',
                    'info' => 'True and 13 are expected, if the param is from type string and value 12.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => '12'],
            ],
            [
                [
                    'no' => '10a',
                    'info' => 'True and 13 are expected, if the param is from type string and value 12 after a space-character.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => ' 12'],
            ],
            [
                [
                    'no' => '10b',
                    'info' => 'True and 13 are expected, if the param is from type string and value 12 surrounded by space.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => ' 12 '],
            ],
            [
                [
                    'no' => '11',
                    'info' => 'True and 13 are expected, if the param is from type integer and value 12.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => 12],
            ],
            [
                [
                    'no' => '12',
                    'info' => 'True and 13 are expected, if the param is from type integer with value 0.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => 0],
            ],
            [
                [
                    'no' => '12b',
                    'info' => 'True and 13 are expected, if the param is from type string with value 0.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => '0'],
            ],
            [
                [
                    'no' => '12c',
                    'info' => 'True and 13 are expected, if the param is from type string  with value 0 and tailing spaces.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => '   0  '],
            ],
            [
                [
                    'no' => '13',
                    'info' => 'True and 13 are expected, if the param is from type integer with an negative Value.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => -1],
            ],
            [
                ['no' => '14', 'info' => 'False and 12 are expected, if the param is from type float and 12.0.'],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => 12.0],
            ],
            [
                [
                    'no' => '15',
                    'info' => 'True and 13 are expected, if the param is from type string and value raound(12.0) in a string surrounded by space.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => ' 12.0 '],
            ],

        ];
    }

    /**
     * @dataProvider dataProviderParamValidateIntegerNotEmptyReturnExpectedBooleanAndCountIfTheParamStringIsVariated
     * @test
     */
    public function paramValidateIntegerNotEmptyReturnExpectedBooleanAndCountIfTheParamStringIsVariated($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $count = $param['count'];
            $this->assertSame(
                $expect['boolean'],
                $this->subject->paramValidateIntegerNotEmpty($count, $param['parameterString']),
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );

            $this->assertSame(
                $expect['count'],
                $count,
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );
        }
    }

    public function dataProviderParamValidateIntegerOptionalExistReturnExpectedBooleanAndCountIfTheParamStringIsVariated()
    {
        return [
            [
                ['no' => 'b2', 'info' => 'False and 12 are expected, if the named param of an array is from type string with value 24g.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parentArray' => ['test' => ' 24g'], 'childName' => 'test'],
            ],
            [
                ['no' => 'b3', 'info' => 'False and 12 are expected, if the named param of an array is from type string and empty.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parentArray' => ['test' => ''], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'b4',
                    'info' => 'False and 12 are expected, if the named param of an array is from type string and only contains space.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parentArray' => ['test' => '             '], 'childName' => 'test'],
            ],

            [
                [
                    'no' => 'b10',
                    'info' => 'True and 13 are expected, if the named param of an array is from type string with the space value 24.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => '    24         '], 'childName' => 'test'],
            ],

            [
                [
                    'no' => 'b11',
                    'info' => 'True and 12 are expected, if the named param of an array is not set.'
                ],
                ['count' => 12, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => null], 'childName' => 'test'],
            ],

            [
                [
                    'no' => 'b20',
                    'info' => 'True and 12 are expected, if the named param of an array dont exist.'
                ],
                ['count' => 12, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => null], 'childName' => 'nix'],
            ],

        ];
    }

    /**
     * @dataProvider dataProviderParamValidateIntegerOptionalExistReturnExpectedBooleanAndCountIfTheParamStringIsVariated
     * @test
     */
    public function paramValidateIntegerOptionalExistReturnExpectedBooleanAndCountIfTheParamStringIsVariated($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $count = $param['count'];
            $this->assertSame(
                $expect['boolean'],
                $this->subject->paramValidateIntegerOptionalExist($count, $param['parentArray'], $param['childName']),
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );

            $this->assertSame(
                $expect['count'],
                $count,
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );
        }
    }


    public function dataProviderParamValidateArrayNotEmptyReturnExpectedBooleanAndCountIfTheParamStringIsVariated()
    {
        return [
            [
                ['no' => 'c1', 'info' => 'False and 12 are expected, if the param is from type null.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => null],
            ],
            [
                ['no' => 'c2', 'info' => 'False and 12 are expected, if the param is from type integer with value 24.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => 24],
            ],
            [
                ['no' => 'c3', 'info' => 'False and 12 are expected, if the param is from type string and empty.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => ''],
            ],
            [
                [
                    'no' => 'c4',
                    'info' => 'False and 12 are expected, if the param is from type string and only contains space.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => '     a        '],
            ],

            [
                ['no' => 'c3', 'info' => 'False and 12 are expected, if the param is from type array, which has noch items.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => []],
            ],

            [
                [
                    'no' => 'c10',
                    'info' => 'True and 13 are expected, if the param is from type array and contains at least one item like an empty string.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => ['']],
            ],
            [
                [
                    'no' => 'c11',
                    'info' => 'True and 13 are expected, if the param is from type array and contains at least one item like a number.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => [12]],
            ],
            [
                [
                    'no' => 'c12',
                    'info' => 'True and 13 are expected, if the param is from type array and contains at least one item like null.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => [null]],
            ],
            [
                [
                    'no' => 'c13',
                    'info' => 'True and 13 are expected, if the param is from type array and contains at least one item like a empty array.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => [[]]],
            ],

        ];
    }

    /**
     * @dataProvider dataProviderParamValidateArrayNotEmptyReturnExpectedBooleanAndCountIfTheParamStringIsVariated
     * @test
     */
    public function paramValidateArrayNotEmptyReturnExpectedBooleanAndCountIfTheParamStringIsVariated($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $count = $param['count'];
            $this->assertSame(
                $expect['boolean'],
                $this->subject->paramValidateArrayNotEmpty($count, $param['parameterString']),
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );

            $this->assertSame(
                $expect['count'],
                $count,
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );
        }
    }


    public function dataProviderParamValidateBooleanExistReturnExpectedBooleanAndCountIfTheParamStringIsVariated()
    {
        return [
            [
                ['no' => 'd1', 'info' => 'False and 12 are expected, if the param is from type null.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => null],
            ],
            [
                ['no' => 'd2', 'info' => 'False and 12 are expected, if the param is from type integer with value 24.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => 24],
            ],
            [
                ['no' => 'd3', 'info' => 'False and 12 are expected, if the param is from type string and not empty.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => 'df'],
            ],
            [
                [
                    'no' => 'd4',
                    'info' => 'False and 12 are expected, if the param is from type string and only contains space.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => 'truge'],
            ],

            [
                [
                    'no' => 'd5',
                    'info' => 'False and 12 are expected, if the param is from type float with value 1.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => 1.0],
            ],
            [
                [
                    'no' => 'd6',
                    'info' => 'False and 12 are expected, if the param is from type float with value 0.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parameterString' => 0.0],
            ],


            [
                [
                    'no' => 'd10',
                    'info' => 'True and 13 are expected, if the param is from type integer with the number 0.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => 0],
            ],
            [
                [
                    'no' => 'd11',
                    'info' => 'True and 13 are expected, if the param is from type integer with the number 1.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => 1],
            ],
            [
                [
                    'no' => 'd12',
                    'info' => 'True and 13 are expected, if the param is from type boolean with value true.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => true],
            ],
            [
                [
                    'no' => 'd13',
                    'info' => 'True and 13 are expected, if the param is from type boolean with value false.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => false],
            ],
            [
                [
                    'no' => 'd14',
                    'info' => 'True and 13 are expected, if the param is from type string with value true.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => 'true'],
            ],
            [
                [
                    'no' => 'd15',
                    'info' => 'True and 13 are expected, if the param is from type string with value false.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => 'false'],
            ],
            [
                [
                    'no' => 'd16',
                    'info' => 'True and 13 are expected, if the param is from type boolean with value true with spaces.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => ' true '],
            ],
            [
                [
                    'no' => 'd17',
                    'info' => 'True and 13 are expected, if the param is from type boolean with value false with spaces.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => ' false '],
            ],
            [
                [
                    'no' => 'd18',
                    'info' => 'True and 13 are expected, if the param is from type boolean with value true in mixed cases.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => 'tRUe'],
            ],
            [
                [
                    'no' => 'd19',
                    'info' => 'True and 13 are expected, if the param is from type boolean with value false in mixed cases.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parameterString' => 'fALSe'],
            ],

        ];
    }

    /**
     * @dataProvider dataProviderParamValidateBooleanExistReturnExpectedBooleanAndCountIfTheParamStringIsVariated
     * @test
     */
    public function paramValidateBooleanExistReturnExpectedBooleanAndCountIfTheParamStringIsVariated($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $count = $param['count'];
            $this->assertSame(
                $expect['boolean'],
                $this->subject->paramValidateBooleanExist($count, $param['parameterString']),
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );

            $this->assertSame(
                $expect['count'],
                $count,
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );
        }
    }


    public function dataProviderParamValidateBooleanOptionalExistReturnExpectedBooleanAndCountIfTheParamStringIsVariated()
    {
        return [
            [
                ['no' => 'e2', 'info' => 'False and 12 are expected, if the named param of an array is from type integer with value 24.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parentArray' => ['test' => 24], 'childName' => 'test'],
            ],
            [
                ['no' => 'e3', 'info' => 'False and 12 are expected, if the named param of an array is from type string and not empty.'],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parentArray' => ['test' => 'df'], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'e4',
                    'info' => 'False and 12 are expected, if the named param of an array is from type string and only contains space.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parentArray' => ['test' => 'truge'], 'childName' => 'test'],
            ],

            [
                [
                    'no' => 'e5',
                    'info' => 'False and 12 are expected, if the named param of an array is from type float with value 1.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parentArray' => ['test' => 1.0], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'e6',
                    'info' => 'False and 12 are expected, if the named param of an array is from type float with value 0.'
                ],
                ['count' => 12, 'boolean' => false],
                ['count' => 12, 'parentArray' => ['test' => 0.0], 'childName' => 'test'],
            ],


            [
                [
                    'no' => 'e10',
                    'info' => 'True and 13 are expected, if the named param of an array is from type integer with the number 0.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => 0], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'e11',
                    'info' => 'True and 13 are expected, if the named param of an array is from type integer with the number 1.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => 1], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'e12',
                    'info' => 'True and 13 are expected, if the named param of an array is from type boolean with value true.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => true], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'e13',
                    'info' => 'True and 13 are expected, if the named param of an array is from type boolean with value false.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => false], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'e14',
                    'info' => 'True and 13 are expected, if the named param of an array is from type string with value true.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => 'true'], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'e15',
                    'info' => 'True and 13 are expected, if the named param of an array is from type string with value false.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => 'false'], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'e16',
                    'info' => 'True and 13 are expected, if the named param of an array is from type boolean with value true with spaces.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => ' true '], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'e17',
                    'info' => 'True and 13 are expected, if the named param of an array is from type boolean with value false with spaces.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => ' false '], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'e18',
                    'info' => 'True and 13 are expected, if the named param of an array is from type boolean with value true in mixed cases.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => 'tRUe'], 'childName' => 'test'],
            ],
            [
                [
                    'no' => 'e19',
                    'info' => 'True and 13 are expected, if the named param of an array is from type boolean with value false in mixed cases.'
                ],
                ['count' => 13, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => 'fALSe'], 'childName' => 'test'],
            ],

            [
                ['no' => 'e20', 'info' => 'True and 12 are expected, if the named param of an array is from type null or is not set.'],
                ['count' => 12, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => null], 'childName' => 'test'],
            ],
            [
                ['no' => 'e30', 'info' => 'True and 12 are expected, if the childname does not exist.'],
                ['count' => 12, 'boolean' => true],
                ['count' => 12, 'parentArray' => ['test' => null], 'childName' => 'nix'],
            ],

        ];
    }

    /**
     * @dataProvider dataProviderParamValidateBooleanOptionalExistReturnExpectedBooleanAndCountIfTheParamStringIsVariated
     * @test
     */
    public function paramValidateBooleanOptionalExistReturnExpectedBooleanAndCountIfTheParamStringIsVariated($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $count = $param['count'];
            $this->assertSame(
                $expect['boolean'],
                $this->subject->paramValidateBooleanOptionalExist($count, $param['parentArray'], $param['childName']),
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );

            $this->assertSame(
                $expect['count'],
                $count,
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );
        }
    }


    public function dataProviderGeneralPartParamValidateStringInTypeListReturnExpectedBooleanAndCountIfTheParamStringIsVariated()
    {
        return [
            [
                ['no' => 'f2', 'info' => 'False and 12 are expected, if the param is from type integer.'],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parameterString' => 24
                ],
            ],
            [
                ['no' => 'f3', 'info' => 'False and 12 are expected, if the param is from type string and not part of the list.'],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parameterString' => 'df'
                ],
            ],
            [
                [
                    'no' => 'f4',
                    'info' => 'False and 12 are expected, if the param is from type string and differ in case from an entry.'
                ],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parameterString' => 'Test'
                ],
            ],

            [
                [
                    'no' => 'f5',
                    'info' => 'False and 12 are expected, if the param is from type float (not string).'
                ],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parameterString' => 1.0
                ],
            ],

            [
                [
                    'no' => 'f6',
                    'info' => 'False and 12 are expected, if the param is from type string and contain a commaseparated sublist.'
                ],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parameterString' => 'test,valid'
                ],
            ],
            [
                [
                    'no' => 'f6',
                    'info' => 'False and 12 are expected, if the param is from type string and contain the list.'
                ],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parameterString' => 'test,valid,crude'
                ],
            ],

            [
                [
                    'no' => 'f10',
                    'info' => 'True and 13 are expected, if the param is from type integer with the number 0.'
                ],
                ['count' => 13, 'boolean' => true],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parameterString' => 'test'
                ],
            ],

        ];
    }

    public function dataProviderNullPartParamValidateStringInTypeListReturnExpectedBooleanAndCountIfTheParamStringIsVariated()
    {
        return [
            [
                ['no' => 'f1', 'info' => 'true and 12 are expected, if the param is null.'],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parameterString' => null
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderGeneralPartParamValidateStringInTypeListReturnExpectedBooleanAndCountIfTheParamStringIsVariated
     * @dataProvider dataProviderNullPartParamValidateStringInTypeListReturnExpectedBooleanAndCountIfTheParamStringIsVariated
     * @test
     */
    public function paramValidateStringInTypeListReturnExpectedBooleanAndCountIfTheParamStringIsVariated($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $count = $param['count'];
            $this->assertSame(
                $expect['boolean'],
                $this->subject->paramValidateStringInTypeList($count, $param['parameterString'], $param['list']),
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );

            $this->assertSame(
                $expect['count'],
                $count,
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );
        }
    }

    public function dataProviderNullPartParamValidateOptionalStringInTypeListReturnExpectedBooleanAndCountIfTheParamStringIsVariated()
    {
        return [
            [
                ['no' => 'f200', 'info' => 'true and 12 are expected, if the named param in an array is null.'],
                ['count' => 12, 'boolean' => true],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parentArray' => ['test' => null],
                    'childName' => 'test'
                ],
            ],
        ];
    }

    public function dataProviderNullPartParamValidateOptionalStringInTypeListReturnExpectedBooleanAndCountIfTheParamStringDontExist()
    {
        return [
            [
                ['no' => 'f100', 'info' => 'true and 12 are expected, if the named param of an array don`t exist .'],
                ['count' => 12, 'boolean' => true],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parentArray' => ['test' => null],
                    'childName' => 'nix'
                ],
            ],
        ];
    }

    public function dataProviderGeneralPartParamValidateOptionalStringInTypeListReturnExpectedBooleanAndCountIfTheParamStringIsVariated()
    {
        return [
            [
                ['no' => 'f2', 'info' => 'False and 12 are expected, if the named param in an array is from type integer.'],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parentArray' => ['test' => 24],
                    'childName' => 'test'
                ],
            ],
            [
                ['no' => 'f3', 'info' => 'False and 12 are expected, if the named param in an array is from type string and not part of the list.'],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parentArray' => ['test' => 'df'],
                    'childName' => 'test'
                ],
            ],
            [
                [
                    'no' => 'f4',
                    'info' => 'False and 12 are expected, if the named param in an array is from type string and differ in case from an entry.'
                ],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parentArray' => ['test' => 'Test'],
                    'childName' => 'test'
                ],
            ],

            [
                [
                    'no' => 'f5',
                    'info' => 'False and 12 are expected, if the named param in an array is from type float (not string).'
                ],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parentArray' => ['test' => 1.0],
                    'childName' => 'test'
                ],
            ],

            [
                [
                    'no' => 'f6',
                    'info' => 'False and 12 are expected, if the named param in an array is from type string and contain a commaseparated sublist.'
                ],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parentArray' => ['test' => 'test,valid'],
                    'childName' => 'test'
                ],
            ],
            [
                [
                    'no' => 'f6',
                    'info' => 'False and 12 are expected, if the named param in an array is from type string and contain the list.'
                ],
                ['count' => 12, 'boolean' => false],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parentArray' => ['test' => 'test,valid,crude'],
                    'childName' => 'test'
                ],
            ],

            [
                [
                    'no' => 'f10',
                    'info' => 'True and 13 are expected, if the named param in an array is from type integer with the number 0.'
                ],
                ['count' => 13, 'boolean' => true],
                [
                    'list' => 'test,valid,crude',
                    'count' => 12,
                    'parentArray' => ['test' => 'test'],
                    'childName' => 'test'
                ],
            ],

        ];
    }

    /**
     * @dataProvider dataProviderGeneralPartParamValidateOptionalStringInTypeListReturnExpectedBooleanAndCountIfTheParamStringIsVariated
     * @dataProvider dataProviderNullPartParamValidateOptionalStringInTypeListReturnExpectedBooleanAndCountIfTheParamStringIsVariated
     * @dataProvider dataProviderNullPartParamValidateOptionalStringInTypeListReturnExpectedBooleanAndCountIfTheParamStringDontExist
     * @test
     */
    public function paramValidateOptionalStringInTypeListReturnExpectedBooleanAndCountIfTheParamStringIsVariated($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $count = $param['count'];
            $this->assertSame(
                $expect['boolean'],
                $this->subject->paramValidateOptionalStringInTypeList($count, $param['parentArray'], $param['childName'], $param['list']),
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );

            $this->assertSame(
                $expect['count'],
                $count,
                $message['no'] . ' => test method-result: ' . "\n" . $message['info']
            );
        }
    }


    public function dataProviderParamValidateArrayWithOptionalItemContainMoreThanStartItemsReturnExpectedBooleanIfTheArrayContainsLessEqualOrMoreItems()
    {
        return [
            [
                ['no' => 'g1', 'info' => 'False if the minimum is 0 and the array contain no elements.'],
                false,
                [
                    'start' => 0,
                    'array' => []
                ],
            ],
            [
                ['no' => 'g2', 'info' => 'False if the minimu ist 1 and the array is empty.'],
                false,
                [
                    'start' => 1,
                    'array' => []
                ],
            ],
            [
                ['no' => 'g3', 'info' => 'False if the minimum is 2 and the array contain one element.'],
                false,
                [
                    'start' => 1,
                    'array' => ['first']
                ],
            ],
            [
                ['no' => 'g3b', 'info' => 'False if the minimum is 2 and the array contain one element, which end with an comma.'],
                false,
                [
                    'start' => 1,
                    'array' => ['first',]
                ],
            ],
            [
                ['no' => 'g4', 'info' => 'False if the minimum is 2 and the array contain one element.'],
                false,
                [
                    'start' => 2,
                    'array' => ['first']
                ],
            ],
            [
                ['no' => 'g5', 'info' => 'False if the minimum is 2 and the array contain two elements.'],
                false,
                [
                    'start' => 2,
                    'array' => ['first', 'second']
                ],
            ],
            [
                ['no' => 'g10', 'info' => 'True if the minimum is 0 and the array contain one element.'],
                true,
                [
                    'start' => 0,
                    'array' => ['first']
                ],
            ],
            [
                ['no' => 'g10', 'info' => 'True if the minimum is 0 and the array contain a null-element.'],
                true,
                [
                    'start' => 0,
                    'array' => [null]
                ],
            ],
            [
                ['no' => 'g11', 'info' => 'True if the minimum is 1 and the array contain one elements with an ending comma.'],
                false,
                [
                    'start' => 1,
                    'array' => ['first',]
                ],
            ],
            [
                ['no' => 'g11', 'info' => 'True if the minimum is 1 and the array contain one elements and an null-element.'],
                true,
                [
                    'start' => 1,
                    'array' => ['first', null,]
                ],
            ],
            [
                ['no' => 'g12', 'info' => 'True if the minimum is 1 and the array contain two elements.'],
                true,
                [
                    'start' => 1,
                    'array' => ['first', 'second',]
                ],
            ],
            [
                ['no' => 'g13', 'info' => 'True if the minimum is 2 and the array contain three elements.'],
                true,
                [
                    'start' => 2,
                    'array' => ['first', 'second', 'third']
                ],
            ],
            [
                ['no' => 'g20', 'info' => 'true if the minimum is negative and the array contain no element.'],
                true,
                [
                    'start' => -1,
                    'array' => []
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderParamValidateArrayWithOptionalItemContainMoreThanStartItemsReturnExpectedBooleanIfTheArrayContainsLessEqualOrMoreItems
     * @test
     */
    public function paramValidateArrayWithOptionalItemContainMoreThanStartItemsReturnExpectedBooleanIfTheArrayContainsLessOrEqualMoreItems($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateArrayWithOptionalItemContainMoreThanStartItems(
                    $param['array'], $param['start']),
                $message['no'] . ': ' . $message['info']
            );
        }
    }


    public function dataProviderParamValidateArrayWithOptionalItemContainPredictedCountOfItemsReturnExpectedBooleanIfTheArrayContainsLessEqualOrMoreItems()
    {
        return [
            [
                ['no' => 'g.a.1', 'info' => 'true if array is empty and the predicted value is 0.'],
                true,
                [
                    'start' => 0,
                    'array' => []
                ],
            ],
            [
                ['no' => 'g.a.2', 'info' => 'true if array contains one element and the predicted value is 1.'],
                true,
                [
                    'start' => 1,
                    'array' => ['test']
                ],
            ],
            [
                ['no' => 'g.a.3', 'info' => 'true if array contains one element, which is null, and the predicted value is 1.'],
                true,
                [
                    'start' => 1,
                    'array' => [null]
                ],
            ],
            [
                ['no' => 'g.a.4', 'info' => 'true if array contains two elements, where one is null, and the predicted value is 2.'],
                true,
                [
                    'start' => 2,
                    'array' => [null, 'second']
                ],
            ],
            [
                ['no' => 'g.a.10', 'info' => 'true if array contains two elements, where one is null, and the predicted value is 1.'],
                false,
                [
                    'start' => 1,
                    'array' => [null, 'second']
                ],
            ],
            [
                ['no' => 'g.a.11', 'info' => 'true if array contains one element and the predicted value is 0.'],
                false,
                [
                    'start' => 0,
                    'array' => ['second']
                ],
            ],
            [
                ['no' => 'g.a.12', 'info' => 'true if array contains one element and the predicted value is negative.'],
                false,
                [
                    'start' => -1,
                    'array' => ['second']
                ],
            ],
            [
                ['no' => 'g.a.13', 'info' => 'true if array is empty  and the predicted value is negative.'],
                false,
                [
                    'start' => -1,
                    'array' => []
                ],
            ],
            [
                ['no' => 'g.a.14', 'info' => 'true if array contains one element and the predicted value is greater than the count of items.'],
                false,
                [
                    'start' => 2,
                    'array' => ['second']
                ],
            ],
            [
                ['no' => 'g.a.15', 'info' => 'true if array is empty  and the predicted value is is greater than the count of items.'],
                false,
                [
                    'start' => 1,
                    'array' => []
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderParamValidateArrayWithOptionalItemContainPredictedCountOfItemsReturnExpectedBooleanIfTheArrayContainsLessEqualOrMoreItems
     * @test
     */
    public function paramValidateArrayWithOptionalItemContainPredictedCountOfItemsReturnExpectedBooleanIfTheArrayContainsLessOrEqualMoreItems($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateArrayWithOptionalItemContainPredictedCountOfItems(
                    $param['array'], $param['start']),
                $message['no'] . ': ' . $message['info']
            );
        }
    }


    public function dataProviderParamValidateSecondLevelCheckNamedValueReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes()
    {
        $regularTypeArray = array_map('trim', explode(
                RebuildBaseService::DELIMITER_COMMA,
                RebuildBaseService::SUB_PARAM_VALUE_TYPE_LISTING
            )
        );
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        foreach ($regularTypeArray as $regularTypeItem) {
            $result[] = [
                ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if the minimum of allowed ' .
                    'parameters are set in the parameters for ' . 'value-replacement.'],
                true,
                [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                    RebuildBaseService::SUB_PARAM_VALUE_TYPE => $regularTypeItem
                ],
            ];
            $result[] = [
                ['no' => 'g10-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if the minimum of allowed ' .
                    'parameters are set, but the new-item is not allowed (empty).(parameters for ' . 'value-replacement)'],
                false,
                [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => '',
                    RebuildBaseService::SUB_PARAM_VALUE_TYPE => $regularTypeItem
                ],
            ];
        }
        $result[] = [
            ['no' => 'g20' . $additionalIndexNumberForLooppedTest, 'info' => 'false if the minimum of allowed parameters are set, but the type is wrong .' .
                'in the parameters for ' . 'value-replacement'],
            false,
            [
                RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                RebuildBaseService::SUB_PARAM_VALUE_TYPE => implode('', $regularTypeArray)
            ],
        ];

        return $result;
    }

    public function dataProviderParamValidateSecondLevelCheckNamedValueReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt()
    {
        $regularTypeArray = array_map('trim', explode(
                RebuildBaseService::DELIMITER_COMMA,
                RebuildBaseService::SUB_PARAM_VALUE_TYPE_LISTING
            )
        );
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        foreach ($regularTypeArray as $regularTypeItem) {
            $result[] = [
                ['no' => 'g30-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if the minimum of allowed ' .
                    'parameters are set in the parameters for ' . 'value-replacement.'],
                false,
                [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                    RebuildBaseService::SUB_PARAM_VALUE_TYPE => $regularTypeItem,
                    'undefindeItem' => 'filled'
                ],
            ];
            $additionalIndexNumberForLooppedTest++;
        }

        return $result;
    }


    public function dataProviderParamValidateSecondLevelCheckNamedValueReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes()
    {
        $regularTypeArray = array_map('trim', explode(
                RebuildBaseService::DELIMITER_COMMA,
                RebuildBaseService::SUB_PARAM_VALUE_TYPE_LISTING
            )
        );
        $baseEntry = [
            'name' => 'needed entries',
            'allowed' => [
                RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                RebuildBaseService::SUB_PARAM_VALUE_TYPE => $regularTypeArray[0]
            ],
            'forbidden' => [
                RebuildBaseService::SUB_PARAM_VALUE_NEW => '',
                RebuildBaseService::SUB_PARAM_VALUE_TYPE => $regularTypeArray[0]
            ]
        ];
        $old = [
            'name' => 'Entry `' . RebuildBaseService::SUB_PARAM_VALUE_OLD . '´',
            'allowed' => [RebuildBaseService::SUB_PARAM_VALUE_OLD => 'filled'],
            'forbidden' => [RebuildBaseService::SUB_PARAM_VALUE_OLD => ''],
            'empty' => [],
        ];
        $maxLength = [
            'name' => 'Entry `' . RebuildBaseService::SUB_PARAM_VALUE_MAX_LENGTH . '´',
            'allowed' => [RebuildBaseService::SUB_PARAM_VALUE_MAX_LENGTH => '12'],
            'forbidden' => [RebuildBaseService::SUB_PARAM_VALUE_MAX_LENGTH => 'integerNeeded'],
            'empty' => [],
        ];
        $result = [];
        $count = 0;
        $variation = [
            'old' => '',
            'maxLength' => '',
            'baseEntry' => '',
        ];
        foreach ($old as $oldKey => $oldItem) {
            if ($oldKey === 'name') continue;
            $variation['old'] = $old['name'] . '-' . $oldKey;
            foreach ($maxLength as $maxLengthKey => $maxLengthItem) {
                if ($maxLengthKey === 'name') continue;
                $variation['maxLength'] = $maxLength['name'] . '-' . $maxLengthKey;
                foreach ($baseEntry as $baseEntryKey => $baseEntryItem) {
                    if ($baseEntryKey === 'name') continue;
                    $variation['baseEntry'] = $baseEntry['name'] . '-' . $baseEntryKey;
                    $variationList = implode(', ', $variation) . '.';
                    $result[] = [
                        [
                            'no' => 'h777-' . $count++,
                            'info' => (strpos($variationList, 'forbidden') === false ? 'True' : 'False') .
                                'is detected for the following variation of optional entries in the parameters for ' .
                                'value-replacement: ' . $variationList,
                        ],
                        (strpos($variationList, 'forbidden') === false ? true : false),
                        array_merge($baseEntryItem, $oldItem, $maxLengthItem)
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedValueReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedValueReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedValueReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes
     * @test
     */
    public function paramValidateSecondLevelCheckNamedValueReturnExpectedBooleanIfTheArrayContainsLessOrEqualMoreItems($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateSecondLevelCheckNamedValue($param),
                $message['no'] . ': ' . $message['info']
            );
        }
    }

    public function dataProviderparamValidateSecondLevelCheckNamedAttributeItemReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes()
    {
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if the minimum of allowed ' .
                'parameters are set.' . 'in the parameters for ' . 'attribute-replacement.'],
            true,
            [
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if the key-value is empty' .
                'in the parameters for ' . 'attribute-replacement.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => '',
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if the new-value is empty'
                . 'in the parameters for ' . 'attribute-replacement.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => '',
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
            ],
        ];

        return $result;
    }

    public function dataProviderparamValidateSecondLevelCheckNamedAttributeItemReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt()
    {
        $regularTypeArray = array_map('trim', explode(
                RebuildBaseService::DELIMITER_COMMA,
                RebuildBaseService::SUB_PARAM_VALUE_TYPE_LISTING
            )
        );
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if the minimum of allowed ' .
                'parameters are set ' . 'in the parameters for ' . 'attribute-replacement.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                'undefindeItem' => 'filled'
            ],
        ];

        return $result;
    }


    public function dataProviderparamValidateSecondLevelCheckNamedAttributeItemReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes()
    {
        $baseEntry = [
            'name' => 'needed entries',
            'allowed' => [
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
            ],
            'forbidden' => [
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => '',
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
            ]
        ];
        $result = [];
        $count = 0;
        $variation = [
            'baseEntry' => '',
        ];
        foreach ($baseEntry as $baseEntryKey => $baseEntryItem) {
            if ($baseEntryKey === 'name') continue;
            $variation['baseEntry'] = $baseEntry['name'] . '-' . $baseEntryKey;
            $variationList = implode(', ', $variation) . '.';
            $result[] = [
                [
                    'no' => 'h777-' . $count++,
                    'info' => (strpos($variationList, 'forbidden') === false ? 'True' : 'False') .
                        'is detected for the following variation of optional entries '
                        . 'in the parameters for ' . 'attribute-replacement: ' . $variationList,
                ],
                (strpos($variationList, 'forbidden') === false ? true : false),
                $baseEntryItem
            ];
        }

        return $result;
    }

    /**
     * @dataProvider dataProviderparamValidateSecondLevelCheckNamedAttributeItemReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes
     * @dataProvider dataProviderparamValidateSecondLevelCheckNamedAttributeItemReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt
     * @dataProvider dataProviderparamValidateSecondLevelCheckNamedAttributeItemReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes
     * @test
     */
    public function paramValidateSecondLevelCheckNamedAttributeItemReturnExpectedBooleanIfTheArrayContainsLessOrEqualMoreItems($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateSecondLevelCheckNamedAttributeItem($param),
                $message['no'] . ': ' . $message['info']
            );
        }
    }


    public function dataProviderparamValidateSecondLevelCheckNamedAttributeReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes()
    {
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if the minimum of allowed ' .
                'parameters are set.' . 'in the parameters for ' . 'attribute-replacement.'],
            true,
            [
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if the minimum of allowed ' .
                'parameters are set in a array.' . 'in the parameters for ' . 'attribute-replacement.'],
            true,
            [
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if two of the minimum of allowed ' .
                'parameters are set in a array.' . 'in the parameters for ' . 'attribute-replacement.'],
            true,
            [
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if the key-value is empty' .
                'in the parameters for ' . 'attribute-replacement.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => '',
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if in a array-set the key-value is empty' .
                'in the parameters for ' . 'attribute-replacement.'],
            false,
            [
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => '',
                ],
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if in the first set of a array-set with two elements the key-value is empty' .
                'in the parameters for ' . 'attribute-replacement.'],
            false,
            [
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => '',
                ],
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if in the second set of a array-set with two elements the key-value is empty' .
                'in the parameters for ' . 'attribute-replacement.'],
            false,
            [
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => '',
                ],
            ],
        ];

        return $result;
    }

    public function dataProviderparamValidateSecondLevelCheckNamedAttributeReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt()
    {
        $regularTypeArray = array_map('trim', explode(
                RebuildBaseService::DELIMITER_COMMA,
                RebuildBaseService::SUB_PARAM_VALUE_TYPE_LISTING
            )
        );
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'false if the minimum of allowed ' .
                'parameters are set ' . 'in the parameters for ' . 'attribute-replacement and an additional value is set'],
            false,
            [
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                'undefindeItem' => 'filled'
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if in a one item-array the minimum of allowed ' .
                'parameters are set ' . 'in the parameters for ' . 'attribute-replacement  and an additional value is set in the first element'],
            false,
            [
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                    'undefindeItem' => 'filled'
                ],
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if in a two  item-array the minimum of allowed ' .
                'parameters are set ' . 'in the parameters for ' . 'attribute-replacement  and an additional value is set in the second element'],
            false,
            [
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                    'undefindeItem' => 'filled'
                ],
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if in a two item-array the minimum of allowed ' .
                'parameters are set ' . 'in the parameters for ' . 'attribute-replacement  and in the item-array a undefined element is set'],
            false,
            [
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
                'undefindeItem' => 'filled'
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if in a two item-array the minimum of allowed ' .
                'parameters are set ' . 'in the parameters for ' . 'attribute-replacement  and in the item-array a undefined array is set'],
            false,
            [
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
                [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
                [
                    'undefindeItem' => 'filled'
                ],
            ],
        ];

        return $result;
    }


    /**
     * @dataProvider dataProviderparamValidateSecondLevelCheckNamedAttributeReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes
     * @dataProvider dataProviderparamValidateSecondLevelCheckNamedAttributeReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt
     * @test
     */
    public function paramValidateSecondLevelCheckNamedAttributeReturnExpectedBooleanIfTheArrayContainsLessOrEqualMoreItems($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateSecondLevelCheckNamedAttribute($param),
                $message['no'] . ': ' . $message['info']
            );
        }
    }


    public function dataProviderParamValidateSecondLevelCheckNamedAttributeOptionalReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes()
    {
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $result[] = [
            ['no' => 'm1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if the minimum of allowed ' .
                'parameters are set.' . 'in the parameters for ' . 'attribute-replacement.'],
            ['valid' => true, 'count' => 1],
            [
                RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES => [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
            ],
        ];
        $result[] = [
            ['no' => 'm1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if the key-value is empty' .
                'in the parameters for ' . 'attribute-replacement.'],
            ['valid' => false, 'count' => 1],
            [
                RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES => [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => '',
                ],
            ],
        ];
        $result[] = [
            ['no' => 'm1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if only the key-value is defined ' .
                'in the parameters for ' . 'attribute-replacement.'],
            ['valid' => false, 'count' => 1],
            [
                RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES => [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
            ],
        ];
        $result[] = [
            ['no' => 'm1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if only the new-value is defined ' .
                'in the parameters for ' . 'attribute-replacement.'],
            ['valid' => false, 'count' => 1],
            [
                RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES => [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                ],
            ],
        ];
        $result[] = [
            ['no' => 'm1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if an empty array ' .
                'in the parameters for ' . 'attribute-replacement.'],
            ['valid' => true, 'count' => 0],
            [
                [],
            ],
        ];
        $result[] = [
            ['no' => 'm1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if no array ' .
                'in the parameters for ' . 'attribute-replacement.'],
            ['valid' => true, 'count' => 0],
            [
                null,
            ],
        ];
        $result[] = [
            ['no' => 'm1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if the key-value is empty' .
                'in the parameters for ' . 'attribute-replacement.'],
            ['valid' => false, 'count' => 1],
            [
                RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES => [],
            ],
        ];
        $result[] = [
            ['no' => 'm1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if the new-value is empty'
                . 'in the parameters for ' . 'attribute-replacement.'],
            ['valid' => false, 'count' => 1],
            [
                RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES => [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => '',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
            ],
        ];

        return $result;
    }

    public function dataProviderParamValidateSecondLevelCheckNamedAttributeOptionalReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt()
    {
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $result[] = [
            ['no' => 'm1-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if the minimum of allowed ' .
                'parameters are set ' . 'in the parameters for ' . 'attribute-replacement.'],
            [
                'valid' => false,
                'count' => 1
            ],
            [
                RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES => [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                    'undefindeItem' => 'filled'
                ]
            ],
        ];

        return $result;
    }


    public function dataProviderParamValidateSecondLevelCheckNamedAttributeOptionalReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes()
    {
        $baseEntry = [
            'name' => 'needed entries',
            'allowed' => [
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
            ],
            'forbidden' => [
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => '',
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
            ]
        ];
        $result = [];
        $count = 0;
        $variation = [
            'baseEntry' => '',
        ];
        foreach ($baseEntry as $baseEntryKey => $baseEntryItem) {
            if ($baseEntryKey === 'name') continue;
            $baseEntryCount = (($baseEntryKey === 'allowed') ? 2 : 1);
            $variation['baseEntry'] = $baseEntry['name'] . '-' . $baseEntryKey;
            $variationList = implode(', ', $variation) . '.';
            $result[] = [
                [
                    'no' => 'h777-' . $count++,
                    'info' => (strpos($variationList, 'forbidden') === false ? 'True' : 'False') .
                        ' is detected for the following variation of optional entries '
                        . 'in the parameters for ' . 'attribute-replacement: ' . $variationList,
                ],
                [
                    'valid' => (strpos($variationList, 'forbidden') === false ? true : false),
                    'count' => 1
                ],
                [
                    RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES => $baseEntryItem,
                ]
            ];
        }

        return $result;
    }


    /**
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedAttributeOptionalReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedAttributeOptionalReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedAttributeOptionalReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes
     * @test
     */
    public function paramValidateSecondLevelCheckNamedAttributeOptionalReturnExpectedBooleanIfTheArrayContainsLessOrEqualMoreItems($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $count = 0;
            $result = $this->subject->paramValidateSecondLevelCheckNamedAttributeOptional(
                $count,
                $param,
                RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES
            );
            $this->assertSame(
                $expect['valid'],
                $result,
                $message['no'] . ': ' . $message['info']
            );
            $this->assertSame(
                $expect['count'],
                $count,
                $message['no'] . ': ' . $message['info']
            );
        }
    }


    public function dataProviderParamValidateSecondLevelCheckNamedStyleReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes()
    {
        $regularTypeArray = array_map('trim', explode(
                RebuildBaseService::DELIMITER_COMMA,
                RebuildBaseService::SUB_PARAM_STYLE_TYPE_LISTING
            )
        );
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        foreach ($regularTypeArray as $regularTypeItem) {
            $result[] = [
                ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if the minimum of allowed ' .
                    'parameters are set in the parameters for ' . 'named style-replacement.'],
                true,
                [
                    RebuildBaseService::SUB_PARAM_STYLE_NEW => 'new-filled',
                    RebuildBaseService::SUB_PARAM_STYLE_KEY => 'key-filled',
                    RebuildBaseService::SUB_PARAM_STYLE_TYPE => $regularTypeItem
                ],
            ];
            $result[] = [
                ['no' => 'g10-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if the minimum of allowed ' .
                    'parameters are set, but the new-item is not allowed (empty).(parameters for ' . 'named style-replacement)'],
                false,
                [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => '',
                    RebuildBaseService::SUB_PARAM_STYLE_KEY => 'key-filled',
                    RebuildBaseService::SUB_PARAM_STYLE_TYPE => $regularTypeItem
                ],
            ];
            $result[] = [
                ['no' => 'g10-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if the minimum of allowed ' .
                    'parameters are set, but the new-item is not allowed (empty).(parameters for ' . 'named style-replacement)'],
                false,
                [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => 'new-filled',
                    RebuildBaseService::SUB_PARAM_STYLE_KEY => '',
                    RebuildBaseService::SUB_PARAM_STYLE_TYPE => $regularTypeItem
                ],
            ];
        }
        $result[] = [
            ['no' => 'g20-' . $additionalIndexNumberForLooppedTest, 'info' => 'false if the minimum of allowed parameters are set, but the type is wrong .' .
                'in the parameters for ' . 'named style-replacement'],
            false,
            [
                RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                RebuildBaseService::SUB_PARAM_STYLE_KEY => 'key-filled',
                RebuildBaseService::SUB_PARAM_STYLE_TYPE => implode('', $regularTypeArray)
            ],
        ];

        return $result;
    }

    public function dataProviderParamValidateSecondLevelCheckNamedStyleReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt()
    {
        $regularTypeArray = array_map('trim', explode(
                RebuildBaseService::DELIMITER_COMMA,
                RebuildBaseService::SUB_PARAM_STYLE_TYPE_LISTING
            )
        );
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        foreach ($regularTypeArray as $regularTypeItem) {
            $result[] = [
                ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if the minimum of allowed ' .
                    'parameters are set in the parameters for ' . 'named style-replacement  and an additional value is set'],
                false,
                [
                    RebuildBaseService::SUB_PARAM_STYLE_KEY => 'key-filled',
                    RebuildBaseService::SUB_PARAM_STYLE_NEW => 'new-filled',
                    RebuildBaseService::SUB_PARAM_STYLE_TYPE => $regularTypeItem,
                    'undefindeItem' => 'filled'
                ],
            ];
            $additionalIndexNumberForLooppedTest++;
        }

        return $result;
    }

    public function dataProviderParamValidateSecondLevelCheckNamedStyleReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes()
    {
        $regularTypeArray = array_map('trim', explode(
                RebuildBaseService::DELIMITER_COMMA,
                RebuildBaseService::SUB_PARAM_STYLE_TYPE_LISTING
            )
        );
        $baseEntry = [
            'name' => 'needed entries',
            'allowed' => [
                RebuildBaseService::SUB_PARAM_STYLE_KEY => 'key-filled',
                RebuildBaseService::SUB_PARAM_STYLE_NEW => 'new-filled',
                RebuildBaseService::SUB_PARAM_STYLE_TYPE => $regularTypeArray[0]
            ],
            'forbidden' => [
                RebuildBaseService::SUB_PARAM_STYLE_KEY => '',
                RebuildBaseService::SUB_PARAM_STYLE_NEW => 'new-filled',
                RebuildBaseService::SUB_PARAM_STYLE_TYPE => $regularTypeArray[0]
            ]
        ];
        $result = [];
        $count = 0;
        $variation = [
            'baseEntry' => '',
        ];
        foreach ($baseEntry as $baseEntryKey => $baseEntryItem) {
            if ($baseEntryKey === 'name') continue;
            $variation['baseEntry'] = $baseEntry['name'] . '-' . $baseEntryKey;
            $variationList = implode(', ', $variation) . '.';
            $result[] = [
                [
                    'no' => 'h777-' . $count++,
                    'info' => (strpos($variationList, 'forbidden') === false ? 'True' : 'False') .
                        'is detected for the following variation of optional entries in the parameters for ' .
                        'named style-replacement: ' . $variationList,
                ],
                (strpos($variationList, 'forbidden') === false ? true : false),
                $baseEntryItem
            ];
        }

        return $result;
    }

    /**
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedStyleReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedStyleReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedStyleReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes
     * @test
     */
    public function paramValidateSecondLevelCheckNamedStyleReturnExpectedBooleanIfTheArrayContainsLessOrEqualMoreItems($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateSecondLevelCheckNamedStyle($param),
                $message['no'] . ': ' . $message['info']
            );
        }
    }


    public function dataProviderParamValidateSecondLevelCheckNamedClassReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes()
    {
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $result[] = [
            ['no' => 'j1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if at least one allowed optional ' .
                'class containig parameters is set in the parameters for ' . 'class-manipulation.'],
            true,
            [
                RebuildBaseService::SUB_PARAM_CLASS_ADD => 'filled'
//                RebuildBaseService::SUB_PARAM_CLASS_OVERRIDE => 'filled'
//                RebuildBaseService::SUB_PARAM_CLASS_REMOVE => 'filled'
            ],
        ];
        $result[] = [
            ['no' => 'j1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if at least one allowed optional ' .
                'class containig parameters is set in the parameters for ' . 'class-manipulation.'],
            true,
            [
//                RebuildBaseService::SUB_PARAM_CLASS_ADD => 'filled'
                RebuildBaseService::SUB_PARAM_CLASS_OVERRIDE => 'filled'
//                RebuildBaseService::SUB_PARAM_CLASS_REMOVE => 'filled'
            ],
        ];
        $result[] = [
            ['no' => 'j1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if at least one allowed optional ' .
                'class containig parameters is set in the parameters for ' . 'class-manipulation.'],
            true,
            [
//                RebuildBaseService::SUB_PARAM_CLASS_ADD => 'filled'
//                RebuildBaseService::SUB_PARAM_CLASS_OVERRIDE => 'filled'
                RebuildBaseService::SUB_PARAM_CLASS_REMOVE => 'filled'
            ],
        ];
        $result[] = [
            ['no' => 'j10-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if no allowed optional ' .
                'class containig parameters is set in parameters for ' . 'class-manipulation)'],
            false,
            [
//                RebuildBaseService::SUB_PARAM_CLASS_ADD => 'filled'
//                RebuildBaseService::SUB_PARAM_CLASS_OVERRIDE => 'filled'
//                RebuildBaseService::SUB_PARAM_CLASS_REMOVE => 'filled'
            ],
        ];

        return $result;
    }

    public function dataProviderParamValidateSecondLevelCheckNamedClassReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt()
    {
        $regularTypeArray = RebuildBaseService::SUB_PARAM_NODE_TYPE_LIST;
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        foreach ($regularTypeArray as $regularTypeItem) {
            $result[] = [
                ['no' => 'j1-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if the minimum of allowed ' .
                    'parameters are set in the parameters for ' . 'class-manipulation  and an additional value is set'],
                false,
                [
//                RebuildBaseService::SUB_PARAM_CLASS_ADD => 'filled'
                    RebuildBaseService::SUB_PARAM_CLASS_OVERRIDE => 'filled',
//                RebuildBaseService::SUB_PARAM_CLASS_REMOVE => 'filled'
                    'undefindeItem' => 'filled',
                ],
            ];
            $additionalIndexNumberForLooppedTest++;
        }

        return $result;
    }


    public function dataProviderParamValidateSecondLevelCheckNamedClassReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes()
    {
        $addEntry = [
            'name' => 'add class option',
            'allowed' => [RebuildBaseService::SUB_PARAM_CLASS_ADD => 'filled'],
            'forbidden' => [RebuildBaseService::SUB_PARAM_CLASS_ADD => ''],
            'empty' => [],
        ];
        $removeEntry = [
            'name' => 'remove class option',
            'allowed' => [RebuildBaseService::SUB_PARAM_CLASS_REMOVE => 'filled'],
            'forbidden' => [RebuildBaseService::SUB_PARAM_CLASS_REMOVE => ''],
            'empty' => [],
        ];
        $overrideEntry = [
            'name' => 'override class option',
            'allowed' => [RebuildBaseService::SUB_PARAM_CLASS_OVERRIDE => 'filled'],
            'forbidden' => [RebuildBaseService::SUB_PARAM_CLASS_OVERRIDE => ''],
            'empty' => [],
        ];
        $result = [];
        $count = 0;
        $variation = [
            'add' => '',
            'remove' => '',
            'override' => '',
            'baseEntry' => '',
        ];
        foreach ($addEntry as $addKey => $addItem) {
            if ($addKey === 'name') continue;
            $variation['add'] = $addEntry['name'] . '-' . $addKey;
            foreach ($removeEntry as $removeKey => $removeItem) {
                if ($removeKey === 'name') continue;
                $variation['remove'] = $removeEntry['name'] . '-' . $removeKey;
                foreach ($overrideEntry as $overrideKey => $overrideItem) {
                    if ($overrideKey === 'name') continue;
                    if (($addKey === 'empty') && ($removeKey === 'empty') && ($overrideKey === 'empty')) continue;
                    $variation['override'] = $overrideEntry['name'] . '-' . $overrideKey;
                    $variationList = implode(', ', $variation) . '.';
                    $result[] = [
                        [
                            'no' => 'j777-' . $count++,
                            'info' => (strpos($variationList, 'forbidden') === false ? 'True' : 'False') .
                                'is detected for the following variation of optional entries in the parameters for ' .
                                'class-manipulation: ' . $variationList,
                        ],
                        (strpos($variationList, 'forbidden') === false ? true : false),
                        array_merge($addItem, $removeItem, $overrideItem)
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedClassReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedClassReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedClassReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes
     * @test
     */
    public function paramValidateSecondLevelCheckNamedClassReturnExpectedBooleanIfTheArrayContainsLessOrEqualMoreItems($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateSecondLevelCheckNamedClass($param),
                $message['no'] . ': ' . $message['info']
            );
        }
    }

    public function dataProviderParamValidateSecondLevelCheckNamedChildReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes()
    {
        $regularTypeArray = RebuildBaseService::SUB_PARAM_NODE_TYPE_LIST;;
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        foreach ($regularTypeArray as $regularTypeItem) {
            $result[] = [
                ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if the minimum of allowed ' .
                    'parameters are set in the parameters for ' . 'child-addition.'],
                true,
                [
                    RebuildBaseService::SUB_PARAM_CHILD_XML => '<xml-filled />',
                    RebuildBaseService::SUB_PARAM_CHILD_TYPE => $regularTypeItem
                ],
            ];
            $result[] = [
                ['no' => 'g10-' . $additionalIndexNumberForLooppedTest++, 'info' => 'false if the minimum of allowed ' .
                    'parameters are set, but the new-item is not allowed (empty).(parameters for ' . 'child-addition)'],
                false,
                [
                    RebuildBaseService::SUB_PARAM_CHILD_XML => '',
                    RebuildBaseService::SUB_PARAM_CHILD_TYPE => $regularTypeItem
                ],
            ];
        }
        $result[] = [
            ['no' => 'g20-' . $additionalIndexNumberForLooppedTest, 'info' => 'false if the minimum of allowed parameters are set, but the type is wrong .' .
                'in the parameters for ' . 'child-addition'],
            false,
            [
                RebuildBaseService::SUB_PARAM_CHILD_XML => '<xml-filled />',
                RebuildBaseService::SUB_PARAM_CHILD_TYPE => implode('', $regularTypeArray)
            ],
        ];

        return $result;
    }

    public function dataProviderParamValidateSecondLevelCheckNamedChildReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt()
    {
        $regularTypeArray = RebuildBaseService::SUB_PARAM_NODE_TYPE_LIST;
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        foreach ($regularTypeArray as $regularTypeItem) {
            $result[] = [
                ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if the minimum of allowed ' .
                    'parameters are set in the parameters for ' . 'child-addition.'],
                false,
                [
                    RebuildBaseService::SUB_PARAM_CHILD_XML => '<xml-filled />',
                    RebuildBaseService::SUB_PARAM_CHILD_TYPE => $regularTypeItem,
                    'undefindeItem' => 'filled'
                ],
            ];
            $additionalIndexNumberForLooppedTest++;
        }

        return $result;
    }


    public function dataProviderParamValidateSecondLevelCheckNamedChildReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes()
    {
        $regularTypeArray = RebuildBaseService::SUB_PARAM_NODE_TYPE_LIST;;
        $baseEntry = [
            'name' => 'needed entries',
            'allowed' => [
                RebuildBaseService::SUB_PARAM_CHILD_XML => '<xml-filled>Hallo </xml-filled>',
                RebuildBaseService::SUB_PARAM_CHILD_TYPE => $regularTypeArray[0]
            ],
            'forbidden' => [
                RebuildBaseService::SUB_PARAM_CHILD_XML => '',
                RebuildBaseService::SUB_PARAM_CHILD_TYPE => $regularTypeArray[0]
            ]
        ];
        $result = [];
        $count = 0;
        $variation = [
            'baseEntry' => '',
        ];
            foreach ($baseEntry as $baseEntryKey => $baseEntryItem) {
                if ($baseEntryKey === 'name') continue;
                $variation['baseEntry'] = $baseEntry['name'] . '-' . $baseEntryKey;
                $variationList = implode(', ', $variation) . '.';
                $result[] = [
                    [
                        'no' => 'h777-' . $count++,
                        'info' => (strpos($variationList, 'forbidden') === false ? 'True' : 'False') .
                            'is detected for the following variation of optional entries in the parameters for ' .
                            'child-addition: ' . $variationList,
                    ],
                    (strpos($variationList, 'forbidden') === false ? true : false),
                    array_merge($baseEntryItem, [])
                ];
        }

        return $result;
    }


    /**
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedChildReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedChildReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedChildReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt
     * @test
     */
    public function paramValidateSecondLevelCheckNamedChildReturnExpectedBooleanIfTheArrayContainsLessOrEqualMoreItems($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateSecondLevelCheckNamedChild($param),
                $message['no'] . ': ' . $message['info']
            );
        }
    }


    public function dataProviderParamValidateSecondLevelCheckNamedUseReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes()
    {
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if the minimum of allowed ' .
                'parameters are set.' . 'in the parameters for ' . 'setting of the use-tag.'],
            true,
            [
                RebuildBaseService::SUB_PARAM_USE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_USE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'false if the href-value is empty' .
                'in the parameters for ' . 'setting of the use-tag.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_USE_HREF => '',
                RebuildBaseService::SUB_PARAM_USE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'false if the type-value is empty' .
                'in the parameters for ' . 'setting of the use-tag.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_USE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_USE_TYPE => '',
            ],
        ];

        return $result;
    }

    public function dataProviderParamValidateSecondLevelCheckNamedUseReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt()
    {
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if the minimum of allowed ' .
                'parameters are set ' . 'in the parameters for ' . 'setting of the use-tag.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_USE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_USE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
                'undefindeItem' => 'filled'
            ],
        ];

        return $result;
    }

    public function dataProviderParamValidateSecondLevelCheckNamedUseReturnExpectedBooleanIfTheUseTypesAreVariated()
    {
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $listOfTypes = RebuildBaseService::SUB_PARAM_NODE_TYPE_LIST;
        $noListOfType = implode('', $listOfTypes);
        foreach ($listOfTypes as $listType) {
            $result[] = [
                ['no' => 'lmaa-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if the allowed type `' .
                    $listType . '´ is set ' . 'in the parameters for ' . 'setting of the use-tag.'],
                true,
                [
                    RebuildBaseService::SUB_PARAM_USE_HREF => 'new filled',
                    RebuildBaseService::SUB_PARAM_USE_TYPE => $listType
                ],
            ];

        }
        $result[] = [
            ['no' => 'lmaa-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if the minimum of allowed ' .
                'parameters are set ' . 'in the parameters for ' . 'setting of the use-tag.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_USE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_USE_TYPE => $noListOfType
            ],
        ];

        return $result;
    }

    public function dataProviderParamValidateSecondLevelCheckNamedUseReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes()
    {
        $baseEntry = [
            'name' => 'needed entries',
            'allowed' => [
                RebuildBaseService::SUB_PARAM_USE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_USE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
            ],
            'forbidden' => [
                RebuildBaseService::SUB_PARAM_USE_HREF => '',
                RebuildBaseService::SUB_PARAM_USE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
            ]
        ];
        $attributesEntry = [
            'name' => 'Entry `' . RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES . '´',
            'allowed' => [
                RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES => [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
            ],
            'forbidden' => [
                RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES => [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => '',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
            ],
            'empty' => [],
        ];
        $result = [];
        $count = 0;
        $variation = [
            'baseEntry' => '',
            'attributes' => '',
        ];
        foreach ($attributesEntry as $attributesKey => $attributesItem) {
            if ($attributesKey === 'name') continue;
            $variation['attributes'] = $attributesEntry['name'] . '-' . $attributesKey;
                foreach ($baseEntry as $baseEntryKey => $baseEntryItem) {
                    if ($baseEntryKey === 'name') continue;
                    $variation['baseEntry'] = $baseEntry['name'] . '-' . $baseEntryKey;
                    $variationList = implode(', ', $variation) . '.';
                    $result[] = [
                        [
                            'no' => 'x777-' . $count++,
                            'info' => (strpos($variationList, 'forbidden') === false ? 'True' : 'False') .
                                'is detected for the following variation of optional entries '
                                . 'in the parameters for ' . 'setting of the use-tag: ' . $variationList,
                        ],
                        (strpos($variationList, 'forbidden') === false ? true : false),
                        array_merge($baseEntryItem, [], $attributesItem)
                    ];
                }
        }

        return $result;
    }

    /**
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedUseReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedUseReturnExpectedBooleanIfTheUseTypesAreVariated
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedUseReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedUseReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes
     * @test
     */
    public function paramValidateSecondLevelCheckNamedUseReturnExpectedBooleanIfTheArrayContainsLessOrEqualMoreItems($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateSecondLevelCheckNamedUse($param),
                $message['no'] . ': ' . $message['info']
            );
        }
    }


    public function dataProviderParamValidateSecondLevelCheckNamedImageReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes()
    {
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if the minimum of allowed ' .
                'parameters are set.' . 'in the parameters for ' . 'setting of the image-tag.'],
            true,
            [
                RebuildBaseService::SUB_PARAM_IMAGE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_IMAGE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'false if the href-value is empty' .
                'in the parameters for ' . 'setting of the image-tag.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_IMAGE_HREF => '',
                RebuildBaseService::SUB_PARAM_IMAGE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
            ],
        ];
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'false if the type-value is empty' .
                'in the parameters for ' . 'setting of the image-tag.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_IMAGE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_IMAGE_TYPE => '',
            ],
        ];

        return $result;
    }

    public function dataProviderParamValidateSecondLevelCheckNamedImageReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt()
    {
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $result[] = [
            ['no' => 'g1-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if the minimum of allowed ' .
                'parameters are set ' . 'in the parameters for ' . 'setting of the image-tag.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_IMAGE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_IMAGE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
                'undefindeItem' => 'filled'
            ],
        ];

        return $result;
    }


    public function dataProviderParamValidateSecondLevelCheckNamedImageReturnExpectedBooleanIfTheImageTypesAreVariated()
    {
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $listOfTypes = RebuildBaseService::SUB_PARAM_NODE_TYPE_LIST;
        $noListOfType = implode('', $listOfTypes);
        foreach ($listOfTypes as $listType) {
            $result[] = [
                ['no' => 'lmaa-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if the allowed type `' .
                    $listType . '´ is set ' . 'in the parameters for ' . 'setting of the image-tag.'],
                true,
                [
                    RebuildBaseService::SUB_PARAM_IMAGE_HREF => 'new filled',
                    RebuildBaseService::SUB_PARAM_IMAGE_TYPE => $listType
                ],
            ];

        }
        $result[] = [
            ['no' => 'lmaa-' . $additionalIndexNumberForLooppedTest, 'info' => 'true if the minimum of allowed ' .
                'parameters are set ' . 'in the parameters for ' . 'setting of the image-tag.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_IMAGE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_IMAGE_TYPE => $noListOfType
            ],
        ];

        return $result;
    }

    public function dataProviderParamValidateSecondLevelCheckNamedImageReturnExpectedBooleanIfTheOptionalPreserveAspectRatioIsVariated()
    {
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $listOfTypes = array_filter(explode(',', RebuildBaseService::SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO_LISTING));
        $noListOfType = implode('', $listOfTypes);
        $result[] = [
            ['no' => 'mmaa-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if no definition of preserveAspectRatio `' .
                ' is set ' . 'in the parameters for ' . 'setting of the image-tag.'],
            true,
            [
                RebuildBaseService::SUB_PARAM_IMAGE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_IMAGE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_APPEND
            ],
        ];
        foreach ($listOfTypes as $listType) {
            $result[] = [
                ['no' => 'mmaa-' . $additionalIndexNumberForLooppedTest++, 'info' => 'true if the allowed type  `' .
                    $listType . '´ is set for preserveAspectratio ' . 'in the parameters for ' . 'setting of the image-tag.'],
                true,
                [
                    RebuildBaseService::SUB_PARAM_IMAGE_HREF => 'new filled',
                    RebuildBaseService::SUB_PARAM_IMAGE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_APPEND,
                    RebuildBaseService::SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO => $listType
                ],
            ];

        }
        $result[] = [
            ['no' => 'mmaa-' . $additionalIndexNumberForLooppedTest, 'info' => 'false if an unallowed ' .
                'parameters is set for preserveAspectratio ' . 'in the parameters for ' . 'setting of the image-tag.'],
            false,
            [
                RebuildBaseService::SUB_PARAM_IMAGE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_IMAGE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_APPEND,
                RebuildBaseService::SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO => $noListOfType
            ],
        ];
        $result[] = [
            ['no' => 'mmaa-' . $additionalIndexNumberForLooppedTest, 'info' => 'false; if the count of list mebers ' .
                count($listOfTypes) . 'differe from the class-constant (hard coded test-check). (' .
                RebuildBaseService::SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO_LISTING_COUNT . ') - attention: inverse-Test Expected ist variated! '],
            (count($listOfTypes) === RebuildBaseService::SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO_LISTING_COUNT),
            [
                RebuildBaseService::SUB_PARAM_IMAGE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_IMAGE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_APPEND,
            ],
        ];

        return $result;
    }


    public function dataProviderParamValidateSecondLevelCheckNamedImageReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes()
    {
        $baseEntry = [
            'name' => 'needed entries',
            'allowed' => [
                RebuildBaseService::SUB_PARAM_IMAGE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_IMAGE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
            ],
            'forbidden' => [
                RebuildBaseService::SUB_PARAM_IMAGE_HREF => '',
                RebuildBaseService::SUB_PARAM_IMAGE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
            ]
        ];
        $externalResourcesRequiredEntry = [
            'name' => 'Entry `' . RebuildBaseService::SUB_PARAM_IMAGE_EXTERNAL_RESOURCES_REQUIRED . '´',
            'allowed' => [RebuildBaseService::SUB_PARAM_IMAGE_EXTERNAL_RESOURCES_REQUIRED => 'true'],
            'forbidden' => [RebuildBaseService::SUB_PARAM_IMAGE_EXTERNAL_RESOURCES_REQUIRED => ''],
            'empty' => [],
        ];
        $attributesEntry = [
            'name' => 'Entry `' . RebuildBaseService::SUB_PARAM_IMAGE_ATTRIBUTES . '´',
            'allowed' => [
                RebuildBaseService::SUB_PARAM_IMAGE_ATTRIBUTES => [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
            ],
            'forbidden' => [
                RebuildBaseService::SUB_PARAM_IMAGE_ATTRIBUTES => [
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => '',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
                ],
            ],
            'empty' => [],
        ];
        $result = [];
        $count = 0;
        $variation = [
            'externalResourcesRequired' => '',
            'baseEntry' => '',
            'attributes' => '',
        ];
        foreach ($attributesEntry as $attributesKey => $attributesItem) {
            if ($attributesKey === 'name') continue;
            $variation['attributes'] = $attributesEntry['name'] . '-' . $attributesKey;
            foreach ($externalResourcesRequiredEntry as $externalResourcesRequiredKey => $externalResourcesRequiredItem) {
                if ($externalResourcesRequiredKey === 'name') continue;
                $variation['externalResourcesRequired'] = $externalResourcesRequiredEntry['name'] . '-' . $externalResourcesRequiredKey;
                    foreach ($baseEntry as $baseEntryKey => $baseEntryItem) {
                        if ($baseEntryKey === 'name') continue;
                        $variation['baseEntry'] = $baseEntry['name'] . '-' . $baseEntryKey;
                        $variationList = implode(', ', $variation) . '.';
                        $result[] = [
                            [
                                'no' => 'x777-' . $count++,
                                'info' => (strpos($variationList, 'forbidden') === false ? 'True' : 'False') .
                                    'is detected for the following variation of optional entries '
                                    . 'in the parameters for ' . 'setting of the image-tag: ' . $variationList,
                            ],
                            (strpos($variationList, 'forbidden') === false ? true : false),
                            array_merge($baseEntryItem, [], $externalResourcesRequiredItem,
                                $attributesItem)
                        ];
                    }
            }
        }

        return $result;
    }

    /**
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedImageReturnExpectedBooleanIfTheArrayContainsNoOptionalAttributes
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedImageReturnExpectedBooleanIfTheImageTypesAreVariated
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedImageReturnExpectedBooleanIfTheOptionalPreserveAspectRatioIsVariated
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedImageReturnExpectedBooleanIfTheArrayContainsAdditionallyAnUndefinedObjekt
     * @dataProvider dataProviderParamValidateSecondLevelCheckNamedImageReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes
     * @test
     */
    public function paramValidateSecondLevelCheckNamedImageReturnExpectedBooleanIfTheArrayContainsLessOrEqualMoreItems($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateSecondLevelCheckNamedImage($param),
                $message['no'] . ': ' . $message['info']
            );
        }
    }

    public function dataProviderParamValidateSecondLevelCheckSwitchToNamedMethodReturnExpectedBooleanIfTheTwoNeededParamsMayBeDeliveredAnd()
    {
        $result = [];
        $result[] = [
            ['no' => 'j1-1', 'info' => 'true if the minimum of allowed parameters are set.' .
                'The valid param-Array for an existing method is defined.'],
            true,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => 'hallo',
                ],
                'method' => 'value',
            ],
        ];
        $result[] = [
            ['no' => 'j1-1', 'info' => 'true if the minimum of allowed parameters plus additional parameter are set.' .
                'The valid param-Array for an existing method is defined.'],
            true,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => 'hallo',
                    RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_PREPEND
                ],
                'method' => 'value',
            ],
        ];
        $result[] = [
            ['no' => 'j1-1', 'info' => 'false if the minimum of allowed parameters is not set.' .
                'The params contains a string and not an array.'],
            false,
            [
                'param' => RebuildBaseService::SUB_PARAM_VALUE_NEW,
                'method' => 'value',
            ],
        ];
        $result[] = [
            ['no' => 'j1-1', 'info' => 'false if the minimum of allowed parameters is not set.' .
                'The params contains a array, which is not allowed for that method, because a neede param is m issing.'],
            false,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_PREPEND
                ],
                'method' => 'value',
            ],
        ];
        $result[] = [
            ['no' => 'j1-1', 'info' => 'false if the minimum of allowed parameters is not set.' .
                'The methode does not exist.'],
            false,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => 'hallo',
                    RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_PREPEND
                ],
                'method' => implode('', RebuildBaseService::SUB_PARAM_ARRAY),
            ],
        ];

        return $result;
    }

    public function dataProviderParamValidateSecondLevelCheckSwitchToNamedMethodReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes()
    {
        $result = [];
        $generalList = RebuildBaseService::SUB_PARAM_ARRAY;

        $method = RebuildBaseService::SUB_PARAM_VALUE;
        $generalList = array_diff($generalList, [$method]);
        $result[] = [
            ['no' => 'k1-1', 'info' => 'true if a set of allowed parameters is set for `' . $method . '`.' .
                'The valid param-Array for an existing method is defined.'],
            true,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => 'hallo',
                    RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_PREPEND
                ],
                'method' => $method,
            ],
        ];
        $result[] = [
            ['no' => 'k1-2', 'info' => 'false, if a needed parameters is not set for `' . $method . '`.'],
            false,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_PREPEND
                ],
                'method' => $method,
            ],
        ];

        $method = RebuildBaseService::SUB_PARAM_USE;
        $generalList = array_diff($generalList, [$method]);
        $result[] = [
            ['no' => 'k1-1', 'info' => 'true if a set of allowed parameters is set  for `' . $method . '`.' .
                'The valid param-Array for an existing method is defined.'],
            true,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_USE_HREF => 'hallo',
                    RebuildBaseService::SUB_PARAM_USE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER
                ],
                'method' => $method,
            ],
        ];
        $result[] = [
            ['no' => 'k1-12', 'info' => 'false, if a needed parameters is not set for `' . $method . '`.'],
            false,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_USE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER
                ],
                'method' => $method,
            ],
        ];

        $method = RebuildBaseService::SUB_PARAM_STYLE;
        $generalList = array_diff($generalList, [$method]);
        $result[] = [
            ['no' => 'k1-1', 'info' => 'true if a set of allowed parameters is set  for `' . $method . '`.' .
                'The valid param-Array for an existing method is defined.'],
            true,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => '#33ff55',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'background-color'
                ],
                'method' => $method,
            ],
        ];
        $result[] = [
            ['no' => 'k1-12', 'info' => 'false, if one needed parameters is not set for `' . $method . '`.'],
            false,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => '#33ff55',
                ],
                'method' => $method,
            ],
        ];

        $method = RebuildBaseService::SUB_PARAM_IMAGE;
        $generalList = array_diff($generalList, [$method]);
        $result[] = [
            ['no' => 'k1-1', 'info' => 'true if a set of allowed parameters is set  for `' . $method . '`.' .
                'The valid param-Array for an existing method is defined.'],
            true,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_USE_HREF => 'hallo',
                ],
                'method' => $method,
            ],
        ];
        $result[] = [
            ['no' => 'k1-12', 'info' => 'false, if the needed href parameters is not set for `' . $method . '`.'],
            false,
            [
                'param' => [
                ],
                'method' => $method,
            ],
        ];


        $method = RebuildBaseService::SUB_PARAM_CLASS;
        $generalList = array_diff($generalList, [$method]);
        $result[] = [
            ['no' => 'k1-1', 'info' => 'true if a set of allowed parameters is set  for `' . $method . '`.' .
                'The valid param-Array for an existing method is defined.'],
            true,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_CLASS_ADD => 'hallo walter',
                ],
                'method' => $method,
            ],
        ];
        $result[] = [
            ['no' => 'k1-12', 'info' => 'false, if the needed href parameters is not set for `' . $method . '`.'],
            false,
            [
                'param' => [
                ],
                'method' => $method,
            ],
        ];

        $method = RebuildBaseService::SUB_PARAM_CHILD;
        $generalList = array_diff($generalList, [$method]);
        $result[] = [
            ['no' => 'k1-1', 'info' => 'true if a set of allowed parameters is set  for `' . $method . '`.' .
                'The valid param-Array for an existing method is defined.'],
            true,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_CHILD_XML => '<g><circle r="12px" cx="12" cy="12" /></g>',
                    RebuildBaseService::SUB_PARAM_CHILD_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
                ],
                'method' => $method,
            ],
        ];
        $result[] = [
            ['no' => 'k1-12', 'info' => 'false, if one (xml) of the needed parameters is not set for `' . $method . '`.'],
            false,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_CHILD_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
                ],
                'method' => $method,
            ],
        ];

        $method = RebuildBaseService::SUB_PARAM_ATTRIBUTE;
        $generalList = array_diff($generalList, [$method]);
        $result[] = [
            ['no' => 'k1-1', 'info' => 'true if a set of allowed parameters is set  for `' . $method . '`.' .
                'The valid param-Array for an existing method is defined.'],
            true,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => 'HalloBert',
                    RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'HalloWalter',
                ],
                'method' => $method,
            ],
        ];
        $result[] = [
            ['no' => 'k1-12', 'info' => 'false, if one (xml) of the needed parameters is not set for `' . $method . '`.'],
            false,
            [
                'param' => [
                    RebuildBaseService::SUB_PARAM_VALUE_NEW => 'HalloBert',
                ],
                'method' => $method,
            ],
        ];

        if (count($generalList) > 0) {
            $result[] = [
                ['no' => 'xx-1', 'info' => 'true The general list of tested method (' .
                    implode('; ', $generalList) . ') must empty. This test shows, that there is a mistake'],
                true,
                [
                    'param' => [
                        RebuildBaseService::SUB_PARAM_VALUE_NEW => 'hallo',
                        RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_PREPEND
                    ],
                    'method' => implode('', RebuildBaseService::SUB_PARAM_ARRAY),
                ],
            ];

        }
        return $result;
    }


    /**
     * @dataProvider dataProviderParamValidateSecondLevelCheckSwitchToNamedMethodReturnExpectedBooleanIfTheTwoNeededParamsMayBeDeliveredAnd
     * @dataProvider dataProviderParamValidateSecondLevelCheckSwitchToNamedMethodReturnExpectedBooleanIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes
     * @test
     */
    public function paramValidateSecondLevelCheckSwitchToNamedMethodReturnExpectedBooleanIfTheArrayContainsLessOrEqualMoreItems($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateSecondLevelCheckSwitchToNamedMethod($param ['param'], $param ['method']),
                $message['no'] . ': ' . $message['info']
            );
        }
    }

    public function dataProviderParamValidateSecondLevelCheckReturnExpectedBooleanIfTheTwoNeededParamsMayBeDeliveredOrNot()
    {
        $methodName = 'value';
        $methodNameWithSpace = '  value  ';
        $unallowedMethodName = 'unallowed' . 'value';


        $result = [];
        $result[] = [
            ['no' => 'r1-1', 'info' => 'true if a allowed method-name is used and if a allowed array is defined with ' .
                'an array-subset indexed by the method-name.'],
            true,
            [
                'param' => [
                    $methodName => [RebuildBaseService::SUB_PARAM_VALUE_NEW => 'hallo'],
                ],
                'method' => $methodName,
            ],
        ];
        $result[] = [
            ['no' => 'r1-1', 'info' => 'true if a allowed untrimed method-name is used and if a allowed array is defined with ' .
                'an array-subset indexed by the method-name.'],
            true,
            [
                'param' => [
                    $methodName => [RebuildBaseService::SUB_PARAM_VALUE_NEW => 'hallo'],
                ],
                'method' => $methodNameWithSpace,
            ],
        ];
        $result[] = [
            ['no' => 'r1-1', 'info' => 'true if a unallowed method-name is used and if a allowed array is defined with ' .
                'an array-subset indexed by the method-name.'],
            false,
            [
                'param' => [
                    $methodName => [RebuildBaseService::SUB_PARAM_VALUE_NEW => 'hallo'],
                ],
                'method' => $unallowedMethodName,
            ],
        ];
        $result[] = [
            ['no' => 'r1-1', 'info' => 'true if a empty method-name is used and if a allowed array is defined with ' .
                'an array-subset indexed by the method-name.'],
            false,
            [
                'param' => [
                    $methodName => [RebuildBaseService::SUB_PARAM_VALUE_NEW => 'hallo'],
                ],
                'method' => '',
            ],
        ];
        $result[] = [
            ['no' => 'r1-1', 'info' => 'true if a allowed method-name is used and if a empty unallowed array is defined with ' .
                'an array-subset indexed by the method-name.'],
            false,
            [
                'param' => [
                    $methodName => [],
                ],
                'method' => $methodName,
            ],
        ];
        $result[] = [
            ['no' => 'r1-1', 'info' => 'true if a allowed method-name is used and if a unallowed string is defined with ' .
                'an array-subset indexed by the method-name.'],
            false,
            [
                'param' => [
                    $methodName => 'Strings are unallowed here',
                ],
                'method' => $methodName,
            ],
        ];
        $result[] = [
            ['no' => 'r1-1', 'info' => 'true if a unallowed method-name is used and if a allowed array is defined with ' .
                'an array-subset indexed by the method-name.'],
            false,
            [
                'param' => [
                    $unallowedMethodName => [RebuildBaseService::SUB_PARAM_VALUE_NEW => 'hallo'],
                ],
                'method' => $methodName,
            ],
        ];

        return $result;
    }

    /**
     * @dataProvider dataProviderParamValidateSecondLevelCheckReturnExpectedBooleanIfTheTwoNeededParamsMayBeDeliveredOrNot
     * @test
     */
    public function paramValidateSecondLevelCheckReturnExpectedBooleanIfParamsAreValidOrNot($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateSecondLevelCheck($param ['param'], $param ['method']),
                $message['no'] . ': ' . $message['info']
            );
        }
    }

    public function dataProviderParamValidateOneOfSecondLevelOfRebuildReturnExpectedBooleanIfTheTwoNeededParamsMayBeDeliveredOrNot()
    {
        $methodNameArray = RebuildBaseService::SUB_PARAM_ARRAY;
        $secondNameArray = RebuildBaseService::SUB_PARAM_ARRAY;
        $paramOkay = [];
        $paramOkay['attribute'] = [
            'xpath' => '//*[exmaple]',
            'repeatMax' => '5',
            'attribute' => [
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_NEW => 'new filled',
                RebuildBaseService::SUB_PARAM_ATTRIBUTE_KEY => 'key filled',
            ],
        ];
        $paramOkay['child'] = [
            'xpath' => '//*[exmaple]',
            'repeatMax' => '5',
            'child' => [
                RebuildBaseService::SUB_PARAM_CHILD_XML => '<xml-filled />',
                RebuildBaseService::SUB_PARAM_CHILD_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
            ],
        ];
        $paramOkay['class'] = [
            'xpath' => '//*[exmaple]',
            'repeatMax' => '5',
            'class' => [
                RebuildBaseService::SUB_PARAM_CLASS_ADD => 'filled'
            ],
        ];
        $paramOkay['image'] = [
            'xpath' => '//*[exmaple]',
            'repeatMax' => '5',
            'image' => [
                RebuildBaseService::SUB_PARAM_IMAGE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_IMAGE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
            ],
        ];
        $paramOkay['style'] = [
            'xpath' => '//*[exmaple]',
            'repeatMax' => '5',
            'style' => [
                RebuildBaseService::SUB_PARAM_STYLE_KEY => 'key-filled',
                RebuildBaseService::SUB_PARAM_STYLE_NEW => 'new-filled',
                RebuildBaseService::SUB_PARAM_STYLE_TYPE => RebuildBaseService::SUB_PARAM_STYLE_TYPUS_ADD,
            ],
        ];
        $paramOkay['use'] = [
            'xpath' => '//*[exmaple]',
            'repeatMax' => '5',
            'use' => [
                RebuildBaseService::SUB_PARAM_USE_HREF => 'new filled',
                RebuildBaseService::SUB_PARAM_USE_TYPE => RebuildBaseService::SUB_PARAM_NODE_TYPUS_AFTER,
            ],
        ];
        $paramOkay['value'] = [
            'xpath' => '//*[exmaple]',
            'repeatMax' => '5',
            'value' => [
                RebuildBaseService::SUB_PARAM_VALUE_NEW => 'hallo',
                RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_PREPEND
            ],
        ];
        $result = [];
        foreach ($methodNameArray as $methodName) {
            $unallowedMethodName = 'unallowed' . $methodName;

            $result[] = [
                ['no' => 's ' . $methodName . '-1', 'info' => 'true if a allowed method-name ' . $methodName .
                    'is used and if a allowed array is defined with ' .
                    'an array-subset indexed by the method-name. The list can Contain '],
                true,
                [
                    'param' => $paramOkay[$methodName],
                ],
            ];
            $result[] = [
                ['no' => 's ' . $methodName . '-2', 'info' => 'true if a allowed method-name ' . $methodName .
                    'is used and if a empty unallowed array is defined with ' .
                    'an array-subset indexed by the method-name.'],
                false,
                [
                    'param' => [
                        $methodName => [],
                    ],
                ],
            ];
            $result[] = [
                ['no' => 's ' . $methodName . '-3', 'info' => 'true if a allowed method-name ' . $methodName .
                    'is used and if a unallowed string is defined with ' .
                    'an array-subset indexed by the method-name.'],
                false,
                [
                    'param' => [
                        $methodName => 'Strings are unallowed here',
                    ],
                ],
            ];
            $result[] = [
                ['no' => 's ' . $methodName . '-4', 'info' => 'true if a unallowed method-name ' . $methodName .
                    'is used and if a allowed array is defined with ' .
                    'an array-subset indexed by the method-name.'],
                false,
                [
                    'param' => [
                        $unallowedMethodName => $paramOkay[$methodName][$methodName],
                    ],
                ],
            ];
        }
        return $result;
    }

    /**
     * @dataProvider dataProviderParamValidateOneOfSecondLevelOfRebuildReturnExpectedBooleanIfTheTwoNeededParamsMayBeDeliveredOrNot
     * @test
     */
    public function paramValidateOneOfSecondLevelOfRebuildReturnExpectedBooleanIfParamsAreValidOrNot($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidateOneOfSecondLevelOfRebuild($param ['param']),
                $message['no'] . ': ' . $message['info']
            );
        }
    }

    public function dataProviderParamValidateSecondLevelOfRebuildReturnExpectedBooleanIfTheTwoNeededParamsMayBeDeliveredOrNot()
    {
        $result = [];
        $result[] = [
            ['no' => 't-1', 'info' => 'false if the param-Array is a string.'],
            ['flag' => false, 'count' => 14],
            [
                'count' => 14,
                'param' => 'Strings are unallowed here',
            ],
        ];
        $result = [];
        $result[] = [
            ['no' => 't-1.b', 'info' => 'false if the param-Array is a string.'],
            ['flag' => false, 'count' => 14],
            [
                'count' => 14,
                'param' => null,
            ],
        ];
        $result = [];
        $result[] = [
            ['no' => 't-1.c', 'info' => 'false if the param-Array is empty.'],
            ['flag' => false, 'count' => 14],
            [
                'count' => 14,
                'param' => [],
            ],
        ];
        $result[] = [
            ['no' => 't-2', 'info' => 'false if the param-Array is not empty. (Count changed)'],
            ['flag' => false, 'count' => 15],
            [
                'count' => 14,
                'param' => ['hallo' => 'super aber falsch'],
            ],
        ];
        $result[] = [
            ['no' => 't-2', 'info' => 'false if the param-Array contains a subarray. (Count changed)'],
            ['flag' => false, 'count' => 15],
            [
                'count' => 14,
                'param' => ['keyNotImportant' => ['hallo' => 'super aber falsch'],],
//                'param' => [RebuildParamValidateService::SUB_PARAM_X => 'super aber falsch'],
            ],
        ];
        $result[] = [
            ['no' => 't-2.b', 'info' => 'false if the param-Array contains a subarray with an empty entry for xPath.'],
            ['flag' => false, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyNotImportant' => [RebuildParamValidateService::ITEM_X_PATH => ''],
                ],
            ],
        ];
        $result[] = [
            ['no' => 't-2.b.I', 'info' => 'false if the param-Array contains a subarray with an filled & unvalidated entry for xPath.'],
            ['flag' => false, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyNotImportant' => [RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated'],
                ],
            ],
        ];
        $result[] = [
            ['no' => 't-2.b.II', 'info' => 'false if the param-Array contains a subarray with an filled & unvalidated entry for xPath. with additional max_repeat'],
            ['flag' => false, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyNotImportant' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::ITEM_REPEAT_MAX => '5',
                    ],
                ],
            ],
        ];
        $result[] = [
            ['no' => 't-2.b.III', 'info' => 'false if the param-Array contains a subarray with an filled & unvalidated ' .
                'entry for xPath. with additional max_repeat and not defined/allowed key-entry-pair'],
            ['flag' => false, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyFirst' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::ITEM_REPEAT_MAX => '5',
                        'unallowedKey' => 'jkadfhjadsf',
                    ],
                ],
            ],
        ];

        $result[] = [
            ['no' => 't-10.', 'info' => 'true if the param-Array contains one item with a xpath-definition and ' .
                'for exapmle an allowed value-parameter-array.'],
            ['flag' => true, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyFirst' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                ],
            ],
        ];
        $result[] = [
            ['no' => 't-11.', 'info' => 'true if the param-Array contains one item with a xpath-definition and ' .
                'for exapmle an allowed value-parameter-arraya and an addition max-repeat-parameter.'],
            ['flag' => true, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyFirst' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::ITEM_REPEAT_MAX => '5',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                ],
            ],
        ];
        $result[] = [
            ['no' => 't-12.', 'info' => 'true if the param-Array contains two items with a xpath-definition and ' .
                'for exapmle an allowed value-parameter-array.'],
            ['flag' => true, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyFirst' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                    'keySecond' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                ],
            ],
        ];
        $result[] = [
            ['no' => 't-13.', 'info' => 'true if the param-Array contains two items with a xpath-definition and ' .
                'for exapmle an allowed value-parameter-array and a allowed max-repeat-parameter'],
            ['flag' => true, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyFirst' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::ITEM_REPEAT_MAX => '5',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                    'keySecond' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::ITEM_REPEAT_MAX => '5',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                ],
            ],
        ];
        $result[] = [
            ['no' => 't-14.', 'info' => 'true if the param-Array contains two items with a xpath-definition and ' .
                'for exapmle an allowed value-parameter-array. One item has an allowed max-repeat-parameter'],
            ['flag' => true, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyFirst' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                    'keySecond' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::ITEM_REPEAT_MAX => '5',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                ],
            ],
        ];

        $result[] = [
            ['no' => 't-20.', 'info' => 'false if the param-Array contains two items with a xpath-definition and ' .
                'for exapmle an allowed value-parameter-array. and if one of them fails (for example missing the xpath'],
            ['flag' => false, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyFirst' => [
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                    'keySecond' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::ITEM_REPEAT_MAX => '5',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                ],
            ],
        ];

        $result[] = [
            ['no' => 't-21.', 'info' => 'false if the param-Array contains two items with a xpath-definition and ' .
                'for exapmle an allowed value-parameter-array. and if one of them fails (for example missing the xpath'],
            ['flag' => false, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyFirst' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                    'keySecond' => [
                        RebuildParamValidateService::ITEM_REPEAT_MAX => '5',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                ],
            ],
        ];
        $result[] = [
            ['no' => 't-22.', 'info' => 'false if the param-Array contains two items with a xpath-definition and ' .
                'for exapmle an allowed value-parameter-array. and if one of them fails (for example wron value parameter'],
            ['flag' => false, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyFirst' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                    'keySecond' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::ITEM_REPEAT_MAX => '5',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                ],
            ],
        ];
        $result[] = [
            ['no' => 't-23.', 'info' => 'false if the param-Array contains two items with a xpath-definition and ' .
                'for exapmle an allowed value-parameter-array. and if one of them fails (for example wrong value-parameter'],
            ['flag' => false, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyFirst' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                    'keySecond' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::ITEM_REPEAT_MAX => '5',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                ],
            ],
        ];

        $result[] = [
            ['no' => 't-24.', 'info' => 'false if the param-Array contains two items with a xpath-definition and ' .
                'for exapmle an allowed value-parameter-array. and if one of them fails (for example wrong item_repeat-max'],
            ['flag' => false, 'count' => 15],
            [
                'count' => 14,
                'param' => [
                    'keyFirst' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                    'keySecond' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::ITEM_REPEAT_MAX => 'hallo',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                ],
            ],
        ];

        return $result;
    }

    /**
     * @dataProvider dataProviderParamValidateSecondLevelOfRebuildReturnExpectedBooleanIfTheTwoNeededParamsMayBeDeliveredOrNot
     * @test
     */
    public function paramValidateSecondLevelOfRebuildReturnExpectedBooleanIfParamsAreValidOrNot($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $count = $param ['count'];
            $resultFlag = $this->subject->paramValidateSecondLevelOfRebuild($count, $param ['param']);
            $this->assertSame(
                $expect['flag'],
                $resultFlag,
                $message['no'] . 'check flag: ' . $message['info']
            );
            $this->assertSame(
                $expect['count'],
                $count,
                $message['no'] . 'check Count: ' . $message['info']
            );
        }
    }

}

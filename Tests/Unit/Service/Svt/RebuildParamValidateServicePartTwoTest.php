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

class RebuildParamValidateServicePartTwoTest extends TestCase
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


    public function dataProviderParamValidateStartLevelOfRebuildReturnExpectedBooleanIfTheTwoNeededParamsMayBeDeliveredOrNot()
    {

        $result = [];

        $baseArray = [
            'true' => [
                RebuildParamValidateService::PARAM_ITEMS => [
                    'keyFirst' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_NEW => 'filled',
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                ],
            ],
            'false' => [
                RebuildParamValidateService::PARAM_ITEMS => [
                    'keyFirst' => [
                        RebuildParamValidateService::ITEM_X_PATH => 'XPath Not Validated',
                        RebuildParamValidateService::SUB_PARAM_VALUE => [
                            RebuildBaseService::SUB_PARAM_VALUE_TYPE => RebuildBaseService::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
                        ],
                    ],
                ],
            ],
            'empty' => [
                RebuildParamValidateService::PARAM_ITEMS => [],
            ]
        ];
        $undefinedArray = [
            'true' => [
            ],
            'false' => [
                'pubs' => 'this pubs is undefined',
            ],
            'null' => [
                'pubs' => null,
            ]
        ];
        $repeatMaxArray = [
            'true' => [
                RebuildParamValidateService::PARAM_REPEAT_MAX => 42,
            ],
            'false' => [
                RebuildParamValidateService::PARAM_REPEAT_MAX => null,
            ],
            'zero' => [
                RebuildParamValidateService::PARAM_REPEAT_MAX => 0,
            ],
            'undefined' => [
            ],
        ];

        $count = 0;
        foreach ($baseArray as $baseKey => $baseItem) {
            $nameArray[$baseKey] = 'base(' . $baseKey . ')';
            $baseFlag = ($baseKey === 'true');
            foreach ($repeatMaxArray as $repeatMaxKey => $repeatMaxItem) {
                $nameArray['repeatMax'] = 'repeatMax(' . $repeatMaxKey . ')';
                $repeatMaxFlag = ($repeatMaxKey === 'true') || ($repeatMaxKey === 'undefined')|| ($repeatMaxKey === 'zero');
                foreach ($undefinedArray as $undefinedKey => $undefinedItem) {
                    $nameArray['undefined'] = 'undefined(' . $undefinedKey . ')';
                    $undefinedFlag = ($undefinedKey === 'true');
                    $resultFlag = $baseFlag && $undefinedFlag && $repeatMaxFlag ;
                    $result[] = [
                        ['no' => 't-10.'.$count++, 'info' => 'The Array contains ' . implode(', ', $nameArray) .
                            ' and give the result `' . ($resultFlag ? 'true' : 'false') . '`.'],
                        $resultFlag,
                        array_merge($baseItem, $undefinedItem, $repeatMaxItem)
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * @dataProvider dataProviderParamValidateStartLevelOfRebuildReturnExpectedBooleanIfTheTwoNeededParamsMayBeDeliveredOrNot
     * @test
     */
    public function paramValidateStartLevelOfRebuildReturnExpectedBooleanIfParamsAreValidOrNot($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $resultFlag = $this->subject->paramValidateStartLevelOfRebuild($param);
            $this->assertSame(
                $expect,
                $resultFlag,
                $message['no'] . $message['info']
            );
        }
    }

}

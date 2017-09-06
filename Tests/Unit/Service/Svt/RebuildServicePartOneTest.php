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
 * Time: 16:29
 */

namespace Porth\HornyShit\Service\Svt;

use PHPUnit\Framework\TestCase;

class RebuildServicePartOneTest extends TestCase
{

    /**
     * @var RebuildService
     */
    protected $subject = null;


    public function setUp()
    {
        $this->subject = new RebuildService();
    }

    public function tearDown()
    {
        unset($this->subject);
    }


    public function dataProviderDefineParameterReplaceNoAttributeIfTheArrayContainsNoOptionalAttributes()
    {
        $result = [];
        $empty = [];
        $result[] = [
            [
                'no' => '1-' . '1',
                'info' => 'All parameter are default, if a empty array is given.',
            ],
            [
                'repeatMax' => RebuildService::DEFAULT_REPEAT_MAX,
            ],
            $empty,
        ];
        $result[] = [
            [
                'no' => '1-2',
                'info' => 'All parameter are default, if a null is given.'
            ],
            [
                'repeatMax' => RebuildService::DEFAULT_REPEAT_MAX
            ],
            null,
        ];
        return $result;
    }

    // The parameter ar check - undefine Objects are not used
    public function dataProviderDefineParameterReplaceNoAttributeIfTheArrayContainsAdditionallyAnUndefinedObjekt()
    {
        $result = [];
        return $result;
    }

    public function dataProviderDefineParameterReplaceAttributesIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes()
    {
        $result = [];
        $repeatMax = [
            'name' => RebuildService::PARAM_REPEAT_MAX,
            'defaultValue' => RebuildService::DEFAULT_REPEAT_MAX,
            'otherValue' => '15',
            'emptyValue' => [],
            'nullValue' => null,
        ];
        $result = [];
        $additionalIndexNumberForLooppedTest = 0;
        $variation = [];
        foreach ($repeatMax as $repeatMaxKey => $repeatMaxItem) {
            if ($repeatMaxKey === 'name') continue;
            $variation[$repeatMax['name']] = $repeatMax['name'] . '-' . $repeatMaxKey;
            $repeatMaxExpect = (!empty($repeatMaxItem) ? $repeatMaxItem : RebuildService::DEFAULT_REPEAT_MAX);
            $repeatMaxParam = $repeatMaxItem;
            $result[] = [
                [
                    'no' => '2-' . $additionalIndexNumberForLooppedTest++,
                    'info' => 'All parameter are default, if a null is given.' . (implode(', ', $variation))
                ],
                [
                    $repeatMax['name'] => $repeatMaxExpect,
                ],
                [
                    $repeatMax['name'] => $repeatMaxParam,
                ],
            ];
        }

        return $result;
    }

    /**
     * @dataProvider dataProviderDefineParameterReplaceNoAttributeIfTheArrayContainsNoOptionalAttributes
     * @dataProvider dataProviderDefineParameterReplaceNoAttributeIfTheArrayContainsAdditionallyAnUndefinedObjekt
     * @dataProvider dataProviderDefineParameterReplaceAttributesIfTheArrayContainsCorrectBaseArrayAndVariationOfOptionalAttributes
     * @test
     */
    public function defineParameterReplaceAttributeIfTheArrayContainsAllowedOptionalAttributes($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->subject->defineParameter($param);
            foreach ($expect as $expectKey => $expectItem) {
                $this->assertAttributeEquals(
                    $expectItem,
                    $expectKey,
                    $this->subject,
                    $message['no'] . ': ' . $message['info'] . '(' . $expectKey . ')'
                );

            }
        }
    }

}

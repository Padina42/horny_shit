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
 * Date: 20.05.2017
 * Time: 14:11
 */

namespace Porth\HornyShit\Service\Svt;

use PHPUnit\Framework\TestCase;




class CheckServiceTest extends TestCase
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
     * @var CheckService
     */
    protected $subject = NULL;

    public function setUp()
    {
        // Create a stub for the SomeClass class.
        $stub = $this->getMockBuilder('\Porth\HornyShit\Service\Svt\Utility\ErrorLocalizationUtility')
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        // Configure the stub.
        $stub->method('translateSimple')
            ->willReturn('Hello');

        $this->subject = new CheckService();

    }

    public function tearDown()
    {
        unset($this->subject);
    }


    public function dataProviderHasOneValidSvgTagReturnExpectedBooleanIfXMLStringIsGiven()
    {
        return [
            ['True expected, if the string contain one <svg, contain one </svg> and has positioned </svg> after <svg .', true,
                '<svg </svg>'],

            ['False expected, if the string is empty.', false,
                ''],
            ['False expected, if the string don´t contain one <svg, contain one </svg> and has positioned </svg> after <svg .', false,
                'teststrin'],
            ['False expected, if the tags <Svg is NOT lowercase.', false,
                '<Svg </svg>'],
            ['False expected, if the tags </Svg> is NOT lowercase.', false,
                '<svg </Svg>'],
            ['False expected, if the string contain two <svg elements.', false,
                '<svg <svg </svg>'],
            ['False expected, if the string contain two </svg> elements.', false,
                '<svg </svg> </svg>'],
            ['false expected, if the string contain the </svg> before the <svg-tag.', false,
                '</svg> <svg'],
        ];
    }

    /**
     * @dataProvider dataProviderHasOneValidSvgTagReturnExpectedBooleanIfXMLStringIsGiven
     * @test
     */
    public function hasOneValidSvgTagReturnExpectedBooleanVariateByXmlStream($message, $expect, $xmlStream)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->hasOneValidSvgTag($xmlStream),
                $message
            );
        }
    }


    public function dataProviderHasOneDocumentEntityReturnExpectedBoooleanVariateInputString()
    {
        return [

            ['True expected, if the string contain in case-sensitive <?xml ... encoding="UTF-8" ...> .', true,
                '<?xml ... encoding="UTF-8" ...>'],
            ['True expected, if the string contain in case-insensitive <?xml ... encoding="utf-8" ...> .', true,
                '<?xml ... encoding="utf-8" ...>'],
            ['True expected, if the string contain in case-sensitive <?xml ... encoding="UtF-8" ...> .', true,
                '<?xml ... encoding="UtF-8" ...>'],
            ['false expected, if the string don´t begin with <?xml ', false,
                '<?xmx ... encoding="utf-8" ...>'],
            ['false expected, if the string don´t begin with a space instead of ´<?xml..´ ', false,
                ' <?xml ... encoding="utf-8" ...>'],
            ['false expected, if the string don´t contain the concoding-Sting `encoding="utf-8"` or `encoding="UTF-8"`.', false,
                ' <?xml ... encoding="utt-8" ...>'],
            ['false expected, if the string don´t contain a closing `>`.', false,
                ' <?xml ... encoding="utt-8" ...'],
        ];
    }

    /**
     * @dataProvider dataProviderHasOneDocumentEntityReturnExpectedBoooleanVariateInputString
     * @test
     */
    public function hasOneDocumentEntityReturnExpectedBoooleanVariateInputString($message, $expect, $xmlStream)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->hasOneDocumentEntity($xmlStream),
                $message
            );
        }

    }

    public function dataProviderCheckSpecificationsReturnExpectedBooleanIfTheSpefifiedFlagIsSet()
    {
        return [
            ['False is ever expected, if null instead of a flag is set and a korrekt svg is presentated.', false,
                ['flag' => null, 'xmlStream' => '<?xml ... encoding="UTF-8" ...><svg> hallo Test </svg>']],
            ['False is ever expected, if null instead of a flag is set and a svg-stream with missing svg-tag is presentated.', false,
                ['flag' => null, 'xmlStream' => '<?xml ... encoding="UTF-8" ...>']],
            ['False is ever expected, if null instead of a flag is set and a svg-stream with missing xml-start is presentated.', false,
                ['flag' => null, 'xmlStream' => '<svg> hallo Test </svg>']],
            ['False is ever expected, if null instead of a flag is set and a svg-stream with missing xml-start and svg-tag is presentated.', false,
                ['flag' => null, 'xmlStream' => 'hallo Test']],
            ['False is ever expected, if null instead of a flag is set and a empty svg-stream is presentated.', false,
                ['flag' => null, 'xmlStream' => '']],

            ['True is ever expected, if "none" is the value of the flag and a korrekt svg is presentated.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__NONE, 'xmlStream' => '<?xml ... encoding="UTF-8" ...><svg> hallo Test </svg>']],
            ['True is ever expected, if "none" is the value of the flag and a svg-stream with missing svg-tag is presentated.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__NONE, 'xmlStream' => '<?xml ... encoding="UTF-8" ...>']],
            ['True is ever expected, if "none" is the value of the flag and a svg-stream with missing xml-start is presentated.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__NONE, 'xmlStream' => '<svg> hallo Test </svg>']],
            ['True is ever expected, if "none" is the value of the flag and a svg-stream with missing xml-start and svg-tag is presentated.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__NONE, 'xmlStream' => 'hallo Test']],
            ['True is ever expected, if "none" is the value of the flag and a empty svg-stream is presentated.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__NONE, 'xmlStream' => '']],

            ['true is ever expected, if "xml" is the value of the flag and a korrekt svg is presentated.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__XML, 'xmlStream' => '<?xml ... encoding="UTF-8" ...><svg> hallo Test </svg>']],
            ['true is ever expected, if "xml" is the value of the flag and a svg-stream with missing svg-tag is presentated.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__XML, 'xmlStream' => '<?xml ... encoding="UTF-8" ...>']],
            ['false is ever expected, if "xml" is the value of the flag and a svg-stream with missing xml-start is presentated.', false,
                ['flag' => CheckService::KEYS_CHECK_SVG__XML, 'xmlStream' => '<svg> hallo Test </svg>']],
            ['false is ever expected, if "xml" is the value of the flag and a svg-stream with missing xml-start and svg-tag is presentated.', false,
                ['flag' => CheckService::KEYS_CHECK_SVG__XML, 'xmlStream' => 'hallo Test']],
            ['false is ever expected, if "xml" is the value of the flag and a empty svg-stream is presentated.', false,
                ['flag' => CheckService::KEYS_CHECK_SVG__XML, 'xmlStream' => '']],

            ['true is ever expected, if "svg" is the value of the flag and a korrekt svg is presentated.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__SVG, 'xmlStream' => '<?xml ... encoding="UTF-8" ...><svg> hallo Test </svg>']],
            ['false is ever expected, if "svg" is the value of the flag and a svg-stream with missing svg-tag is presentated.', false,
                ['flag' => CheckService::KEYS_CHECK_SVG__SVG, 'xmlStream' => '<?xml ... encoding="UTF-8" ...>']],
            ['true is ever expected, if "svg" is the value of the flag and a svg-stream with missing xml-start is presentated.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__SVG, 'xmlStream' => '<svg> hallo Test </svg>']],
            ['false is ever expected, if "svg" is the value of the flag and a svg-stream with missing xml-start and svg-tag is presentated.', false,
                ['flag' => CheckService::KEYS_CHECK_SVG__SVG, 'xmlStream' => 'hallo Test']],
            ['false is ever expected, if "svg" is the value of the flag and a empty svg-stream is presentated.', false,
                ['flag' => CheckService::KEYS_CHECK_SVG__SVG, 'xmlStream' => '']],

            ['true is ever expected, if "xmlSvg" is the value of the flag and a korrekt svg is presentated.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__XML_SVG, 'xmlStream' => '<?xml ... encoding="UTF-8" ...><svg> hallo Test </svg>']],
            ['false is ever expected, if "xmlSvg" is the value of the flag and a svg-stream with missing svg-tag is presentated.', false,
                ['flag' => CheckService::KEYS_CHECK_SVG__XML_SVG, 'xmlStream' => '<?xml ... encoding="UTF-8" ...>']],
            ['false is ever expected, if "xmlSvg" is the value of the flag and a svg-stream with missing xml-start is presentated.', false,
                ['flag' => CheckService::KEYS_CHECK_SVG__XML_SVG, 'xmlStream' => '<svg> hallo Test </svg>']],
            ['false is ever expected, if "xmlSvg" is the value of the flag and a svg-stream with missing xml-start and svg-tag is presentated.', false,
                ['flag' => CheckService::KEYS_CHECK_SVG__XML_SVG, 'xmlStream' => 'hallo Test']],
            ['false is ever expected, if "xmlSvg" is the value of the flag and a empty svg-stream is presentated.', false,
                ['flag' => CheckService::KEYS_CHECK_SVG__XML_SVG, 'xmlStream' => '']],
        ];

    }

    /**
     * @dataProvider dataProviderCheckSpecificationsReturnExpectedBooleanIfTheSpefifiedFlagIsSet
     * @test
     */
    public function checkSpecificationsReturnExpectedBooleanIfTheSpecefiedFlagIsSet($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->checkSpecification($params['flag'], $params['xmlStream']),
                $message
            );
        }

    }

    public function dataProviderValidateSpecificationReturnExpectedBooleanIfTheSpefifiedFlagIsSet()
    {
        return [
            ['False is ever expected, if null instead of a string is set and a korrekt svg is presentated.', false,
                ['flag' => null]],
            ['False is ever expected, if an array instead of a string is set and a korrekt svg is presentated.', false,
                ['flag' => [CheckService::KEYS_CHECK_SVG__NONE]]],
            ['False is ever expected, if an integer instead of a string is set and a korrekt svg is presentated.', false,
                ['flag' => 1]],

            ['True is ever expected, if the string "' . CheckService::KEYS_CHECK_SVG__NONE . '" is the value of the flag.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__NONE]],
            ['True is ever expected, if the string "' . CheckService::KEYS_CHECK_SVG__XML_SVG . '" is the value of the flag.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__XML_SVG]],
            ['True is ever expected, if the string "' . CheckService::KEYS_CHECK_SVG__XML . '" is the value of the flag.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__XML]],
            ['True is ever expected, if the string "' . CheckService::KEYS_CHECK_SVG__SVG . '" is the value of the flag.', true,
                ['flag' => CheckService::KEYS_CHECK_SVG__SVG]],
        ];

    }

    /**
     * @dataProvider dataProviderValidateSpecificationReturnExpectedBooleanIfTheSpefifiedFlagIsSet
     * @test
     */
    public function validateSpecificationReturnExpectedBooleanIfTheSpecefiedFlagIsSet($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->validateSpecification($params['flag']),
                $message
            );
        }

    }

    public function dataProviderParamValidateReturnExpectedBooleanIfTheSpefifiedFlagIsSet()
    {
        return [
            ['False is ever expected, if null instead of a string is set.', false,
                ['param' => null]],
            ['False is ever expected, if an integer instead of a string is set.', false,
                ['param' => 42]],
            ['False is ever expected, if an array instead of a string is set.', false,
                ['param' => [CheckService::KEYS_CHECK_SVG__XML_SVG]]],
            ['False is ever expected, if a string with an undefined parameter like ' . CheckService::KEYS_CHECK_LISTING_TEXT . 'is set.', false,
                ['param' => CheckService::KEYS_CHECK_LISTING_TEXT]],
            ['True is expected, if a string with an allowed Parameter like ' . CheckService::KEYS_CHECK_SVG__XML_SVG . ' is set.', true,
                ['param' => CheckService::KEYS_CHECK_SVG__XML_SVG]],
        ];
    }

    /**
     * @dataProvider dataProviderParamValidateReturnExpectedBooleanIfTheSpefifiedFlagIsSet
     * @test
     */
    public function paramValidateReturnExpectedBooleanIfTheSpecefiedFlagIsSet($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->assertSame(
                $expect,
                $this->subject->paramValidate($params['param']),
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

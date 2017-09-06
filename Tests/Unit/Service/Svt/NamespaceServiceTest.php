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
 * Date: 10.06.2017
 * Time: 11:02
 */

namespace Porth\HornyShit\Service\Svt;

use PHPUnit\Framework\TestCase;

class NamespaceServiceTest extends TestCase
{


    /**
     * @var NamespaceService
     */
    protected $subject = NULL;

    public function setUp()
    {
        $this->subject = new NamespaceService();
    }

    public function tearDown()
    {
        unset($this->subject);
    }


    public function dataProviderParamValidateReturnExpectedBooleanIfTheSpefifiedFlagIsSet()
    {
        return [
            ['10. False is ever expected, if null instead of a string is set.',
                false,
                ['param' => null]],
            ['20. False is ever expected, if an integer instead of a string is set.',
                false,
                ['param' => 42]],
            ['30. TRUE is ever expected, if an empty array is set.',
                true,
                ['param' => []]],
            ['40. False is ever expected, if the array contains an one  key than the allowed definitions.',
                false,
                ['param' => ['notallowed' => 'on']]],
            ['50. False is ever expected, if the array contains two entries, where one key is not allowed.',
                false,
                ['param' => [
                    NamespaceService::PARAM_LIST_URL => 'link,link',
                    'notallowed' => 'on'
                ]]],
            ['60. False is ever expected, if the array contains two entries, where one key is not allowed.',
                false,
                ['param' => [
                    NamespaceService::PARAM_TYPE => 'add',
                    'notallowed' => 'on'
                ]]],
            ['70. False is ever expected, if the array contains an type, which is not key of type.',
                false,
                ['param' => [
                    NamespaceService::PARAM_TYPE => 'dull',
                    NamespaceService::PARAM_LIST_URL => 'link,link',
                    'notallowed' => 'on'
                ]]],
            ['80. False is ever expected, if the array contains a type with value `add` with uppercase.',
                false,
                ['param' => [
                    NamespaceService::PARAM_TYPE => 'Add',
                    NamespaceService::PARAM_LIST_URL => 'link,link',
                ]]],
            ['81. False is ever expected, if the array contains a type with value `define` with uppercase.',
                false,
                ['param' => [
                    NamespaceService::PARAM_TYPE => 'Define',
                    NamespaceService::PARAM_LIST_URL => 'link,link',
                ]]],
            ['82. False is ever expected, if the array contains a type, which is not a string.',
                false,
                ['param' => [
                    NamespaceService::PARAM_TYPE => [],
                    NamespaceService::PARAM_LIST_URL => 'link,link',
                ]]],
            ['82. False is ever expected, if the array contains a list, which is not a string.',
                false,
                ['param' => [
                    NamespaceService::PARAM_TYPE => 'add',
                    NamespaceService::PARAM_LIST_URL => ['link','link'],
                ]]],

            ['91. False is ever expected, if the list-part is missing.',
                false,
                ['param' => [
                    NamespaceService::PARAM_TYPE => 'add',
                ]]],

            ['100. True is ever expected, if the array contains a type with value `add`.',
                true,
                ['param' => [
                    NamespaceService::PARAM_TYPE => 'add',
                    NamespaceService::PARAM_LIST_URL => 'link,link',
                ]]],
            ['101. True is ever expected, if the array contains a type with value `define`.',
                true,
                ['param' => [
                    NamespaceService::PARAM_TYPE => 'define',
                    NamespaceService::PARAM_LIST_URL => 'link,link',
                ]]],
            ['110. True is expected, if the array contains no type.-information.',
                true,
                ['param' => [
                    NamespaceService::PARAM_LIST_URL => 'link,link',
                ]]],
            ['120. True is expected, if the array contains no type.-information and the list-part is empty.  There is no more validation.',
                true,
                ['param' => [
                    NamespaceService::PARAM_LIST_URL => '',
                ]]],
            ['121. True is expected, if the array contains no type.-information and the list-part contain only one element. There is no more validation.',
                true,
                ['param' => [
                    NamespaceService::PARAM_LIST_URL => 'link',
                ]]],
            ['122. True is expected, if the array contains no type-information and there are many commaseparated element. There is no more validation.',
                true,
                ['param' => [
                    NamespaceService::PARAM_LIST_URL => 'link,link,link',
                ]]],

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
    public function getDefaultLinksReturnsInitialValueForString()
    {
        $this->assertSame(
            NamespaceService::INITIATIVE_DEFAULT_LINKS_VALUE,
            $this->subject->getDefaultLinks()
        );
    }

    /**
     * @test
     */
    public function setDefaultLinksForStringSetsName()
    {
        $this->subject->setDefaultLinks(['hallo']);

        $this->assertAttributeEquals(
            ['hallo'],
            'defaultLinks',
            $this->subject
        );
    }

    public function dataProviderRebuildParamArrayGenerateExspectedInternatlArrayIfAllowedParamsAreSet()
    {
        $paramAddOne = 'linkAddOne';
        $paramAddTwo  = 'linkAddOne,LinkAddTwo';
        $paramAddThree  = 'linkAddOne,LinkAddTwo,LinkAddThree';
        $paramAddOneSpace = '   linkAddOne  ';
        $paramAddTwoSpace  = '  linkAddOne,  LinkAddTwo';
        $paramAddThreeSpace  = 'linkAddOne , LinkAddTwo,         LinkAddThree                 ';
        $defineEmpty = [];
        $defineOne = ['linkAddOne'];
        $defineTwo  = ['linkAddOne','LinkAddTwo'];
        $defineThree  = ['linkAddOne','LinkAddTwo','LinkAddThree'];
        $resDefault = NamespaceService::INITIATIVE_DEFAULT_LINKS_VALUE;
        $resAddOne =  $resDefault;
        $resAddOne[] = 'linkAddOne';
        $resAddTwo = $resAddOne;
        $resAddTwo[] = 'LinkAddTwo';
        $resAddThree = $resAddTwo;
        $resAddThree[] = 'LinkAddThree';
        return [
            ['10. The array of defaults is given back, if the params-array is empty',
                $resDefault,
                ['param' => []]
            ],
            ['20. The array of defaults is given back,if the list-part of the Param-Array is an empty string and the type-part is missing',
                $resDefault,
                ['param' => [NamespaceService::PARAM_LIST_URL => '']]
            ],
            ['21. The array of defaults is given back, if the list-part of the Param-Array is an empty string and the type-part has the value `add`.',
                $resDefault,
                ['param' => [NamespaceService::PARAM_LIST_URL => '', NamespaceService::PARAM_TYPE=>'add']]
            ],
            ['23. The array of the links is empty, if the list-part of the Param-Array is an empty string and the type-part has the value `define`.',
                $defineEmpty,
                ['param' => [NamespaceService::PARAM_LIST_URL => '', NamespaceService::PARAM_TYPE=>'define']]
            ],
            ['24. The array of the links is empty, if the list-part of the Param-Array contains only spaceas and the type-part has the value `define`.',
                $defineEmpty,
                ['param' => [NamespaceService::PARAM_LIST_URL => '             ', NamespaceService::PARAM_TYPE=>'define']]
            ],
            ['30. A with one element extended array of links is generated, if the list-part of the array of params contain a single string and the type-part is missing',
                $resAddOne,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddOne]]
            ],
            ['31. A with one element extended array of links is generated, if the list-part of the array of params contain a single string and the type-part has the value `add`.',
                $resAddOne,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddOne, NamespaceService::PARAM_TYPE=>'add']]
            ],
            ['32. A with one element extended array of links is generated, if the spacy list-part of the array of params contain a single string and the type-part has the value `add`.',
                $resAddOne,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddOneSpace, NamespaceService::PARAM_TYPE=>'add']]
            ],
            ['33. The array of the links contains one element, if the list-part of the Param-Array contains only one item and the type-part has the value `define`.',
                $defineOne,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddOne, NamespaceService::PARAM_TYPE=>'define']]
            ],
            ['34. The array of the links contains one element, if the list-part of the Param-Array contains only one item with spaces and the type-part has the value `define`.',
                $defineOne,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddOneSpace, NamespaceService::PARAM_TYPE=>'define']]
            ],
            ['40. A with one element extended array of links is generated, if the list-part of the array of params contain a single string and the type-part is missing',
                $resAddTwo,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddTwo]]
            ],
            ['41. A with one element extended array of links is generated, if the list-part of the array of params contain a single string and the type-part has the value `add`.',
                $resAddTwo,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddTwo, NamespaceService::PARAM_TYPE=>'add']]
            ],
            ['42. A with one element extended array of links is generated, if the spacy list-part of the array of params contain a single string and the type-part has the value `add`.',
                $resAddTwo,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddTwoSpace, NamespaceService::PARAM_TYPE=>'add']]
            ],
            ['43. The array of the links contains two elements, if the list-part of the Param-Array contains only two items and the type-part has the value `define`.',
                $defineTwo,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddTwo, NamespaceService::PARAM_TYPE=>'define']]
            ],
            ['44. The array of the links contains two element, if the list-part of the Param-Array contains only two item with spaces and the type-part has the value `define`.',
                $defineTwo,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddTwoSpace, NamespaceService::PARAM_TYPE=>'define']]
            ],
            ['50. A with one element extended array of links is generated, if the list-part of the array of params contain a single string and the type-part is missing',
                $resAddThree,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddThree]]
            ],
            ['51. A with one element extended array of links is generated, if the list-part of the array of params contain a single string and the type-part has the value `add`.',
                $resAddThree,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddThree, NamespaceService::PARAM_TYPE=>'add']]
            ],
            ['52. A with one element extended array of links is generated, if the spacy list-part of the array of params contain a single string and the type-part has the value `add`.',
                $resAddThree,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddThreeSpace, NamespaceService::PARAM_TYPE=>'add']]
            ],
            ['53. The array of the links contains three elements, if the list-part of the Param-Array contains only three items and the type-part has the value `define`.',
                $defineThree,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddThree, NamespaceService::PARAM_TYPE=>'define']]
            ],
            ['54. The array of the links contains three element, if the list-part of the Param-Array contains only three item with spaces and the type-part has the value `define`.',
                $defineThree,
                ['param' => [NamespaceService::PARAM_LIST_URL => $paramAddThreeSpace, NamespaceService::PARAM_TYPE=>'define']]
            ],

        ];
    }

    /**
     * @dataProvider dataProviderRebuildParamArrayGenerateExspectedInternatlArrayIfAllowedParamsAreSet
     * @test
     */
    public function rebuildParamArrayGenerateExspectedInternatlArrayIfAllowedParamsAreSet($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->subject->rebuildParamArray($params['param']);
            // get the generated Parameter
            $result = $this->subject->getDefaultLinks();
            $this->assertSame(
                $expect,
                $result,
                $message
            );
            $diffArray = array_diff_assoc($expect, $result);
            $this->assertSame(
                0,
                count($diffArray),
                'Test difference of exspected and caclulated array: must be 0. ' . "\n" . $message
            );
            $this->assertSame(
                count($expect),
                count($result),
                'Test length of result-array and expected array: must be equal. ' . "\n" . $message
            );

        }
    }

    public function dataProviderDetectRemovableNamesspacesReturnArrayWithAllNamespacesWhichHasToBeRemoved()
    {
        $simpleSVG = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          version="1.1" width="810" height="540">
       <title>Sterne als Götterfunken</title>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" transform="scale(-1,1)"/>
		    </g>
			<g id="a">
			  <use xlink:href="#c" transform="rotate(72)"/>
			  <use xlink:href="#c" transform="rotate(144)"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        $fullDefaultSVG = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          version="1.1" width="810" height="540">
       <title>Sterne als Götterfunken</title>
       <cc:author>Tester</cc:author>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
		    </g>
			<g id="a">
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)"/>
			  <use xlink:href="#c" transform="rotate(144)"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        $fullIncludedSVG = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          version="1.1" width="810" height="540">
       <title>Sterne als Götterfunken</title>
       <cc:author>Tester</cc:author>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
		    </g>
			<g id="a">
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)"/>
			  <use xlink:href="#c" transform="rotate(144)"/>

			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        $AddOneNewNamespaceTop = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:test="http://www.test.de/1542/test" 
          xmlns:cc="http://creativecommons.org/ns#" 
          version="1.1" width="810" height="540">
       <title>Sterne als Götterfunken</title>
       <cc:author>Tester</cc:author>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
		    </g>
			<g id="a">
			  <test:watIs color="green">allesTest</test:watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)"/>
			  <use xlink:href="#c" transform="rotate(144)"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        $AddOneNewNamespaceInclude = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
           
          xmlns:cc="http://creativecommons.org/ns#" 
          version="1.1" width="810" height="540">
       <title>Sterne als Götterfunken</title>
       <cc:author>Tester</cc:author>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/test">
			  <testtwo:watIs color="green">allesTest</testtwo:watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)"/>
			  <use xlink:href="#c" transform="rotate(144)"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        $AddTwoNewNamespaceInclude = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          version="1.1" width="810" height="540">
       <title>Sterne als Götterfunken</title>
       <cc:author>Tester</cc:author>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo">
			  <test:watIs  color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green">allesTest</testtwo:watIs>
			  
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)"/>
			  <use xlink:href="#c" transform="rotate(144)"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;


        return [
            ['10. The xml contains parts of defaults links at the startnode. the exspected Result-Array is empty.',
                [],
                ['param' => $simpleSVG]
            ],
            ['11. The xml contains all of defaults links at the startnode. The exspected Result-Array is empty.',
                [],
                ['param' => $fullDefaultSVG]
            ],
            ['12. The xml contains all of defaults links. Some links are in the startnode, some are included. The exspected Result-Array is empty.',
                [],
                ['param' => $fullIncludedSVG]
            ],
            ['21. The xml contains an undefined namespace in the startnode.. The Array of removable Namespaces contain one element.',
                [ 'test' => 'http://www.test.de/1542/test'],
                ['param' => $AddOneNewNamespaceTop]
            ],
            ['22. The xml contains an undefined namespace, which is included in the SVG. The Array of removable Namespaces contain one element.',
                ['testtwo' => 'http://www.testtwo.de/1542/test'],
                ['param' => $AddOneNewNamespaceInclude]
            ],
            ['23. The xml contains two undefined namespace. One is included in the SVG and one ist part of the startnode. The Array of removable Namespaces contain two element.',
                ['testtwo' => 'http://www.testtwo.de/1542/testtwo', 'test' => 'http://www.test.de/1542/test'],
                ['param' => $AddTwoNewNamespaceInclude]
            ],
            ['23.b The xml contains two undefined namespace. One is included in the SVG and one ist part of the startnode. The Array of removable Namespaces contain two element.',
                ['test' => 'http://www.test.de/1542/test', 'testtwo' => 'http://www.testtwo.de/1542/testtwo', ],
                ['param' => $AddTwoNewNamespaceInclude]
            ],
        ];
    }


    /**
     * @dataProvider dataProviderDetectRemovableNamesspacesReturnArrayWithAllNamespacesWhichHasToBeRemoved
     * @test
     */
    public function detectRemovableNamesspacesReturnArrayWithAllNamespacesWhichHasToBeRemoved($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $result = $this->subject->detectRemovableNamesspaces($params['param']);
            $diffArray = array_diff_assoc($expect, $result);
            $this->assertSame(
                0,
                count($diffArray),
                'Test difference of exspected and caclulated array: must be 0. ' . "\n" . $message
            );
            $this->assertSame(
                count($expect),
                count($result),
                'Test length of result-array and expected array: must be equal. ' . "\n" . $message
            );

        }
    }

    public function dataProviderDetectRemovableNamesspacesReturnExceptionIfGivenSVGisFail()
    {
        $errorSvgWithMissingEndTag = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          version="1.1" width="810" height="540">
       <title>Sterne als Götterfunken</title>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" transform="scale(-1,1)"/>
		    </g>
			<g id="a">
			  <use xlink:href="#c" transform="rotate(72)"/>
			  <use xlink:href="#c" transform="rotate(144)"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     
XML;

        return [
            ['10. The xml is empty. The method detectRemovableNamesspaces will cause an exception.',
                [],
                [
                    'xmlStream' => '',
                    'exception' => \UnexpectedValueException::class
                ]
            ],
            ['20. The xml contains an error. The method detectRemovableNamesspaces will cause an exception.',
                [],
                [
                    'xmlStream' => $errorSvgWithMissingEndTag,
                    'exception' => \UnexpectedValueException::class
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderDetectRemovableNamesspacesReturnExceptionIfGivenSVGisFail
     * @test
     */
    public function detectRemovableNamesspacesThrowsExceptionIfGivenSvgHasErrros($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->expectException($params['exception']);
            $dummy = $this->subject->detectRemovableNamesspaces($params['xmlStream']);
        }
    }

    public function dataProviderRemoveTagsWithNamespaceReturnArrayWithAllNamespacesWhichHasToBeRemoved()
    {

        $baseExample = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo">
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removedTestTagExample = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo">
			  
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              
              <testtwo:intern  color="green"> 
                 
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removedTesttwoTag = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo">
			  <test:watIs color="green">allesTest </test:watIs>
			  
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 
              </test:intern>
              
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removedTestAndTesttwoTag = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo">
			  
			  
              
              
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

//        ['test'=>'http://www.test.de/1542/test']
        return [
            ['10. Remove namespaced tags: The namespace-part is empty. The xml is identical to the beginning.',
                $baseExample,
                [
                    'namespace' => [],
                    'param' => $baseExample,
                ]
            ],
            ['11. Remove namespaced tags: The namespace-part is not an array. The xml is identical to the beginning. No error-message is thrown.',
                $baseExample,
                [
                    'namespace' => [],
                    'param' => $baseExample,
                ]
            ],
            ['12. Remove namespaced tags: The namespace-part is not an array. The xml is identical to the beginning. No error-message is thrown.',
                $baseExample,
                [
                    'namespace' => [],
                    'param' => $baseExample,
                ]
            ],
            ['20. Remove namespaced tags: The namespace-part contains the test-definition. in the xml ist the test removed. ',
                $removedTestTagExample,
                [
                    'namespace' => ['test'=>'http://www.test.de/1542/test'],
                    'param' => $baseExample,
                ]
            ],
            ['21. Remove namespaced tags: The namespace-part is not an array. The xml is identical to the beginning. No error-message is thrown.',
                $removedTesttwoTag,
                [
                    'namespace' => ['testtwo'=>'http://www.testtwo.de/1542/testtwo'],
                    'param' => $baseExample,
                ]
            ],
            ['22. Remove namespaced tags: The namespace-part is not an array. The xml is identical to the beginning. No error-message is thrown.',
                $removedTestAndTesttwoTag,
                [
                    'namespace' => [
                        'test'=>'http://www.test.de/1542/test',
                        'testtwo'=>'http://www.testtwo.de/1542/testtwo',
                    ],
                    'param' => $baseExample,
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderRemoveTagsWithNamespaceReturnArrayWithAllNamespacesWhichHasToBeRemoved
     * @test
     */
    public function removeTagsWithNamespaceReturnArrayWithAllNamespacesWhichHasToBeRemoved($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $domExpect = new \DOMDocument();
            $domExpect->loadXML($expect);
            $cannonicalExpect = $domExpect->documentElement->c14n();
            $helpDOM = new \DOMDocument();
            $helpDOM->loadXML($params['param']);
            $helpDOM = $this->subject->removeTagsWithNamespace($params['namespace'],$helpDOM);
            $cannonicalResult = $helpDOM->documentElement->c14n();
            $this->assertSame(
                $cannonicalExpect,
                $cannonicalResult,
                'Test of cannonical xml-strings: ' . "\n" . $message
            );
            $this->assertSame(
                preg_replace('/\s/','',$cannonicalExpect),
                preg_replace('/\s/','',$cannonicalResult),
                'Test of cannonical xml-strings (whitespace-free): ' . "\n" . $message
            );
        }
    }

    public function dataProviderRemoveAttributesWithNamespaceReturnArrayWithAllNamespacesWhichHasToBeRemoved()
    {

        $baseExample = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo">
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removedTestAttributeExample = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple>test:validate="hallo Welt"</wumple>
       <wapple />
       <cc:author>Tester</cc:author>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo">
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removedTesttwoAttribute = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo">
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple>test:validate="hallo Welt"</wumple>
              <wapple />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" />
			  <use xlink:href="#c" transform="rotate(144)"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removedTestAndTesttwoAttribute = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple>test:validate="hallo Welt"</wumple>
       <wapple />
       <cc:author>Tester</cc:author>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo">
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green">allesTest</testtwo:watIs>
              <test:intern  color="green"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple>test:validate="hallo Welt"</wumple>
              <wapple/>
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)"/>
			  <use xlink:href="#c" transform="rotate(144)" />
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

//        ['test'=>'http://www.test.de/1542/test']
        return [
            ['10. Remove namespaced-attributes. The namespace-part is empty. The xml is identical to the beginning.',
                $baseExample,
                [
                    'namespace' => [],
                    'param' => $baseExample,
                ]
            ],
            ['11. Remove namespaced-attributes. The namespace-part is not an array. The xml is identical to the beginning. No error-message is thrown.',
                $baseExample,
                [
                    'namespace' => [],
                    'param' => $baseExample,
                ]
            ],
            ['12. Remove namespaced-attributes. The namespace-part is not an array. The xml is identical to the beginning. No error-message is thrown.',
                $baseExample,
                [
                    'namespace' => [],
                    'param' => $baseExample,
                ]
            ],
            ['20. Remove namespaced-attributes. The namespace-part contains the test-definition. in the xml ist the test removed. ',
                $removedTestAttributeExample,
                [
                    'namespace' => ['test'=>'http://www.test.de/1542/test'],
                    'param' => $baseExample,
                ]
            ],
            ['21. Remove namespaced-attributes. The namespace-part is not an array. The xml is identical to the beginning. No error-message is thrown.',
                $removedTesttwoAttribute,
                [
                    'namespace' => ['testtwo'=>'http://www.testtwo.de/1542/testtwo'],
                    'param' => $baseExample,
                ]
            ],
            ['22. Remove namespaced-attributes. The namespace-part is not an array. The xml is identical to the beginning. No error-message is thrown.',
                $removedTestAndTesttwoAttribute,
                [
                    'namespace' => [
                        'test'=>'http://www.test.de/1542/test',
                        'testtwo'=>'http://www.testtwo.de/1542/testtwo',
                    ],
                    'param' => $baseExample,
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderRemoveAttributesWithNamespaceReturnArrayWithAllNamespacesWhichHasToBeRemoved
     * @test
     */
    public function removeAttributesWithNamespaceReturnArrayWithAllNamespacesWhichHasToBeRemoved($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $domExpect = new \DOMDocument();
            $domExpect->loadXML($expect);
            $cannonicalExpect = $domExpect->documentElement->c14n();
            $helpDOM = new \DOMDocument();
            $helpDOM->loadXML($params['param']);
            $helpDOM = $this->subject->removeAttributesWithNamespace($params['namespace'],$helpDOM);
            $cannonicalResult = $helpDOM->documentElement->c14n();
            $this->assertSame(
                $cannonicalExpect,
                $cannonicalResult,
                'Test of cannonical xml-strings: ' . "\n" . $message
            );
            $this->assertSame(
                preg_replace('/\s/','',$cannonicalExpect),
                preg_replace('/\s/','',$cannonicalResult),
                'Test of cannonical xml-strings (whitespace-free): ' . "\n" . $message
            );
        }
    }

    public function dataProviderRemoveSingleNamespaceDeclarationReturnArrayWithAllNamespacesWhichHasToBeRemoved()
    {

        $baseExample = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:nametest="http://www.test.de/1542/nametest"           
          xmlns:nametesttwo="http://www.test.de/1542/nametesttwo"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c" xmlns:nametestthree="http://www.test.de/1542/nametestthree">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    xmlns:nametestthree="http://www.test.de/1542/nametestthree"
			    xmlns:nametestfour="http://www.test.de/1542/nametestfour"
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removedTestNamespaceExample = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
                     
          xmlns:nametesttwo="http://www.test.de/1542/nametesttwo"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c" xmlns:nametestthree="http://www.test.de/1542/nametestthree">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    xmlns:nametestthree="http://www.test.de/1542/nametestthree"
			    xmlns:nametestfour="http://www.test.de/1542/nametestfour"
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removedTesttwoNamespace= <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:nametest="http://www.test.de/1542/nametest"           
                     
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c" xmlns:nametestthree="http://www.test.de/1542/nametestthree">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    xmlns:nametestthree="http://www.test.de/1542/nametestthree"
			    xmlns:nametestfour="http://www.test.de/1542/nametestfour"
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removedTestthreeNamespace = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:nametest="http://www.test.de/1542/nametest"           
          xmlns:nametesttwo="http://www.test.de/1542/nametesttwo"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    
			    xmlns:nametestfour="http://www.test.de/1542/nametestfour"
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removedTestfourNamespace = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:nametest="http://www.test.de/1542/nametest"           
          xmlns:nametesttwo="http://www.test.de/1542/nametesttwo"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c" xmlns:nametestthree="http://www.test.de/1542/nametestthree">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    xmlns:nametestthree="http://www.test.de/1542/nametestthree"
			    
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removedTestAndTesttwoNamespace = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
                     
                     
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c" xmlns:nametestthree="http://www.test.de/1542/nametestthree">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    xmlns:nametestthree="http://www.test.de/1542/nametestthree"
			    xmlns:nametestfour="http://www.test.de/1542/nametestfour"
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removedTestAndTesttwoAndTestthreeNamespace = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
                     
                     
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    
			    xmlns:nametestfour="http://www.test.de/1542/nametestfour"
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        $removedTestAndTesttwoAndTestthreeAndTestfourNamespace = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
                     
                     
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    
			    
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        $removedTestthreeAndTestfourNamespace = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:nametest="http://www.test.de/1542/nametest"           
          xmlns:nametesttwo="http://www.test.de/1542/nametesttwo"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    
			    
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        return [
            ['10. Remove namespace-Definitions. The namespace-part is empty. The xml is identical to the beginning.',
                $baseExample,
                [
                    'namespace' => [],
                    'param' => $baseExample,
                ]
            ],
            ['11. Remove namespace-Definitions. The namespace-part is not an array. The xml is identical to the beginning. No error-message is thrown.',
                $baseExample,
                [
                    'namespace' => [],
                    'param' => $baseExample,
                ]
            ],
            ['12. Remove namespace-Definitions. The namespace-part is not an array. The xml is identical to the beginning. No error-message is thrown.',
                $baseExample,
                [
                    'namespace' => [],
                    'param' => $baseExample,
                ]
            ],
            ['21. Remove namespace-Definitions. The namespace-part contains a single rooted namespaces, which is not used. The xml is valid',
                $removedTestNamespaceExample,
                [
                    'namespace' => ['nametest'=>'http://www.test.de/1542/nametest'],
                    'param' => $baseExample,
                ]
            ],
            ['21. Remove namespace-Definitions. The namespace-part contains a second single rooted namespaces, which is not used. The xml is valid',
                $removedTesttwoNamespace,
                [
                    'namespace' => ['nametesttwo'=>'http://www.test.de/1542/nametesttwo'],
                    'param' => $baseExample,
                ]
            ],
            ['22. Remove namespace-Definitions. The namespace-part contains a multiple included namespaces, which is not used. The xml is valid',
                $removedTestthreeNamespace,
                [
                    'namespace' => ['nametestthree'=>'http://www.test.de/1542/nametestthree'],
                    'param' => $baseExample,
                ]
            ],
            ['23. Remove namespace-Definitions. The namespace-part contains a single included namespaces, which is not used. The xml is valid',
                $removedTestfourNamespace,
                [
                    'namespace' => ['nametestfour'=>'http://www.test.de/1542/nametestfour'],
                    'param' => $baseExample,
                ]
            ],
            ['30. Remove namespace-Definitions. The namespace-part contains the rooted namespaces(= located in the root-node), which are not used. The xml is valid',
                $removedTestAndTesttwoNamespace,
                [
                    'namespace' => [
                        'nametest'=>'http://www.test.de/1542/nametest',
                        'nametesttwo'=>'http://www.test.de/1542/nametesttwo',
                    ],
                    'param' => $baseExample,
                ]
            ],
            ['31. Remove namespace-Definitions. The namespace-part contains some included namespaces and rooted namespaces, which are not used. The xml is valid',
                $removedTestAndTesttwoAndTestthreeNamespace,
                [
                    'namespace' => [
                        'nametest'=>'http://www.test.de/1542/nametest',
                        'nametesttwo'=>'http://www.test.de/1542/nametesttwo',
                        'nametestthree'=>'http://www.test.de/1542/nametestthree',
                    ],
                    'param' => $baseExample,
                ]
            ],
            ['32. Remove namespace-Definitions. The namespace-part contains all included namespaces and rooted namespaces, which are not used. The xml is valid',
                $removedTestAndTesttwoAndTestthreeAndTestfourNamespace,
                [
                    'namespace' => [
                        'nametest'=>'http://www.test.de/1542/nametest',
                        'nametesttwo'=>'http://www.test.de/1542/nametesttwo',
                        'nametestthree'=>'http://www.test.de/1542/nametestthree',
                        'nametestfour'=>'http://www.test.de/1542/nametestfour',
                    ],
                    'param' => $baseExample,
                ]
            ],
            ['32. Remove namespace-Definitions. The namespace-part contain only included namespace. The xml ist valid',
                $removedTestthreeAndTestfourNamespace,
                [
                    'namespace' => [
                        'nametestthree'=>'http://www.test.de/1542/nametestthree',
                        'nametestfour'=>'http://www.test.de/1542/nametestfour',
                    ],
                    'param' => $baseExample,
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderRemoveSingleNamespaceDeclarationReturnArrayWithAllNamespacesWhichHasToBeRemoved
     * @test
     */
    public function removeSingleNamespaceDeclarationReturnArrayWithAllNamespacesWhichHasToBeRemoved($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $domExpect = new \DOMDocument();
            $domExpect->loadXML($expect);
            $cannonicalExpect = $domExpect->documentElement->c14n();
            $helpDOM = new \DOMDocument();
            $helpDOM->loadXML($params['param']);
            $helpDOM = $this->subject->removeSingleNamespaceDeclaration($params['namespace'],$helpDOM);
            $cannonicalResult = $helpDOM->documentElement->c14n();
            $this->assertSame(
                $cannonicalExpect,
                $cannonicalResult,
                'Test of cannonical xml-strings: ' . "\n" . $message
            );
            $this->assertSame(
                preg_replace('/\s/','',$cannonicalExpect),
                preg_replace('/\s/','',$cannonicalResult),
                'Test of cannonical xml-strings (whitespace-free): ' . "\n" . $message
            );
        }
    }

    public function dataProviderRemoveNodesAndAttributesWithNamespacesReturnArrayWithAllNamespacesWhichHasToBeRemoved()
    {

        $baseExample = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:nametest="http://www.test.de/1542/nametest"           
          xmlns:nametesttwo="http://www.test.de/1542/nametesttwo"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c" xmlns:nametestthree="http://www.test.de/1542/nametestthree">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    xmlns:nametestthree="http://www.test.de/1542/nametestthree"
			    xmlns:nametestfour="http://www.test.de/1542/nametestfour"
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removeAllAsTestNamespaceExample = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
                     
                     
          xmlns:nametesttwo="http://www.test.de/1542/nametesttwo"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      
       <title>Sterne als Götterfunken</title>
       <wumple>test:validate="hallo Welt"</wumple>
       <wapple />
       <cc:author>Tester</cc:author>
       <defs>
	     <g id="s">
		   <g id="c" xmlns:nametestthree="http://www.test.de/1542/nametestthree">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    xmlns:nametestthree="http://www.test.de/1542/nametestthree"
			    xmlns:nametestfour="http://www.test.de/1542/nametestfour"
			    >
			  
			  <testtwo:watIs color="green">allesTest</testtwo:watIs>
              
              <testtwo:intern  color="green"> 
                 
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        $removeAllAsTesttwoNamespace= <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:nametest="http://www.test.de/1542/nametest"           
                     
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c" xmlns:nametestthree="http://www.test.de/1542/nametestthree">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a"
			    xmlns:nametestthree="http://www.test.de/1542/nametestthree"
			    xmlns:nametestfour="http://www.test.de/1542/nametestfour"
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  
              <test:intern  color="green"> 
                 
              </test:intern>
              
              <wumple>test:validate="hallo Welt"</wumple>
              <wapple />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" />
			  <use xlink:href="#c" transform="rotate(144)" />
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removeAllAsTestthreeNamespace = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:nametest="http://www.test.de/1542/nametest"           
          xmlns:nametesttwo="http://www.test.de/1542/nametesttwo"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    
			    xmlns:nametestfour="http://www.test.de/1542/nametestfour"
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removeAllAsTestfourNamespace = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:nametest="http://www.test.de/1542/nametest"           
          xmlns:nametesttwo="http://www.test.de/1542/nametesttwo"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c" xmlns:nametestthree="http://www.test.de/1542/nametestthree">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    xmlns:nametestthree="http://www.test.de/1542/nametestthree"
			    
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removeAllAsTestAndTesttwoNamespace = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
                     
                     
                     
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      
       <title>Sterne als Götterfunken</title>
       <wumple>test:validate="hallo Welt"</wumple>
       <wapple />
       <cc:author>Tester</cc:author>
       <defs>
	     <g id="s">
		   <g id="c" xmlns:nametestthree="http://www.test.de/1542/nametestthree">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 
		    </g>
			<g id="a"
			    xmlns:nametestthree="http://www.test.de/1542/nametestthree"
			    xmlns:nametestfour="http://www.test.de/1542/nametestfour"
			    >
			  
			  
              
              
              <wumple>test:validate="hallo Welt"</wumple>
              <wapple />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" />
			  <use xlink:href="#c" transform="rotate(144)" />
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $removeAllAsTestAndTesttwoAndTestthreeNamespace = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
                     
                     
                     
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      
       <title>Sterne als Götterfunken</title>
       <wumple>test:validate="hallo Welt"</wumple>
       <wapple />
       <cc:author>Tester</cc:author>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 
		    </g>
			<g id="a"
			    
			    xmlns:nametestfour="http://www.test.de/1542/nametestfour"
			    >
			  
			  
              
              
              <wumple>test:validate="hallo Welt"</wumple>
              <wapple />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" />
			  <use xlink:href="#c" transform="rotate(144)" />
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        $removeAllAsTestAndTesttwoAndTestthreeAndTestfourNamespace = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
                     
                     
                     
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      
       <title>Sterne als Götterfunken</title>
       <wumple>test:validate="hallo Welt"</wumple>
       <wapple />
       <cc:author>Tester</cc:author>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 
		    </g>
			<g id="a"
			    
			    
			    >
			  
			  
              
              
              <wumple>test:validate="hallo Welt"</wumple>
              <wapple />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" />
			  <use xlink:href="#c" transform="rotate(144)" />
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        $removeAllAsTestthreeAndTestfourNamespace = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:xhtml="http://www.w3.org/1999/xhtml" 
          xmlns:cc="http://creativecommons.org/ns#" 
          xmlns:test="http://www.test.de/1542/test"           
          xmlns:nametest="http://www.test.de/1542/nametest"           
          xmlns:nametesttwo="http://www.test.de/1542/nametesttwo"           
          xmlns:f="http://typo3.org/ns/TYPO3/Fluid/ViewHelpers" 
          xmlns:p="http://mobger.de/ns/Porth/HornyShit/ViewHelpers" 
          version="1.1" width="810" height="540">
      <test:watIs  color="green">allesTest </test:watIs>
       <title>Sterne als Götterfunken</title>
       <wumple  test:validate="hallo Welt">test:validate="hallo Welt"</wumple>
       <wapple test:validate="hallo Welt" />
       <cc:author>Tester</cc:author>
       <defs  test:validate="hallo Welt">
	     <g id="s"  test:validate="hallo Welt">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" xhtml:style="color:green" transform="scale(-1,1)"/>
			 <test:watIs color="green"  test:validate="hallo Welt">allesTest </test:watIs>
		    </g>
			<g id="a" xmlns:testtwo="http://www.testtwo.de/1542/testtwo"
			    
			    
			    >
			  <test:watIs color="green">allesTest </test:watIs>
			  <testtwo:watIs color="green"  test:validate="hallo Welt">allesTest</testtwo:watIs>
              <test:intern  color="green" testtwo:validate="hallo Welt"> 
                 <testtwo:watIs  color="green">allesTest </testtwo:watIs>
              </test:intern>
              <testtwo:intern  color="green"> 
                 <test:watIs  color="green">allesTest </test:watIs>
              </testtwo:intern>
              <wumple  testtwo:validate="hallo Welt">test:validate="hallo Welt"</wumple>
              <wapple  testtwo:validate="hallo Welt" />
              <watIs>testtwo:validate="hallo Welt" </watIs>
              <watIs>test:validate="hallo Welt" </watIs>
			  <watIs>Ist problemfrei</watIs>
			  <use xlink:href="#c" transform="rotate(72)" testtwo:isTest="alles gut"/>
			  <use xlink:href="#c" transform="rotate(144)" testtwo:isTest="alles besser"/>
			  <f:cObject typoscriptObjectPath="lib.testSvg" />
			  <p:svt src="test.svg">alles wird gut</p:svt>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        return [
            ['10. Remove namespace-Definitions. The namespace-part is empty. The xml is identical to the beginning.',
                $baseExample,
                [
                    'namespace' => [],
                    'param' => $baseExample,
                ]
            ],
            ['11. Remove namespace-Definitions. The namespace-part is not an array. The xml is identical to the beginning. No error-message is thrown.',
                $baseExample,
                [
                    'namespace' => [],
                    'param' => $baseExample,
                ]
            ],
            ['12. Remove namespace-Definitions. The namespace-part is not an array. The xml is identical to the beginning. No error-message is thrown.',
                $baseExample,
                [
                    'namespace' => [],
                    'param' => $baseExample,
                ]
            ],
            ['21. Remove namespace-Definitions. The namespace-part contains a single rooted namespaces, which is not used. The xml is valid',
                $removeAllAsTestNamespaceExample,
                [
                    'namespace' => [
                        'test'=>'http://www.test.de/1542/test',
                        'nametest'=>'http://www.test.de/1542/nametest',
                    ],
                    'param' => $baseExample,
                ]
            ],
            ['21. Remove namespace-Definitions. The namespace-part contains a second single rooted namespaces, which is not used. The xml is valid',
                $removeAllAsTesttwoNamespace,
                [
                    'namespace' => [
                        'testtwo'=>'http://www.testtwo.de/1542/testtwo',
                        'nametesttwo'=>'http://www.test.de/1542/nametesttwo',
                    ],
                    'param' => $baseExample,
                ]
            ],
            ['22. Remove namespace-Definitions. The namespace-part contains a multiple included namespaces, which is not used. The xml is valid',
                $removeAllAsTestthreeNamespace,
                [
                    'namespace' => [
                        'nametestthree'=>'http://www.test.de/1542/nametestthree',
                    ],
                    'param' => $baseExample,
                ]
            ],
            ['23. Remove namespace-Definitions. The namespace-part contains a single included namespaces, which is not used. The xml is valid',
                $removeAllAsTestfourNamespace,
                [
                    'namespace' => [
                        'nametestfour'=>'http://www.test.de/1542/nametestfour',
                    ],
                    'param' => $baseExample,
                ]
            ],
            ['30. Remove namespace-Definitions. The namespace-part contains the rooted namespaces(= located in the root-node), which are not used. The xml is valid',
                $removeAllAsTestAndTesttwoNamespace,
                [
                    'namespace' => [
                        'test'=>'http://www.test.de/1542/test',
                        'testtwo'=>'http://www.testtwo.de/1542/testtwo',
                        'nametest'=>'http://www.test.de/1542/nametest',
                        'nametesttwo'=>'http://www.test.de/1542/nametesttwo',
                    ],
                    'param' => $baseExample,
                ]
            ],
            ['31. Remove namespace-Definitions. The namespace-part contains some included namespaces and rooted namespaces, which are not used. The xml is valid',
                $removeAllAsTestAndTesttwoAndTestthreeNamespace,
                [
                    'namespace' => [
                        'test'=>'http://www.test.de/1542/test',
                        'testtwo'=>'http://www.testtwo.de/1542/testtwo',
                        'nametest'=>'http://www.test.de/1542/nametest',
                        'nametesttwo'=>'http://www.test.de/1542/nametesttwo',
                        'nametestthree'=>'http://www.test.de/1542/nametestthree',
                    ],
                    'param' => $baseExample,
                ]
            ],
            ['32. Remove namespace-Definitions. The namespace-part contains all included namespaces and rooted namespaces, which are not used. The xml is valid',
                $removeAllAsTestAndTesttwoAndTestthreeAndTestfourNamespace,
                [
                    'namespace' => [
                        'test'=>'http://www.test.de/1542/test',
                        'testtwo'=>'http://www.testtwo.de/1542/testtwo',
                        'nametest'=>'http://www.test.de/1542/nametest',
                        'nametesttwo'=>'http://www.test.de/1542/nametesttwo',
                        'nametestthree'=>'http://www.test.de/1542/nametestthree',
                        'nametestfour'=>'http://www.test.de/1542/nametestfour',
                    ],
                    'param' => $baseExample,
                ]
            ],
            ['32. Remove namespace-Definitions. The namespace-part contain only included namespace. The xml ist valid',
                $removeAllAsTestthreeAndTestfourNamespace,
                [
                    'namespace' => [
                        'nametestthree'=>'http://www.test.de/1542/nametestthree',
                        'nametestfour'=>'http://www.test.de/1542/nametestfour',
                    ],
                    'param' => $baseExample,
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderRemoveNodesAndAttributesWithNamespacesReturnArrayWithAllNamespacesWhichHasToBeRemoved
     * @test
     */
    public function removeNodesAndAttributesWithNamespacesReturnArrayWithAllNamespacesWhichHasToBeRemoved($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $domExpect = new \DOMDocument();
            $domExpect->loadXML($expect);
            $cannonicalExpect = $domExpect->documentElement->c14n();
            $helpDOM = new \DOMDocument();
            $helpDOM->loadXML($params['param']);
            $helpDOM = $this->subject->removeNodesAndAttributesWithNamespaces($params['namespace'],$helpDOM);
            $cannonicalResult = $helpDOM->documentElement->c14n();
            $this->assertSame(
                $cannonicalExpect,
                $cannonicalResult,
                'Test of cannonical xml-strings: ' . "\n" . $message
            );
            $this->assertSame(
                preg_replace('/\s/','',$cannonicalExpect),
                preg_replace('/\s/','',$cannonicalResult),
                'Test of cannonical xml-strings (whitespace-free): ' . "\n" . $message
            );
        }
    }


    public function dataProviderAllowedNamespacesInGeneratedSvgDomReturnArrayWithAllNamespacesWhichHasToBeRemoved()
    {
        $simpleSVG = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          version="1.1" width="810" height="540">
       <title>Sterne als Götterfunken</title>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" transform="scale(-1,1)"/>
		    </g>
			<g id="a">
			  <use xlink:href="#c" transform="rotate(72)"/>
			  <use xlink:href="#c" transform="rotate(144)"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        $extendedSVG = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
          xmlns:meyer="http://www.meyer.org/1999/meyer" 
          xmlns:schulze="http://www.schulze.org/1999/schulze" 
          version="1.1" width="810" height="540">
       <title>Sterne als Götterfunken</title>
       <meyer:title>Sterne als Götterfunken</meyer:title>
       <schulze:title>Sterne als Götterfunken</schulze:title>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" transform="scale(-1,1)"/>
		    </g>
			<g id="a">
			  <use xlink:href="#c" transform="rotate(72)"/>
			  <use xlink:href="#c" transform="rotate(144)"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $extendedSVGMeyerRemoved = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
           
          xmlns:schulze="http://www.schulze.org/1999/schulze" 
          version="1.1" width="810" height="540">
       <title>Sterne als Götterfunken</title>
       
       <schulze:title>Sterne als Götterfunken</schulze:title>
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" transform="scale(-1,1)"/>
		    </g>
			<g id="a">
			  <use xlink:href="#c" transform="rotate(72)"/>
			  <use xlink:href="#c" transform="rotate(144)"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;
        $extendedSVGRemoved = <<< XML
        <svg xmlns="http://www.w3.org/2000/svg" 
          xmlns:xlink="http://www.w3.org/1999/xlink" 
           
           
          version="1.1" width="810" height="540">
       <title>Sterne als Götterfunken</title>
       
       
       <defs>
	     <g id="s">
		   <g id="c">
		     <path id="t" d="M0,0v1h0.5z" transform="translate(0,-1)rotate(18)"/>
			 <use xlink:href="#t" transform="scale(-1,1)"/>
		    </g>
			<g id="a">
			  <use xlink:href="#c" transform="rotate(72)"/>
			  <use xlink:href="#c" transform="rotate(144)"/>
			</g>
			<use xlink:href="#a" transform="scale(-1,1)"/>
		  </g>
		 </defs>
         <rect fill="#039" width="810" height="540"/>
		 <g fill="#fc0" transform="scale(30)translate(13.5,9)">
		 <use xlink:href="#s" y="-6"/>
		 <use xlink:href="#s" y="6"/>
		 <g id="l">
		   <use xlink:href="#s" x="-6"/>
		   <use xlink:href="#s" transform="rotate(150)translate(0,6)rotate(66)"/>
		   <use xlink:href="#s" transform="rotate(120)translate(0,6)rotate(24)"/>
		   <use xlink:href="#s" transform="rotate(60)translate(0,6)rotate(12)"/>
		   <use xlink:href="#s" transform="rotate(30)translate(0,6)rotate(42)"/>
		 </g>
		 <use xlink:href="#l" transform="scale(-1,1)"/>
		</g>
     </svg>
XML;

        return [
            ['10. The array of namespace-definitions are empty. The svg is valid. xml contains an error. The last node is missing.',
                $simpleSVG,
                [
                    'namespace' => [],
                    'xmlStream' => $simpleSVG,
                ]
            ],
            ['20. The array of namespace-definitions are empty. A disallowed namespace-part, which is not part of the default-namespacees , wil be removed..',
                $extendedSVGRemoved,
                [
                    'namespace' => [],
                    'xmlStream' => $extendedSVG,
                ]
            ],
            ['30. The array of namespace-definitions is filled with  empty. A disallowed namespace-part, which is not part of the default-namespacees , wil be removed..',
                $extendedSVGMeyerRemoved,
                [
                    'namespace' => [
                        NamespaceService::PARAM_TYPE => 'add',
                        NamespaceService::PARAM_LIST_URL => 'http://www.schulze.org/1999/schulze'
                    ],
                    'xmlStream' => $extendedSVG,
                ]
            ],
            ['31. The array of namespace-definitions are empty. A disallowed namespace-part, which is not part of the default-namespacees , wil be removed..',
                $extendedSVG,
                [
                    'namespace' => [
                        NamespaceService::PARAM_TYPE => 'add',
                        NamespaceService::PARAM_LIST_URL => 'http://www.meyer.org/1999/meyer,http://www.schulze.org/1999/schulze'
                    ],
                    'xmlStream' => $extendedSVG,
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderAllowedNamespacesInGeneratedSvgDomReturnArrayWithAllNamespacesWhichHasToBeRemoved
     * @test
     */
    public function allowedNamespacesInGeneratedSvgDomReturnArrayWithAllNamespacesWhichHasToBeRemoved($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $domExpect = new \DOMDocument();
            $domExpect->loadXML($expect);
            $cannonicalExpect = $domExpect->documentElement->c14n();
            $helpDOM = $this->subject->allowedNamespacesInGeneratedSvgDom($params['namespace'],$params['xmlStream']);
            $cannonicalResult = $helpDOM->documentElement->c14n();
            $this->assertSame(
                $cannonicalExpect,
                $cannonicalResult,
                'Test of cannonical xml-strings: ' . "\n" . $message
            );
            $this->assertSame(
                preg_replace('/\s/','',$cannonicalExpect),
                preg_replace('/\s/','',$cannonicalResult),
                'Test of cannonical xml-strings (whitespace-free): ' . "\n" . $message
            );
        }
    }


    public function dataProviderAllowedNamespacesInGeneratedSvgDomThrowsExceptionIfGivenSVGisFail()
    {
        /**
         * generate a svg-stream with an parsing-error
         */
        return self::dataProviderDetectRemovableNamesspacesReturnExceptionIfGivenSVGisFail();
    }

    /**
     * @dataProvider dataProviderAllowedNamespacesInGeneratedSvgDomThrowsExceptionIfGivenSVGisFail
     * @test
     */
    public function allowedNamespacesInGeneratedSvgDomThrowsExceptionIfGivenSvgHasErrros($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $this->expectException($params['exception']);
            $dummy = $this->subject->detectRemovableNamesspaces($params['xmlStream']);
        }
    }


}

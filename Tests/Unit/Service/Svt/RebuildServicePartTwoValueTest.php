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

class RebuildServicePartTwoValueTest extends TestCase
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

    public function dataProviderValueRebuildDetailActionTextToMaxLengthReturnExpectedStringIfLengthAndMultiByteStringsAreVariated()
    {
        return [
            [
                ['no' => 'a1', 'info' => "`HÄßµ\n `" . ' is a six char long string with multibytes and new-Line-char.'],
                [
                    'result' => "HÄßµ\n ",
                    'strLen' => 9,
                    'mbstrLen' => 6,
                ],
                [
                    'maxLengthArray' => [RebuildService::SUB_PARAM_VALUE_MAX_LENGTH => 6],
                    'testString' => "HÄßµ\n TeststÄö?ßng\n",
                ],
            ],
            [
                ['no' => 'a2', 'info' => "`hallo `" . ' is a six char long string without multibytes. There are only Multibytes '],
                [
                    'result' => "hallo ",
                    'strLen' => 6,
                    'mbstrLen' => 6,
                ],
                [
                    'maxLengthArray' => [RebuildService::SUB_PARAM_VALUE_MAX_LENGTH => 6],
                    'testString' => "hallo TeststÄö?ßng\n",
                ],
            ],
            [
                ['no' => 'a3', 'info' => "`Hâllò´\n\r `" . ' is a nine char long string with multibytes and new-Line-char and charrige-return.'],
                [
                    'result' => "Hâllò´\n\r ",
                    'strLen' => 12,
                    'mbstrLen' => 9,
                ],
                [
                    'maxLengthArray' => [RebuildService::SUB_PARAM_VALUE_MAX_LENGTH => 9],
                    'testString' => "Hâllò´\n\r GGâllò´\n Jâllò´\n\r ",
                ],
            ],
            [
                ['no' => 'a4', 'info' => "Hâllò´\n\r " . 'is a four multibyte-char long string and shorter than 5 multibyte-chars.'],
                [
                    'result' => "äüöß",
                    'strLen' => 8,
                    'mbstrLen' => 4,
                ],
                [
                    'maxLengthArray' => [RebuildService::SUB_PARAM_VALUE_MAX_LENGTH => 5],
                    'testString' => "äüöß",
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderValueRebuildDetailActionTextToMaxLengthReturnExpectedStringIfLengthAndMultiByteStringsAreVariated
     * @test
     */
    public function valueRebuildDetailActionTextToMaxLengthReturnExpectedStringGivenDifferentLengthTeststringVariations($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $resultString = $this->subject->valueRebuildDetailActionTextToMaxLength($param['maxLengthArray'], $param['testString']);

            $this->assertSame(
                $expect['result'],
                $resultString,
                $message['no'] . ' => Stringtest: ' . "\n" . $message['info']
            );
            $this->assertSame(
                $expect['strLen'],
                strlen($resultString),
                $message['no'] . ' => Lentest: ' . "\n" . $message['info']
            );
            $this->assertSame(
                $expect['mbstrLen'],
                mb_strlen($resultString),
                $message['no'] . ' => MB-Lentest: ' . "\n" . $message['info']
            );
        }
    }

    public function dataProviderValueRebuildDetailActionAppendReturnVoidChangeTextInDomElement()
    {
        return [
            [
                ['no' => 'b1', 'info' => "` und Tschüss`" . ' are appended to a simple DOMNode of the document.'],
                [
                    'result' => "HÄßµ\n ",
                    'strLen' => 9,
                    'mbstrLen' => 6,
                ],
                [
                    'xmlStart' => '<text>Hallo</text>',
                    'xmlExpect' => '<text>Hallo und Tschüss</text>',
                    'findNodeName' => 'text',
                    'testAppendArray' => [
                        RebuildService::SUB_PARAM_VALUE_NEW => ' und Tschüss',
                    ],
                ],
            ],
            [
                ['no' => 'b2', 'info' => "` und Tschüss`" . ' are appended to twoe simple DOMNode of the document.'],
                [
                    'result' => "HÄßµ\n ",
                    'strLen' => 9,
                    'mbstrLen' => 6,
                ],
                [
                    'xmlStart' => '<root>
  <text>Hallo</text>
  <text>Super</text>
</root>',
                    'xmlExpect' => '<root>
  <text>Hallo und Tschüss</text>
  <text>Super und Tschüss</text>
</root>',
                    'findNodeName' => 'text',
                    'testAppendArray' => [
                        RebuildService::SUB_PARAM_VALUE_NEW => ' und Tschüss',
                    ],
                ],
            ],
            [
                ['no' => 'b3', 'info' => "` und Tschüss`" . ' are appended to an dom-Element with injected nodes. The internal Node will be deleted (!).'],
                [
                    'result' => "HÄßµ\n ",
                    'strLen' => 9,
                    'mbstrLen' => 6,
                ],
                [
                    'xmlStart' => '<root>
  <text>Hal<g>gustav</g>lo</text>
  <text>Super</text>
</root>',
                    'xmlExpect' => '<root>
  <text>Halgustavlo und Tschüss</text>
  <text>Super und Tschüss</text>
</root>',
                    'findNodeName' => 'text',
                    'testAppendArray' => [
                        RebuildService::SUB_PARAM_VALUE_NEW => ' und Tschüss',
                    ],
                ],
            ],
            [
                [
                    'no' => 'b4',
                    'info' => "` und Tschüss`" . ' are appended. ' .
                        'The max-length will respected. The internal Node will be deleted (!).'
                ],
                [
                    'result' => "HÄßµ\n ",
                    'strLen' => 9,
                    'mbstrLen' => 6,
                ],
                [
                    'xmlStart' => '<root>
  <text>Hal<g>gußtav</g>lo</text>
  <text>Super</text>
</root>',
                    'xmlExpect' => '<root>
  <text>Halgußtavlo und Ts</text>
  <text>Super und Tschüss</text>
</root>',
                    'findNodeName' => 'text',
                    'testAppendArray' => [
                        RebuildService::SUB_PARAM_VALUE_NEW => ' und Tschüss',
                        RebuildService::SUB_PARAM_VALUE_MAX_LENGTH => '18',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderValueRebuildDetailActionAppendReturnVoidChangeTextInDomElement
     * @test
     */
    public function valueRebuildDetailActionAppendReturnVoidChangeTextInDomElement($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $doc = new \DOMDocument();
            $doc->loadXML($param['xmlStart']);
            $expect = new \DOMDocument();
            $expect->loadXML($param['xmlExpect']);

            $testExpect = $expect->C14N(true, false);

            $testNodes = $doc->getElementsByTagName($param['findNodeName']);
            foreach ($testNodes as $testNode) {
                $this->subject->valueRebuildDetailActionAppend($param['testAppendArray'], $testNode);
            }
            $resultNew = $doc->C14N(true, false);
            $this->assertSame(
                $testExpect,
                $resultNew,
                $message['no'] . '-' . $message['info'] . "\n - " . $testExpect . "\n - " . $resultNew
            );
        }
    }

    public function dataProviderValueRebuildDetailActionPrependReturnVoidChangeTextInDomElement()
    {
        return [
            [
                ['no' => 'c1', 'info' => "` und Tschüss`" . ' are prepended to a simple DOMNode of the document.'],
                [
                    'result' => "HÄßµ\n ",
                    'strLen' => 9,
                    'mbstrLen' => 6,
                ],
                [
                    'xmlStart' => '<text>Hallo</text>',
                    'xmlExpect' => '<text> Liebü Bäutel @µHallo</text>',
                    'findNodeName' => 'text',
                    'testPrependArray' => [
                        RebuildService::SUB_PARAM_VALUE_NEW => ' Liebü Bäutel @µ',
                    ],
                ],
            ],
            [
                ['no' => 'c2', 'info' => "` und Tschüss`" . ' are prepended to twoe simple DOMNode of the document.'],
                [
                    'result' => "HÄßµ\n ",
                    'strLen' => 9,
                    'mbstrLen' => 6,
                ],
                [
                    'xmlStart' => '<root>
  <text>Hallo</text>
  <text>Super</text>
</root>',
                    'xmlExpect' => '<root>
  <text> Liebü Bäutel @µHallo</text>
  <text> Liebü Bäutel @µSuper</text>
</root>',
                    'findNodeName' => 'text',
                    'testPrependArray' => [
                        RebuildService::SUB_PARAM_VALUE_NEW => ' Liebü Bäutel @µ',
                    ],
                ],
            ],
            [
                ['no' => 'c3', 'info' => "` und Tschüss`" . ' are prepended to an dom-Element with injected nodes. The internal Node will be deleted (!).'],
                [
                    'result' => "HÄßµ\n ",
                    'strLen' => 9,
                    'mbstrLen' => 6,
                ],
                [
                    'xmlStart' => '<root>
  <text>Hal<g>gustav</g>lo</text>
  <text>Super</text>
</root>',
                    'xmlExpect' => '<root>
  <text> Liebü Bäutel @µHalgustavlo</text>
  <text> Liebü Bäutel @µSuper</text>
</root>',
                    'findNodeName' => 'text',
                    'testPrependArray' => [
                        RebuildService::SUB_PARAM_VALUE_NEW => ' Liebü Bäutel @µ',
                    ],
                ],
            ],
            [
                [
                    'no' => 'c4',
                    'info' => "` und Tschüss`" . ' are prepended. ' .
                        'The max-length will respected. The internal Node will be deleted (!).'
                ],
                [
                    'result' => "HÄßµ\n ",
                    'strLen' => 9,
                    'mbstrLen' => 6,
                ],
                [
                    'xmlStart' => '<root>
  <text>Hal<g>gußtav</g>lo</text>
  <text>Super</text>
</root>',
                    'xmlExpect' => '<root>
  <text> Liebü Bäutel @µHalgußt</text>
  <text> Liebü Bäutel @µSuper</text>
</root>',
                    'findNodeName' => 'text',
                    'testPrependArray' => [
                        RebuildService::SUB_PARAM_VALUE_NEW => ' Liebü Bäutel @µ',
                        RebuildService::SUB_PARAM_VALUE_MAX_LENGTH => '23',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderValueRebuildDetailActionPrependReturnVoidChangeTextInDomElement
     * @test
     */
    public function valueRebuildDetailActionPrependReturnVoidChangeTextInDomElement($message, $expect, $param)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $doc = new \DOMDocument();
            $doc->loadXML($param['xmlStart']);
            $expect = new \DOMDocument();
            $expect->loadXML($param['xmlExpect']);

            $testExpect = $expect->C14N(true, false);

            $testNodes = $doc->getElementsByTagName($param['findNodeName']);
            foreach ($testNodes as $testNode) {
                $this->subject->valueRebuildDetailActionPrepend($param['testPrependArray'], $testNode);
            }
            $resultNew = $doc->C14N(true, false);
            $this->assertSame(
                $testExpect,
                $resultNew,
                $message['no'] . '-' . $message['info'] . "\n - " . $testExpect . "\n - " . $resultNew
            );
        }
    }


}

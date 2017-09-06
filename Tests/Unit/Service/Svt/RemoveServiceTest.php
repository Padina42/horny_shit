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

class RemoveServiceTest extends TestCase
{


    /**
     * @var RemoveService
     */
    protected $subject = NULL;

    public function setUp()
    {
        $this->subject = new RemoveService();
    }

    public function tearDown()
    {
        unset($this->subject);
    }


    /**
     * @test
     */
    public function getDefaultTagsReturnsInitialValueForString()
    {
        $this->assertSame(
            RemoveService::INITIATIVE_DEFAULT_LIST_TAGS,
            $this->subject->getDefaultTags(),
            'test getDefaultTags'
        );
    }

    /**
     * @test
     */
    public function setDefaultTagsForStringSetsName()
    {
        $this->subject->setDefaultTags('hallo');

        $this->assertAttributeEquals(
            'hallo',
            'defaultTags',
            $this->subject,
            'test setDefaultTags'
        );
    }


    /**
     * @test
     */
    public function getDefaultAttributesReturnsInitialValueForString()
    {
        $this->assertSame(
            RemoveService::INITIATIVE_DEFAULT_ATTRIBUTE_LIST,
            $this->subject->getDefaultAttributes(),
            'test getDefaultAttributes'
        );
    }

    /**
     * @test
     */
    public function setDefaultAttributesForStringSetsName()
    {
        $this->subject->setDefaultAttributes('hallo');

        $this->assertAttributeEquals(
            'hallo',
            'defaultAttributes',
            $this->subject,
            'test setDefaultAttributes'
        );
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
            ['30. False is ever expected, if an empty array is set.',
                false,
                ['param' => []]],
            ['40. False is ever expected, if the array contains an one  key than the allowed definitions.',
                false,
                ['param' => ['notallowed' => 'on']]],
            ['50. False is ever expected, if the array contains two entries, where one key is not allowed.',
                false,
                ['param' => [
                    RemoveService::PARAM_ATTRIBUTES => 'onload,style,class',
                    'notallowed' => 'on'
                ]]],
            ['60. False is ever expected, if the array contains two entries, where one key is not allowed.',
                false,
                ['param' => [
                    RemoveService::PARAM_TAGS => 'svg,g,image',
                    'notallowed' => 'on'
                ]]],
            ['70. False is ever expected, if the array contains the two allowwed entries and a key, that is not allowed.',
                false,
                ['param' => [
                    RemoveService::PARAM_TAGS => 'svg,g,image',
                    RemoveService::PARAM_ATTRIBUTES => 'onload,style,class',
                    'notallowed' => 'on'
                ]]],
            ['80. true is ever expected, if the array contains the two allowwed entries and get an comma at the end of the array notation ' .
                '(Experience: will be ignored by PHP).',
                true,
                ['param' => [
                    RemoveService::PARAM_TAGS => 'svg,g,image',
                    RemoveService::PARAM_ATTRIBUTES => 'onload,style,class',
                ]]],
            ['90. true is expected, if the array contains the two allowed entries.',
                true,
                ['param' => [
                    RemoveService::PARAM_TAGS => 'svg,g,image',
                    RemoveService::PARAM_ATTRIBUTES => 'onload,style,class'
                ]]],
            ['100. true is expected, if the array contains the allowed entry ´' . RemoveService::PARAM_TAGS . '´.',
                true,
                ['param' => [
                    RemoveService::PARAM_ATTRIBUTES => 'onload,style,class'
                ]]],
            ['110. true is expected, if the array contains the allowed entry ´' . RemoveService::PARAM_ATTRIBUTES . '´.',
                true,
                ['param' => [
                    RemoveService::PARAM_TAGS => 'svg,g,image'
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
     * @param $xmlDom
     */
    public function forTestNormalizeXmlDocumentToStringWithoutWhitespaces($xmlDom)
    {
        $xmlString = '';
        if (!is_null($xmlDom)) {

            $xmlString = $xmlDom->saveXml();
            $xmlString = preg_replace('/\s/', '', $xmlString);
        }
        return $xmlString;
    }

    public function dataProviderRemoveAllTagsReturnExpectedDomObjectIfTheSpecefiedTagsHasToBeDeleted()
    {
        $baseXml = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <empty></empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty></deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;

        $resXmlMissingThead = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN" "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd">
<book id="listing">
 <variant attribute="hallo">hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');">My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo"/>
  <lonely/>
  <ontear onload="ups();"/>
  <empty/>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');">
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo">hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo"/>
      <deepLonely/>
      <deepOntear onload="ups();"/>
      <deepEmpty/>
     
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;
        $resXmlMissingTwoSingle = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <lonely />
  <ontear onload="ups();" />
  <empty></empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty></deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;
        $resXmlMissingOneLonely = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <ontear onload="ups();" />
  <empty></empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty></deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;
        $resXmlMissingOneOntear = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <empty></empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty></deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;
        $resXmlMissingOneEmpty = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty></deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;
        $resXmlMissingTwoDeepSingle = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <empty></empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty></deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;
        $resXmlMissingOneDeepLonely = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <empty></empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepOntear onload="ups();" />
      <deepEmpty></deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;
        $resXmlMissingOneDeepOntear = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <empty></empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepEmpty></deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;
        $resXmlMissingOneDeepEmpty = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <empty></empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;
        $resXmlMissingMultiEntry = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <empty></empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty></deepEmpty>
     <thead>
      <row>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
      </row>
      <row>
      </row>
      <row>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;
        $resXmlMissingTwoVariant = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <empty></empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty></deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;

        $resXmlMissingTheadAndEntry = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <empty></empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty></deepEmpty>
     <tbody>
      <row>
       <title>My chapter</title>
      </row>
      <row>
      </row>
      <row>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;

        $resXmlMissingTheadAndEntryAndTitle = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <chapter id="books">
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <empty></empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty></deepEmpty>
     <tbody>
      <row>
      </row>
      <row>
      </row>
      <row>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;

        return [
            ['10. There is defined no tag. The unchanged dom will returned.',
                $baseXml,
                [
                    'tagList' => '',
                    'xmlString' => $baseXml,
                ]
            ],
            ['20. There is defined one tag, which is not part of the xml. The unchanged dom will returned.',
                $baseXml,
                [
                    'tagList' => 'nopart',
                    'xmlString' => $baseXml,
                ]
            ],
            ['21. There are defined two tag in a spaced comma-separated listhich are both not part of the xml. The unchanged dom will returned.',
                $baseXml,
                [
                    'tagList' => 'nopart,noparttwo',
                    'xmlString' => $baseXml,
                ]
            ],
            ['22. There are defined two tag in a spaced comma-separated list, which are both not part of the xml. The unchanged dom will returned.',
                $baseXml,
                [
                    'tagList' => 'nopart,noparttwo',
                    'xmlString' => $baseXml,
                ]
            ],
            ['23. There is defined one tag, which is only as an attribute part of the xml. The unchanged dom will returned.',
                $baseXml,
                [
                    'tagList' => 'onload',
                    'xmlString' => $baseXml,
                ]
            ],
            ['24. There is defined one tag, which differ in upper- and lower-case from the part of the xml. The unchanged dom will returned.',
                $baseXml,
                [
                    'tagList' => 'Title',
                    'xmlString' => $baseXml,
                ]
            ],
            ['25. There is defined two tags, which differ both in upper- and lower-case from the part of the xml. The unchanged dom will returned.',
                $baseXml,
                [
                    'tagList' => 'Title, Thead',
                    'xmlString' => $baseXml,
                ]
            ],
            ['31. There is defined one tag, which has child and exist one time in the xml. The node will removed from the DOM and the result is given back.',
                $resXmlMissingThead,
                [
                    'tagList' => 'thead',
                    'xmlString' => $baseXml,
                ]
            ],
            ['40. There are two tags without a child in the root-node. The nodes will removed from the DOM and the result is given back.',
                $resXmlMissingTwoSingle,
                [
                    'tagList' => 'single',
                    'xmlString' => $baseXml,
                ]
            ],
            ['41. There is one singleton tag without a child in the root-node. The node will removed from the DOM and the result is given back.',
                $resXmlMissingOneLonely,
                [
                    'tagList' => 'lonely',
                    'xmlString' => $baseXml,
                ]
            ],
            ['42. There is one singleton tag without a child in the root-node. The node will removed from the DOM and the result is given back.',
                $resXmlMissingOneOntear,
                [
                    'tagList' => 'ontear',
                    'xmlString' => $baseXml,
                ]
            ],
            ['43. There is one empty tag without a child in the root-node. The node will removed from the DOM and the result is given back.',
                $resXmlMissingOneEmpty,
                [
                    'tagList' => 'empty',
                    'xmlString' => $baseXml,
                ]
            ],
            ['44. There are two tags without a child in the deeper child-node. The nodes will removed from the DOM and the result is given back.',
                $resXmlMissingTwoDeepSingle,
                [
                    'tagList' => 'deepSingle',
                    'xmlString' => $baseXml,
                ]
            ],
            ['45. There is one singleton tag without a child in the deeper child-node. The node will removed from the DOM and the result is given back.',
                $resXmlMissingOneDeepLonely,
                [
                    'tagList' => 'deepLonely',
                    'xmlString' => $baseXml,
                ]
            ],
            ['46. There is one singleton tag without a child in the deeper child-node. The node will removed from the DOM and the result is given back.',
                $resXmlMissingOneDeepOntear,
                [
                    'tagList' => 'deepOntear',
                    'xmlString' => $baseXml,
                ]
            ],
            ['47. There is one empty tag without a child in the deeper child-node. The node will removed from the DOM and the result is given back.',
                $resXmlMissingOneDeepEmpty,
                [
                    'tagList' => 'deepEmpty',
                    'xmlString' => $baseXml,
                ]
            ],
            ['48. There are multiple tag, which has no child and spread over various deeper child-nodes. The nodes will removed from the DOM and the result is given back.',
                $resXmlMissingMultiEntry,
                [
                    'tagList' => 'entry',
                    'xmlString' => $baseXml,
                ]
            ],
            ['49. There is defined one tag, which is defined in the root-node and in some deeper child. The node will removed from the DOM and the result is given back.',
                $resXmlMissingTwoVariant,
                [
                    'tagList' => 'variant',
                    'xmlString' => $baseXml,
                ]
            ],
            ['51.a There is defined two tags. One tag is part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tag will removed from the DOM and the result is given back.',
                $resXmlMissingThead, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => 'thead,Entry',
                    'xmlString' => $baseXml,
                ]
            ],
            ['51.b There is defined two tags. The order changed and spaces added to the commaseparated list. ' .
                'One tag is part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tag will removed from the DOM and the result is given back.',
                $resXmlMissingThead, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => 'Entry, thead',
                    'xmlString' => $baseXml,
                ]
            ],
            ['52.a There is defined three tags. Two tags are part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tags will removed from the DOM and the result is given back.',
                $resXmlMissingTheadAndEntry, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => 'thead,entry,Title',
                    'xmlString' => $baseXml,
                ]
            ],
            ['52.a.b There is defined three tags. The order changed and spaces added to the commaseparated list. ' .
                'Two tags are part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tags will removed from the DOM and the result is given back.',
                $resXmlMissingTheadAndEntry, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => 'entry,thead ,Title',
                    'xmlString' => $baseXml,
                ]
            ],
            ['52.b There is defined three tags. The order changed and spaces added to the commaseparated list. ' .
                'Two tags are part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tags will removed from the DOM and the result is given back.',
                $resXmlMissingTheadAndEntry, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => 'thead, Title, entry',
                    'xmlString' => $baseXml,
                ]
            ],
            ['52.b There is defined three tags. The order changed and spaces added to the commaseparated list. ' .
                'Two tags are part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tags will removed from the DOM and the result is given back.',
                $resXmlMissingTheadAndEntry, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => '  entry , Title, thead ',
                    'xmlString' => $baseXml,
                ]
            ],
            ['52.c There is defined three tags. The order changed and spaces added to the commaseparated list. ' .
                'Two tags are part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tags will removed from the DOM and the result is given back.',
                $resXmlMissingTheadAndEntry, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => 'Title, thead, entry ',
                    'xmlString' => $baseXml,
                ]
            ],
            ['52.c.b There is defined three tags. The order changed and spaces added to the commaseparated list. ' .
                'Two tags are part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tags will removed from the DOM and the result is given back.',
                $resXmlMissingTheadAndEntry, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => 'Title, entry, thead',
                    'xmlString' => $baseXml,
                ]
            ],
            ['53.a There is defined three tags. Add a empty definition athe end.' .
                'Two tags are part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tags will removed from the DOM and the result is given back.' .
                'The empty definitions are ignored.',
                $resXmlMissingTheadAndEntry, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => ' Title, entry, thead,',
                    'xmlString' => $baseXml,
                ]
            ],
            ['53.b There is defined three tags. Add a empty definition at the and and in the middle. ' .
                'Two tags are part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tags will removed from the DOM and the result is given back.' .
                'The empty definitions are ignored.',
                $resXmlMissingTheadAndEntry, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => 'Title, ,entry,, thead,',
                    'xmlString' => $baseXml,
                ]
            ],
            ['53.c There is defined three tags. Add a empty definition in the middle. ' .
                'Two tags are part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tags will removed from the DOM and the result is given back.' .
                'The empty definitions are ignored.',
                $resXmlMissingTheadAndEntry, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => ' Title, entry,, thead',
                    'xmlString' => $baseXml,
                ]
            ],
            ['53.d There is defined three tags. Add a empty definition at the start. ' .
                'Two tags are part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tags will removed from the DOM and the result is given back.' .
                'The empty definitions are ignored.',
                $resXmlMissingTheadAndEntry, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => ' ,Title, entry, thead',
                    'xmlString' => $baseXml,
                ]
            ],
            ['53.e There is defined three tags. Add a empty definition at the start and in the middle. ' .
                'Two tags are part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tags will removed from the DOM and the result is given back.' .
                'The empty definitions are ignored.',
                $resXmlMissingTheadAndEntry, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => ' ,Title,, entry,, thead',
                    'xmlString' => $baseXml,
                ]
            ],
            ['53.f There is defined three tags. Add a empty definition at the start, in the middle and at the end. ' .
                'Two tags are part of the xml. The other tag differ in upper-lower-case from ' .
                'an tag. The existing tags will removed from the DOM and the result is given back. ' .
                'The empty definitions are ignored.',
                $resXmlMissingTheadAndEntry, // $resXmlMissingTheadAndEntryAndTitle
                [
                    'tagList' => ' ,Title,, entry,, thead,',
                    'xmlString' => $baseXml,
                ]
            ],
            ['53.f There is defined three tags. all tags are part of the xml.' .
                'The existing tags will removed from the DOM and the result is given back. ',
                $resXmlMissingTheadAndEntryAndTitle,
                [
                    'tagList' => ' title, entry,thead',
                    'xmlString' => $baseXml,
                ]
            ],

        ];
    }

    /**
     *
     * @dataProvider dataProviderRemoveAllTagsReturnExpectedDomObjectIfTheSpecefiedTagsHasToBeDeleted
     * @test
     */
    public function removeAllTagsReturnExpectedDomObjectIfTheSpecefiedTagsHasToBeDeleted($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $xmlDom = new \DOMDocument();
            if ($xmlDom->loadXML($params['xmlString']) === false) {
                throw new \DomainException('The parsing of the Expection-XML fails in the test.',
                    1492412565);
            }
            $expectDom = new \DOMDocument();
            if ($expectDom->loadXML($expect) === false) {
                throw new \DomainException('The parsing of the Actual-XML fails in the test.',
                    1492412566);
            }
            $this->subject->setDefaultTags('');
            $this->subject->setDefaultAttributes('');
            $expectString = self::forTestNormalizeXmlDocumentToStringWithoutWhitespaces($expectDom);
            $actualString = self::forTestNormalizeXmlDocumentToStringWithoutWhitespaces($this->subject->removeAllTags($params['tagList'], $xmlDom));
            $this->assertSame(
                $expectString,
                $actualString,
                $message
            );
        }
    }

    public function dataProviderRemoveAllAttributesReturnExpectedDomObjectIfTheSpecefiedAttributesHasToBeDeleted()
    {
        $baseXml = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <empty> </empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty> </deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;

        $resXmlWithMissingOnchange = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <empty> </empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty> </deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;

        $baseXml = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear onload="ups();" />
  <empty> </empty>
  <para onload="alert('hallo Welt');" onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear onload="ups();" />
      <deepEmpty> </deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry onload="alert('hallo Welt');">Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onload="alert('hallo Welt');" onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;

        $resXmlWithMissingOnchangeAndOnload = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear />
  <empty> </empty>
  <para onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear />
      <deepEmpty> </deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry >Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onblur="alert('hallo Universum');">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;

        $resXmlWithMissingOnchangeAndOnloadAndOnblur = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear />
  <empty> </empty>
  <para >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear />
      <deepEmpty> </deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry >Author</entry>
       <entry >Language</entry>
       <entry >ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;

        $resXmlWithMissingOnload = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title onblur="alert('hallo Universum');" >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear />
  <empty> </empty>
  <para onblur="alert('hallo Universum');" >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear />
      <deepEmpty> </deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry >Author</entry>
       <entry onblur="alert('hallo Universum');" >Language</entry>
       <entry onblur="alert('hallo Universum');" onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;

        $resXmlWithMissingOnloadAndOnblur = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE book PUBLIC "-//OASIS//DTD DocBook XML V4.1.2//EN"
 "http://www.oasis-open.org/docbook/xml/4.1.2/docbookx.dtd" >
<book id="listing">
 <variant attribute="hallo" >hallo</variant>
 <title >My lists</title>
 <chapter id="books">
  <title>My books</title>
  <single>My Info</single>
  <single attribute="hallo" />
  <lonely />
  <ontear />
  <empty> </empty>
  <para >
   <informaltable>
    <tgroup cols="4">
      <variant attribute="hallo" >hallo</variant>
      <deepSingle>My Info</deepSingle>
      <deepSingle attribute="hallo" />
      <deepLonely />
      <deepOntear />
      <deepEmpty> </deepEmpty>
     <thead>
      <row>
       <entry onLoad="alert('hallo Welt');" onBlur="alert('hallo Universum');" >Title</entry>
       <entry >Author</entry>
       <entry >Language</entry>
       <entry onchange="function blau(){alert('ups')}; blau();">ISBN</entry>
      </row>
     </thead>
     <tbody>
      <row>
       <title>My chapter</title>
       <entry>The Grapes of Wrath</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>0140186409</entry>
      </row>
      <row>
       <entry>The Pearl</entry>
       <entry>John Steinbeck</entry>
       <entry>en</entry>
       <entry>014017737X</entry>
      </row>
      <row>
       <entry>Samarcande</entry>
       <entry>Amine Maalouf</entry>
       <entry>fr</entry>
       <entry>2253051209</entry>
      </row>
      <!-- TODO: I have a lot of remaining books to add.. -->
     </tbody>
    </tgroup>
   </informaltable>
  </para>
 </chapter>
</book>
XML;

        return [
            ['10. There is defined no attribute. The unchanged dom will returned.',
                $baseXml,
                [
                    'tagList' => '',
                    'xmlString' => $baseXml,
                ]
            ],
            ['20. There is defined one attribute, which is not part of the xml. The unchanged dom will returned.',
                $baseXml,
                [
                    'tagList' => 'onclear',
                    'xmlString' => $baseXml,
                ]
            ],
            ['21. There are defined two attributes, which are both not part of the xml. The unchanged dom will returned.',
                $baseXml,
                [
                    'tagList' => 'onclear,onclean',
                    'xmlString' => $baseXml,
                ]
            ],
            ['23. There is defined one attribute, which differ in uppeer- and lowerr-case from the defined attributes in the xml. The unchanged dom will returned.',
                $baseXml,
                [
                    'tagList' => 'onChange',
                    'xmlString' => $baseXml,
                ]
            ],
            ['31. There is defined one attribute. The attribute will removed. The chaged dom will returned.',
                $resXmlWithMissingOnchange,
                [
                    'tagList' => 'onchange',
                    'xmlString' => $baseXml,
                ]
            ],
            ['31.b There is defined two attribute. One attribute will removed. ' .
                'The other differ in lower-uper-case and dont exist. The chaged dom will returned.',
                $resXmlWithMissingOnchange, // $resXmlWithMissingOnloadAndOnblur, $resXmlWithMissingOnchangeAndOnloadAndOnblur
                [
                    'tagList' => 'onchange,OnLoad',
                    'xmlString' => $baseXml,
                ]
            ],
            ['32.a There are defined two attributes. Both will removed. ' .
                'The chaged dom will returned.',
                $resXmlWithMissingOnchangeAndOnload, // $resXmlWithMissingOnloadAndOnblur, $resXmlWithMissingOnchangeAndOnloadAndOnblur
                [
                    'tagList' => 'onchange,onload',
                    'xmlString' => $baseXml,
                ]
            ],
            ['32.a.a There are defined two attributes. Both will removed. Add empty/space-filled attribute at start and space at start' .
                'The chaged dom will returned.',
                $resXmlWithMissingOnchangeAndOnload, // $resXmlWithMissingOnloadAndOnblur, $resXmlWithMissingOnchangeAndOnloadAndOnblur
                [
                    'tagList' => ' , onchange,onload',
                    'xmlString' => $baseXml,
                ]
            ],
            ['32.a.b There are defined two attributes. Both will removed. Add empty attribute in the middle and space at the end ' .
                'The chaged dom will returned.',
                $resXmlWithMissingOnchangeAndOnload, // $resXmlWithMissingOnloadAndOnblur, $resXmlWithMissingOnchangeAndOnloadAndOnblur
                [
                    'tagList' => 'onchange ,,onload',
                    'xmlString' => $baseXml,
                ]
            ],
            ['32.a.c There are defined two attributes. Both will removed. Add empty attribute in the end and space at front and end. ' .
                'The chaged dom will returned.',
                $resXmlWithMissingOnchangeAndOnload, // $resXmlWithMissingOnloadAndOnblur, $resXmlWithMissingOnchangeAndOnloadAndOnblur
                [
                    'tagList' => ' onchange , onload ,',
                    'xmlString' => $baseXml,
                ]
            ],
            ['32.b There are defined two attributes. Both will removed. Both have multiple outcomes.' .
                'Add empty/space-filled attribute at start and space at start' .
                'The chaged dom will returned.',
                $resXmlWithMissingOnloadAndOnblur, // , $resXmlWithMissingOnchangeAndOnloadAndOnblur
                [
                    'tagList' => ' onload, onblur',
                    'xmlString' => $baseXml,
                ]
            ],
            ['32.b.a There are defined two attributes. Both will removed. Both have multiple outcomes' .
                'Add empty/space-filled attribute at start and space at start' .
                'The chaged dom will returned.',
                $resXmlWithMissingOnloadAndOnblur, // , $resXmlWithMissingOnchangeAndOnloadAndOnblur
                [
                    'tagList' => ' ,    ,  , onload , onblur',
                    'xmlString' => $baseXml,
                ]
            ],
            ['32.b.b There are defined two attributes. Both will removed. Both have multiple outcomes' .
                'Add empty attribute in the middle and space at the end.' .
                'The chaged dom will returned.',
                $resXmlWithMissingOnloadAndOnblur, // , $resXmlWithMissingOnchangeAndOnloadAndOnblur
                [
                    'tagList' => ',onload ,  , onblur,,,,',
                    'xmlString' => $baseXml,
                ]
            ],
            ['32.b.b There are defined three attributes. All will removed. ' .
                'The changed dom will returned.',
                $resXmlWithMissingOnchangeAndOnloadAndOnblur,
                [
                    'tagList' => ',onload ,  , onblur,,,,onchange',
                    'xmlString' => $baseXml,
                ]
            ],
        ];
    }

    /**
     *
     * @dataProvider dataProviderRemoveAllAttributesReturnExpectedDomObjectIfTheSpecefiedAttributesHasToBeDeleted
     * @test
     */
    public function removeAllAttributesReturnExpectedDomObjectIfTheSpecefiedTagsHasToBeDeleted($message, $expect, $params)
    {
        if (!isset($expect) && empty($expect)) {
            $this->assertSame(true, true, 'empty-data at the End of the provider');
        } else {
            $xmlDom = new \DOMDocument();
            if ($xmlDom->loadXML($params['xmlString']) === false) {
                throw new \DomainException('The parsing of the Expection-XML fails in the test.',
                    1492412565);
            }
            $expectDom = new \DOMDocument();
            if ($expectDom->loadXML($expect) === false) {
                throw new \DomainException('The parsing of the Actual-XML fails in the test.',
                    1492412566);
            }
            $this->subject->setDefaultTags('');
            $this->subject->setDefaultAttributes('');

            $expectString = self::forTestNormalizeXmlDocumentToStringWithoutWhitespaces($expectDom);
            $actualString = self::forTestNormalizeXmlDocumentToStringWithoutWhitespaces($this->subject->removeAllAttributes($params['tagList'], $xmlDom));
            $this->assertSame(
                $expectString,
                $actualString,
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

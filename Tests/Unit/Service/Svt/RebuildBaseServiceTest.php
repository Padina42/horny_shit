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

class RebuildBaseServiceTest extends TestCase
{

    /**
     * @var RebuildBaseService
     */
    protected $subject = null;


    public function setUp()
    {
        $this->subject = new RebuildBaseService();
    }

    public function tearDown()
    {
        unset($this->subject);
    }



    /**
     * @test
     */
    public function getRepeatMaxoReturnsInitialValueForNumber()
    {
        $this->assertSame(
            RebuildBaseService::DEFAULT_REPEAT_MAX,
            $this->subject->getRepeatMax()
        );
    }

    /**
     * @test
     */
    public function setRepeatMaxoForStringSetsRepeatMaxoWithGettterCheck()
    {
        $this->subject->setRepeatMax(458);

        $this->assertAttributeEquals(
            458,
            'repeatMax',
            $this->subject
        );
        $this->assertSame(
            458,
            $this->subject->getRepeatMax(),
            $this->subject
        );
    }

}

<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008 AOE media GmbH
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class tx_realurl_pagepath_testcase
 */
class tx_realurl_pagepath_testcase extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * just test that 0 is returned even if nothing is submitted and realurl returns non-int
     *
     * @test
     * @return void
     */
    public function defaultLanguageIs0()
    {
        $mock = $this->getMockBuilder(tx_realurl::class)
            ->setMethods(['getRetrievedPreGetVar'])
            ->getMock();
        $mock->expects($this->any())
            ->method('getRetrievedPreGetVar')
            ->will($this->returnValue(false));

        $pp = new tx_realurl_pagepath();
        $pp->_setConf([]);
        $pp->_setParent($mock);

        $this->assertSame(0, $pp->_getLanguageVarEncode(), 'Wrong default language');
    }

    /**
     *
     * @test
     * @return void
     */
    public function basicLanguageDetectionWorks()
    {
        $mock = $this->getMockBuilder(tx_realurl::class)
            ->getMock();
        $mock->orig_paramKeyValues['L'] = 3;

        $pp = new tx_realurl_pagepath();
        $pp->_setConf(['languageGetVar' => 'L']);
        $pp->_setParent($mock);

        $this->assertSame(3, $pp->_getLanguageVarEncode(), 'Wrong language detected');
    }

    /**
     *
     * @test
     * @return void
     */
    public function abbrevLisTheDefaultAbbreviation()
    {
        $mock = $this->getMockBuilder(tx_realurl::class)
            ->getMock();
        $mock->orig_paramKeyValues['L'] = 3;

        $pp = new tx_realurl_pagepath();
        $pp->_setConf([]);
        $pp->_setParent($mock);

        $this->assertSame(3, $pp->_getLanguageVarEncode(), 'it seems that L is not used as default-parameter for the language detection');
    }

    /**
     * makes sure that there's no fixed dependency to the "L" anymore
     *
     * @test
     * @return void
     */
    public function languageAbbrevCanBeChanged()
    {
        $mock = $this->getMockBuilder(tx_realurl::class)
            ->getMock();
        $mock->orig_paramKeyValues['L'] = 3;
        $mock->orig_paramKeyValues['newL'] = 10;

        $pp = new tx_realurl_pagepath();
        $pp->_setConf(['languageGetVar' => 'newL']);
        $pp->_setParent($mock);

        $this->assertSame(10, $pp->_getLanguageVarEncode(), 'seems that we\'re using the wrong GET var to read the language');
    }

    /**
     * makes sure that the exception (blacklist) work
     *
     * @test
     * @return void
     */
    public function languageExceptionsWork()
    {
        $mock = $this->getMockBuilder(tx_realurl::class)
            ->getMock();
        $mock->orig_paramKeyValues['L'] = 3;

        $pp = new tx_realurl_pagepath();
        $pp->_setConf(['languageExceptionUids' => '2']);
        $pp->_setParent($mock);

        $this->assertEquals(3, $pp->_getLanguageVarEncode(), 'it seems that a regular request was filtered with the blacklist');

        $mock->orig_paramKeyValues['L'] = 2;
        $this->assertEquals(0, $pp->_getLanguageVarEncode(), 'it seems that a excepted / blacklisted language wasn\'t filtered');
    }

    /**
     * makes sure that the realurl-pre-settings are used if there's no other input
     *
     * @test
     * @return void
     */
    public function languageIsDetectedFromPreVar()
    {
        $mock = $this->getMockBuilder(tx_realurl::class)
            ->setMethods(['getRetrievedPreGetVar'])
            ->getMock();
        $mock->expects($this->any())
            ->method('getRetrievedPreGetVar')
            ->will($this->returnValue(7));

        $pp = new tx_realurl_pagepath();
        $pp->_setConf([]);
        $pp->_setParent($mock);

        $this->assertSame(7, $pp->_getLanguageVarDecode(), 'pregetvar isn\'t used as supposed');
    }
}

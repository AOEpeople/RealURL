<?php
namespace AOE\Realurl\Tests\Unit;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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

use AOE\Realurl\Pathgenerator;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Class PathgeneratorTest
 */
class PathgeneratorTest extends UnitTestCase
{
    ////////////////////////////////////////////////////////////////////////
    //  Tests concerning tx_realurl_pathgenerator::getRootLine()
    ////////////////////////////////////////////////////////////////////////

    /**
     * @test
     */
    public function shouldGetFullRootLine()
    {
        $rootLine = [
            ['uid' => 8],
            ['uid' => 5],
            ['uid' => 3],
            ['uid' => 1]
        ];
        $rootPid = 1;

        /** @var PageRepository|MockObject $pageRepository */
        $pageRepository = $this->getMockBuilder(PageRepository::class)->setMethods(['getRootLine'])->getMock();
        $pageRepository->expects(self::once())->method('getRootLine')->willReturn($rootLine);

        /** @var Pathgenerator|MockObject $subject */
        $subject = $this->getMockBuilder(Pathgenerator::class)->setMethods(['_initSysPage'])->getMock();
        $this->inject($subject, 'rootPid', $rootPid);
        $this->inject($subject, 'sys_page', $pageRepository);

        self::assertEquals(array_reverse($rootLine), $subject->getRootLine(8, 0, 0));
    }

    /**
     * @test
     */
    public function shouldStopRootLineAtConfiguredRootPid()
    {
        $rootLine = [
            ['uid' => 8],
            ['uid' => 5],
            ['uid' => 3],
            ['uid' => 1]
        ];
        $rootPid = 5;

        /** @var PageRepository|MockObject $pageRepository */
        $pageRepository = $this->getMockBuilder(PageRepository::class)->setMethods(['getRootLine'])->getMock();
        $pageRepository->expects(self::once())->method('getRootLine')->willReturn($rootLine);

        /** @var Pathgenerator|MockObject $subject */
        $subject = $this->getMockBuilder(Pathgenerator::class)->setMethods(['_initSysPage'])->getMock();
        $this->inject($subject, 'rootPid', $rootPid);
        $this->inject($subject, 'sys_page', $pageRepository);

        self::assertEquals([$rootLine[1], $rootLine[0]], $subject->getRootLine(8, 0, 0));
    }

    /**
     * @test
     * @expectedException \AOE\Realurl\Exception\RootlineException
     * @expectedExceptionCode 1481273270
     * @throws \Exception
     */
    public function shouldThrowExceptionOnEmptyRootLine()
    {
        /** @var PageRepository|MockObject $pageRepository */
        $pageRepository = $this->getMockBuilder(PageRepository::class)->setMethods(['getRootLine'])->getMock();
        $pageRepository->expects(self::once())->method('getRootLine')->willReturn([]);

        /** @var Pathgenerator|MockObject $subject */
        $subject = $this->getMockBuilder(Pathgenerator::class)->setMethods(['_initSysPage'])->getMock();
        $this->inject($subject, 'sys_page', $pageRepository);

        $subject->getRootLine(1, 0, 0);
    }

    /**
     * @test
     * @expectedException \AOE\Realurl\Exception\RootlineException
     * @expectedExceptionCode 1481273270
     * @throws \Exception
     */
    public function shouldThrowExceptionIfConfiguredRootPidIsNotInRootLine()
    {
        $rootLine = [
            ['uid' => 8],
            ['uid' => 5],
            ['uid' => 3],
            ['uid' => 1]
        ];
        $rootPid = 99;

        /** @var PageRepository|MockObject $pageRepository */
        $pageRepository = $this->getMockBuilder(PageRepository::class)->setMethods(['getRootLine'])->getMock();
        $pageRepository->expects(self::once())->method('getRootLine')->willReturn($rootLine);

        /** @var Pathgenerator|MockObject $subject */
        $subject = $this->getMockBuilder(Pathgenerator::class)->setMethods(['_initSysPage'])->getMock();
        $this->inject($subject, 'rootPid', $rootPid);
        $this->inject($subject, 'sys_page', $pageRepository);

        $subject->getRootLine(8, 0, 0);
    }
}

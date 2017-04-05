<?php

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

/**
 * Class tx_realurl_pagepath_testcase
 */
class tx_realurl_pagepath_testcase extends \TYPO3\CMS\Core\Tests\FunctionalTestCase
{
    /**
     * @var array
     */
    protected $coreExtensionsToLoad = ['version', 'workspaces'];

    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/realurl'];

    /**
     * @var tx_realurl_pagepath
     */
    private $pagepath;

    /**
     * Creates a test instance and sets up the test database
     */
    public function setUp()
    {
        parent::setUp();

        $this->importDataSet(dirname(__FILE__) . '/fixtures/pages.xml');

        $this->pagepath = new tx_realurl_pagepath();
        $this->pagepath->_setConf(['segTitleFieldList' => 'title', 'rootpage_id' => 123]);
    }

    /**
     * @test
     */
    public function shouldFindPossiblePageIdsOnReverseLookup()
    {
        $pathSegment = 'testpage';
        $possiblePageIds = $this->callInaccessibleMethod($this->pagepath, 'findPossiblePageIds', $pathSegment);

        self::assertSame(['456'], $possiblePageIds);
    }

    /**
     * @test
     */
    public function shouldIgnoreShortcutsOnReverseLookup()
    {
        $pathSegment = 'shortcut-testpage';
        $possiblePageIds = $this->callInaccessibleMethod($this->pagepath, 'findPossiblePageIds', $pathSegment);

        self::assertEmpty($possiblePageIds);
    }

    /**
     * @test
     */
    public function shouldIgnoreVersionsOnReverseLookup()
    {
        $pathSegment = 'versioned-testpage';
        $possiblePageIds = $this->callInaccessibleMethod($this->pagepath, 'findPossiblePageIds', $pathSegment);

        self::assertEmpty($possiblePageIds);
    }
}

<?php
namespace AOE\Realurl\Tests\Functional;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008 AOE GmbH
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

use AOE\Realurl\Pathgenerator;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Class PathgeneratorTest
 */
class PathgeneratorTest extends FunctionalTestCase
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
     * @var Pathgenerator
     */
    private $pathgenerator;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Creates a test instance and sets up the test database
     */
    public function setUp()
    {
        parent::setUp();
        $this->objectManager = new ObjectManager();

        $this->importDataSet(__DIR__ . '/fixtures/page-livews.xml');
        $this->importDataSet(__DIR__ . '/fixtures/overlay-livews.xml');
        $this->importDataSet(__DIR__ . '/fixtures/page-ws.xml');
        $this->importDataSet(__DIR__ . '/fixtures/overlay-ws.xml');

        $this->initializeTsfeCharsetConverter();

        $this->pathgenerator = new Pathgenerator();
        $this->pathgenerator->init($this->fixture_defaultconfig());
        $this->pathgenerator->setRootPid(1);
    }

    /**
     * @test
     */
    public function canGetCorrectRootline()
    {
        $result = $this->pathgenerator->getRootLine(87, 0, 0);

        $this->assertCount(4, $result, 'rootline should be 4 long');
        $this->assertArrayHasKey('tx_realurl_pathsegment', $result[0], 'tx_realurl_pathsegment should be set');
        $this->assertArrayHasKey('tx_realurl_exclude', $result[0], 'tx_realurl_exclude should be set');
    }

    /**
     * @test
     */
    public function canBuildStandardPaths()
    {
        // 1) Rootpage
        $result = $this->pathgenerator->build(1, 0, 0);
        $this->assertEquals('', $result['path'], 'root should be empty');

        // 2) Normal Level 2 page
        $result = $this->pathgenerator->build(83, 0, 0);
        $this->assertEquals('excludeofmiddle', $result['path'], 'should be excludeofmiddle');

        // 3) Page without title informations
        $result = $this->pathgenerator->build(94, 0, 0);
        $this->assertEquals('normal-3rd-level/page_94', $result['path'], 'should be normal-3rd-level/page_94');
    }

    /**
     * @test
     */
    public function canBuildPathsWithExcludeAndOverride()
    {
        // page root->excludefrommiddle->subpage(with pathsegment)
        $result = $this->pathgenerator->build(85, 0, 0);
        $this->assertEquals('subpagepathsegment', $result['path'], 'should be subpagepathsegment');

        // page root->excludefrommiddle->subpage(with pathsegment)
        $result = $this->pathgenerator->build(87, 0, 0);
        $this->assertEquals('subpagepathsegment/sub-subpage', $result['path'], 'should be subpagepathsegment/sub-subpage');

        $result = $this->pathgenerator->build(80, 0, 0);
        $this->assertEquals('override/path/item', $result['path'], 'should be override/path/item');

        $result = $this->pathgenerator->build(81, 0, 0);
        $this->assertEquals('specialpath/withspecial/chars', $result['path'], 'should be specialpath/withspecial/chars');

        // instead of shortcut page the shortcut target should be used within path
        $result = $this->pathgenerator->build(92, 0, 0);
        $this->assertEquals('normal-3rd-level/subsection', $result['path'], 'shortcut from uid92 to uid91 should be resolved');
    }

    /**
     * @test
     */
    public function canHandleSelfReferringShortcuts()
    {
        // shortcuts with a reference to themselfs might be a problem
        $result = $this->pathgenerator->build(95, 0, 0);
        $this->assertEquals('shortcut-page', $result['path'], 'shortcut should not be resolved');
    }

    /**
     * @test
     */
    public function invalidOverridePathWillFallBackToDefaultGeneration()
    {
        $result = $this->pathgenerator->build(82, 0, 0);
        $this->assertEquals('invalid-overridepath', $result['path'], 'should be invalid-overridepath');
    }

    /**
     * @test
     */
    public function canBuildPathsWithLanguageOverlay()
    {
        // page root->excludefrommiddle->languagemix (Austria)
        $result = $this->pathgenerator->build(86, 2, 0);
        $this->assertEquals('own/url/for/austria', $result['path'], 'should be own/url/for/austria');

        // page root->excludefrommiddle->subpage(with pathsegment)
        $result = $this->pathgenerator->build(85, 2, 0);
        $this->assertEquals('subpagepathsegment-austria', $result['path'], 'should be subpagepathsegment-austria');

        // page root->excludefrommiddle->subpage (overlay with exclude middle)->sub-subpage
        $result = $this->pathgenerator->build(87, 2, 0);
        $this->assertEquals('sub-subpage-austria', $result['path'], 'should be sub-subpage-austria');

        //for French (5)
        $result = $this->pathgenerator->build(86, 5, 0);
        $this->assertEquals('languagemix-segment', $result['path'], 'should be languagemix-segment');

        // page root->excludefrommiddle->languagemix (French)
        $result = $this->pathgenerator->build(101, 5, 0);
        $this->assertEquals('languagemix-segment/another/vivelafrance', $result['path'], 'should be languagemix-segment/another/vivelafrance');
    }

    /**
     * @test
     */
    public function canBuildPathsInWorkspace()
    {
        // page root->excludefrommiddle->subpagepathsegment-ws
        $result = $this->pathgenerator->build(85, 0, 1);
        $this->assertEquals('subpagepathsegment-ws', $result['path'], 'should be subpagepathsegment-ws');

        // page
        $result = $this->pathgenerator->build(86, 2, 1);
        $this->assertEquals('own/url/for/austria/in/ws', $result['path'], 'should be own/url/for/austria/in/ws');

        //page languagemix in German (only translated in ws)
        $result = $this->pathgenerator->build(86, 1, 1);
        $this->assertEquals('languagemix-de', $result['path'], 'should be languagemix-de');

        //page languagemix in German (only translated in ws)
        $result = $this->pathgenerator->build(85, 1, 1);
        $this->assertEquals('subpage-ws-de', $result['path'], 'should be subpage-ws-de');
    }

    /**
     * @test
     */
    public function canBuildPathIfOverlayUsesNonLatinChars()
    {
        // some non latin characters are replaced
        $result = $this->pathgenerator->build(83, 4, 0);
        $this->assertEquals('page-exclude', $result['path'], 'should be pages-exclude');

        // overlay has no latin characters therefore the default record is used
        $result = $this->pathgenerator->build(84, 4, 0);
        $this->assertEquals('normal-3rd-level', $result['path'], 'should be normal-3rd-level');

        // overlay has no latin characters therefore the default record is used
//        $this->markTestIncomplete('Test fails for unknown reason');
        $result = $this->pathgenerator->build(94, 4, 0);
        $this->assertEquals('normal-3rd-level/page_94', $result['path'], 'should be normal-3rd-level/page_94');
    }

    /**
     * @test
     */
    public function canResolvePathFromDelegatedFlexibleURLField()
    {
        $this->pathgenerator->init($this->fixture_delegationconfig());

        // Test direct delegation
        $result = $this->pathgenerator->build(97, 0, 0);
        $this->assertEquals('delegation-target', $result['path'], 'delegation should be executed');

        // Test multi-hop delegation
        $result = $this->pathgenerator->build(96, 0, 0);
        $this->assertEquals('delegation-target', $result['path'], 'delegation should be executed');
    }

    /**
     * @test
     */
    public function canResolveURLFromExternalURLField()
    {
        $this->pathgenerator->init($this->fixture_defaultconfig());

        $result = $this->pathgenerator->build(199, 0, 0);
        $this->assertEquals('https://www.aoe.com', $result['path'], 'external URL is expected');

        $result = $this->pathgenerator->build(199, 4, 0);
        $this->assertEquals('https://www.aoe.com', $result['path'], 'Chinese record does not provide own value therefore default-value is used');

        $result = $this->pathgenerator->build(199, 5, 0);
        $this->assertEquals('https://www.aoe.com/fr', $result['path'], 'French record is supposed to overlay the URL');
    }

    /**
     * @test
     */
    public function canResolveURLFromDelegatedFlexibleURLField()
    {
        $this->pathgenerator->init($this->fixture_delegationconfig());

        $result = $this->pathgenerator->build(99, 0, 0);
        $this->assertEquals('http://www.aoe.com', $result['path'], 'delegation should be executed');
    }

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionCode 1481273270
     */
    public function canNotBuildPathForPageInForeignRooline()
    {
        $this->pathgenerator->init($this->fixture_defaultconfig());
        $this->pathgenerator->build(200, 0, 0);
    }

    /**
     * Basic configuration (strict mode)
     */
    private function fixture_defaultconfig()
    {
        return [
            'type' => 'user',
            'userFunc' => 'EXT:realurl/class.tx_realurl_pagepath.php:&tx_realurl_pagepath->main',
            'spaceCharacter' => '-',
            'cacheTimeOut' => '100',
            'languageGetVar' => 'L',
            'rootpage_id' => '1',
            'strictMode' => '1',
            'segTitleFieldList' => 'alias,tx_realurl_pathsegment,nav_title,title,subtitle'
        ];
    }

    /**
     * Configuration with enabled delegation function for pagetype 77
     */
    private function fixture_delegationconfig()
    {
        return [
            'type' => 'user',
            'userFunc' => 'EXT:realurl/class.tx_realurl_pagepath.php:&tx_realurl_pagepath->main',
            'spaceCharacter' => '-',
            'cacheTimeOut' => '100',
            'languageGetVar' => 'L',
            'rootpage_id' => '1',
            'strictMode' => '1',
            'segTitleFieldList' => 'alias,tx_realurl_pathsegment,nav_title,title,subtitle',
            'delegation' => [
                '77' => 'url'
            ]
        ];
    }

    /**
     * Initializes the TSFE CharsetConverter required for running the functional tests
     */
    private function initializeTsfeCharsetConverter()
    {
        if (isset($GLOBALS['TSFE']) && is_object($GLOBALS['TFSE'])) {
            return;
        }

        $GLOBALS['TSFE'] = $this->objectManager->get(
            TypoScriptFrontendController::class,
            $GLOBALS['TYPO3_CONF_VARS'],
            1,
            ''
        );

        $GLOBALS['TSFE']->sys_page = $this->objectManager->get(PageRepository::class);
        $GLOBALS['TSFE']->sys_page->init(false);
        $GLOBALS['TSFE']->tmpl = $this->objectManager->get(TemplateService::class);
        $GLOBALS['TSFE']->tmpl->init();
        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->initTemplate();
    }
}

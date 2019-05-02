<?php
namespace AOE\RealUrl\Utility;

/***************************************************************
 * Copyright notice
 *
 * (c) 2019 AOE GmbH <dev@aoe.com>
 * All rights reserved
 *
 * This script is part of the Typo3 project. The Typo3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Type\Icon\IconState;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class IconUtility
 *
 */
class IconUtility
{
    /**
     * Renders the HTML tag to show icons for a database record
     *
     * @param string $table
     * @param array $row
     *
     * @return string
     */
    public static function getIconForRecord($table, array $row)
    {
        /** @var IconFactory $iconFactory */
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);

        return $iconFactory->getIconForRecord($table, $row, Icon::SIZE_SMALL)->render();
    }

    /**
     * Renders the HTML to show icon for given identifier
     *
     * @param string $identifier
     * @param string $size
     * @param string|null $overlayIdentifier
     * @param IconState|null $state
     *
     * @return string
     */
    public static function getIcon($identifier, $size = Icon::SIZE_DEFAULT, $overlayIdentifier = null, IconState $state = null)
    {
        /** @var IconFactory $iconFactory */
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);

        return $iconFactory->getIcon($identifier, $size, $overlayIdentifier, $state)->render();
    }

}
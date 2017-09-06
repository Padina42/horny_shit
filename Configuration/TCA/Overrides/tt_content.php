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

if ($hornyShitConfig['activeDevelopmentEnvironment']) {
    $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
        'LLL:EXT:horny_shit/Resources/Private/Language/locallang_db.xlf:tt_content.CType.div._hornyshit_',
        'hornyshit_horny_shit',
        'icon-hornyshit-horny_shit',
    ];
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['hornyshit_horny_shit'] = 'icon-hornyshit-horny_shit';

    $tempTypes = [
        'hornyshit_horny_shit' => [
            'showitem' => '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general',
        ],

    ];
    $GLOBALS['TCA']['tt_content']['types'] = array_merge($GLOBALS['TCA']['tt_content']['types'], $tempTypes);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'horny_shit',
        'Configuration/TypoScript/',
        'horny_shit'
    );
}

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('svt') ) {
    // plugin for test of purified sister-extension
    $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = [
        'LLL:EXT:svt_test/Resources/Private/Language/locallang_db.xlf:tt_content.CType.div._svttest_',
        'hornyshit_svt_test',
        'icon-hornyshit-svt_test'
    ];
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['hornyshit_svt_test'] = 'icon-hornyshit-svt_test';
    $tempTypes = [
        'hornyshit_svt_test' => [
            'showitem' => '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general',
        ],
    ];
    $GLOBALS['TCA']['tt_content']['types'] = array_merge($GLOBALS['TCA']['tt_content']['types'], $tempTypes);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'svt_test',
        'Configuration/TypoScript/',
        'svt_test'
    );
}
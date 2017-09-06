<?php
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:horny_shit/Configuration/PageTSconfig/NewContentElementWizard.tsconfig">'
);

$hornyShitConfig = unserialize($_EXTCONF);

/** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
if ($hornyShitConfig['activeDevelopmentEnvironment']) {
    $iconRegistry->registerIcon(
        'icon-hornyshit-horny_shit',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:horny_shit/Resources/Public/Icons/HornyshitHornyShit.svg']

    );
} else {
    $iconRegistry->registerIcon(
        'icon-hornyshit-horny_shit',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:horny_shit/Resources/Public/Icons/SvtSmart.svg']
    );
}


if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('svt') ) {
    // integrate the test-scenario for that purified sister-extension
    $iconRegistry->registerIcon(
        'icon-hornyshit-svt_test',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:horny_shit/Resources/Public/Icons/SvtTest.svg']
    );
   $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['svt']  = ['Porth\\Svt\\ViewHelpers'];
}
// Allow own namespaces
 $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['hornyShit']  = ['Porth\\HornyShit\\ViewHelpers'];

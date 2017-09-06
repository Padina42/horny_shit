<?php
namespace Porth\HornyShit\ViewHelpers;

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


use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Porth\HornyShit\Service\Svt\Utility\CalculateUtility;

class CalculateViewHelper extends AbstractViewHelper
{

    use CompileWithContentArgumentAndRenderStatic;

    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @var CalculateUtility\
     */
    protected $caclulator = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('value', 'string', 'string, which contain the arthmetic calculation. '.
            'Allowed are brackets (), the operators +,-,*,/, div[=\\] , mod [=§]'.
            'plus the non-mathimatical operators `↑`[max of pair], `↓`[min of pair] and the unitäry operators ['.
            '`-`, round, floor, ceil, abs, cos, sin, tan, ln, log, exp].');
        $this->registerArgument('error', 'bool',
            'If set to `true`, the viewhelper present the region of the error.', false, false);    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return integer
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $calculationString = $renderChildrenClosure();
        /** @var CalculateUtility $calculate */
        $calculate = GeneralUtility::makeInstance(CalculateUtility::class);
        if ($arguments['error'] === true) {
            $result = $calculate->calculate($calculationString, true);
            if ($result  === false) {
                $result = $calculate->getErrorNear();
            }
        } else {
            $result = $calculate->calculate($calculationString);
        }
        return $result;
//        return $calculate->calculate(
//            $renderChildrenClosure()
//        );
    }
}

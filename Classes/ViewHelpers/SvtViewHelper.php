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

use Porth\HornyShit\Service\Svt\ExtractService;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception;

use Porth\HornyShit\Service\SvtService;

use Porth\HornyShit\Service\Svt\Utility\FluidViewHelperArgumentsUtility;
use Porth\HornyShit\Service\Svt\Utility\SvtSequenzerUtility;

use TYPO3\CMS\Extbase\Service\ImageService;

/**
 * Class SvtViewHelper
 *
 * param-validation:
 * - set:       ?
 * - unitest:   ?
 * error-handling:
 * - set:       ?
 * - unitest:   ?
 *
 * @package Porth\HornyShit\ViewHelpers
 */
class SvtViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{
    const VIEWHELPER_TREAT_ID_AS_REFERENCE = 'treatIdAsReference';
    const VIEWHELPER_SRC = 'src';
    const VIEWHELPER_IMAGE_FAL_OBJECT = 'image';

    /**
     * @var string
     */
    protected $tagName = 'object'; // not used currently

    /**
     * @var ImageService $imageService
     */
    protected $imageService;

    /**
     * @var SvtService $svtService
     */
    protected $svtService;

    /**
     * @param \TYPO3\CMS\Extbase\Service\ImageService $imageService
     */
    public function injectImageService(\TYPO3\CMS\Extbase\Service\ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Initialize arguments.
     *
     * unittest ?
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();

        $this->registerArgument(self::VIEWHELPER_SRC,
            'string',
            'Path to a file, a combined FAL identifier or an uid (int). If $treatIdAsReference is set, the integer is ' .
            'considered the uid of the sys_file_reference record. If you already got a FAL object, consider using the $image parameter instead',
            false,
            ''
        );
        $this->registerArgument(self::VIEWHELPER_TREAT_ID_AS_REFERENCE,
            'bool',
            'Given src argument is a sys_file_reference record',
            false,
            false
        );
        $this->registerArgument(self::VIEWHELPER_IMAGE_FAL_OBJECT,
            'object',
            'FAL object',
            false,
            null
        );
        // install the fluid-arguments for the svt-service
        $svtFluidArguments = FluidViewHelperArgumentsUtility::argumentsArray();
        foreach ($svtFluidArguments as $svtFluidKey => $svtFluidArgument) {
            $this->registerArgument(
                $svtFluidKey,
                $svtFluidArgument['type'],
                $svtFluidArgument['description'],
                $svtFluidArgument['required'],
                $svtFluidArgument['defaultValue']
            );
        }
    }

    public static function convertBooleanToString(&$item)
    {
        $item = ((is_bool($item)) ?
            (($item === false) ? 'false' : 'true') :
            $item);
        return $item;
    }

    /**
     * rebuild a given svg-code and renders the respective svg-code as a string
     *
     * @see analogous https://docs.typo3.org/typo3cms/TyposcriptReference/ContentObjects/Image/
     *
     * unittest ?
     *
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     * @throws ResourceDoesNotExistException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @return string Rendered tag
     */
    public function render()
    {
        if ((is_null($this->arguments[self::VIEWHELPER_SRC]) && is_null($this->arguments[self::VIEWHELPER_IMAGE_FAL_OBJECT])) ||
            (!is_null($this->arguments[self::VIEWHELPER_SRC]) && !is_null($this->arguments[self::VIEWHELPER_IMAGE_FAL_OBJECT]))
        ) {
            throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('You must either specify a string src or a File object for the SVG-Template.', 1492236829);
        }
        $svg = '';

        try {
            // get svg-stream
            $image = $this->imageService->getImage(
                $this->arguments[self::VIEWHELPER_SRC],
                $this->arguments[self::VIEWHELPER_IMAGE_FAL_OBJECT],
                $this->arguments[self::VIEWHELPER_TREAT_ID_AS_REFERENCE]
            );
            $svg = $image->getContents();

            /** @var SvtService svtService */
            $this->svtService = svtService::getInstance();
            $this->svtService->buildService();

            // svt-options defined by viewhelper-Parameter
            $arguments = $this->svtService->paramExtract($this->arguments);
            // extract dynamiclly defined informations for the SVG
            $variables = $this->svtService->paramVariable($this->arguments);
//            // Generate some example-definition von formated configuration - json is the best
//            if ((isset($arguments[SvtService::ATTRIBUTE_COMPRESS])) &&
//                (!empty($arguments[SvtService::ATTRIBUTE_COMPRESS]))
//            ) {
//                $jsonArguments = json_encode($arguments);
//                $phpArguments = serialize($arguments);
//            }
            // svg-options defined by serialized php-array or JSON-String will override ordinary options definde by svt-attributes
            $arguments = $this->svtService->extractparamOverrideByPhpOrJson($arguments);
            if (!isset($arguments[SvtService::ATTRIBUTE_INFO_SPECIAL])) {
                // generate the normal SVG
                $svg = SvtSequenzerUtility::sequenceOfSvt($arguments, $svg, $this->svtService);
            } else {
                $extract = $this->objectManager->get(ExtractService::class);

                switch ($arguments[SvtService::ATTRIBUTE_INFO_SPECIAL]) {
                    case SvtService::PARAM_INFO_SPECIAL_SVG_BEFORE_LAST :
                        $stream = SvtSequenzerUtility::sequenceOfSvtBeforeLast($arguments, $svg, $this->svtService);
                        break;
                    case SvtService::PARAM_INFO_SPECIAL_SVG_ONLY_LAST :
                        $stream = SvtSequenzerUtility::sequenceOfSvtOnlyLast($arguments, $svg, $this->svtService);
                        break;
                    case SvtService::PARAM_INFO_SPECIAL_SER_BEFORE :
                        $newArguments = $extract->rebuildArguments($arguments, ExtractService::EXTRACT_REBUILD_FLAG_BEFORE);
                        $stream = $extract->buildSerialized($newArguments);
                        break;
                    case SvtService::PARAM_INFO_SPECIAL_SER_NO_BEFORE  :
                        $newArguments = $extract->rebuildArguments($arguments, ExtractService::EXTRACT_REBUILD_FLAG_NO_BEFORE);
                        $stream = $extract->buildSerialized($newArguments);
                        break;
                    case SvtService::PARAM_INFO_SPECIAL_JSON_BEFORE  :
                        $newArguments = $extract->rebuildArguments($arguments, ExtractService::EXTRACT_REBUILD_FLAG_BEFORE);
                        $stream = $extract->buildJson($newArguments);
                        break;
                    case SvtService::PARAM_INFO_SPECIAL_JSON_NO_BEFORE  :
                        $newArguments = $extract->rebuildArguments($arguments, ExtractService::EXTRACT_REBUILD_FLAG_NO_BEFORE);
                        $stream = $extract->buildJson($newArguments);
                        break;
                    case SvtService::PARAM_INFO_SPECIAL_YAML_BEFORE  :
                        $newArguments = $extract->rebuildArguments($arguments, ExtractService::EXTRACT_REBUILD_FLAG_BEFORE);
                        $stream = $extract->buildYaml($newArguments);
                        break;
                    case SvtService::PARAM_INFO_SPECIAL_YAML_NO_BEFORE  :
                        $newArguments = $extract->rebuildArguments($arguments, ExtractService::EXTRACT_REBUILD_FLAG_NO_BEFORE);
                        $stream = $extract->buildYaml($newArguments);
                        break;
                    default:
                        $stream = '';
                        break;
                }
                // override the normal render-flow with the special values
                $svg = $stream;
            }

            $addInfo = $this->svtService->resultValidationNotifyService();

        } catch (ResourceDoesNotExistException $e) {
            // thrown if file does not exist
        } catch (\UnexpectedValueException $e) {
            // thrown if a file has been replaced with a folder
        } catch (\RuntimeException $e) {
            // RuntimeException thrown if a file is outside of a storage
        } catch (\InvalidArgumentException $e) {
            // thrown if file storage does not exist
        } catch (\DomainException $e) {
            // thrown if file storage does not exist
        }


        return $addInfo . $svg;
    }
}

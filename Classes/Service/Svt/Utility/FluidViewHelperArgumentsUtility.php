<?php
namespace Porth\HornyShit\Service\Svt\Utility;


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

use Porth\HornyShit\Service\Svt\ConfigService;
use Porth\HornyShit\Service\Svt\ParameterService;
use Porth\HornyShit\Service\Svt\VariableService;
use Porth\HornyShit\Service\SvtService;
use Porth\HornyShit\Service\Svt\CheckService;
use Porth\HornyShit\Service\Svt\ExtractService;
use Porth\HornyShit\Service\Svt\NamespaceService;
use Porth\HornyShit\Service\Svt\RemoveService;
use Porth\HornyShit\Service\Svt\RebuildBaseService;
use Porth\HornyShit\Service\Svt\StringReplaceService;
use Porth\HornyShit\Service\Svt\CompressService;

/**
 * class FluidViewHelperArgumentsUtility
 *
 * @author Dr. Dieter Porth <info@mobger.de>
 */
class FluidViewHelperArgumentsUtility
{

    /**
     * generate a parameter-Array for the typo3-Viewhelper
     *
     * unittest ?
     *
     * @return array
     */
    public static function argumentsArray()
    {
        $fluidViewHelperArray = [];
        $fluidViewHelperArray[SvtService::ATTRIBUTE_INFO_CONFIG_ERROR] = [
            'type' => 'bool',
            'description' => 'Show messages of failure in the configuration.',
            'required' => false,
            'defaultValue' => false
        ];
        $fluidViewHelperArray[SvtService::ATTRIBUTE_EXTRACT] = [
            'type' => 'array',
            'description' => 'The array can contains at least three items. The item `' . ExtractService::EXTRACT_ENTRY .
                '` contains the configuration-stream. Pleace use the placeholder-definition in the section parameter '.
                'to prohibit conflicts with fluid. The optional item ´' .
                ExtractService::EXTRACT_TYPE . '` contains one of the following list: ' .
                implode(', ', ExtractService::EXTRACT_TYPE_LIST) . '. The default type is`' .
                ExtractService::EXTRACT_TYPUS_JSON . '`.',
            'required' => false,
            'defaultValue' => null
        ];
        $fluidViewHelperArray[SvtService::ATTRIBUTE_IGNORE] = [
            'type' => 'string',
            'description' => 'It can contain one or more parts of the following list: ' .
                implode(',', SvtService::ATTRIBUTE_IGNORE_lIST) . '.',
            'required' => false,
            'defaultValue' => null
        ];
        $fluidViewHelperArray[SvtService::ATTRIBUTE_PARAMETER] = [
            'type' => 'array',
            'description' => 'The array of `' . SvtService::ATTRIBUTE_PARAMETER . '` can contain one or more of ' .
                'named subarrays from the following list: `' .
                implode('`, `', ParameterService::PARAM_LIST) . '``. The list can be part of the compact ' .
                'array in the JSON-, YAML- or serialized-PHP-array-format.' .
                'The optional sub-array of `' . ParameterService::PARAM_CALCULATE . '` contains the fields `' .
                implode('´,´',ParameterService::SUB_PARAM_CALCULATE_LIST) . '` to overrride the '.
                'default-values `' . implode('´,´',ParameterService::SUB_PARAM_CALCULATE_LIST_DEFAULT) . '`.' .
                'The optional array of `' . ParameterService::PARAM_PLACEHOLDER . '` contains the fields `' .
                implode('`, `', ParameterService::SUB_PARAM_PLACEHOLDER_LIST) .
                '` or an array with items, which each contain the former named fields. The pairs define the transformation 
                of incoming values of the configuration-array. ' .
                'The optional array of `' . ParameterService::PARAM_REGISTER . '` contains the fields `' .
                implode('`, `', ParameterService::SUB_PARAM_REGISTER_LIST) .
                '` or an array with items, which each contain the former named fields. The pairs define the start-values '.
                'of the registersused in the service of `'.SvtService::ATTRIBUTE_REBUILD.'`. It`s needed to generate '.
                'diagramss or other types of generic SVGs.' .
                '',
            'required' => false,
            'defaultValue' => null
        ];
        $fluidViewHelperArray[SvtService::ATTRIBUTE_VARIABLE] = [
            'type' => 'array',
            'description' => 'The array of `' . SvtService::ATTRIBUTE_VARIABLE . '` contains the fields `' .
                implode('`, `', VariableService::PARAM_LIST) .
                '` or an array with items, which each contain the former named fields.',
            'required' => false,
            'defaultValue' => null
        ];
        $fluidViewHelperArray[SvtService::ATTRIBUTE_COMPRESS] = [
            'type' => 'string',
            'description' => 'You can compress your svg-code after the manipulation. ' .
                'You can chose the following options: "' .
                implode('", "', CompressService::TYPE_COMPRESS) . '".',
            'required' => false,
            'defaultValue' => null
        ];
        $fluidViewHelperArray[SvtService::ATTRIBUTE_CHECK_SVG] = [
            'type' => 'string',
            'description' => 'You can select the check of ' . CheckService::KEYS_CHECK_LISTING_TEXT . '.',
            'required' => false,
            'defaultValue' => CheckService::KEYS_CHECK_SVG__XML_SVG
        ];
        $prepareWorkOver = 'The array must contain `' . StringReplaceService::PARAM_SEARCH . '`, ´' .
            StringReplaceService::PARAM_REPLACE . '´. It can contain ´' . StringReplaceService::PARAM_MAX . '´, ´' .
            StringReplaceService::PARAM_METHOD . '´ and ' . 'additionally ´' . StringReplaceService::PARAM_CLEAN .
            '´ after ´' . StringReplaceService::PARAM_MAX . '´. The dafault ' .
            '´' . StringReplaceService::PARAM_METHOD . '´ is ´´' . StringReplaceService::PARAM_METHOD_NORMAL . '``. ' .
            'The Method can use regulare expressions, can replace case-insensitive and/or (' .
            'can decode html-special-chars in serach,Replace,clean-Sting or ' .
            '  can decode html-chars of all strings plus the haystack before the replace-action). ' .
            "\n" . 'A simple example: ' . SvtService::ATTRIBUTE_PREPARE . '="{' .
            "" . StringReplaceService::PARAM_SEARCH . ":'abc/(..)xyz', " .
            StringReplaceService::PARAM_REPLACE . ":'ABC/(..)XYZ'}" . "\n" .
            'A case-insensitive example with regex (you have to escape chars, Limiter / and option will be set.): ' .
            SvtService::ATTRIBUTE_PREPARE . '="{' .
            "" . StringReplaceService::PARAM_SEARCH . ":'abc\\/(..)xyz&uuml;', " . StringReplaceService::PARAM_REPLACE .
            ":'ABC/$1XYZü', " . StringReplaceService::PARAM_METHOD . ":'" .
            StringReplaceService::PARAM_METHOD_HTML_REGEX_NO_CASE . "'}" . "\n" .
            'A complex example with regex, maxlimit and clean: ' . SvtService::ATTRIBUTE_PREPARE . '="{' .
            "" . StringReplaceService::PARAM_SEARCH . ":'abc\\/(..)xyz', " . StringReplaceService::PARAM_REPLACE .
            ":'ABC/$1XYZ', " . StringReplaceService::PARAM_METHOD . ":'regex'" . StringReplaceService::PARAM_MAX . ":'3'," .
            StringReplaceService::PARAM_CLEAN . ":'xyz$1abc'" . "}" . "\n";
        $fluidViewHelperArray[SvtService::ATTRIBUTE_PREPARE] = [
            'type' => 'array',
            'description' => $prepareWorkOver,
            'required' => false,
            'defaultValue' => null
        ];
        $fluidViewHelperArray[SvtService::ATTRIBUTE_NAMESPACE] = [
            'type' => 'array',
            'description' => 'The array can contain the parameter ´' . NamespaceService::PARAM_LIST_URL .
                '´ and ´' . NamespaceService::PARAM_TYPE . '´. You will define with `' . NamespaceService::PARAM_LIST_URL .
                '` a comma-separated list of allowed namesspace-links for svg. ' .
                ' Per default the Service will allow namespaces for SVG, HTML, XLINK, CREATIVECOMMONS and FLUID.' .
                ' You define with the fixed parameter in `' . NamespaceService::PARAM_TYPE .
                '` wether your list should fully `' . NamespaceService::VALUE_DEFINE . '` or `' .
                NamespaceService::VALUE_ADD . '` (to) the defaultlist. The `' . NamespaceService::VALUE_ADD . '` is the default-type.',
            'required' => false,
            'defaultValue' => null
        ];
        $fluidViewHelperArray[SvtService::ATTRIBUTE_REMOVE] = [
            'type' => 'array',
            'description' => 'The array can contain the parameter ´' . RemoveService::PARAM_ATTRIBUTES .
                '´ and ´' . RemoveService::PARAM_TAGS . '´, which are added to the default-attributes and default-tags. You ' .
                'override the defaultdefinitiions with comma-separated list in ' . RemoveService::PARAM_DEFAULT_ATTRIBUTES .
                ' and ' . RemoveService::PARAM_DEFAULT_TAGS . '.' .
                'You define comma-separated list and the script will remove all ' .
                'attributes and nodes plus their child-nodes  from the svg-stream.' .
                'Example: ' . SvtService::ATTRIBUTE_REMOVE . '="' .
                "{ " . RemoveService::PARAM_ATTRIBUTES . ": 'onload,style,class', " . RemoveService::PARAM_TAGS . ": ''}" . '"',
            'required' => false,
            'defaultValue' => null
        ];
        $fluidViewHelperArray[SvtService::ATTRIBUTE_REBUILD] = [
            'type' => 'array',
            'description' => 'The array contain a ´' . RebuildBaseService::PARAM_ITEMS . '´ array. ' .
                'The ´' . RebuildBaseService::PARAM_ITEMS . '´-array contains a ´' . RebuildBaseService::ITEM_X_PATH . '´ ' .
                'to one or more svg-tags, an optional ´' . RebuildBaseService::ITEM_REPEAT_MAX . '´ value and one of the ' .
                'following type-arrays.' .
                'The type-array `' . RebuildBaseService::SUB_PARAM_ATTRIBUTE . '` contains the fields `' . RebuildBaseService::SUB_PARAM_ATTRIBUTE_LISTING . '` or an array with items, which contain the former fields.' .
                'The type-array `' . RebuildBaseService::SUB_PARAM_CHILD . '` contains the fields `' . RebuildBaseService::SUB_PARAM_CHILD_LISTING . '`.' .
                'The type-array `' . RebuildBaseService::SUB_PARAM_CLASS . '` contains the fields `' . RebuildBaseService::SUB_PARAM_CLASS_LISTING . '`.' .
                'The type-array `' . RebuildBaseService::SUB_PARAM_DELETE . '` contains the fields `' . implode('`, `',RebuildBaseService::SUB_PARAM_DELETE_TYPUS_LIST) . '`.' .
                'The type-array `' . RebuildBaseService::SUB_PARAM_REGISTER . '` contains the fields `' . implode('`, `',RebuildBaseService::SUB_PARAM_REGISTER_LIST). '`.' .
                'The type-array `' . RebuildBaseService::SUB_PARAM_STYLE . '` contains the fields `' . RebuildBaseService::SUB_PARAM_STYLE_LISTING . '`.' .
                'The type-array `' . RebuildBaseService::SUB_PARAM_USE . '` contains the fields `' . RebuildBaseService::SUB_PARAM_USE_LISTING . '`.' .
                'The field `' . RebuildBaseService::SUB_PARAM_USE_ATTRIBUTES . '` of the type-array `' . RebuildBaseService::SUB_PARAM_USE . '` can contain an array, which has the same structure as the above described array.' .
                'The type-array `' . RebuildBaseService::SUB_PARAM_VALUE . '` contains the fields `' . RebuildBaseService::SUB_PARAM_VALUE_LISTING . '`.',
            'required' => false,
            'defaultValue' => null
        ];
        $fluidViewHelperArray[SvtService::ATTRIBUTE_WORK_OVER] = [
            'type' => 'array',
            'description' => $prepareWorkOver,
            'required' => false,
            'defaultValue' => null
        ];
        return $fluidViewHelperArray;

    }
}

?>

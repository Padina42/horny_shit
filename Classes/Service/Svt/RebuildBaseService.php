<?php

namespace Porth\HornyShit\Service\Svt;

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

use Porth\HornyShit\Service\Svt\Utility\ValidateNotifyService;

/**
 * class RebuildBaseService
 *
 * error-handling:
 * - set:       ?
 * - unitest:   ?
 *
 * @author Dr. Dieter Porth <info@mobger.de>
 */
class RebuildBaseService
{
    /** helpful definition for callable funktion for the rebuild */
    const ITEM_TYPE_PARAM = 'typeParam';
    const ITEM_REPEAT_MAX = 'repeatMax';
    const ITEM_X_PATH = 'xPath';


    const PARAM_REPEAT_MAX = 'repeatMax';
    const PARAM_ITEMS = 'items';

    const SUB_PARAM_ARRAY = [
        RebuildBaseService::SUB_PARAM_ATTRIBUTE,
        RebuildBaseService::SUB_PARAM_CLASS,
        RebuildBaseService::SUB_PARAM_CHILD,
        RebuildBaseService::SUB_PARAM_DELETE,
        RebuildBaseService::SUB_PARAM_IMAGE,
        RebuildBaseService::SUB_PARAM_REGISTER,
        RebuildBaseService::SUB_PARAM_STYLE,
        RebuildBaseService::SUB_PARAM_USE,
        RebuildBaseService::SUB_PARAM_VALUE,
    ];

    const SUB_PARAM_NODE_TYPUS_AFTER = 'after';
    const SUB_PARAM_NODE_TYPUS_APPEND = 'append';
    const SUB_PARAM_NODE_TYPUS_BEFORE = 'before';
    const SUB_PARAM_NODE_TYPUS_PREPEND = 'prepend';
    const SUB_PARAM_NODE_TYPE_LIST = [
        self::SUB_PARAM_NODE_TYPUS_AFTER,
        self::SUB_PARAM_NODE_TYPUS_APPEND,
        self::SUB_PARAM_NODE_TYPUS_BEFORE,
        self::SUB_PARAM_NODE_TYPUS_PREPEND,
    ];

    const SUB_PARAM_ATTRIBUTE = 'attribute';
    const SUB_PARAM_CHILD = 'child';
    const SUB_PARAM_CLASS = 'class';
    const SUB_PARAM_DELETE = 'delete';
    const SUB_PARAM_IMAGE = 'image';
    const SUB_PARAM_REGISTER = 'register';
    const SUB_PARAM_STYLE = 'style';
    const SUB_PARAM_USE = 'use';
    const SUB_PARAM_VALUE = 'value';

    const SUB_PARAM_ATTRIBUTE_KEY = 'key';
    const SUB_PARAM_ATTRIBUTE_NEW = 'new';
    const SUB_PARAM_ATTRIBUTE_LISTING = 'new, key';

    const SUB_PARAM_CHILD_TYPE = 'type';
    const SUB_PARAM_CHILD_TYPUS_APPEND = self::SUB_PARAM_NODE_TYPUS_APPEND;
    const SUB_PARAM_CHILD_TYPUS_PREPEND = self::SUB_PARAM_NODE_TYPUS_PREPEND;
    const SUB_PARAM_CHILD_TYPUS_BEFORE = self::SUB_PARAM_NODE_TYPUS_BEFORE;
    const SUB_PARAM_CHILD_TYPUS_AFTER = self::SUB_PARAM_NODE_TYPUS_AFTER;
    const SUB_PARAM_CHILD_XML = 'xml';
    const SUB_PARAM_CHILD_LISTING = 'xml, type[' . self::SUB_PARAM_CHILD_TYPUS_APPEND . ',' . self::SUB_PARAM_CHILD_TYPUS_PREPEND . ',' . self::SUB_PARAM_CHILD_TYPUS_BEFORE . ',' . self::SUB_PARAM_CHILD_TYPUS_AFTER . ']';

    const SUB_PARAM_CLASS_ADD = 'add';
    const SUB_PARAM_CLASS_OVERRIDE = 'override';
    const SUB_PARAM_CLASS_REMOVE = 'remove';
    const SUB_PARAM_CLASS_LISTING = 'add, override, remove';

    const SUB_PARAM_DELETE_TYPE = 'type';
    const SUB_PARAM_DELETE_TYPUS_NODE = 'node';
    const SUB_PARAM_DELETE_TYPUS_ATTRIBUTE = 'attribute';
    const SUB_PARAM_DELETE_TYPUS_LIST = [
        self::SUB_PARAM_DELETE_TYPUS_NODE,
        self::SUB_PARAM_DELETE_TYPUS_ATTRIBUTE,
    ];
    const SUB_PARAM_DELETE_ATTRIBUTE = 'attribute';

    const SUB_PARAM_IMAGE_HREF = 'href';
    const SUB_PARAM_IMAGE_TYPE = 'type';
    const SUB_PARAM_IMAGE_ATTRIBUTES = 'attributes';
    const SUB_PARAM_IMAGE_EXTERNAL_RESOURCES_REQUIRED = 'externalResourcesRequired';
    const SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO = 'preserveAspectRatio';
    const SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO_LISTING = ',none,xMinYMin,xMidYMin,xMaxYMin,xMinYMid,xMidYMid,' .
    'xMaxYMid,xMinYMax,xMidYMax,xMaxYMax,xMinYMin meet,xMidYMin meet,xMaxYMin meet,xMinYMid meet,' .
    'xMidYMid meet,xMaxYMid meet,xMinYMax meet,xMidYMax meet,xMaxYMax meet,xMinYMin slice,xMidYMin slice,' .
    'xMaxYMin slice,xMinYMid slice,xMidYMid slice,xMaxYMid slice,xMinYMax slice,xMidYMax slice,xMaxYMax slice,';
    const SUB_PARAM_IMAGE_PRESERVE_ASPECT_RATIO_LISTING_COUNT = 28; // [x[Min,Mid,Max]]*[y[Min,Mid,Max]]*['','meet','slice']] +'none' = 3*3*3+1 = 27+1=28


    const SUB_PARAM_IMAGE_LISTING = 'href, attributes, externalResourcesRequired';

    const SUB_PARAM_REGISTER_MARKER = ParameterService::SUB_PARAM_REGISTER_MARKER;
    const SUB_PARAM_REGISTER_ACTION = ParameterService::SUB_PARAM_REGISTER_ACTION;
    const SUB_PARAM_REGISTER_VALUE = ParameterService::SUB_PARAM_REGISTER_VALUE;
    const SUB_PARAM_REGISTER_ACTION_APPEND = ParameterService::SUB_PARAM_REGISTER_ACTION_APPEND;
    const SUB_PARAM_REGISTER_ACTION_PREPEND = ParameterService::SUB_PARAM_REGISTER_ACTION_PREPEND;
    const SUB_PARAM_REGISTER_ACTION_ADD = ParameterService::SUB_PARAM_REGISTER_ACTION_ADD;
    const SUB_PARAM_REGISTER_ACTION_SUB = ParameterService::SUB_PARAM_REGISTER_ACTION_SUB;
    const SUB_PARAM_REGISTER_ACTION_ADD_INT = ParameterService::SUB_PARAM_REGISTER_ACTION_ADD_INT;
    const SUB_PARAM_REGISTER_ACTION_SUB_INT = ParameterService::SUB_PARAM_REGISTER_ACTION_SUB_INT;
    const SUB_PARAM_REGISTER_ACTION_INC = ParameterService::SUB_PARAM_REGISTER_ACTION_INC;
    const SUB_PARAM_REGISTER_ACTION_DEC = ParameterService::SUB_PARAM_REGISTER_ACTION_DEC;
    const SUB_PARAM_REGISTER_ACTION_RENEW = ParameterService::SUB_PARAM_REGISTER_ACTION_RENEW;
    const SUB_PARAM_REGISTER_ACTION_CALCULATE = ParameterService::SUB_PARAM_REGISTER_ACTION_CALCULATE;
    const SUB_PARAM_REGISTER_ACTION_LIST = ParameterService::SUB_PARAM_REGISTER_ACTION_LIST;
    const SUB_PARAM_REGISTER_LIST = ParameterService::SUB_PARAM_REGISTER_LIST;

    const SUB_PARAM_STYLE_TYPE = 'type';
    const SUB_PARAM_STYLE_KEY = 'key';
    const SUB_PARAM_STYLE_NEW = 'new';
    const SUB_PARAM_STYLE_TYPE_LISTING = 'add, override, remove';
    const SUB_PARAM_STYLE_TYPUS_ADD = 'add';
    const SUB_PARAM_STYLE_TYPUS_OVERRIDE = 'override';
    const SUB_PARAM_STYLE_TYPUS_REMOVE = 'remove';
    const SUB_PARAM_STYLE_LISTING = 'new, key, type[add, override, remove],';

    const SUB_PARAM_USE_HREF = 'href';
    const SUB_PARAM_USE_ID_ELEMENT = 'id';
    const SUB_PARAM_USE_TYPE = 'type';
    const SUB_PARAM_USE_TYPELIST = 'append, prepend, before, after';
    const SUB_PARAM_USE_TYPUS_APPEND = 'append';
    const SUB_PARAM_USE_TYPUS_PREPEND = 'prepend';
    const SUB_PARAM_USE_TYPUS_BEFORE = 'before';
    const SUB_PARAM_USE_TYPUS_AFTER = 'after';
    const SUB_PARAM_USE_ATTRIBUTES = 'attributes';
    const SUB_PARAM_USE_LISTING = 'href, attributes, externalResourcesRequired';

    const SUB_PARAM_VALUE_MARKER = 'marker';
    const SUB_PARAM_VALUE_NEW = 'new';
    const SUB_PARAM_VALUE_MAX_LENGTH = 'maxLength';
    const SUB_PARAM_VALUE_TYPE = 'type';
    const SUB_PARAM_VALUE_TYPE_LIST = [
        self::SUB_PARAM_VALUE_TYPUS_NORMAL,
        self::SUB_PARAM_VALUE_TYPUS_REGULAR,
        self::SUB_PARAM_VALUE_TYPUS_MB_REGULAR,
        self::SUB_PARAM_VALUE_TYPUS_OVERRIDE,
        self::SUB_PARAM_VALUE_TYPUS_APPEND,
        self::SUB_PARAM_VALUE_TYPUS_PREPEND,
    ];
    const SUB_PARAM_VALUE_TYPUS_NORMAL = 'normal';
    const SUB_PARAM_VALUE_TYPUS_REGULAR = 'regular';
    const SUB_PARAM_VALUE_TYPUS_MB_REGULAR = 'mbregular';
    const SUB_PARAM_VALUE_TYPUS_OVERRIDE = 'override';
    const SUB_PARAM_VALUE_TYPUS_APPEND = 'append';
    const SUB_PARAM_VALUE_TYPUS_PREPEND = 'prepend';
    const SUB_PARAM_VALUE_LISTING = 'marker, new, type[normal, mbregular, regular, override, append, prepend]';

    const DELIMITER_COMMA = ',';
    const DEFAULT_REPEAT_MAX = 1;


    /**
     * maximum of repeat for an item-service
     * @var int
     */
    protected $repeatMax = self::DEFAULT_REPEAT_MAX;

    /**
     * @var ValidateNotifyService
     */
    protected $validateNotifyService = null;

    /**
     * RebuildBaseService constructor.
     *
     * unittest ?
     *
     * @param string $languageKey
     */
    public function __construct($languageKey = '')
    {
        $this->validateNotifyService = ValidateNotifyService::getInstance();
        $this->validateNotifyService->changeLanguage($languageKey);
    }


    /**
     *  convert a comma-separated List into an array or into an empty array, in the string contains at least only spaces
     *
     * unittest 20170704
     *
     * @param $list
     * @return array|ø
     */
    public function transformCommaListToArray($list)
    {
        return (empty(trim($list)) ?
            [] :
            array_filter(
                array_map(
                    'trim',
                    explode(RebuildBaseService::DELIMITER_COMMA, $list)
                )
            )
        );

    }


    /**
     * Returns the repeatMax
     *
     * unittest 20170707
     *
     * @return string $repeatMax
     */
    public function getRepeatMax()
    {
        return $this->repeatMax;
    }

    /**
     * Sets the repeatMax
     *
     * unittest 20170707
     *
     * @param string $repeatMax
     * @return void
     */
    public function setRepeatMax($repeatMax)
    {
        $this->repeatMax = $repeatMax;
    }


}

?>
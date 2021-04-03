<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SystemsModuleType extends Enum
{
    const DASHBOARD =   'DASHBOARD';
    const SYSTEMS_MODULE =   'SYSTEMS_MODULE';
    const USER =   'USER';
    const POST =   'POST';
    const ADD_POST = 'ADD_POST';
    const POST_CATEGORY = 'POST_CATEGORY';
    const PAGE =   'PAGE';
    const CATEGORY = 'CATEGORY';
    const SUPPORT =  'SUPPORT';

    const NEWS_CATEGORY =   'NEWS_CATEGORY';

    const PRODUCT =   'PRODUCT';
    const ADD_PRODUCT =   'ADD_PRODUCT';
    const LIST_PRODUCT =   'LIST_PRODUCT';
    const PRODUCT_CATEGORY =   'PRODUCT_CATEGORY';
    const ATTRIBUTE =   'ATTRIBUTE';
    const ATTRIBUTE_CATEGORY =   'ATTRIBUTE_CATEGORY';
    const LIST_ORDER =   'LIST_ORDER';

    const ADD_PAGES =   'ADD_PAGES';
    const LIST_PAGES =   'LIST_PAGES';
    const VIDEO =   'VIDEO';
    const PHOTO =   'PHOTO';
    const GALLERY =   'GALLERY';
    const MENU =   'MENU';
    const CUSTOMER =   'CUSTOMER';
    const CUSTOMER_COMMENT =   'CUSTOMER_COMMENT';

    const CONTACT =   'CONTACT';
    const WEBSITE =   'WEBSITE';
    const SOURCE =   'SOURCE';
    const SETTING =   'SETTING';
    const ALIAS =   'ALIAS';
    const LANG =   'LANG';
    const CONFIG_MODULE =   'CONFIG_MODULE';
    const ADD_MODULE = 'ADD_MODULE';
    const AGENCY = 'AGENCY';
    const IMPORT = 'IMPORT';
    const HISTORY_IMPORT = 'HISTORY_IMPORT';
    const EXPORT = 'EXPORT';
    const HISTORY_EXPORT = 'HISTORY_EXPORT';
    const STOCK = 'STOCK';
    const REPORT = 'REPORT';
    const COMMENTS = 'COMMENTS';
}

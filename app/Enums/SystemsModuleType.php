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
    const NEWS =   'NEWS';
    const ADD_NEWS =   'ADD_NEWS';
    const NEWS_CATEGORY =   'NEWS_CATEGORY';
    const LIST_NEWS =   'LIST_NEWS';
    const PRODUCT =   'PRODUCT';
    const ADD_PRODUCT =   'ADD_PRODUCT';
    const LIST_PRODUCT =   'LIST_PRODUCT';
    const PRODUCT_CATEGORY =   'PRODUCT_CATEGORY';
    const ATTRIBUTE =   'ATTRIBUTE';
    const ATTRIBUTE_CATEGORY =   'ATTRIBUTE_CATEGORY';
    const LIST_ORDER =   'LIST_ORDER';
    const PAGES =   'PAGES';
    const ADD_PAGES =   'ADD_PAGES';
    const LIST_PAGES =   'LIST_PAGES';
    const RECRUITMENT =   'RECUITMENT';
    const ADD_RECRUITMENT =   'ADD_RECRUITMENT';
    const RECRUITMENT_CATEGORY =   'RECUITMENT_CATEGORY';
    const LIST_RECRUITMENT =   'LIST_CRUITMENT';
    const MEDIA =   'MEDIA';
    const VIDEO =   'VIDEO';
    const IMAGE =   'IMAGE';
    const GALLERY =   'GALLERY';
    const MENU =   'MENU';
    const CUSTOMER =   'CUSTOMER';
    const CUSTOMER_COMMENT =   'CUSTOMER_COMMENT';
    const SUPPORT =   'SUPPORT';
    const CONTACT =   'CONTACT';
    const WEBSITE =   'WEBSITE';
    const SOURCE =   'SOURCE';
    const SITE_SETTING =   'SITE_SETTING';
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

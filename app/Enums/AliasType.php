<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AliasType extends Enum
{
    const VIDEO = 'VIDEO';
    const HOMEVIDEO = 'HOMEVIDEO';
    const CONTACT = 'CONTACT';
    const GALLERY = 'GALLERY';
    const GALLERY_CATEGORY = 'GALLERY_CATEGORY';
    const HOMEGALLERY = 'HOMEGALLERY';
    const PRODUCT = 'PRODUCT';
    const PRODUCT_CATEGORY = 'PRODUCT_CATEGORY';
    const POST = 'POST';
    const POST_CATEGORY = 'POST_CATEGORY';
    const RECRUITMENT = 'RECRUIMENT';
    const RECRUITMENT_CATEGORY = 'RECUITMENT_CATEGORY';
}

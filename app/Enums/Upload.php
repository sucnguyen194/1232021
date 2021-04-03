<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Upload extends Enum
{
    const logo =   'logo';
    const background =   'background';
    const favicon = 'favicon';
    const image = 'image';
    const thumbnail = 'thumb';
    const og_image = 'og_image';
    const avata = 'avata';
}

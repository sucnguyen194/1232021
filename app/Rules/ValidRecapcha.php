<?php

namespace App\Rules;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SiteSetting;
use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Client;

class ValidRecapcha extends Controller implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
       //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client([
            'base_uri' => 'https://google.com/recaptcha/api/'
        ]);

        $response = $client->post('siteverify', [
            'query' => [
                'secret' => setting()->re_captcha_secret,
                'response' => $value
            ]
        ]);

        return json_decode($response->getBody())->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return  flash('Mã xác nhận không chính xác!', 3);
    }
}

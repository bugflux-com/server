<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User's photo sizes
    |--------------------------------------------------------------------------
    |
    | Here you can provide sizes that will be prepared while user change
    | his account photo and will be accessed via /users/{id}/photo/{size}.
    | The 'large' size must have the greatest value.
    |
    */

    'photo_sizes' => [
        'small' => 50,
        'large' => 150,
    ],

    /*
    |--------------------------------------------------------------------------
    | Changing E-mail
    |--------------------------------------------------------------------------
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'new_email_token' => [
        'expire' => 60,
        'invalidate_previous' => false,
    ],

];
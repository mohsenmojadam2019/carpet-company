<?php

return [
    'postmark'=>['token'=>env('POSTMARK_TOKEN')],
    'ses'=>['key'=>env('AWS_ACCESS_KEY_ID'),'secret'=>env('AWS_SECRET_ACCESS_KEY'),'region'=>env('AWS_DEFAULT_REGION','us-east-1')],
    'slack'=>['notifications'=>['bot_user_oauth_token'=>env('SLACK_BOT_USER_OAUTH_TOKEN'),'channel'=>env('SLACK_BOT_USER_DEFAULT_CHANNEL')]],
    'zarinpal'=>[
        'merchant_id'=>env('ZARINPAL_MERCHANT_ID'),
        'sandbox'=>filter_var(env('ZARINPAL_SANDBOX',true),FILTER_VALIDATE_BOOL),
        'callback_url'=>env('ZARINPAL_CALLBACK_URL'),
        'amount_multiplier'=>(int)env('ZARINPAL_AMOUNT_MULTIPLIER',10),
    ],
    'kavenegar'=>[
        'api_key'=>env('KAVENEGAR_API_KEY'),
        'sender'=>env('KAVENEGAR_SENDER'),
        'verify_template'=>env('KAVENEGAR_VERIFY_TEMPLATE'),
        'sandbox'=>filter_var(env('KAVENEGAR_SANDBOX',true),FILTER_VALIDATE_BOOL),
    ],
    'store'=>['price_unit'=>env('STORE_PRICE_UNIT','IRT'),'shipping_flat'=>(int)env('STORE_SHIPPING_FLAT',0)],
];

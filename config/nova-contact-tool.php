<?php

return [
    'default_to' => '',
    'default_subject' => '',
    'save_messages' => true,
    'save_subscribers' => true,
    'priority' => 3,
    'delete_on_unsubscribe' => false,

    'enable_gmail_api' => env('ENABLE_GMAIL_API', false),
];

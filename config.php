<?php

return [
    'provider' => [
        'id' => 'mailgun',
        'params' => [
            'apiKey' => 'XXXXXX'
        ],
    ],
    'templates' => [
        'contactForm' => [
            'label' => 'Contact form',
            'templateParams' => [
                'name' => 'Name',
                'email' => 'E-Mail',
                'message' => 'Message'
            ]
        ]
    ]
];

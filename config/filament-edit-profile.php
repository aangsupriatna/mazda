<?php

return [
    'show_custom_fields' => true,
    'custom_fields' => [
        'github' => [
            'label' => 'Github username',
            'type' => 'text',
            'placeholder' => 'Masukkan username Github',
            'rules' => 'nullable|url',
            'required' => false,
        ],
    ],
];

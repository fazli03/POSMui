<?php

return [

    'title' => 'Masuk',

    'heading' => 'Masuk ke akun Anda',

    'actions' => [

        'register' => [
            'before' => 'atau',
            'label' => 'buat akun baru',
        ],

        'request_password_reset' => [
            'label' => 'Lupa kata sandi?',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'Email',
        ],

        'password' => [
            'label' => 'Password    ',
        ],

        'remember' => [
            'label' => 'Ingat saya',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'Login',
            ],

        ],

    ],

    'messages' => [

        'failed' => 'Email atau password salah, silahkan ulangi!',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Terlalu banyak permintaan',
            'body' => 'Silakan coba lagi dalam :seconds detik.',
        ],

    ],

];

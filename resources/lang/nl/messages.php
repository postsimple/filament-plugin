<?php

return [
    'action' => [
        'label' => 'Verstuur naar PostSimple',
        'modal_heading' => 'Verstuur naar PostSimple',
        'modal_description' => 'Dit verstuurt de titel en URL van dit record naar PostSimple om social media content te genereren.',
        'modal_submit' => 'Verstuur naar PostSimple',
    ],

    'notifications' => [
        'api_key_missing' => [
            'title' => 'PostSimple API key niet geconfigureerd',
            'body' => 'Stel POSTSIMPLE_API_KEY in via je .env bestand.',
        ],
        'no_title' => [
            'title' => 'Geen titel gevonden',
            'body' => 'Dit record heeft geen titel veld. Kan niet versturen naar PostSimple.',
        ],
        'no_url' => [
            'title' => 'Geen URL gevonden',
            'body' => 'Kan de publieke URL voor dit record niet bepalen.',
        ],
        'api_error' => [
            'title' => 'Versturen naar PostSimple mislukt',
        ],
        'no_batch_id' => [
            'title' => 'Fout',
            'body' => 'Geen batch ID ontvangen van PostSimple',
        ],
        'success' => [
            'title' => 'Succesvol verstuurd naar PostSimple!',
            'body' => 'Openen in PostSimple:',
        ],
        'error' => [
            'title' => 'Fout bij versturen naar PostSimple',
        ],
    ],
];

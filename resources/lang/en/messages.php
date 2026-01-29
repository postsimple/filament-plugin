<?php

return [
    'action' => [
        'label' => 'Send to PostSimple',
        'modal_heading' => 'Send to PostSimple',
        'modal_description' => 'This will send the title and URL of this record to PostSimple to generate social media content.',
        'modal_submit' => 'Send to PostSimple',
    ],

    'notifications' => [
        'api_key_missing' => [
            'title' => 'PostSimple API key not configured',
            'body' => 'Please set POSTSIMPLE_API_KEY in your .env file.',
        ],
        'no_title' => [
            'title' => 'No title found',
            'body' => 'This record doesn\'t have a title field. Cannot send to PostSimple.',
        ],
        'no_url' => [
            'title' => 'No URL found',
            'body' => 'Cannot determine the public URL for this record.',
        ],
        'api_error' => [
            'title' => 'Failed to send to PostSimple',
        ],
        'no_batch_id' => [
            'title' => 'Error',
            'body' => 'No batch ID received from PostSimple',
        ],
        'success' => [
            'title' => 'Successfully sent to PostSimple!',
            'body' => 'Open in PostSimple:',
        ],
        'error' => [
            'title' => 'Error sending to PostSimple',
        ],
    ],
];

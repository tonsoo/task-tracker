<?php

return [

    'messaging' => [

        'drivers' => [

            'whatsapp' => [
                /**
                 * The messaging driver class.
                 */
                'driver' => Tonsoo\TaskTracker\Messaging\Drivers\WhatsAppDriver::class,

                /**
                 * The token from whatsapp api
                 */
                'token' => env('WHATSAPP_TOKEN'),

                /**
                 * Information about the phone number registered on your whatsapp settings
                 */
                'from' => [
                    'number' => env('WHATSAPP_FROM_NUMBER'),
                    'id' => env('WHATSAPP_FROM_ID'),
                ],

                /**
                 * An arbitrary secret you will provide on your settings for webhooks for whatsapp
                 * This is used for webhook validation
                 */
                'secret' => env('WHATSAPP_SECRET'),
            ],

        ],

    ],

    'task_driver' => env('TASK_TRACKER_DRIVER', 'trello'),

    'task_drivers' => [

        'trello' => [
            /**
             * The task driver class.
             */
            'driver' => Tonsoo\TaskTracker\Integrations\Trello\TrelloDriver::class,

            /**
             * Key for your trello account
             */
            'key' => env('TRELLO_KEY'),

            /**
             * Token for your trello account
             */
            'token' => env('TRELLO_TOKEN'),

            /**
             * The board id to use, you can get it from the trello url
             */
            'board_id' => env('TRELLO_BOARD_ID'),

            /**
             * The default trello list id
             */
            'default_list_id' => env('TRELLO_LIST_ID'),

        ],

    ],

    'ai' => [

        /**
         * The similarity threshold so the AI knows if and issue is related to an existing card
         */
        'similarity_threshold' => 0.8,

        'driver' => env('TASK_TRACKER_AI_DRIVER', 'openai'),

        'drivers' => [

            'openai' => [
                /**
                 * The AI driver class.
                 */
                'driver' => Tonsoo\TaskTracker\AI\Drivers\OpenAIDriver::class,

                /**
                 * Api key for open api, can be found here: https://platform.openai.com/api-keys
                 */
                'key' => env('OPENAI_API_KEY'),

                /**
                 * The open api model to use
                 */
                'model' => env('OPENAI_MODEL', 'gpt-4.1-mini'),
            ],

        ],

    ],

    'transcriptions' => [

        'secret_key' => env('TRANSCRIBER_SECRET_KEY'),

    ]

];

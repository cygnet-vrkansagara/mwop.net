<?php

/**
 * @license http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) Matthew Weier O'Phinney
 */

namespace OAuth2Authentication;

return [
    'oauth2' => [
        'debug' => [],
    ],
    'dependencies' => [
        'invokables' => [
            Debug\DebugProvider::class => Debug\DebugProvider::class,
        ],
        'factories' => [
            Debug\DebugProviderMiddleware::class => Debug\DebugProviderMiddlewareFactory::class,
        ],
    ],
];

<?php
/**
 * @license http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) Matthew Weier O'Phinney
 */

declare(strict_types=1);

namespace Mwop\Console;

class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies() : array
    {
        return [
            'invokables' => [
                ClearCache::class          => ClearCache::class,
                CopyAssetSymlinks::class   => CopyAssetSymlinks::class,
                CreateAssetSymlinks::class => CreateAssetSymlinks::class,
                UseDistTemplates::class    => UseDistTemplates::class,
            ],
            'factories' => [
                FeedAggregator::class => FeedAggregatorFactory::class,
            ],
        ];
    }
}

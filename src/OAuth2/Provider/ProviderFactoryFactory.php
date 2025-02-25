<?php
/**
 * @license http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) Matthew Weier O'Phinney
 */

declare(strict_types=1);

namespace Mwop\OAuth2\Provider;

use Psr\Container\ContainerInterface;

class ProviderFactoryFactory
{
    public function __invoke(ContainerInterface $container) : ProviderFactory
    {
        return new ProviderFactory($container);
    }
}

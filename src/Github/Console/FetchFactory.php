<?php
/**
 * @license http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) Matthew Weier O'Phinney
 */

declare(strict_types=1);

namespace Mwop\Github\Console;

use Mwop\Github\AtomReader;
use Psr\Container\ContainerInterface;

class FetchFactory
{
    public function __invoke(ContainerInterface $container) : Fetch
    {
        return new Fetch($container->get(AtomReader::class));
    }
}

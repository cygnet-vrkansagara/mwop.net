<?php
/**
 * @license http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) Matthew Weier O'Phinney
 */

namespace Mwop\Factory;

use Interop\Container\ContainerInterface;
use Mwop\ErrorHandler;
use Zend\Expressive\Template\TemplateRendererInterface;

class ErrorHandlerFactory
{
    public function __invoke(ContainerInterface $container) : ErrorHandler
    {
        $config = $container->get('config');
        $displayErrors = array_key_exists('debug', $config)
            ? (bool) $config['debug']
            : false;
        return new ErrorHandler(
            $container->get(TemplateRendererInterface::class),
            $displayErrors
        );
    }
}

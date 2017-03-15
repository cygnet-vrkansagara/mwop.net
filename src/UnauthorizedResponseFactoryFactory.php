<?php
/**
 * @license http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) Matthew Weier O'Phinney
 */

namespace Mwop;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class UnauthorizedResponseFactoryFactory
{
    const TEMPLATE = 'error::401';

    public function __invoke(ContainerInterface $container) : callable
    {
        return function (Request $request) use ($container) {
            $originalRequest = $request->getAttribute('originalRequest', $request);

            $config = $container->get('config');
            $debug  = $config['debug'] ?? false;

            $view = [
                'auth_path' => (string) $request->getUri()->withPath('/auth'),
                'redirect'  => (string) $originalRequest->getUri(),
                'debug'     => (bool) $debug,
            ];

            $renderer = $container->get(TemplateRendererInterface::class);
            return new HtmlResponse(
                $renderer->render(self::TEMPLATE, $view),
                401
            );
        };
    }
}

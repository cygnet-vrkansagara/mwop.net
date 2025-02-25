<?php
/**
 * @license http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) Matthew Weier O'Phinney
 */

declare(strict_types=1);

namespace Mwop\Contact;

use Phly\EventDispatcher\ListenerProvider\AttachableListenerProvider;
use Phly\Expressive\ConfigFactory;
use Zend\Expressive\Application;
use Zend\Expressive\Csrf\CsrfMiddleware;
use Zend\Expressive\Session\SessionMiddleware;

class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'contact'      => $this->getConfig(),
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplateConfig(),
        ];
    }

    public function getConfig() : array
    {
        return [
            'recaptcha_pub_key'  => null,
            'recaptcha_priv_key' => null,
            'message' => [
                'to'   => null,
                'from' => null,
                'sender' => [
                    'address' => null,
                    'name'    => null,
                ],
            ],
        ];
    }

    public function getDependencies() : array
    {
        return [
            'factories' => [
                'config-contact'                           => ConfigFactory::class,
                'config-contact.message'                   => ConfigFactory::class,
                Handler\DisplayContactFormHandler::class   => Handler\DisplayContactFormHandlerFactory::class,
                Handler\ProcessContactFormHandler::class   => Handler\ProcessContactFormHandlerFactory::class,
                Handler\DisplayThankYouHandler::class      => Handler\DisplayThankYouHandlerFactory::class,
                Listener\SendContactMessageListener::class => Listener\SendContactMessageListenerFactory::class,
            ],
            'delegators' => [
                AttachableListenerProvider::class => [
                    Listener\SendContactMessageListenerDelegator::class,
                ],
            ],
        ];
    }

    public function getTemplateConfig() : array
    {
        return [
            'paths' => [
                'contact' => [__DIR__ . '/templates'],
            ],
        ];
    }

    public function registerRoutes(Application $app, string $basePath = '/contact') : void
    {
        $app->get($basePath . '[/]', [
            SessionMiddleware::class,
            CsrfMiddleware::class,
            Handler\DisplayContactFormHandler::class,
        ], 'contact');
        $app->post($basePath . '/process', [
            SessionMiddleware::class,
            CsrfMiddleware::class,
            Handler\ProcessContactFormHandler::class,
        ], 'contact.process');
        $app->get($basePath . '/thank-you', Handler\DisplayThankYouHandler::class, 'contact.thank-you');
    }
}

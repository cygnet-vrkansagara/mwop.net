<?php
/**
 * @license http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) Matthew Weier O'Phinney
 */

declare(strict_types=1);

namespace Mwop\OAuth2;

use Psr\Http\Message\ResponseFactoryInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

use function in_array;

trait ValidateProviderTrait
{
    /**
     * @var string[]
     */
    private $allowedProviders = [
        'github',
        'google',
    ];

    private function validateProvider(?string $provider) : bool
    {
        $allowedProviders = $this->allowedProviders;
        if ($this->isDebug) {
            $allowedProviders[] = 'debug';
        }

        return in_array($provider, $allowedProviders, true);
    }
}

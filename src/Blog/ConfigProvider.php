<?php
/**
 * @license http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) Matthew Weier O'Phinney
 */

namespace Mwop\Blog;

use Phly\EventDispatcher\ListenerProvider\AttachableListenerProvider;
use Zend\Expressive\Application;

class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'blog'         => $this->getConfig(),
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplateConfig(),
        ];
    }

    public function getConfig() : array
    {
        return [
            'db'     => null,
            'cache'  => [
                'enabled' => false,
            ],
            'disqus' => [
                'developer' => 0,
                'key'       => null,
            ],
        ];
    }

    public function getDependencies() : array
    {
        return [
            'factories' => [
                BlogCachePool::class                            => BlogCachePoolFactory::class,
                Console\ClearCache::class                       => Console\ClearCacheFactory::class,
                Console\FeedGenerator::class                    => Console\FeedGeneratorFactory::class,
                Console\TagCloud::class                         => Console\TagCloudFactory::class,
                Handler\DisplayPostHandler::class               => Handler\DisplayPostHandlerFactory::class,
                Handler\FeedHandler::class                      => Handler\FeedHandlerFactory::class,
                Handler\ListPostsHandler::class                 => Handler\ListPostsHandlerFactory::class,
                Handler\SearchHandler::class                    => Handler\SearchHandlerFactory::class,
                Mapper\MapperInterface::class                   => Mapper\MapperFactory::class,
                Listener\CacheBlogPostListener::class           => Listener\CacheBlogPostListenerFactory::class,
                Listener\FetchBlogPostFromMapperListener::class => Listener\FetchBlogPostFromMapperListenerFactory::class,
                Listener\FetchBlogPostFromCacheListener::class  => Listener\FetchBlogPostFromCacheListenerFactory::class,
            ],
            'invokables' => [
                Console\GenerateSearchData::class => Console\GenerateSearchData::class,
                Console\SeedBlogDatabase::class   => Console\SeedBlogDatabase::class,
            ],
            'delegators' => [
                AttachableListenerProvider::class => [
                    Listener\BlogPostEventListenersDelegator::class,
                ],
            ],
        ];
    }

    public function getTemplateConfig() : array
    {
        return [
            'paths' => [
                'blog' => [__DIR__ . '/templates'],
            ],
        ];
    }

    public function registerRoutes(Application $app, string $basePath = '/blog') : void
    {
        $app->get($basePath, '[/]', Handler\ListPostsHandler::class, 'blog');
        $app->get($basePath, '/{id:[^/]+}.html', Handler\DisplayPostHandler::class, 'blog.post');
        $app->get($basePath, '/tag/{tag:php}.xml', Handler\FeedHandler::class, 'blog.feed.php');
        $app->get($basePath, '/{tag:php}.xml', Handler\FeedHandler::class, 'blog.feed.php.also');
        $app->get($basePath, '/tag/{tag:[^/]+}/{type:atom|rss}.xml', Handler\FeedHandler::class, 'blog.tag.feed');
        $app->get($basePath, '/tag/{tag:[^/]+}', Handler\ListPostsHandler::class, 'blog.tag');
        $app->get($basePath, '/{type:atom|rss}.xml', Handler\FeedHandler::class, 'blog.feed');
        $app->get($basePath, '/search[/]', Handler\SearchHandler::class, 'blog.search');
    }
}

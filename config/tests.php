<?php

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Asset\AssetConverter;
use Yiisoft\Asset\AssetManager;
use Yiisoft\EventDispatcher\Dispatcher;
use Yiisoft\EventDispatcher\Provider\Provider;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Log\Logger;
use Yiisoft\View\Theme;
use Yiisoft\View\View;
use Yiisoft\View\WebView;
use Yiisoft\Widget\Widget;

$tempDir = sys_get_temp_dir();

return [
    Aliases::class => [
        '@root' => dirname(__DIR__, 1),
        '@public' => '@root/tests/public',
        '@basePath' => '@public/assets',
        '@baseUrl'  => '/baseUrl',
        '@converter' => '@public/assetconverter',
        '@npm' => '@root/node_modules',
        '@view' => '@public/view',
        '@web' => '@baseUrl',
        '@testSourcePath' => '@public/assetsources'
    ],

    AssetConverter::class => [
        '__class' => AssetConverter::class,
        '__construct()' => [
            Reference::to(Aliases::class),
            Reference::to(LoggerInterface::class)
        ]
    ],

    AssetManager::class => [
        '__class' => AssetManager::class,
        '__construct()' => [
            Reference::to(Aliases::class),
            Reference::to(LoggerInterface::class)
        ],
        'setBasePath' => ['@basePath'],
        'setBaseUrl'  => ['@baseUrl'],
    ],

    ListenerProviderInterface::class => [
        '__class' => Provider::class,
    ],

    EventDispatcherInterface::class => [
        '__class' => Dispatcher::class,
        '__construct()' => [
           'listenerProvider' => Reference::to(ListenerProviderInterface::class)
        ],
    ],

    LoggerInterface::class => [
        '__class' => Logger::class,
        '__construct()' => [
            'targets' => [],
        ],
    ],

    Theme::class => [
        '__class' => Theme::class,
    ],

    View::class => [
        '__class' => View::class,
        '__construct()' => [
            'basePath'=> $tempDir . DIRECTORY_SEPARATOR . 'views',
            'theme'=> Reference::to(Theme::class),
            'eventDispatcher' => Reference::to(EventDispatcherInterface::class),
            'logger' => Reference::to(LoggerInterface::class)
        ],
    ],

    Widget::class => [
        '__class' => Widget::class,
        '__construct()' => [
            Reference::to(EventDispatcherInterface::class),
        ]
    ],
];

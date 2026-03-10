<?php

/**
 * @package     com_wheels
 * @subpackage  Administrator Services
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Component\Wheels\Administrator\Extension\WheelsComponent;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class implements ServiceProviderInterface {
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     */
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new MVCFactory('\\Joomla\\Component\\Wheels'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\Joomla\\Component\\Wheels'));
        $container->registerServiceProvider(new RouterFactory('\\Joomla\\Component\\Wheels'));

        $container->set(
            ComponentInterface::class,
            static function (Container $container) {
                $component = new WheelsComponent(
                    $container->get(ComponentDispatcherFactoryInterface::class)
                );

                $component->setMVCFactory($container->get(MVCFactoryInterface::class));

                return $component;
            }
        );
    }
};

<?php

/**
 * @package     com_wheels
 * @subpackage  Install script
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
class Com_WheelsInstallerScript
{
    // phpcs:enable PSR1.Classes.ClassDeclaration.MissingNamespace

    /**
     * Called after any type of action.
     *
     * @param   string            $type    install|uninstall|discover_install|update
     * @param   InstallerAdapter  $parent  The installer adapter.
     *
     * @return  bool
     */
    public function postflight(string $type, InstallerAdapter $parent): bool
    {
        if ($type === 'install' || $type === 'discover_install') {
            $this->createMenuItems();
        }

        return true;
    }

    /**
     * Create the hidden menu items needed for SEF URL routing.
     *
     * @return  void
     */
    private function createMenuItems(): void
    {
        $db = Factory::getDbo();

        // Check if menu items already exist to avoid duplicates
        $query = $db->getQuery(true)
            ->select($db->quoteName('id'))
            ->from($db->quoteName('#__menu'))
            ->where($db->quoteName('component_id') . ' = (SELECT ' . $db->quoteName('extension_id') .
                ' FROM ' . $db->quoteName('#__extensions') .
                ' WHERE ' . $db->quoteName('element') . ' = ' . $db->quote('com_wheels') . ')')
            ->setLimit(1);

        $db->setQuery($query);
        $existing = $db->loadResult();

        if ($existing) {
            // Menu items already exist
            return;
        }

        // Get the component extension_id
        $query = $db->getQuery(true)
            ->select($db->quoteName('extension_id'))
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('element') . ' = ' . $db->quote('com_wheels'))
            ->where($db->quoteName('type') . ' = ' . $db->quote('component'));

        $db->setQuery($query);
        $componentId = (int) $db->loadResult();

        if (!$componentId) {
            return;
        }

        // Menu items to create in the "System" hidden menu
        // Joomla uses client_id = 0 for site, client_id = 1 for admin
        // Hidden system menu uses menutype = 'main' and parent_id = 1 typically
        // For hidden (unlinked) menu items, we use a dedicated menutype
        $menutype = 'wheels-system';

        // Create the menutype if it doesn't exist
        $query = $db->getQuery(true)
            ->select($db->quoteName('menutype'))
            ->from($db->quoteName('#__menu_types'))
            ->where($db->quoteName('menutype') . ' = ' . $db->quote($menutype));

        $db->setQuery($query);
        $menuTypeExists = $db->loadResult();

        if (!$menuTypeExists) {
            $menuTypeData = (object) [
                'menutype'    => $menutype,
                'title'       => 'Wheels System Menu',
                'description' => 'Hidden system menu for com_wheels SEF URLs',
                'client_id'   => 0,
            ];
            $db->insertObject('#__menu_types', $menuTypeData);
        }

        $menuItems = [
            [
                'alias'    => 'all-wheeling-systems',
                'title'    => 'All Wheeling Systems',
                'link'     => 'index.php?option=com_wheels&view=hub',
                'type'     => 'component',
                'params'   => '{}',
                'language' => '*',
            ],
            [
                'alias'    => 'wheeling-systems',
                'title'    => 'Wheeling Systems Category',
                'link'     => 'index.php?option=com_wheels&view=category',
                'type'     => 'component',
                'params'   => '{}',
                'language' => '*',
            ],
            [
                'alias'    => 'wheeling-system',
                'title'    => 'Wheeling System Detail',
                'link'     => 'index.php?option=com_wheels&view=wheel',
                'type'     => 'component',
                'params'   => '{}',
                'language' => '*',
            ],
            [
                'alias'    => 'sitemap-wheels',
                'title'    => 'Wheels Sitemap',
                'link'     => 'index.php?option=com_wheels&view=sitemap&format=raw',
                'type'     => 'component',
                'params'   => '{}',
                'language' => '*',
            ],
        ];

        // Get root menu item id
        $query = $db->getQuery(true)
            ->select($db->quoteName('id'))
            ->from($db->quoteName('#__menu'))
            ->where($db->quoteName('menutype') . ' = ' . $db->quote($menutype))
            ->where($db->quoteName('parent_id') . ' = 0')
            ->setLimit(1);

        $db->setQuery($query);
        $rootId = (int) $db->loadResult();

        if (!$rootId) {
            // Insert root item for this menutype
            $rootData = (object) [
                'menutype'     => $menutype,
                'title'        => 'Menu_Item_Root',
                'alias'        => '',
                'note'         => '',
                'path'         => '',
                'link'         => '',
                'type'         => 'system.default',
                'published'    => 1,
                'parent_id'    => 0,
                'level'        => 0,
                'component_id' => 0,
                'checked_out'  => 0,
                'checked_out_time' => null,
                'browserNav'   => 0,
                'access'       => 1,
                'img'          => '',
                'template_style_id' => 0,
                'params'       => '{}',
                'lft'          => 0,
                'rgt'          => 1,
                'home'         => 0,
                'language'     => '*',
                'client_id'    => 0,
            ];
            $db->insertObject('#__menu', $rootData);
            $rootId = (int) $db->insertid();
        }

        foreach ($menuItems as $item) {
            $menuData = (object) [
                'menutype'     => $menutype,
                'title'        => $item['title'],
                'alias'        => $item['alias'],
                'note'         => '',
                'path'         => $item['alias'],
                'link'         => $item['link'],
                'type'         => $item['type'],
                'published'    => 1,
                'parent_id'    => $rootId,
                'level'        => 1,
                'component_id' => $componentId,
                'checked_out'  => 0,
                'checked_out_time' => null,
                'browserNav'   => 0,
                'access'       => 1,
                'img'          => '',
                'template_style_id' => 0,
                'params'       => $item['params'],
                'lft'          => 0,
                'rgt'          => 1,
                'home'         => 0,
                'language'     => $item['language'],
                'client_id'    => 0,
            ];

            try {
                $db->insertObject('#__menu', $menuData);

                // Rebuild nested sets for menu
                Table::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_menus/tables');
            } catch (\Exception $e) {
                // Silently skip if item already exists
            }
        }

        // Rebuild the menu nested sets
        /** @var \Joomla\CMS\Application\AdministratorApplication $app */
        $app = Factory::getApplication();
        if ($app->isClient('administrator') || $app->isClient('cli_installer')) {
            try {
                /** @var \Joomla\Component\Menus\Administrator\Model\MenuModel $menuModel */
                $menuModel = $app->bootComponent('com_menus')
                    ->getMVCFactory()
                    ->createModel('Menu', 'Administrator', ['ignore_request' => true]);
                $menuModel->rebuild();
            } catch (\Exception $e) {
                // Non-fatal: nested set rebuild failed
            }
        }
    }
}

<?php

/**
 * @package     com_wheels
 * @subpackage  Site Model
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Component\Wheels\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\Component\Wheels\Site\Helper\CategoryHelper;
use Joomla\Component\Wheels\Site\Service\WheelsLoader;

/**
 * Sitemap model.
 *
 * Builds the list of URLs for all wheel pages and category pages to be
 * included in the XML sitemap at /sitemap-wheels.xml.
 *
 * @since  1.0.0
 */
class SitemapModel extends BaseDatabaseModel
{
    /**
     * Returns all sitemap entries as an array of associative arrays.
     *
     * Each entry has:
     *  – loc        (string) absolute URL
     *  – changefreq (string) e.g. "weekly"
     *  – priority   (string) e.g. "0.8"
     *
     * @param   string  $baseUrl  Site base URL (with trailing slash stripped).
     *
     * @return  array<int,array<string,string>>
     */
    public function getEntries(string $baseUrl): array
    {
        $entries = [];
        $wheels  = WheelsLoader::getWheels();

        // Hub page
        $entries[] = [
            'loc'        => $baseUrl . Route::_('index.php?option=com_wheels&view=hub', false),
            'changefreq' => 'weekly',
            'priority'   => '1.0',
        ];

        // Category pages
        $categories = CategoryHelper::getAllCategories($wheels);

        foreach ($categories as $slug => $cat) {
            $entries[] = [
                'loc'        => $baseUrl . Route::_(
                    'index.php?option=com_wheels&view=category&slug=' . rawurlencode($slug),
                    false
                ),
                'changefreq' => 'weekly',
                'priority'   => '0.8',
            ];
        }

        // Individual wheel pages
        foreach ($wheels as $systemId => $wheel) {
            $entries[] = [
                'loc'        => $baseUrl . Route::_(
                    'index.php?option=com_wheels&view=wheel&system_id=' . rawurlencode($systemId),
                    false
                ),
                'changefreq' => 'monthly',
                'priority'   => '0.6',
            ];
        }

        return $entries;
    }
}

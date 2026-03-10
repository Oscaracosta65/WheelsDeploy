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
use Joomla\Component\Wheels\Site\Helper\CategoryHelper;
use Joomla\Component\Wheels\Site\Service\WheelsLoader;

/**
 * Hub page model.
 *
 * Provides the data needed to render the /all-wheeling-systems hub page,
 * including the full list of categories and a set of featured wheels.
 *
 * @since  1.0.0
 */
class HubModel extends BaseDatabaseModel
{
    /**
     * Returns all categories derived from the wheels dataset.
     *
     * @return  array<string,array<string,mixed>>  Categories keyed by slug.
     */
    public function getCategories(): array
    {
        $wheels = WheelsLoader::getWheels();

        return CategoryHelper::getAllCategories($wheels);
    }

    /**
     * Returns a limited number of featured wheels for the hub page.
     *
     * @param   int  $limit  Maximum number of wheels to return.
     *
     * @return  array<string,array<string,mixed>>
     */
    public function getFeaturedWheels(int $limit = 12): array
    {
        $wheels = WheelsLoader::getWheels();

        return array_slice($wheels, 0, $limit, true);
    }

    /**
     * Returns the total number of wheels in the dataset.
     *
     * @return  int
     */
    public function getTotalWheels(): int
    {
        return count(WheelsLoader::getWheels());
    }

    /**
     * Returns categories grouped by their type.
     *
     * Groups: 'pick', 'selections', 'assurance', 'lines'.
     * Any unrecognised type is placed under 'other'.
     *
     * @return  array<string,array<string,array<string,mixed>>>
     */
    public function getCategoriesGrouped(): array
    {
        $categories = $this->getCategories();
        $grouped    = [
            'pick'       => [],
            'selections' => [],
            'assurance'  => [],
            'lines'      => [],
        ];

        foreach ($categories as $slug => $cat) {
            $type = $cat['type'] ?? 'assurance';

            if (isset($grouped[$type])) {
                $grouped[$type][$slug] = $cat;
            }
        }

        // Sort each group by value
        foreach ($grouped as &$group) {
            uasort($group, static function ($a, $b) {
                return ($a['value'] ?? 0) <=> ($b['value'] ?? 0);
            });
        }

        unset($group);

        return $grouped;
    }
}

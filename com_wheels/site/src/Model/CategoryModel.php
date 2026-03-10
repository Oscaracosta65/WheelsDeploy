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
 * Category page model.
 *
 * Provides the list of wheels for a given category slug and the metadata
 * needed to build the breadcrumb and SEO tags.
 *
 * @since  1.0.0
 */
class CategoryModel extends BaseDatabaseModel
{
    /**
     * Returns the wheels belonging to the given category slug.
     *
     * @param   string  $slug  Category slug (e.g. "pick-6", "n-10", "guarantee-4-if-6").
     *
     * @return  array<string,array<string,mixed>>  Wheels keyed by system_id.
     */
    public function getWheelsBySlug(string $slug): array
    {
        $wheels = WheelsLoader::getWheels();

        return CategoryHelper::getWheelsByCategory($wheels, $slug);
    }

    /**
     * Returns the human-readable label for a category slug.
     *
     * @param   string  $slug  Category slug.
     *
     * @return  string
     */
    public function getCategoryLabel(string $slug): string
    {
        return CategoryHelper::getLabelFromSlug($slug);
    }

    /**
     * Returns the category type string ('pick'|'selections'|'assurance'|null).
     *
     * @param   string  $slug  Category slug.
     *
     * @return  string|null
     */
    public function getCategoryType(string $slug): ?string
    {
        return CategoryHelper::getSlugType($slug);
    }

    /**
     * Returns all available categories (for the hub link on category pages).
     *
     * @return  array<string,array<string,mixed>>
     */
    public function getAllCategories(): array
    {
        $wheels = WheelsLoader::getWheels();

        return CategoryHelper::getAllCategories($wheels);
    }
}

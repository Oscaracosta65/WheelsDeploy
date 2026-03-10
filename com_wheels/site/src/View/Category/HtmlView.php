<?php

/**
 * @package     com_wheels
 * @subpackage  Site View
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Component\Wheels\Site\View\Category;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Router\Route;

/**
 * Category page view.
 *
 * Renders /wheeling-systems/{slug} listing pages.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
    /** @var string Active category slug. */
    public string $slug = '';

    /** @var string Human-readable category label. */
    public string $categoryLabel = '';

    /** @var string|null Category type ('pick'|'selections'|'assurance'|null). */
    public ?string $categoryType = null;

    /** @var array<string,array<string,mixed>> Wheels in this category. */
    public array $wheels = [];

    /** @var array<string,array<string,mixed>> All categories (for navigation). */
    public array $allCategories = [];

    /**
     * Prepares data and renders the view.
     *
     * @param   string|null  $tpl  Template override.
     *
     * @return  void
     */
    public function display($tpl = null): void
    {
        $app   = Factory::getApplication();
        $input = $app->getInput();

        $this->slug = $input->get('slug', '', 'cmd');

        /** @var \Joomla\Component\Wheels\Site\Model\CategoryModel $model */
        $model = $this->getModel();

        $this->categoryLabel = $model->getCategoryLabel($this->slug);
        $this->categoryType  = $model->getCategoryType($this->slug);
        $this->wheels        = $model->getWheelsBySlug($this->slug);
        $this->allCategories = $model->getAllCategories();

        $wheelCount = count($this->wheels);

        // Set SEO document properties
        $doc = $app->getDocument();
        $doc->setTitle($this->categoryLabel . ' Lottery Wheels | LottoExpert');
        $doc->setDescription(
            'Browse ' . $wheelCount . ' lottery wheeling systems in the '
            . $this->categoryLabel . ' category. Guaranteed win combinations.'
        );
        $doc->setMetaData('robots', 'index, follow');

        // Canonical
        $doc->addHeadLink(
            Route::_(
                'index.php?option=com_wheels&view=category&slug=' . rawurlencode($this->slug),
                true,
                Route::TLS_IGNORE,
                true
            ),
            'canonical'
        );

        parent::display($tpl);
    }
}

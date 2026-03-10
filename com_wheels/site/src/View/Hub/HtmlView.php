<?php

/**
 * @package     com_wheels
 * @subpackage  Site View
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Component\Wheels\Site\View\Hub;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/**
 * Hub page view.
 *
 * Renders the main /all-wheeling-systems discovery page.
 * Applies noindex meta when filter query-string parameters are present.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
    /** @var array<string,array<string,mixed>> */
    public array $categories = [];

    /** @var array<string,array<string,mixed[]>> */
    public array $categoriesGrouped = [];

    /** @var array<string,array<string,mixed>> */
    public array $featuredWheels = [];

    /** @var int */
    public int $totalWheels = 0;

    /** @var bool True when a filter query string is present (noindex). */
    public bool $hasFilter = false;

    /**
     * Prepares data and renders the view.
     *
     * @param   string|null  $tpl  Template override.
     *
     * @return  void
     */
    public function display($tpl = null): void
    {
        /** @var \Joomla\Component\Wheels\Site\Model\HubModel $model */
        $model = $this->getModel();

        $this->categories       = $model->getCategories();
        $this->categoriesGrouped = $model->getCategoriesGrouped();
        $this->featuredWheels   = $model->getFeaturedWheels(12);
        $this->totalWheels      = $model->getTotalWheels();

        // Detect filter state: any query param other than option/view/Itemid
        $app    = Factory::getApplication();
        $input  = $app->getInput();
        $params = $input->getArray();
        unset($params['option'], $params['view'], $params['Itemid'], $params['format']);
        $this->hasFilter = !empty($params);

        // Set document SEO properties
        $doc = $app->getDocument();
        $doc->setTitle('All Lottery Wheeling Systems | LottoExpert');
        $doc->setDescription(
            'Browse all ' . $this->totalWheels . ' lottery wheeling systems. '
            . 'Find the perfect wheel by pick size, numbers selected or guarantee type.'
        );

        if ($this->hasFilter) {
            // Filter states must NOT be indexable
            $doc->setMetaData('robots', 'noindex, follow');
        } else {
            $doc->setMetaData('robots', 'index, follow');
            // Canonical = self (the clean hub URL)
            $doc->addHeadLink(
                Route::_('index.php?option=com_wheels&view=hub', true, Route::TLS_IGNORE, true),
                'canonical'
            );
        }

        parent::display($tpl);
    }
}

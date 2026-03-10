<?php

/**
 * @package     com_wheels
 * @subpackage  Site View
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Component\Wheels\Site\View\Wheel;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Router\Route;

/**
 * Individual wheel page view.
 *
 * Renders /wheeling-system/{system_id} detail pages.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
    /** @var string Active system_id. */
    public string $systemId = '';

    /** @var array<string,mixed>|null Wheel data (null if not found). */
    public ?array $wheel = null;

    /** @var array<string,array<string,mixed>> Five similar wheels. */
    public array $similarWheels = [];

    /** @var array<string,string> Categories this wheel belongs to (slug => label). */
    public array $wheelCategories = [];

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

        $this->systemId = $input->get('system_id', '', 'cmd');

        /** @var \Joomla\Component\Wheels\Site\Model\WheelModel $model */
        $model = $this->getModel();

        $this->wheel = $model->getWheel($this->systemId);

        if ($this->wheel === null) {
            // Return 404 if wheel not found
            throw new \RuntimeException(
                'Wheel ' . htmlspecialchars($this->systemId, ENT_QUOTES, 'UTF-8') . ' not found.',
                404
            );
        }

        $this->similarWheels    = $model->getSimilarWheels($this->wheel);
        $this->wheelCategories  = $model->getWheelCategories($this->wheel);

        // Raw values for SEO metadata (not HTML-escaped)
        $nameRaw            = (string) ($this->wheel['name'] ?? $this->systemId);
        $requiredSelections = (int) ($this->wheel['requiredSelections'] ?? 0);
        $assuranceTextRaw   = (string) ($this->wheel['assuranceText'] ?? '');
        $combinationsCount  = (int) ($this->wheel['combinationsCount'] ?? 0);
        $siteNameRaw        = (string) Factory::getApplication()->getParams('com_wheels')->get('site_name', 'LottoExpert');

        // Set SEO document properties per spec
        // Title format: {wheel name} – Lottery Wheeling System | LottoExpert
        $doc = $app->getDocument();
        $doc->setTitle($nameRaw . " \u{2013} Lottery Wheeling System | " . $siteNameRaw);
        $doc->setDescription(
            'Lottery wheel for selecting ' . $requiredSelections . ' numbers with '
            . $assuranceTextRaw . ' guarantee producing ' . $combinationsCount . ' combinations.'
        );
        $doc->setMetaData('robots', 'index, follow');

        // Canonical
        $doc->addHeadLink(
            Route::_(
                'index.php?option=com_wheels&view=wheel&system_id=' . rawurlencode($this->systemId),
                true,
                Route::TLS_IGNORE,
                true
            ),
            'canonical'
        );

        parent::display($tpl);
    }
}

<?php

/**
 * @package     com_wheels
 * @subpackage  Site View
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Component\Wheels\Site\View\Sitemap;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Uri\Uri;

/**
 * Sitemap view.
 *
 * Renders the XML sitemap at /sitemap-wheels.xml.
 * The view is called with format=raw so no Joomla template is applied.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
    /** @var array<int,array<string,string>> Sitemap URL entries. */
    public array $entries = [];

    /**
     * Prepares data and renders the sitemap XML.
     *
     * @param   string|null  $tpl  Template override.
     *
     * @return  void
     */
    public function display($tpl = null): void
    {
        $app = Factory::getApplication();

        /** @var \Joomla\Component\Wheels\Site\Model\SitemapModel $model */
        $model = $this->getModel();

        $baseUrl      = rtrim(Uri::base(), '/');
        $this->entries = $model->getEntries($baseUrl);

        // Set appropriate Content-Type for XML output
        $app->getDocument()->setMimeEncoding('application/xml');

        parent::display($tpl);
    }
}

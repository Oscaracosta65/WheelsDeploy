<?php

/**
 * @package     com_wheels
 * @subpackage  Site Controller
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Component\Wheels\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Default display controller for com_wheels.
 *
 * Delegates rendering to the appropriate view based on the "view" request
 * variable. Recognised views: hub, category, wheel, sitemap.
 *
 * @since  1.0.0
 */
class DisplayController extends BaseController
{
    /**
     * Default view name.
     *
     * @var    string
     * @since  1.0.0
     */
    protected $default_view = 'hub';

    /**
     * Method to display a view.
     *
     * @param   bool        $cachable   Ignored (component uses Joomla page-cache).
     * @param   array|bool  $urlparams  URL parameters safe-list.
     *
     * @return  static
     *
     * @since   1.0.0
     */
    public function display($cachable = false, $urlparams = []): static
    {
        $view   = $this->input->get('view', $this->default_view, 'cmd');
        $format = $this->input->get('format', 'html', 'cmd');

        $allowed = ['hub', 'category', 'wheel', 'sitemap'];

        if (!in_array($view, $allowed, true)) {
            $view = $this->default_view;
            $this->input->set('view', $view);
        }

        // For the sitemap, force raw format so Joomla renders without template.
        if ($view === 'sitemap' && $format !== 'raw') {
            $this->input->set('format', 'raw');
        }

        $cachable = in_array($view, ['hub', 'category', 'wheel'], true);

        return parent::display($cachable, $urlparams);
    }
}

<?php

/**
 * @package     com_wheels
 * @subpackage  Site Service
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Component\Wheels\Site\Service;

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\Router\RouterBase;
use Joomla\CMS\Menu\AbstractMenu;

/**
 * SEF URL router for com_wheels.
 *
 * Maps between query-var arrays and URL-segment arrays for the following
 * route shapes (the path prefix shown is the alias of the matching menu item):
 *
 *   Menu alias              Query vars                     Extra segments
 *   ─────────────────────── ─────────────────────────────  ──────────────
 *   all-wheeling-systems    view=hub                       (none)
 *   wheeling-systems        view=category & slug={slug}    {slug}
 *   wheeling-system         view=wheel & system_id={id}    {id}
 *   sitemap-wheels          view=sitemap & format=raw      (none)
 *
 * @since  1.0.0
 */
class Router extends RouterBase
{
    /**
     * Router constructor.
     *
     * @param   CMSApplicationInterface  $app   The CMS application.
     * @param   AbstractMenu             $menu  The menu object.
     */
    public function __construct(CMSApplicationInterface $app, AbstractMenu $menu)
    {
        parent::__construct($app, $menu);
    }

    /**
     * Converts query variables to URL segments.
     *
     * Only the segments that follow the menu-item path are returned; the
     * menu-item alias itself is handled by Joomla's core router.
     *
     * @param   array<string,mixed>  $query  Query variables (modified in place).
     *
     * @return  string[]  URL path segments to append after the menu-item alias.
     */
    public function build(&$query): array
    {
        $segments = [];

        if (!isset($query['view'])) {
            return $segments;
        }

        $view = $query['view'];
        unset($query['view']);

        switch ($view) {
            case 'hub':
                // No extra segments; the menu-item alias is the full path.
                break;

            case 'category':
                if (isset($query['slug'])) {
                    $segments[] = rawurlencode((string) $query['slug']);
                    unset($query['slug']);
                }
                break;

            case 'wheel':
                if (isset($query['system_id'])) {
                    $segments[] = rawurlencode((string) $query['system_id']);
                    unset($query['system_id']);
                }
                break;

            case 'sitemap':
                // No extra segments; format=raw stays in query if not removed.
                if (isset($query['format'])) {
                    unset($query['format']);
                }
                break;
        }

        return $segments;
    }

    /**
     * Converts URL segments (those after the menu-item alias) back to query vars.
     *
     * The active menu item's link vars (e.g. view=category) have already been
     * merged into the request before this method is called, so we only need to
     * handle the extra path segments.
     *
     * @param   string[]  $segments  Remaining URL segments (modified in place).
     *
     * @return  array<string,mixed>  Query variables extracted from segments.
     */
    public function parse(&$segments): array
    {
        $vars = [];

        if (empty($segments)) {
            return $vars;
        }

        // Determine which view is active from the already-matched menu item.
        $activeItem = $this->menu->getActive();
        $view       = '';

        if ($activeItem !== null) {
            parse_str(parse_url($activeItem->link, PHP_URL_QUERY) ?? '', $linkVars);
            $view = $linkVars['view'] ?? '';
        }

        switch ($view) {
            case 'category':
                $vars['slug'] = rawurldecode(array_shift($segments));
                break;

            case 'wheel':
                $vars['system_id'] = rawurldecode(array_shift($segments));
                break;

            case 'sitemap':
                $vars['format'] = 'raw';
                break;
        }

        return $vars;
    }
}

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
 * Individual wheel page model.
 *
 * Loads a single wheel by system_id and computes five "similar" wheels
 * (same pickSize and assuranceText, closest requiredSelections value).
 *
 * @since  1.0.0
 */
class WheelModel extends BaseDatabaseModel
{
    /**
     * Returns the wheel data for the given system_id, or null if not found.
     *
     * @param   string  $systemId  Wheel identifier.
     *
     * @return  array<string,mixed>|null
     */
    public function getWheel(string $systemId): ?array
    {
        return WheelsLoader::getWheel($systemId);
    }

    /**
     * Returns up to five wheels "similar" to the given one.
     *
     * Similarity criteria (in order of preference):
     *  1. Same pickSize AND same assuranceText
     *  2. Sorted by absolute difference in requiredSelections (closest first)
     *  3. Exclude the source wheel itself
     *
     * @param   array<string,mixed>  $wheel  The source wheel.
     * @param   int                  $limit  Maximum number of similar wheels.
     *
     * @return  array<string,array<string,mixed>>  Similar wheels keyed by system_id.
     */
    public function getSimilarWheels(array $wheel, int $limit = 5): array
    {
        $wheels    = WheelsLoader::getWheels();
        $sourceId  = (string) ($wheel['system_id'] ?? '');
        $pickSize  = (int) ($wheel['pickSize'] ?? 0);
        $assurance = (string) ($wheel['assuranceText'] ?? '');
        $selCount  = (int) ($wheel['requiredSelections'] ?? 0);

        $candidates = [];

        foreach ($wheels as $id => $candidate) {
            if ($id === $sourceId) {
                continue;
            }

            if ((int) ($candidate['pickSize'] ?? 0) !== $pickSize) {
                continue;
            }

            if ((string) ($candidate['assuranceText'] ?? '') !== $assurance) {
                continue;
            }

            $diff                       = abs((int) ($candidate['requiredSelections'] ?? 0) - $selCount);
            $candidates[$id]            = $candidate;
            $candidates[$id]['_diff']   = $diff;
        }

        uasort($candidates, static function ($a, $b) {
            return ($a['_diff'] ?? 0) <=> ($b['_diff'] ?? 0);
        });

        $result = array_slice($candidates, 0, $limit, true);

        // Remove internal diff key
        foreach ($result as &$item) {
            unset($item['_diff']);
        }

        unset($item);

        return $result;
    }

    /**
     * Returns the categories the given wheel belongs to.
     *
     * @param   array<string,mixed>  $wheel  Wheel data.
     *
     * @return  array<string,string>  Associative array slug => label.
     */
    public function getWheelCategories(array $wheel): array
    {
        $pickSize           = (int) ($wheel['pickSize'] ?? 0);
        $requiredSelections = (int) ($wheel['requiredSelections'] ?? 0);
        $assuranceText      = (string) ($wheel['assuranceText'] ?? '');

        return [
            CategoryHelper::getPickSlug($pickSize)
                => CategoryHelper::getLabelFromSlug(CategoryHelper::getPickSlug($pickSize)),
            CategoryHelper::getSelectionsSlug($requiredSelections)
                => CategoryHelper::getLabelFromSlug(CategoryHelper::getSelectionsSlug($requiredSelections)),
            CategoryHelper::getAssuranceSlug($assuranceText)
                => CategoryHelper::getLabelFromSlug(CategoryHelper::getAssuranceSlug($assuranceText)),
        ];
    }
}

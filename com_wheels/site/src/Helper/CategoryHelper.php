<?php

/**
 * @package     com_wheels
 * @subpackage  Site Helper
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Component\Wheels\Site\Helper;

defined('_JEXEC') or die;

/**
 * CategoryHelper – derives category slugs and groupings from wheel data.
 *
 * When a wheel record contains the precomputed "categoryKeys" array, those
 * values are used directly. For older records without the field, slugs are
 * derived programmatically from:
 *  – pickSize           → slug: pick-{n}
 *  – requiredSelections → slug: n-{n}
 *  – assuranceText      → slug: guarantee-{normalised}
 *
 * The helper also recognises the "lines-{n}" slug type that appears in
 * categoryKeys for wheels that carry a line-count category.
 *
 * @since  1.0.0
 */
final class CategoryHelper
{
    /**
     * Returns the "pick size" category slug for a given pickSize value.
     *
     * @param   int  $pickSize  E.g. 3, 4, 5, 6.
     *
     * @return  string  E.g. "pick-3".
     */
    public static function getPickSlug(int $pickSize): string
    {
        return 'pick-' . $pickSize;
    }

    /**
     * Returns the "required selections" category slug.
     *
     * @param   int  $requiredSelections  E.g. 7, 10, 20.
     *
     * @return  string  E.g. "n-7".
     */
    public static function getSelectionsSlug(int $requiredSelections): string
    {
        return 'n-' . $requiredSelections;
    }

    /**
     * Returns the assurance / guarantee category slug derived from assuranceText.
     *
     * Normalisation rules:
     *  1. Lower-case the string.
     *  2. Replace one or more whitespace characters with a single hyphen.
     *  3. Strip any characters that are not a–z, 0–9 or hyphen.
     *  4. Prefix with "guarantee-".
     *
     * @param   string  $assuranceText  E.g. "2 if 3".
     *
     * @return  string  E.g. "guarantee-2-if-3".
     */
    public static function getAssuranceSlug(string $assuranceText): string
    {
        $slug = strtolower(trim($assuranceText));
        $slug = (string) preg_replace('/\s+/', '-', $slug);
        $slug = (string) preg_replace('/[^a-z0-9\-]/', '', $slug);
        $slug = trim($slug, '-');

        return 'guarantee-' . $slug;
    }

    /**
     * Returns a human-readable category label for a given slug.
     *
     * Handles the following slug prefixes:
     *  – pick-{n}         → "Pick N Wheels"
     *  – n-{n}            → "N Numbers Selected"
     *  – guarantee-{code} → "Code Guarantee"
     *  – lines-{n}        → "N Lines"
     *
     * @param   string  $slug  Category slug.
     *
     * @return  string  Human-readable label.
     */
    public static function getLabelFromSlug(string $slug): string
    {
        if (preg_match('/^pick-(\d+)$/', $slug, $m)) {
            return 'Pick ' . $m[1] . ' Wheels';
        }

        if (preg_match('/^n-(\d+)$/', $slug, $m)) {
            return $m[1] . ' Numbers Selected';
        }

        if (preg_match('/^guarantee-(.+)$/', $slug, $m)) {
            return ucwords(str_replace('-', ' ', $m[1])) . ' Guarantee';
        }

        if (preg_match('/^lines-(\d+)$/', $slug, $m)) {
            return $m[1] . ' Lines';
        }

        return ucwords(str_replace('-', ' ', $slug));
    }

    /**
     * Returns all unique categories derived from the wheels dataset.
     *
     * When a wheel has the precomputed "categoryKeys" field, its entries are
     * used directly. For wheels without "categoryKeys", the three standard
     * categories are derived from pickSize, requiredSelections, and assuranceText.
     *
     * Each returned entry is an associative array with keys:
     *  – slug  (string)
     *  – label (string)
     *  – type  ('pick' | 'selections' | 'assurance' | 'lines' | 'other')
     *  – value (mixed)  a sortable value extracted from the slug
     *  – count (int)    number of wheels in this category
     *
     * @param   array<string,array<string,mixed>>  $wheels  Associative wheels array.
     *
     * @return  array<string,array<string,mixed>>  Categories keyed by slug.
     */
    public static function getAllCategories(array $wheels): array
    {
        $categories = [];

        foreach ($wheels as $wheel) {
            // Prefer precomputed categoryKeys when available
            $categoryKeys = isset($wheel['categoryKeys']) && is_array($wheel['categoryKeys'])
                ? $wheel['categoryKeys']
                : null;

            if ($categoryKeys !== null) {
                foreach ($categoryKeys as $slug) {
                    $slug = (string) $slug;

                    if (!isset($categories[$slug])) {
                        $categories[$slug] = [
                            'slug'  => $slug,
                            'label' => self::getLabelFromSlug($slug),
                            'type'  => self::getSlugType($slug) ?? 'other',
                            'value' => self::getValueFromSlug($slug),
                            'count' => 0,
                        ];
                    }

                    $categories[$slug]['count']++;
                }

                continue;
            }

            // Fall back: compute categories from individual fields
            $pickSize           = (int) ($wheel['pickSize'] ?? 0);
            $requiredSelections = (int) ($wheel['requiredSelections'] ?? 0);
            $assuranceText      = (string) ($wheel['assuranceText'] ?? '');

            // Pick-size category
            $pickSlug = self::getPickSlug($pickSize);

            if (!isset($categories[$pickSlug])) {
                $categories[$pickSlug] = [
                    'slug'  => $pickSlug,
                    'label' => 'Pick ' . $pickSize . ' Wheels',
                    'type'  => 'pick',
                    'value' => $pickSize,
                    'count' => 0,
                ];
            }

            $categories[$pickSlug]['count']++;

            // Required-selections category
            $selSlug = self::getSelectionsSlug($requiredSelections);

            if (!isset($categories[$selSlug])) {
                $categories[$selSlug] = [
                    'slug'  => $selSlug,
                    'label' => $requiredSelections . ' Numbers Selected',
                    'type'  => 'selections',
                    'value' => $requiredSelections,
                    'count' => 0,
                ];
            }

            $categories[$selSlug]['count']++;

            // Assurance category
            $assSlug = self::getAssuranceSlug($assuranceText);

            if (!isset($categories[$assSlug])) {
                $categories[$assSlug] = [
                    'slug'  => $assSlug,
                    'label' => ucwords($assuranceText) . ' Guarantee',
                    'type'  => 'assurance',
                    'value' => $assuranceText,
                    'count' => 0,
                ];
            }

            $categories[$assSlug]['count']++;
        }

        return $categories;
    }

    /**
     * Returns all wheels that belong to the given category slug.
     *
     * @param   array<string,array<string,mixed>>  $wheels  Full wheels dataset.
     * @param   string                             $slug    Category slug.
     *
     * @return  array<string,array<string,mixed>>  Filtered wheels, preserving keys.
     */
    public static function getWheelsByCategory(array $wheels, string $slug): array
    {
        $result = [];

        foreach ($wheels as $id => $wheel) {
            if (self::wheelMatchesSlug($wheel, $slug)) {
                $result[$id] = $wheel;
            }
        }

        return $result;
    }

    /**
     * Returns true when the given wheel belongs to the given category slug.
     *
     * Checks the precomputed "categoryKeys" array first; falls back to
     * on-the-fly computation from pickSize, requiredSelections, and assuranceText.
     *
     * @param   array<string,mixed>  $wheel  Single wheel data.
     * @param   string               $slug   Category slug to test.
     *
     * @return  bool
     */
    public static function wheelMatchesSlug(array $wheel, string $slug): bool
    {
        // Prefer precomputed categoryKeys
        if (isset($wheel['categoryKeys']) && is_array($wheel['categoryKeys'])) {
            return in_array($slug, $wheel['categoryKeys'], true);
        }

        // Fall back to computed matching
        $pickSize           = (int) ($wheel['pickSize'] ?? 0);
        $requiredSelections = (int) ($wheel['requiredSelections'] ?? 0);
        $assuranceText      = (string) ($wheel['assuranceText'] ?? '');

        if ($slug === self::getPickSlug($pickSize)) {
            return true;
        }

        if ($slug === self::getSelectionsSlug($requiredSelections)) {
            return true;
        }

        if ($slug === self::getAssuranceSlug($assuranceText)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the category type for a given slug, or null if unrecognised.
     *
     * Recognised types and their prefixes:
     *  – 'pick'       → pick-{n}
     *  – 'selections' → n-{n}
     *  – 'assurance'  → guarantee-{…}
     *  – 'lines'      → lines-{n}
     *
     * @param   string  $slug  Category slug.
     *
     * @return  string|null
     */
    public static function getSlugType(string $slug): ?string
    {
        if (str_starts_with($slug, 'pick-')) {
            return 'pick';
        }

        if (str_starts_with($slug, 'n-')) {
            return 'selections';
        }

        if (str_starts_with($slug, 'guarantee-')) {
            return 'assurance';
        }

        if (str_starts_with($slug, 'lines-')) {
            return 'lines';
        }

        return null;
    }

    /**
     * Extracts a sortable scalar value from a slug for use in ordering.
     *
     * – pick-{n}         → int n
     * – n-{n}            → int n
     * – lines-{n}        → int n
     * – guarantee-{…}    → the trailing code string (alphabetical sort)
     * – other            → 0
     *
     * @param   string  $slug  Category slug.
     *
     * @return  int|string
     */
    private static function getValueFromSlug(string $slug): int|string
    {
        if (preg_match('/^(?:pick|n|lines)-(\d+)$/', $slug, $m)) {
            return (int) $m[1];
        }

        if (preg_match('/^guarantee-(.+)$/', $slug, $m)) {
            return $m[1];
        }

        return 0;
    }
}

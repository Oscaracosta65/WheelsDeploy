<?php

/**
 * @package     com_wheels
 * @subpackage  Site Service
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Component\Wheels\Site\Service;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * WheelsLoader – loads and caches the wheels JSON dataset.
 *
 * Reads wheels.json once per request and caches the parsed result in a static
 * property. Uses filemtime() to automatically invalidate the cache whenever the
 * source file is modified.
 *
 * @since  1.0.0
 */
final class WheelsLoader
{
    /** @var array<string,array<string,mixed>>|null In-memory cache of parsed wheels. */
    private static ?array $cache = null;

    /** @var int|null Modification-time stamp of the cached file. */
    private static ?int $cacheTime = null;

    /** @var string Resolved path to wheels.json (populated once). */
    private static string $dataPath = '';

    /**
     * Returns the resolved path to the wheels.json dataset.
     *
     * The path is taken from the component parameter "data_path". When that
     * parameter is empty the default location inside the site media folder is
     * used.
     *
     * @return  string  Absolute filesystem path.
     */
    public static function getDataPath(): string
    {
        if (self::$dataPath !== '') {
            return self::$dataPath;
        }

        try {
            $params = Factory::getApplication()->getParams('com_wheels');
            $configured = trim((string) $params->get('data_path', ''));
        } catch (\Exception $e) {
            $configured = '';
        }

        self::$dataPath = $configured !== ''
            ? $configured
            : JPATH_ROOT . '/media/lottoexpert/wheels/wheels.json';

        return self::$dataPath;
    }

    /**
     * Returns all wheels from the JSON dataset, keyed by system_id.
     *
     * @return  array<string,array<string,mixed>>  Associative array of wheels.
     */
    public static function getWheels(): array
    {
        $path = self::getDataPath();

        if (!is_file($path) || !is_readable($path)) {
            return [];
        }

        $mtime = (int) filemtime($path);

        if (self::$cache !== null && self::$cacheTime === $mtime) {
            return self::$cache;
        }

        $json = file_get_contents($path);

        if ($json === false || $json === '') {
            return [];
        }

        $data = json_decode($json, true);

        if (!is_array($data) || !isset($data['wheels']) || !is_array($data['wheels'])) {
            return [];
        }

        self::$cache    = $data['wheels'];
        self::$cacheTime = $mtime;

        return self::$cache;
    }

    /**
     * Returns a single wheel by its system_id, or null if not found.
     *
     * @param   string  $systemId  The wheel system identifier.
     *
     * @return  array<string,mixed>|null
     */
    public static function getWheel(string $systemId): ?array
    {
        $wheels = self::getWheels();

        return $wheels[$systemId] ?? null;
    }

    /**
     * Resets the in-memory cache. Primarily useful for unit testing.
     *
     * @return  void
     */
    public static function resetCache(): void
    {
        self::$cache     = null;
        self::$cacheTime = null;
        self::$dataPath  = '';
    }
}

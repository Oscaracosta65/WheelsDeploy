<?php
/**
 * @package     com_wheels
 * @subpackage  Site Template
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/** @var \Joomla\Component\Wheels\Site\View\Sitemap\HtmlView $this */

$entries = $this->entries;

// Output raw XML – no HTML template wrapper (format=raw)
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($entries as $entry) : ?>
    <url>
        <loc><?php echo htmlspecialchars($entry['loc'], ENT_XML1, 'UTF-8'); ?></loc>
        <changefreq><?php echo htmlspecialchars($entry['changefreq'], ENT_XML1, 'UTF-8'); ?></changefreq>
        <priority><?php echo htmlspecialchars($entry['priority'], ENT_XML1, 'UTF-8'); ?></priority>
    </url>
<?php endforeach; ?>
</urlset>

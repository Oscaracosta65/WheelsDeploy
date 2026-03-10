<?php
/**
 * @package     com_wheels
 * @subpackage  Site Template
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var \Joomla\Component\Wheels\Site\View\Wheel\HtmlView $this */

$wheel           = $this->wheel;
$systemId        = $this->systemId;
$similarWheels   = $this->similarWheels;
$wheelCategories = $this->wheelCategories;

$name               = htmlspecialchars((string) ($wheel['name'] ?? $systemId), ENT_QUOTES, 'UTF-8');
$requiredSelections = (int) ($wheel['requiredSelections'] ?? 0);
$pickSize           = (int) ($wheel['pickSize'] ?? 0);
$assuranceText      = htmlspecialchars((string) ($wheel['assuranceText'] ?? ''), ENT_QUOTES, 'UTF-8');
$combinationsCount  = (int) ($wheel['combinationsCount'] ?? 0);
$wheelLines         = $wheel['wheelLines'] ?? [];

$hubUrl         = Route::_('index.php?option=com_wheels&view=hub');
$pageUrl        = Route::_('index.php?option=com_wheels&view=wheel&system_id=' . rawurlencode($systemId), true, Route::TLS_IGNORE, true);
$siteName       = htmlspecialchars((string) Factory::getApplication()->getParams('com_wheels')->get('site_name', 'LottoExpert'), ENT_QUOTES, 'UTF-8');
?>
<div class="com-wheels-wheel">

    <!-- ============================================================
         BREADCRUMB
    ============================================================ -->
    <nav aria-label="Breadcrumb" class="wheels-breadcrumb">
        <ol itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="/" itemprop="item"><span itemprop="name">Home</span></a>
                <meta itemprop="position" content="1">
            </li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="<?php echo $hubUrl; ?>" itemprop="item">
                    <span itemprop="name">All Wheeling Systems</span>
                </a>
                <meta itemprop="position" content="2">
            </li>
            <?php
            $bPos = 3;
            foreach ($wheelCategories as $catSlug => $catLabel) :
                $catUrl = Route::_('index.php?option=com_wheels&view=category&slug=' . rawurlencode($catSlug));
            ?>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="<?php echo $catUrl; ?>" itemprop="item">
                    <span itemprop="name"><?php echo htmlspecialchars($catLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                </a>
                <meta itemprop="position" content="<?php echo $bPos++; ?>">
            </li>
            <?php endforeach; ?>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php echo $name; ?></span>
                <meta itemprop="position" content="<?php echo $bPos; ?>">
            </li>
        </ol>
    </nav>

    <!-- ============================================================
         PAGE HEADER
    ============================================================ -->
    <h1><?php echo $name; ?></h1>

    <!-- ============================================================
         SUMMARY BLOCK
    ============================================================ -->
    <section class="wheels-summary" aria-label="Wheel Summary">
        <table class="wheels-summary__table">
            <tbody>
                <tr>
                    <th scope="row">Numbers Selected</th>
                    <td><?php echo $requiredSelections; ?></td>
                </tr>
                <tr>
                    <th scope="row">Pick Size</th>
                    <td><?php echo $pickSize; ?></td>
                </tr>
                <tr>
                    <th scope="row">Guarantee</th>
                    <td><?php echo $assuranceText; ?></td>
                </tr>
                <tr>
                    <th scope="row">Total Ticket Combinations</th>
                    <td><?php echo number_format($combinationsCount); ?></td>
                </tr>
                <tr>
                    <th scope="row">System ID</th>
                    <td><code><?php echo htmlspecialchars($systemId, ENT_QUOTES, 'UTF-8'); ?></code></td>
                </tr>
            </tbody>
        </table>
    </section>

    <!-- ============================================================
         EXPLANATION
    ============================================================ -->
    <section class="wheels-explanation">
        <h2>How This Wheeling System Works</h2>
        <p>
            The <strong><?php echo $name; ?></strong> is a lottery wheeling system designed for
            Pick&#x2009;<?php echo $pickSize; ?> lottery games. You select
            <strong><?php echo $requiredSelections; ?> numbers</strong> from the lottery&rsquo;s
            full number pool and enter them into this wheel to produce
            <strong><?php echo number_format($combinationsCount); ?> unique tickets</strong>.
        </p>
        <p>
            <strong>Guarantee:</strong> <?php echo $assuranceText; ?>. This means that if the
            required count of your chosen numbers appears among the official draw, at least one
            of your <?php echo number_format($combinationsCount); ?>&nbsp;tickets is guaranteed to
            win the corresponding prize tier.
        </p>
        <h3>How to Use This Wheel</h3>
        <ol>
            <li>
                Choose <?php echo $requiredSelections; ?> numbers from your lottery&rsquo;s
                full range (e.g. 1&#x2013;49 for a typical game).
            </li>
            <li>
                Assign each chosen number to a wheel position (position&nbsp;1, 2,
                3&#x2026;<?php echo $requiredSelections; ?>).
            </li>
            <li>
                Replace the numbers in the combination table below with your assigned
                numbers, preserving their positions.
            </li>
            <li>
                Purchase one ticket per row in the combination table.
            </li>
        </ol>
        <p>
            For example, if your chosen numbers are
            <em>3, 7, 12, 18, 25, 33<?php if ($requiredSelections > 6) : ?>, &hellip;<?php endif; ?></em>,
            then position&nbsp;1&nbsp;=&nbsp;3, position&nbsp;2&nbsp;=&nbsp;7, and so on.
            Each row in the table shows which positions (numbers) to play on that ticket.
        </p>
    </section>

    <!-- ============================================================
         COMBINATION TABLE
    ============================================================ -->
    <section class="wheels-combinations" id="combination-table">
        <h2>
            Wheel Combinations
            <span class="wheels-count">(<?php echo number_format($combinationsCount); ?> tickets)</span>
        </h2>

        <?php if (empty($wheelLines)) : ?>
        <p class="wheels-no-combinations">Combination data not available for this wheel.</p>
        <?php else : ?>
        <div class="wheels-table-wrapper">
            <table class="wheels-table" id="wheels-combination-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <?php for ($c = 1; $c <= $pickSize; $c++) : ?>
                        <th scope="col">Pos&nbsp;<?php echo $c; ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wheelLines as $rowIdx => $line) : ?>
                    <tr>
                        <td class="wheels-table__rownum"><?php echo (int) $rowIdx + 1; ?></td>
                        <?php foreach ((array) $line as $num) : ?>
                        <td><?php echo (int) $num; ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </section>

    <!-- ============================================================
         CATEGORY LINKS
    ============================================================ -->
    <?php if (!empty($wheelCategories)) : ?>
    <section class="wheels-categories-nav">
        <h2>Browse Related Categories</h2>
        <ul class="wheels-category-list">
            <?php foreach ($wheelCategories as $catSlug => $catLabel) : ?>
            <li>
                <a href="<?php echo Route::_('index.php?option=com_wheels&view=category&slug=' . rawurlencode($catSlug)); ?>">
                    <?php echo htmlspecialchars($catLabel, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <!-- ============================================================
         SIMILAR WHEELS
    ============================================================ -->
    <?php if (!empty($similarWheels)) : ?>
    <section class="wheels-similar" id="similar-wheels">
        <h2>Similar Wheeling Systems</h2>
        <p>
            These wheels share the same Pick Size and Guarantee, with a similar number of
            selections.
        </p>
        <ul class="wheels-list">
            <?php foreach ($similarWheels as $simId => $simWheel) : ?>
            <li class="wheels-list__item">
                <a href="<?php echo Route::_('index.php?option=com_wheels&view=wheel&system_id=' . rawurlencode($simId)); ?>"
                   class="wheels-list__link">
                    <span class="wheels-list__name">
                        <?php echo htmlspecialchars($simWheel['name'] ?? $simId, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </a>
                <span class="wheels-list__meta">
                    <?php echo (int) ($simWheel['requiredSelections'] ?? 0); ?> numbers &bull;
                    <?php echo (int) ($simWheel['combinationsCount'] ?? 0); ?> tickets
                </span>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <!-- ============================================================
         BACK TO HUB
    ============================================================ -->
    <p class="wheels-back-link">
        <a href="<?php echo $hubUrl; ?>">&#8592; Back to All Wheeling Systems</a>
    </p>

    <!-- ============================================================
         JSON-LD: BreadcrumbList
    ============================================================ -->
    <?php
    $schemaBase    = rtrim(Uri::base(), '/');
    $schemaHubUrl  = Route::_('index.php?option=com_wheels&view=hub', true, Route::TLS_IGNORE, true);
    $wheelNameRaw  = (string) ($wheel['name'] ?? $systemId);

    $wheelBreadcrumb = [
        '@context' => 'https://schema.org',
        '@type'    => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $schemaBase],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'All Wheeling Systems', 'item' => $schemaHubUrl],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $wheelNameRaw, 'item' => $pageUrl],
        ],
    ];

    echo '<script type="application/ld+json">'
        . json_encode($wheelBreadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)
        . '</script>';
    ?>

</div><!-- /.com-wheels-wheel -->

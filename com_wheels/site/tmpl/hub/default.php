<?php
/**
 * @package     com_wheels
 * @subpackage  Site Template
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

/** @var \Joomla\Component\Wheels\Site\View\Hub\HtmlView $this */

$categories       = $this->categories;
$categoriesGrouped = $this->categoriesGrouped;
$featuredWheels   = $this->featuredWheels;
$totalWheels      = $this->totalWheels;
?>
<div class="com-wheels-hub">

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
                <span itemprop="name">All Wheeling Systems</span>
                <meta itemprop="position" content="2">
            </li>
        </ol>
    </nav>

    <!-- ============================================================
         HERO / INTRO
    ============================================================ -->
    <section class="wheels-intro">
        <h1>All Lottery Wheeling Systems</h1>
        <p class="wheels-intro__lead">
            Discover all <strong><?php echo (int) $totalWheels; ?> lottery wheeling systems</strong>
            available on LottoExpert. Filter by pick size, number pool or
            guarantee type to find the perfect wheel for your game.
        </p>
    </section>

    <!-- ============================================================
         WHAT IS LOTTERY WHEELING?
    ============================================================ -->
    <section class="wheels-explainer">
        <h2>What Is Lottery Wheeling?</h2>
        <p>
            A <strong>lottery wheeling system</strong> is a mathematical strategy that
            allows you to cover multiple number combinations in a structured way,
            maximising your chances of matching the required numbers to win a prize.
            Instead of playing random tickets, a wheel guarantees that if a certain
            subset of your selected numbers is drawn, you will win at least a minimum
            prize tier.
        </p>
        <p>
            Wheeling systems are categorised by three key parameters:
        </p>
        <ul>
            <li>
                <strong>Numbers Selected (N)</strong> – the total pool of numbers you choose
                to include in the wheel (e.g. N&#x2009;=&#x2009;7 means you select 7 numbers
                to wheel).
            </li>
            <li>
                <strong>Pick Size</strong> – how many numbers appear on each ticket
                (e.g. Pick&#x2009;6 means each line has 6 numbers).
            </li>
            <li>
                <strong>Guarantee</strong> – the minimum win condition if a certain count
                of your selected numbers is drawn (e.g. "3 if 4" means: if any 4 of your
                selected numbers are drawn, at least one ticket wins a 3-number prize).
            </li>
        </ul>
    </section>

    <!-- ============================================================
         UNDERSTANDING GUARANTEES
    ============================================================ -->
    <section class="wheels-guarantee-explainer">
        <h2>Understanding Wheel Guarantees</h2>
        <p>
            The <strong>guarantee</strong> is the most important property of a wheeling
            system. It is expressed as &ldquo;X if Y&rdquo;, where:
        </p>
        <ul>
            <li><strong>X</strong> is the minimum number of matches on a single ticket.</li>
            <li><strong>Y</strong> is how many of your selected numbers must appear in
                the draw for the guarantee to apply.</li>
        </ul>
        <p>
            For example, a wheel with a guarantee of
            <em>3 if 4</em> means: if at least 4 of the numbers you wheeled are among
            the drawn numbers, you are guaranteed to have at least one ticket with 3
            correct numbers. Higher guarantees (e.g. 5 if 6) require more tickets but
            offer stronger win assurance.
        </p>
    </section>

    <!-- ============================================================
         FILTER MODULE PLACEHOLDER
         The Joomla module "mod_wheels_filter" is loaded here.
    ============================================================ -->
    <div class="wheels-filter-module" id="wheels-filter">
        <?php echo HTMLHelper::_('content.prepare', '{loadmodule mod_wheels_filter,wheels_filter}'); ?>
    </div>

    <!-- ============================================================
         CATEGORY LINKS – by Pick Size
    ============================================================ -->
    <?php if (!empty($categoriesGrouped['pick'])) : ?>
    <section class="wheels-category-section" id="by-pick-size">
        <h2>Browse by Pick Size</h2>
        <p>Choose wheels grouped by the number of balls on each ticket.</p>
        <ul class="wheels-category-list">
            <?php foreach ($categoriesGrouped['pick'] as $slug => $cat) : ?>
            <li class="wheels-category-list__item">
                <a href="<?php echo Route::_('index.php?option=com_wheels&view=category&slug=' . rawurlencode($slug)); ?>"
                   class="wheels-category-link">
                    <?php echo htmlspecialchars($cat['label'], ENT_QUOTES, 'UTF-8'); ?>
                    <span class="wheels-category-link__count">(<?php echo (int) $cat['count']; ?>)</span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <!-- ============================================================
         CATEGORY LINKS – by Numbers Selected (N)
    ============================================================ -->
    <?php if (!empty($categoriesGrouped['selections'])) : ?>
    <section class="wheels-category-section" id="by-numbers-selected">
        <h2>Browse by Numbers Selected</h2>
        <p>Find wheels that use a specific pool of selected numbers.</p>
        <ul class="wheels-category-list">
            <?php foreach ($categoriesGrouped['selections'] as $slug => $cat) : ?>
            <li class="wheels-category-list__item">
                <a href="<?php echo Route::_('index.php?option=com_wheels&view=category&slug=' . rawurlencode($slug)); ?>"
                   class="wheels-category-link">
                    <?php echo htmlspecialchars($cat['label'], ENT_QUOTES, 'UTF-8'); ?>
                    <span class="wheels-category-link__count">(<?php echo (int) $cat['count']; ?>)</span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <!-- ============================================================
         CATEGORY LINKS – by Guarantee
    ============================================================ -->
    <?php if (!empty($categoriesGrouped['assurance'])) : ?>
    <section class="wheels-category-section" id="by-guarantee">
        <h2>Browse by Guarantee</h2>
        <p>Select wheels with a specific win guarantee condition.</p>
        <ul class="wheels-category-list">
            <?php foreach ($categoriesGrouped['assurance'] as $slug => $cat) : ?>
            <li class="wheels-category-list__item">
                <a href="<?php echo Route::_('index.php?option=com_wheels&view=category&slug=' . rawurlencode($slug)); ?>"
                   class="wheels-category-link">
                    <?php echo htmlspecialchars($cat['label'], ENT_QUOTES, 'UTF-8'); ?>
                    <span class="wheels-category-link__count">(<?php echo (int) $cat['count']; ?>)</span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <!-- ============================================================
         FEATURED WHEELS
    ============================================================ -->
    <?php if (!empty($featuredWheels)) : ?>
    <section class="wheels-featured" id="featured-wheels">
        <h2>Popular Wheeling Systems</h2>
        <ul class="wheels-list wheels-list--grid" itemscope itemtype="https://schema.org/ItemList">
            <?php $pos = 1; foreach ($featuredWheels as $id => $w) : ?>
            <li class="wheels-list__item"
                itemprop="itemListElement"
                itemscope itemtype="https://schema.org/ListItem">
                <meta itemprop="position" content="<?php echo $pos++; ?>">
                <a href="<?php echo Route::_('index.php?option=com_wheels&view=wheel&system_id=' . rawurlencode($id)); ?>"
                   itemprop="url"
                   class="wheels-list__link">
                    <span itemprop="name" class="wheels-list__name">
                        <?php echo htmlspecialchars($w['name'] ?? $id, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <span class="wheels-list__meta">
                        Pick&#x2009;<?php echo (int) ($w['pickSize'] ?? 0); ?> &bull;
                        <?php echo (int) ($w['requiredSelections'] ?? 0); ?> numbers &bull;
                        <?php echo htmlspecialchars($w['assuranceText'] ?? '', ENT_QUOTES, 'UTF-8'); ?> guarantee
                    </span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <!-- ============================================================
         JSON-LD: BreadcrumbList
    ============================================================ -->
    <?php
    $schemaBase   = rtrim(\Joomla\CMS\Uri\Uri::base(), '/');
    $schemaHubUrl = Route::_('index.php?option=com_wheels&view=hub', true, Route::TLS_IGNORE, true);
    $hubBreadcrumb = [
        '@context' => 'https://schema.org',
        '@type'    => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $schemaBase],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'All Wheeling Systems', 'item' => $schemaHubUrl],
        ],
    ];
    echo '<script type="application/ld+json">'
        . json_encode($hubBreadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)
        . '</script>';
    ?>

</div><!-- /.com-wheels-hub -->

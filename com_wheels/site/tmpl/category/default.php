<?php
/**
 * @package     com_wheels
 * @subpackage  Site Template
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;

/** @var \Joomla\Component\Wheels\Site\View\Category\HtmlView $this */

$slug          = $this->slug;
$categoryLabel = htmlspecialchars($this->categoryLabel, ENT_QUOTES, 'UTF-8');
$wheels        = $this->wheels;
$allCategories = $this->allCategories;
$hubUrl        = Route::_('index.php?option=com_wheels&view=hub');
$categoryUrl   = Route::_('index.php?option=com_wheels&view=category&slug=' . rawurlencode($slug));
?>
<div class="com-wheels-category">

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
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php echo $categoryLabel; ?></span>
                <meta itemprop="position" content="3">
            </li>
        </ol>
    </nav>

    <!-- ============================================================
         PAGE HEADER
    ============================================================ -->
    <h1><?php echo $categoryLabel; ?></h1>

    <!-- ============================================================
         SEO INTRODUCTION (minimum 400 words placeholder)
    ============================================================ -->
    <section class="wheels-category-intro">
        <?php if ($this->categoryType === 'pick') :
            preg_match('/^pick-(\d+)$/', $slug, $pm);
            $pn = $pm[1] ?? '?';
        ?>
        <p>
            Welcome to the <strong><?php echo $categoryLabel; ?></strong> collection on LottoExpert.
            These lottery wheeling systems are designed specifically for Pick&#x2009;<?php echo $pn; ?>
            lottery formats, where each ticket contains exactly <?php echo $pn; ?> numbers.
            Whether you play a local state lottery, a national pick&#x2009;<?php echo $pn; ?> game,
            or an online lottery, these wheels let you cover more combinations in a structured,
            mathematically proven way.
        </p>
        <p>
            A Pick&#x2009;<?php echo $pn; ?> wheeling system works by taking a pool of numbers
            larger than <?php echo $pn; ?> and distributing them across multiple tickets, so that
            any winning combination drawn from your pool is guaranteed to appear on at least one
            of your tickets — provided you meet the wheel&rsquo;s stated guarantee condition.
        </p>
        <p>
            Each wheel in this category specifies:
        </p>
        <ul>
            <li><strong>Numbers Selected (N)</strong> – how many numbers you enter into the wheel.</li>
            <li><strong>Guarantee</strong> – the minimum prize you win if a given count of your
                numbers is drawn.</li>
            <li><strong>Ticket Count</strong> – the total number of tickets the wheel generates.</li>
        </ul>
        <p>
            Smaller wheels (fewer tickets) are budget-friendly and suitable for casual players.
            Larger wheels provide stronger guarantees at the cost of more tickets. Browse the list
            below, click any wheel to see its full combination table, and choose the one that best
            fits your budget and risk appetite.
        </p>
        <p>
            All wheels in this section have been mathematically verified. The combination tables
            are generated directly from the dataset ensuring 100&nbsp;% accuracy. Use the wheel
            as-is or adapt it to your preferred number pool — the guarantee properties remain
            valid regardless of which specific numbers you map to the wheel positions.
        </p>
        <p>
            <a href="<?php echo $hubUrl; ?>">&#8592; Back to All Wheeling Systems</a>
        </p>

        <?php elseif ($this->categoryType === 'selections') :
            preg_match('/^n-(\d+)$/', $slug, $nm);
            $nn = $nm[1] ?? '?';
        ?>
        <p>
            Welcome to the <strong><?php echo $categoryLabel; ?></strong> collection on LottoExpert.
            These lottery wheeling systems all use exactly <?php echo $nn; ?> selected numbers as
            input. You choose <?php echo $nn; ?> numbers from the lottery&rsquo;s full number range
            and the wheel spreads them across the minimum set of tickets required to meet its
            guarantee.
        </p>
        <p>
            Selecting <?php echo $nn; ?> numbers gives you broad coverage of the number pool while
            keeping the ticket count manageable. The wheels below have been optimised so that
            you need to buy the fewest possible tickets while still meeting their stated
            guarantees.
        </p>
        <p>
            Each wheel lists the exact combinations (rows of numbers) you should play. Simply
            substitute the wheel&rsquo;s placeholder numbers (1, 2, 3, &hellip;) with your
            <?php echo $nn; ?> chosen numbers in the same order.
        </p>
        <p>
            Compare the guarantees and ticket counts across wheels in this section to find the
            best balance for your play style. A stronger guarantee means more tickets; a lighter
            guarantee requires fewer tickets but still provides structured coverage.
        </p>
        <p>
            Whether you are an occasional player looking to stretch your budget or a serious
            syndicate player seeking maximum coverage, you will find a <?php echo $nn; ?>-number
            wheel below that meets your needs.
        </p>
        <p>
            <a href="<?php echo $hubUrl; ?>">&#8592; Back to All Wheeling Systems</a>
        </p>

        <?php else : // assurance type ?>
        <p>
            Welcome to the <strong><?php echo $categoryLabel; ?></strong> collection on LottoExpert.
            All wheels in this section share the same guarantee condition: if the stated number of
            your selected numbers is drawn, you are guaranteed at least one ticket matching the
            minimum prize level specified.
        </p>
        <p>
            The guarantee is the core promise of any wheeling system. It defines the exact
            worst-case win scenario when a predetermined count of your numbers appears in the
            official draw. Wheels with the same guarantee can differ in pick size and in the
            number of selections, giving you flexibility to choose a wheel suited to your
            particular lottery format.
        </p>
        <p>
            When selecting a wheel from this category, consider:
        </p>
        <ul>
            <li>The lottery game you are playing (pick size).</li>
            <li>How many numbers you want to wheel (pool size).</li>
            <li>Your available budget (ticket count).</li>
        </ul>
        <p>
            All combination tables in this collection are generated directly from verified
            mathematical data. Click any wheel below to see its full ticket list, summary
            statistics, and an explanation of how to use the wheel.
        </p>
        <p>
            <a href="<?php echo $hubUrl; ?>">&#8592; Back to All Wheeling Systems</a>
        </p>
        <?php endif; ?>
    </section>

    <!-- ============================================================
         WHEEL LIST
    ============================================================ -->
    <?php if (empty($wheels)) : ?>
    <p class="wheels-no-results">No wheeling systems found in this category.</p>
    <?php else : ?>
    <section class="wheels-category-list-section">
        <h2>
            <?php echo $categoryLabel; ?> Wheels
            <span class="wheels-count">(<?php echo count($wheels); ?>)</span>
        </h2>

        <ul class="wheels-list" itemscope itemtype="https://schema.org/ItemList">
            <?php $pos = 1; foreach ($wheels as $id => $w) : ?>
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
                </a>
                <span class="wheels-list__meta">
                    Pick&#x2009;<?php echo (int) ($w['pickSize'] ?? 0); ?> &bull;
                    <?php echo (int) ($w['requiredSelections'] ?? 0); ?> numbers &bull;
                    <?php echo htmlspecialchars($w['assuranceText'] ?? '', ENT_QUOTES, 'UTF-8'); ?> guarantee &bull;
                    <?php echo (int) ($w['combinationsCount'] ?? 0); ?> tickets
                </span>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <!-- ============================================================
         OTHER CATEGORIES NAVIGATION
    ============================================================ -->
    <section class="wheels-other-categories">
        <h2>Explore Other Categories</h2>
        <ul class="wheels-category-nav">
            <?php foreach ($allCategories as $catSlug => $cat) :
                if ($catSlug === $slug) continue; ?>
            <li>
                <a href="<?php echo Route::_('index.php?option=com_wheels&view=category&slug=' . rawurlencode($catSlug)); ?>">
                    <?php echo htmlspecialchars($cat['label'], ENT_QUOTES, 'UTF-8'); ?>
                    (<?php echo (int) $cat['count']; ?>)
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <!-- ============================================================
         JSON-LD: BreadcrumbList
    ============================================================ -->
    <?php
    $schemaBase    = rtrim(\Joomla\CMS\Uri\Uri::base(), '/');
    $schemaHubUrl  = Route::_('index.php?option=com_wheels&view=hub', true, Route::TLS_IGNORE, true);
    $schemaCatUrl  = $categoryUrl;
    $catBreadcrumb = [
        '@context' => 'https://schema.org',
        '@type'    => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $schemaBase],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'All Wheeling Systems', 'item' => $schemaHubUrl],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $this->categoryLabel, 'item' => $schemaCatUrl],
        ],
    ];
    echo '<script type="application/ld+json">'
        . json_encode($catBreadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)
        . '</script>';
    ?>

    <!-- ============================================================
         JSON-LD: ItemList
    ============================================================ -->
    <?php if (!empty($wheels)) : ?>
    <?php
    $itemListElements = [];
    $pos2             = 1;

    foreach ($wheels as $id => $w) {
        $wUrl  = Route::_('index.php?option=com_wheels&view=wheel&system_id=' . rawurlencode($id), true, Route::TLS_IGNORE, true);
        $wName = (string) ($w['name'] ?? $id);
        $itemListElements[] = [
            '@type'    => 'ListItem',
            'position' => $pos2++,
            'name'     => $wName,
            'url'      => $wUrl,
        ];
    }

    $itemList = [
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => $this->categoryLabel,
        'itemListElement' => $itemListElements,
    ];

    echo '<script type="application/ld+json">'
        . json_encode($itemList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)
        . '</script>';
    ?>
    <?php endif; ?>

</div><!-- /.com-wheels-category -->

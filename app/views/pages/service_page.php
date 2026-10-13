<?php
/*
 * The shared template every service page is built from. All of its text, photos and lists come
 * from the service's content in the admin (Service_schema describes the sections), and each
 * section is shown only when it is switched on and has something to show.
 *
 * Expects: $data['service'] (settings row), $data['content'] ['f' => fields, 'items' => lists],
 *          $data['related'], $data['isDraft'].
 */
require_once APPROOT . '/views/inc/header.php';
// Repeated values, defined once
$activeCls = ' active';
$enquiryLink = '/contact-us#enquiry';

$s = $data['service'];
$f = $data['content']['f'];
$items = $data['content']['items'];

$e = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
$para = function ($v) use ($e) { return nl2br($e($v)); };
// only site images, never an arbitrary URL
$img = function ($p) use ($e) { return (is_string($p) && strpos($p, '/images/') === 0 && strpos($p, '..') === false) ? $e(URLROOT . $p) : ''; };
$icon = function ($c) use ($e) { return preg_match('/^fa-[a-z0-9-]+$/', (string)$c) ? $e($c) : ''; };
// site-relative, http(s), #anchor, mailto: and tel: links only
$href = function ($u) use ($e) {
    $u = trim((string)$u);
    if ($u === '') { return '#'; }
    if ($u[0] === '/' && substr($u, 0, 2) !== '//') { return $e(URLROOT . $u); }
    if (preg_match('#^(https?://|mailto:|tel:|\#)#i', $u)) { return $e($u); }
    return '#';
};
$on = function ($key) use ($f) { return ($f[$key . '_show'] ?? '0') === '1'; };
$list = function ($key) use ($items) { return $items[$key] ?? []; };
$val = function ($k, $default = '') use ($f) { return trim((string)($f[$k] ?? '')) !== '' ? trim($f[$k]) : $default; };

// ---- which sections are visible (and the labels for the jump menu) ----
$vis = [
    'introduction' => $on('intro') && ($val('intro_text') !== '' || $list('intro_strip')),
    'highlight-1'  => $on('hl1') && ($val('hl1_heading') !== '' || $val('hl1_text') !== '' || $list('hl1_items')),
    'options'      => $on('options') && $list('options'),
    'location'     => $on('location') && ($val('location_heading') !== '' || $val('location_highlight') !== ''),
    'feature-tabs' => $on('tabs') && $list('tabs'),
    'who-its-for'  => $on('audience') && $list('audience'),
    'key-points'   => $on('keypoints') && $list('keypoints'),
    'steps'        => $on('steps') && $list('steps'),
    'highlight-2'  => $on('hl2') && ($val('hl2_heading') !== '' || $val('hl2_text') !== '' || $list('hl2_items')),
    'faq'          => $on('faq') && $list('faq'),
    'closing-banner' => $on('cta') && $val('cta_heading') !== '',
    'explore-more' => $on('related') && !empty($data['related']),
];
$jump = [
    'highlight-1' => $val('hl1_eyebrow', 'Overview'), 'options' => $val('options_eyebrow', 'Options'), 'location' => 'Location',
    'feature-tabs' => $val('tabs_eyebrow', 'Features'), 'who-its-for' => $val('audience_eyebrow', "Who It's For"),
    'key-points' => $val('keypoints_eyebrow', 'Key Points'), 'steps' => $val('steps_eyebrow', 'Steps'),
    'highlight-2' => $val('hl2_eyebrow', 'More'), 'faq' => 'FAQ',
];
$jumpItems = [];
foreach ($jump as $key => $label) { if (!empty($vis[$key])) { $jumpItems[$key] = $label; } }

$alt = false;          // alternates section backgrounds as they are printed
$bg = function () use (&$alt) { $alt = !$alt; return $alt ? '' : ' bg-alt'; };

$bannerImg = $img($f['banner_image'] ?? '') ?: $e(URLROOT . '/images/services_bg.jpg');
$tagline = $val('banner_tagline');
$heading = $val('banner_heading', $s['title']);

// Renders one "highlight" block (photo + text + sliding cards or a row list)
$highlight = function ($p, $secKey) use ($f, $e, $para, $img, $icon, $href, $val, $list, $bg, $enquiryLink) {
    $style = ($f[$p . '_style'] ?? 'list') === 'slides' ? 'slides' : 'list';
    $imgUrl = $img($f[$p . '_image'] ?? '');
    $side = ($f[$p . '_side'] ?? 'left') === 'right' ? 'right' : 'left';
    $rows = $list($p . '_items');
    $btnText = $val($p . '_btn_text');
    ob_start(); ?>
<section class="section-padding<?php echo $bg(); ?>" id="sec-<?php echo $secKey; ?>">
    <div class="container max-w-1100">
        <div class="<?php echo $imgUrl !== '' ? 'sw-editorial' : 'sw-ed-solo'; ?>"<?php echo $imgUrl === '' ? ' style="max-width:820px; margin:0 auto;"' : ''; ?>>
            <?php
            $media = '';
            if ($imgUrl !== '') {
                $media = '<div class="sw-ed-media premium-img-wrapper" data-aos="fade-' . ($side === 'left' ? 'right' : 'left') . '"><img src="' . $imgUrl . '" alt="' . $e($val($p . '_heading')) . '" class="premium-img" loading="lazy">';
                if ($val($p . '_badge_big') !== '' || $val($p . '_badge_small') !== '') {
                    $media .= '<div class="sw-ed-badge"><strong>' . $e($val($p . '_badge_big')) . '</strong><small>' . $e($val($p . '_badge_small')) . '</small></div>';
                }
                $media .= '</div>';
            }
            ob_start(); ?>
            <div class="sw-ed-body" data-aos="fade-up">
                <?php if ($val($p . '_eyebrow') !== ''): ?><span class="category-eyebrow"><?php echo $e($val($p . '_eyebrow')); ?></span><?php endif; ?>
                <?php if ($val($p . '_heading') !== ''): ?><h2 class="section-title" style="text-align:left;"><?php echo $e($val($p . '_heading')); ?></h2><?php endif; ?>
                <?php if ($val($p . '_text') !== ''): ?><p><?php echo $para($val($p . '_text')); ?></p><?php endif; ?>

                <?php if ($rows && $style === 'slides'): ?>
                <div class="sw-carousel-container">
                    <button class="sw-carousel-btn sw-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                    <div class="sw-carousel-viewport">
                        <div class="sw-carousel-track">
                            <?php foreach ($rows as $r): ?>
                            <div class="sw-carousel-slide">
                                <?php if ($icon($r['icon'] ?? '')): ?><i class="fas <?php echo $icon($r['icon']); ?>"></i><?php endif; ?>
                                <div>
                                    <h3><?php echo $e($r['title'] ?? ''); ?></h3>
                                    <?php if (trim((string)($r['text'] ?? '')) !== ''): ?><p><?php echo $para($r['text']); ?></p><?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button class="sw-carousel-btn sw-next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
                </div>
                <?php elseif ($rows): ?>
                <?php $hasRowText = false; foreach ($rows as $r) { if (trim((string)($r['text'] ?? '')) !== '') { $hasRowText = true; break; } } ?>
                <ul class="sw-rows<?php echo $hasRowText ? '' : ' sw-rows-compact'; ?>">
                    <?php foreach ($rows as $r): ?>
                    <li><i class="fas <?php echo $icon($r['icon'] ?? '') ?: 'fa-circle-check'; ?>"></i><div><h3><?php echo $e($r['title'] ?? ''); ?></h3><?php if (trim((string)($r['text'] ?? '')) !== ''): ?><p><?php echo $para($r['text']); ?></p><?php endif; ?></div></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php if ($btnText !== ''): ?><a href="<?php echo $href($val($p . '_btn_link', $enquiryLink)); ?>" class="btn btn-primary mt-4"><?php echo $e($btnText); ?></a><?php endif; ?>
            </div>
            <?php $body = ob_get_clean();
            echo $side === 'right' ? $body . $media : $media . $body; ?>
        </div>
    </div>
</section>
    <?php return ob_get_clean();
};

// eyebrow + heading + subtitle block shared by several sections
$head = function ($p) use ($e, $para, $val) {
    $o = '';
    if ($val($p . '_eyebrow') !== '') { $o .= '<span class="category-eyebrow">' . $e($val($p . '_eyebrow')) . '</span>'; }
    if ($val($p . '_heading') !== '') { $o .= '<h2 class="section-title">' . $e($val($p . '_heading')) . '</h2>'; }
    if ($val($p . '_subtitle') !== '') { $o .= '<p class="section-subtitle">' . $para($val($p . '_subtitle')) . '</p>'; }
    return $o;
};
?>
<?php if (!empty($data['isDraft'])): ?>
<div style="background:#fef3c7; color:#92400e; text-align:center; padding:10px 16px; font-size:0.9rem; font-weight:600; position:relative; z-index:5;">
    <i class="fas fa-eye-slash"></i> Draft preview &mdash; this service is not published yet and only logged-in admins can see it.
</div>
<?php endif; ?>

<!-- Page banner -->
<section class="page-banner" style="--banner-img: url('<?php echo $bannerImg; ?>'); color: #fff;">
    <div class="banner-overlay"></div>
    <div class="container banner-content text-center" style="position: relative; z-index: 10;">
        <h1 style="margin-bottom: 0.5rem;"><?php echo $e($heading); ?></h1>
        <?php if ($tagline !== ''): ?><p class="tagline"><?php echo $e($tagline); ?></p><?php endif; ?>
        <div class="breadcrumb" style="margin-top: 1.5rem;">
            <a href="<?php echo URLROOT; ?>/">Home</a> <span>&gt;</span>
            <a href="<?php echo URLROOT; ?>/services">Services</a> <span>&gt;</span>
            <span><?php echo $e($s['title']); ?></span>
        </div>
    </div>
</section>

<?php if (count($jumpItems) >= 3): ?>
<nav class="services-subnav">
    <div class="container">
        <?php foreach ($jumpItems as $key => $label): ?><a href="#sec-<?php echo $e($key); ?>"><?php echo $e($label); ?></a><?php endforeach; ?>
    </div>
</nav>
<?php endif; ?>

<?php if ($vis['introduction']):
    $strip = $list('intro_strip');
    $displayStrip = $strip;
    if (empty($displayStrip)) {
        $displayStrip = [
            ['icon' => 'fa-location-dot', 'text' => 'AV Info Tech Park, Saravanampatti'],
            ['icon' => 'fa-clock', 'text' => 'Flexible 24/7 Workspace Access'],
            ['icon' => 'fa-wifi', 'text' => 'High-Speed Wi-Fi & Modern Amenities'],
            ['icon' => 'fa-users', 'text' => 'Startups, Freelancers & Teams']
        ];
    }
    $introHeading = $val('intro_heading');
    if ($introHeading === '') {
        $introHeading = 'Why Choose Workiify ' . $e($s['title']) . ' in Coimbatore?';
    }
?>
<!-- Introduction -->
<section class="section-padding<?php echo $bg(); ?> sw-intro-section" id="sec-introduction">
    <div class="container max-w-1100">
        <div class="sw-intro-card" data-aos="fade-up">
            <div class="sw-intro-header">
                <span class="sw-intro-eyebrow">
                    <i class="fas fa-sparkles"></i> Overview &amp; Highlights
                </span>
                <h2 class="sw-intro-title"><?php echo $e($introHeading); ?></h2>
            </div>

            <?php if ($val('intro_text') !== ''): ?>
            <div class="sw-intro-body">
                <p class="sw-intro-lead"><?php echo $para($val('intro_text')); ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($displayStrip)): ?>
            <div class="sw-intro-highlights">
                <?php foreach ($displayStrip as $r):
                    $ic = $icon($r['icon'] ?? '');
                    if (!$ic) { $ic = 'fa-check-circle'; }
                    $tx = $e($r['text'] ?? '');
                    if ($tx === '') { continue; }
                ?>
                <div class="sw-intro-pill">
                    <div class="sw-intro-pill-icon"><i class="fas <?php echo $ic; ?>"></i></div>
                    <span class="sw-intro-pill-text"><?php echo $tx; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($val('intro_btn_text') !== ''): ?>
            <div class="sw-intro-actions">
                <a href="<?php echo $href($val('intro_btn_link', $enquiryLink)); ?>" class="btn btn-primary sw-intro-btn">
                    <span><?php echo $e($val('intro_btn_text')); ?></span>
                    <i class="fas fa-arrow-right"></i>
                </a>
                <div class="sw-intro-subnote">
                    <i class="fas fa-shield-halved"></i>
                    <span>Flexible Plans &bull; Instant Walk-in Tours &bull; Zero Setup Hassle</span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($vis['highlight-1']) { echo $highlight('hl1', 'highlight-1'); } ?>

<?php if ($vis['options']): $cards = $list('options'); $n = count($cards); ?>
<!-- Options -->
<section class="section-padding<?php echo $bg(); ?>" id="sec-options">
    <div class="container text-center max-w-1100">
        <?php echo $head('options'); ?>
        <div class="sw-listings<?php echo $n !== 3 ? ' sw-listings-auto' : ''; ?>">
            <?php foreach ($cards as $i => $c):
                $chips = array_filter(array_map('trim', explode(',', (string)($c['chips'] ?? ''))), 'strlen');
                $cImg = $img($c['image'] ?? ''); ?>
            <article class="sw-listing" data-aos="fade-up" data-aos-delay="<?php echo ($i % 3) * 100; ?>">
                <?php if ($cImg !== ''): ?>
                <div class="sw-listing-img">
                    <img src="<?php echo $cImg; ?>" alt="<?php echo $e($c['title'] ?? ''); ?>" loading="lazy">
                    <?php if (trim((string)($c['tag'] ?? '')) !== ''): ?><span class="sw-tag"><?php echo $e($c['tag']); ?></span><?php endif; ?>
                </div>
                <?php endif; ?>
                <div class="sw-listing-body">
                    <h3><?php echo $e($c['title'] ?? ''); ?></h3>
                    <?php if (trim((string)($c['text'] ?? '')) !== ''): ?><p><?php echo $para($c['text']); ?></p><?php endif; ?>
                    <?php if ($chips): ?><div class="sw-meta"><?php foreach ($chips as $chip): ?><span><?php echo $e($chip); ?></span><?php endforeach; ?></div><?php endif; ?>
                    <?php if (trim((string)($c['note'] ?? '')) !== '' || trim((string)($c['link_text'] ?? '')) !== ''): ?>
                    <div class="sw-listing-foot">
                        <span><?php echo $e($c['note'] ?? ''); ?></span>
                        <?php if (trim((string)($c['link_text'] ?? '')) !== ''): ?><a href="<?php echo $href($c['link_url'] ?? ''); ?>"><?php echo $e($c['link_text']); ?> <i class="fas fa-arrow-right"></i></a><?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($vis['location']): $pts = $list('location_points'); $locImg = $img($f['location_image'] ?? '') ?: $e(URLROOT . '/images/gallery-building-exterior.png'); $alt = !$alt; ?>
<!-- Location -->
<section class="section-padding" id="sec-location" style="padding: 0;">
    <div class="sw-location-cinematic" style="background-image: url('<?php echo $locImg; ?>');">
        <div class="sw-location-overlay"></div>
        <div class="sw-location-content container max-w-1100">
            <div class="sw-location-text" data-aos="fade-right">
                <span class="sw-location-eyebrow">LOCATION</span>
                <h2 class="sw-location-title"><?php echo $e($val('location_heading')); ?><?php if ($val('location_highlight') !== ''): ?> <br><span class="sw-text-gradient"><?php echo $e($val('location_highlight')); ?></span><?php endif; ?></h2>
                <?php if ($val('location_text') !== ''): ?><p style="color:#cbd5e1; margin-bottom:1.5rem;"><?php echo $para($val('location_text')); ?></p><?php endif; ?>
                <?php if ($val('location_address') !== ''): ?>
                <div class="sw-location-address"><i class="fas fa-map-marker-alt"></i><p><?php echo $para($val('location_address')); ?></p></div>
                <?php endif; ?>
                <?php if ($pts): ?>
                <div class="sw-location-ticker" style="height: 130px;">
                    <div class="sw-ticker-wrap">
                        <?php foreach ($pts as $i => $pt): ?>
                        <div class="sw-ticker-item<?php echo $i === 0 ? $activeCls : ''; ?>"><i class="fas <?php echo $icon($pt['icon'] ?? '') ?: 'fa-location-dot'; ?>"></i> <span><strong><?php echo $e($pt['title'] ?? ''); ?><?php echo trim((string)($pt['text'] ?? '')) !== '' ? ':' : ''; ?></strong> <?php echo $e($pt['text'] ?? ''); ?></span></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($val('location_btn_text') !== ''): ?><a href="<?php echo $href($val('location_btn_link', $enquiryLink)); ?>" class="btn btn-primary" style="margin-top: 1.5rem;"><?php echo $e($val('location_btn_text')); ?></a><?php endif; ?>
            </div>
            <div class="sw-location-visual"></div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($vis['feature-tabs']): $tabs = $list('tabs'); ?>
<!-- Feature tabs -->
<section class="section-padding<?php echo $bg(); ?>" id="sec-feature-tabs">
    <div class="container text-center max-w-1100">
        <?php echo $head('tabs'); ?>
        <div class="sw-amenities-tabs" data-aos="fade-up">
            <div class="sw-tabs-sidebar">
                <?php foreach ($tabs as $i => $t): ?>
                <button type="button" class="sw-tab-btn<?php echo $i === 0 ? $activeCls : ''; ?>" data-target="svc-tab-<?php echo $i; ?>">
                    <div class="sw-tab-icon"><i class="fas <?php echo $icon($t['icon'] ?? '') ?: 'fa-circle'; ?>"></i></div>
                    <div class="sw-tab-text"><?php echo $e($t['label'] ?? ($t['title'] ?? '')); ?></div>
                </button>
                <?php endforeach; ?>
            </div>
            <div class="sw-tabs-content">
                <?php foreach ($tabs as $i => $t): ?>
                <div class="sw-tab-pane<?php echo $i === 0 ? $activeCls : ''; ?>" id="svc-tab-<?php echo $i; ?>">
                    <?php if ($img($t['image'] ?? '') !== ''): ?><img src="<?php echo $img($t['image']); ?>" alt="<?php echo $e($t['title'] ?? ''); ?>" loading="lazy"><?php endif; ?>
                    <div class="sw-pane-info">
                        <h3><?php echo $e($t['title'] ?? ''); ?></h3>
                        <p><?php echo $para($t['text'] ?? ''); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($vis['who-its-for']): $cards = $list('audience'); ?>
<!-- Who it's for -->
<section class="section-padding<?php echo $bg(); ?>" id="sec-who-its-for">
    <div class="container text-center max-w-1100">
        <?php echo $head('audience'); ?>
        <div class="sw-audience-accordion" data-aos="fade-up">
            <?php foreach ($cards as $i => $c): ?>
            <div class="sw-aud-card<?php echo $i === 0 ? $activeCls : ''; ?>">
                <?php if ($img($c['image'] ?? '') !== ''): ?><img src="<?php echo $img($c['image']); ?>" alt="<?php echo $e($c['title'] ?? ''); ?>" loading="lazy"><?php endif; ?>
                <div class="sw-aud-card-content">
                    <span class="sw-aud-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
                    <div class="sw-aud-text">
                        <h3><?php echo $e($c['title'] ?? ''); ?></h3>
                        <div class="sw-aud-text-wrapper"><div class="sw-aud-text-inner"><p><?php echo $para($c['text'] ?? ''); ?></p></div></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($vis['key-points']): $pts = $list('keypoints'); ?>
<!-- Key points -->
<section class="section-padding<?php echo $bg(); ?>" id="sec-key-points">
    <div class="container text-center max-w-1100">
        <?php echo $head('keypoints'); ?>
        <div class="sw-why" data-aos="fade-up" data-cols="<?php echo min(3, max(1, count($pts))); ?>">
            <?php foreach ($pts as $i => $pt): ?>
            <div>
                <div class="num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></div>
                <h3><?php echo $e($pt['title'] ?? ''); ?></h3>
                <p><?php echo $para($pt['text'] ?? ''); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($vis['steps']): $steps = $list('steps'); ?>
<!-- Steps -->
<section class="section-padding<?php echo $bg(); ?>" id="sec-steps">
    <div class="container text-center max-w-1100">
        <?php echo $head('steps'); ?>
        <div class="sw-steps" data-aos="fade-up" data-cols="<?php echo min(3, max(1, count($steps))); ?>">
            <?php foreach ($steps as $i => $st): ?>
            <div class="sw-step">
                <div class="sw-step-dot"><?php echo $i + 1; ?></div>
                <div>
                    <h3><?php echo $e($st['title'] ?? ''); ?></h3>
                    <p><?php echo $para($st['text'] ?? ''); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($vis['highlight-2']) { echo $highlight('hl2', 'highlight-2'); } ?>

<?php if ($vis['faq']): $qs = $list('faq'); ?>
<!-- FAQ -->
<section class="faq-section section-padding<?php echo $bg(); ?>" id="sec-faq">
    <div class="container">
        <div class="faq-layout">
            <div class="faq-intro" data-aos="fade-right">
                <span class="category-eyebrow">FAQ</span>
                <?php if ($val('faq_heading') !== ''): ?><h2 class="section-title"><?php echo $e($val('faq_heading')); ?></h2><?php endif; ?>
                <?php if ($val('faq_text') !== ''): ?><p><?php echo $para($val('faq_text')); ?></p><?php endif; ?>
                <?php if ($val('faq_btn_text') !== ''): ?><a href="<?php echo $href($val('faq_btn_link', $enquiryLink)); ?>" class="btn btn-primary"<?php echo preg_match('#^https?://#i', $val('faq_btn_link')) ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo $e($val('faq_btn_text')); ?></a><?php endif; ?>
            </div>
            <div class="faq-accordion" data-aos="fade-left">
                <?php foreach ($qs as $q): ?>
                <div class="faq-item">
                    <button class="faq-question"><?php echo $e($q['question'] ?? ''); ?><i class="fas fa-chevron-down"></i></button>
                    <div class="faq-answer"><p><?php echo $para($q['answer'] ?? ''); ?></p></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($vis['closing-banner']): $ctaImg = $img($f['cta_image'] ?? ''); ?>
<!-- Closing banner -->
<section class="<?php echo $ctaImg !== '' ? 'sw-cta' : 'section-padding bg-alt text-center'; ?>" id="sec-closing-banner"<?php if ($ctaImg !== ''): ?> style="background-image: url('<?php echo $ctaImg; ?>');"<?php endif; ?>>
    <div class="container max-w-1100">
        <h2 class="section-title"><?php echo $e($val('cta_heading')); ?></h2>
        <?php if ($val('cta_text') !== ''): ?><p class="<?php echo $ctaImg === '' ? 'section-subtitle' : ''; ?>"><?php echo $para($val('cta_text')); ?></p><?php endif; ?>
        <?php if ($val('cta_btn_text') !== ''): ?><a href="<?php echo $href($val('cta_btn_link', $enquiryLink)); ?>" class="btn btn-primary mt-4"><?php echo $e($val('cta_btn_text')); ?></a><?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($vis['explore-more']): ?>
<!-- Explore other services -->
<section class="services-showcase-section section-padding<?php echo $bg(); ?>" id="sec-explore-more">
    <div class="container text-center">
        <h2 class="section-title"><?php echo $e($val('related_heading', 'Explore Other Services')); ?></h2>
        <div class="services-grid">
            <?php foreach ($data['related'] as $r): $rImg = $img($r['card_image'] ?? ''); ?>
            <a href="<?php echo URLROOT; ?>/services/<?php echo $e($r['slug']); ?>" class="service-card">
                <?php if ($rImg !== ''): ?>
                <div class="service-img-wrap">
                    <img src="<?php echo $rImg; ?>" alt="<?php echo $e($r['title']); ?>" class="service-img" loading="lazy" decoding="async" style="object-fit: cover; width: 100%; height: 200px;">
                </div>
                <?php endif; ?>
                <div class="service-content"><h3><?php echo $e($r['title']); ?></h3></div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php if ($val('related_btn_text') !== ''): ?><a href="<?php echo URLROOT; ?>/services" class="btn btn-primary mt-4"><?php echo $e($val('related_btn_text')); ?></a><?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>

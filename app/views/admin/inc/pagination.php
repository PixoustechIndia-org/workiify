<?php
/**
 * Pager under a list. Expects $data['pg'] = ['page', 'pages', 'total', 'perPage'];
 * every other query parameter (search, filters, sort) is kept on each link.
 */
$pg = $data['pg'];
$from = $pg['total'] ? ($pg['page'] - 1) * $pg['perPage'] + 1 : 0;
$to = min($pg['total'], $pg['page'] * $pg['perPage']);
?>
<div class="admin-pager">
    <span class="admin-pager-info">Showing <strong><?php echo $from; ?>–<?php echo $to; ?></strong> of <strong><?php echo $pg['total']; ?></strong></span>
    <?php if ($pg['pages'] > 1): ?>
        <nav class="admin-pager-links" aria-label="Pagination">
            <?php if ($pg['page'] > 1): ?>
                <a href="<?php echo admin_query_url(['page' => $pg['page'] - 1]); ?>" class="admin-pager-btn" aria-label="Previous page"><i class="fas fa-chevron-left"></i></a>
            <?php else: ?>
                <span class="admin-pager-btn is-disabled"><i class="fas fa-chevron-left"></i></span>
            <?php endif; ?>
            <?php
            $shown = [];
            foreach ([1, $pg['page'] - 1, $pg['page'], $pg['page'] + 1, $pg['pages']] as $n) {
                if ($n >= 1 && $n <= $pg['pages']) { $shown[$n] = true; }
            }
            ksort($shown);
            $prev = 0;
            foreach (array_keys($shown) as $n):
                if ($n - $prev > 1): ?><span class="admin-pager-gap">…</span><?php endif;
                $prev = $n; ?>
                <?php if ($n === $pg['page']): ?>
                    <span class="admin-pager-btn is-current" aria-current="page"><?php echo $n; ?></span>
                <?php else: ?>
                    <a href="<?php echo admin_query_url(['page' => $n]); ?>" class="admin-pager-btn"><?php echo $n; ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if ($pg['page'] < $pg['pages']): ?>
                <a href="<?php echo admin_query_url(['page' => $pg['page'] + 1]); ?>" class="admin-pager-btn" aria-label="Next page"><i class="fas fa-chevron-right"></i></a>
            <?php else: ?>
                <span class="admin-pager-btn is-disabled"><i class="fas fa-chevron-right"></i></span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</div>

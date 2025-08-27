<h3 class="page-title"><?= __('home_title') ?></h3>
<div class="card-grid">
    <?php foreach ($professions as $profession): ?>
        <?php if ($profession->questions_count > 0): ?>
            <a href="profession.php?id=<?= $profession->id ?>" class="item-card">
                <h4><?= htmlspecialchars($profession->name) ?></h4>
            </a>
        <?php else: ?>
            <div class="item-card item-card-no-questions toast-trigger" data-profession-name="<?= htmlspecialchars($profession->name) ?>">
                <h4><?= htmlspecialchars($profession->name) ?></h4>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
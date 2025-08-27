<h3 class="page-title"><?=__('requirement_title')?></h3>
<div class="card-grid">
    <?php foreach ($professions as $profession): ?>
        <a href="requirements.php?id=<?= $profession->id ?>" class="item-card">
            <h4><?= htmlspecialchars($profession->name) ?></h4>
        </a>
    <?php endforeach; ?>
</div>
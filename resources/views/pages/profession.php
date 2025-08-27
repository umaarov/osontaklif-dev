<h3 class="page-title"><?= __('profession_title') ?> - <?= htmlspecialchars($profession->name) ?></h3>
<p class="page-subtitle"><?= __('profession_total_questions') ?> <?= $total ?></p>
<div class="content-container">
    <div class="main-content">
        <form method="GET" class="search-form" action="profession.php">
            <input type="hidden" name="name" value="<?= htmlspecialchars($profession->slug) ?>">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                   placeholder="<?= __('profession_search_placeholder') ?>" class="search-input">
            <button type="submit" class="btn-outline"
                    style="margin-left: 6px;"><?= __('profession_search_btn') ?></button>
        </form>
        <table class="data-table">
            <thead>
            <tr>
                <th>#</th>
                <th><?= __('profession_table_title_1') ?></th>
                <th><?= __('profession_table_title_2') ?></th>
                <th><?= __('profession_table_title_3') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($questions as $i => $q): ?>
                <tr>
                    <td><?= ($page - 1) * 100 + $i + 1 ?></td>
                    <td>
                        <a href="question.php?id=<?= $q['id'] ?>&pid=<?= $profession->id ?>" class="table-link">
                            <?= htmlspecialchars($q['question']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($q['chance']) ?>%</td>
                    <td><?= htmlspecialchars($q['tag']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pagination-container">
            <?php if ($totalPages > 1): ?>
                <div class="simple-pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="profession.php?name=<?= urlencode($profession->slug) ?>&page=<?= $i ?>&search=<?= urlencode($search) ?>"
                           class="page-button <?= $page == $i ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>

        <div style="margin-top: 20px;">
            <a href="home.php" class="btn-outline"><?= __('back_to_home') ?></a>
        </div>
    </div>
    <?php include BASE_PATH . '/resources/views/partials/ad.php'; ?>
</div>
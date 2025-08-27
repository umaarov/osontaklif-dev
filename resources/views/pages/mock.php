<h3 class="page-title"><?= __('mock_title') ?></h3>
<div class="content-container">
    <div class="main-content">
        <form method="GET" action="mock.php" class="filter-container">
            <select name="position" class="filter-select">
                <option value="">---------</option>
                <?php foreach ($positions as $position): ?>
                    <option value="<?= $position->id ?>" <?= ($_GET['position'] ?? '') == $position->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($position->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="radio-group">
                <label><input type="radio" name="grade"
                              value="Junior" <?= ($_GET['grade'] ?? '') == 'Junior' ? 'checked' : '' ?>> Junior</label>
                <label><input type="radio" name="grade"
                              value="Middle" <?= ($_GET['grade'] ?? '') == 'Middle' ? 'checked' : '' ?>> Middle</label>
                <label><input type="radio" name="grade"
                              value="Senior" <?= ($_GET['grade'] ?? '') == 'Senior' ? 'checked' : '' ?>> Senior</label>
            </div>
            <button type="submit" class="btn-primary"><?= __('mock_select_btn') ?></button>
        </form>

        <table class="data-table">
            <thead>
            <tr>
                <th>#</th>
                <th><?= __('mock_table_title_1') ?></th>
                <th><?= __('mock_table_title_2') ?></th>
                <th><?= __('mock_table_title_3') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($interviews as $i => $interview): ?>
                <tr>
                    <td><?= ($page - 1) * 100 + $i + 1 ?></td>
                    <td>
                        <a href="<?= htmlspecialchars($interview['link']) ?>" class="table-link"
                           target="_blank"><?= htmlspecialchars($interview['title']) ?></a>
                    </td>
                    <td><?= htmlspecialchars($interview['profession_name']) ?></td>
                    <td><?= htmlspecialchars($interview['grade']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pagination-container">
            <?php if ($totalPages > 1): ?>
                <div class="simple-pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++):
                        $queryParams = http_build_query(array_merge($_GET, ['page' => $i]));
                        ?>
                        <a href="?<?= $queryParams ?>" class="page-button <?= $page == $i ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php include BASE_PATH . '/resources/views/partials/ad.php'; ?>
</div>
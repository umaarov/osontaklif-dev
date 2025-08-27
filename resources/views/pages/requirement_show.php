<h3 class="page-title"><?= __('requirement_show_title') ?> <?= htmlspecialchars($profession->name) ?></h3>
<p class="page-subtitle"><?= __('requirement_show_desc_1') ?> <?= htmlspecialchars($profession->name) ?> <?= __('requirement_show_desc_2') ?></p>

<?php if ($lastUpdated): ?>
    <p class="page-subtitle">
        <?= __('requirement_show_desc_3') ?> <?= date('F d, Y', strtotime($lastUpdated)) ?> <?= __('requirement_show_desc_4') ?>
        <a href="https://hh.uz/search/vacancy?text=<?= urlencode($profession->name) ?>&area=97"
           target="_blank"><?= __('requirement_show_desc_5') ?></a> <?= __('requirement_show_desc_6') ?>
        <?= __('requirement_show_desc_7') ?> <?= $totalProcessed ?? 0 ?>.
        <?= __('requirement_show_desc_8') ?> <?= $totalSkills ?? 0 ?>.
    </p>
<?php endif; ?>

<div class="content-container">
    <div class="main-content">
        <form method="GET" class="search-form" action="requirements.php">
            <input type="hidden" name="name" value="<?= htmlspecialchars($profession->slug) ?>">
            <input type="text" name="search" value="<?= htmlspecialchars($validatedSearch ?? '') ?>"
                   placeholder="<?= __('requirement_show_search_placeholder') ?>" class="search-input">
            <button type="submit" class="btn-outline"
                    style="margin-left: 6px;"><?= __('requirement_show_search_btn') ?></button>
        </form>

        <table class="data-table" id="skills-table">
            <thead>
            <tr>
                <th>#</th>
                <th><?= __('requirement_show_table_title_1') ?></th>
                <th><?= __('requirement_show_table_title_2') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($skills as $index => $skill): ?>
                <tr>
                    <td><?= ($page - 1) * $limit + $index + 1 ?></td>
                    <td><?= htmlspecialchars($skill['skill_name']) ?></td>
                    <td><?= htmlspecialchars($skill['count']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($hasMoreSkills): ?>
            <div class="load-more-container">
                <a href="requirements.php?name=<?= urlencode($profession->slug) ?>&page=<?= ($page + 1) ?>"
                   class="load-more-text" id="load-more-btn">
                    <?= __('load_more') ?>
                </a>
            </div>
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <a href="requirements.php" class="btn-outline"><?= __('back_to_requirements') ?></a>
        </div>
    </div>
    <?php include BASE_PATH . '/resources/views/partials/ad.php'; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const loadMoreBtn = document.getElementById('load-more-btn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newRows = doc.querySelectorAll('#skills-table tbody tr');
                        const currentTable = document.querySelector('#skills-table tbody');
                        newRows.forEach(row => currentTable.appendChild(row));
                        const newLoadMoreBtn = doc.querySelector('#load-more-btn');
                        if (newLoadMoreBtn) {
                            loadMoreBtn.setAttribute('href', newLoadMoreBtn.getAttribute('href'));
                        } else {
                            loadMoreBtn.parentElement.remove();
                        }
                    }).catch(error => console.error('Error:', error));
            });
        }
    });
</script>

<h3 class="page-title"><?= htmlspecialchars($question->question) ?></h3>

<div class="content-container">
    <div class="main-content">
        <div class="content-box main-content">
            <?php echo $question->content; ?>

            <?php if ($question->created_at): ?>
                <p class="timestamp">
                    <?= date('Y-m-d H:i', strtotime($question->created_at)) ?>
                </p>
            <?php endif; ?>
        </div>
        <div style="margin-top: 20px;">
            <a href="<?= APP_URL . '/professions/' . $profession->slug ?>" class="btn-outline">
                <?= __('back_to_questions') ?>
            </a>
        </div>
    </div>
    <?php include BASE_PATH . '/resources/views/partials/ad.php'; ?>
</div>

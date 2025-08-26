<h3 class="page-title"><?= __('about_us_title') ?></h3>
<div class="content-container">
    <div class="main-content">
        <div class="content-box main-content">
            <p><strong><?= __('app_name') ?></strong> - <?= __('about_us_desc') ?> </p>
        </div>
        <div style="margin-top: 20px;">
            <a href="<?= APP_URL ?>" class="btn-outline">
                <?= __('back_to_home') ?>
            </a>
        </div>
    </div>
    <?php include BASE_PATH . '/resources/views/partials/ad.php'; ?>
</div>
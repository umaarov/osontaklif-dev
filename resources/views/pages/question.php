<?php
$questionData = [
    "@context" => "https://schema.org",
    "@type" => "QAPage",
    "mainEntity" => [
        "@type" => "Question",
        "name" => $question->question,
        "text" => strip_tags($question->content),
        "answerCount" => 1,
        "acceptedAnswer" => [
            "@type" => "Answer",
            "text" => strip_tags($question->content),
            "dateCreated" => date('c', strtotime($question->created_at)),
            "upvoteCount" => $question->chance
        ]
    ]
];
?>
<script type="application/ld+json">
<?= json_encode($questionData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>

</script>
<h3 class="page-title"><?= htmlspecialchars($question->question) ?></h3>
<div class="content-container">
    <div class="main-content">
        <div class="content-box main-content">
            <?= purify($question->content) ?>
            <?php if ($question->created_at): ?>
                <p class="timestamp"><?= date('Y-m-d H:i', strtotime($question->created_at)) ?></p>
            <?php endif; ?>
        </div>
        <div style="margin-top: 20px;">
            <a href="profession.php?name=<?= urlencode($profession->slug) ?>" class="btn-outline">
                <?= __('back_to_questions') ?>
            </a>
        </div>
    </div>
    <?php include BASE_PATH . '/resources/views/partials/ad.php'; ?>
</div>
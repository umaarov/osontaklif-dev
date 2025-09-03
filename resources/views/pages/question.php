<?php
$parsedown = new Parsedown();

$suggestedAnswers = [];
foreach ($answers as $answer) {
    $suggestedAnswers[] = [
        '@type' => 'Answer',
        'text' => strip_tags($parsedown->text($answer->content)),
        'dateCreated' => date('c', strtotime($answer->created_at)),
        'author' => [
            '@type' => 'Person',
            'name' => htmlspecialchars($answer->first_name)
        ]
    ];
}

$questionData = [
    "@context" => "https://schema.org",
    "@type" => "QAPage",
    "mainEntity" => [
        "@type" => "Question",
        "name" => $question->question,
        "text" => strip_tags($question->question),
        "answerCount" => count($answers) + 1,
        "acceptedAnswer" => [
            "@type" => "Answer",
            "text" => strip_tags($question->content),
            "dateCreated" => date('c', strtotime($question->created_at)),
        ],
        "suggestedAnswer" => $suggestedAnswers
    ]
];
?>
<script type="application/ld+json">
    <?= json_encode($questionData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>


</script>

<h3 class="page-title"><?= htmlspecialchars($question->question) ?></h3>
<div class="content-container">
    <div class="main-content">
        <div class="content-box">
            <?= purify($question->content) ?>
            <?php if ($question->created_at): ?>
                <p class="timestamp">Published on: <?= date('Y-m-d H:i', strtotime($question->created_at)) ?></p>
            <?php endif; ?>
        </div>

        <hr class="my-4">

        <h4 class="mb-3">Community Answers (<?= count($answers) ?>)</h4>
        <div class="answer-form-container mt-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Post Your Answer</h5></div>
                    <div class="card-body">
                        <form action="answer_post.php" method="POST">
                            <input type="hidden" name="question_id" value="<?= $question->id ?>">
                            <input type="hidden" name="profession_id" value="<?= $profession->id ?>">

                            <div class="mb-3">
                                <textarea name="content" class="form-control" rows="8" placeholder="Share your answer. Markdown is supported!" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Submit Answer</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    Please <a href="login.php" class="alert-link">login</a> or <a href="register.php" class="alert-link">register</a> to post an answer.
                </div>
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
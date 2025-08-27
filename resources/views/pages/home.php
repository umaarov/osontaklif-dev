<?php
$itemListElements = [];
$position = 1;
foreach ($professions as $profession) {
    if ($profession->questions_count > 0) {
        $itemListElements[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => htmlspecialchars($profession->name),
            'url' => APP_URL . '/profession.php?name=' . urlencode($profession->slug)
        ];
    }
}

$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'OsonTaklif',
    'url' => APP_URL . '/',
    'description' => 'O\'zbekistondagi eng talabgir IT kasblari bo\'yicha suhbat savollari, kompaniya talablari va amaliy mock intervyular bazasi.',
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => [
            '@type' => 'EntryPoint',
            'urlTemplate' => APP_URL . '/requirements.php?search={search_term_string}'
        ],
        'query-input' => 'required name=search_term_string'
    ],
    'mainEntity' => [
        '@type' => 'ItemList',
        'name' => 'IT Professions',
        'itemListElement' => $itemListElements
    ]
];
?>

<script type="application/ld+json">
<?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>


</script>


<h3 class="page-title"><?= __('home_title') ?></h3>
<div class="card-grid">
    <?php foreach ($professions as $profession): ?>
        <?php if ($profession->questions_count > 0): ?>
            <a href="profession.php?name=<?= urlencode($profession->slug) ?>" class="item-card">
                <h4><?= htmlspecialchars($profession->name) ?></h4>
            </a>
        <?php else: ?>
            <div class="item-card item-card-no-questions toast-trigger"
                 data-profession-name="<?= htmlspecialchars($profession->name) ?>">
                <h4><?= htmlspecialchars($profession->name) ?></h4>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
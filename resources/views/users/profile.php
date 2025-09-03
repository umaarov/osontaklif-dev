<?php
// Helper function to format dates
function format_date_range($startDate, $endDate) {
    $start = date('M Y', strtotime($startDate));
    $end = $endDate ? date('M Y', strtotime($endDate)) : 'Present';
    return "{$start} - {$end}";
}
?>

<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <img src="<?= htmlspecialchars($profile->avatar_url ?? 'https://placehold.co/150') ?>" alt="avatar"
                     class="rounded-circle img-fluid" style="width: 150px;">
                <h5 class="my-3"><?= htmlspecialchars($user->first_name . ' ' . ($profile->surname ?? '')) ?></h5>
                <p class="text-muted mb-1"><?= htmlspecialchars($profile->headline ?? 'No headline') ?></p>
                <p class="text-muted mb-4"><?= htmlspecialchars($profile->position ?? 'No position') ?> at <?= htmlspecialchars($profile->company ?? 'No company') ?></p>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h4>About</h4></div>
            <div class="card-body">
                <p><?= nl2br(htmlspecialchars($profile->bio ?? 'This user has not written a bio yet.')) ?></p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h4>Work Experience</h4></div>
            <div class="card-body">
                <?php if (empty($experiences)): ?>
                    <p>No work experience added yet.</p>
                <?php else: ?>
                    <?php foreach ($experiences as $exp): ?>
                        <div class="mb-3">
                            <h5 class="mb-0"><?= htmlspecialchars($exp->job_title) ?></h5>
                            <div class="text-muted"><?= htmlspecialchars($exp->company_name) ?></div>
                            <small class="text-muted"><?= format_date_range($exp->start_date, $exp->end_date) ?></small>
                            <p><?= nl2br(htmlspecialchars($exp->description ?? '')) ?></p>
                        </div>
                        <?php if (!$loop->last): ?><hr><?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h4>Education</h4></div>
            <div class="card-body">
                <?php if (empty($educations)): ?>
                    <p>No education history added yet.</p>
                <?php else: ?>
                    <?php foreach ($educations as $edu): ?>
                        <div class="mb-3">
                            <h5 class="mb-0"><?= htmlspecialchars($edu->institution_name) ?></h5>
                            <div class="text-muted"><?= htmlspecialchars($edu->degree) ?>, <?= htmlspecialchars($edu->field_of_study) ?></div>
                            <small class="text-muted"><?= format_date_range($edu->start_date, $edu->end_date) ?></small>
                        </div>
                        <?php if (!$loop->last): ?><hr><?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h4>Recent Activity</h4></div>
            <div class="list-group list-group-flush">
                <?php if (empty($activities)): ?>
                    <div class="list-group-item">No recent activity.</div>
                <?php else: ?>
                    <?php foreach ($activities as $activity): ?>
                        <a href="question.php?id=<?= $activity->question_id ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Answered a question:</h6>
                                <small><?= date('M d, Y', strtotime($activity->created_at)) ?></small>
                            </div>
                            <p class="mb-1">"<?= htmlspecialchars(substr($activity->question, 0, 100)) ?>..."</p>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
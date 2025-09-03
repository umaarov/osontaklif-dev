<?php
function format_date_range($startDate, $endDate)
{
    $start = date('M Y', strtotime($startDate));
    $end = $endDate ? date('M Y', strtotime($endDate)) : 'Present';
    return "{$start} - {$end}";
}

?>

<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <img src="<?= htmlspecialchars($profile ? $profile->avatar_url : 'https://placehold.co/150') ?>"
                     alt="avatar"
                     class="rounded-circle img-fluid" style="width: 150px;">
                <h5 class="my-3"><?= htmlspecialchars($user->first_name . ' ' . ($profile ? $profile->surname : '')) ?></h5>
                <p class="text-muted mb-1"><?= htmlspecialchars($profile ? $profile->headline : 'No headline') ?></p>
                <p class="text-muted mb-4"><?= htmlspecialchars($profile ? $profile->position : 'No position') ?>
                    at <?= htmlspecialchars($profile ? $profile->company : 'No company') ?></p>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user->id): ?>
                    <div class="d-flex justify-content-center mb-2">
                        <a href="profile_edit.php" class="btn btn-primary">Edit Profile</a>
                        <a href="profile_download.php?user=<?= $user->username ?>"
                           class="btn btn-outline-secondary ms-2">Download PDF</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-8">
    <div class="card mb-4">
        <div class="card-header"><h4>About</h4></div>
        <div class="card-body">
            <p><?= nl2br(htmlspecialchars($profile ? $profile->bio : 'This user has not written a bio yet.')) ?></p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h4>Work Experience</h4></div>
        <div class="card-body">
            <?php if (empty($experiences)): ?>
                <p>No work experience added yet.</p>
            <?php else:
                $experienceCount = count($experiences);
                foreach ($experiences as $index => $exp): ?>
                    <div class="mb-3">
                        <h5 class="mb-0"><?= htmlspecialchars($exp->job_title) ?></h5>
                        <div class="text-muted"><?= htmlspecialchars($exp->company_name) ?></div>
                        <small class="text-muted"><?= format_date_range($exp->start_date, $exp->end_date) ?></small>
                        <p><?= nl2br(htmlspecialchars($exp->description ?? '')) ?></p>
                    </div>
                    <?php if ($index < $experienceCount - 1): ?>
                        <hr><?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h4>Education</h4></div>
        <div class="card-body">
            <?php if (empty($educations)): ?>
                <p>No education history added yet.</p>
            <?php else:
                $educationCount = count($educations);
                foreach ($educations as $index => $edu): ?>
                    <div class="mb-3">
                        <h5 class="mb-0"><?= htmlspecialchars($edu->institution_name) ?></h5>
                        <div class="text-muted"><?= htmlspecialchars($edu->degree) ?>
                            , <?= htmlspecialchars($edu->field_of_study) ?></div>
                        <small class="text-muted"><?= format_date_range($edu->start_date, $edu->end_date) ?></small>
                    </div>
                    <?php if ($index < $educationCount - 1): ?>
                        <hr><?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>
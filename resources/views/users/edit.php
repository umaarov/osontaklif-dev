<h3 class="page-title">Edit Your Profile</h3>

<div class="card">
    <div class="card-body">
        <form action="profile_update.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="<?= htmlspecialchars($profile->avatar_url ?? '/uploads/avatars/default.png') ?>"
                         class="rounded-circle img-fluid mb-3" style="width: 150px;">
                    <label for="avatar" class="form-label">Change Profile Picture</label>
                    <input class="form-control" type="file" id="avatar" name="avatar">
                </div>
                <div class="col-md-8">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                           value="<?= htmlspecialchars($user->first_name ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="surname" class="form-label">Surname</label>
                    <input type="text" class="form-control" id="surname" name="surname"
                           value="<?= htmlspecialchars($profile->surname ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="headline" class="form-label">Headline</label>
                <input type="text" class="form-control" id="headline" name="headline"
                       placeholder="e.g., Senior Backend Developer at Tech Corp"
                       value="<?= htmlspecialchars($profile->headline ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea class="form-control" id="bio" name="bio"
                          rows="5"><?= htmlspecialchars($profile->bio ?? '') ?></textarea>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Work Experience</h4>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addExperienceModal">
                        Add Experience
                    </button>
                </div>
                <div class="card-body">
                </div>
            </div>

            <div class="modal fade" id="addExperienceModal" tabindex="-1" aria-labelledby="addExperienceModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="addExperienceModalLabel">Add New Experience</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="experience_add.php" method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="job_title" class="form-label">Job Title</label>
                                    <input type="text" class="form-control" name="job_title" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Experience</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Save Changes</button>
                <a href="profile.php?user=<?= $user->username ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
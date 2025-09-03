<h3 class="page-title">Edit Your Profile</h3>

<div class="card">
    <div class="card-body">
        <form action="profile_update.php" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user->first_name ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="surname" class="form-label">Surname</label>
                    <input type="text" class="form-control" id="surname" name="surname" value="<?= htmlspecialchars($profile->surname ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="headline" class="form-label">Headline</label>
                <input type="text" class="form-control" id="headline" name="headline" placeholder="e.g., Senior Backend Developer at Tech Corp" value="<?= htmlspecialchars($profile->headline ?? '') ?>">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="position" class="form-label">Position</label>
                    <input type="text" class="form-control" id="position" name="position" value="<?= htmlspecialchars($profile->position ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="company" class="form-label">Company</label>
                    <input type="text" class="form-control" id="company" name="company" value="<?= htmlspecialchars($profile->company ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="5"><?= htmlspecialchars($profile->bio ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-success">Save Changes</button>
            <a href="profile.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
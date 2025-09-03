<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!--    <title>--><?php //= APP_NAME ?><!--</title>-->
    <title><?= htmlspecialchars($metaTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/app.css">
</head>
<body class="d-flex flex-column min-vh-100">
<header class="header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark"
         style="position: fixed; top: 0; width: 100%; z-index: 100;">
        <div class="container">
            <a class="navbar-brand" href="home.php"><?= APP_NAME ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/mock.php">Mock</a></li>
                    <li class="nav-item"><a class="nav-link" href="/requirements.php">Requirements</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="navbar-text me-3">
                            Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!
                        </span>
                        <a href="/profile.php" class="btn btn-outline-light btn-sm me-2">Profile</a>
                        <a href="/logout.php" class="btn btn-secondary btn-sm">Logout</a>
                    <?php else: ?>
                        <a href="/login.php" class="btn btn-outline-light btn-sm me-2">Login</a>
                        <a href="/register.php" class="btn btn-primary btn-sm">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="content-wrapper">

    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php echo $content; ?>
    </div>
</div>


<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
    <div id="infoToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
        </div>
    </div>
</div>

<footer class="footer text-center mt-auto">
    <div class="container d-flex flex-column align-items-center py-3 mb-4">
        <p class="mb-2 text-secondary fw-semibold"><?= __('osontaklif_by_umarov_ismoiljon') ?></p>
        <div class="d-flex gap-3">
            <a href="https://telegram.me/dribbblxr" target="_blank" class="text-dark">
                <i class="fab fa-telegram fa-lg"></i>
            </a>
            <a href="https://github.com/umaarov" target="_blank" class="text-dark">
                <i class="fab fa-github fa-lg"></i>
            </a>
            <a href="https://linkedin.com/in/umaarov" target="_blank" class="text-dark">
                <i class="fab fa-linkedin fa-lg"></i>
            </a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>

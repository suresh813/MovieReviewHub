<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieReviewHub — Welcome</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="text-white" style="background-color: #001f5b;">

<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center text-center px-3">
    <div class="mb-4">
        <i class="bi bi-camera-reels-fill text-warning" style="font-size:4rem;"></i>
    </div>
    <h1 class="display-4 fw-bold mb-3">MovieReviewHub</h1>
    <p class="lead text-secondary mb-5">Discover films. Share honest reviews. Join the community.</p>

    <div class="d-flex gap-3 flex-wrap justify-content-center">
        <a href="<?= base_url('login') ?>" class="btn btn-warning btn-lg px-5 fw-semibold">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login
        </a>
        <a href="<?= base_url('register') ?>" class="btn btn-outline-light btn-lg px-5">
            <i class="bi bi-person-plus me-2"></i>Register
        </a>
        <a href="<?= base_url('movies') ?>" class="btn btn-outline-secondary btn-lg px-5">
            <i class="bi bi-film me-2"></i>Browse Movies
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

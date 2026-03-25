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
<body class="text-white" style="background: linear-gradient(135deg, #0d0d2b 0%, #001f5b 50%, #0a2a4a 100%); min-height:100vh;">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background:rgba(0,0,0,0.3); backdrop-filter:blur(10px);">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= base_url('/') ?>">
            <i class="bi bi-camera-reels-fill text-warning me-2"></i>MovieReviewHub
        </a>
        <div class="ms-auto d-flex gap-2">
            <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-sm">Login</a>
            <a href="<?= base_url('register') ?>" class="btn btn-warning btn-sm fw-semibold">Sign Up</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center text-center px-3" style="margin-top:-56px;">
    <div class="mb-4" style="animation: fadeInDown 0.8s ease;">
        <i class="bi bi-camera-reels-fill text-warning" style="font-size:5rem; filter: drop-shadow(0 0 20px rgba(255,193,7,0.5));"></i>
    </div>
    <h1 class="display-3 fw-bold mb-3" style="animation: fadeInDown 0.9s ease; text-shadow: 0 2px 20px rgba(0,0,0,0.5);">MovieReviewHub</h1>
    <p class="lead mb-2" style="color:rgba(255,255,255,0.7); animation: fadeInUp 1s ease;">Discover films. Share honest reviews. Join the community.</p>
    <p class="mb-5 small" style="color:rgba(255,255,255,0.4);">Thousands of movies. Real reviews from real people.</p>

    <div class="d-flex gap-3 flex-wrap justify-content-center mb-5" style="animation: fadeInUp 1.1s ease;">
        <a href="<?= base_url('login') ?>" class="btn btn-warning btn-lg px-5 fw-semibold shadow">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login
        </a>
        <a href="<?= base_url('register') ?>" class="btn btn-outline-light btn-lg px-5">
            <i class="bi bi-person-plus me-2"></i>Register
        </a>
        <a href="<?= base_url('movies') ?>" class="btn btn-outline-secondary btn-lg px-5">
            <i class="bi bi-film me-2"></i>Browse Movies
        </a>
    </div>

    <!-- Feature highlights -->
    <div class="row g-3 justify-content-center" style="max-width:700px; animation: fadeInUp 1.2s ease;">
        <div class="col-4">
            <div class="p-3 rounded-3" style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.1);">
                <i class="bi bi-star-fill text-warning fs-4 d-block mb-2"></i>
                <div class="small fw-semibold">Rate Movies</div>
                <div class="small" style="color:rgba(255,255,255,0.5);">1–5 star ratings</div>
            </div>
        </div>
        <div class="col-4">
            <div class="p-3 rounded-3" style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.1);">
                <i class="bi bi-chat-dots-fill text-warning fs-4 d-block mb-2"></i>
                <div class="small fw-semibold">Write Reviews</div>
                <div class="small" style="color:rgba(255,255,255,0.5);">Share your thoughts</div>
            </div>
        </div>
        <div class="col-4">
            <div class="p-3 rounded-3" style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.1);">
                <i class="bi bi-people-fill text-warning fs-4 d-block mb-2"></i>
                <div class="small fw-semibold">Community</div>
                <div class="small" style="color:rgba(255,255,255,0.5);">Join discussions</div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeInDown { from { opacity:0; transform:translateY(-30px); } to { opacity:1; transform:translateY(0); } }
@keyframes fadeInUp   { from { opacity:0; transform:translateY(30px);  } to { opacity:1; transform:translateY(0); } }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

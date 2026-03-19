<!<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieReviewHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <!-- jQuery loaded in <head> so inline scripts in views can use $ immediately -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= base_url('/') ?>">
            <i class="bi bi-camera-reels-fill text-warning me-2"></i>MovieReviewHub
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('movies') ?>">
                        <i class="bi bi-film me-1"></i>Browse
                    </a>
                </li>
                <?php if(session()->get('user')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('addmovie') ?>">
                        <i class="bi bi-plus-circle me-1"></i>Add Movie
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('reviews/history') ?>">
                        <i class="bi bi-clock-history me-1"></i>My Reviews
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <?php if(session()->get('user')): ?>
                    <li class="nav-item">
                        <span class="navbar-text text-warning">
                            <i class="bi bi-person-circle me-1"></i><?= esc(session()->get('user')) ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('login') ?>">Login</a></li>
                    <li class="nav-item">
                        <a href="<?= base_url('register') ?>" class="btn btn-warning btn-sm fw-semibold">
                            <i class="bi bi-person-plus me-1"></i>Sign Up
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Flash messages -->
<div class="container mt-3" id="flash-wrap">
<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
</div>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MovieReviewHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="bg-dark">

<div class="min-vh-100 d-flex align-items-center justify-content-center px-3">
    <div class="card shadow-lg border-0 w-100" style="max-width:420px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="bi bi-camera-reels-fill text-warning" style="font-size:2.5rem;"></i>
                <h2 class="fw-bold mt-2">Welcome back</h2>
                <p class="text-muted small">Sign in to your account</p>
            </div>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('checkLogin') ?>">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-warning w-100 fw-semibold py-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </form>

            <p class="text-center text-muted mt-4 mb-0">
                Don't have an account?
                <a href="<?= base_url('register') ?>" class="text-warning fw-semibold text-decoration-none">Sign Up</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

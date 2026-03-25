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
<body style="background: linear-gradient(135deg, #0d0d2b 0%, #001f5b 50%, #0a2a4a 100%); min-height:100vh;">

<div class="min-vh-100 d-flex align-items-center justify-content-center px-3">
    <div style="width:100%; max-width:420px;">

        <!-- Back link -->
        <div class="text-center mb-3">
            <a href="<?= base_url('/') ?>" class="text-warning text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i>Back to Home
            </a>
        </div>

        <div class="card shadow-lg border-0" style="border-radius:16px; overflow:hidden;">
            <!-- Card top accent -->
            <div style="height:4px; background: linear-gradient(90deg, #ffc107, #ff9800);"></div>
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="bi bi-camera-reels-fill text-warning" style="font-size:2.5rem;"></i>
                    <h2 class="fw-bold mt-2 mb-1">Welcome back</h2>
                    <p class="text-muted small">Sign in to your MovieReviewHub account</p>
                </div>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger rounded-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success rounded-3">
                        <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('checkLogin') ?>">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="you@example.com" required autocomplete="email">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" id="loginPassword" class="form-control border-start-0 border-end-0 ps-0" placeholder="••••••••" required autocomplete="current-password">
                            <button type="button" class="btn btn-light border border-start-0" id="toggleLoginPw" tabindex="-1">
                                <i class="bi bi-eye" id="toggleLoginIcon"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 fw-semibold py-2 rounded-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </button>
                </form>

                <p class="text-center text-muted mt-4 mb-0 small">
                    Don't have an account?
                    <a href="<?= base_url('register') ?>" class="text-warning fw-semibold text-decoration-none">Sign Up free</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('toggleLoginPw').addEventListener('click', function() {
    var pw   = document.getElementById('loginPassword');
    var icon = document.getElementById('toggleLoginIcon');
    if (pw.type === 'password') {
        pw.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        pw.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});
</script>
</body>
</html>

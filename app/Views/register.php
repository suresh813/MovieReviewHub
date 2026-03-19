<?= view('layout/header') ?>

<div class="min-vh-100 d-flex align-items-center justify-content-center px-3 py-5">
    <div class="card shadow-lg border-0 w-100" style="max-width:440px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="bi bi-camera-reels-fill text-warning" style="font-size:2.5rem;"></i>
                <h2 class="fw-bold mt-2">Create account</h2>
                <p class="text-muted small">Join MovieReviewHub today</p>
            </div>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('saveUser') ?>">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="cooluser123" required minlength="3">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Min. 6 characters" required minlength="6">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Repeat password" required>
                    </div>
                    <div id="pw-match" class="form-text"></div>
                </div>
                <button type="submit" class="btn btn-warning w-100 fw-semibold py-2" id="reg-btn">
                    <i class="bi bi-person-plus me-2"></i>Create Account
                </button>
            </form>

            <p class="text-center text-muted mt-4 mb-0">
                Already have an account?
                <a href="<?= base_url('login') ?>" class="text-warning fw-semibold text-decoration-none">Login</a>
            </p>
        </div>
    </div>
</div>

<script>
const pw  = document.getElementById('password');
const cpw = document.getElementById('confirm_password');
const msg = document.getElementById('pw-match');
const btn = document.getElementById('reg-btn');

[pw, cpw].forEach(el => el.addEventListener('input', function () {
    if (!cpw.value) { msg.textContent = ''; return; }
    if (pw.value === cpw.value) {
        msg.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Passwords match</span>';
        btn.disabled = false;
    } else {
        msg.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Passwords do not match</span>';
        btn.disabled = true;
    }
}));
</script>

<?= view('layout/footer') ?>

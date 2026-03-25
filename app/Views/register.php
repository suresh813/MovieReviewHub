<?= view('layout/header') ?>

<div class="min-vh-100 d-flex align-items-center justify-content-center px-3 py-5">
    <div style="width:100%; max-width:440px;">

        <div class="text-center mb-3">
            <a href="<?= base_url('/') ?>" class="text-warning text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i>Back to Home
            </a>
        </div>

        <div class="card shadow-lg border-0" style="border-radius:16px; overflow:hidden;">
            <div style="height:4px; background: linear-gradient(90deg, #ffc107, #ff9800);"></div>
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="bi bi-camera-reels-fill text-warning" style="font-size:2.5rem;"></i>
                    <h2 class="fw-bold mt-2 mb-1">Create account</h2>
                    <p class="text-muted small">Join MovieReviewHub today — it's free</p>
                </div>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger rounded-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('saveUser') ?>">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="cooluser123" required minlength="3" autocomplete="username">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="you@example.com" required autocomplete="email">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" id="password" class="form-control border-start-0 border-end-0 ps-0" placeholder="Min. 6 characters" required minlength="6" autocomplete="new-password">
                            <button type="button" class="btn btn-light border border-start-0" id="togglePw" tabindex="-1">
                                <i class="bi bi-eye" id="togglePwIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control border-start-0 ps-0" placeholder="Repeat password" required autocomplete="new-password">
                        </div>
                        <div id="pw-match" class="form-text mt-1"></div>
                    </div>

                    <!-- Password strength bar -->
                    <div class="mb-3">
                        <div class="progress" style="height:4px; border-radius:2px;">
                            <div id="strength-bar" class="progress-bar" style="width:0%; transition:width 0.3s;"></div>
                        </div>
                        <div id="strength-label" class="form-text"></div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 fw-semibold py-2 rounded-3" id="reg-btn">
                        <i class="bi bi-person-plus me-2"></i>Create Account
                    </button>
                </form>

                <p class="text-center text-muted mt-4 mb-0 small">
                    Already have an account?
                    <a href="<?= base_url('login') ?>" class="text-warning fw-semibold text-decoration-none">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
const pw  = document.getElementById('password');
const cpw = document.getElementById('confirm_password');
const msg = document.getElementById('pw-match');
const btn = document.getElementById('reg-btn');
const bar = document.getElementById('strength-bar');
const lbl = document.getElementById('strength-label');

document.getElementById('togglePw').addEventListener('click', function() {
    var icon = document.getElementById('togglePwIcon');
    if (pw.type === 'password') {
        pw.type = 'text'; icon.classList.replace('bi-eye','bi-eye-slash');
    } else {
        pw.type = 'password'; icon.classList.replace('bi-eye-slash','bi-eye');
    }
});

pw.addEventListener('input', function() {
    var v = pw.value, score = 0;
    if (v.length >= 6)  score++;
    if (v.length >= 10) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    var colors = ['#dc3545','#fd7e14','#ffc107','#20c997','#198754'];
    var labels = ['Very weak','Weak','Fair','Good','Strong'];
    bar.style.width = (score * 20) + '%';
    bar.style.background = colors[score-1] || '#dee2e6';
    lbl.textContent = score > 0 ? 'Password strength: ' + labels[score-1] : '';
    checkMatch();
});

[pw, cpw].forEach(el => el.addEventListener('input', checkMatch));

function checkMatch() {
    if (!cpw.value) { msg.textContent = ''; btn.disabled = false; return; }
    if (pw.value === cpw.value) {
        msg.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Passwords match</span>';
        btn.disabled = false;
    } else {
        msg.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Passwords do not match</span>';
        btn.disabled = true;
    }
}
</script>

<?= view('layout/footer') ?>

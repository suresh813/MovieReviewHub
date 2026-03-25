<?= view('layout/header') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('movies') ?>" class="text-warning text-decoration-none">
                            <i class="bi bi-film me-1"></i>Movies
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Add Movie</li>
                </ol>
            </nav>

            <div class="card border-0 shadow rounded-4">
                <div style="height:4px; background: linear-gradient(90deg, #ffc107, #ff9800); border-radius:16px 16px 0 0;"></div>
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-1">
                        <i class="bi bi-plus-circle-fill text-warning me-2"></i>Add New Movie
                    </h3>
                    <p class="text-muted mb-4 small">Fill in the details to add a movie to the database.</p>

                    <form id="add-movie-form" method="post" action="<?= base_url('savemovie') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Movie Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control rounded-3"
                                   placeholder="e.g. The Dark Knight" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-sm-7">
                                <label class="form-label fw-semibold">Genre <span class="text-danger">*</span></label>
                                <select name="genre" class="form-select rounded-3" required>
                                    <option value="" disabled selected>Select genre</option>
                                    <option>Action</option><option>Adventure</option><option>Animation</option>
                                    <option>Biography</option><option>Comedy</option><option>Crime</option>
                                    <option>Documentary</option><option>Drama</option><option>Fantasy</option>
                                    <option>Horror</option><option>Musical</option><option>Mystery</option>
                                    <option>Romance</option><option>Sci-Fi</option><option>Thriller</option>
                                    <option>Western</option>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
                                <input type="number" name="year" class="form-control rounded-3"
                                       placeholder="<?= date('Y') ?>" min="1888" max="<?= date('Y') + 2 ?>" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control rounded-3" rows="4"
                                      placeholder="Brief synopsis or description of the movie..."
                                      maxlength="1000"></textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <div class="form-text">Optional but recommended</div>
                                <div class="form-text"><span id="desc-count">0</span>/1000</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning fw-semibold flex-grow-1 py-2 rounded-pill">
                                <i class="bi bi-save me-2"></i>Save Movie
                            </button>
                            <a href="<?= base_url('movies') ?>" class="btn btn-outline-secondary py-2 rounded-pill">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.querySelector('[name="description"]').addEventListener('input', function () {
    document.getElementById('desc-count').textContent = this.value.length;
});
</script>

<?= view('layout/footer') ?>

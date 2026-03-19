<?= view('layout/header') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-1">
                        <i class="bi bi-plus-circle-fill text-warning me-2"></i>Add New Movie
                    </h3>
                    <p class="text-muted mb-4">Fill in the details to add a movie to the database.</p>

                    <form id="add-movie-form" method="post" action="<?= base_url('savemovie') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Movie Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control"
                                   placeholder="e.g. The Dark Knight" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-sm-7">
                                <label class="form-label fw-semibold">Genre <span class="text-danger">*</span></label>
                                <select name="genre" class="form-select" required>
                                    <option value="" disabled selected>Select genre</option>
                                    <option>Action</option>
                                    <option>Adventure</option>
                                    <option>Animation</option>
                                    <option>Biography</option>
                                    <option>Comedy</option>
                                    <option>Crime</option>
                                    <option>Documentary</option>
                                    <option>Drama</option>
                                    <option>Fantasy</option>
                                    <option>Horror</option>
                                    <option>Musical</option>
                                    <option>Mystery</option>
                                    <option>Romance</option>
                                    <option>Sci-Fi</option>
                                    <option>Thriller</option>
                                    <option>Western</option>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
                                <input type="number" name="year" class="form-control"
                                       placeholder="2024" min="1888" max="<?= date('Y') + 2 ?>" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="4"
                                      placeholder="Brief synopsis or description of the movie..."
                                      maxlength="1000"></textarea>
                            <div class="form-text text-end"><span id="desc-count">0</span>/1000</div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning fw-semibold flex-grow-1 py-2">
                                <i class="bi bi-save me-2"></i>Save Movie
                            </button>
                            <a href="<?= base_url('movies') ?>" class="btn btn-outline-secondary py-2">
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

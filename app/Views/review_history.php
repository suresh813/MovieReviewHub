<?= view('layout/header') ?>

<div class="container py-5">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-clock-history text-warning me-2"></i>My Review History
        </h2>
        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
            <i class="bi bi-person-circle me-1"></i><?= esc($username) ?>
            &nbsp;&middot;&nbsp;
            <?= count($reviews) ?> review<?= count($reviews) != 1 ? 's' : '' ?>
        </span>
    </div>

    <?php if(empty($reviews)): ?>
    <div class="text-center py-5">
        <i class="bi bi-journal-x display-3 text-muted"></i>
        <h5 class="text-muted mt-3">No reviews yet</h5>
        <p class="text-muted">You haven't reviewed any movies yet.</p>
        <a href="<?= base_url('movies') ?>" class="btn btn-warning mt-2">
            <i class="bi bi-film me-2"></i>Browse Movies
        </a>
    </div>
    <?php else: ?>

    <!-- Stats bar -->
    <?php
        $totalRating = array_sum(array_column($reviews, 'rating'));
        $avgRating   = count($reviews) > 0 ? round($totalRating / count($reviews), 1) : 0;
        $genreCounts = array_count_values(array_column($reviews, 'movie_genre'));
        arsort($genreCounts);
        $topGenre = array_key_first($genreCounts);
    ?>
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-warning display-6 fw-bold"><?= count($reviews) ?></div>
                <div class="text-muted small">Total Reviews</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-warning display-6 fw-bold">
                    <i class="bi bi-star-fill fs-4"></i> <?= $avgRating ?>
                </div>
                <div class="text-muted small">Average Rating Given</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-warning display-6 fw-bold"><?= esc($topGenre ?? '—') ?></div>
                <div class="text-muted small">Favourite Genre</div>
            </div>
        </div>
    </div>

    <!-- Review cards -->
    <div id="history-list">
        <?php foreach($reviews as $r): ?>
        <div class="card border-0 shadow-sm mb-3 review-history-card" id="history-card-<?= (int)$r['id'] ?>">
            <div class="card-body">
                <div class="row g-3 align-items-start">

                    <!-- Movie info -->
                    <div class="col-md-4">
                        <a href="<?= base_url('movies/details/'.(int)$r['movie_id']) ?>"
                           class="text-decoration-none text-dark">
                            <h6 class="fw-bold mb-1">
                                <i class="bi bi-film text-warning me-1"></i><?= esc($r['movie_title']) ?>
                            </h6>
                        </a>
                        <span class="badge bg-secondary me-1"><?= esc($r['movie_genre']) ?></span>
                        <span class="badge bg-dark"><?= esc($r['movie_year']) ?></span>
                    </div>

                    <!-- Stars + rating -->
                    <div class="col-md-2 text-md-center">
                        <div class="mb-1">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="bi <?= $i <= $r['rating'] ? 'bi-star-fill text-warning' : 'bi-star text-secondary' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="badge bg-warning text-dark"><?= (int)$r['rating'] ?>/5</span>
                    </div>

                    <!-- Review text -->
                    <div class="col-md-4">
                        <p class="mb-0 text-muted small review-text-preview">
                            <?= esc($r['review']) ?>
                        </p>
                    </div>

                    <!-- Date + delete -->
                    <div class="col-md-2 text-md-end">
                        <div class="text-muted small mb-2">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?= isset($r['created_at']) ? date('d M Y', strtotime($r['created_at'])) : 'N/A' ?>
                        </div>
                        <button class="btn btn-sm btn-outline-danger delete-history-review"
                                data-id="<?= (int)$r['id'] ?>"
                                data-movie="<?= esc($r['movie_title']) ?>">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Empty state (shown after all deleted) -->
    <div id="history-empty" class="text-center py-5 d-none">
        <i class="bi bi-journal-x display-3 text-muted"></i>
        <h5 class="text-muted mt-3">No reviews left</h5>
        <a href="<?= base_url('movies') ?>" class="btn btn-warning mt-2">
            <i class="bi bi-film me-2"></i>Browse Movies
        </a>
    </div>

    <?php endif; ?>
</div>

<script>
var CSRF_TOKEN = '<?= csrf_hash() ?>';
var CSRF_NAME  = '<?= csrf_token() ?>';

$(document).ready(function () {

    $(document).on('click', '.delete-history-review', function () {
        var btn      = $(this);
        var reviewId = btn.data('id');
        var movie    = btn.data('movie');

        if (!confirm('Delete your review for "' + movie + '"?')) return;

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        var postData = {};
        postData[CSRF_NAME] = CSRF_TOKEN;

        $.ajax({
            url: '<?= base_url('reviews/delete/') ?>' + reviewId,
            method: 'POST',
            data: postData,
            success: function (res) {
                if (res.status === 'success') {
                    CSRF_TOKEN = res.csrf_token || CSRF_TOKEN;
                    $('#history-card-' + reviewId).fadeOut(300, function () {
                        $(this).remove();
                        var remaining = $('.review-history-card').length;
                        // Update counter badge
                        var badge = $('.badge.bg-warning.text-dark.fs-6');
                        badge.html(
                            '<i class="bi bi-person-circle me-1"></i><?= esc($username) ?>' +
                            ' &nbsp;&middot;&nbsp; ' + remaining +
                            ' review' + (remaining !== 1 ? 's' : '')
                        );
                        if (remaining === 0) {
                            $('#history-empty').removeClass('d-none');
                        }
                    });
                } else {
                    alert(res.message || 'Could not delete review.');
                    btn.prop('disabled', false).html('<i class="bi bi-trash me-1"></i>Delete');
                }
            },
            error: function () {
                alert('Network error. Please try again.');
                btn.prop('disabled', false).html('<i class="bi bi-trash me-1"></i>Delete');
            }
        });
    });

});
</script>

<style>
.review-text-preview {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?= view('layout/footer') ?>

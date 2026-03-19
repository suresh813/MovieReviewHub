<?= view('layout/header') ?>

<?php
function getDetailPoster(string $title, $year): ?string {
    $url = 'http://www.omdbapi.com/?t=' . urlencode($title) . '&y=' . urlencode((string)$year) . '&apikey=768863d2';
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5, CURLOPT_SSL_VERIFYPEER => false]);
        $resp = curl_exec($ch); curl_close($ch);
    } else { $resp = @file_get_contents($url); }
    if ($resp) { $d = json_decode($resp, true); if (!empty($d['Poster']) && $d['Poster'] !== 'N/A') return $d['Poster']; }
    return null;
}
$detailPoster = getDetailPoster($movie['title'], $movie['year']);
?>

<div class="container py-5">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('movies') ?>" class="text-warning text-decoration-none">
                    <i class="bi bi-film me-1"></i>Movies
                </a>
            </li>
            <li class="breadcrumb-item active"><?= esc($movie['title']) ?></li>
        </ol>
    </nav>

    <!-- Movie Hero -->
    <div class="row g-4 mb-5">
        <div class="col-md-3 col-lg-2">
            <?php if($detailPoster): ?>
                <img src="<?= $detailPoster ?>" alt="<?= esc($movie['title']) ?>"
                     class="rounded shadow" style="width:100%; max-height:300px; object-fit:cover;"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <div class="movie-detail-poster d-none align-items-center justify-content-center rounded shadow">
                    <i class="bi bi-camera-reels text-secondary" style="font-size:4rem;"></i>
                </div>
            <?php else: ?>
                <div class="movie-detail-poster d-flex align-items-center justify-content-center rounded shadow">
                    <i class="bi bi-camera-reels text-secondary" style="font-size:4rem;"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-9 col-lg-10">
            <div class="d-flex align-items-start gap-3 flex-wrap mb-2">
                <h1 class="fw-bold mb-0"><?= esc($movie['title']) ?></h1>
                <?php if(session()->get('user')): ?>
                <button class="btn btn-outline-danger btn-sm mt-1" id="delete-this-movie"
                        data-id="<?= (int)$movie['id'] ?>"
                        data-title="<?= esc($movie['title']) ?>">
                    <i class="bi bi-trash me-1"></i>Delete Movie
                </button>
                <?php endif; ?>
            </div>
            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge bg-secondary fs-6"><i class="bi bi-tag me-1"></i><?= esc($movie['genre']) ?></span>
                <span class="badge bg-dark fs-6"><i class="bi bi-calendar3 me-1"></i><?= esc($movie['year']) ?></span>
                <span class="badge bg-warning text-dark fs-6">
                    <i class="bi bi-star-fill me-1"></i>
                    <span id="avg-rating-val"><?= $movie['avg_rating'] ?? 'No rating' ?></span>/5
                    &nbsp;&middot;&nbsp;
                    <span id="review-count-val"><?= $movie['review_count'] ?></span>
                    review<?= $movie['review_count'] != 1 ? 's' : '' ?>
                </span>
            </div>
            <p class="lead"><?= esc($movie['description']) ?></p>

            <!-- OMDB Extra Info – loaded live via JavaScript (Third-party API from JS) -->
            <div id="omdb-info" class="d-none mt-3">
                <div class="d-flex flex-wrap gap-3 align-items-start">
                    <div id="omdb-meta" class="small text-muted"></div>
                    <div id="omdb-ratings-wrap" class="d-none">
                        <span class="fw-semibold small me-1">External Ratings:</span>
                        <span id="omdb-ratings"></span>
                    </div>
                </div>
                <div id="omdb-plot" class="mt-2 text-muted fst-italic small"></div>
                <div id="omdb-cast" class="mt-1 small text-muted"></div>
            </div>
            <div id="omdb-loading" class="mt-2 small text-muted">
                <span class="spinner-border spinner-border-sm me-1 text-warning"></span>Fetching additional info…
            </div>
        </div>
    </div>

    <!-- Nearby Cinemas – Geolocation Hardware API -->
    <div class="card border-0 shadow-sm mb-5 bg-dark text-white" id="nearby-cinemas-card">
        <div class="card-body py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-1">
                        <i class="bi bi-geo-alt-fill text-warning me-2"></i>Find Nearby Cinemas
                    </h6>
                    <p class="small text-secondary mb-0">Use your location to discover cinemas near you.</p>
                </div>
                <button class="btn btn-warning btn-sm fw-semibold" id="find-cinemas-btn">
                    <i class="bi bi-crosshair me-1"></i>Use My Location
                </button>
            </div>
            <div id="geo-result" class="mt-3 d-none"></div>
            <div id="geo-error" class="mt-2 text-danger small d-none"></div>
        </div>
    </div>

    <div class="row g-5">

        <!-- Reviews List -->
        <div class="col-lg-7">
            <h4 class="fw-bold mb-4">
                <i class="bi bi-chat-dots-fill text-warning me-2"></i>Reviews
            </h4>
            <div id="reviews-loading" class="text-center py-3">
                <div class="spinner-border text-warning spinner-border-sm" role="status"></div>
                <span class="text-muted ms-2 small">Loading reviews...</span>
            </div>
            <div id="reviews-list"></div>
            <p id="no-reviews-msg" class="text-muted d-none">No reviews yet. Be the first!</p>
        </div>

        <!-- Submit Review -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm sticky-top" style="top:80px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-pencil-square text-warning me-2"></i>Write a Review
                    </h5>
                    <?php if(!session()->get('user')): ?>
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <a href="<?= base_url('login') ?>" class="alert-link">Log in</a> to leave a review.
                        </div>
                    <?php else: ?>
                        <div id="review-alert" class="d-none"></div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rating <span class="text-danger">*</span></label>
                            <div class="star-picker mb-1" id="star-picker">
                                <?php for($i=1; $i<=5; $i++): ?>
                                <i class="bi bi-star star-btn fs-4" data-val="<?= $i ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" id="rating-input" value="0">
                            <small class="text-muted" id="rating-label">Click a star to rate</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Your Review <span class="text-danger">*</span></label>
                            <textarea id="review-text" class="form-control" rows="5"
                                placeholder="What did you think? (min. 10 characters)"
                                maxlength="1000"></textarea>
                            <div class="form-text text-end"><span id="char-count">0</span>/1000</div>
                        </div>
                        <button class="btn btn-warning w-100 fw-semibold py-2" id="submit-btn">
                            <span id="btn-label"><i class="bi bi-send-fill me-2"></i>Submit Review</span>
                            <span id="btn-spinner" class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Movie Modal -->
<div class="modal fade" id="deleteMovieModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Movie
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-1">Are you sure you want to delete:</p>
                <p class="fw-bold fs-5 text-danger" id="modal-movie-title">—</p>
                <p class="text-muted small mb-0">This will permanently delete the movie and all its reviews.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger fw-semibold" id="confirm-delete-movie-btn">
                    <i class="bi bi-trash me-1"></i>Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<?= view('layout/footer') ?>

<script>
/* ── PHP-injected variables ── */
var MOVIE_ID     = <?= (int)$movie['id'] ?>;
var MOVIE_TITLE  = '<?= addslashes(esc($movie['title'])) ?>';
var MOVIE_YEAR   = '<?= (int)$movie['year'] ?>';
var SESSION_USER = '<?= esc(session()->get('user') ?? '') ?>';

/* CSRF – required for every POST in CodeIgniter 4 */
var CSRF_NAME  = '<?= csrf_token() ?>';
var CSRF_TOKEN = '<?= csrf_hash() ?>';

var URL_REVIEWS_ADD    = '<?= base_url('reviews/add') ?>';
var URL_REVIEWS_DELETE = '<?= base_url('reviews/delete') ?>';
var URL_MOVIES_DELETE  = '<?= base_url('movies/delete') ?>';
var URL_MOVIES_LIST    = '<?= base_url('movies') ?>';
var OMDB_API_KEY       = '768863d2';

/* Add CSRF token to POST data string */
function csrfData(extra) {
    var base = CSRF_NAME + '=' + encodeURIComponent(CSRF_TOKEN);
    return extra ? base + '&' + extra : base;
}

/* Refresh CSRF from server response to keep token valid */
function refreshCsrf(res) {
    if (res && res.csrf_token) CSRF_TOKEN = res.csrf_token;
}

/* ── Render reviews helper ── */
function renderReviews(reviews) {
    $('#reviews-loading').addClass('d-none');
    if (!reviews || reviews.length === 0) {
        $('#no-reviews-msg').removeClass('d-none');
        $('#reviews-list').html('');
        return;
    }
    $('#no-reviews-msg').addClass('d-none');
    var html = '';
    reviews.forEach(function(r) {
        var stars = '';
        for (var i = 1; i <= 5; i++) {
            stars += i <= r.rating
                ? '<i class="bi bi-star-fill text-warning"></i>'
                : '<i class="bi bi-star text-secondary"></i>';
        }
        var canDelete = (SESSION_USER !== '' && r.user_name === SESSION_USER);
        var deleteBtn = canDelete
            ? '<button class="btn btn-sm btn-outline-danger ms-2 review-delete-btn" data-review-id="' + r.id + '"><i class="bi bi-trash"></i></button>'
            : '';
        html += '<div class="card border-0 shadow-sm mb-3" id="review-card-' + r.id + '">' +
            '<div class="card-body">' +
                '<div class="d-flex justify-content-between align-items-start mb-2">' +
                    '<div>' +
                        '<span class="fw-semibold"><i class="bi bi-person-circle text-warning me-1"></i>' + escHtml(r.user_name) + '</span>' +
                        '<br><span class="small text-muted">' + formatDate(r.created_at) + '</span>' +
                    '</div>' +
                    '<div class="d-flex align-items-center">' +
                        stars +
                        '<span class="badge bg-warning text-dark ms-2">' + r.rating + '/5</span>' +
                        deleteBtn +
                    '</div>' +
                '</div>' +
                '<p class="mb-0">' + escHtml(r.review) + '</p>' +
            '</div></div>';
    });
    $('#reviews-list').html(html);
}

/* ═══════════════════════════════════════════
   THIRD-PARTY API: OMDB called from JavaScript
   Fetches plot, cast, director, ratings live
═══════════════════════════════════════════ */
function loadOmdbInfo() {
    var url = 'https://www.omdbapi.com/?t=' + encodeURIComponent(MOVIE_TITLE)
            + '&y=' + MOVIE_YEAR + '&apikey=' + OMDB_API_KEY;
    $.getJSON(url, function(data) {
        $('#omdb-loading').addClass('d-none');
        if (data.Response !== 'True') return;

        var meta = [];
        if (data.Runtime && data.Runtime !== 'N/A') meta.push('<i class="bi bi-clock me-1"></i>' + escHtml(data.Runtime));
        if (data.Director && data.Director !== 'N/A') meta.push('<i class="bi bi-camera me-1"></i>Dir: ' + escHtml(data.Director));
        if (data.Rated && data.Rated !== 'N/A') meta.push('<span class="badge bg-secondary">' + escHtml(data.Rated) + '</span>');
        if (meta.length) $('#omdb-meta').html(meta.join(' &nbsp;·&nbsp; '));

        if (data.Plot && data.Plot !== 'N/A')
            $('#omdb-plot').html('<i class="bi bi-quote me-1 text-warning"></i>' + escHtml(data.Plot));

        if (data.Actors && data.Actors !== 'N/A')
            $('#omdb-cast').html('<i class="bi bi-people me-1 text-warning"></i><strong>Cast:</strong> ' + escHtml(data.Actors));

        if (data.Ratings && data.Ratings.length > 0) {
            var badges = data.Ratings.map(function(r) {
                var src = escHtml(r.Source.replace('Internet Movie Database','IMDb').replace('Rotten Tomatoes','RT'));
                return '<span class="badge bg-dark border me-1">' + src + ': ' + escHtml(r.Value) + '</span>';
            }).join('');
            $('#omdb-ratings').html(badges);
            $('#omdb-ratings-wrap').removeClass('d-none');
        }

        $('#omdb-info').removeClass('d-none');
    }).fail(function() {
        $('#omdb-loading').addClass('d-none');
    });
}

/* ═══════════════════════════════════════════
   HARDWARE API: Geolocation – Find nearby cinemas
═══════════════════════════════════════════ */
$('#find-cinemas-btn').on('click', function() {
    var btn = $(this);
    if (!navigator.geolocation) {
        $('#geo-error').text('Geolocation is not supported by your browser.').removeClass('d-none');
        return;
    }
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Locating…');
    $('#geo-error').addClass('d-none');
    $('#geo-result').addClass('d-none');

    navigator.geolocation.getCurrentPosition(
        function(pos) {
            var lat = pos.coords.latitude.toFixed(5);
            var lon = pos.coords.longitude.toFixed(5);
            var acc = Math.round(pos.coords.accuracy);
            btn.prop('disabled', false).html('<i class="bi bi-check-circle-fill me-1"></i>Location Found');

            var mapsUrl = 'https://www.google.com/maps/search/cinema/@' + lat + ',' + lon + ',14z';
            var osmUrl  = 'https://www.openstreetmap.org/?mlat=' + lat + '&mlon=' + lon + '#map=14/' + lat + '/' + lon;

            $('#geo-result').html(
                '<div class="d-flex flex-wrap gap-2 align-items-center">' +
                  '<span class="text-success small"><i class="bi bi-geo-alt-fill me-1"></i>Detected (±' + acc + 'm)</span>' +
                  '<a href="' + mapsUrl + '" target="_blank" class="btn btn-sm btn-outline-warning">' +
                    '<i class="bi bi-google me-1"></i>Cinemas on Google Maps' +
                  '</a>' +
                  '<a href="' + osmUrl + '" target="_blank" class="btn btn-sm btn-outline-light">' +
                    '<i class="bi bi-map me-1"></i>OpenStreetMap' +
                  '</a>' +
                '</div>'
            ).removeClass('d-none');
        },
        function(err) {
            btn.prop('disabled', false).html('<i class="bi bi-crosshair me-1"></i>Use My Location');
            var msgs = ['','Location access denied.','Location unavailable.','Location request timed out.'];
            $('#geo-error').text(msgs[err.code] || 'Could not get location.').removeClass('d-none');
        },
        { timeout: 10000, maximumAge: 60000 }
    );
});

$(document).ready(function () {

    renderReviews(<?= json_encode($reviews) ?>);
    loadOmdbInfo();

    /* Star picker */
    $('.star-btn').on('mouseover', function () { highlightStars($(this).data('val')); });
    $('.star-btn').on('mouseleave', function () { highlightStars($('#rating-input').val()); });
    $('.star-btn').on('click', function () {
        var val = $(this).data('val');
        $('#rating-input').val(val);
        $('#rating-label').text(val + '/5 selected');
        highlightStars(val);
    });

    /* Char counter */
    $('#review-text').on('input', function () { $('#char-count').text($(this).val().length); });

    /* ════════════════════════
       AJAX: SUBMIT REVIEW
    ════════════════════════ */
    $('#submit-btn').on('click', function () {
        var rating = parseInt($('#rating-input').val());
        var text   = $('#review-text').val().trim();
        if (!rating) { showAlert('danger', 'Please select a star rating.'); return; }
        if (text.length < 10) { showAlert('danger', 'Review must be at least 10 characters.'); return; }

        $('#btn-label').addClass('d-none');
        $('#btn-spinner').removeClass('d-none');
        $('#submit-btn').prop('disabled', true);

        $.ajax({
            url        : URL_REVIEWS_ADD,
            method     : 'POST',
            contentType: 'application/x-www-form-urlencoded',
            data       : csrfData('movie_id=' + MOVIE_ID + '&rating=' + rating + '&review=' + encodeURIComponent(text)),
            dataType   : 'json',
            success: function (res) {
                refreshCsrf(res);
                $('#btn-label').removeClass('d-none');
                $('#btn-spinner').addClass('d-none');

                if (res.status === 'success') {
                    showAlert('success', '<i class="bi bi-check-circle-fill me-2"></i>Review submitted!');
                    $('#review-text').val('');
                    $('#char-count').text('0');
                    $('#rating-input').val('0');
                    highlightStars(0);
                    $('#submit-btn').prop('disabled', true).html('<i class="bi bi-check-lg me-2"></i>Review Submitted');
                    $('#avg-rating-val').text(res.avg_rating || 'N/A');
                    $('#review-count-val').text(res.review_count);

                    var stars = '';
                    for (var i = 1; i <= 5; i++) {
                        stars += i <= res.rating ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-secondary"></i>';
                    }
                    var rid = res.review_id ? res.review_id : 'tmp-' + Date.now();
                    var newCard = '<div class="card border-0 shadow-sm mb-3 border-start border-warning border-3" id="review-card-' + rid + '">' +
                        '<div class="card-body">' +
                            '<div class="d-flex justify-content-between align-items-start mb-2">' +
                                '<div><span class="fw-semibold"><i class="bi bi-person-circle text-warning me-1"></i>' + escHtml(res.user_name) + '</span>' +
                                '<br><span class="small text-muted">Just now</span></div>' +
                                '<div class="d-flex align-items-center">' +
                                    stars +
                                    '<span class="badge bg-warning text-dark ms-2">' + res.rating + '/5</span>' +
                                    '<button class="btn btn-sm btn-outline-danger ms-2 review-delete-btn" data-review-id="' + rid + '"><i class="bi bi-trash"></i></button>' +
                                '</div>' +
                            '</div>' +
                            '<p class="mb-0">' + escHtml(res.review) + '</p>' +
                        '</div></div>';
                    $('#no-reviews-msg').addClass('d-none');
                    $('#reviews-list').prepend(newCard);
                } else {
                    $('#submit-btn').prop('disabled', false);
                    showAlert('danger', res.message || 'Something went wrong.');
                }
            },
            error: function (xhr) {
                $('#btn-label').removeClass('d-none');
                $('#btn-spinner').addClass('d-none');
                $('#submit-btn').prop('disabled', false);
                showAlert('danger', 'Network error (HTTP ' + xhr.status + '). Please try again.');
            }
        });
    });

    /* ════════════════════════
       AJAX: DELETE REVIEW
       FIX: CSRF token now included in every POST
    ════════════════════════ */
    $(document).on('click', '.review-delete-btn', function () {
        var btn      = $(this);
        var reviewId = btn.data('review-id');
        if (!confirm('Delete this review?')) return;

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url        : URL_REVIEWS_DELETE + '/' + reviewId,
            method     : 'POST',
            contentType: 'application/x-www-form-urlencoded',
            data       : csrfData('review_id=' + reviewId),   /* CSRF fix */
            dataType   : 'json',
            success: function (res) {
                refreshCsrf(res);
                if (res.status === 'success') {
                    $('#review-card-' + reviewId).fadeOut(300, function () {
                        $(this).remove();
                        if ($('#reviews-list .card').length === 0) {
                            $('#no-reviews-msg').removeClass('d-none');
                        }
                    });
                    $('#avg-rating-val').text(res.avg_rating || 'N/A');
                    $('#review-count-val').text(res.review_count);
                } else {
                    btn.prop('disabled', false).html('<i class="bi bi-trash"></i>');
                    alert(res.message || 'Could not delete review.');
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="bi bi-trash"></i>');
                alert('Delete failed (HTTP ' + xhr.status + '). Please try again.');
            }
        });
    });

    /* ════════════════════════
       AJAX: DELETE MOVIE
    ════════════════════════ */
    $('#delete-this-movie').on('click', function () {
        $('#modal-movie-title').text($(this).data('title'));
        $('#confirm-delete-movie-btn').prop('disabled', false).html('<i class="bi bi-trash me-1"></i>Yes, Delete');
        new bootstrap.Modal(document.getElementById('deleteMovieModal')).show();
    });

    $('#confirm-delete-movie-btn').on('click', function () {
        var btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Deleting…');

        $.ajax({
            url        : URL_MOVIES_DELETE + '/' + MOVIE_ID,
            method     : 'POST',
            contentType: 'application/x-www-form-urlencoded',
            data       : csrfData('movie_id=' + MOVIE_ID),    /* CSRF fix */
            dataType   : 'json',
            success: function (res) {
                if (res && res.status === 'success') {
                    window.location.href = URL_MOVIES_LIST;
                } else {
                    btn.prop('disabled', false).html('<i class="bi bi-trash me-1"></i>Yes, Delete');
                    alert((res && res.message) ? res.message : 'Delete failed.');
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="bi bi-trash me-1"></i>Yes, Delete');
                alert('Delete failed (HTTP ' + xhr.status + '). Please try again.');
            }
        });
    });

});

/* ── Helpers ── */
function highlightStars(val) {
    $('.star-btn').each(function () {
        var v = $(this).data('val');
        $(this).toggleClass('bi-star-fill text-warning', v <= val)
               .toggleClass('bi-star text-secondary',    v >  val);
    });
}
function showAlert(type, msg) {
    $('#review-alert').removeClass('d-none alert-success alert-danger')
        .addClass('alert alert-' + type).html(msg);
    setTimeout(function () { $('#review-alert').addClass('d-none'); }, 5000);
}
function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function formatDate(dt) {
    if (!dt) return '';
    return new Date(dt).toLocaleDateString('en-GB', {day:'numeric', month:'short', year:'numeric'});
}
</script>

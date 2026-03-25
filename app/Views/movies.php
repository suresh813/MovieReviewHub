<?= view('layout/header') ?>

<?php
$omdbApiKey = getenv('OMDB_API_KEY') ?: '768863d2';

function getMoviePoster(string $title, $year, string $apiKey): ?string {
    $url = 'http://www.omdbapi.com/?t=' . urlencode($title)
         . '&y=' . urlencode((string)$year)
         . '&apikey=' . $apiKey;
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5, CURLOPT_SSL_VERIFYPEER => false]);
        $resp = curl_exec($ch);
        curl_close($ch);
    } else {
        $resp = @file_get_contents($url);
    }
    if ($resp) {
        $data = json_decode($resp, true);
        if (!empty($data['Poster']) && $data['Poster'] !== 'N/A') return $data['Poster'];
    }
    return null;
}

// Collect all genres for filter buttons
$allGenres = array_unique(array_column($movies, 'genre'));
sort($allGenres);
?>

<div class="container py-5">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-film text-warning me-2"></i>Browse Movies
        </h2>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <!-- Search -->
            <div class="input-group">
                <span class="input-group-text bg-warning border-0">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="search" class="form-control"
                       placeholder="Search title or genre..." autocomplete="off" style="min-width:200px;">
                <button class="btn btn-outline-secondary d-none" id="clear-btn" title="Clear">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <!-- Sort -->
            <select id="sort-select" class="form-select" style="width:auto;">
                <option value="">Sort by...</option>
                <option value="rating">⭐ Highest Rated</option>
                <option value="year-desc">📅 Newest First</option>
                <option value="year-asc">📅 Oldest First</option>
                <option value="reviews">💬 Most Reviewed</option>
            </select>
            <?php if(session()->get('user')): ?>
            <a href="<?= base_url('addmovie') ?>" class="btn btn-warning fw-semibold text-nowrap">
                <i class="bi bi-plus-lg me-1"></i>Add Movie
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Genre Filter Pills -->
    <?php if(!empty($allGenres)): ?>
    <div class="d-flex flex-wrap gap-2 mb-4" id="genre-filters">
        <button class="btn btn-warning btn-sm rounded-pill fw-semibold genre-pill active" data-genre="">All</button>
        <?php foreach($allGenres as $genre): ?>
        <button class="btn btn-outline-secondary btn-sm rounded-pill genre-pill" data-genre="<?= esc($genre) ?>">
            <?= esc($genre) ?>
        </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Stats bar -->
    <div class="d-flex gap-3 mb-4 small text-muted">
        <span><i class="bi bi-collection me-1"></i><strong id="movie-count"><?= count($movies) ?></strong> movies</span>
    </div>

    <!-- Spinner -->
    <div id="search-spinner" class="text-center py-4 d-none">
        <div class="spinner-border text-warning" role="status"></div>
        <p class="text-muted mt-2 small">Searching movies...</p>
    </div>

    <!-- Movie Grid -->
    <div id="movieResults" class="row g-4">
        <?php foreach($movies as $movie):
            $poster = getMoviePoster($movie['title'], $movie['year'], $omdbApiKey);
        ?>
        <div class="col-6 col-md-4 col-lg-3 movie-item"
             id="movie-col-<?= (int)$movie['id'] ?>"
             data-genre="<?= esc($movie['genre']) ?>"
             data-rating="<?= (float)($movie['avg_rating'] ?? 0) ?>"
             data-year="<?= (int)$movie['year'] ?>"
             data-reviews="<?= (int)($movie['review_count'] ?? 0) ?>">
            <div class="card movie-card h-100 border-0 shadow-sm">
                <div class="movie-poster-wrap" style="position:relative; overflow:hidden;">

                    <?php if($poster): ?>
                        <img src="<?= $poster ?>"
                             alt="<?= esc($movie['title']) ?>"
                             style="width:100%; height:280px; object-fit:cover;"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                        <div class="movie-poster-placeholder d-none align-items-center justify-content-center" style="height:280px;">
                            <i class="bi bi-camera-reels text-secondary" style="font-size:3rem;"></i>
                        </div>
                    <?php else: ?>
                        <div class="movie-poster-placeholder d-flex align-items-center justify-content-center" style="height:280px;">
                            <i class="bi bi-camera-reels text-secondary" style="font-size:3rem;"></i>
                        </div>
                    <?php endif; ?>

                    <!-- Rating badge -->
                    <div class="poster-rating-badge">
                        <i class="bi bi-star-fill text-warning me-1"></i>
                        <span><?= $movie['avg_rating'] ?? 'N/A' ?></span>
                    </div>

                    <!-- Delete button -->
                    <?php if(session()->get('user')): ?>
                    <div style="position:absolute; top:8px; right:8px;">
                        <button class="btn btn-sm btn-danger delete-movie-btn"
                                data-id="<?= (int)$movie['id'] ?>"
                                data-title="<?= esc($movie['title']) ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title fw-bold"><?= esc($movie['title']) ?></h6>
                    <p class="small text-muted mb-1">
                        <span class="badge bg-secondary me-1"><?= esc($movie['genre']) ?></span>
                        <i class="bi bi-calendar3 me-1"></i><?= esc($movie['year']) ?>
                    </p>
                    <p class="small text-muted mb-3">
                        <i class="bi bi-chat-dots text-warning me-1"></i>
                        <?= $movie['review_count'] ?? 0 ?> review<?= ($movie['review_count'] ?? 0) != 1 ? 's' : '' ?>
                    </p>
                    <a href="<?= base_url('movies/details/'.$movie['id']) ?>"
                       class="btn btn-warning btn-sm mt-auto fw-semibold rounded-pill">
                        <i class="bi bi-eye me-1"></i>View Details
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- No results -->
    <div id="no-results" class="text-center py-5 d-none">
        <i class="bi bi-search display-3 text-muted"></i>
        <h5 class="text-muted mt-3">No movies found</h5>
        <p class="text-muted">Try a different search term or genre.</p>
        <button class="btn btn-outline-warning" onclick="resetFilters()">Clear Filters</button>
    </div>

</div>

<!-- Delete Movie Modal -->
<div class="modal fade" id="deleteMovieModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-danger text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Movie
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-1">Are you sure you want to delete:</p>
                <p class="fw-bold fs-5 text-danger" id="modal-movie-title">—</p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    This will permanently delete the movie and all its reviews.
                </p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger fw-semibold rounded-pill" id="confirm-delete-btn">
                    <i class="bi bi-trash me-1"></i>Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

    var CSRF_NAME  = '<?= csrf_token() ?>';
    var CSRF_TOKEN = '<?= csrf_hash() ?>';
    var BASE_URL   = '<?= base_url() ?>';

    function withCsrf(data) { data = data || {}; data[CSRF_NAME] = CSRF_TOKEN; return data; }

    /* ── GENRE FILTER ── */
    $(document).on('click', '.genre-pill', function() {
        $('.genre-pill').removeClass('active btn-warning').addClass('btn-outline-secondary');
        $(this).addClass('active btn-warning').removeClass('btn-outline-secondary');
        applyFilters();
    });

    /* ── SORT ── */
    $('#sort-select').on('change', function() { applyFilters(); });

    function applyFilters() {
        var genre    = $('.genre-pill.active').data('genre');
        var sortVal  = $('#sort-select').val();
        var items    = $('.movie-item').toArray();

        // Filter
        var visible = items.filter(function(el) {
            return !genre || $(el).data('genre') === genre;
        });
        items.forEach(function(el) { $(el).addClass('d-none'); });
        visible.forEach(function(el) { $(el).removeClass('d-none'); });

        // Sort
        if (sortVal) {
            visible.sort(function(a, b) {
                if (sortVal === 'rating')    return $(b).data('rating') - $(a).data('rating');
                if (sortVal === 'year-desc') return $(b).data('year') - $(a).data('year');
                if (sortVal === 'year-asc')  return $(a).data('year') - $(b).data('year');
                if (sortVal === 'reviews')   return $(b).data('reviews') - $(a).data('reviews');
            });
            var grid = $('#movieResults');
            visible.forEach(function(el) { grid.append(el); });
        }

        $('#movie-count').text(visible.length);
        $('#no-results').toggleClass('d-none', visible.length > 0);
    }

    /* ── LIVE SEARCH ── */
    var searchTimer;
    $('#search').on('input', function () {
        clearTimeout(searchTimer);
        var keyword = $(this).val().trim();
        $('#clear-btn').toggleClass('d-none', !keyword);

        if (!keyword) {
            $('.genre-pill[data-genre=""]').trigger('click');
            return;
        }

        $('#search-spinner').removeClass('d-none');
        $('#movieResults').addClass('opacity-50');

        searchTimer = setTimeout(function () {
            $.ajax({
                url: BASE_URL + 'movies/search', method: 'POST',
                data: withCsrf({ keyword: keyword }),
                success: function (html) {
                    $('#search-spinner').addClass('d-none');
                    $('#movieResults').removeClass('opacity-50');
                    if (!html.trim()) {
                        $('#movieResults').html('');
                        $('#no-results').removeClass('d-none');
                    } else {
                        $('#no-results').addClass('d-none');
                        $('#movieResults').html(html.trim());
                    }
                },
                error: function() { $('#search-spinner').addClass('d-none'); $('#movieResults').removeClass('opacity-50'); }
            });
        }, 350);
    });

    $('#clear-btn').on('click', function () { $('#search').val('').trigger('input'); });

    /* ── DELETE MOVIE ── */
    var pendingDeleteId = null;
    $(document).on('click', '.delete-movie-btn', function () {
        pendingDeleteId = $(this).data('id');
        $('#modal-movie-title').text($(this).data('title'));
        $('#confirm-delete-btn').prop('disabled', false).html('<i class="bi bi-trash me-1"></i>Yes, Delete');
        new bootstrap.Modal(document.getElementById('deleteMovieModal')).show();
    });

    $('#confirm-delete-btn').on('click', function () {
        if (!pendingDeleteId) return;
        var btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Deleting…');
        $.ajax({
            url: BASE_URL + 'movies/delete/' + pendingDeleteId, method: 'POST',
            data: withCsrf({}),
            success: function (res) {
                if (res && res.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('deleteMovieModal')).hide();
                    $('[data-id="' + pendingDeleteId + '"]').closest('.movie-item').fadeOut(350, function () {
                        $(this).remove();
                        $('#movie-count').text($('.movie-item:not(.d-none)').length);
                        if ($('#movieResults .movie-item').length === 0) $('#no-results').removeClass('d-none');
                    });
                    pendingDeleteId = null;
                } else {
                    btn.prop('disabled', false).html('<i class="bi bi-trash me-1"></i>Yes, Delete');
                    alert((res && res.message) ? res.message : 'Could not delete movie.');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="bi bi-trash me-1"></i>Yes, Delete');
                alert('Network error (' + xhr.status + '). Please try again.');
            }
        });
    });
});

function resetFilters() {
    $('.genre-pill[data-genre=""]').trigger('click');
    $('#search').val('');
    $('#sort-select').val('');
    $('#clear-btn').addClass('d-none');
}
</script>

<?= view('layout/footer') ?>

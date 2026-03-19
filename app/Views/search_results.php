<?php
/**
 * AJAX partial — returned as raw HTML into #movieResults.
 * No layout/header/footer here — this is a fragment only.
 *
 * Each card must have:
 *   - id="movie-col-{id}"  so JS can remove it after delete
 *   - data-id on the .delete-movie-btn so delegation works
 */

if (!function_exists('getSearchPoster')) {
    function getSearchPoster(string $title, $year): ?string {
        $url = 'http://www.omdbapi.com/?t=' . urlencode($title)
             . '&y=' . urlencode((string)$year)
             . '&apikey=768863d2';
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5, CURLOPT_SSL_VERIFYPEER => false]);
            $resp = curl_exec($ch);
            curl_close($ch);
        } else {
            $resp = @file_get_contents($url);
        }
        if ($resp) {
            $d = json_decode($resp, true);
            if (!empty($d['Poster']) && $d['Poster'] !== 'N/A') return $d['Poster'];
        }
        return null;
    }
}
?>
<?php if (empty($movies)): ?>
<?php /* intentionally empty — JS shows #no-results */ ?>
<?php else: ?>
<?php foreach ($movies as $movie):
    $poster = getSearchPoster($movie['title'], $movie['year']);
?>
<div class="col-6 col-md-4 col-lg-3" id="movie-col-<?= (int)$movie['id'] ?>">
    <div class="card movie-card h-100 border-0 shadow-sm">
        <div class="movie-poster-wrap" style="position:relative; overflow:hidden;">

            <?php if ($poster): ?>
                <img src="<?= $poster ?>" alt="<?= esc($movie['title']) ?>"
                     style="width:100%; height:280px; object-fit:cover;"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <div class="d-none align-items-center justify-content-center"
                     style="height:280px; background:#f0f0f0;">
                    <i class="bi bi-camera-reels text-secondary" style="font-size:3rem;"></i>
                </div>
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center"
                     style="height:280px; background:#f0f0f0;">
                    <i class="bi bi-camera-reels text-secondary" style="font-size:3rem;"></i>
                </div>
            <?php endif; ?>

            <!-- Rating badge -->
            <div class="poster-rating-badge"
                 style="position:absolute; top:8px; left:8px; background:rgba(0,0,0,0.7);
                        color:#fff; padding:3px 8px; border-radius:20px; font-size:0.8rem;">
                <i class="bi bi-star-fill text-warning me-1"></i>
                <span><?= ($movie['avg_rating'] ?? null) ? $movie['avg_rating'] : 'N/A' ?></span>
            </div>

            <!-- Delete button — data-id is what the delegated handler reads -->
            <?php if (session()->get('user')): ?>
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
                <i class="bi bi-tag me-1"></i><?= esc($movie['genre']) ?>
                &nbsp;&middot;&nbsp;
                <i class="bi bi-calendar3 me-1"></i><?= esc($movie['year']) ?>
            </p>
            <p class="small text-muted mb-3">
                <i class="bi bi-chat-dots text-warning me-1"></i>
                <?= (int)($movie['review_count'] ?? 0) ?>
                review<?= ($movie['review_count'] ?? 0) != 1 ? 's' : '' ?>
            </p>
            <a href="<?= base_url('movies/details/' . (int)$movie['id']) ?>"
               class="btn btn-warning btn-sm mt-auto fw-semibold">
                <i class="bi bi-eye me-1"></i>View Details
            </a>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

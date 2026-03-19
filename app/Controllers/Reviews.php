<?php
namespace App\Controllers;
use App\Models\ReviewModel;
use App\Models\MovieModel;

class Reviews extends BaseController
{
    // AJAX: POST /reviews/add
    public function add()
    {
        if (!session()->get('user')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please log in to submit a review.']);
        }

        $movieId = (int)$this->request->getPost('movie_id');
        $rating  = (int)$this->request->getPost('rating');
        $review  = trim($this->request->getPost('review'));
        $name    = session()->get('user');

        if (!$rating || !$review) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Rating and review are required.']);
        }

        if (strlen($review) < 10) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Review must be at least 10 characters.']);
        }

        $model = new ReviewModel();
        $model->save([
            'movie_id'  => $movieId,
            'user_name' => $name,
            'rating'    => $rating,
            'review'    => $review,
        ]);
        $reviewId = $model->getInsertID();

        $movie = (new MovieModel())->getOneWithRating($movieId);

        return $this->response->setJSON([
            'status'       => 'success',
            'message'      => 'Review submitted!',
            'review_id'    => $reviewId,
            'user_name'    => esc($name),
            'rating'       => $rating,
            'review'       => esc($review),
            'avg_rating'   => $movie['avg_rating'] ?? 'N/A',
            'review_count' => $movie['review_count'],
            'csrf_token'   => csrf_hash(),
        ]);
    }

    // AJAX: POST /reviews/delete/{id}
    public function delete(int $id)
    {
        if (!session()->get('user')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorised.']);
        }

        $model  = new ReviewModel();
        $review = $model->find($id);

        if (!$review) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Review not found.']);
        }

        if ($review['user_name'] !== session()->get('user')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorised.']);
        }

        $model->delete($id);

        $movie = (new MovieModel())->getOneWithRating($review['movie_id']);

        return $this->response->setJSON([
            'status'       => 'success',
            'avg_rating'   => $movie['avg_rating'] ?? 'N/A',
            'review_count' => $movie['review_count'],
            'csrf_token'   => csrf_hash(),
        ]);
    }

    // Review History page: GET /reviews/history
    public function history()
    {
        if (!session()->get('user')) return redirect()->to('/login');

        $username = session()->get('user');
        $reviews  = (new ReviewModel())->getForUser($username);

        return view('review_history', ['reviews' => $reviews, 'username' => $username]);
    }
}

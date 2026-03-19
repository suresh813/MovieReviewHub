<?php
namespace App\Controllers;
use App\Models\MovieModel;
use App\Models\ReviewModel;

class Movies extends BaseController
{
    public function index()
    {
        $model = new MovieModel();
        return view('movies', ['movies' => $model->getWithAvgRating()]);
    }

    public function add()
    {
        if (!session()->get('user')) return redirect()->to('/login');
        return view('add_movie');
    }

    public function save()
    {
        if (!session()->get('user')) return redirect()->to('/login');

        $model = new MovieModel();
        $model->save([
            'title'       => $this->request->getPost('title'),
            'genre'       => $this->request->getPost('genre'),
            'year'        => $this->request->getPost('year'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/movies')->with('success', 'Movie added successfully!');
    }

    public function details(int $id)
    {
        $movie = (new MovieModel())->getOneWithRating($id);
        if (!$movie) throw new \CodeIgniter\Exceptions\PageNotFoundException("Movie not found.");

        $reviews = (new ReviewModel())->getForMovie($id);
        return view('movie_details', compact('movie', 'reviews'));
    }

    /**
     * AJAX POST /movies/search
     * Returns an HTML partial (search_results.php) injected into #movieResults.
     */
    public function search()
    {
        $keyword = $this->request->getPost('keyword');
        $model   = new MovieModel();
        $movies  = $keyword
            ? $model->search($keyword)
            : $model->getWithAvgRating();

        return view('search_results', ['movies' => $movies]);
    }

    /**
     * AJAX POST /movies/delete/{id}
     * Deletes a movie and all its reviews, returns JSON.
     */
    public function delete(int $id)
    {
        // Must be logged in
        if (!session()->get('user')) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['status' => 'error', 'message' => 'Please log in.']);
        }

        $movieModel = new MovieModel();
        $movie = $movieModel->find($id);

        if (!$movie) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Movie not found.']);
        }

        // Delete reviews first (foreign key safety)
        (new ReviewModel())->where('movie_id', $id)->delete();

        // Delete movie
        $movieModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Movie deleted successfully.',
        ]);
    }
}

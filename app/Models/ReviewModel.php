<?php
namespace App\Models;
use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table         = 'reviews';
    protected $allowedFields = ['movie_id','user_name','rating','review'];
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = '';

    public function getForMovie(int $movieId): array
    {
        return $this->where('movie_id', $movieId)
                    ->orderBy('id','DESC')
                    ->findAll();
    }

    /**
     * Get all reviews by a specific user, joined with movie title.
     */
    public function getForUser(string $username): array
    {
        return $this->db->table('reviews r')
            ->select('r.*, m.title AS movie_title, m.genre AS movie_genre, m.year AS movie_year')
            ->join('movies m', 'm.id = r.movie_id', 'left')
            ->where('r.user_name', $username)
            ->orderBy('r.id','DESC')
            ->get()->getResultArray();
    }
}

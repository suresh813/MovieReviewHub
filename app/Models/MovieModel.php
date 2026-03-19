<?php
namespace App\Models;
use CodeIgniter\Model;

class MovieModel extends Model
{
    protected $table         = 'movies';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['title','genre','year','description','image'];

    /**
     * Search with avg_rating so search results show ratings correctly.
     */
    public function search(string $keyword): array
    {
        return $this->db->table('movies m')
            ->select('m.*, ROUND(AVG(r.rating),1) AS avg_rating, COUNT(r.id) AS review_count')
            ->join('reviews r', 'r.movie_id = m.id', 'left')
            ->groupBy('m.id')
            ->like('m.title', $keyword)
            ->orLike('m.genre', $keyword)
            ->orderBy('m.id','DESC')
            ->get()->getResultArray();
    }

    public function getWithAvgRating(): array
    {
        return $this->db->table('movies m')
            ->select('m.*, ROUND(AVG(r.rating),1) AS avg_rating, COUNT(r.id) AS review_count')
            ->join('reviews r', 'r.movie_id = m.id', 'left')
            ->groupBy('m.id')
            ->orderBy('m.id','DESC')
            ->get()->getResultArray();
    }

    public function getOneWithRating(int $id): ?array
    {
        return $this->db->table('movies m')
            ->select('m.*, ROUND(AVG(r.rating),1) AS avg_rating, COUNT(r.id) AS review_count')
            ->join('reviews r', 'r.movie_id = m.id', 'left')
            ->where('m.id', $id)
            ->groupBy('m.id')
            ->get()->getRowArray() ?: null;
    }
}

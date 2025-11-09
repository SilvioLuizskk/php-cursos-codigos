<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface
{
    public function all();
    public function find($id);
    public function findBySlug($slug);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getFiltered(array $filters);
    public function getFeatured();
    public function search($query);
    public function updateStock($id, $quantity);
}

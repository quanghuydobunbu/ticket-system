<?php

namespace App\Repositories;

use App\Models\Category;

interface CategoryRepositoryInterface{
    public function create(array $data);

    public function update(Category $category, array $data);

    public function delete(Category $category);

    public function findById($id);

    public function getAllCategory();
}

class CategoryRepository implements CategoryRepositoryInterface{
    public function create(array $data){
        
        return Category::create($data);
    }

    public function update(Category $category, array $data){
        $category->update($data);
        return $category->fresh();
    }

    public function delete(Category $category){
        return $category->delete();
    }

    public function findById($id){
        return Category::find($id);
    }

    public function getAllCategory(){
        return Category::all();
    }
}
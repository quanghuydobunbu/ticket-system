<?php
namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoryService{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository){
        $this->categoryRepository = $categoryRepository;
    }
    public function createCategory(array $data){
        $categoryData = [
            'name' => $data['name'],
            'slug' => $data['slug']
        ];
        if (isset($data['status'])) {
                $isActive = ($data['status'] == 1);
                $categoryData['is_active'] = $isActive ? 1 : 0;
            }
        $category = $this->categoryRepository->create($categoryData);
        return $category;
    }

    public function updateCategory(Category $category, array $data){
        $categoryData = [
            'name' => $data['name'],
            'slug' => $data['slug']
        ];

        if(isset($data['name'])){
            $categoryData['name'] = $data['name'];
        }

        if(isset($data['slug'])){
            $categoryData['slug'] = $data['slug'];
        }

        if (isset($data['status'])) {
            $categoryData['is_active'] = ($data['status'] == 1) ? 1 : 0;
        }

        $updateCategory = $this->categoryRepository->update($category,$categoryData);
        return $updateCategory;
    }

    public function deleteCategory($id){
        $category = $this->categoryRepository->findById($id);
        if(!$category){
            return false;
        }
        $deleted  = $this->categoryRepository->delete($category);
        return $deleted;
    }

    public function findCategory($id){
        return $this->categoryRepository->findById($id);
    }

    public function getAllCategory(){
        return $this->categoryRepository->getAllCategory();
    }
}
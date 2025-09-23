<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;
    public function __construct(CategoryService $categoryService){
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $categories = $this->categoryService->getAllCategory();
        return view('admin.category.index')->with('categories', $categories);
    }
    public function create()
    {
        return view('admin.category.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'status' => 'required|boolean',
        ],[
            'name.required' => 'Tên danh mục không được để trống',
            'slug.required' => 'Tên slug không được để trống'
        ]);

        $this->categoryService->createCategory($validated);
        return redirect()->route('categories.index');
    }
    public function show(string $id)
    {
        $category = $this->categoryService->findCategory($id);
        if($category){
            return view('admin.category.detail')->with('category', $category);
        }
    }
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit')->with('category',$category);
    }
    public function update(Request $request, string $id)
    {
        $category = $this->categoryService->findCategory($id);
        $update_data = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'status' => 'required|boolean',
        ]);

        $this->categoryService->updateCategory($category,$update_data);
        return redirect()->route('categories.index');
    }
    public function destroy(string $id)
    {
        $deleted = $this->categoryService->deleteCategory($id);
        if ($deleted) {
                return redirect()->route('categories.index')
                    ->with('success', 'Category deleted successfully!');
        } else {
                return redirect()->route('categories.index')
                    ->with('error', 'Category not found or cannot be deleted!');
        }
    }
}

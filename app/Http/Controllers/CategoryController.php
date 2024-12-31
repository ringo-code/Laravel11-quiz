<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * カテゴリー一覧を表示
     */
    public function top()
    {
        // カテゴリー一覧を取得
        $categories = Category::get();

        return view('admin.top', [
            'categories' => $categories,
        ]);
    }

    /**
     * カテゴリー新規登録画面を表示
     */
    public function create()
    {
        // 新規登録画面を表示
        return view('admin.categories.create');
    }

    /**
     * カテゴリーを新規登録
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return redirect()->route('admin.top');
    }

    /**
     * カテゴリー詳細＆クイズ一覧を表示
     */
    public function show(Request $request, int $categoryId)
    {
        $category = Category::with('quizzes')->findOrFail($categoryId);

        return view('admin.categories.show', [
            'category' => $category,
            'quizzes'  => $category->quizzes,
        ]);
    }

    /**
     * カテゴリー編集画面を表示
     */
    public function edit(Request $request, int $categoryId)
    {
        $category = Category::find($categoryId);

        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * カテゴリー情報を更新
     */
    public function update(UpdateCategoryRequest $request, int $categoryId)
    {
        $category = Category::find($categoryId);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return redirect()->route('admin.categories.show', ['categoryId' => $categoryId]);
    }

    /**
     * カテゴリーを削除
     */
    public function destroy(Category $category, int $categoryId)
    {
        $category = Category::find($categoryId);
        $category->delete();

        return redirect()->route('admin.top');
    }
}

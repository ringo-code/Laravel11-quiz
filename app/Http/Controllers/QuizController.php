<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Models\Quiz;
use App\Models\Option;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * クイズ新規登録画面を表示
     */
    public function create(Request $request, int $categoryId)
    {
        return view('admin.quizzes.create', [
            'categoryId' => $categoryId,
        ]);

    }

    /**
     * クイズを新規登録
     */
    public function store(StoreQuizRequest $request, int $categoryId)
    {
        // 先にクイズを登録
        $quiz = new Quiz();
        $quiz->category_id = $request->categoryId;
        $quiz->question = $request->question;
        $quiz->explanation = $request->explanation;
        $quiz->save();

        // クイズIDを基に選択肢を登録
        $options = [
            ['quiz_id' => $quiz->id, 'content' => $request->content1, 'is_correct' => $request->isCorrect1],
            ['quiz_id' => $quiz->id, 'content' => $request->content2, 'is_correct' => $request->isCorrect2],
            ['quiz_id' => $quiz->id, 'content' => $request->content3, 'is_correct' => $request->isCorrect3],
            ['quiz_id' => $quiz->id, 'content' => $request->content4, 'is_correct' => $request->isCorrect4],
        ];

        foreach ($options as $option) {
            $newOption = new Option();
            $newOption->quiz_id    = $option['quiz_id'];
            $newOption->content    = $option['content'];
            $newOption->is_correct = $option['is_correct'];
            $newOption->save();
        }

        return redirect()->route('admin.categories.show', ['categoryId' => $categoryId]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        //
    }

    /**
     * クイズ編集画面を表示
     */
    public function edit(Request $request, int $categoryId, int $quizId)
    {
        $quiz = Quiz::with('options')->findOrFail($quizId);

        return view('admin.quizzes.edit', [
            'category' => $quiz->category,
            'quiz'     => $quiz,
            'options'  => $quiz->options,
        ]);
    }

    /**
     * クイズを更新
     */
    public function update(UpdateQuizRequest $request, int $categoryId, int $quizId)
    {
        // クイズを更新
        $quiz = Quiz::find($quizId);
        $quiz->question    = $request->question;
        $quiz->explanation = $request->explanation;
        $quiz->save();

        // 選択肢の更新
        $options = [
            ['id' => $request->optionId1, 'content' => $request->content1, 'is_correct' => $request->isCorrect1],
            ['id' => $request->optionId2, 'content' => $request->content2, 'is_correct' => $request->isCorrect2],
            ['id' => $request->optionId3, 'content' => $request->content3, 'is_correct' => $request->isCorrect3],
            ['id' => $request->optionId4, 'content' => $request->content4, 'is_correct' => $request->isCorrect4],
        ];

        foreach ($options as $option) {
            $UpdateOption = Option::findOrFail($option['id']);
            $UpdateOption->content    = $option['content'];
            $UpdateOption->is_correct = $option['is_correct'];
            $UpdateOption->save();
        }

        // カテゴリー詳細画面にリダイレクト
        return redirect()->route('admin.categories.show', ['categoryId' => $categoryId]);
    }

    /**
     * クイズを削除
     */
    public function destroy(Request $request, int $categoryId, int $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->delete();

        // カテゴリー詳細画面にリダイレクト
        return redirect()->route('admin.categories.show', ['categoryId' => $categoryId]);
    }
}

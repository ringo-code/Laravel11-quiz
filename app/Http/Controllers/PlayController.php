<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class PlayController extends Controller
{
    /**
     * プレイヤートップ画面を表示
     */
    public function top()
    {
        // カテゴリーを取得
        $categories = Category::all();

        return view('play.top', [
            'categories' => $categories,
        ]);
    }

    /**
     * スタート画面を表示
     */
    public function categories(Request $request, int $categoryId)
    {
        // セッションの削除
        session()->forget('resultArray');

        $category = Category::withCount('quizzes')->findOrFail($categoryId);

        return view('play.start', [
            'category' => $category,
            'quizzesCount' => $category->quizzes_count,
        ]);
    }

    /**
     * クイズ出題画面を表示
     */
    public function quizzes(Request $request, int $categoryId)
    {
        // カテゴリーに紐づくクイズとその選択肢を取得
        $category = Category::with('quizzes.options')->findOrFail($categoryId);

        // セッションからクイズIDと解答情報を取得
        $resultArray = session('resultArray');

        // 初回アクセス時はセッションに保存されたクイズのIDの配列を作成
        if (is_null($resultArray)) {
            $resultArray = $this->setResultArraySession($category);
            session(['resultArray' => $resultArray]);
        }

        // $resultArrayの中でresultがnullのもののうち、最初のデータを選ぶ
        $noAnswerResult = collect($resultArray)->filter(function ($item) {
            return $item['result'] === null;
        })->first();

        if (!$noAnswerResult) {
            // 全てのクイズに解答したらリザルト画面にリダイレクト
            return redirect()->route('categories.quizzes.result', $categoryId);
        }

        $quiz = $category->quizzes->firstWhere('id', $noAnswerResult['id'])->toArray();

        return view('play.quizzes', [
            'quiz' => $quiz,
            'category' => $category,
        ]);
    }

    /**
     * セッションにクイズIDと解答情報を保存
     */
    private function setResultArraySession(Category $category)
    {
        // クイズIDを全て抽出
        $quizIds = $category->quizzes->pluck('id')->toArray();
        // クイズIDの配列をシャッフル
        shuffle($quizIds);
        $resultArray = [];
        foreach ($quizIds as $quizId) {
            $resultArray[] = [
                'id' => $quizId,
                'result' => null,
            ];
        }
        return $resultArray;
    }

    /**
     * クイズ解答画面を表示
     */
    public function answer(Request $request, int $categoryId)
    {
        // カテゴリーに紐づくクイズとその選択肢を取得
        $category = Category::with('quizzes.options')->findOrFail($categoryId);

        $quizId          = $request->quizId;
        $selectedOptions = $request->optionId === null ? [] : $request->optionId;
        $quiz            = $category->quizzes->firstWhere('id', $quizId);
        $quizOptions     = $quiz->options->toArray();
        $isCorrectAnswer = $this->isCorrectAnswer($selectedOptions, $quizOptions);

        // セッションからクイズIDと解答情報を取得
        $resultArray = session('resultArray');

        foreach ($resultArray as $index => $result) {
            if ($result['id'] === (int)$quizId) {
                $resultArray[$index]['result'] = $isCorrectAnswer;
                break;
            }
        }

        // 解答結果をセッションに保存
        session(['resultArray' => $resultArray]);


        return view('play.answer', [
            'categoryId'      => $categoryId,
            'quiz'            => $quiz->toArray(),
            'quizOptions'     => $quizOptions,
            'selectedOptions' => $selectedOptions,
            'isCorrectAnswer' => $isCorrectAnswer,
        ]);
    }

    /**
     * 回答が正しいか判定
     */
    private function isCorrectAnswer(array $selectedOptions, array $quizOptions)
    {
        // クイズの選択肢から正解の選択肢を抽出し、全て取得
        $correctOptions = array_filter($quizOptions, function ($option) {
            return $option['is_correct'] === 1;
        });

        // IDの数字だけを抽出
        $correctOptionIds = array_map(function ($option) {
            return $option['id'];
        }, $correctOptions);

        // プレイヤーが選んだ選択肢の個数と正解の選択肢の個数が一致するか判定
        if (count($selectedOptions) !== count($correctOptionIds)) {
            return false;
        }

        // プレイヤーが選んだ選択肢のIDと正解のIDが全て一致することを判定
        foreach ($selectedOptions as $selectedOption) {
            if (!in_array($selectedOption, $correctOptionIds)) {
                return false;
            }
        }

        // 正解であることを返す
        return true;
    }

    /**
     * リザルト画面を表示
     */
    public function result(Request $request, int $categoryId)
    {
        // カテゴリーに紐づくクイズとその選択肢を取得
        // $category = Category::with('quizzes.options')->findOrFail($categoryId);

        // セッションからクイズIDと解答情報を取得
        $resultArray = session('resultArray');
        $questionCount = count($resultArray);
        // 正解数をカウント
        $correctCount = collect($resultArray)->filter(function ($item) {
            return $item['result'] === true;
        })->count();

        return view('play.result', [
            'categoryId'     => $categoryId,
            'questionCount' => $questionCount,
            'correctCount' => $correctCount,
        ]);
    }
}

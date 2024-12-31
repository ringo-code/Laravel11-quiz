<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    /**
     * CategoryモデルとQuizモデルの一対多のリレーション
     *
     * @return  App\Models\Category  Quizに紐づくカテゴリー
     *
     */
    public function category()
    {
        // Categoryモデルに従属する。
        return $this->belongsTo(Category::class);
    }

    /**
     * QuizモデルとOptionモデルの一対多のリレーション
     *
     * @return  App\Models\Option  クイズに紐づく選択肢
     *
     */
    public function options()
    {
	    // Optionモデルを多数持つ。
        return $this->hasMany(Option::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * CategoryモデルとQuizモデルの一対多のリレーション
     *
     * @return  App\Models\Quiz  クイズに紐づく選択肢
     *
     */
    public function quizzes()
    {
	    // Quizモデルを多数持つ。
        return $this->hasMany(Quiz::class);
    }
}

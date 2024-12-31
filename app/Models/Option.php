<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    /**
     * QuizモデルとOptionモデルの一対多のリレーション
     *
     * @return  App\Models\Quiz  Optionに紐づくクイズ
     *
     */
    public function quiz()
    {
        // Quizモデルに従属する。
        // 第一引数：親テーブル、第2引数：子テーブルの外部キー、第3引数：親テーブルの主キー
        return $this->belongsTo(Quiz::class);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                    ->constrained()       // 外部キー制約
                    ->onUpdete('cascade') // 親テーブルの更新時にも更新
                    ->onDelete('cascade') // 親テーブルの削除時にも削除
                    ->comment('カテゴリーID');
            $table->text('question')->comment('問題文');
            $table->text('explanation')->comment('解説');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};

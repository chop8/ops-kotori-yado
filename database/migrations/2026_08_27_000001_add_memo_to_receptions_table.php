<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('receptions', function (Blueprint $table) {
            // すでに存在しない場合のみ追加
            if (!Schema::hasColumn('receptions', 'memo')) {
                $table->text('memo')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('receptions', function (Blueprint $table) {
            $table->dropColumn('memo');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsLrcToTorrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('torrents', function (Blueprint $table): void {
            $table->boolean('is_lrc')->default(0); // 添加 is_lrc 字段，默认值为 0
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('torrents', function (Blueprint $table): void {
            $table->dropColumn('is_lrc'); // 移除 is_lrc 字段
        });
    }
}

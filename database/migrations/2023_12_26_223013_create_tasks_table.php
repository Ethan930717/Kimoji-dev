<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * 运行迁移。
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 发布人ID，外键关联到 users 表
            $table->foreignId('assignee_id')->nullable()->constrained('users')->onDelete('set null'); // 接收人ID，外键关联到 users 表，可为空
            $table->string('title'); // 任务标题
            $table->text('description'); // 任务内容
            $table->integer('deadline'); // 任务期限（以天为单位）
            $table->decimal('reward', 10, 2); // 任务赏金
            $table->enum('status', ['open', 'assigned', 'pending_review', 'completed', 'cancelled'])->default('open'); // 任务状态
            $table->boolean('is_anon')->default(false); // 是否匿名
            $table->timestamp('completed_at')->nullable(); // 完成时间
            $table->string('review_reason')->nullable(); // 审核/拒绝理由
            $table->string('cancellation_reason')->nullable(); // 任务取消原因
            $table->timestamps(); // 创建时间和更新时间
        });
    }

    /**
     * 回滚迁移。
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function (Blueprint $table) {
			$table->id();
			$table->string('action');
			$table->string('action_from');
			$table->string('avatar');
			$table->foreignId('user_id')->constrained()->cascadeOnDelete();
			$table->foreignId('quote_id')->constrained()->cascadeOnDelete();
			$table->foreignId('like_id')->nullable()->constrained()->cascadeOnDelete();
			$table->foreignId('comment_id')->nullable()->constrained()->cascadeOnDelete();
			$table->string('notification_phase')->nullable();
			$table->timestamp('created_date');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('notifications');
	}
};

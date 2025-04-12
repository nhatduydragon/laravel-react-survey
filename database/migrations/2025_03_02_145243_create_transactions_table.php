<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('transfer_id');
            $table->decimal('amount', 16, 4);
            $table->decimal('balance', 16, 4);
            $table->string('category'); // deposit, withdrawal
            $table->boolean('confirmed');
            $table->string('description')->nullable();
            $table->dateTime('date');
            $table->text('metal')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('transactions');
    }
};

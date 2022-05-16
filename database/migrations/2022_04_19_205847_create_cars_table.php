<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('created_by');
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('rent')->nullable();
            $table->bigInteger('seat')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->string('status')->default(1)->comment('0=inactive, 1= active');
            $table->string('deleted_at')->nullable();
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
        Schema::dropIfExists('rentals');
    }
}

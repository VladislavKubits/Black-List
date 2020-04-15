<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');

            $table->string('car')->nullable();
            $table->string('region')->nullable();
            $table->string('initials')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('pasport_data')->nullable();
            $table->string('receipt_info')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('card')->nullable();
            $table->text('notation')->nullable();
            $table->string('status')->default('новый');
            $table->boolean('NDS')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people');
    }
}

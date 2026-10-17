<?php

use Illuminate\Database\Migrations\MigratesSchemaUp;
use Illuminate\Database\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreatePlaceApprovalsTable extends Migration
{
    protected $table = 'place_approvals';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->unsignedBigInteger('place_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('status', ['approved', 'rejected'])->default('pending');
            $table->timestamps();
            $table->foreign('place_id')->references('id')->on('places');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }

}

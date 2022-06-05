<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubMgsIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_mgs_ids', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status', ['pending', 'delivered', 'failed'])->default('pending');
            $table->unsignedInteger('subscriber_id')->nullable();
            $table->unsignedInteger('message_id')->nullable();
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
        Schema::dropIfExists('sub_mgs_ids');
    }
}

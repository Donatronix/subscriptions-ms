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
            $table->bigIncrements('id');
            $table->enum('status', ['pending', 'delivered', 'failed'])->default('pending');
            $table->text('subscriber_ids');
            $table->string('message_id', 50);
            $table->string('failed_message', 100);
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->enum('status',['new','in_progress','done'])->default('new');
            $table->string('cancel_reason')->nullable();
            //record,write
            $table->foreignId('type_id')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('provider_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->integer('price')->default(0);
            $table->boolean('paid')->default(0);
            $table->json('more_details')->nullable();
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
        Schema::dropIfExists('orders');
    }
}

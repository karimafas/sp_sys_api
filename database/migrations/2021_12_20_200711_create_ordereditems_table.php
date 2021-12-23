<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdereditemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordered_products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('orders')->on('id')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('products')->on('id')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('ordered_variations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('orders')->on('id')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('ordered_product_id');
            $table->foreign('ordered_product_id')->references('products')->on('id')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('variation_id');
            $table->foreign('variation_id')->references('variations')->on('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordered_items');
        Schema::dropIfExists('ordered_variations');
    }
}

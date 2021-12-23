<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;

class CustomersNullability extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }

        Schema::table('customers', function (Blueprint $table) {
            $table->text('address')->nullable()->change();
            $table->integer('address2')->nullable()->change();
            $table->text('area')->nullable()->change();
            $table->double('credit')->nullable()->change();
            $table->double('discount')->nullable()->change();
            $table->text('last_name')->nullable()->change();
            $table->integer('level')->nullable()->change();
            $table->text('notes')->nullable()->change();
            $table->integer('phone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

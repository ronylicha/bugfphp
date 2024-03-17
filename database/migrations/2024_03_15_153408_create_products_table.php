<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('qte')->nullable();
            $table->float('value')->nullable();
            $table->float('com_to_pay')->nullable();
            $table->boolean('com_payed')->nullable();
            $table->timestamp('date_com_payed')->nullable();
            $table->float('com_to_cancel')->nullable();
            $table->boolean('com_cancel')->nullable();
            $table->timestamp('date_com_cancel')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};

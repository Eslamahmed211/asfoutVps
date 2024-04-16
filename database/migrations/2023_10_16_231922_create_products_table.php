<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();


            $table->string('name');

            $table->string('slug');
            $table->text('dis')->nullable();

            $table->float('price');
            $table->string('sku');
            $table->integer('stock')->nullable();

            $table->enum('show' , [0 , 1])->default(0);



            $table->foreignId("trader_id")->nullable()->constrained("users")->onDelete("set null");
            $table->float("comissation")->nullable();;
            $table->float("min_comissation")->nullable();
            $table->float("max_comissation")->nullable();
            $table->float("ponus")->nullable();
            $table->float("systemComissation")->nullable();

            $table->enum("unavailable" , ["yes" , 'no'])->default('no');

            $table->string("drive")->nullable();

            $table->string("nickName");

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

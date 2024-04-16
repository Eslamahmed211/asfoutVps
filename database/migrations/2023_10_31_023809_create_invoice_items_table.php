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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("invoice_id")->constrained("invoices")->onDelete("cascade");
            $table->dateTime("date");
            $table->string("product_name");
            $table->string("variants")->nullable();
            $table->float("price");
            $table->integer("qnt");
            $table->float("total");
            $table->foreignId("product_id")->nullable()->constrained("products")->onDelete('set null');
            $table->foreignId("variant_id")->nullable()->constrained("variants")->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};

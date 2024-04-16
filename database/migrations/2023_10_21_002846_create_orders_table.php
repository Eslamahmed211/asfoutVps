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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId("user_id")->nullable()->constrained('users')->onDelete("set null");
            $table->string('reference');
            $table->string('trackingNumber')->nullable();

            $table->enum('status', [
                "قيد الانتظار",
                "قيد المراجعة",
                "محاولة تانية",
                "تم الالغاء" ,
                "تم المراجعة",
                "جاهز للتغليف",
                "جاري التجهيز للشحن",
                "تم ارسال الشحن",
                "تم التوصيل",
                "مكتمل",
                'طلب استرجاع',
                "فشل التوصيل",
            ]);

            $table->integer('get')->nullable();
            $table->integer('take')->nullable();

            $table->foreignId('postMan_id')->nullable()->constrained('users')->onDelete("set null");

            $table->string("clientName");



            $table->string("clientPhone");
            $table->string("clientPhone2")->nullable();
            $table->string("city");
            $table->text("address");


            $table->string('reasons')->nullable();
            $table->string('Waitingdate')->nullable();

            $table->float("delivery_price");
            $table->float("return_price");
            $table->float("bosta_delivery_price");
            $table->float("bosta_return_price");

            $table->string('bosta_id')->nullable();
            $table->dateTime('delivery_at')->nullable();

            $table->string("page")->nullable();
            $table->text("notes")->nullable();;
            $table->text("notesBosta")->nullable();;
            $table->json("logs")->nullable()->default("[]");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

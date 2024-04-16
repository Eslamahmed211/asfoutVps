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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->float('wallet')->default(0);
            $table->string('mobile')->nullable();
            $table->enum('role', ['admin','trader', 'user', 'postman', 'moderator'])->default('user');


            $table->foreignId('marketer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('active')->default(0);
            $table->string('city')->nullable();


            $table->string('state')->nullable();
            $table->string('address')->nullable();
            $table->json('permissions')->nullable()->default("[]");
            $table->json("notification_settings")->nullable()->default('["قيد الانتظار", "قيد المراجعة", "تم المراجعة", "محاولة تانية", "تم الالغاء", "جاهز للتغليف", "جاري التجهيز للشحن", "تم ارسال الشحن", "تم التوصيل", "فشل التوصيل", "مكتمل"]');
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

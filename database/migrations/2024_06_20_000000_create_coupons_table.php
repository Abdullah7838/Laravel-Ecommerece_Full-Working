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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type'); // percentage, fixed
            $table->decimal('value', 10, 2); // discount value
            $table->decimal('min_order_amount', 10, 2)->nullable(); // minimum order amount to apply coupon
            $table->integer('max_uses')->nullable(); // maximum number of times coupon can be used
            $table->integer('used_count')->default(0); // number of times coupon has been used
            $table->dateTime('starts_at')->nullable(); // when coupon becomes valid
            $table->dateTime('expires_at')->nullable(); // when coupon expires
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->date('quotation_date')->nullable();
            $table->time('quotation_time')->nullable();
            $table->date('validity_date')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->boolean('is_completed')->default(0);
            $table->json('product_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};

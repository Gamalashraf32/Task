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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number');
            $table->string('warehouse_name');
            $table->unsignedBigInteger('expense_category_id');
            $table->foreign('expense_category_id')->references('id')->on('expenses_categories')->onDelete('cascade')->onUpdate('cascade');

            $table->double('amount');
            $table->text('note')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

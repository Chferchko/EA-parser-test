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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('last_change_date');
            $table->string('supplier_article')->index();
            $table->string('tech_size')->nullable();
            $table->unsignedBigInteger('barcode')->unique();
            $table->integer('quantity');
            $table->boolean('is_supply');
            $table->boolean('is_realization');
            $table->integer('quantity_full');
            $table->string('warehouse_name')->index();
            $table->integer('in_way_to_client');
            $table->integer('in_way_from_client');
            $table->unsignedBigInteger('nm_id')->index();
            $table->string('subject')->index();
            $table->string('category')->index();
            $table->string('brand')->index();
            $table->unsignedBigInteger('sc_code')->index();
            $table->unsignedInteger('price');
            $table->unsignedSmallInteger('discount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};

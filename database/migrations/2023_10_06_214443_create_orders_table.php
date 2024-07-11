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
            // dates
            $table->date('date_ordered')->nullable();
            $table->date('date_sent')->nullable();
            $table->date('date_deadline')->nullable();
            $table->date('date_delivered')->nullable();
            // has foreign key
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('source_id')->unsigned();
            $table->bigInteger('delivery_service_id')->unsigned();
            $table->bigInteger('payment_type_id')->unsigned();
            $table->bigInteger('delivery_country_id')->unsigned();
            // other order stuff
            $table->char('tracking_code', 100)->nullable();
            $table->char('delivery_address', 100)->nullable();
            $table->char('delivery_city', 100)->nullable();
            $table->char('delivery_postal', 100)->nullable();
            $table->char('delivery_phone', 100)->nullable();
            $table->char('delivery_email', 100)->nullable();
            // timestamps
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('source_id')->references('id')->on('sources')->onDelete('cascade');
            $table->foreign('delivery_service_id')->references('id')->on('delivery_service')->onDelete('cascade');
            $table->foreign('payment_type_id')->references('id')->on('payment_type')->onDelete('cascade');
            $table->foreign('delivery_country_id')->references('id')->on('country')->onDelete('cascade');
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

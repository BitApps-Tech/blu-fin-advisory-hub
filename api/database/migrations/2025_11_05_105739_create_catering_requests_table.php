<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCateringRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catering_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_code')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('event_type')->nullable(); // wedding, birthday, corporate, etc.
            $table->dateTime('event_date');
            $table->string('event_location');
            $table->integer('guest_count');
            $table->text('menu_preferences')->nullable();
            $table->text('special_requirements')->nullable();
            $table->decimal('estimated_budget', 10, 2)->nullable();
            $table->decimal('quoted_price', 10, 2)->nullable();
            $table->string('status')->default('pending'); // pending, quoted, confirmed, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catering_requests');
    }
}

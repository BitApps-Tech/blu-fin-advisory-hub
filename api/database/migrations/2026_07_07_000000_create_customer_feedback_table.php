<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFeedbackTable extends Migration
{
    public function up(): void
    {
        Schema::create('customer_feedback', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->nullable();
            $table->date('visit_date');
            $table->text('customer_order')->nullable();
            $table->unsignedTinyInteger('food_taste');
            $table->unsignedTinyInteger('food_presentation');
            $table->unsignedTinyInteger('food_freshness');
            $table->unsignedTinyInteger('food_portion_size');
            $table->unsignedTinyInteger('service_friendliness');
            $table->unsignedTinyInteger('service_speed');
            $table->unsignedTinyInteger('service_accuracy');
            $table->unsignedTinyInteger('service_attentiveness');
            $table->unsignedTinyInteger('environment_cleanliness');
            $table->unsignedTinyInteger('environment_ambiance');
            $table->unsignedTinyInteger('environment_comfort');
            $table->text('comments')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->foreignId('read_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('visit_date');
            $table->index('is_read');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_feedback');
    }
}

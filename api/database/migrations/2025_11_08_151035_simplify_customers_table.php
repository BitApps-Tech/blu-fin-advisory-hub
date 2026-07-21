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
        Schema::table('customers', function (Blueprint $table) {
            // Drop unnecessary columns
            $table->dropColumn([
                'email',
                'address',
                'date_of_birth',
                'customer_type',
                'total_orders',
                'total_spent',
                'loyalty_points',
                'notes',
                'is_active',
            ]);
            
            // Make name nullable
            $table->string('name')->nullable()->change();
            
            // Add soft deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Re-add dropped columns
            $table->string('email')->unique()->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('customer_type')->default('regular');
            $table->integer('total_orders')->default(0);
            $table->decimal('total_spent', 10, 2)->default(0);
            $table->integer('loyalty_points')->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Make name required again
            $table->string('name')->nullable(false)->change();
            
            // Drop soft deletes
            $table->dropSoftDeletes();
        });
    }
};

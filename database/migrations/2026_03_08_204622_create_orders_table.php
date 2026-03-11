<?php

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Warehouse;
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
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->date('date')->default(now());
            $table->string('order_number')->unique();
            $table->foreignIdFor(Customer::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Driver::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Warehouse::class)->nullable()->constrained()->nullOnDelete();
            $table->string('car_number');

            $table->decimal('gasoline', 10, 2)->nullable(); // amount
            $table->decimal('benzin', 10, 2)->nullable(); // amount
            $table->decimal('fleet', 10, 2)->nullable();
            $table->decimal('transit', 10, 2)->nullable();
            $table->decimal('window_fee', 10, 2)->nullable();
            $table->decimal('manfisto', 10, 2)->nullable();
            $table->decimal('freightage', 10, 2)->nullable(); // النولون
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('commission', 10, 2)->nullable();
            $table->decimal('amount', 10, 2);

            $table->string('company')->nullable();
            $table->string('destination')->nullable();
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

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('pickup_date');
            $table->time('pickup_time')->nullable();
            $table->enum('delivery_type', ['pickup', 'delivery']);
            $table->text('delivery_address')->nullable();
            $table->enum('status', ['pending', 'processing', 'production', 'ready', 'completed', 'cancelled'])->default('pending');
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
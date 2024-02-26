<?php

use App\Enums\InquiryStatus;
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
        Schema::create('demo_test_inquiry', function (Blueprint $table) {
            $table->id();
            $table->json('payload');
            $table->enum('status', [
                InquiryStatus::Active->value,
                InquiryStatus::Processed->value,
                InquiryStatus::Failed->value
            ])->default(InquiryStatus::Active->value);
            $table->unsignedInteger('items_total_count')->nullable();
            $table->unsignedInteger('items_processed_count')->default(0);
            $table->unsignedInteger('items_failed_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_test_inquiry');
    }
};

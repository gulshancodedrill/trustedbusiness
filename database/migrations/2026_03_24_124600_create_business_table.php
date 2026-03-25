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
        Schema::create('business', function (Blueprint $table) {
            $table->id();
            $table->string('owner_first_name');
            $table->string('owner_last_name');
            $table->string('contact_number');
            $table->string('business_name');
            $table->string('business_email');
            $table->string('business_contact_number');
            $table->string('website')->nullable();
            $table->text('business_description')->nullable();
            $table->string('business_logo')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('pincode', 20);
            $table->string('address_line_1');

            $table->foreignId('industry_id')->constrained('industries')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();

            // Store multiple tags as JSON array.
            $table->json('tags')->nullable();
            $table->string('hear_from', 50)->nullable();

            $table->string('sunday_timing')->nullable();
            $table->string('monday_timing')->nullable();
            $table->string('tuesday_timing')->nullable();
            $table->string('wednesday_timing')->nullable();
            $table->string('thursday_timing')->nullable();
            $table->string('friday_timing')->nullable();
            $table->string('saturday_timing')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('industry_id')->constrained('industries')->cascadeOnDelete();
                $table->timestamps();
            });

            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            if (! Schema::hasColumn('categories', 'name')) {
                $table->string('name');
            }

            if (! Schema::hasColumn('categories', 'description')) {
                $table->text('description')->nullable();
            }

            if (! Schema::hasColumn('categories', 'industry_id')) {
                $table->foreignId('industry_id')->nullable()->after('description');
            }
        });

        $hasIndustryFk = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'categories')
            ->where('COLUMN_NAME', 'industry_id')
            ->where('REFERENCED_TABLE_NAME', 'industries')
            ->exists();

        if (! $hasIndustryFk) {
            Schema::table('categories', function (Blueprint $table) {
                $table->foreign('industry_id')
                    ->references('id')
                    ->on('industries')
                    ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

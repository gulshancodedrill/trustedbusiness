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
        if (! Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
                $table->timestamps();
            });

            return;
        }

        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'name')) {
                $table->string('name');
            }

            if (! Schema::hasColumn('services', 'description')) {
                $table->text('description')->nullable();
            }

            if (! Schema::hasColumn('services', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('description');
            }
        });

        $hasCategoryFk = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'services')
            ->where('COLUMN_NAME', 'category_id')
            ->where('REFERENCED_TABLE_NAME', 'categories')
            ->exists();

        if (! $hasCategoryFk) {
            Schema::table('services', function (Blueprint $table) {
                $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

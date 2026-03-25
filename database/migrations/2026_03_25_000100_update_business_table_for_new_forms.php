<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business', function (Blueprint $table) {
            // Old owner name fields are replaced by `business_name` + later `owner_id` claiming.
            if (Schema::hasColumn('business', 'owner_first_name')) {
                $table->dropColumn('owner_first_name');
            }
            if (Schema::hasColumn('business', 'owner_last_name')) {
                $table->dropColumn('owner_last_name');
            }

            // Added for the future business claim feature (kept NULL for now).
            if (! Schema::hasColumn('business', 'owner_id')) {
                $table->foreignId('owner_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('business', 'contact_person')) {
                $table->string('contact_person')->nullable();
            }

            if (! Schema::hasColumn('business', 'hide_address')) {
                $table->boolean('hide_address')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('business', function (Blueprint $table) {
            if (! Schema::hasColumn('business', 'owner_first_name')) {
                $table->string('owner_first_name');
            }
            if (! Schema::hasColumn('business', 'owner_last_name')) {
                $table->string('owner_last_name');
            }

            if (Schema::hasColumn('business', 'owner_id')) {
                $table->dropForeign(['owner_id']);
                $table->dropColumn('owner_id');
            }

            if (Schema::hasColumn('business', 'contact_person')) {
                $table->dropColumn('contact_person');
            }
            if (Schema::hasColumn('business', 'hide_address')) {
                $table->dropColumn('hide_address');
            }
        });
    }
};


<?php
// Baseline migration — adopts existing smartkos tables into Laravel migration history.
// SAFE: uses Schema::hasTable() guards so it never recreates existing tables.
// Run: php artisan migrate  (will record all missing entries)

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adopt the existing schema produced outside Laravel's migration system.
     * Each block is wrapped in hasTable() so it is a no-op if the table already exists.
     * New fresh installs (e.g. on Supabase) will have the tables created from scratch.
     */
    public function up(): void
    {
        // --- users (extends default with role columns) ---
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('role')->default('tenant'); // admin | super_admin | caretaker | tenant
                $table->timestamp('approved_at')->nullable();
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->boolean('is_active')->default(true);
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        } elseif (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('tenant')->after('email');
                $table->timestamp('approved_at')->nullable()->after('role');
                $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
                $table->boolean('is_active')->default(true)->after('approved_by');
            });
        }

        // --- password_reset_tokens ---
        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // --- sessions ---
        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // --- cache ---
        if (! Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }
        if (! Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }

        // --- jobs ---
        if (! Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }
        if (! Schema::hasTable('job_batches')) {
            Schema::create('job_batches', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('name');
                $table->integer('total_jobs');
                $table->integer('pending_jobs');
                $table->integer('failed_jobs');
                $table->longText('failed_job_ids');
                $table->mediumText('options')->nullable();
                $table->integer('cancelled_at')->nullable();
                $table->integer('created_at');
                $table->integer('finished_at')->nullable();
            });
        }
        if (! Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        // --- rooms ---
        if (! Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->string('room_number')->unique();
                $table->string('type')->default('standard'); // standard | deluxe | vip
                $table->unsignedInteger('price');
                $table->integer('size')->nullable(); // m2
                $table->text('description')->nullable();
                $table->json('facilities')->nullable();
                $table->boolean('is_available')->default(true);
                $table->timestamps();
            });
        }

        // --- tenants ---
        if (! Schema::hasTable('tenants')) {
            Schema::create('tenants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
                $table->string('full_name');
                $table->string('phone');
                $table->string('id_card_number')->unique();
                $table->text('address');
                $table->string('emergency_contact')->nullable();
                $table->date('check_in_date')->nullable();
                $table->date('contract_start')->nullable();
                $table->date('contract_end')->nullable();
                $table->string('status')->default('pending'); // pending | active | inactive
                $table->timestamps();
            });
        }

        // --- bills ---
        if (! Schema::hasTable('bills')) {
            Schema::create('bills', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->integer('month');
                $table->integer('year');
                $table->integer('room_rental');
                $table->integer('electricity')->default(0);
                $table->integer('water')->default(0);
                $table->integer('wifi')->default(0);
                $table->integer('late_fee')->default(0);
                $table->integer('total_amount');
                $table->integer('paid_amount')->default(0);
                $table->string('status')->default('pending'); // pending | partial | paid | overdue
                $table->date('paid_at')->nullable();
                $table->timestamps();
            });
        }

        // --- complaints ---
        if (! Schema::hasTable('complaints')) {
            Schema::create('complaints', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
                $table->string('title');
                $table->text('description');
                $table->string('category')->default('facility'); // facility | noise | billing | other
                $table->string('priority')->default('medium');   // low | medium | high | urgent
                $table->string('status')->default('pending');    // pending | in_progress | resolved | closed
                $table->text('response')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
            });
        }

        // --- announcements ---
        if (! Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->string('type')->default('general');     // general | payment | maintenance | event
                $table->string('priority')->default('normal');  // low | normal | important | urgent
                $table->timestamp('published_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // --- personal_access_tokens (Laravel Sanctum) ---
        if (! Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->text('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Down intentionally limited — never drop tables that may have production data.
        // Schema drops should be handled via targeted forward migrations only.
        Schema::dropIfExists('personal_access_tokens');
    }
};
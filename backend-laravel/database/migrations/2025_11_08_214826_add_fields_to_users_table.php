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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->enum('role', ['user', 'admin', 'seller'])->default('user')->after('avatar');
            $table->enum('status', ['active', 'inactive', 'suspended', 'deleted'])->default('active')->after('role');
            $table->timestamp('last_login_at')->nullable()->after('password');
            $table->ipAddress('last_login_ip')->nullable()->after('last_login_at');
            $table->json('preferences')->nullable()->after('last_login_ip');
            $table->softDeletes();

            // Índices
            $table->index('role');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'phone', 'avatar', 'role', 'status',
                'last_login_at', 'last_login_ip', 'preferences'
            ]);
            $table->dropIndex(['role']);
            $table->dropIndex(['status']);
        });
    }
};

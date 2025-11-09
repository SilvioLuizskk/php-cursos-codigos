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
        Schema::table('pages', function (Blueprint $table) {
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->boolean('active')->default(true);
            $table->integer('order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('image')->nullable();

            $table->index(['active', 'order']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['active', 'order']);
            $table->dropIndex(['slug']);
            $table->dropColumn([
                'title',
                'slug',
                'content',
                'active',
                'order',
                'meta_title',
                'meta_description',
                'image'
            ]);
        });
    }
};

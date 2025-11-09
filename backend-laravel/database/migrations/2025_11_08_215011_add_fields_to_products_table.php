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
        Schema::table('products', function (Blueprint $table) {
            $table->longText('description')->nullable()->change();
            $table->string('short_description')->nullable()->after('description');
            $table->decimal('discount_price', 10, 2)->nullable()->after('price');
            $table->decimal('cost_price', 10, 2)->nullable()->after('discount_price');
            $table->string('barcode')->nullable()->after('sku');
            $table->integer('low_stock_alert')->default(10)->after('stock');
            $table->decimal('rating', 3, 2)->default(0)->after('low_stock_alert');
            $table->integer('review_count')->default(0)->after('rating');
            $table->json('images')->nullable()->after('image');
            $table->json('customization_options')->nullable()->after('images');
            $table->string('meta_title')->nullable()->after('customization_options');
            $table->string('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('seo_url')->nullable()->after('meta_keywords');

            // Alterar campos existentes
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active')->change();
            $table->boolean('is_active')->default(true)->change();

            // Novos índices
            $table->index('rating');
            $table->index('review_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'short_description', 'discount_price', 'cost_price', 'barcode',
                'low_stock_alert', 'rating', 'review_count', 'images',
                'customization_options', 'meta_title', 'meta_description',
                'meta_keywords', 'seo_url'
            ]);
            $table->dropIndex(['rating']);
            $table->dropIndex(['review_count']);
        });
    }
};

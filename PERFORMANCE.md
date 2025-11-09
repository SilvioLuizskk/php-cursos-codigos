# Cache Configuration for Chinelos Karibe

## Redis Cache Strategy

### 1. Product Cache (TTL: 1 hour)

- Key: `products:list:{page}:{filters}`
- Key: `products:featured`
- Key: `products:categories`
- Key: `product:{id}`

### 2. Cart Cache (TTL: 24 hours)

- Key: `cart:user:{user_id}`
- Key: `cart:session:{session_id}`

### 3. Order Cache (TTL: 30 minutes)

- Key: `order:{id}`
- Key: `orders:user:{user_id}`

### 4. Payment Cache (TTL: 15 minutes)

- Key: `payment:status:{payment_id}`

## Laravel Artisan Commands for Cache

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Queue jobs cache
php artisan queue:work --tries=3 --timeout=90
```

## Database Query Optimization

### Eager Loading Examples:

```php
// Products with categories and reviews
Product::with(['category', 'reviews', 'images'])->get();

// Orders with user and items
Order::with(['user', 'orderItems.product'])->get();

// Cart with products
Cart::with(['user', 'cartItems.product'])->get();
```

### Indexes to Add:

```sql
-- Products table
CREATE INDEX idx_products_category_id ON products(category_id);
CREATE INDEX idx_products_featured ON products(is_featured);
CREATE INDEX idx_products_active ON products(is_active);
CREATE INDEX idx_products_price ON products(price);

-- Orders table
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_created_at ON orders(created_at);

-- Cart items table
CREATE INDEX idx_cart_items_user_id ON cart_items(user_id);
CREATE INDEX idx_cart_items_product_id ON cart_items(product_id);
```

## Frontend Performance

### Vue.js Optimizations:

- Lazy loading for routes
- Image lazy loading with Intersection Observer
- Virtual scrolling for large product lists
- Component caching with keep-alive

### Vite Build Optimizations:

```js
// vite.config.js additions
export default {
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          vendor: ["vue", "vue-router", "pinia"],
          ui: ["@headlessui/vue", "tailwindcss"],
        },
      },
    },
  },
};
```

## CDN Configuration (Future)

### Assets to CDN:

- Product images
- CSS/JS bundles
- Font files
- Static assets

### CloudFlare Settings:

- Cache Level: Standard
- Browser Cache TTL: 1 month
- Edge Cache TTL: 1 week

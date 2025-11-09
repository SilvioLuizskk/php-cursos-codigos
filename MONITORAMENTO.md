# Sistema de Monitoramento - Chinelos Karibe

## 📊 Métricas Importantes

### Performance Metrics:

- Response time médio das APIs
- Throughput (requests/segundo)
- Errors rate (4xx/5xx)
- Database query time
- Cache hit ratio

### Business Metrics:

- Produtos visualizados
- Conversão carrinho → pedido
- Valor médio do pedido
- Taxa de abandono do carrinho
- Produtos mais vendidos

## 🔍 Log Monitoring

### Laravel Logs:

```bash
# Monitorar logs em tempo real
tail -f storage/logs/laravel.log

# Filtrar por nível de erro
grep "ERROR" storage/logs/laravel.log

# Filtrar por data específica
grep "2025-11-07" storage/logs/laravel.log
```

### Structured Logging:

```php
// No PaymentController
Log::info('Payment processed', [
    'user_id' => $user->id,
    'amount' => $amount,
    'method' => $paymentMethod,
    'order_id' => $order->id,
    'gateway' => $gateway
]);

// No ProductController
Log::warning('Low stock alert', [
    'product_id' => $product->id,
    'current_stock' => $product->stock,
    'threshold' => 10
]);
```

## 🚨 Alertas Críticos

### High Priority:

- Payment gateway failures
- Database connection errors
- High error rate (>5%)
- Cart abandonment spike

### Medium Priority:

- Slow query warnings (>2s)
- Low stock alerts
- Failed login attempts

### Nginx Access Logs:

```bash
# Requests mais frequentes
awk '{print $7}' /var/log/nginx/access.log | sort | uniq -c | sort -nr | head -10

# IPs com mais requests
awk '{print $1}' /var/log/nginx/access.log | sort | uniq -c | sort -nr | head -10

# Status codes distribution
awk '{print $9}' /var/log/nginx/access.log | sort | uniq -c | sort -nr
```

## 📈 Performance Monitoring

### Database Queries:

```php
// Enable query log in AppServiceProvider
DB::listen(function ($query) {
    if ($query->time > 1000) { // > 1 second
        Log::warning('Slow query detected', [
            'sql' => $query->sql,
            'time' => $query->time,
            'bindings' => $query->bindings
        ]);
    }
});
```

### Cache Performance:

```php
// Cache hit/miss logging
Cache::extend('logged-redis', function ($app) {
    return new LoggedRedisStore(
        $app['redis'],
        config('database.redis.cache.prefix'),
        'cache'
    );
});
```

## 🔧 Health Checks

### API Health Endpoint:

```php
// Route: GET /api/health
public function health()
{
    $checks = [
        'database' => $this->checkDatabase(),
        'redis' => $this->checkRedis(),
        'storage' => $this->checkStorage(),
        'external_apis' => $this->checkExternalAPIs()
    ];

    $allHealthy = collect($checks)->every(fn($check) => $check['status'] === 'ok');

    return response()->json([
        'status' => $allHealthy ? 'healthy' : 'unhealthy',
        'checks' => $checks,
        'timestamp' => now()
    ], $allHealthy ? 200 : 503);
}
```

### Frontend Monitoring:

```js
// Error tracking
window.addEventListener("error", (event) => {
  console.error("JavaScript Error:", {
    message: event.error.message,
    filename: event.filename,
    lineno: event.lineno,
    colno: event.colno,
    stack: event.error.stack,
  });
});

// Performance tracking
window.addEventListener("load", () => {
  const perfData = performance.getEntriesByType("navigation")[0];
  console.log(
    "Page Load Time:",
    perfData.loadEventEnd - perfData.loadEventStart
  );
});
```

## 📧 Notification Setup

### Slack Integration:

```bash
# Webhook URL para notificações críticas
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
```

### Email Alerts:

```php
// Para erros críticos
Mail::to('admin@chineloskaribe.com.br')->send(
    new CriticalErrorAlert($exception, $context)
);
```

## 🚀 Production Monitoring Tools

### Recomendados:

1. **New Relic** - APM completo
2. **Sentry** - Error tracking
3. **DataDog** - Infrastructure monitoring
4. **Pingdom** - Uptime monitoring
5. **Google Analytics** - User behavior

### Self-hosted Alternatives:

1. **Grafana + Prometheus** - Métricas
2. **ELK Stack** - Logs centralizados
3. **Uptime Kuma** - Uptime monitoring

---

**Next Steps:**

1. ✅ Update PHP to 8.2+
2. ⚠️ Run `php artisan test`
3. 🚀 Deploy to production
4. 📊 Setup monitoring dashboard

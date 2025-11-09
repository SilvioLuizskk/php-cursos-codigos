# Cloudflare CDN Setup

## 1. Ativação

-   Aponte o DNS do seu domínio para Cloudflare.
-   Ative SSL/TLS (Full ou Flexible).
-   Configure Page Rules para `/assets/*` e `/static/*` com cache everything e edge TTL alto.

## 2. Headers recomendados

-   Adicione `Cache-Control: public, max-age=31536000, immutable` para assets estáticos (config no Vite ou nginx).
-   Para HTML, use `Cache-Control: no-cache, must-revalidate`.

## 3. Purge automático

-   Use o script `scripts/cloudflare_purge.sh` após deploy:

```bash
CLOUDFLARE_ZONE_ID=... CLOUDFLARE_API_TOKEN=... ./scripts/cloudflare_purge.sh https://seusite.com/assets/main.js
```

## 4. Worker opcional

-   Faça upload de `scripts/cloudflare_worker.js` no painel Workers para cache edge customizado.

## 5. Segurança

-   Ative WAF, Rate Limiting e Bot Fight Mode no painel Cloudflare.

## 6. Referências

-   https://developers.cloudflare.com/cache/about/
-   https://developers.cloudflare.com/workers/

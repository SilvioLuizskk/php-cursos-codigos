# PROD / Local stack quickstart

Este documento descreve passos rápidos para levantar o stack de observability e infra localmente (Redis, Prometheus, Grafana) e como configurar alertas (Twilio) e Sentry na frontend.

1. Variáveis de ambiente importantes

-   `ADMIN_WHATSAPP` - número do admin para alertas (formato 5511999999999)
-   `TWILIO_SID`, `TWILIO_TOKEN`, `TWILIO_WHATSAPP_FROM` - credenciais Twilio
-   `VITE_SENTRY_DSN` - DSN Sentry para frontend
-   `DB_CONNECTION`, `DB_DATABASE`, `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` - DB config
-   `S3_BUCKET` - bucket para upload de backups (opcional)

2. Levantar stack local (docker-compose)

No diretório raiz do projeto:

```bash
# levanta redis, prometheus, grafana e app (conforme docker-compose.yml)
docker compose up -d redis prometheus grafana app

# verificar logs
docker compose logs -f prometheus
```

3. Prometheus

-   Prometheus está configurado para fazer scrape de `http://app:9000/api/metrics` a cada 15s (veja `docs/prometheus/prometheus.yml`).
-   Abra `http://localhost:9090` para consultar métricas.

4. Grafana

-   Acesse `http://localhost:3000` (usuário admin / senha admin por padrão no docker-compose).
-   O provisioning carrega dashboards em `docs/grafana/provisioning`.

5. Teste rápido A/B

-   Gere variante no frontend (via `useABTest`) e poste conversão com `fetch('/api/ab/convert', ...)`.
-   Métricas A/B estarão em `/api/metrics` e aparecerão no Grafana quando o Prometheus fizer scrape.

6. Backups

-   Rodar script:

```bash
./scripts/backup-db.sh --keep 7
```

-   Para enviar para S3 (requer aws cli e credenciais):

```bash
S3_BUCKET=my-bucket ./scripts/backup-db.sh --upload-s3
```

7. Sentry (frontend)

-   Configure `VITE_SENTRY_DSN` no `.env` do `frontend-vue` (ou na env de build). O `src/main.js` faz init condicional do Sentry.

8. WhatsApp alerts

-   Configure `TWILIO_*` e `ADMIN_WHATSAPP`.
-   O backend tentará enviar uma mensagem quando houver erro ao criar pedido (melhoria inicial). Ajuste conforme necessário.

Observações

-   Antes de habilitar em produção, configure rate-limits e teste as integrações com contas de teste do Twilio e Sentry.
-   Para Cloudflare/CDN, siga o passo a passo no painel do Cloudflare e use a opção de 'Purge by URL' para limpar cache após deploy.

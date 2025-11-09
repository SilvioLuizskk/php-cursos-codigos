# Executando os testes BDD via Docker

Este compose facilita executar a suíte Behat em um ambiente isolado com Selenium (Chrome) e MySQL.

Pré-requisitos
- Docker e docker-compose instalados e funcionando localmente.

Comandos úteis

- Build e execute apenas o serviço `behat` (vai subir `web`, `db` e `selenium` como dependências):

```bash
docker-compose -f docker-compose.bdd.yml up --build behat
```

- Para iniciar apenas os serviços e depois rodar o behat manualmente:

```bash
docker-compose -f docker-compose.bdd.yml up --build -d web db selenium
# Em seguida, execute o behat (um container efêmero):
docker-compose -f docker-compose.bdd.yml run --rm behat
```

Observações
- Este compose assume que seu `behat.yml.dist` está configurado para apontar para `http://web:8000` e usar o Selenium em `http://selenium:4444/wd/hub`.
- Se você pretende usar drivers Mink (goutte/mink-selenium2-driver), instale-os no `require-dev` do `composer.json` antes de rodar no container. Pode haver conflitos de versão com dependências do framework; teste localmente primeiro.

# Projeto Catálogo de Produtos

Este repositório contém um catálogo de produtos em Laravel com foco em boas práticas: PSR-4, DI, repositórios, testes (PHPUnit) e tratamento de exceções de domínio.

Principais features

-   CRUD de produtos (web + API)
-   Upload de imagens
-   Autenticação web (Laravel UI) e API (Sanctum usado nos testes)
-   Eventos assíncronos (ProductCreated) e listeners
-   Repositório `ProductRepositoryInterface` + implementação
-   Testes: unitários, feature e integração (PHPUnit)

Como rodar localmente

1. Instale dependências

```bash
composer install
```

2. Configure variáveis de ambiente

Copie `.env.example` para `.env` e ajuste conexões (MySQL) conforme necessário:

```bash
cp .env.example .env
php artisan key:generate
# edite .env para apontar para seu MySQL
```

3. Migrations e seeds (opcional)

```bash
php artisan migrate --seed
```

4. Rodar servidor

```bash
php artisan serve
# abrir http://127.0.0.1:8000
```

Testes

O projeto já está configurado para rodar testes com SQLite em memória (via `phpunit.xml`). Para executar:

```bash
vendor/bin/phpunit --testdox
```

BDD (Behat)

Este projeto inclui cenários básicos de BDD (Behat). Para evitar conflitos de versão do PHP / extensões locais, a forma recomendada de executar Behat é via Docker (o repositório já traz um `docker-compose.bdd.yml` e um serviço `behat`).

Pré-requisitos

- Docker e docker-compose instalados na sua máquina.
- Pelo menos 4GB de RAM livre para executar Selenium + MySQL + PHP/Nginx containers.

Rodando Behat (recomendado)

1) Build + run (modo único — builda e executa o runner que roda os testes):

```bash
./scripts/run-behat-docker.sh behat
```

2) Ou, para iniciar os serviços em background e depois executar o runner:

```bash
./scripts/run-behat-docker.sh up
# aguarde os serviços subirem (veja healthchecks)
./scripts/run-behat-docker.sh run
```

O script encapsula `docker-compose -f docker-compose.bdd.yml` e já usa `wait-for.sh` para aguardar readiness do `nginx` e do `selenium` antes de executar o `vendor/bin/behat`.

Executando sem Docker (alternativa rápida)

Se preferir não usar Docker, você pode rodar Behat localmente apontando `APP_URL` para uma instância local do Laravel (php artisan serve) e executando:

```bash
composer install --dev
vendor/bin/behat -c behat.yml.dist
```

Observações sobre `behat.selenium.yml.dist`

O repositório inclui um perfil separado para execução com Mink + Selenium (`behat.selenium.yml.dist`). Use-o se quiser rodar testes de navegador reais contra o Selenium standalone.

Troubleshooting comum

- Permissão negada ao conectar com Docker daemon: verifique se seu usuário pertence ao grupo `docker` ou execute via `sudo`.
- Portas em uso: por padrão o compose publica `8000` (nginx) e `4444` (selenium). Se já houver processos, pare-os ou altere as portas em `docker-compose.bdd.yml`.
- Contêiner `behat` falhando em `composer install`: verifique conectividade com packagist (proxy/firewall) ou monte o `~/.composer` corretamente; você pode também executar `./scripts/run-behat-docker.sh up` e entrar no container (`docker-compose -f docker-compose.bdd.yml exec behat bash`) para diagnosticar manualmente.
- MySQL não pronto: o compose inclui healthchecks e `wait-for.sh`, mas em máquinas lentas aumente os `retries`/`start_period` no `docker-compose.bdd.yml` para dar mais tempo ao MySQL.
- Selenium falhando: confira se a versão `selenium/standalone-chrome` é compatível com o driver; logs do container mostram detalhes em `/var/log/selenium`.

Como rodar no CI

O workflow em `.github/workflows/ci-behat.yml` demonstra como orquestrar MySQL + Selenium como serviços no GitHub Actions. Se preferir, você pode usar a imagem `selenium/standalone-chrome` como serviço e executar `vendor/bin/behat` no job principal.

Se algo falhar aqui, cole o output do `docker-compose -f docker-compose.bdd.yml up --build` e eu ajudo a diagnosticar.

CI

Incluí um workflow básico do GitHub Actions que roda `composer install` e `vendor/bin/phpunit` em pushes e pull requests.

Próximos passos recomendados

-   Adicionar cobertura de código (Xdebug / PCOV) no ambiente de CI
-   Implementar testes de controller adicionais e extrair mais regras de negócio para serviços/repositórios
-   Configurar filas (Redis) para listeners queueable em produção
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Cubet Techno Labs](https://cubettech.com)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[Many](https://www.many.co.uk)**
-   **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
-   **[DevSquad](https://devsquad.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[OP.GG](https://op.gg)**
-   **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
-   **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

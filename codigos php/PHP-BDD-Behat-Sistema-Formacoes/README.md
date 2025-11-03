# Sistema de Formações e Cursos com BDD

Sistema de gerenciamento de formações e cursos desenvolvido em PHP utilizando **Behavior Driven Development (BDD)** com **Behat**.

## 🚀 Características

- **Arquitetura MVC**: Organização clara e separação de responsabilidades
- **BDD com Behat**: Testes comportamentais em linguagem natural (Gherkin)
- **Doctrine ORM**: Mapeamento objeto-relacional para persistência de dados
- **PSR-7**: Padrões de requisição e resposta HTTP
- **PHP-DI**: Injeção de dependências
- **PSR-4**: Autoloading padrão

## 📋 Requisitos

- **PHP** >= 7.4
- **Composer**
- **SQLite** (para banco de dados)

### Extensões PHP necessárias:
- PDO SQLite
- mbstring
- xml

## 🛠️ Instalação

1. **Clone o repositório**:
```bash
git clone <url-do-repositorio>
cd "PHP e Behavior Driven Development BDD com Behat"
```

2. **Instale as dependências**:
```bash
composer install
```

3. **Configure o banco de dados**:
```bash
composer doctrine:schema:create
```

## 🚦 Executando o Sistema

### Servidor de Desenvolvimento
```bash
composer serve
```
O sistema estará disponível em: `http://localhost:8080`

### Executando Testes

**Todos os testes**:
```bash
composer test
```

**Testes unitários** (em memória):
```bash
composer test:unit
```

**Testes de integração** (com banco):
```bash
composer test:integration
```

**Testes E2E** (navegador):
```bash
composer test:e2e
```

## 📁 Estrutura do Projeto

```
├── src/                    # Código fonte da aplicação
│   ├── Controller/         # Controladores (MVC)
│   ├── Entity/            # Entidades do Doctrine
│   ├── Helper/            # Classes auxiliares
│   └── Infra/             # Infraestrutura (EntityManager, etc.)
├── features/              # Especificações BDD (Gherkin)
│   └── bootstrap/         # Contextos do Behat
├── public/                # Ponto de entrada web
├── view/                  # Templates HTML
├── config/                # Configurações da aplicação
├── behat.yml             # Configuração do Behat
└── composer.json         # Dependências e scripts
```

## 🧪 Metodologia BDD

Este projeto utiliza **Behavior Driven Development** com **Behat**, permitindo:

- ✅ Especificações em linguagem natural (português)
- ✅ Testes unitários, integração e E2E
- ✅ Colaboração entre negócio e desenvolvimento
- ✅ Documentação viva através dos cenários

### Exemplo de Cenário:
```gherkin
Funcionalidade: Cadastro de formações
  Eu, como instrutor
  Quero cadastrar formações
  Para organizar meus cursos

  @unidade
  Cenário: Criação de formação válida
    Quando eu tentar criar uma formação com a descrição "PHP na web"
    Então eu devo ter uma formação criada com a descrição "PHP na web"
```

## 🛠️ Scripts Disponíveis

```bash
composer serve                    # Inicia servidor de desenvolvimento
composer test                     # Executa todos os testes
composer test:unit                # Testes unitários
composer test:integration         # Testes de integração  
composer test:e2e                 # Testes end-to-end
composer doctrine:schema:create   # Cria schema do banco
composer doctrine:schema:update   # Atualiza schema do banco
composer doctrine:schema:validate # Valida schema do banco
```

## 🔧 Tecnologias Utilizadas

- **PHP** 7.4+
- **Behat** - Framework BDD
- **Doctrine ORM** - Mapeamento objeto-relacional
- **PHP-DI** - Container de injeção de dependências
- **PSR-7** - HTTP Message interfaces
- **Nyholm PSR-7** - Implementação PSR-7
- **SQLite** - Banco de dados

## 🤝 Como Contribuir

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Escreva especificações BDD para sua funcionalidade
4. Implemente o código seguindo os testes
5. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
6. Push para a branch (`git push origin feature/AmazingFeature`)
7. Abra um Pull Request

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

```
$ docker run --rm -itv $(pwd):/app -w /app -u $(id -u):$(id -g) composer install --ignore-platform-reqs
```

Para inicializar o sistema, o primeiro passo é criar o banco de dados. Para isso, crie um arquivo vazio chamado db.sqlite
na raiz deste projeto.

Depois, execute o seguinte comando: 
```
$ docker run --rm -itv $(pwd):/app -w /app -u $(id -u):$(id -g) php:latest php vendor/bin/doctrine orm:schema-tool:create
```

Este comando criará a estrutura do banco de dados SQLite. Agora vamos inserir um usuário com e-mail `email@example.com` e senha `123456`:

```
$ docker run --rm -itv $(pwd):/app -w /app -u $(id -u):$(id -g) php:latest php vendor/bin/doctrine dbal:run-sql "INSERT INTO usuarios (email, senha) VALUES ('email@example.com', '\$argon2i\$v=19\$m=65536,t=4,p=1\$WHpBb1FzTDVpTmQubU55bA\$jtZiWSSbmw1Ru4tYEq1SzShrMu0ap2PjblRQRubNPgo');"
```

Tendo feito isso, basta subir um servidor de testes. Isso pode ser feito com:

```
docker run -itv $(pwd):/app -w /app -u $(id -u):$(id -g) -p 8080:8080 php:latest php -S 0.0.0.0:8080 -t public
```

Pronto! Basta acessar no seu navegador o endereço http://localhost:8080/ e começar a interagir com o sistema.
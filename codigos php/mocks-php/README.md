# Mocks em PHP - Dublês de Testes

Este projeto demonstra o uso de mocks e dublês de testes em PHP usando PHPUnit.

## Características

- **PHP**: ^8.0 (compatível com PHP 8.4)
- **PHPUnit**: ^10.0 (versão mais recente)
- **Mocks**: Demonstração de como usar mocks para testes unitários

## Estrutura

```
mocks-php/
├── composer.json
├── tests/
│   └── Service/
│       └── EncerradorTest.php
└── README.md
```

## Principais Melhorias Aplicadas

### 1. Compatibilidade com PHP 8.4

- Atualização da versão mínima do PHP de `^7.3` para `^8.0`
- Compatibilidade total com PHP 8.4.13

### 2. PHPUnit 10

- Migração do PHPUnit de `^8.1` para `^10.0`
- Remoção do método descontinuado `withConsecutive()`
- Substituição por `willReturnCallback()` com verificações usando `assertContains()`

### 3. Melhores Práticas de Mocking

- Uso de anotações de tipo adequadas: `/** @var EnviadorEmail&MockObject */`
- Verificações mais robustas nos testes
- Melhor legibilidade e manutenibilidade do código

## Como usar

1. Instale as dependências:

```bash
composer install
```

2. Execute os testes:

```bash
vendor/bin/phpunit
```

## Testes Incluídos

- `testLeiloesComMaisDeUmaSemanaDevemSerEncerrados`: Verifica se leilões antigos são encerrados
- `testDeveContinuarOProcessamentoAoEncontrarErroAoEnviarEmail`: Testa tratamento de erros
- `testSoDeveEnviarLeilaoPorEmailAposFinalizado`: Verifica envio de email após finalização

## Benefícios dos Mocks

- **Isolamento**: Testes unitários puros sem dependências externas
- **Velocidade**: Execução rápida sem acesso a banco de dados ou APIs
- **Controle**: Simulação precisa de cenários específicos
- **Confiabilidade**: Testes determinísticos e repetíveis

<?php

namespace App\Exceptions;

use Exception;

/**
 * Exceção de domínio para produto não encontrado.
 * Lance esta exceção quando a regra de negócio determinar que um produto não existe
 * ou não está disponível para a operação solicitada.
 */
class ProductNotFoundException extends Exception
{
    // Mensagem padrão
    protected $message = 'Produto não encontrado.';

    // Podemos adicionar propriedades/metadata no futuro (ex.: product_id)
}

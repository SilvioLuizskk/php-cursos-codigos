<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;

class LoginController implements Controller
{
    use \Alura\Mvc\Helper\FlashMessageTrait;
    private \PDO $pdo;

    public function __construct()
    {
        $dbPath = __DIR__ . '/../../banco.sqlite';
        $this->pdo = new \PDO("sqlite:$dbPath");
    }

    public function processaRequisicao(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();
        
        $email = filter_var($parsedBody['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $parsedBody['password'] ?? '';

        $sql = 'SELECT * FROM usuarios WHERE email = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $email);
        $statement->execute();

        $userData = $statement->fetch(\PDO::FETCH_ASSOC);
        $correctPassword = password_verify($password, $userData['senha'] ?? '');

        if (isset($userData['senha']) && password_needs_rehash($userData['senha'], PASSWORD_ARGON2ID)) {
            $statement = $this->pdo->prepare('UPDATE usuarios SET senha = ? WHERE id = ?');
            $statement->bindValue(1, password_hash($password, PASSWORD_ARGON2ID));
            $statement->bindValue(2, $userData['id']);
            $statement->execute();
        }

        if ($correctPassword) {
            $_SESSION['logado'] = true;
            return new Response(302, ['Location' => '/']);
        } else {
            $this->addErrorMessage('usuario ou senha inválidos');
            return new Response(302, ['Location' => '/login']);
        }
    }
}

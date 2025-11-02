<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;

class NewJsonVideoController implements Controller
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getBody()->getContents();
        $videoData = json_decode($body, true);
        
        if (!isset($videoData['url']) || !isset($videoData['title'])) {
            return new Response(400, ['Content-Type' => 'application/json'], 
                json_encode(['error' => 'URL e título são obrigatórios']));
        }
        
        $video = new Video($videoData['url'], $videoData['title']);
        $success = $this->videoRepository->add($video);
        
        if ($success) {
            return new Response(201, ['Content-Type' => 'application/json'], 
                json_encode(['message' => 'Vídeo criado com sucesso']));
        } else {
            return new Response(500, ['Content-Type' => 'application/json'], 
                json_encode(['error' => 'Erro ao criar vídeo']));
        }
    }
}
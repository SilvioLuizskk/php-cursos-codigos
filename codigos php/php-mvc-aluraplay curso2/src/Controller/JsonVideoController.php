<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;

class JsonVideoController implements Controller
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(ServerRequestInterface $request): ResponseInterface
    {
        $videoList = array_map(function (Video $video) {
            return [
                'url' => $video->url,
                'title' => $video->title,
                'file_path' => $video->getFilePath()
            ];
        }, $this->videoRepository->all());

        return new Response(200, ['Content-Type' => 'application/json'], json_encode($videoList));
    }
}
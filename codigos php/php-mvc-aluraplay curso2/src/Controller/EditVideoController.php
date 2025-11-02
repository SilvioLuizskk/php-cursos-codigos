<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Nyholm\Psr7\Response;

class EditVideoController implements Controller
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $parsedBody = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        
        $id = filter_var($queryParams['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null) {
            return new Response(302, ['Location' => '/?sucesso=0']);
        }

        $url = filter_var($parsedBody['url'] ?? '', FILTER_VALIDATE_URL);
        if ($url === false) {
            return new Response(302, ['Location' => '/?sucesso=0']);
        }
        
        $titulo = $parsedBody['titulo'] ?? '';
        if (empty($titulo)) {
            return new Response(302, ['Location' => '/?sucesso=0']);
        }

        $video = new Video($url, $titulo);
        $video->setId($id);

        if (isset($uploadedFiles['image']) && $uploadedFiles['image']->getError() === UPLOAD_ERR_OK) {
            $uploadedFile = $uploadedFiles['image'];
            $stream = $uploadedFile->getStream();
            $tmpName = tempnam(sys_get_temp_dir(), 'upload');
            file_put_contents($tmpName, $stream->getContents());
            
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($tmpName);

            if (str_starts_with($mimeType, 'image/')) {
                $safeFileName = uniqid('upload_') . '_' . pathinfo($uploadedFile->getClientFilename(), PATHINFO_BASENAME);
                
                // Criar diretório se não existir
                $uploadDir = __DIR__ . '/../../public/img/uploads';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $uploadPath = $uploadDir . '/' . $safeFileName;
                if (move_uploaded_file($tmpName, $uploadPath)) {
                    $video->setFilePath($safeFileName);
                }
            }
            unlink($tmpName);
        }

        $success = $this->videoRepository->update($video);

        if ($success === false) {
            return new Response(302, ['Location' => '/?sucesso=0']);
        } else {
            return new Response(302, ['Location' => '/?sucesso=1']);
        }
    }
}
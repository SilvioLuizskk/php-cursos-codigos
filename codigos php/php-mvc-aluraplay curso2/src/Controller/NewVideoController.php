<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;

class NewVideoController implements RequestHandlerInterface
{
    use \Alura\Mvc\Helper\FlashMessageTrait;
    
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        
        $url = filter_var($parsedBody['url'] ?? '', FILTER_VALIDATE_URL);
        if ($url === false) {
            $this->addErrorMessage('URL inválida');
            return new Response(302, ['Location' => '/novo-video']);
        }
        
        $titulo = $parsedBody['titulo'] ?? '';
        if (empty($titulo)) {
            $this->addErrorMessage('título não informado');
            return new Response(302, ['Location' => '/novo-video']);
        }

        $video = new Video($url, $titulo);
        
        if (isset($uploadedFiles['image']) && $uploadedFiles['image']->getError() === UPLOAD_ERR_OK) {
            $uploadedFile = $uploadedFiles['image'];
            $stream = $uploadedFile->getStream();
            $tmpName = tempnam(sys_get_temp_dir(), 'upload');
            file_put_contents($tmpName, $stream->getContents());
            
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($tmpName);

            if (str_starts_with($mimeType, 'image/')) {
                $safeFileName = uniqid('upload_') . '_' . pathinfo($uploadedFile->getClientFilename(), PATHINFO_BASENAME);
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/img/uploads/' . $safeFileName;
                move_uploaded_file($tmpName, $uploadPath);
                $video->setFilePath($safeFileName);
            }
            unlink($tmpName);
        }

        $success = $this->videoRepository->add($video);
        if ($success === false) {
            $this->addErrorMessage('erro ao salvar vídeo');
            return new Response(302, ['Location' => '/novo-video']);
        } else {
            return new Response(302, ['Location' => '/?sucesso=1']);
        }
    }
}

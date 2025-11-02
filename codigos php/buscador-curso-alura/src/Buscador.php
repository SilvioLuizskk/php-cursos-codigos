<?php

namespace BuscadorDeCurso;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class Buscador
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var Crawler
     */
    private $crawler;

    public function __construct(Client $httpClient, Crawler $crawler)
    {
        $this->httpClient = $httpClient;
        $this->crawler = $crawler;
    }

    public function buscar(string $url): array
    {
        $resposta = $this->httpClient->request('GET', $url);
        $html = $resposta->getBody()->getContents();
        $this->crawler->addHtmlContent($html);

        $elementosCurso = $this->crawler->filter('span.card-curso__nome');
        $cursos = [];

        foreach ($elementosCurso as $elemento) {
            $cursos[] = $elemento->textContent;
        }
        
        return $cursos;
    }
}


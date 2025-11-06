<?php

namespace App\Traits;

trait SlugTrait
{
    /**
     * Gera um slug limpo a partir de uma string.
     * Normaliza acentos, remove caracteres inválidos e transforma espaços em hífen.
     */
    public function generateSlug(string $text): string
    {
        // Normaliza para UTF-8 e translitera para ASCII (onde possível)
        $text = mb_convert_encoding($text, 'UTF-8', mb_detect_encoding($text));
        $trans = @iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        if ($trans !== false) {
            $text = $trans;
        }

        // Substitui qualquer sequência não alfanumérica por hífen
        $text = preg_replace('/[^A-Za-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        $text = mb_strtolower($text);
        $text = preg_replace('/-+/', '-', $text);

        if ($text === '') {
            return 'n-a';
        }

        return $text;
    }
}

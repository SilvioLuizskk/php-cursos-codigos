<?php
    namespace ScreenMatch\Modelo;

class Serie extends Titulo implements Avaliavel
{
    public function __construct(
        string $nome,
        int $anoLancamento,
        Genero $genero,
        public readonly int $temporadas,
        public readonly int $episodiosPorTemporadas,
        public readonly int $minutosPorEpidio
    ) {
        parent::__construct($nome, $anoLancamento, $genero);
    }
    #[\Override]
    public function duracaoEmMinutos(): int
    {
        return $this->temporadas * $this->episodiosPorTemporadas * $this->minutosPorEpidio;
    }
}

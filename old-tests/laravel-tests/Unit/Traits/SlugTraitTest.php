<?php

namespace Tests\Unit\Traits;

use PHPUnit\Framework\TestCase;

class DummySlug
{
    use \App\Traits\SlugTrait;
}

class SlugTraitTest extends TestCase
{
    public function test_generate_slug_basic()
    {
        $d = new DummySlug();
        $this->assertEquals('ola-mundo', $d->generateSlug('Olá Mundo'));
        $this->assertEquals('abc-123', $d->generateSlug('ABC 123'));
    }

    public function test_generate_slug_normalizes_spaces_and_special_chars()
    {
        $d = new DummySlug();
        $this->assertEquals('uma-string-com-maiusculas', $d->generateSlug('Uma   string -- com MAIÚSCULAS!!'));
    }

    public function test_generate_slug_empty_returns_na()
    {
        $d = new DummySlug();
        $this->assertEquals('n-a', $d->generateSlug('   '));
    }
}

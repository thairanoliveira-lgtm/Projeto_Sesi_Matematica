<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Math\Matrix;
use App\Exceptions\IncompatibleDimensionsException;

class MatrixTest extends TestCase
{
    public function testSomaCasoFeliz(): void
    {
        $m1 = new Matrix([[1, 2], [3, 4]]);
        $m2 = new Matrix([[5, 6], [7, 8]]);
        
        $resultado = $m1->add($m2);
        
        $this->assertEquals([[6, 8], [10, 12]], $resultado->getData());
    }

    public function testSomaComDimensoesIncompativeisLancaExcecao(): void
    {
        $this->expectException(IncompatibleDimensionsException::class);
        
        $m1 = new Matrix([[1, 2]]);
        $m2 = new Matrix([[1, 2], [3, 4]]);
        
        $m1->add($m2);
    }

    public function testMultiplicacaoCasoFeliz(): void
    {
        $m1 = new Matrix([[1, 2], [3, 4]]);
        $m2 = new Matrix([[2, 0], [1, 2]]);
        
        $resultado = $m1->multiply($m2);
        
        $this->assertEquals([[4, 4], [10, 8]], $resultado->getData());
    }

    public function testMultiplicacaoComMatrizIdentidade(): void
    {
        $m = new Matrix([[3, 7], [1, 9]]);
        $identidade = new Matrix([[1, 0], [0, 1]]);
        
        $resultado = $m->multiply($identidade);
        
        $this->assertEquals($m->getData(), $resultado->getData());
    }

    public function testDeterminanteMatriz1x1(): void
    {
        $m = new Matrix([[7]]);
        $this->assertEqualsWithDelta(7.0, $m->determinant(), 0.0001);
    }

    public function testDeterminanteMatriz3x3(): void
    {
        $m = new Matrix([
            [6, 1, 1],
            [4, -2, 5],
            [2, 8, 7]
        ]);
        
        $this->assertEqualsWithDelta(-306.0, $m->determinant(), 0.0001);
    }

    public function testTransposicaoMatrizNula(): void
    {
        $m = new Matrix([[0, 0], [0, 0]]);
        $transposta = $m->transpose();
        
        $this->assertEquals([[0, 0], [0, 0]], $transposta->getData());
    }
}
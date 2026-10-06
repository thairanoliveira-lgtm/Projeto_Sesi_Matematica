<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Math\Matrix;
use App\Math\LinearSystemSolver;
use App\Exceptions\SingularMatrixException;

class LinearSystemSolverTest extends TestCase
{
    private LinearSystemSolver $solver;

    protected function setUp(): void
    {
        $this->solver = new LinearSystemSolver();
    }

    public function testSistema2x2CasoFeliz(): void
    {
        // 2x + y = 5
        // x - y = 1 => Solução: x = 2, y = 1
        $A = new Matrix([[2, 1], [1, -1]]);
        $b = [5, 1];

        $solucao = $this->solver->solve($A, $b);

        $this->assertEqualsWithDelta(2.0, $solucao[0], 0.0001);
        $this->assertEqualsWithDelta(1.0, $solucao[1], 0.0001);
    }

    public function testSistema3x3CasoFeliz(): void
    {
        // 3x + 2y - z = 1
        // 2x - 2y + 4z = -2
        // -x + (1/2)y - z = 0
        $A = new Matrix([
            [3, 2, -1],
            [2, -2, 4],
            [-1, 0.5, -1]
        ]);
        $b = [1, -2, 0];

        $solucao = $this->solver->solve($A, $b);

        $this->assertEqualsWithDelta(1.0, $solucao[0], 0.0001);
        $this->assertEqualsWithDelta(-2.0, $solucao[1], 0.0001);
        $this->assertEqualsWithDelta(-2.0, $solucao[2], 0.0001);
    }

    public function testMatrizSingularLancaExcecao(): void
    {
        $this->expectException(SingularMatrixException::class);

        $A = new Matrix([[1, 2], [2, 4]]);
        $b = [3, 6];

        $this->solver->solve($A, $b);
    }
}
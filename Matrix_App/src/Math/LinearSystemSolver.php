<?php

namespace App\Math;

use App\Exceptions\IncompatibleDimensionsException;
use App\Exceptions\SingularMatrixException;

class LinearSystemSolver
{
    /**
     * Resolve sistemas lineares do tipo Ax = b usando Eliminação de Gauss com pivoteamento
     * @param Matrix $A Matriz de coeficientes (N x N)
     * @param array<float|int> $b Vetor de termos independentes (tamanho N)
     * @return array<float> Vetor solução x
     */
    public function solve(Matrix $A, array $b): array
    {
        $n = $A->getRows();

        if ($A->getRows() !== $A->getCols()) {
            throw new IncompatibleDimensionsException("A matriz de coeficientes deve ser quadrada.");
        }

        if (count($b) !== $n) {
            throw new IncompatibleDimensionsException("O tamanho do vetor 'b' deve corresponder ao número de linhas de 'A'.");
        }

        $aData = $A->getData();
        for ($i = 0; $i < $n; $i++) {
            $aData[$i][] = (float)$b[$i];
        }

        for ($i = 0; $i < $n; $i++) {
            $maxRow = $i;
            for ($k = $i + 1; $k < $n; $k++) {
                if (abs($aData[$k][$i]) > abs($aData[$maxRow][$i])) {
                    $maxRow = $k;
                }
            }

            if ($maxRow !== $i) {
                $temp = $aData[$i];
                $aData[$i] = $aData[$maxRow];
                $aData[$maxRow] = $temp;
            }

            if (abs($aData[$i][$i]) < 1e-9) {
                throw new SingularMatrixException("O sistema não possui solução única (matriz singular ou sistema indeterminado/impossível).");
            }

            for ($k = $i + 1; $k < $n; $k++) {
                $factor = $aData[$k][$i] / $aData[$i][$i];
                for ($j = $i; $j <= $n; $j++) {
                    $aData[$k][$j] -= $factor * $aData[$i][$j];
                }
            }
        }

        $x = array_fill(0, $n, 0.0);
        for ($i = $n - 1; $i >= 0; $i--) {
            $sum = 0.0;
            for ($j = $i + 1; $j < $n; $j++) {
                $sum += $aData[$i][$j] * $x[$j];
            }
            $x[$i] = ($aData[$i][$n] - $sum) / $aData[$i][$i];
        }

        return $x;
    }
}
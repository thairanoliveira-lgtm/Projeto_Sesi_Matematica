<?php

namespace App\Math;

use App\Exceptions\IncompatibleDimensionsException;
use InvalidArgumentException;

class Matrix
{
    /**
     * @param array<int, array<int, float|int>> $data
     */
    public function __construct(private array $data)
    {
        $this->validate();
    }

    private function validate(): void
    {
        $rows = count($this->data);
        if ($rows === 0) {
            throw new InvalidArgumentException("A matriz não pode ser vazia.");
        }
        $cols = count($this->data[0]);
        foreach ($this->data as $row) {
            if (count($row) !== $cols) {
                throw new InvalidArgumentException("Todas as linhas da matriz devem ter o mesmo número de colunas.");
            }
        }
    }

    public function getRows(): int
    {
        return count($this->data);
    }

    public function getCols(): int
    {
        return count($this->data[0]);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function add(Matrix $other): Matrix
    {
        if ($this->getRows() !== $other->getRows() || $this->getCols() !== $other->getCols()) {
            throw new IncompatibleDimensionsException("Dimensões incompatíveis para soma de matrizes.");
        }

        $result = [];
        for ($i = 0; $i < $this->getRows(); $i++) {
            for ($j = 0; $j < $this->getCols(); $j++) {
                $result[$i][$j] = $this->data[$i][$j] + $other->getData()[$i][$j];
            }
        }

        return new Matrix($result);
    }

    public function multiply(Matrix $other): Matrix
    {
        if ($this->getCols() !== $other->getRows()) {
            throw new IncompatibleDimensionsException("O número de colunas da primeira matriz deve ser igual ao número de linhas da segunda.");
        }

        $result = [];
        $otherData = $other->getData();

        for ($i = 0; $i < $this->getRows(); $i++) {
            for ($j = 0; $j < $other->getCols(); $j++) {
                $sum = 0;
                for ($k = 0; $k < $this->getCols(); $k++) {
                    $sum += $this->data[$i][$k] * $otherData[$k][$j];
                }
                $result[$i][$j] = $sum;
            }
        }

        return new Matrix($result);
    }

    public function transpose(): Matrix
    {
        $result = [];
        for ($i = 0; $i < $this->getRows(); $i++) {
            for ($j = 0; $j < $this->getCols(); $j++) {
                $result[$j][$i] = $this->data[$i][$j];
            }
        }

        return new Matrix($result);
    }

    public function determinant(): float
    {
        if ($this->getRows() !== $this->getCols()) {
            throw new IncompatibleDimensionsException("O determinante só pode ser calculado para matrizes quadradas.");
        }

        return $this->calculateDeterminant($this->data);
    }

    private function calculateDeterminant(array $matrix): float
    {
        $n = count($matrix);

        if ($n === 1) {
            return (float) $matrix[0][0];
        }

        if ($n === 2) {
            return (float) ($matrix[0][0] * $matrix[1][1] - $matrix[0][1] * $matrix[1][0]);
        }

        $det = 0.0;
        for ($j = 0; $j < $n; $j++) {
            $subMatrix = $this->getSubMatrix($matrix, 0, $j);
            $sign = ($j % 2 === 0) ? 1 : -1;
            $det += $sign * $matrix[0][$j] * $this->calculateDeterminant($subMatrix);
        }

        return $det;
    }

    private function getSubMatrix(array $matrix, int $excludeRow, int $excludeCol): array
    {
        $sub = [];
        for ($i = 0; $i < count($matrix); $i++) {
            if ($i === $excludeRow) continue;
            $row = [];
            for ($j = 0; $j < count($matrix[$i]); $j++) {
                if ($j === $excludeCol) continue;
                $row[] = $matrix[$i][$j];
            }
            $sub[] = $row;
        }
        return $sub;
    }
}
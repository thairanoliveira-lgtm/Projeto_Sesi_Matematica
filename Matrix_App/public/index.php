<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Inclusão manual para contornar a ausência do mapa de autoload atualizado no Composer
require_once __DIR__ . '/../src/Exceptions/IncompatibleDimensionsException.php';
require_once __DIR__ . '/../src/Exceptions/SingularMatrixException.php';
require_once __DIR__ . '/../src/Math/Matrix.php';
require_once __DIR__ . '/../src/Math/LinearSystemSolver.php';

use App\Math\Matrix;
use App\Math\LinearSystemSolver;

$resultadoDet = null;
$resultadoSistema = null;
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['calc_det'])) {
            $m = new Matrix([
                [(float)$_POST['m00'], (float)$_POST['m01']],
                [(float)$_POST['m10'], (float)$_POST['m11']]
            ]);
            $resultadoDet = $m->determinant();
        } elseif (isset($_POST['solve_system'])) {
            $A = new Matrix([
                [(float)$_POST['a00'], (float)$_POST['a01']],
                [(float)$_POST['a10'], (float)$_POST['a11']]
            ]);
            $b = [(float)$_POST['b0'], (float)$_POST['b1']];
            $solver = new LinearSystemSolver();
            $resultadoSistema = $solver->solve($A, $b);
        }
    } catch (\Throwable $e) {
        $erro = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Álgebra Linear - Aplicação Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="text-center mb-4">Calculadora de Álgebra Linear</h1>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Calculadora Determinante -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Determinante de Matriz 2x2</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row g-2 mb-3">
                            <div class="col-6"><input type="number" step="any" name="m00" class="form-control" placeholder="a11" required></div>
                            <div class="col-6"><input type="number" step="any" name="m01" class="form-control" placeholder="a12" required></div>
                            <div class="col-6"><input type="number" step="any" name="m10" class="form-control" placeholder="a21" required></div>
                            <div class="col-6"><input type="number" step="any" name="m11" class="form-control" placeholder="a22" required></div>
                        </div>
                        <button type="submit" name="calc_det" class="btn btn-primary w-100">Calcular Determinante</button>
                    </form>
                    <?php if ($resultadoDet !== null): ?>
                        <div class="mt-3 alert alert-info mb-0">Determinante: <strong><?= $resultadoDet ?></strong></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Resolver Sistema Linear -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Sistema Linear 2x2 (Ax = b)</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row g-2 mb-3">
                            <div class="col-4"><input type="number" step="any" name="a00" class="form-control" placeholder="a11" required></div>
                            <div class="col-4"><input type="number" step="any" name="a01" class="form-control" placeholder="a12" required></div>
                            <div class="col-4"><input type="number" step="any" name="b0" class="form-control" placeholder="b1" required></div>
                            <div class="col-4"><input type="number" step="any" name="a10" class="form-control" placeholder="a21" required></div>
                            <div class="col-4"><input type="number" step="any" name="a11" class="form-control" placeholder="a22" required></div>
                            <div class="col-4"><input type="number" step="any" name="b1" class="form-control" placeholder="b2" required></div>
                        </div>
                        <button type="submit" name="solve_system" class="btn btn-success w-100">Resolver Sistema</button>
                    </form>
                    <?php if ($resultadoSistema !== null): ?>
                        <div class="mt-3 alert alert-success mb-0">
                            Solução: x = <strong><?= $resultadoSistema[0] ?></strong>, y = <strong><?= $resultadoSistema[1] ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
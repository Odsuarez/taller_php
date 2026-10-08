<?php

/**
 * Calcula series numéricas usando solo ciclos y operaciones básicas.
 */
class Secuencia
{
    const MAX_FIBONACCI = 90; // el término 90 aún cabe en un entero de PHP
    const MAX_FACTORIAL = 20; // 20! es el último factorial que cabe en un entero
    const MAX_DIGITOS = 3;    // dígitos máximos que se aceptan al escribir el número

    /**
     * Serie de Fibonacci con $cantidad términos: 0, 1, 1, 2, 3, 5...
     */
    public function fibonacci(int $cantidad): array
    {
        $serie = [];
        $anterior = 0;
        $actual = 1;

        for ($i = 0; $i < $cantidad; $i++) {
            $serie[] = $anterior;
            $siguiente = $anterior + $actual;
            $anterior = $actual;
            $actual = $siguiente;
        }
        return $serie;
    }

    /**
     * Serie de factoriales desde 0! hasta $n!: 1, 1, 2, 6, 24...
     * El último elemento es el factorial de $n.
     */
    public function factorial(int $n): array
    {
        $serie = [];
        $acumulado = 1; // 0! = 1

        for ($i = 0; $i <= $n; $i++) {
            if ($i > 0) {
                $acumulado = $acumulado * $i;
            }
            $serie[] = $acumulado;
        }
        return $serie;
    }
}

<?php

/**
 * Algoritmos de ordenamiento hechos a mano.
 */
class Ordenamiento
{
    /** Ordena de menor a mayor (ordenamiento por inserción). */
    public static function ascendente(array $numeros): array
    {
        $total = count($numeros);

        for ($i = 1; $i < $total; $i++) {
            $actual = $numeros[$i];
            $j = $i - 1;

            while ($j >= 0 && $numeros[$j] > $actual) {
                $numeros[$j + 1] = $numeros[$j];
                $j--;
            }
            $numeros[$j + 1] = $actual;
        }
        return $numeros;
    }
}

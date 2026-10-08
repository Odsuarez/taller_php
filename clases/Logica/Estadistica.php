<?php

/**
 * Calcula promedio, mediana y moda usando solo ciclos y condiciones.
 */
class Estadistica
{
    const MAX_CANTIDAD = 50;
    const MAX_DIGITOS_CANTIDAD = 3;

    /**
     * Valida la cantidad de números: solo dígitos y entre 1 y MAX_CANTIDAD.
     */
    public function esCantidadValida(string $texto): bool
    {
        if (!Numero::esEntero($texto, self::MAX_DIGITOS_CANTIDAD)) {
            return false;
        }
        $numero = Numero::aEntero($texto);
        return $numero >= 1 && $numero <= self::MAX_CANTIDAD;
    }

    /** Promedio: suma de todos dividida entre la cantidad. */
    public function promedio(array $numeros): float
    {
        if (count($numeros) === 0) {
            return 0.0; // evita dividir entre cero con una lista vacía
        }

        $suma = 0;
        $cantidad = 0;

        foreach ($numeros as $n) {
            $suma = $suma + $n;
            $cantidad++;
        }
        return $suma / $cantidad;
    }

    /** Mediana: el valor central de la lista ordenada (o el promedio de los dos centrales). */
    public function mediana(array $numeros): float
    {
        $ordenados = Ordenamiento::ascendente($numeros);
        $total = count($ordenados);
        $mitad = intdiv($total, 2);

        if ($total % 2 === 1) {
            return $ordenados[$mitad];
        }
        return ($ordenados[$mitad - 1] + $ordenados[$mitad]) / 2;
    }

    /**
     * Moda: el valor (o valores) que más se repite.
     * Devuelve un arreglo vacío si ningún valor se repite.
     */
    public function moda(array $numeros): array
    {
        $total = count($numeros);
        $maxRepeticiones = 0;
        $repeticiones = [];

        // Contar cuántas veces aparece cada elemento
        for ($i = 0; $i < $total; $i++) {
            $veces = 0;
            for ($j = 0; $j < $total; $j++) {
                if ($numeros[$i] === $numeros[$j]) {
                    $veces++;
                }
            }
            $repeticiones[$i] = $veces;
            if ($veces > $maxRepeticiones) {
                $maxRepeticiones = $veces;
            }
        }

        if ($maxRepeticiones < 2) {
            return [];
        }

        // Reunir los valores con el máximo de repeticiones, sin duplicarlos
        $moda = [];
        for ($i = 0; $i < $total; $i++) {
            if ($repeticiones[$i] === $maxRepeticiones) {
                $yaEsta = false;
                foreach ($moda as $m) {
                    if ($m === $numeros[$i]) {
                        $yaEsta = true;
                    }
                }
                if (!$yaEsta) {
                    $moda[] = $numeros[$i];
                }
            }
        }
        return $moda;
    }
}

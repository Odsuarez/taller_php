<?php

/**
 * Operaciones entre conjuntos de números enteros usando solo ciclos y condiciones.
 */
class Conjuntos
{
    const MAX_ELEMENTOS = 50;
    const MAX_DIGITOS = 9;

    /** Indica si el carácter separa números (espacio, coma, punto y coma, tab). */
    private function esSeparador(string $c): bool
    {
        return $c === ' ' || $c === ',' || $c === ';' || $c === "\t";
    }

    /** Devuelve true si el número ya está en el conjunto. */
    public function contiene(array $conjunto, int $numero): bool
    {
        foreach ($conjunto as $elemento) {
            if ($elemento === $numero) {
                return true;
            }
        }
        return false;
    }

    /**
     * Convierte un texto como "1, 2 3;4" en un conjunto (sin repetidos).
     * Devuelve null si algún valor no es un entero válido, o si no hay ninguno,
     * o si hay demasiados elementos.
     */
    public function aConjunto(string $texto): ?array
    {
        $conjunto = [];
        $palabra = '';
        $total = strlen($texto);

        // Se agrega un separador imaginario al final para cerrar la última palabra
        for ($i = 0; $i <= $total; $i++) {
            $c = $i < $total ? $texto[$i] : ' ';

            if ($this->esSeparador($c)) {
                if ($palabra !== '') {
                    if (!Numero::esEntero($palabra, self::MAX_DIGITOS, true)) {
                        return null;
                    }
                    $numero = Numero::aEntero($palabra);
                    if (!$this->contiene($conjunto, $numero)) {
                        $conjunto[] = $numero;
                    }
                    $palabra = '';
                }
            } else {
                $palabra .= $c;
            }
        }

        if (count($conjunto) === 0 || count($conjunto) > self::MAX_ELEMENTOS) {
            return null;
        }
        return Ordenamiento::ascendente($conjunto);
    }

    /** Unión: todos los elementos de A y de B, sin repetir. */
    public function union(array $a, array $b): array
    {
        $resultado = $a;
        foreach ($b as $elemento) {
            if (!$this->contiene($resultado, $elemento)) {
                $resultado[] = $elemento;
            }
        }
        return Ordenamiento::ascendente($resultado);
    }

    /** Intersección: los elementos que están en A y también en B. */
    public function interseccion(array $a, array $b): array
    {
        $resultado = [];
        foreach ($a as $elemento) {
            if ($this->contiene($b, $elemento)) {
                $resultado[] = $elemento;
            }
        }
        return Ordenamiento::ascendente($resultado);
    }

    /** Diferencia A - B: los elementos de A que NO están en B. */
    public function diferencia(array $a, array $b): array
    {
        $resultado = [];
        foreach ($a as $elemento) {
            if (!$this->contiene($b, $elemento)) {
                $resultado[] = $elemento;
            }
        }
        return Ordenamiento::ascendente($resultado);
    }
}

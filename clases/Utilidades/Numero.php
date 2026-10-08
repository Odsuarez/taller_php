<?php

/**
 * Validación y conversión de números escritos como texto.
 * Usa solo ciclos y comparación de códigos de caracteres.
 */
class Numero
{
    const CODIGO_CERO = 48;   // código ASCII de '0'
    const CODIGO_NUEVE = 57;  // código ASCII de '9'

    /**
     * Entero válido: solo dígitos, con un signo (+/-) al inicio si $conSigno es true.
     * Debe tener entre 1 y $maxDigitos dígitos.
     */
    public static function esEntero(string $texto, int $maxDigitos, bool $conSigno = false): bool
    {
        $total = strlen($texto);
        $inicio = 0;

        if ($conSigno && $total > 0 && ($texto[0] === '-' || $texto[0] === '+')) {
            $inicio = 1;
        }

        $cantidadDigitos = $total - $inicio;
        if ($cantidadDigitos < 1 || $cantidadDigitos > $maxDigitos) {
            return false;
        }

        for ($i = $inicio; $i < $total; $i++) {
            $codigo = ord($texto[$i]);
            if ($codigo < self::CODIGO_CERO || $codigo > self::CODIGO_NUEVE) {
                return false;
            }
        }
        return true;
    }

    /** Convierte un entero ya validado (texto) en número, dígito por dígito. */
    public static function aEntero(string $texto): int
    {
        $total = strlen($texto);
        $signo = 1;
        $inicio = 0;

        if ($texto[0] === '-') {
            $signo = -1;
            $inicio = 1;
        } elseif ($texto[0] === '+') {
            $inicio = 1;
        }

        $numero = 0;
        for ($i = $inicio; $i < $total; $i++) {
            $numero = $numero * 10 + (ord($texto[$i]) - self::CODIGO_CERO);
        }
        return $signo * $numero;
    }

    /**
     * Real válido: signo opcional, dígitos y un solo separador decimal (punto o coma).
     * Ejemplos válidos: 5, -3.2, 0,75. Máximo $maxCaracteres caracteres.
     */
    public static function esReal(string $texto, int $maxCaracteres = 15): bool
    {
        $total = strlen($texto);

        if ($total === 0 || $total > $maxCaracteres) {
            return false;
        }

        $inicio = 0;
        if ($texto[0] === '-' || $texto[0] === '+') {
            $inicio = 1;
        }

        $hayDigitos = false;
        $haySeparador = false;

        for ($i = $inicio; $i < $total; $i++) {
            $c = $texto[$i];
            $codigo = ord($c);

            if ($codigo >= self::CODIGO_CERO && $codigo <= self::CODIGO_NUEVE) {
                $hayDigitos = true;
            } elseif (($c === '.' || $c === ',') && !$haySeparador) {
                $haySeparador = true;
            } else {
                return false;
            }
        }
        return $hayDigitos;
    }

    /** Convierte un real ya validado (texto) en número, carácter por carácter. */
    public static function aReal(string $texto): float
    {
        $total = strlen($texto);
        $signo = 1;
        $inicio = 0;

        if ($texto[0] === '-') {
            $signo = -1;
            $inicio = 1;
        } elseif ($texto[0] === '+') {
            $inicio = 1;
        }

        $entera = 0;
        $fraccion = 0;
        $divisor = 1;
        $despuesDelSeparador = false;

        for ($i = $inicio; $i < $total; $i++) {
            $c = $texto[$i];

            if ($c === '.' || $c === ',') {
                $despuesDelSeparador = true;
            } elseif (!$despuesDelSeparador) {
                $entera = $entera * 10 + (ord($c) - self::CODIGO_CERO);
            } else {
                $fraccion = $fraccion * 10 + (ord($c) - self::CODIGO_CERO);
                $divisor = $divisor * 10;
            }
        }
        return $signo * ($entera + $fraccion / $divisor);
    }
}

<?php

/** Utilidades de presentación. */
class Vista
{
    /**
     * Reemplaza a mano los caracteres peligrosos de HTML para evitar XSS.
     */
    public static function e($valor): string
    {
        $texto = (string) $valor;
        $salida = '';
        $total = strlen($texto);

        for ($i = 0; $i < $total; $i++) {
            $c = $texto[$i];

            if ($c === '&') {
                $salida .= '&amp;';
            } elseif ($c === '<') {
                $salida .= '&lt;';
            } elseif ($c === '>') {
                $salida .= '&gt;';
            } elseif ($c === '"') {
                $salida .= '&quot;';
            } elseif ($c === "'") {
                $salida .= '&#039;';
            } else {
                $salida .= $c;
            }
        }
        return $salida;
    }

    /** Muestra un conjunto como { 1, 2, 3 }, o ∅ si está vacío (ya escapado para HTML). */
    public static function conjunto(array $conjunto): string
    {
        if (count($conjunto) === 0) {
            return '∅ (vacío)';
        }

        $texto = '';
        foreach ($conjunto as $i => $elemento) {
            if ($i > 0) {
                $texto .= ', ';
            }
            $texto .= self::e($elemento);
        }
        return '{ ' . $texto . ' }';
    }
}

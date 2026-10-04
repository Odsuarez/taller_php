<?php

class Vista
{
    public static function e(string $texto): string
    {
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
}
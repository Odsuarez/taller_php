<?php

class ConversorBinario
{
    const MAX_DIGITOS = 15;


    public function esEnteroValido(string $texto): bool
    {
        $total = strlen($texto);
        $inicio = 0;

        if ($total > 0 && ($texto[0] === '-' || $texto[0] === '+')) {
            $inicio = 1;
        }

        $cantidadDigitos = $total - $inicio;
        if ($cantidadDigitos < 1 || $cantidadDigitos > self::MAX_DIGITOS) {
            return false;
        }

        for ($i = $inicio; $i < $total; $i++) {
            $codigo = ord($texto[$i]);
            if ($codigo < 48 || $codigo > 57) {
                return false;
            }
        }
        return true;
    }

  
    public function aEntero(string $texto): int
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
            $numero = $numero * 10 + (ord($texto[$i]) - 48);
        }
        return $signo * $numero;
    }

    public function aBinario(int $numero): array
    {
        $negativo = $numero < 0;
        $n = $negativo ? -$numero : $numero;

        if ($n === 0) {
            return ['binario' => '0', 'pasos' => [['dividendo' => 0, 'cociente' => 0, 'residuo' => 0]]];
        }

        $binario = '';
        $pasos = [];

        while ($n > 0) {
            $residuo = $n % 2;
            $cociente = ($n - $residuo) / 2;

            $pasos[] = ['dividendo' => $n, 'cociente' => $cociente, 'residuo' => $residuo];

  
            $binario = $residuo . $binario;
            $n = $cociente;
        }

        if ($negativo) {
            $binario = '-' . $binario;
        }
        return ['binario' => $binario, 'pasos' => $pasos];
    }
}
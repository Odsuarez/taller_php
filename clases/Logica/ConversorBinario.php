<?php

/**
 * Convierte números enteros a binario con el método de divisiones sucesivas entre 2.
 */
class ConversorBinario
{
    const MAX_DIGITOS = 15;

    /**
     * Convierte a binario dividiendo entre 2 y leyendo los residuos de abajo hacia arriba.
     * Devuelve el binario y los pasos de las divisiones.
     * Para negativos se convierte el valor absoluto y se antepone el signo "-".
     */
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

            // El residuo se pone al principio: así queda leído de abajo hacia arriba
            $binario = $residuo . $binario;
            $n = $cociente;
        }

        if ($negativo) {
            $binario = '-' . $binario;
        }
        return ['binario' => $binario, 'pasos' => $pasos];
    }
}

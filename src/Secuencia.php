<?php

class Secuencia
{
    const MAX_FIBONACCI = 90; 
    const MAX_FACTORIAL = 20; 

    public function esNumeroValido(string $texto): bool
    {
        $total = strlen($texto);

        if ($total === 0 || $total > 3) {
            return false;
        }

        for ($i = 0; $i < $total; $i++) {
            $codigo = ord($texto[$i]);
            if ($codigo < 48 || $codigo > 57) { 
                return false;
            }
        }
        return true;
    }

   
    public function aEntero(string $texto): int
    {
        $numero = 0;
        $total = strlen($texto);

        for ($i = 0; $i < $total; $i++) {
            $numero = $numero * 10 + (ord($texto[$i]) - 48);
        }
        return $numero;
    }

   
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

  
    public function factorial(int $n): array
    {
        $serie = [];
        $acumulado = 1; 

        for ($i = 0; $i <= $n; $i++) {
            if ($i > 0) {
                $acumulado = $acumulado * $i;
            }
            $serie[] = $acumulado;
        }
        return $serie;
    }
}
<?php

class Estadistica
{
    const MAX_CANTIDAD = 50;

    
    public function esCantidadValida(string $texto): bool
    {
        $total = strlen($texto);

        if ($total === 0 || $total > 3) {
            return false;
        }
        $numero = 0;
        for ($i = 0; $i < $total; $i++) {
            $codigo = ord($texto[$i]);
            if ($codigo < 48 || $codigo > 57) {
                return false;
            }
            $numero = $numero * 10 + ($codigo - 48);
        }
        return $numero >= 1 && $numero <= self::MAX_CANTIDAD;
    }

    public function aEntero(string $texto): int
    {
        $numero = 0;
        for ($i = 0; $i < strlen($texto); $i++) {
            $numero = $numero * 10 + (ord($texto[$i]) - 48);
        }
        return $numero;
    }

  
    public function esRealValido(string $texto): bool
    {
        $total = strlen($texto);

        if ($total === 0 || $total > 15) {
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

            if ($codigo >= 48 && $codigo <= 57) {
                $hayDigitos = true;
            } elseif (($c === '.' || $c === ',') && !$haySeparador) {
                $haySeparador = true;
            } else {
                return false;
            }
        }
        return $hayDigitos;
    }

   
    public function aReal(string $texto): float
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
                $entera = $entera * 10 + (ord($c) - 48);
            } else {
                $fraccion = $fraccion * 10 + (ord($c) - 48);
                $divisor = $divisor * 10;
            }
        }
        return $signo * ($entera + $fraccion / $divisor);
    }
    public function promedio(array $numeros): float
    {
        $suma = 0;
        $cantidad = 0;

        foreach ($numeros as $n) {
            $suma = $suma + $n;
            $cantidad++;
        }
        return $suma / $cantidad;
    }

    
    public function ordenar(array $numeros): array
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

    public function mediana(array $numeros): float
    {
        $ordenados = $this->ordenar($numeros);
        $total = count($ordenados);
        $mitad = intdiv($total, 2);

        if ($total % 2 === 1) {
            return $ordenados[$mitad];
        }
        return ($ordenados[$mitad - 1] + $ordenados[$mitad]) / 2;
    }

   
    public function moda(array $numeros): array
    {
        $total = count($numeros);
        $maxRepeticiones = 0;
        $repeticiones = [];

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
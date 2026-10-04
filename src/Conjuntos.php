<?php

class Conjuntos
{
    const MAX_ELEMENTOS = 50;

 
    private function esSeparador(string $c): bool
    {
        return $c === ' ' || $c === ',' || $c === ';' || $c === "\t";
    }

   
    private function esEnteroValido(string $texto): bool
    {
        $total = strlen($texto);
        $inicio = 0;

        if ($total > 0 && ($texto[0] === '-' || $texto[0] === '+')) {
            $inicio = 1;
        }

        $cantidadDigitos = $total - $inicio;
        if ($cantidadDigitos < 1 || $cantidadDigitos > 9) {
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

    
    private function aEntero(string $texto): int
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


    public function contiene(array $conjunto, int $numero): bool
    {
        foreach ($conjunto as $elemento) {
            if ($elemento === $numero) {
                return true;
            }
        }
        return false;
    }


    public function aConjunto(string $texto): ?array
    {
        $conjunto = [];
        $palabra = '';
        $total = strlen($texto);

       
        for ($i = 0; $i <= $total; $i++) {
            $c = $i < $total ? $texto[$i] : ' ';

            if ($this->esSeparador($c)) {
                if ($palabra !== '') {
                    if (!$this->esEnteroValido($palabra)) {
                        return null;
                    }
                    $numero = $this->aEntero($palabra);
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
        return $this->ordenar($conjunto);
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

    public function union(array $a, array $b): array
    {
        $resultado = $a;
        foreach ($b as $elemento) {
            if (!$this->contiene($resultado, $elemento)) {
                $resultado[] = $elemento;
            }
        }
        return $this->ordenar($resultado);
    }

   
    public function interseccion(array $a, array $b): array
    {
        $resultado = [];
        foreach ($a as $elemento) {
            if ($this->contiene($b, $elemento)) {
                $resultado[] = $elemento;
            }
        }
        return $this->ordenar($resultado);
    }

   
    public function diferencia(array $a, array $b): array
    {
        $resultado = [];
        foreach ($a as $elemento) {
            if (!$this->contiene($b, $elemento)) {
                $resultado[] = $elemento;
            }
        }
        return $this->ordenar($resultado);
    }
}
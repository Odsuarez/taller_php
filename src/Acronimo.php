<?php

class Acronimo
{
    private string $frase;

    public function __construct(string $frase)
    {
        $this->frase = $frase;
    }

    private function esSeparador(string $caracter): bool
    {
        return $caracter === ' ' || $caracter === "\t" || $caracter === "\n" || $caracter === "\r";
    }

    public function generar(): string
    {
        $resultado = '';
        $total = strlen($this->frase);
        $enPalabra = false;
        $i = 0;

        while ($i < $total) {
            $caracter = $this->frase[$i];

            if ($this->esSeparador($caracter)) {
                $enPalabra = false;
                $i++;
            } elseif (!$enPalabra) {
                $enPalabra = true;
                $codigo = ord($caracter);

                if ($codigo === 195 && $i + 1 < $total) {
                    $segundo = ord($this->frase[$i + 1]);
                    if ($segundo >= 160 && $segundo <= 190 && $segundo !== 183) {
                        $segundo = $segundo - 32;
                    }
                    $resultado .= chr($codigo) . chr($segundo);
                    $i += 2;
                } else {
                    if ($codigo >= 97 && $codigo <= 122) {
                        $codigo = $codigo - 32;
                    }
                    $resultado .= chr($codigo);
                    $i++;
                }
            } else {
                $i++;
            }
        }

        return $resultado;
    }

    public function estaVacia(): bool
    {
        $total = strlen($this->frase);
        for ($i = 0; $i < $total; $i++) {
            if (!$this->esSeparador($this->frase[$i])) {
                return false;
            }
        }
        return true;
    }
}
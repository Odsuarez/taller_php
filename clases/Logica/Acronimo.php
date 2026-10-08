<?php

/**
 * Genera el acrónimo de una frase usando solo lógica básica:
 * ciclos, condiciones y comparación de códigos de caracteres.
 */
class Acronimo
{
    private string $frase;

    public function __construct(string $frase)
    {
        $this->frase = $frase;
    }

    /** Devuelve true si el carácter es espacio, tabulación o salto de línea. */
    private function esSeparador(string $caracter): bool
    {
        return $caracter === ' ' || $caracter === "\t" || $caracter === "\n" || $caracter === "\r";
    }

    public function generar(): string
    {
        $resultado = '';
        $total = strlen($this->frase);
        $enPalabra = false;   // ¿estamos dentro de una palabra?
        $i = 0;

        while ($i < $total) {
            $caracter = $this->frase[$i];

            if ($this->esSeparador($caracter)) {
                // Un separador termina la palabra actual
                $enPalabra = false;
                $i++;
            } elseif (!$enPalabra) {
                // Primera letra de una palabra nueva: la tomamos
                $enPalabra = true;
                $codigo = ord($caracter);

                if ($codigo === 195 && $i + 1 < $total) {
                    // Letras con tilde/ñ en UTF-8 ocupan 2 bytes y empiezan con 195 (0xC3).
                    // Las minúsculas (á, é, ñ...) tienen el 2.º byte entre 160 y 190
                    // y su mayúscula está 32 posiciones antes (excepto el 247, que es "÷").
                    $segundo = ord($this->frase[$i + 1]);
                    if ($segundo >= 160 && $segundo <= 190 && $segundo !== 183) {
                        $segundo = $segundo - 32;
                    }
                    $resultado .= chr($codigo) . chr($segundo);
                    $i += 2;
                } else {
                    // Letra normal: de 'a' (97) a 'z' (122) se pasa a mayúscula restando 32
                    if ($codigo >= 97 && $codigo <= 122) {
                        $codigo = $codigo - 32;
                    }
                    $resultado .= chr($codigo);
                    $i++;
                }
            } else {
                // Resto de letras de la palabra: se ignoran
                $i++;
            }
        }

        return $resultado;
    }

    /** Indica si la frase no tiene ninguna letra (vacía o solo separadores). */
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

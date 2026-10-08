<?php

/**
 * Mensajes "de una sola vez" entre páginas (patrón Post/Redirect/Get).
 * Lo que se guarda con guardar() se entrega una sola vez con recoger()
 * y luego se borra, así al recargar la página ya no queda nada.
 *
 * Cada página usa su propio ámbito, así no se mezclan los datos entre ejercicios.
 */
class Flash
{
    private string $ambito;

    public function __construct(string $ambito)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->ambito = $ambito;
    }

    /** Guarda un dato para mostrarlo en la siguiente visita. */
    public function guardar(string $clave, $valor): void
    {
        $_SESSION['flash'][$this->ambito][$clave] = $valor;
    }

    /** Devuelve todo lo guardado y lo borra. Si no hay nada, devuelve []. */
    public function recoger(): array
    {
        $datos = $_SESSION['flash'][$this->ambito] ?? [];
        unset($_SESSION['flash'][$this->ambito]);
        return $datos;
    }

    /** Redirige a otra dirección y detiene el script. */
    public function redirigir(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}

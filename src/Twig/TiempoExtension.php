<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TiempoExtension extends AbstractExtension
{
    const CONFIGURACION = [
        'formato' => 'd/m/Y H:i:s'
    ];

    public function getFilters(): array
    {
        return [
            new TwigFilter('tiempo', [$this, 'formatearTiempo']),
        ];
    }

    public function formatearTiempo(\DateTime $fecha, array $configuracion = [])
    {
        $configuracion = array_merge(self::CONFIGURACION, $configuracion);
        $fechaActual = new \DateTime();
        $fechaFormateada = $fecha->format($configuracion['formato']);
        $diferenciaSegundos = $fechaActual->getTimestamp() - $fecha->getTimestamp();

        if ($diferenciaSegundos < 60) {
            $fechaFormateada = 'Creada hace unos segundos';
        } elseif ($diferenciaSegundos < 3600) {
            $fechaFormateada = 'Creada recientemente';
        } elseif ($diferenciaSegundos < 14400) {
            $fechaFormateada = 'Creada hace unas horas';
        }

        return $fechaFormateada;
    }
}

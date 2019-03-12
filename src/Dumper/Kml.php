<?php

namespace Map\Laravel\Dumper;

use Illuminate\Support\Collection;

class Kml implements Dumper
{
    /**
     * @param string $name
     * @param Collection $coordinates
     * @return string
     */
    public function dumpRoute(string $name, Collection $coordinates): string
    {
        $points = $coordinates->getThroughPoints()
            ->transform(function ($coordinate) { return $coordinate->getLatitude() . ',' . $coordinate->getLongitude(); })
            ->implode(' ');

        $kml = <<<'KML'
<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2">
    <Document>
        <name>Маршрут</name>
        <description>KML вариант маршрута по переданным точкам.</description>
        <Style id="yellowLineGreenPoly">
            <LineStyle>
                <color>
                    7f00ffff
                </color>
                <width>
                    4
                </width>
            </LineStyle>
            <PolyStyle>
                <color>
                    7f00ff00
                </color>
            </PolyStyle>
        </Style>
        <Placemark>
            <name><![CDATA[%s]]></name>
            <description><![CDATA[%s]]></description>
            <styleUrl>#yellowLineGreenPoly</styleUrl>
            <LineString>
                <extrude>1</extrude>
                <tessellate>1</tessellate>
                <altitudeMode>absolute</altitudeMode>
                <coordinates>%s</coordinates>
            </LineString>
        </Placemark>
    </Document>
</kml>
KML;

        return sprintf($kml, $name, $name, $points);
    }
}

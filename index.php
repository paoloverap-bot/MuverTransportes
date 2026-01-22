<?php
include 'funciones.php';
try {
    $direccion = "Av. Pajaritos 1234, Maipú, Santiago, Chile";
    $geo = geocodeAddress($direccion);

    echo "Dirección normalizada: " . $geo['formatted_address'] . PHP_EOL;
    echo "Latitud: " . $geo['lat'] . PHP_EOL;
    echo "Longitud: " . $geo['lng'] . PHP_EOL;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


?>

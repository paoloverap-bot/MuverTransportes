<?php
/**
 * API para guardar búsqueda de servicio
 * Muver - Plataforma de cotización de mudanzas
 */

header('Content-Type: application/json; charset=utf-8');

// Incluir configuración
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../funciones.php';

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
    exit;
}

try {
    // Validar campos requeridos
    $camposRequeridos = ['lugar_partida', 'lugar_destino', 'id_tipo_servicio'];
    $errores = [];
    
    foreach ($camposRequeridos as $campo) {
        if (empty($_POST[$campo])) {
            $errores[] = "El campo '$campo' es requerido";
        }
    }
    
    if (!empty($errores)) {
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $errores)
        ]);
        exit;
    }
    
    // Sanitizar datos
    $lugarPartida = trim($_POST['lugar_partida']);
    $lugarDestino = trim($_POST['lugar_destino']);
    $idTipoServicio = (int)$_POST['id_tipo_servicio'];
    $regionOrigen = !empty($_POST['region_origen']) ? trim($_POST['region_origen']) : null;
    $regionDestino = !empty($_POST['region_destino']) ? trim($_POST['region_destino']) : null;
    
    // Obtener información adicional
    $ipOrigen = getClientIP();
    $userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
    $dispositivo = detectarDispositivo();
    
    // Variables para distancia y duración (se calcularán con geocoding)
    $distanciaKm = null;
    $duracionMin = null;
    
    // Intentar geocodificar las direcciones para obtener más info
    try {
        $geoOrigen = geocodeAddress($lugarPartida);
        $geoDestino = geocodeAddress($lugarDestino);
        
        // Actualizar direcciones con formato normalizado
        $lugarPartida = $geoOrigen['formatted_address'];
        $lugarDestino = $geoDestino['formatted_address'];
        
        // Calcular distancia aproximada (Haversine)
        $distanciaKm = calcularDistanciaHaversine(
            $geoOrigen['lat'], $geoOrigen['lng'],
            $geoDestino['lat'], $geoDestino['lng']
        );
        
        // Estimar duración (promedio 60 km/h)
        $duracionMin = round(($distanciaKm / 60) * 60);
        
    } catch (Exception $e) {
        // Si falla el geocoding, continuar sin la información adicional
        error_log("Error geocoding: " . $e->getMessage());
    }
    
    // Insertar en la base de datos
    $pdo = getConnection();
    
    $sql = "INSERT INTO busqueda_servicio (
                fecha_busqueda, 
                lugar_partida, 
                lugar_destino, 
                region_origen, 
                region_destino, 
                distancia_km, 
                duracion_min, 
                ip_origen, 
                user_agent, 
                dispositivo, 
                convertido_cotizacion, 
                id_tipo_servicio
            ) VALUES (
                NOW(), 
                :lugar_partida, 
                :lugar_destino, 
                :region_origen, 
                :region_destino, 
                :distancia_km, 
                :duracion_min, 
                :ip_origen, 
                :user_agent, 
                :dispositivo, 
                0, 
                :id_tipo_servicio
            )";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':lugar_partida' => $lugarPartida,
        ':lugar_destino' => $lugarDestino,
        ':region_origen' => $regionOrigen,
        ':region_destino' => $regionDestino,
        ':distancia_km' => $distanciaKm,
        ':duracion_min' => $duracionMin,
        ':ip_origen' => $ipOrigen,
        ':user_agent' => $userAgent,
        ':dispositivo' => $dispositivo,
        ':id_tipo_servicio' => $idTipoServicio
    ]);
    
    $idBusqueda = $pdo->lastInsertId();
    
    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'message' => '¡Búsqueda registrada correctamente! Pronto recibirás cotizaciones de nuestros proveedores.',
        'id_busqueda' => $idBusqueda,
        'datos' => [
            'origen' => $lugarPartida,
            'destino' => $lugarDestino,
            'distancia_km' => $distanciaKm,
            'duracion_estimada_min' => $duracionMin
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Error BD: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al procesar la solicitud. Por favor, intente nuevamente.'
    ]);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error inesperado. Por favor, intente nuevamente.'
    ]);
}

/**
 * Calcula la distancia entre dos puntos usando la fórmula de Haversine
 * @param float $lat1 Latitud punto 1
 * @param float $lng1 Longitud punto 1
 * @param float $lat2 Latitud punto 2
 * @param float $lng2 Longitud punto 2
 * @return float Distancia en kilómetros
 */
function calcularDistanciaHaversine($lat1, $lng1, $lat2, $lng2): float {
    $radioTierra = 6371; // Radio de la Tierra en km
    
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng / 2) * sin($dLng / 2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
    return round($radioTierra * $c, 2);
}
?>

<?php
/**
 * API para obtener cotizaciones de proveedores
 * Calcula distancias usando Google Maps Distance Matrix API
 * Calcula precio basado en: precio_base_servicio + costo_combustible
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../config/database.php';

// API Key de Google Maps
define('GOOGLE_MAPS_API_KEY', 'AIzaSyAiqMuCjLrbE7h6sW87VOq7Cy0OgluTqDU');

try {
    // Obtener parámetros
    $origenLat = isset($_REQUEST['origen_lat']) ? floatval($_REQUEST['origen_lat']) : null;
    $origenLng = isset($_REQUEST['origen_lng']) ? floatval($_REQUEST['origen_lng']) : null;
    $destinoLat = isset($_REQUEST['destino_lat']) ? floatval($_REQUEST['destino_lat']) : null;
    $destinoLng = isset($_REQUEST['destino_lng']) ? floatval($_REQUEST['destino_lng']) : null;
    $origenDireccion = isset($_REQUEST['origen_direccion']) ? trim($_REQUEST['origen_direccion']) : '';
    $destinoDireccion = isset($_REQUEST['destino_direccion']) ? trim($_REQUEST['destino_direccion']) : '';
    $idTipoServicio = isset($_REQUEST['id_tipo_servicio']) ? intval($_REQUEST['id_tipo_servicio']) : 1;

    // Validar coordenadas
    if (!$origenLat || !$origenLng || !$destinoLat || !$destinoLng) {
        throw new Exception('Coordenadas de origen y destino son requeridas');
    }

    $pdo = getConnection();

    // Obtener el tipo de servicio con su precio base
    $stmtServicio = $pdo->prepare("
        SELECT id_tipo_servicio, descripcion, precio_base 
        FROM tipo_servicio 
        WHERE id_tipo_servicio = ? AND estado = 1
    ");
    $stmtServicio->execute([$idTipoServicio]);
    $tipoServicio = $stmtServicio->fetch();

    if (!$tipoServicio) {
        // Valor por defecto si no existe el tipo de servicio
        $tipoServicio = [
            'id_tipo_servicio' => 1,
            'descripcion' => 'Mudanza Residencial',
            'precio_base' => 15000.00
        ];
    }

    // Obtener proveedores activos con información de combustible
    $stmt = $pdo->query("
        SELECT p.*, r.nombre_region, pc.descripcion as tipo_combustible, pc.precio as precio_combustible
        FROM proveedor p 
        LEFT JOIN region r ON p.id_region_domicilio = r.id_region
        LEFT JOIN precio_combustible pc ON p.id_combustible = pc.id_combustible
        WHERE p.estado = 1 
        AND p.latitud IS NOT NULL 
        AND p.longitud IS NOT NULL
        ORDER BY p.calificacion DESC
    ");
    $proveedores = $stmt->fetchAll();

    if (empty($proveedores)) {
        echo json_encode([
            'success' => false,
            'message' => 'No hay proveedores disponibles'
        ]);
        exit;
    }

    // Calcular distancias y cotizaciones para cada proveedor
    $cotizaciones = [];
    
    foreach ($proveedores as $proveedor) {
        // Calcular las 3 distancias usando Distance Matrix API
        $distancias = calcularDistanciasRuta(
            $proveedor['latitud'], 
            $proveedor['longitud'],
            $origenLat, 
            $origenLng,
            $destinoLat, 
            $destinoLng
        );
        
        if ($distancias) {
            // Calcular distancia total
            $distanciaTotal = $distancias['base_a_origen']['distancia_km'] + 
                             $distancias['origen_a_destino']['distancia_km'] + 
                             $distancias['destino_a_base']['distancia_km'];
            
            // ============================================
            // NUEVO CÁLCULO DE PRECIO
            // ============================================
            // 1. Precio base del servicio (de tipo_servicio)
            $precioBaseServicio = floatval($tipoServicio['precio_base']);
            
            // 2. Calcular litros necesarios = Distancia Total / km_x_litro
            $kmPorLitro = floatval($proveedor['km_x_litro']) ?: 8.0; // Default 8 km/L
            $litrosNecesarios = $distanciaTotal / $kmPorLitro;
            
            // 3. Calcular costo de combustible = Litros * Precio Combustible
            $precioCombustible = floatval($proveedor['precio_combustible']) ?: 980.00; // Default Diesel
            $costoCombustible = $litrosNecesarios * $precioCombustible;
            
            // 4. Precio final = Precio Base Servicio + Costo Combustible
            $precioFinal = $precioBaseServicio + $costoCombustible;
            
            // Aplicar precio mínimo si existe
            $precioMinimo = floatval($proveedor['precio_minimo']) ?: 0;
            if ($precioMinimo > 0 && $precioFinal < $precioMinimo) {
                $precioFinal = $precioMinimo;
            }
            
            $cotizaciones[] = [
                'proveedor' => [
                    'id' => $proveedor['id_proveedor'],
                    'nombre' => $proveedor['nombre'],
                    'direccion' => $proveedor['direccion'],
                    'latitud' => floatval($proveedor['latitud']),
                    'longitud' => floatval($proveedor['longitud']),
                    'telefono' => $proveedor['telefono'],
                    'email' => $proveedor['email'],
                    'calificacion' => floatval($proveedor['calificacion']),
                    'total_servicios' => intval($proveedor['total_servicios']),
                    'region' => $proveedor['nombre_region'],
                    'km_x_litro' => $kmPorLitro,
                    'tipo_combustible' => $proveedor['tipo_combustible'] ?: 'Diesel'
                ],
                'distancias' => $distancias,
                'distancia_total_km' => round($distanciaTotal, 2),
                'duracion_total_min' => $distancias['base_a_origen']['duracion_min'] + 
                                       $distancias['origen_a_destino']['duracion_min'] + 
                                       $distancias['destino_a_base']['duracion_min'],
                // Detalle del cálculo
                'detalle_calculo' => [
                    'precio_base_servicio' => round($precioBaseServicio, 0),
                    'tipo_servicio' => $tipoServicio['descripcion'],
                    'km_x_litro' => $kmPorLitro,
                    'litros_necesarios' => round($litrosNecesarios, 2),
                    'precio_combustible_litro' => round($precioCombustible, 0),
                    'tipo_combustible' => $proveedor['tipo_combustible'] ?: 'Diesel',
                    'costo_combustible' => round($costoCombustible, 0),
                    'precio_minimo' => round($precioMinimo, 0)
                ],
                'precio_calculado' => round($precioFinal, 0)
            ];
        }
    }

    // Ordenar por precio
    usort($cotizaciones, function($a, $b) {
        return $a['precio_calculado'] - $b['precio_calculado'];
    });

    echo json_encode([
        'success' => true,
        'origen' => [
            'direccion' => $origenDireccion,
            'lat' => $origenLat,
            'lng' => $origenLng
        ],
        'destino' => [
            'direccion' => $destinoDireccion,
            'lat' => $destinoLat,
            'lng' => $destinoLng
        ],
        'tipo_servicio' => $tipoServicio,
        'total_proveedores' => count($cotizaciones),
        'cotizaciones' => $cotizaciones
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

/**
 * Calcula las distancias de la ruta completa usando Google Distance Matrix API
 * @param float $baseLat Latitud base del proveedor
 * @param float $baseLng Longitud base del proveedor
 * @param float $origenLat Latitud origen cliente
 * @param float $origenLng Longitud origen cliente
 * @param float $destinoLat Latitud destino cliente
 * @param float $destinoLng Longitud destino cliente
 * @return array|null
 */
function calcularDistanciasRuta($baseLat, $baseLng, $origenLat, $origenLng, $destinoLat, $destinoLng): ?array {
    // Construir los puntos
    $puntoBase = "$baseLat,$baseLng";
    $puntoOrigen = "$origenLat,$origenLng";
    $puntoDestino = "$destinoLat,$destinoLng";
    
    // Hacer una sola llamada con múltiples orígenes y destinos
    $origins = implode('|', [$puntoBase, $puntoOrigen, $puntoDestino]);
    $destinations = implode('|', [$puntoOrigen, $puntoDestino, $puntoBase]);
    
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?" . http_build_query([
        'origins' => $origins,
        'destinations' => $destinations,
        'mode' => 'driving',
        'language' => 'es',
        'key' => GOOGLE_MAPS_API_KEY
    ]);
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    
    $response = curl_exec($ch);
    
    if ($response === false) {
        error_log('Error cURL Distance Matrix: ' . curl_error($ch));
        curl_close($ch);
        return null;
    }
    
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if ($data['status'] !== 'OK') {
        error_log('Error Distance Matrix API: ' . $data['status']);
        // Fallback a cálculo Haversine si la API falla
        return calcularDistanciasHaversine($baseLat, $baseLng, $origenLat, $origenLng, $destinoLat, $destinoLng);
    }
    
    // Extraer las distancias de la matriz
    // rows[0] = desde base, rows[1] = desde origen, rows[2] = desde destino
    // elements[0] = hacia origen, elements[1] = hacia destino, elements[2] = hacia base
    
    $resultado = [
        'base_a_origen' => [
            'distancia_km' => 0,
            'distancia_texto' => 'N/A',
            'duracion_min' => 0,
            'duracion_texto' => 'N/A'
        ],
        'origen_a_destino' => [
            'distancia_km' => 0,
            'distancia_texto' => 'N/A',
            'duracion_min' => 0,
            'duracion_texto' => 'N/A'
        ],
        'destino_a_base' => [
            'distancia_km' => 0,
            'distancia_texto' => 'N/A',
            'duracion_min' => 0,
            'duracion_texto' => 'N/A'
        ]
    ];
    
    // Base a Origen (row 0, element 0)
    if (isset($data['rows'][0]['elements'][0]) && $data['rows'][0]['elements'][0]['status'] === 'OK') {
        $resultado['base_a_origen'] = [
            'distancia_km' => round($data['rows'][0]['elements'][0]['distance']['value'] / 1000, 2),
            'distancia_texto' => $data['rows'][0]['elements'][0]['distance']['text'],
            'duracion_min' => round($data['rows'][0]['elements'][0]['duration']['value'] / 60),
            'duracion_texto' => $data['rows'][0]['elements'][0]['duration']['text']
        ];
    }
    
    // Origen a Destino (row 1, element 1)
    if (isset($data['rows'][1]['elements'][1]) && $data['rows'][1]['elements'][1]['status'] === 'OK') {
        $resultado['origen_a_destino'] = [
            'distancia_km' => round($data['rows'][1]['elements'][1]['distance']['value'] / 1000, 2),
            'distancia_texto' => $data['rows'][1]['elements'][1]['distance']['text'],
            'duracion_min' => round($data['rows'][1]['elements'][1]['duration']['value'] / 60),
            'duracion_texto' => $data['rows'][1]['elements'][1]['duration']['text']
        ];
    }
    
    // Destino a Base (row 2, element 2)
    if (isset($data['rows'][2]['elements'][2]) && $data['rows'][2]['elements'][2]['status'] === 'OK') {
        $resultado['destino_a_base'] = [
            'distancia_km' => round($data['rows'][2]['elements'][2]['distance']['value'] / 1000, 2),
            'distancia_texto' => $data['rows'][2]['elements'][2]['distance']['text'],
            'duracion_min' => round($data['rows'][2]['elements'][2]['duration']['value'] / 60),
            'duracion_texto' => $data['rows'][2]['elements'][2]['duration']['text']
        ];
    }
    
    return $resultado;
}

/**
 * Fallback: Calcula distancias usando fórmula Haversine
 */
function calcularDistanciasHaversine($baseLat, $baseLng, $origenLat, $origenLng, $destinoLat, $destinoLng): array {
    return [
        'base_a_origen' => [
            'distancia_km' => haversine($baseLat, $baseLng, $origenLat, $origenLng),
            'distancia_texto' => round(haversine($baseLat, $baseLng, $origenLat, $origenLng), 1) . ' km',
            'duracion_min' => round(haversine($baseLat, $baseLng, $origenLat, $origenLng) * 1.5), // Estimado
            'duracion_texto' => round(haversine($baseLat, $baseLng, $origenLat, $origenLng) * 1.5) . ' min'
        ],
        'origen_a_destino' => [
            'distancia_km' => haversine($origenLat, $origenLng, $destinoLat, $destinoLng),
            'distancia_texto' => round(haversine($origenLat, $origenLng, $destinoLat, $destinoLng), 1) . ' km',
            'duracion_min' => round(haversine($origenLat, $origenLng, $destinoLat, $destinoLng) * 1.5),
            'duracion_texto' => round(haversine($origenLat, $origenLng, $destinoLat, $destinoLng) * 1.5) . ' min'
        ],
        'destino_a_base' => [
            'distancia_km' => haversine($destinoLat, $destinoLng, $baseLat, $baseLng),
            'distancia_texto' => round(haversine($destinoLat, $destinoLng, $baseLat, $baseLng), 1) . ' km',
            'duracion_min' => round(haversine($destinoLat, $destinoLng, $baseLat, $baseLng) * 1.5),
            'duracion_texto' => round(haversine($destinoLat, $destinoLng, $baseLat, $baseLng) * 1.5) . ' min'
        ]
    ];
}

/**
 * Calcula distancia Haversine entre dos puntos
 */
function haversine($lat1, $lng1, $lat2, $lng2): float {
    $radioTierra = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng / 2) * sin($dLng / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return round($radioTierra * $c, 2);
}
?>

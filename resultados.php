<?php
/**
 * Página de Resultados - Cotizaciones de Proveedores
 * Muver - Plataforma de cotización de mudanzas
 */

require_once __DIR__ . '/config/database.php';

// Obtener parámetros de la URL
$origenLat = isset($_GET['origen_lat']) ? floatval($_GET['origen_lat']) : null;
$origenLng = isset($_GET['origen_lng']) ? floatval($_GET['origen_lng']) : null;
$destinoLat = isset($_GET['destino_lat']) ? floatval($_GET['destino_lat']) : null;
$destinoLng = isset($_GET['destino_lng']) ? floatval($_GET['destino_lng']) : null;
$origenDireccion = isset($_GET['origen_direccion']) ? urldecode($_GET['origen_direccion']) : '';
$destinoDireccion = isset($_GET['destino_direccion']) ? urldecode($_GET['destino_direccion']) : '';
$idTipoServicio = isset($_GET['id_tipo_servicio']) ? intval($_GET['id_tipo_servicio']) : 1;

// Validar que tenemos los datos necesarios
$datosValidos = $origenLat && $origenLng && $destinoLat && $destinoLng;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Resultados de cotización de mudanzas - Muver">
    <title>Cotizaciones Disponibles - Muver</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #f97316;
            --bg-gradient: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f1f5f9;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
        }
        
        .navbar-brand .brand-icon {
            color: var(--secondary-color);
        }
        
        .page-header {
            background: var(--bg-gradient);
            padding: 2rem 0;
            color: white;
            margin-bottom: 2rem;
        }
        
        .route-summary {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .route-point {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .route-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .route-icon.origin {
            background: #dcfce7;
            color: #16a34a;
        }
        
        .route-icon.destination {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .route-connector {
            width: 2px;
            height: 30px;
            background: #e2e8f0;
            margin-left: 19px;
        }
        
        #map {
            width: 100%;
            height: 400px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .provider-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .provider-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }
        
        .provider-card.best-price {
            border-color: #16a34a;
            position: relative;
        }
        
        .provider-card.best-price::before {
            content: '⭐ Mejor Precio';
            position: absolute;
            top: -12px;
            left: 20px;
            background: #16a34a;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .provider-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .provider-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }
        
        .provider-rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            color: #f59e0b;
            font-weight: 500;
        }
        
        .provider-price {
            text-align: right;
        }
        
        .price-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .price-label {
            font-size: 0.75rem;
            color: #64748b;
        }
        
        .distance-breakdown {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem 0;
        }
        
        .distance-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        
        .distance-item:last-child {
            border-bottom: none;
        }
        
        .distance-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #475569;
            font-size: 0.875rem;
        }
        
        .distance-value {
            font-weight: 600;
            color: #1e293b;
        }
        
        .distance-total {
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Estilos para el desglose de precio */
        .price-breakdown {
            background: #fefce8;
            border: 1px solid #fef08a;
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem 0;
        }
        
        .price-breakdown h6 {
            color: #854d0e;
            margin-bottom: 0.75rem;
        }
        
        .price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.4rem 0;
            border-bottom: 1px dashed #fde68a;
        }
        
        .price-item:last-of-type {
            border-bottom: none;
        }
        
        .price-label-detail {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #713f12;
            font-size: 0.8rem;
        }
        
        .price-value-detail {
            font-weight: 600;
            color: #854d0e;
            font-size: 0.875rem;
        }
        
        .price-total {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .price-total small {
            opacity: 0.85;
            font-size: 0.7rem;
        }
        
        .btn-contact {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-contact:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
            color: white;
        }
        
        .btn-show-route {
            background: #f1f5f9;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: #475569;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-show-route:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: #eff6ff;
        }
        
        .btn-show-route.active {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: #eff6ff;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .loading-spinner-large {
            width: 60px;
            height: 60px;
            border: 4px solid #e2e8f0;
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 16px;
        }
        
        .empty-state-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }
        
        .services-count {
            font-size: 0.75rem;
            color: #64748b;
        }
        
        .provider-contact-info {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #64748b;
        }
        
        @media (max-width: 768px) {
            .provider-header {
                flex-direction: column;
            }
            
            .provider-price {
                text-align: left;
                margin-top: 1rem;
            }
            
            #map {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner-large mb-3"></div>
        <h5 class="text-primary">Buscando proveedores...</h5>
        <p class="text-muted">Calculando rutas y cotizaciones</p>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--bg-gradient);">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-truck brand-icon"></i> Muver
            </a>
            <a href="index.php" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Nueva Cotización
            </a>
        </div>
    </nav>

    <?php if (!$datosValidos): ?>
    <!-- Error: No hay datos -->
    <div class="container py-5">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h4>Datos incompletos</h4>
            <p class="text-muted">No se encontraron los datos necesarios para calcular las cotizaciones.</p>
            <a href="index.php" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left me-2"></i> Volver a Cotizar
            </a>
        </div>
    </div>
    <?php else: ?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h4 class="mb-1"><i class="bi bi-list-check me-2"></i>Cotizaciones Disponibles</h4>
            <p class="mb-0 opacity-75">Proveedores ordenados por mejor precio</p>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            <!-- Columna Izquierda: Mapa y Resumen -->
            <div class="col-lg-5 mb-4">
                <!-- Resumen de Ruta -->
                <div class="route-summary">
                    <h6 class="mb-3"><i class="bi bi-signpost-2 me-2 text-primary"></i>Tu Ruta</h6>
                    
                    <div class="route-point">
                        <div class="route-icon origin">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div>
                            <small class="text-muted">Origen</small>
                            <p class="mb-0 fw-medium" id="origenTexto"><?php echo htmlspecialchars($origenDireccion); ?></p>
                        </div>
                    </div>
                    
                    <div class="route-connector"></div>
                    
                    <div class="route-point">
                        <div class="route-icon destination">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted">Destino</small>
                            <p class="mb-0 fw-medium" id="destinoTexto"><?php echo htmlspecialchars($destinoDireccion); ?></p>
                        </div>
                    </div>
                    
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Distancia directa:</span>
                            <strong id="distanciaDirecta">Calculando...</strong>
                        </div>
                    </div>
                </div>
                
                <!-- Mapa -->
                <div id="map"></div>
                
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Haz clic en "Ver Ruta" en un proveedor para visualizar el recorrido completo
                    </small>
                </div>
            </div>
            
            <!-- Columna Derecha: Lista de Proveedores -->
            <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <span id="totalProveedores">0</span> Proveedores Disponibles
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-sort-down me-1"></i> Ordenar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="#" data-sort="precio">Menor Precio</a></li>
                            <li><a class="dropdown-item" href="#" data-sort="distancia">Menor Distancia</a></li>
                            <li><a class="dropdown-item" href="#" data-sort="calificacion">Mejor Calificación</a></li>
                        </ul>
                    </div>
                </div>
                
                <div id="listaProveedores">
                    <!-- Los proveedores se cargarán aquí dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAiqMuCjLrbE7h6sW87VOq7Cy0OgluTqDU&libraries=places,geometry"></script>
    
    <?php if ($datosValidos): ?>
    <script>
        // Datos de la búsqueda
        const searchData = {
            origen: {
                lat: <?php echo $origenLat; ?>,
                lng: <?php echo $origenLng; ?>,
                direccion: "<?php echo addslashes($origenDireccion); ?>"
            },
            destino: {
                lat: <?php echo $destinoLat; ?>,
                lng: <?php echo $destinoLng; ?>,
                direccion: "<?php echo addslashes($destinoDireccion); ?>"
            },
            idTipoServicio: <?php echo $idTipoServicio; ?>
        };
        
        let map;
        let directionsService;
        let directionsRenderer;
        let markers = [];
        let cotizacionesData = [];
        let proveedorActivo = null;
        
        // Inicializar el mapa
        function initMap() {
            const centroChile = { lat: -33.45, lng: -70.65 };
            
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: centroChile,
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    }
                ]
            });
            
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                suppressMarkers: false,
                polylineOptions: {
                    strokeColor: '#2563eb',
                    strokeWeight: 5,
                    strokeOpacity: 0.8
                }
            });
            
            // Agregar marcadores de origen y destino
            addMarker(searchData.origen.lat, searchData.origen.lng, 'Origen', '#16a34a', 'A');
            addMarker(searchData.destino.lat, searchData.destino.lng, 'Destino', '#dc2626', 'B');
            
            // Ajustar vista para mostrar ambos puntos
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(new google.maps.LatLng(searchData.origen.lat, searchData.origen.lng));
            bounds.extend(new google.maps.LatLng(searchData.destino.lat, searchData.destino.lng));
            map.fitBounds(bounds);
            
            // Calcular y mostrar distancia directa
            const distanciaDirecta = google.maps.geometry.spherical.computeDistanceBetween(
                new google.maps.LatLng(searchData.origen.lat, searchData.origen.lng),
                new google.maps.LatLng(searchData.destino.lat, searchData.destino.lng)
            );
            document.getElementById('distanciaDirecta').textContent = (distanciaDirecta / 1000).toFixed(1) + ' km';
            
            // Cargar cotizaciones
            cargarCotizaciones();
        }
        
        // Agregar marcador al mapa
        function addMarker(lat, lng, title, color, label) {
            const marker = new google.maps.Marker({
                position: { lat, lng },
                map: map,
                title: title,
                label: {
                    text: label,
                    color: 'white',
                    fontWeight: 'bold'
                },
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 12,
                    fillColor: color,
                    fillOpacity: 1,
                    strokeColor: 'white',
                    strokeWeight: 2
                }
            });
            markers.push(marker);
            return marker;
        }
        
        // Limpiar marcadores
        function clearMarkers() {
            markers.forEach(marker => marker.setMap(null));
            markers = [];
        }
        
        // Cargar cotizaciones desde la API
        async function cargarCotizaciones() {
            try {
                const params = new URLSearchParams({
                    origen_lat: searchData.origen.lat,
                    origen_lng: searchData.origen.lng,
                    destino_lat: searchData.destino.lat,
                    destino_lng: searchData.destino.lng,
                    origen_direccion: searchData.origen.direccion,
                    destino_direccion: searchData.destino.direccion,
                    id_tipo_servicio: searchData.idTipoServicio
                });
                
                const response = await fetch(`api/obtener_cotizaciones.php?${params}`);
                const data = await response.json();
                
                if (data.success) {
                    cotizacionesData = data.cotizaciones;
                    renderizarProveedores(cotizacionesData);
                    document.getElementById('totalProveedores').textContent = data.total_proveedores;
                } else {
                    mostrarError(data.message);
                }
            } catch (error) {
                console.error('Error cargando cotizaciones:', error);
                mostrarError('Error al cargar las cotizaciones. Por favor, intente nuevamente.');
            } finally {
                document.getElementById('loadingOverlay').style.display = 'none';
            }
        }
        
        // Renderizar lista de proveedores
        function renderizarProveedores(proveedores) {
            const container = document.getElementById('listaProveedores');
            
            if (proveedores.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h5>No hay proveedores disponibles</h5>
                        <p class="text-muted">No encontramos proveedores para esta ruta en este momento.</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = proveedores.map((cot, index) => `
                <div class="provider-card ${index === 0 ? 'best-price' : ''}" data-provider-id="${cot.proveedor.id}">
                    <div class="provider-header">
                        <div>
                            <div class="provider-name">${cot.proveedor.nombre}</div>
                            <div class="provider-rating">
                                <i class="bi bi-star-fill"></i>
                                <span>${cot.proveedor.calificacion.toFixed(1)}</span>
                                <span class="services-count ms-2 text-muted">
                                    (${cot.proveedor.total_servicios} servicios realizados)
                                </span>
                            </div>
                            <div class="provider-contact-info">
                                <span><i class="bi bi-geo-alt me-1"></i>${cot.proveedor.direccion}</span>
                            </div>
                        </div>
                        <div class="provider-price">
                            <div class="price-value">$${formatNumber(cot.precio_calculado)}</div>
                            <div class="price-label">Precio estimado CLP</div>
                        </div>
                    </div>
                    
                    <!-- Desglose de Distancias -->
                    <div class="distance-breakdown">
                        <h6 class="mb-2"><i class="bi bi-signpost me-1"></i> Distancias del Recorrido</h6>
                        <div class="distance-item">
                            <div class="distance-label">
                                <i class="bi bi-building text-primary"></i>
                                Base → Tu Origen
                            </div>
                            <div class="distance-value">${cot.distancias.base_a_origen.distancia_texto}</div>
                        </div>
                        <div class="distance-item">
                            <div class="distance-label">
                                <i class="bi bi-truck text-success"></i>
                                Tu Origen → Tu Destino
                            </div>
                            <div class="distance-value">${cot.distancias.origen_a_destino.distancia_texto}</div>
                        </div>
                        <div class="distance-item">
                            <div class="distance-label">
                                <i class="bi bi-arrow-return-left text-warning"></i>
                                Tu Destino → Base
                            </div>
                            <div class="distance-value">${cot.distancias.destino_a_base.distancia_texto}</div>
                        </div>
                        <div class="distance-total">
                            <span><i class="bi bi-signpost-2 me-2"></i>Distancia Total</span>
                            <strong>${cot.distancia_total_km} km</strong>
                        </div>
                    </div>
                    
                    <!-- Detalle del Cálculo del Precio -->
                    <div class="price-breakdown">
                        <h6 class="mb-2"><i class="bi bi-calculator me-1"></i> Detalle del Cálculo</h6>
                        <div class="price-item">
                            <div class="price-label-detail">
                                <i class="bi bi-box-seam text-primary"></i>
                                Precio Base (${cot.detalle_calculo.tipo_servicio})
                            </div>
                            <div class="price-value-detail">$${formatNumber(cot.detalle_calculo.precio_base_servicio)}</div>
                        </div>
                        <div class="price-item">
                            <div class="price-label-detail">
                                <i class="bi bi-speedometer2 text-info"></i>
                                Rendimiento del vehículo
                            </div>
                            <div class="price-value-detail">${cot.detalle_calculo.km_x_litro} km/L</div>
                        </div>
                        <div class="price-item">
                            <div class="price-label-detail">
                                <i class="bi bi-droplet text-warning"></i>
                                Litros necesarios (${cot.distancia_total_km} km ÷ ${cot.detalle_calculo.km_x_litro} km/L)
                            </div>
                            <div class="price-value-detail">${cot.detalle_calculo.litros_necesarios} L</div>
                        </div>
                        <div class="price-item">
                            <div class="price-label-detail">
                                <i class="bi bi-fuel-pump text-danger"></i>
                                Precio ${cot.detalle_calculo.tipo_combustible}
                            </div>
                            <div class="price-value-detail">$${formatNumber(cot.detalle_calculo.precio_combustible_litro)}/L</div>
                        </div>
                        <div class="price-item">
                            <div class="price-label-detail">
                                <i class="bi bi-cash-stack text-success"></i>
                                Costo Combustible (${cot.detalle_calculo.litros_necesarios} L × $${formatNumber(cot.detalle_calculo.precio_combustible_litro)})
                            </div>
                            <div class="price-value-detail">$${formatNumber(cot.detalle_calculo.costo_combustible)}</div>
                        </div>
                        <div class="price-total">
                            <div>
                                <i class="bi bi-receipt me-2"></i>
                                <strong>Total Estimado</strong>
                                <br><small>Precio Base + Combustible</small>
                            </div>
                            <strong class="fs-5">$${formatNumber(cot.precio_calculado)}</strong>
                        </div>
                    </div>
                    
                    <div class="row g-2 mt-3">
                        <div class="col-6">
                            <button class="btn btn-show-route" onclick="mostrarRuta(${cot.proveedor.id}, ${cot.proveedor.latitud}, ${cot.proveedor.longitud}, '${cot.proveedor.nombre}')">
                                <i class="bi bi-map me-1"></i> Ver Ruta
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-contact" onclick="contactarProveedor(${cot.proveedor.id})">
                                <i class="bi bi-whatsapp me-1"></i> Contactar
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        // Mostrar ruta en el mapa
        function mostrarRuta(proveedorId, provLat, provLng, provNombre) {
            // Marcar botón activo
            document.querySelectorAll('.btn-show-route').forEach(btn => btn.classList.remove('active'));
            event.target.closest('.btn-show-route').classList.add('active');
            
            // Calcular ruta con waypoints
            const request = {
                origin: { lat: provLat, lng: provLng },
                destination: { lat: provLat, lng: provLng },
                waypoints: [
                    { location: { lat: searchData.origen.lat, lng: searchData.origen.lng }, stopover: true },
                    { location: { lat: searchData.destino.lat, lng: searchData.destino.lng }, stopover: true }
                ],
                travelMode: google.maps.TravelMode.DRIVING,
                optimizeWaypoints: false
            };
            
            directionsService.route(request, (result, status) => {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                } else {
                    console.error('Error calculando ruta:', status);
                }
            });
        }
        
        // Contactar proveedor
        function contactarProveedor(proveedorId) {
            const proveedor = cotizacionesData.find(c => c.proveedor.id === proveedorId);
            if (proveedor && proveedor.proveedor.telefono) {
                const mensaje = encodeURIComponent(
                    `Hola, vi su servicio en Muver y me gustaría cotizar una mudanza:\n\n` +
                    `📍 Origen: ${searchData.origen.direccion}\n` +
                    `📍 Destino: ${searchData.destino.direccion}\n\n` +
                    `El precio estimado es de $${formatNumber(proveedor.precio_calculado)}. ¿Está disponible?`
                );
                const telefono = proveedor.proveedor.telefono.replace(/\s+/g, '').replace('+', '');
                window.open(`https://wa.me/${telefono}?text=${mensaje}`, '_blank');
            } else {
                alert('Información de contacto no disponible');
            }
        }
        
        // Mostrar error
        function mostrarError(mensaje) {
            document.getElementById('listaProveedores').innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    ${mensaje}
                </div>
            `;
        }
        
        // Formatear número con separadores de miles
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        // Ordenar proveedores
        document.querySelectorAll('[data-sort]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const sortBy = this.dataset.sort;
                
                document.querySelectorAll('[data-sort]').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                
                let sorted = [...cotizacionesData];
                
                switch(sortBy) {
                    case 'precio':
                        sorted.sort((a, b) => a.precio_calculado - b.precio_calculado);
                        break;
                    case 'distancia':
                        sorted.sort((a, b) => a.distancia_total_km - b.distancia_total_km);
                        break;
                    case 'calificacion':
                        sorted.sort((a, b) => b.proveedor.calificacion - a.proveedor.calificacion);
                        break;
                }
                
                renderizarProveedores(sorted);
            });
        });
        
        // Inicializar cuando la página cargue
        window.addEventListener('load', initMap);
    </script>
    <?php endif; ?>
</body>
</html>

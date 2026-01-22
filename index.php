<?php
/**
 * Página principal - Cotización de Mudanzas
 * Muver - Plataforma de cotización de mudanzas
 */

require_once __DIR__ . '/config/database.php';

// Obtener datos para los selectores
try {
    $tiposServicio = getTiposServicio();
    $regiones = getRegiones();
} catch (Exception $e) {
    $tiposServicio = [];
    $regiones = [];
    $errorDB = true;
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="floating-truck">
            <i class="bi bi-truck"></i>
        </div>
        
        <div class="container">
            <div class="row min-vh-100 align-items-center">
                <div class="col-lg-6 text-white hero-content py-5" style="margin-top: 60px;">
                    <h1 class="hero-title mb-4">
                        Tu mudanza, <br>
                        <span style="color: #fbbf24;">simple y sin estrés</span>
                    </h1>
                    <p class="hero-subtitle mb-4">
                        Conectamos a miles de personas con los mejores proveedores de mudanza y flete de Chile. 
                        Cotiza en segundos y recibe ofertas personalizadas.
                    </p>
                    
                    <div class="d-flex flex-wrap mb-4">
                        <span class="feature-badge">
                            <i class="bi bi-check-circle-fill"></i> Cotización gratuita
                        </span>
                        <span class="feature-badge">
                            <i class="bi bi-check-circle-fill"></i> +500 proveedores
                        </span>
                        <span class="feature-badge">
                            <i class="bi bi-check-circle-fill"></i> Cobertura nacional
                        </span>
                    </div>
                </div>
                
                <div class="col-lg-6 py-5">
                    <!-- Espacio para la tarjeta de cotización -->
                </div>
            </div>
        </div>
    </section>
    
    <!-- Quote Card -->
    <div class="container" style="margin-top: -350px; position: relative; z-index: 10;">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="quote-card">
                    <div class="quote-card-header">
                        <h2><i class="bi bi-search text-primary me-2"></i>Cotiza tu Mudanza</h2>
                        <p>Completa los datos y recibe cotizaciones de proveedores cercanos</p>
                    </div>
                    
                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step active">
                            <span class="step-number">1</span>
                            <span>Origen</span>
                        </div>
                        <div class="step active">
                            <span class="step-number">2</span>
                            <span>Destino</span>
                        </div>
                        <div class="step active">
                            <span class="step-number">3</span>
                            <span>Servicio</span>
                        </div>
                    </div>
                    
                    <!-- Alert Container -->
                    <div id="alertContainer" class="mb-3"></div>
                    
                    <?php if (isset($errorDB)): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        No se pudo conectar a la base de datos. Asegúrate de que el servidor MySQL esté activo.
                    </div>
                    <?php endif; ?>
                    
                    <!-- Formulario de Cotización -->
                    <form id="formCotizacion" method="POST" action="api/guardar_busqueda.php">
                        
                        <!-- Lugar de Partida -->
                        <div class="mb-4">
                            <label for="lugar_partida" class="form-label">
                                <i class="bi bi-geo-alt text-success me-1"></i> Dirección de Origen
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-house-door"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       id="lugar_partida" 
                                       name="lugar_partida" 
                                       placeholder="Ej: Av. Providencia 1234, Providencia, Santiago"
                                       required
                                       autocomplete="off">
                            </div>
                            <small class="text-muted">Ingresa la dirección completa incluyendo comuna y ciudad</small>
                        </div>
                        
                        <!-- Región Origen (opcional) -->
                        <div class="mb-4">
                            <label for="region_origen" class="form-label">
                                <i class="bi bi-map me-1"></i> Región de Origen
                            </label>
                            <select class="form-select" id="region_origen" name="region_origen">
                                <option value="">Selecciona una región (opcional)</option>
                                <?php foreach ($regiones as $region): ?>
                                <option value="<?php echo htmlspecialchars($region['nombre_region']); ?>">
                                    <?php echo htmlspecialchars($region['nombre_region']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Lugar de Destino -->
                        <div class="mb-4">
                            <label for="lugar_destino" class="form-label">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> Dirección de Destino
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-flag"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       id="lugar_destino" 
                                       name="lugar_destino" 
                                       placeholder="Ej: Los Carrera 567, Viña del Mar"
                                       required
                                       autocomplete="off">
                            </div>
                            <small class="text-muted">Ingresa la dirección de destino de tu mudanza</small>
                        </div>
                        
                        <!-- Región Destino (opcional) -->
                        <div class="mb-4">
                            <label for="region_destino" class="form-label">
                                <i class="bi bi-map-fill me-1"></i> Región de Destino
                            </label>
                            <select class="form-select" id="region_destino" name="region_destino">
                                <option value="">Selecciona una región (opcional)</option>
                                <?php foreach ($regiones as $region): ?>
                                <option value="<?php echo htmlspecialchars($region['nombre_region']); ?>">
                                    <?php echo htmlspecialchars($region['nombre_region']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Tipo de Servicio -->
                        <div class="mb-4">
                            <label for="id_tipo_servicio" class="form-label">
                                <i class="bi bi-truck me-1"></i> Tipo de Servicio
                            </label>
                            <select class="form-select" id="id_tipo_servicio" name="id_tipo_servicio" required>
                                <option value="">Selecciona el tipo de servicio</option>
                                <?php if (empty($tiposServicio)): ?>
                                    <option value="1">Mudanza Residencial</option>
                                    <option value="2">Mudanza Comercial</option>
                                    <option value="3">Flete</option>
                                <?php else: ?>
                                    <?php foreach ($tiposServicio as $tipo): ?>
                                    <option value="<?php echo $tipo['id_tipo_servicio']; ?>">
                                        <?php echo htmlspecialchars($tipo['descripcion']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <!-- Información adicional (colapsable) -->
                        <div class="mb-4">
                            <a class="btn btn-link text-decoration-none p-0" data-bs-toggle="collapse" href="#infoAdicional">
                                <i class="bi bi-plus-circle me-1"></i> Agregar información adicional (opcional)
                            </a>
                            <div class="collapse mt-3" id="infoAdicional">
                                <div class="card card-body bg-light border-0">
                                    <div class="mb-3">
                                        <label for="comentarios" class="form-label">Comentarios o detalles especiales</label>
                                        <textarea class="form-control" id="comentarios" name="comentarios" rows="3" 
                                                  placeholder="Ej: Tengo un piano, necesito embalaje especial, mudanza en fin de semana..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botón Submit -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-primary-custom" id="btnCotizar">
                                <span class="btn-text">
                                    <i class="bi bi-search me-2"></i> Buscar Proveedores
                                </span>
                                <span class="loading-spinner">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Buscando...
                                </span>
                            </button>
                        </div>
                        
                        <p class="text-center text-muted mt-3 mb-0">
                            <i class="bi bi-shield-check me-1"></i> 
                            Tu información está protegida y no será compartida
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Espaciador -->
    <div style="height: 100px;"></div>

<?php include __DIR__ . '/includes/footer.php'; ?>

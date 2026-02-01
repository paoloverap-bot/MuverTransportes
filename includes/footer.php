    <!-- Features Section -->
    <section class="features-section" id="como-funciona">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">¿Por qué elegir Muver?</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h4>Búsqueda Inteligente</h4>
                        <p class="text-muted">Encuentra proveedores de mudanza cercanos a tu ubicación automáticamente.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-calculator"></i>
                        </div>
                        <h4>Cotización Instantánea</h4>
                        <p class="text-muted">Obtén precios precisos basados en la distancia real de tu mudanza.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Proveedores Verificados</h4>
                        <p class="text-muted">Todos nuestros proveedores están verificados y evaluados por clientes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contacto">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3"><i class="bi bi-truck"></i> Muver</h5>
                    <p>La plataforma líder en cotización de servicios de mudanza y flete en Chile.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3">Enlaces</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Términos y Condiciones</a></li>
                        <li><a href="#">Política de Privacidad</a></li>
                        <li><a href="#">Centro de Ayuda</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3">Contacto</h5>
                    <p><i class="bi bi-envelope me-2"></i> contacto@muver.cl</p>
                    <p><i class="bi bi-telephone me-2"></i> +56 9 1234 5678</p>
                    <div class="mt-3">
                        <a href="#" class="me-3"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#"><i class="bi bi-twitter-x fs-4"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: #334155;">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> Muver. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Validación del formulario antes de enviar -->
    <script>
        // Función para manejar el envío del formulario
        document.getElementById('formCotizacion')?.addEventListener('submit', function(e) {
            const btn = document.getElementById('btnCotizar');
            const alertContainer = document.getElementById('alertContainer');
            
            // Obtener coordenadas
            const origenLat = document.getElementById('origen_lat')?.value;
            const origenLng = document.getElementById('origen_lng')?.value;
            const destinoLat = document.getElementById('destino_lat')?.value;
            const destinoLng = document.getElementById('destino_lng')?.value;
            
            // Validar que se seleccionaron direcciones válidas del autocomplete
            if (!origenLat || !origenLng || origenLat === '' || origenLng === '') {
                e.preventDefault();
                alertContainer.innerHTML = `
                    <div class="alert alert-custom alert-danger-custom">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Por favor, selecciona una dirección de origen válida de las sugerencias.
                    </div>
                `;
                return false;
            }
            
            if (!destinoLat || !destinoLng || destinoLat === '' || destinoLng === '') {
                e.preventDefault();
                alertContainer.innerHTML = `
                    <div class="alert alert-custom alert-danger-custom">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Por favor, selecciona una dirección de destino válida de las sugerencias.
                    </div>
                `;
                return false;
            }
            
            // Mostrar loading mientras redirige
            btn.classList.add('loading');
            btn.disabled = true;
            
            // El formulario se enviará normalmente (GET a resultados.php)
            return true;
        });
    </script>
</body>
</html>

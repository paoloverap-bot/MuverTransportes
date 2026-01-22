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
    
    <!-- Google Maps Autocomplete (opcional para futuro) -->
    <script>
        // Función para manejar el envío del formulario con AJAX
        document.getElementById('formCotizacion')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('btnCotizar');
            const alertContainer = document.getElementById('alertContainer');
            
            // Mostrar loading
            btn.classList.add('loading');
            btn.disabled = true;
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch('api/guardar_busqueda.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alertContainer.innerHTML = `
                        <div class="alert alert-custom alert-success-custom">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            ${result.message}
                            <br><small>ID de búsqueda: #${result.id_busqueda}</small>
                        </div>
                    `;
                    // Opcional: limpiar formulario
                    // this.reset();
                } else {
                    alertContainer.innerHTML = `
                        <div class="alert alert-custom alert-danger-custom">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            ${result.message}
                        </div>
                    `;
                }
            } catch (error) {
                alertContainer.innerHTML = `
                    <div class="alert alert-custom alert-danger-custom">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Error de conexión. Por favor, intente nuevamente.
                    </div>
                `;
            } finally {
                btn.classList.remove('loading');
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>

    <!-- Footer -->
    <footer style="background: linear-gradient(135deg, #1a2e0a 0%, #2d5016 100%); color: white; padding: 3rem 0 1rem 0; border-top: 3px solid #4a7c59;">
        <div class="container">
            <div class="row">
                <!-- Información de la empresa -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3" style="color: #d4a574;">
                        <i class="bi bi-tree-fill me-2"></i>Aserradería Pequinez
                    </h5>
                    <p class="mb-3" style="line-height: 1.6;">
                        🌲 Maderas de calidad para tu hogar. Más de 25 años ofreciendo productos de madera selecta y elaborada para las familias ecuatorianas.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge" style="background: rgba(255, 255, 255, 0.9); color: #2d5016;">
                            <i class="bi bi-award-fill me-1"></i> Calidad Premium
                        </span>
                        <span class="badge" style="background: rgba(255, 255, 255, 0.9); color: #2d5016;">
                            <i class="bi bi-tree me-1"></i> Madera Selecta
                        </span>
                    </div>
                </div>
                
                <!-- Contacto -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3" style="color: #d4a574;">
                        <i class="bi bi-telephone-fill me-2"></i>Contacto
                    </h5>
                    <div class="contact-info">
                        <p class="mb-2">
                            <i class="bi bi-phone" style="color: #d4a574; margin-right: 8px;"></i>
                            <strong>+593 992470053</strong>
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-envelope" style="color: #d4a574; margin-right: 8px;"></i>
                            maderas@pequinez.com
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-geo-alt" style="color: #d4a574; margin-right: 8px;"></i>
                            Sector la Vasija, K5
                        </p>
                    </div>
                </div>
                
                <!-- Horarios -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3" style="color: #d4a574;">
                        <i class="bi bi-clock-fill me-2"></i>Horarios de Atención
                    </h5>
                    <div class="schedule-info">
                        <p class="mb-2">
                            <i class="bi bi-calendar-check" style="color: #6b8e23; margin-right: 8px;"></i>
                            <strong>Lunes a Viernes:</strong><br>
                            <span style="margin-left: 24px;">8:00 AM - 5:00 PM</span>
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-calendar-event" style="color: #6b8e23; margin-right: 8px;"></i>
                            <strong>Sábados:</strong><br>
                            <span style="margin-left: 24px;">8:00 AM - 3:00 PM</span>
                        </p>
                    </div>
                </div>
                
                <!-- Redes Sociales -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3" style="color: #d4a574;">
                        <i class="bi bi-share-fill me-2"></i>Síguenos
                    </h5>
                    <div class="social-links">
                        <a href="https://www.facebook.com/AserraderiaPequinez" target="_blank" 
                           class="text-white me-3 d-inline-block mb-2"
                           style="transition: color 0.3s; text-decoration: none;"
                           onmouseover="this.style.color='#d4a574'" 
                           onmouseout="this.style.color='white'">
                            <i class="bi bi-facebook me-2"></i>Facebook
                        </a>
                        <br>
                        <a href="https://www.tiktok.com/@aserraderiapequinez" target="_blank"
                           class="text-white me-3 d-inline-block mb-2"
                           style="transition: color 0.3s; text-decoration: none;"
                           onmouseover="this.style.color='#d4a574'" 
                           onmouseout="this.style.color='white'">
                            <i class="bi bi-tiktok me-2"></i>TikTok
                        </a>
                        <br>
                        <a href="https://wa.me/593992470053" target="_blank"
                           class="text-white me-3 d-inline-block mb-2"
                           style="transition: color 0.3s; text-decoration: none;"
                           onmouseover="this.style.color='#d4a574'" 
                           onmouseout="this.style.color='white'">
                            <i class="bi bi-whatsapp me-2"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Línea separadora -->
            <hr style="border-color: #4a7c59; margin: 2rem 0 1rem 0;">
            
            <!-- Copyright y enlaces adicionales -->
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="mb-0" style="color: rgba(255, 255, 255, 0.8);">
                        © 2025 Aserradería Pequinez. Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="footer-links">
                        <a href="<?php echo isset($basePath) ? $basePath : ''; ?>index.php" 
                           class="text-white me-3" 
                           style="text-decoration: none; transition: color 0.3s;"
                           onmouseover="this.style.color='#d4a574'" 
                           onmouseout="this.style.color='white'">
                            <i class="bi bi-house me-1"></i>Inicio
                        </a>
                        <a href="<?php echo isset($basePath) ? $basePath : ''; ?>public/Login/Quienessomos.html" 
                           class="text-white me-3" 
                           style="text-decoration: none; transition: color 0.3s;"
                           onmouseover="this.style.color='#d4a574'" 
                           onmouseout="this.style.color='white'">
                            <i class="bi bi-info-circle me-1"></i>Misión
                        </a>
                        <a href="<?php echo isset($basePath) ? $basePath : ''; ?>public/Login/Quienessomos.html#vision" 
                           class="text-white" 
                           style="text-decoration: none; transition: color 0.3s;"
                           onmouseover="this.style.color='#d4a574'" 
                           onmouseout="this.style.color='white'">
                            <i class="bi bi-eye me-1"></i>Visión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if(isset($additionalJS)): ?>
        <!-- JavaScript adicional específico de la página -->
        <?php foreach($additionalJS as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- JavaScript personalizado -->
    <script>
        // Función de búsqueda
        function realizarBusqueda() {
            const searchInput = document.querySelector('.search-input');
            const searchTerm = searchInput.value.trim();
            
            if (searchTerm) {
                console.log('Buscando:', searchTerm);
                // Aquí puedes implementar la lógica de búsqueda
                alert(`Buscando: ${searchTerm}`);
            } else {
                alert('Por favor, ingresa un término de búsqueda');
            }
        }
        
        // Ejecutar búsqueda al presionar Enter
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        realizarBusqueda();
                    }
                });
            }
        });
    </script>

    </div> <!-- Cierre de main-content -->
</body>
</html>

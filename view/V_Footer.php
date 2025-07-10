<!-- Footer -->
<style>
.pie-pagina {
	background: linear-gradient(135deg, #1a2e0a 0%, #2d5016 100%);
	color: white;
	padding: 3rem 0 1rem 0;
	border-top: 3px solid #4a7c59;
}
.contenedor-pie {
	max-width: 1200px;
	margin: 0 auto;
}
.titulo-pie {
	color: #d4a574;
	font-weight: bold;
	margin-bottom: 1rem;
}
.descripcion-empresa {
	line-height: 1.6;
	margin-bottom: 1rem;
}
.etiqueta-pie {
	background: rgba(255,255,255,0.9);
	color: #2d5016;
	margin-right: 0.5rem;
	margin-bottom: 0.5rem;
}
.info-contacto i,
.info-horario i,
.enlaces-sociales a i,
.enlaces-pie a i {
	margin-right: 8px;
}
.info-contacto p,
.info-horario p {
	margin-bottom: 0.5rem;
}
.enlaces-sociales a,
.enlaces-pie a {
	color: white;
	text-decoration: none;
	transition: color 0.3s;
	display: inline-block;
	margin-bottom: 0.5rem;
	margin-right: 1rem;
}
.enlaces-sociales a:hover,
.enlaces-pie a:hover {
	color: #d4a574;
}
.separador-pie {
	border-color: #4a7c59;
	margin: 2rem 0 1rem 0;
}
.texto-copyright {
	color: rgba(255,255,255,0.8);
	margin-bottom: 0;
}
@media (max-width: 991px) {
	.contenedor-pie .row > div {
		margin-bottom: 1.5rem;
	}
}
</style>

<footer class="pie-pagina">
	<div class="container contenedor-pie">
		<div class="row">
			<!-- Información de la empresa -->
			<div class="col-lg-3 col-md-6 mb-4">
				<h5 class="titulo-pie">
					<i class="bi bi-tree-fill me-2"></i>Aserradería Pequinez
				</h5>
				<p class="descripcion-empresa">
					🌲 Maderas de calidad para tu hogar. Más de 25 años ofreciendo productos de madera selecta y elaborada para las familias ecuatorianas.
				</p>
				<div class="d-flex flex-wrap">
					<span class="badge etiqueta-pie">
						<i class="bi bi-award-fill"></i> Calidad Premium
					</span>
					<span class="badge etiqueta-pie">
						<i class="bi bi-tree"></i> Madera Selecta
					</span>
				</div>
			</div>
			
			<!-- Contacto -->
			<div class="col-lg-3 col-md-6 mb-4">
				<h5 class="titulo-pie">
					<i class="bi bi-telephone-fill me-2"></i>Contacto
				</h5>
				<div class="info-contacto">
					<p>
						<i class="bi bi-phone" style="color: #d4a574;"></i>
						<strong>+593 992470053</strong>
					</p>
					<p>
						<i class="bi bi-envelope" style="color: #d4a574;"></i>
						maderas@pequinez.com
					</p>
					<p>
						<i class="bi bi-geo-alt" style="color: #d4a574;"></i>
						Sector la Vasija, K5
					</p>
				</div>
			</div>
			
			<!-- Horarios -->
			<div class="col-lg-3 col-md-6 mb-4">
				<h5 class="titulo-pie">
					<i class="bi bi-clock-fill me-2"></i>Horarios de Atención
				</h5>
				<div class="info-horario">
					<p>
						<i class="bi bi-calendar-check" style="color: #6b8e23;"></i>
						<strong>Lunes a Viernes:</strong><br>
						<span style="margin-left: 24px;">8:00 AM - 5:00 PM</span>
					</p>
					<p>
						<i class="bi bi-calendar-event" style="color: #6b8e23;"></i>
						<strong>Sábados:</strong><br>
						<span style="margin-left: 24px;">8:00 AM - 3:00 PM</span>
					</p>
				</div>
			</div>
			
			<!-- Redes Sociales -->
			<div class="col-lg-3 col-md-6 mb-4">
				<h5 class="titulo-pie">
					<i class="bi bi-share-fill me-2"></i>Síguenos
				</h5>
				<div class="enlaces-sociales">
					<a href="https://www.facebook.com/AserraderiaPequinez" target="_blank">
						<i class="bi bi-facebook"></i>Facebook
					</a>
					<br>
					<a href="https://www.tiktok.com/@aserraderiapequinez" target="_blank">
						<i class="bi bi-tiktok"></i>TikTok
					</a>
					<br>
					<a href="https://wa.me/593992470053" target="_blank">
						<i class="bi bi-whatsapp"></i>WhatsApp
					</a>
				</div>
			</div>
		</div>
		
		<!-- Línea separadora -->
		<hr class="separador-pie">
		
		<!-- Copyright y enlaces adicionales -->
		<div class="row align-items-center">
			<div class="col-md-8">
				<p class="texto-copyright">
					© 2025 Aserradería Pequinez. Todos los derechos reservados.
				</p>
			</div>
			<div class="col-md-4 text-md-end">
				<div class="enlaces-pie">
					<a href="index.php">
						<i class="bi bi-house"></i>Inicio
					</a>
					<a href="public/Login/Quienessomos.html">
						<i class="bi bi-info-circle"></i>Misión
					</a>
					<a href="public/Login/Quienessomos.html#vision">
						<i class="bi bi-eye"></i>Visión
					</a>
				</div>
			</div>
		</div>
	</div>
</footer>

<!-- Scripts de Bootstrap y Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>
</html>

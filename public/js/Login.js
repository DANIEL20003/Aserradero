// const backgrounds = [ 'Imagenes/img4.png', 'Imagenes/img2.png' , 'Imagenes/img3.png']; 
// let currentIndex = 0;

    // function changeBackground() {
      // document.body.style.backgroundImage = `url(${backgrounds[currentIndex]})`;
      // currentIndex = (currentIndex + 1) % backgrounds.length;
    // }

// setInterval(changeBackground, 5000);

// Funciones globales para modales
function abrirModal(tipo) {
  console.log("Abriendo modal para:", tipo);
  // Cerrar modal de registro si está abierto
  const modalRegistro = document.getElementById("modalRegistro");
  if (modalRegistro && modalRegistro.hasAttribute('open')) {
    cerrarModalRegistro();
  }
  // Abrir modal de login
  const modalLogin = document.getElementById("modalLogin");
  if (modalLogin) {
    if (typeof modalLogin.showModal === 'function') {
      modalLogin.showModal();
    } else {
      // Fallback para navegadores sin soporte para dialog
      modalLogin.style.display = 'flex';
      modalLogin.style.position = 'fixed';
      modalLogin.style.top = '0';
      modalLogin.style.left = '0';
      modalLogin.style.width = '100vw';
      modalLogin.style.height = '100vh';
      modalLogin.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
      modalLogin.style.alignItems = 'center';
      modalLogin.style.justifyContent = 'center';
      modalLogin.style.zIndex = '1000';
      modalLogin.setAttribute('open', '');
      document.body.style.overflow = 'hidden';
    }
  }
}

function cerrarModal() {
  const modal = document.getElementById("modalLogin");
  if (modal) {
    if (typeof modal.close === 'function') {
      modal.close();
    } else {
      // Fallback para navegadores sin soporte para dialog
      modal.style.display = 'none';
      modal.removeAttribute('open');
      modal.style.position = '';
      modal.style.top = '';
      modal.style.left = '';
      modal.style.width = '';
      modal.style.height = '';
      modal.style.backgroundColor = '';
      document.body.style.overflow = '';
    }
  }
}

function Iniciar() {
  alert("Iniciando sesión...");
}

function Crearcuenta() {
  alert("Redirigiendo a crear cuenta...");
}

// Asegurar que las funciones estén disponibles globalmente
window.abrirModal = abrirModal;
window.cerrarModal = cerrarModal;
window.Iniciar = Iniciar;
window.Crearcuenta = Crearcuenta;

// Funciones para modal de contactos
function abrirModalContactos() {
  console.log("Abriendo modal de contactos");
  const modal = document.getElementById("modalContactos");
  if (modal) {
    if (typeof modal.showModal === 'function') {
      modal.showModal();
    } else {
      // Fallback para navegadores sin soporte para dialog
      modal.style.display = 'block';
      modal.setAttribute('open', '');
      document.body.style.overflow = 'hidden';
    }
  }
}

function cerrarModalContactos() {
  const modal = document.getElementById("modalContactos");
  if (modal) {
    if (typeof modal.close === 'function') {
      modal.close();
    } else {
      // Fallback para navegadores sin soporte para dialog
      modal.style.display = 'none';
      modal.removeAttribute('open');
      document.body.style.overflow = '';
    }
  }
}

// Asegurar que las funciones estén disponibles globalmente
window.abrirModalContactos = abrirModalContactos;
window.cerrarModalContactos = cerrarModalContactos;

// Funciones para modal de registro
function abrirModalRegistro() {
  console.log("Abriendo modal de registro...");
  const modal = document.getElementById("modalRegistro");
  if (modal) {
    if (typeof modal.showModal === 'function') {
      modal.showModal();
    } else {
      // Fallback para navegadores sin soporte para dialog
      modal.style.display = 'block';
      modal.setAttribute('open', '');
      document.body.style.overflow = 'hidden';
    }
    console.log("Modal de registro abierto");
  } else {
    console.error("Modal de registro no encontrado");
    alert("Error: No se encontró el modal de registro");
  }
}

function cerrarModalRegistro() {
  console.log("Cerrando modal de registro...");
  const modal = document.getElementById("modalRegistro");
  if (modal) {
    if (typeof modal.close === 'function') {
      modal.close();
    } else {
      // Fallback para navegadores sin soporte para dialog
      modal.style.display = 'none';
      modal.removeAttribute('open');
      document.body.style.overflow = '';
    }
  }
}

// Asegurar que las funciones estén disponibles globalmente
window.abrirModalRegistro = abrirModalRegistro;
window.cerrarModalRegistro = cerrarModalRegistro;

// Funciones para modal de catálogo
function abrirModalCatalogo() {
  const modal = document.getElementById("modalCatalogo");
  if (modal) {
    if (typeof modal.showModal === 'function') {
      modal.showModal();
    } else {
      modal.style.display = 'flex';
      modal.style.position = 'fixed';
      modal.style.top = '0';
      modal.style.left = '0';
      modal.style.width = '100vw';
      modal.style.height = '100vh';
      modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
      modal.style.alignItems = 'center';
      modal.style.justifyContent = 'center';
      modal.style.zIndex = '1000';
      modal.setAttribute('open', '');
      document.body.style.overflow = 'hidden';
    }
  }
}

function cerrarModalCatalogo() {
  const modal = document.getElementById("modalCatalogo");
  if (modal) {
    if (typeof modal.close === 'function') {
      modal.close();
    } else {
      modal.style.display = 'none';
      modal.removeAttribute('open');
      document.body.style.overflow = '';
    }
  }
}

// Funciones para modal de contacto
function abrirModalContacto() {
  const modal = document.getElementById("modalContacto");
  if (modal) {
    if (typeof modal.showModal === 'function') {
      modal.showModal();
    } else {
      modal.style.display = 'flex';
      modal.style.position = 'fixed';
      modal.style.top = '0';
      modal.style.left = '0';
      modal.style.width = '100vw';
      modal.style.height = '100vh';
      modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
      modal.style.alignItems = 'center';
      modal.style.justifyContent = 'center';
      modal.style.zIndex = '1000';
      modal.setAttribute('open', '');
      document.body.style.overflow = 'hidden';
    }
  }
}

function cerrarModalContacto() {
  const modal = document.getElementById("modalContacto");
  if (modal) {
    if (typeof modal.close === 'function') {
      modal.close();
    } else {
      modal.style.display = 'none';
      modal.removeAttribute('open');
      document.body.style.overflow = '';
    }
  }
}

// Función actualizada de búsqueda específica para aserradero
function realizarBusqueda() {
  const input = document.getElementById('searchInput');
  const searchTerm = input.value.toLowerCase().trim();
  
  if (searchTerm === '') {
    alert('🔍 Por favor, ingresa un término de búsqueda.\n\nPuedes buscar:\n• Tipos de madera (eucalipto, pino, cedro)\n• Productos (tablones, vigas, tablas)\n• Medidas (2x4, 2x6, etc.)');
    return;
  }
  
  // Términos relacionados con aserradero
  const productos = {
    'eucalipto': 'Madera de Eucalipto - Ideal para construcción y estructuras',
    'pino': 'Madera de Pino - Perfecta para carpintería y muebles',
    'cedro': 'Madera de Cedro - Excelente para exteriores y decoración',
    'laurel': 'Madera de Laurel - Premium para ebanistería',
    'nogal': 'Madera de Nogal - Elegante para muebles finos',
    'tablones': 'Tablones - Disponibles en diferentes medidas y grosores',
    'tablas': 'Tablas - Cortes precisos para sus proyectos',
    'vigas': 'Vigas estructurales - Para construcción y techumbres',
    '2x4': 'Tablones 2x4 pulgadas - Medida estándar para construcción',
    '2x6': 'Tablones 2x6 pulgadas - Para estructuras robustas',
    '2x8': 'Tablones 2x8 pulgadas - Ideales para vigas',
    'puerta': 'Puertas de madera - Diseños personalizados',
    'ventana': 'Marcos de ventanas - Madera tratada'
  };
  
  let resultado = null;
  for (let termino in productos) {
    if (searchTerm.includes(termino)) {
      resultado = productos[termino];
      break;
    }
  }
  
  if (resultado) {
    alert(`✅ Producto encontrado:\n\n${resultado}\n\n📞 Contacta con nosotros para cotización:\n+593 992470053`);
  } else {
    alert(`🔍 Producto "${searchTerm}" no encontrado en nuestro catálogo.\n\n📱 Contáctanos para consultar disponibilidad:\n+593 992470053\n\nProductos disponibles:\n• Maderas: Eucalipto, Pino, Cedro, Laurel, Nogal\n• Productos: Tablones, Vigas, Tablas, Puertas\n• Medidas: 2x4, 2x6, 2x8, 2x10 y personalizadas`);
  }
}

// Asegurar que la función esté disponible globalmente
window.realizarBusqueda = realizarBusqueda;

    // Búsqueda al presionar Enter y verificación de funciones
    document.addEventListener('DOMContentLoaded', function() {
      console.log("DOM cargado - Verificando elementos...");
      
      // Verificar elementos
      const modalRegistro = document.getElementById('modalRegistro');
      const modalLogin = document.getElementById('modalLogin');
      const modalContactos = document.getElementById('modalContactos');
      
      console.log("Modal Registro:", modalRegistro);
      console.log("Modal Login:", modalLogin);
      console.log("Modal Contactos:", modalContactos);
      
      // Verificar funciones
      console.log("Función abrirModalRegistro:", typeof window.abrirModalRegistro);
      console.log("Función abrirModalContactos:", typeof window.abrirModalContactos);
      console.log("Función realizarBusqueda:", typeof window.realizarBusqueda);
      
      // Verificar si los modales tienen las funciones necesarias
      if (modalRegistro) {
        console.log("Modal Registro tiene showModal:", typeof modalRegistro.showModal);
        console.log("Modal Registro tiene close:", typeof modalRegistro.close);
      }
      
      const searchInput = document.getElementById('searchInput');
      if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
            realizarBusqueda();
          }
        });
        console.log("Event listener de búsqueda agregado");
      }
      
      // Agregar listeners para cerrar modales al hacer clic fuera
      if (modalRegistro) {
        modalRegistro.addEventListener('click', function(e) {
          if (e.target === modalRegistro) {
            cerrarModalRegistro();
          }
        });
      }
      
      if (modalContactos) {
        modalContactos.addEventListener('click', function(e) {
          if (e.target === modalContactos) {
            cerrarModalContactos();
          }
        });
      }
      
      if (modalLogin) {
        modalLogin.addEventListener('click', function(e) {
          if (e.target === modalLogin) {
            cerrarModal();
          }
        });
      }
      
      // Event listeners para nuevos modales
      const modalCatalogo = document.getElementById("modalCatalogo");
      const modalContacto = document.getElementById("modalContacto");
      
      if (modalCatalogo) {
        modalCatalogo.addEventListener('click', function(e) {
          if (e.target === modalCatalogo) {
            cerrarModalCatalogo();
          }
        });
      }
      
      if (modalContacto) {
        modalContacto.addEventListener('click', function(e) {
          if (e.target === modalContacto) {
            cerrarModalContacto();
          }
        });
      }
      
      console.log("Inicialización completada");
    });

// Funciones para modal de login
function abrirModalLogin() {
  console.log("=== INTENTANDO ABRIR MODAL LOGIN ===");
  
  // Debug: Verificar que el elemento existe
  const modal = document.getElementById("modalLogin");
  console.log("Modal encontrado:", modal);
  
  if (!modal) {
    console.error("ERROR: Modal de login no encontrado en el DOM");
    alert("Error: No se encontró el modal de login");
    return;
  }
  
  try {
    console.log("Tipo de showModal:", typeof modal.showModal);
    
    if (typeof modal.showModal === 'function') {
      console.log("Usando showModal nativo");
      modal.showModal();
    } else {
      console.log("Usando fallback para dialog");
      // Fallback para navegadores sin soporte para dialog
      modal.style.display = 'flex';
      modal.style.position = 'fixed';
      modal.style.top = '0';
      modal.style.left = '0';
      modal.style.width = '100vw';
      modal.style.height = '100vh';
      modal.style.backgroundColor = 'rgba(45, 80, 22, 0.8)';
      modal.style.alignItems = 'center';
      modal.style.justifyContent = 'center';
      modal.style.zIndex = '1000';
      modal.setAttribute('open', '');
      document.body.style.overflow = 'hidden';
    }
    console.log("Modal de login abierto exitosamente");
  } catch (error) {
    console.error("Error al abrir modal:", error);
    alert("Error al abrir modal: " + error.message);
  }
}

// Versión alternativa más simple
function abrirModalLoginSimple() {
  console.log("Función simple ejecutada");
  const modal = document.getElementById("modalLogin");
  if (modal) {
    modal.style.display = 'flex';
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100vw';
    modal.style.height = '100vh';
    modal.style.backgroundColor = 'rgba(45, 80, 22, 0.8)';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.zIndex = '1000';
    modal.setAttribute('open', '');
    document.body.style.overflow = 'hidden';
  } else {
    alert("Modal no encontrado");
  }
}

// ===================================
// FUNCIONES PARA SELECCIÓN DE USUARIO
// ===================================

// Función para abrir modal de selección de tipo de usuario
function abrirModalSeleccionUsuario() {
  console.log('Abriendo modal de selección de usuario');
  
  const modal = document.getElementById('modalSeleccionUsuario');
  if (modal) {
    modal.style.display = 'flex';
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100vw';
    modal.style.height = '100vh';
    modal.style.backgroundColor = 'rgba(45, 80, 22, 0.8)';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.zIndex = '9999';
    modal.style.backdropFilter = 'blur(8px)';
    modal.classList.add('debug-open');
    document.body.style.overflow = 'hidden';
    console.log('Modal de selección abierto');
  } else {
    console.error('Modal de selección no encontrado');
    alert('Error: Modal de selección no encontrado');
  }
}

// Función para abrir login específico según el tipo
function abrirLoginEspecifico(tipo) {
  console.log('Abriendo login para:', tipo);
  
  // Cerrar modal de selección
  cerrarModalSeleccion();
  
  // Pequeño delay para suavizar la transición
  setTimeout(() => {
    if (tipo === 'usuario') {
      abrirModalLoginUsuario();
    } else if (tipo === 'administrador') {
      abrirModalLoginAdmin();
    }
  }, 150);
}

// Función para abrir modal de login de usuario
function abrirModalLoginUsuario() {
  const modal = document.getElementById('modalLoginUsuario');
  if (modal) {
    modal.style.display = 'flex';
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100vw';
    modal.style.height = '100vh';
    modal.style.backgroundColor = 'rgba(45, 80, 22, 0.8)';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.zIndex = '9999';
    modal.style.backdropFilter = 'blur(8px)';
    modal.classList.add('debug-open');
    document.body.style.overflow = 'hidden';
    console.log('Modal login usuario abierto');
  }
}

// Función para abrir modal de login de administrador
function abrirModalLoginAdmin() {
  const modal = document.getElementById('modalLoginAdmin');
  if (modal) {
    modal.style.display = 'flex';
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100vw';
    modal.style.height = '100vh';
    modal.style.backgroundColor = 'rgba(139, 69, 19, 0.8)';  // Color marrón para admin
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.zIndex = '9999';
    modal.style.backdropFilter = 'blur(8px)';
    modal.classList.add('debug-open');
    document.body.style.overflow = 'hidden';
    console.log('Modal login admin abierto');
  }
}

// Funciones para cerrar modales
function cerrarModalSeleccion() {
  const modal = document.getElementById('modalSeleccionUsuario');
  if (modal) {
    modal.style.display = 'none';
    modal.classList.remove('debug-open');
    document.body.style.overflow = '';
    console.log('Modal de selección cerrado');
  }
}

function cerrarModalLoginUsuario() {
  const modal = document.getElementById('modalLoginUsuario');
  if (modal) {
    modal.style.display = 'none';
    modal.classList.remove('debug-open');
    document.body.style.overflow = '';
    console.log('Modal login usuario cerrado');
  }
}

function cerrarModalLoginAdmin() {
  const modal = document.getElementById('modalLoginAdmin');
  if (modal) {
    modal.style.display = 'none';
    modal.classList.remove('debug-open');
    document.body.style.overflow = '';
    console.log('Modal login admin cerrado');
  }
}

// Funciones de acción para los formularios
function iniciarSesionUsuario() {
  console.log('Iniciando sesión como usuario...');
  // Aquí puedes agregar la lógica de autenticación para usuarios
  alert('Login de usuario - Redirigiendo al panel de cliente...');
  // Ejemplo: window.location.href = 'panel-usuario.php';
}

function iniciarSesionAdmin() {
  console.log('Iniciando sesión como administrador...');
  // Aquí puedes agregar la lógica de autenticación para administradores
  alert('Login de administrador - Redirigiendo al panel administrativo...');
  // Ejemplo: window.location.href = 'panel-admin.php';
}

function crearCuentaUsuario() {
  console.log('Creando cuenta de usuario...');
  // Cerrar modal actual y abrir registro
  cerrarModalLoginUsuario();
  // Aquí puedes abrir el modal de registro o redirigir
  setTimeout(() => {
    alert('Registro de usuario - Funcionalidad por implementar');
    // abrirModalRegistro(); // Si tienes un modal de registro
  }, 200);
}

// Asegurar que las funciones estén disponibles globalmente
window.abrirModalSeleccionUsuario = abrirModalSeleccionUsuario;
window.abrirLoginEspecifico = abrirLoginEspecifico;
window.abrirModalLoginUsuario = abrirModalLoginUsuario;
window.abrirModalLoginAdmin = abrirModalLoginAdmin;
window.cerrarModalSeleccion = cerrarModalSeleccion;
window.cerrarModalLoginUsuario = cerrarModalLoginUsuario;
window.cerrarModalLoginAdmin = cerrarModalLoginAdmin;
window.iniciarSesionUsuario = iniciarSesionUsuario;
window.iniciarSesionAdmin = iniciarSesionAdmin;
window.crearCuentaUsuario = crearCuentaUsuario;

// Test de funciones al cargar
console.log("Funciones de modal cargadas:");
console.log("- abrirModalLogin:", typeof window.abrirModalLogin);
console.log("- cerrarModal:", typeof window.cerrarModal);
console.log("- abrirModalSeleccionUsuario:", typeof window.abrirModalSeleccionUsuario);
console.log("- abrirLoginEspecifico:", typeof window.abrirLoginEspecifico);
console.log("- abrirModalLoginUsuario:", typeof window.abrirModalLoginUsuario);
console.log("- abrirModalLoginAdmin:", typeof window.abrirModalLoginAdmin);


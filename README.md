# Sistema de Gestión para Aserradero

Sistema completo de gestión para aserradero desarrollado en PHP, que incluye administración de productos, categorías, proveedores, clientes y compras, con integración de ImgBB para manejo de imágenes.

## Características

- ✅ **CRUD Completo**: Administración de productos, categorías, proveedores y clientes
- 🖼️ **Gestión de Imágenes**: Subida automática de imágenes de productos a ImgBB
- 📦 **Sistema de Compras**: Aumentar stock de productos por proveedor
- 🔐 **Sistema de Autenticación**: Login, registro y gestión de sesiones
- 👥 **Registro de Usuarios**: Sistema completo de registro con validaciones
- 🔒 **Seguridad**: Contraseñas hasheadas y validaciones robustas
- 🗄️ **Base de Datos**: MySQL con soft delete para todos los registros
- 📱 **Interfaz Responsiva**: CSS moderno con tema forest

## Instalación

### Requisitos Previos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Extensión cURL de PHP habilitada

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone [URL_DEL_REPOSITORIO]
   cd Aserradero
   ```

2. **Configurar la base de datos**
   - Crear una base de datos MySQL
   - Importar el archivo SQL base (si está disponible)
   - Ejecutar las migraciones si es necesario:
     ```sql
     source database/migration_add_image_field.sql
     ```

3. **Configurar las credenciales**
   ```bash
   cp config/clavebasededatos.example.php config/clavebasededatos.php
   ```
   
   Editar `config/clavebasededatos.php` con tus datos:
   ```php
   <?php
   $hostname = 'tu_servidor_mysql';
   $username = 'tu_usuario_mysql';
   $password = 'tu_contraseña_mysql';
   $database = 'tu_base_de_datos';
   
   // API Key de ImgBB (obtener en https://api.imgbb.com/)
   $imgbb_api_key = 'tu_api_key_de_imgbb';
   ?>
   ```

4. **Configurar permisos**
   ```bash
   chmod 755 config/
   chmod 600 config/clavebasededatos.php
   ```

5. **Obtener API Key de ImgBB**
   - Visitar [https://api.imgbb.com/](https://api.imgbb.com/)
   - Crear cuenta gratuita
   - Obtener API key
   - Agregar la key al archivo `config/clavebasededatos.php`

## Estructura del Proyecto

```
├── config/
│   ├── Cconexion.php           # Conexión a base de datos
│   ├── clavebasededatos.php    # Credenciales (no incluido en git)
│   └── imgbb_helper.php        # Helper para subida de imágenes
├── controller/
│   └── Copciones.php           # Controlador principal
├── model/
│   ├── M_Agregar*.php          # Modelos para crear registros
│   ├── M_Editar*.php           # Modelos para editar registros
│   ├── M_Listar*.php           # Modelos para listar registros
│   ├── M_Eliminar*.php         # Modelos para eliminar registros
│   └── obtener_productos_proveedor.php # AJAX endpoint
├── view/
│   ├── V_Agregar*.php          # Vistas para crear registros
│   ├── V_Editar*.php           # Vistas para editar registros
│   ├── V_Listar*.php           # Vistas para listar registros
│   └── V_Administrador.php     # Dashboard principal
├── public/
│   ├── css/                    # Archivos de estilo
│   ├── js/                     # JavaScript
│   └── img/                    # Imágenes estáticas
└── database/
    └── migration_add_image_field.sql # Migraciones
```

## Funcionalidades

### Gestión de Productos
- ➕ Agregar productos con imagen, categoría y proveedor
- ✏️ Editar productos (incluyendo cambio de imagen)
- 👀 Listar productos con imágenes en miniatura
- 🗑️ Eliminar productos (soft delete)

### Gestión de Categorías
- ➕ Crear nuevas categorías
- ✏️ Editar categorías existentes
- 👀 Listar todas las categorías
- 🗑️ Eliminar categorías

### Gestión de Proveedores
- ➕ Agregar nuevos proveedores
- ✏️ Editar información de proveedores
- 👀 Listar todos los proveedores
- 🗑️ Eliminar proveedores

### Gestión de Clientes
- ➕ Registrar nuevos clientes con validación de email y cédula únicos
- ✏️ Editar información de clientes
- 👀 Listar todos los clientes
- 🗑️ Eliminar clientes
- 🔐 Hash automático de contraseñas

### Sistema de Compras
- 📦 Seleccionar proveedor y productos
- ➕ Aumentar stock de productos por cantidad comprada
- 🔄 Actualización automática de inventario

### Gestión de Imágenes
- 📤 Subida automática a ImgBB
- ✅ Validación de tipos de archivo (JPG, PNG, GIF, WEBP)
- 📏 Límite de tamaño (10MB máximo)
- 🖼️ Visualización de imágenes en listings y formularios

## API de ImgBB

El sistema utiliza ImgBB para almacenar las imágenes de productos:

- **Formatos soportados**: JPG, PNG, GIF, WEBP
- **Tamaño máximo**: 10MB por imagen
- **Almacenamiento**: Gratuito en ImgBB
- **URLs persistentes**: Las imágenes permanecen disponibles

## Seguridad

- 🔐 Contraseñas hasheadas con `password_hash()`
- 🛡️ Validación de inputs con `mysqli_real_escape_string()`
- 📁 Archivo de credenciales excluido del control de versiones
- ✅ Validación de tipos de archivo para imágenes
- 🚫 Soft delete para mantener integridad referencial

## Uso

### Primer Acceso

**Usuario Administrador por defecto:**
- **Correo**: `admin@acerraderopequines.com`
- **Contraseña**: `admin`

> ⚠️ **Importante**: Después de la instalación, ejecute el script `database/update_admin_password.php` para hashear la contraseña del administrador.

### Navegación del Sistema

1. **Acceso inicial**: Navegar a `index.php`
2. **Login**: `index.php?opc=login`
3. **Registro**: `index.php?opc=registro`
4. **Dashboard**: `index.php?opc=dashboard` (requiere sesión activa)
5. **Gestión de productos**: `index.php?opc=listar_productos`
6. **Agregar producto**: `index.php?opc=agregar_producto`
7. **Sistema de compras**: `index.php?opc=agregar_compra`
8. **Cerrar sesión**: `index.php?opc=logout`

### Funciones por Tipo de Usuario

**Administrador:**
- Acceso completo a todas las funcionalidades
- Gestión de productos, categorías, proveedores
- Sistema de compras
- Gestión de clientes

**Usuario Registrado:**
- Acceso al dashboard
- Visualización de productos
- Funcionalidades básicas del sistema

## Resolución de Problemas

### Error de subida de imágenes
- Verificar que la API key de ImgBB sea válida
- Comprobar conectividad a internet
- Revisar que cURL esté habilitado en PHP

### Error de conexión a base de datos
- Verificar credenciales en `config/clavebasededatos.php`
- Comprobar que el servidor MySQL esté activo
- Verificar permisos de usuario de base de datos

### Problemas con formularios
- Asegurar que `enctype="multipart/form-data"` esté presente en formularios con imágenes
- Verificar que todos los campos requeridos estén llenos

## Contribuir

1. Fork del repositorio
2. Crear rama para nueva feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit de cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear Pull Request

## Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo LICENSE para detalles.

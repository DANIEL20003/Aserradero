<?php
// Verificar si existe el parámetro 'opc' y asignar valor por defecto si no existe
$opcion = $_GET['opc'] ?? 'inicio'; 
// En esta variable voy a obtener de la variable opc el valor de la opcion que se selecciona en el menu

switch ($opcion) {
    case 1:
        include('../public/Login/Quienessomos.html');
        break;

	case 'login':
		include('./view/V_Login.php');
		break;

	case 'registro':
		include('./view/V_Registro.php');
		break;
 
    case 'productos':
        include './view/V_Productos.php';
        break;
        
    case 'clientes':
        include('./view/clientes.php');
        break;
        
    case 'reportes':
        include('./view/reportes.php');
        break;
        
    case 'facturas':
        include('./view/facturas.php');
        break;
        
    case 'dashboard':
        include './view/V_Administrador.php';
        break;
    case 'inicio':
        include './view/V_Inicio.php';
        break;
    case 'agregar_producto':
		include './view/V_AgregarProducto.php';
		break;
	case 'agregar_compra':
		include './view/V_AgregarCompra.php';
		break;
	case 'ingresar_producto':
		include './view/V_IngresarProducto.php';
		break;
	
	case 'listar_productos':
		include './view/V_ListarProducto.php';
		break;
	case 'editar_producto':
		include './view/V_EditarProducto.php';
		break;
	case 'listar_categorias':
		include './view/V_ListarCategoria.php';
		break;
	case 'agregar_categoria':
		include './view/V_AgregarCategoria.php';
		break;
	case 'editar_categoria':
		include './view/V_EditarCategoria.php';
		break;
	case 'listar_proveedores':
		include './view/V_ListarProveedor.php';
		break;
	case 'agregar_proveedor':
		include './view/V_AgregarProveedor.php';
		break;
	case 'editar_proveedor':
		include './view/V_EditarProveedor.php';
		break;
	case 'listar_ventas':
		include './view/V_ListarVenta.php';
		break;
	case 'agregar_venta':
		include './view/V_AgregarVenta.php';
		break;
	case 'ver_venta':
		include './view/V_VerVenta.php';
		break;
	case 'editar_venta':
		include './view/V_EditarVenta.php';
		break;
	case 'listar_clientes':
		include './view/V_ListarCliente.php';
		break;
	case 'agregar_cliente':
		include './view/V_AgregarCliente.php';
		break;
	case 'editar_cliente':
		include './view/V_EditarCliente.php';
		break;

    case 'mis_pedidos':
        include './view/V_MisPedidos.php';
        break;

    case 'checkout':
        include './view/V_Checkout.php';
        break;
        
    case 'checkout_admin':
        include './view/V_CheckoutAdmin.php';
        break;
        
    case 'gestion_pedidos':
        include './view/V_GestionPedidos.php';
        break;
        
    case 'generar_factura':
        include './model/M_GenerarFactura.php';
        break;
    
    default:
        // Si no se encuentra la opción, redirigir al dashboard
        include './view/V_Inicio.php';
        break;
}
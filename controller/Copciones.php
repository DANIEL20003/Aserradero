<?php
$opcion = $_GET['opc']; 
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
	case 'listar_categorias':
		include './view/V_ListarCategoria.php';
		break;
	case 'agregar_categoria':
		include './view/V_AgregarCategoria.php';
		break;
	case 'listar_proveedores':
		include './view/V_ListarProveedor.php';
		break;
	case 'agregar_proveedor':
		include './view/V_AgregarProveedor.php';
		break;
	case 'listar_ventas':
		include './view/V_ListarVenta.php';
		break;
	case 'agregar_venta':
		include './view/V_AgregarVenta.php';
		break;
	case 'listar_clientes':
		include './view/V_ListarCliente.php';
		break;
	case 'agregar_cliente':
		include './view/V_AgregarCliente.php';
		break;
    
    default:
        // Si no se encuentra la opción, redirigir al dashboard
        include './view/V_Inicio.php';
        break;
}
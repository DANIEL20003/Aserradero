<?php
$opcion = $_GET['opc']; 
// En esta variable voy a obtener de la variable opc el valor de la opcion que se selecciona en el menu

switch ($opcion) {
    case 1:
        include('../public/Login/Quienessomos.html');
        break;
    
 
    case 'productos':
        include('../view/productos.php');
        break;
        
    case 'clientes':
        include('../view/clientes.php');
        break;
        
    case 'reportes':
        include('../view/reportes.php');
        break;
        
    case 'facturas':
        include('../view/facturas.php');
        break;
        
    case 'dashboard':
        include('../model/VentanaAdministrador.php');
        break;
    
    
    default:
        // Si no se encuentra la opción, redirigir al dashboard
        if (isset($_SESSION['usuario_id'])) {
            include('../model/VentanaAdministrador.php');
        } else {
            include('../public/layaout/Login.php');
        }
        break;
}
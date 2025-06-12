<?php
 session_start();  // Aquí se inicia la sesión una sola vez

require_once "./controllers/UserController.php";

// Aquí empieza la lógica de las rutas
$userController = new UserController();

$action = $_GET["action"] ?? "dashboard";

switch($action) {
case 'listaUsuarios':
    $usuario = $_GET['usuario'] ?? ''; // puedes cambiar 'id' por el nombre que uses en tu input de búsqueda
    $usuarios = $userController->listaUsuarios($usuario);
    break;

    case 'eliminarUsuario':
        $userController->eliminarUsuario();
        break;

      case 'login':
        $userController->login();
        break;

    case 'formRecuperarPassword':
        $userController->formRecuperarPassword();
        break;

    case 'enviarToken':
        $userController->enviarToken();
        break;

    case 'formResetPassword':
        $userController->formResetPassword();
        break;

    case 'resetPassword':
        $userController->resetPassword();
        break;

    case 'logout':
        session_start();
        session_destroy();
        header('Location: index3.php?action=login');
        exit();
    
    case 'registrar':
        $userController->registrar();
        break;

    
   // ======================
    // GESTIÓN DE EMPLEADOS
    // ======================

    // Listar empleados (con filtro opcional)
    case 'listaEmpleados':
        $docEmpleado = $_GET['docEmpleado'] ?? '';
        $users = $userController->getAllEmpleado($docEmpleado);
        include './views/Empleado/consul_General.php';
        break;

    // Insertar nuevo empleado (POST o mostrar formulario)
    case 'insertEmpleado':
        $userController->insertEmpleado();
        break;

    // Mostrar formulario de actualización
    case 'openFormEmpleado':
        $userController->openFormEmpleado();
        break;

    // Procesar actualización de empleado
    case 'actualizarEmpleado':
        $userController->actualizarEmpleado();
        break;

    // Eliminar empleado
    case 'eliminarEmpleado':
        $userController->eliminarEmpleado();
        break;

case 'generarPDFEmpleado':
    $userController->generarPDFEmpleado();
    break;

case 'generarPDFEmpleados':
    $userController->generarPDFEmpleados();
    break;

    



 // ======================
// GESTIÓN DE PROVEEDORES
// ======================

// Listar proveedores (con filtro opcional)
    case 'listaProveedores':
        $docProveedor = $_GET['docProveedor'] ?? '';
        $users = $userController->getAllProveedor($docProveedor);
        include './views/Proveedor/consul_GeneralProveedor.php';
        break;

    // Insertar nuevo proveedor (POST o mostrar formulario)
    case 'insertProveedor':
       $userController->insertProveedor();
        break;

    // Mostrar formulario de actualización
    case 'openFormProveedor':
     $userController->openFormProveedor();
        break;

    // Procesar actualización de proveedor
    case 'actualizarProveedor':
        $userController->actualizarProveedor();
        break;

    // Eliminar proveedor
    case 'eliminarProveedor':
       $userController->eliminarProveedor();
        break;

        // PDF individual de proveedor
case 'generarPDFProveedor':
    $userController->generarPDFProveedor();  // Método que crearemos en el controlador
    break;

// PDF grupal de proveedores
case 'generarPDFProveedores':
    $userController->generarPDFProveedores();  // Otro método que también crearemos
    break;


        //ENTRADA

case 'listaEntradas':
    $idEntrada = $_GET['idEntrada'] ?? '';
    $users = $userController->getAllEntrada($idEntrada);
    include './views/Entrada/consulGen_entra.php';
break;

case 'insertEntrada':
    $userController->insertEntrada();
    include './views/Entrada/insert_entrada.php';
break;

case 'openFormEntrada':
    $userController->openFormEntrada();
break;

case "actualizarEntrada":
    $userController->actualizarEntrada();
break;

case "eliminarEntrada":
    $userController->eliminarEntrada(); 
break;

// PDF INDIVIDUAL DE ENTRADA
case 'generarPDFEntrada':
    $userController->generarPDFEntrada();
    break;

// PDF GRUPAL DE ENTRADAS
case 'generarPDFEntradas':
    $userController->generarPDFEntradas();
    break;

//INICIO SALIDA

case 'listaSalidas':
    $idSalida = $_GET['idSalida'] ?? '';
    $users = $userController->getAllSalida($idSalida);
    include './views/Salida/consulGen_sal.php';
break;
    
case "insertSalida":
    $userController->insertSalida();
    include './views/Salida/insert_salida.php';
break;

case "openFormSalida":
    $userController->openFormSalida();
break;
        
case "actualizarSalida":
    $userController->actualizarSalida();
break;

case "eliminarSalida":
    $userController->eliminarSalida();  // Esta línea debe estar procesando correctamente el $docEncargada
break;

// PDF individual de salida
case 'generarPDFSalida':
    $userController->generarPDFSalida();  // Método que genera PDF de una salida específica
    break;

// PDF grupal de salidas
case 'generarPDFSalidas':
    $userController->generarPDFSalidas();  // Método que genera PDF con listado general de salidas
    break;

//PRODUCTO

case 'consultaproducto':
    $userspros = $userController->getAllProducto();
    include './views/Producto/consulGen_producto.php';
    break;


     case "insertproducto":
         if ($_SERVER["REQUEST_METHOD"] == "POST") {
             $userController->insertproducto();
         } else {
             include_once './views/Producto/insert_producto.php';
         }
         break;
        

      // inicio actualizar prtoducto


case "openFormproducto":
    $userspros = $userController->productoById(); // ← obtener datos para actualizar
    include './views/Producto/actualizar_producto.php';
    break;
    
case "searchproductoByNumberDocumento":
    $userspros = $userController->productoById();
    include  './views/Producto/consulGen_producto.php';
    break;


// BUSCAR PRODUCTO POR CÓDIGO, NOMBRE O DESCRIPCIÓN
case 'searchproductoByNumberDocumento':
    $userspros = $userController->buscarProducto(); 
    include './views/Producto/consulGen_producto.php'; 
break;


case "actualizarproducto":
     $userspros = $userController->actualizarproducto();
    header("Location: index3.php?action=consultaproducto");
    exit();
    break;
    
       
       //INICIO ELIMINAR PRODUCTO
case "eliminarproducto":
    $userController->eliminarproducto();
    header("Location: index3.php?action=consultaproducto");
    exit();
    break;

         //PDF producto
     case 'generarPDFProducto':
        $userController->generarPDFProducto();
        break;

    // PDF grupal de productos
     case 'generarPDFProductos':
    $userController->generarPDFProductos();
    break;
    
    //FIN de producto

    //===========================
    //PEDIDO
    //===========================

    // Listar pedidos (con filtro opcional)
case 'listaPedidos':
    $idPedido = $_GET['idPedido'] ?? '';
    $pedidos = $userController->getAllPedido($idPedido);
    include './views/Pedido/consul_GeneralPedido.php';
    break;

// Insertar nuevo pedido (POST o mostrar formulario)
case 'insertPedido':
    $userController->insertPedido();
    break;

// Mostrar formulario de actualización
case 'openFormPedido':
    $userController->openFormPedido();
    break;

// Procesar actualización de pedido
case 'actualizarPedido':
    $userController->actualizarPedido();
    break;

// Eliminar pedido
case 'eliminarPedido':
    $userController->eliminarPedido();
    break;

// PDF individual de pedido
case 'generarPDFPedido':
    $userController->generarPDFPedido();
    break;


//===========================
// DETALLE PEDIDO
//===========================

// Listar detallepedidos (con filtro opcional por idDetalle o IdPedido)
 case 'listaDetallePedidos':
        $idDetalle = $_GET['idDetalle'] ?? '';
        $users = $userController->getAllDetallePedidos($idDetalle);
        include './views/Detalle/consul_General.php';
        break;


// Insertar nuevo detallepedido
case 'insertDetallePedido':
    $userController->insertDetallePedido();
    break;

// Mostrar formulario de actualización de detallepedido
case 'openFormDetallePedido':
    $userController->openFormDetallePedido();
    break;

// Procesar actualización de detallepedido
case 'actualizarDetallePedido':
    $userController->actualizarDetallePedido();
    break;

// Eliminar detallepedido
case 'eliminarDetallePedido':
    $userController->eliminarDetallePedido();
    break;

// PDF individual de detallepedido
case 'generarPDFDetallePedido':
    $userController->generarPDFDetallePedido();
    break;

// PDF grupal de detallepedidos
case 'generarPDFDetallePedidos':
    $userController->generarPDFDetallePedidos();
    break;


  
}
?>
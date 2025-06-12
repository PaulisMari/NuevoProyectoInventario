<?php

include  "./config/database.php";
require_once "./models/UserModel.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../models/MailService.php';

require './vendor/autoload.php'; // Cargar PHPMailer desde Composer


class UserController {
    private $db;
    private $userModel;

    public function dashboard() {
        require "./index2.php"; 
    }

    public function __construct() {
        $database = new database();
        $this->db = $database->getConnection();
        $this->userModel = new UserModel($this->db);
    }

    
public function registrar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username']; // Documento (DocEmpleado)
        $password = $_POST['password'];

        // Validación de contraseña
        if (!$this->esContrasenaValida($password)) {
            session_start();
            $_SESSION['error'] = "La contraseña debe tener al menos 6 caracteres, una mayúscula, un número y un símbolo.";
            header("Location: ingresar.php");
            exit();
        }

        // Intentar registrar
        if ($this->userModel->registerUser($username, $password)) {
            header('Location: usuario.php');
            exit();
        } else {
            session_start();
            $_SESSION['error'] = "No se pudo registrar. Verifica que el documento exista en empleados y no esté ya registrado.";
            header("Location: ingresar.php");
            exit();
        }
    } else {
        require_once "./ProyectoInventario/InventarioPHP/ingresar.php";
    }
}

private function esContrasenaValida($password): bool {
    if (strlen($password) < 6) {
        return false;
    }

    // Al menos un símbolo
    if (!preg_match('/[\W_]/', $password)) {
        return false;
    }

    // Al menos un número
    if (!preg_match('/\d/', $password)) {
        return false;
    }

    // Al menos una letra mayúscula
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }

    return true;
}


    //RECUPERAR CONTRASEÑA 



public function enviarToken() {
    $mensaje = null; // Variable para el mensaje (advertencia o éxito)

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $documento = $_POST['usuario'] ?? null;

        if (!$documento) {
            $mensaje = "Debes ingresar el número de documento.";
        } else {
            $email = $this->userModel->obtenerCorreoPorDocumento($documento);

            if (!$email) {
                $mensaje = "No se encontró un empleado con ese documento.";
            } else {
                $token = bin2hex(random_bytes(32));
                $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

                $stmt = $this->db->prepare("INSERT INTO recovery_tokens (usuario, token, expiracion) VALUES (?, ?, ?)");
                $stmt->execute([$documento, $token, $expira]);

               $link = "http://localhost/nuevoproyectoinventario/index3.php?action=formResetPassword&token=" . $token;

                //=================================
                // NOMBRE DE LA RUTA PRINCIPAL
                //=================================

                require_once './models/MailService.php';
                $resultado = MailService::enviarCorreo(
                    $email,
                    "Recuperación de contraseña",
                    "Hola,\n\nHemos recibido una solicitud para restablecer tu contraseña.\n\nHaz clic en este enlace:\n$link\n\nEste enlace es válido por 1 hora."
                );

                if ($resultado === true) {
                    $mensaje = "Correo enviado correctamente a $email.";
                } else {
                    $mensaje = $resultado;
                }
            }
        }
    }

    // En vez de hacer echo, cargamos la vista y enviamos el mensaje para que se muestre
    require_once './recuperar_password.php';
}
    public function formRecuperarPassword() {
        require_once "./recuperar_password.php";  // Vista con formulario para pedir usuario
    }


    public function formResetPassword() {
        $token = $_GET['token'] ?? '';

        $tokenData = $this->userModel->getValidToken($token);

        if (!$tokenData) {
            echo "Token inválido o expirado.";
            exit;
        }

        require_once "./reset_password.php"; // Vista recibe $token
    }
public function resetPassword() {
    $mensaje = null;
    $token = $_POST['token'] ?? $_GET['token'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nuevaPassword = $_POST['password'];

        if (!$this->esContrasenaValida($nuevaPassword)) {
            $mensaje = "La contraseña debe tener al menos 6 caracteres, una mayúscula, un número y un símbolo.";
        } else {
            $tokenData = $this->userModel->getValidToken($token);

            if (!$tokenData) {
                $mensaje = "Token inválido o expirado.";
            } else {
                $this->userModel->updatePassword($tokenData['usuario'], $nuevaPassword);
                $this->userModel->markTokenUsed($tokenData['id']);
                $mensaje = "Contraseña actualizada correctamente. <a href='index3.php?action=login'>Iniciar sesión</a>";
            }
        }
    }

    require_once './reset_password.php';
}



 //   Método login ya existente que use password_verify original

public function login() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $isValid = $this->userModel->checkCredentials($username, $password);

        if ($isValid) {
            $_SESSION['user'] = $username;
            header('Location: index2.php'); // página principal
            exit();
        } else {
            $_SESSION['error'] = "Usuario o contraseña incorrectos.";
            header("Location: usuario.php"); // página de login
            exit();
        }
    } else {
        require_once "./usuario.php";
    }
}

    // ============================
    // EMPLEADO: CONSULTAR
    // ============================

    public function getAllEmpleado($docEmpleado = '') {
        return $this->userModel->listaEmpleados($docEmpleado);
    }

    public function listaEmpleados() {
        $users = $this->userModel->getEmpleados();
    }

    // ============================
    // EMPLEADO: INSERTAR
    // ============================

    public function insertEmpleado() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $docEmpleado = $_POST["DocEmpleado"] ?? null;
            $TipoDoc = $_POST["TipoDoc"] ?? null;
            $Nombre = $_POST["Nombre"] ?? null;
            $FechaNaci = $_POST["FechaNaci"] ?? null;
            $Telefono = $_POST["Telefono"] ?? null;
            $Direccion = $_POST["Direccion"] ?? null;
            $Email = $_POST["Email"] ?? null;

            if ($docEmpleado && $TipoDoc && $Nombre && $FechaNaci && $Telefono && $Direccion && $Email) {
                $this->userModel->insertEmpleado($docEmpleado, $TipoDoc, $Nombre, $FechaNaci, $Telefono, $Direccion, $Email);
                header("Location: index3.php?action=listaEmpleados");
                exit();
            } else {
                $_SESSION['message'] = "Todos los campos son obligatorios.";
            }
        }

        $tiposDocumento = $this->userModel->getTiposDocumento();
        include './views/Empleado/insert_Empleado.php';
    }

    // ============================
    // EMPLEADO: ACTUALIZAR
    // ============================

    public function openFormEmpleado() {
        if (!isset($_GET['docEmpleado'])) {
            echo "Documento de empleado no especificado.";
            return;
        }

        $docEmpleado = $_GET['docEmpleado'];
          $empleado = $this->userModel->getEmpleadoPorDocumento($docEmpleado);

        if (!$empleado) {
            echo "Empleado no encontrado.";
            return;
        }

        $tiposDocumento = $this->userModel->getTiposDocumento();
        include('./views/Empleado/actualizarEmpleado.php');
    }

    public function actualizarEmpleado() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $docEmpleadoOriginal = $_POST['docEmpleadoOriginal'];
            $docEmpleado = $_POST['docEmpleado'];
            $tipoDoc = $_POST['tipoDoc'];
            $nombre = $_POST['nombre'];
            $fechaNaci = $_POST['fechaNaci'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $email = $_POST['email'];

            try {
                $this->userModel->actualizarEmpleado(
                    $docEmpleadoOriginal, $docEmpleado, $tipoDoc,
                    $nombre, $fechaNaci, $telefono, $direccion, $email
                );

                $_SESSION['message'] = "Empleado actualizado correctamente.";
                header("Location: index3.php?action=listaEmpleados");
                exit();
            } catch (Exception $e) {
                $_SESSION['message'] = "Error al actualizar el empleado: " . $e->getMessage();
            }
        }
    }

    // ============================
    // EMPLEADO: ELIMINAR
    // ============================

    public function eliminarEmpleado() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $docEmpleado = $_POST['DocEmpleado'] ?? null;

            if ($docEmpleado) {
                try {
                    $this->userModel->eliminarEmpleado($docEmpleado);
                    $_SESSION['message'] = "Empleado eliminado correctamente.";
                } catch (Exception $e) {
                    $_SESSION['message'] = "Error al eliminar el empleado: " . $e->getMessage();
                }
            } else {
                $_SESSION['message'] = "Documento de empleado no proporcionado.";
            }
            header("Location: index3.php?action=listaEmpleados");
            exit();
        }
    }

       // ============================
    // EMPLEADO: PDF
    // ============================
  public function generarPDFEmpleado() {
    $doc = $_GET['docEmpleado'] ?? null;

    if (!$doc) {
        echo "Documento del empleado no proporcionado.";
        return;
    }

    $empleado = $this->userModel->obtenerEmpleadoPorDocumento($doc);

    if (!$empleado) {
        echo "Empleado no encontrado.";
        return;
    }

    require_once './Libs/fpdf186/fpdf.php';

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Ficha del Empleado', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);

    foreach ($empleado as $campo => $valor) {
        // Si estás seguro de que los datos están en UTF-8 y FPDF no los acepta, puedes usar iconv
        $campo = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $campo);
        $valor = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $valor);

        $pdf->Cell(60, 10, $campo . ':', 0, 0);
        $pdf->Cell(100, 10, $valor, 0, 1);
    }

    if (ob_get_length()) {
        ob_end_clean();
    }

    $pdf->Output('I', 'Empleado_' . $doc . '.pdf');
}
  // ============================
    // EMPLEADO: PDF GRUPAL
    // ============================

public function generarPDFEmpleados() {
    $empleados = $this->userModel->obtenerTodosLosEmpleados();

    if (!$empleados || count($empleados) === 0) {
        echo "No hay empleados para mostrar.";
        return;
    }

    require_once __DIR__ . '/../Libs/fpdf186/fpdf.php';  // Ajusta la ruta si es necesario

    $pdf = new FPDF('L', 'mm', 'A4');  // Orientación horizontal para mejor tabla
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Listado General de Empleados', 0, 1, 'C');
    $pdf->Ln(5);

    $this->dibujarTablaPDF($pdf, $empleados);

    if (ob_get_length()) {
        ob_end_clean();
    }

     $pdf->Output('I', 'Lista_Empleados.pdf');
}



    // ============================
    // PROVEEDOR
    // ============================

    //consulta
    public function getAllProveedor($docProveedor = '') {
    return $this->userModel->listaProveedores($docProveedor);
}

public function listaProveedores() {
    $users = $this->userModel->getProveedores();
    include './views/Proveedor/listarProveedores.php';
}

     //INSERTAR
public function insertProveedor() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $DocProveedor = $_POST["DocProveedor"] ?? null;
        $TipoDoc = $_POST["TipoDoc"] ?? null;
        $Nombre = $_POST["Nombre"] ?? null;
        $Telefono = $_POST["Telefono"] ?? null;
        $Direccion = $_POST["Direccion"] ?? null;
        $Email = $_POST["Email"] ?? null;

        if ($DocProveedor && $TipoDoc && $Nombre && $Telefono && $Direccion && $Email) {
            try {
                $this->userModel->insertProveedor($DocProveedor, $TipoDoc, $Nombre, $Telefono, $Direccion, $Email);
                $_SESSION['message'] = "Proveedor insertado correctamente.";
                header("Location: index3.php?action=listaProveedores");
                exit();
            } catch (PDOException $e) {
                // Aquí capturas el error y lo guardas en la sesión
                $_SESSION['message'] = "Error al insertar proveedor: " . $e->getMessage();
            }
        } else {
            $_SESSION['message'] = "Todos los campos son obligatorios.";
        }
    }

    $tiposDocumento = $this->userModel->getTiposDocumento();
    include './views/Proveedor/insert_Proveedor.php';
}

    //actualizar 
    public function openFormProveedor() {
    if (!isset($_GET['docProveedor'])) {
        echo "Documento de proveedor no especificado.";
        return;
    }

    $docProveedor = $_GET['docProveedor'];
    $proveedor = $this->userModel->getProveedorPorDocumento($docProveedor);

    if (!$proveedor) {
        echo "Proveedor no encontrado.";
        return;
    }

    $tiposDocumento = $this->userModel->getTiposDocumento();
    include('./views/Proveedor/actualizarProveedor.php');
}

public function actualizarProveedor() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $docProveedorOriginal = $_POST['docProveedorOriginal'];
        $docProveedor = $_POST['docProveedor'];
        $tipoDoc = $_POST['tipoDoc'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $email = $_POST['email'];

        try {
            $this->userModel->actualizarProveedor(
                $docProveedorOriginal, $docProveedor, $tipoDoc,
                $nombre, $telefono, $direccion, $email
            );

            $_SESSION['message'] = "Proveedor actualizado correctamente.";
            header("Location: index3.php?action=listaProveedores");
            exit();
        } catch (Exception $e) {
            $_SESSION['message'] = "Error al actualizar el proveedor: " . $e->getMessage();
        }
    }
}

//ELIMINAR
public function eliminarProveedor() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $docProveedor = $_POST['DocProveedor'] ?? null;

        if ($docProveedor) {
            try {
                $this->userModel->eliminarProveedor($docProveedor);
                $_SESSION['message'] = "Proveedor eliminado correctamente.";
            } catch (Exception $e) {
                $_SESSION['message'] = "Error al eliminar el proveedor: " . $e->getMessage();
            }
        } else {
            $_SESSION['message'] = "Documento de proveedor no proporcionado.";
        }
        header("Location: index3.php?action=listaProveedores");
        exit();
    }
}
// ============================
// PROVEEDOR: PDF INDIVIDUAL
// ============================
public function generarPDFProveedor() {
    $doc = $_GET['docProveedor'] ?? null;

    if (!$doc) {
        echo "Documento del proveedor no proporcionado.";
        return;
    }

    $proveedor = $this->userModel->obtenerProveedorPorDocumento($doc);

    if (!$proveedor) {
        echo "Proveedor no encontrado.";
        return;
    }

    require_once './Libs/fpdf186/fpdf.php';

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Ficha del Proveedor', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);

    foreach ($proveedor as $campo => $valor) {
        $campo = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $campo);
        $valor = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $valor);

        $pdf->Cell(60, 10, $campo . ':', 0, 0);
        $pdf->Cell(100, 10, $valor, 0, 1);
    }

    if (ob_get_length()) {
        ob_end_clean();
    }

    $pdf->Output('I', 'Proveedor_' . $doc . '.pdf');
}
 // ============================
// PROVEEDOR: PDF GRUPAL
// ============================

    public function generarPDFProveedores() {
        $proveedores = $this->userModel->obtenerTodosLosProveedores();

        if (!$proveedores || count($proveedores) === 0) {
            echo "No hay proveedores para mostrar.";
            return;
        }

        require_once './Libs/fpdf186/fpdf.php';

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Listado General de Proveedores', 0, 1, 'C');
        $pdf->Ln(5);

        $this->dibujarTablaPDF($pdf, $proveedores);

        if (ob_get_length()) {
            ob_end_clean();
        }

        $pdf->Output('I', 'Proveedores_Listado.pdf');
    }

    private function dibujarTablaPDF($pdf, $datos) {
        if (empty($datos)) return;

        $headers = array_keys($datos[0]);
        $totalWidth = 270;
        $colCount = count($headers);
        $colWidth = $totalWidth / $colCount;

        // Encabezados
        $pdf->SetFont('Arial', 'B', 12);
        foreach ($headers as $header) {
            $headerText = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $header);
            $pdf->Cell($colWidth, 10, $headerText, 1, 0, 'C');
        }
        $pdf->Ln();

        // Filas
        $pdf->SetFont('Arial', '', 11);
        foreach ($datos as $fila) {
            foreach ($fila as $valor) {
                $valorText = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $valor);
                $pdf->Cell($colWidth, 8, $valorText, 1);
            }
            $pdf->Ln();

            if ($pdf->GetY() > 190) {
                $pdf->AddPage();

                $pdf->SetFont('Arial', 'B', 12);
                foreach ($headers as $header) {
                    $headerText = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $header);
                    $pdf->Cell($colWidth, 10, $headerText, 1, 0, 'C');
                }
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 11);
            }
        }
    }

    // ============================
    // FIN PROVEEDOR
    // ============================

//INICIO ENTRADA

public function getAllEntrada($idEntrada = '') {
    return $this->userModel->listaEntradas($idEntrada);
}

// Controlador: obtener todas las entradas sin filtro (como listaEmpleados)
public function listaEntradas() {
    $entradas = $this->userModel->getEntradas();
    return $entradas;
}

//Seleccionar codigo de producto
public function getCodigoP(){
    return $this->userModel->getCodigoP();
}

public function generarPDFEntrada() {
    $idEntrada = $_GET['idEntrada'] ?? null;

    if (!$idEntrada) {
        echo "ID de entrada no proporcionado.";
        return;
    }

    $entrada = $this->userModel->obtenerEntradaPorId($idEntrada);

    if (!$entrada) {
        echo "Entrada no encontrada.";
        return;
    }

    require_once './Libs/fpdf186/fpdf.php';

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Ficha de Entrada', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);

    foreach ($entrada as $campo => $valor) {
        $campo = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $campo);
        $valor = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $valor);

        $pdf->Cell(60, 10, $campo . ':', 0, 0);
        $pdf->Cell(100, 10, $valor, 0, 1);
    }

    if (ob_get_length()) {
        ob_end_clean();
    }

    $pdf->Output('I', 'Entrada_' . $idEntrada . '.pdf');
}

public function generarPDFEntradas() {
    $entradas = $this->userModel->obtenerTodasLasEntradas();

    if (!$entradas || count($entradas) === 0) {
        echo "No hay entradas para mostrar.";
        return;
    }

    require_once './Libs/fpdf186/fpdf.php';

    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Listado General de Entradas', 0, 1, 'C');
    $pdf->Ln(5);

    // Encabezados y anchos
    $headers = ['ID', 'Descripción', 'Cantidad', 'Fecha', 'Precio Unitario', 'Código'];
    $widths = [20, 70, 30, 40, 40, 40];

    // Encabezado tabla
    $pdf->SetFont('Arial', 'B', 12);
    foreach ($headers as $i => $header) {
        $pdf->Cell($widths[$i], 10, $header, 1, 0, 'C');
    }
    $pdf->Ln();

    // Contenido tabla
    $pdf->SetFont('Arial', '', 11);
    foreach ($entradas as $entrada) {
        $pdf->Cell($widths[0], 10, $entrada['idEntrada'], 1);
        $pdf->Cell($widths[1], 10, utf8_decode($entrada['DescripcionEntrada']), 1);
        $pdf->Cell($widths[2], 10, $entrada['CantidadEntrada'], 1, 0, 'C');
        $pdf->Cell($widths[3], 10, $entrada['FechaEntrada'], 1);
        $pdf->Cell($widths[4], 10, '$' . $entrada['PrecioUni'], 1);
        $pdf->Cell($widths[5], 10, $entrada['Codigo'], 1);
        $pdf->Ln();
    }

    if (ob_get_length()) {
        ob_end_clean();
    }

    $pdf->Output('I', 'Entradas_Listado.pdf');
}

//Insertar entrada
public function insertEntrada() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $descripcionEntrada = isset($_POST["descripcionEntrada"]) ? $_POST["descripcionEntrada"] : null;
        $cantidadEntrada = isset($_POST["cantidadEntrada"]) ? $_POST["cantidadEntrada"] : null;
        $fechaEntrada = isset($_POST["fechaEntrada"]) ? $_POST["fechaEntrada"] : null;
        $precioUni = isset($_POST["precioUni"]) ? $_POST["precioUni"] : null;
        $codigo = isset($_POST["codigo"]) ? $_POST["codigo"] : null;
        
        // Verificar si 'codigo' y otros campos son válidos
        if ($descripcionEntrada && $cantidadEntrada && $fechaEntrada && $precioUni && $codigo) {
            $this->userModel->insertEntrada($descripcionEntrada, $cantidadEntrada, $fechaEntrada, $precioUni, $codigo);
            header("Location: index3.php?action=listaEntradas");
            exit();  // Detiene la ejecución después de redirigir
        } else {
            // Manejo del error si alguno de los campos es inválido
            echo "Por favor, complete todos los campos obligatorios.";
        }
    }
}

public function openFormEntrada() {
    if (!isset($_GET['idEntrada'])) {
        echo "ID de entrada no especificado.";
        return;
    }

    $idEntrada = $_GET['idEntrada'];
    $entrada = $this->userModel->getEntradaById($idEntrada);

    if (!$entrada) {
        echo "Entrada no encontrada.";
        return;
    }

    include('./views/Entrada/actualizarEntra.php');
}

// Actualizar entrada
public function actualizarEntrada() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idEntrada = $_POST['idEntrada'];
    $descripcionEntrada = $_POST['descripcionEntrada'];
    $cantidadEntrada = $_POST['cantidadEntrada'];
    $fechaEntrada = $_POST['fechaEntrada'];
    $precioUni = $_POST['precioUni'];
    $codigo = $_POST['codigo'];

    try {
        $this->userModel->actualizarEntra($descripcionEntrada, $cantidadEntrada, $fechaEntrada, $precioUni, $codigo, $idEntrada);
        echo "Entrada actualizada correctamente.";
    } catch (Exception $e) {
        echo "Error al actualizar: " . $e->getMessage();
    }
    header("Location: index3.php?action=listaEntradas");
    exit();
    }
}

public function eliminarEntrada() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoge el número de documento desde el formulario
        $idEntrada = $_POST['idEntrada'];
        // Llama al método eliminar del modelo
        $this->userModel->eliminarEntrada($idEntrada);
        // Redirige a la lista de usuarios
        header("Location:index3.php?action=listaEntradas");
        exit();
    }
}

//SALIDA
// Obtener todas las salidas con o sin filtro por ID (como getAllEntrada)
public function getAllSalida($idSalida = '') {
    return $this->userModel->listaSalidas($idSalida);
}

// Obtener todas las salidas sin filtro (como listaEntradas)
public function listaSalidas() {
    $salidas = $this->userModel->getSalidas();
    return $salidas;
}

// Seleccionar códigos de producto (si aplica a salidas también)
public function getCodigoProS() {
    return $this->userModel->getCodigoProS();
}

public function generarPDFSalida() {
    $idSalida = $_GET['idSalida'] ?? null;

    if (!$idSalida) {
        echo "ID de salida no proporcionado.";
        return;
    }

    $salida = $this->userModel->obtenerSalidaPorId($idSalida);

    if (!$salida) {
        echo "Salida no encontrada.";
        return;
    }

    require_once './Libs/fpdf186/fpdf.php';

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Ficha de Salida', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);

    foreach ($salida as $campo => $valor) {
        $campo = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $campo);
        $valor = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $valor);

        $pdf->Cell(60, 10, $campo . ':', 0, 0);
        $pdf->Cell(100, 10, $valor, 0, 1);
    }

    if (ob_get_length()) {
        ob_end_clean();
    }

    $pdf->Output('I', 'Salida_' . $idSalida . '.pdf');
}

public function generarPDFSalidas() {
    $salidas = $this->userModel->obtenerTodasLasSalidas();

    if (!$salidas || count($salidas) === 0) {
        echo "No hay salidas para mostrar.";
        return;
    }

    require_once './Libs/fpdf186/fpdf.php';

    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Listado General de Salidas', 0, 1, 'C');
    $pdf->Ln(5);

    // Definir encabezados de columna
    $headers = ['ID', 'Motivo Salida', 'Cantidad', 'Fecha Salida', 'Codigo Producto'];

    // Anchos de columnas (ajusta según contenido)
    $widths = [20, 60, 30, 40, 40];

    // Encabezado tabla
    $pdf->SetFont('Arial', 'B', 12);
    foreach ($headers as $i => $header) {
        $pdf->Cell($widths[$i], 10, $header, 1, 0, 'C');
    }
    $pdf->Ln();

    // Datos tabla
    $pdf->SetFont('Arial', '', 11);
    foreach ($salidas as $salida) {
        $pdf->Cell($widths[0], 10, $salida['idSalida'], 1);
        $pdf->Cell($widths[1], 10, utf8_decode($salida['MotivoSalida']), 1);
        $pdf->Cell($widths[2], 10, $salida['CantidadSalida'], 1, 0, 'C');
        $pdf->Cell($widths[3], 10, $salida['FechaSalida'], 1);
        $pdf->Cell($widths[4], 10, $salida['Codigo'], 1);
        $pdf->Ln();
    }

    if (ob_get_length()) {
        ob_end_clean();
    }

    $pdf->Output('I', 'Salidas_Listado.pdf');
}

public function insertSalida() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $motivoSalida = isset($_POST["motivoSalida"]) ? $_POST["motivoSalida"] : null;
        $cantidadSalida = isset($_POST["cantidadSalida"]) ? $_POST["cantidadSalida"] : null;
        $fechaSalida = isset($_POST["fechaSalida"]) ? $_POST["fechaSalida"] : null;
        $codigo = isset($_POST["codigo"]) ? $_POST["codigo"] : null;

        if ($motivoSalida && $cantidadSalida && $fechaSalida && $codigo) {
            $this->userModel->insertSalida($motivoSalida, $cantidadSalida, $fechaSalida, $codigo);
            header("Location: index3.php?action=listaSalidas");
            exit();
        } else {
            echo "Por favor, complete todos los campos obligatorios.";
        }
    }
}

public function openFormSalida() {
    if (!isset($_GET['idSalida'])) {
        echo "ID de entrada no especificado.";
        return;
    }

    $idSalida = $_GET['idSalida'];
    $salida = $this->userModel->getSalidaById($idSalida);

    if (!$salida) {
        echo "Salida no encontrada.";
        return;
    }

    include('./views/Salida/actualizarSal.php');
}

// Actualizar salida
public function actualizarSalida() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $idSalida = $_POST['idSalida'];
        $motivoSalida = $_POST['motivoSalida'];
        $cantidadSalida = $_POST['cantidadSalida'];
        $fechaSalida = $_POST['fechaSalida'];
        $codigo = $_POST['codigo'];
            try {
                $this->userModel->actualizarSal($motivoSalida, $cantidadSalida, $fechaSalida, $codigo, $idSalida);
                echo "Salida actualizada correctamente.";
            } catch (Exception $e) {
                echo "Error al actualizar: " . $e->getMessage();
            }
        header("Location: index3.php?action=listaSalidas");
        exit();
    }
}

//Eliminar salida
public function eliminarSalida() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoge el número de documento desde el formulario
        $idSalida = $_POST['idSalida'];

        // Llama al método eliminar del modelo
        $this->userModel->eliminarSalida($idSalida);

        // Redirige a la lista de usuarios
        header("Location:index3.php?action=listaSalidas");
        exit();
    }
}
    //INICO TODO LO DE PRODUCTO

      //CONSULTA GENERAL 

public function getAllProducto() {

    return $this->userModel->getAllProducto();
}

public function insertproducto() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $Codigo = $_POST["Codigo"] ?? null;
        $NombreProducto = $_POST["NombreProducto"] ?? null;
        $Descripcion = $_POST["Descripcion"] ?? null;
        $Precio = $_POST["Precio"] ?? null;
        $CantMax = $_POST["CantMax"] ?? null;
        $CantMin = $_POST["CantMin"] ?? null;
        $CantDis = $_POST["CantDis"] ?? null;
        $CreadoPor = $_POST["CreadoPor"] ?? null;

        // Validar campos obligatorios
        if (!$Codigo || !$NombreProducto || !$Descripcion || !$Precio || !$CantMax || !$CantMin || !$CantDis || !$CreadoPor) {
            $_SESSION['message'] = "Por favor, complete todos los campos obligatorios.";
            header("Location: index3.php?action=insertproducto");
            exit();
        }

        // Verificar existencia de la cédula en la tabla empleado
        if (!$this->userModel->cedulaExiste($CreadoPor)) {
            $_SESSION['message'] = "La cédula ingresada no está registrada en el sistema.";
            header("Location: index3.php?action=insertproducto");
            exit();
        }


        $this->userModel->insertproducto($Codigo, $NombreProducto, $Descripcion, $Precio, $CantMin, $CantMax, $CantDis, $CreadoPor);
        
        header("Location: index3.php?action=consultaproducto");
        exit();
    }
}

public function buscarProducto() {
    $Codigo = $_GET['Codigo'] ?? '';
    return $this->userModel->buscarProducto($Codigo); // llama al modelo
}


    public function listproducto() {
        return $this->userModel->getproducto();
    }
    

   public function productoById() {
    $Codigo = $_GET['Codigo'] ?? '';
    return $this->userModel->getproductoById($Codigo);
    }

// Actualizar PRODUCTO

public function actualizarproducto() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $CodigoOriginal = $_POST['CodigoOriginal'];
        $Codigo = $_POST['Codigo'];
        $NombreProducto = $_POST['NombreProducto'];
        $Descripcion = $_POST['Descripcion'];
        $Precio = $_POST['Precio'];
        $CantMax = $_POST['CantMax'];
        $CantMin = $_POST['CantMin'];
        $CantDis = $_POST['CantDis'];
        $CreadoPor = $_POST['CreadoPor'];

        try {
            $this->userModel->actualizarpro($CodigoOriginal, $Codigo, $NombreProducto, $Descripcion, $Precio, $CantMax, $CantMin, $CantDis, $CreadoPor);
            echo "Producto actualizado correctamente.";
        } catch (Exception $e) {
            echo "Error al actualizar: " . $e->getMessage();    
        }

        header("Location: index3.php?action=consultaproducto");
        exit();
    }
}
      //ELIMINAR PRODUCTO
public function eliminarproducto() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $Codigo = $_POST['Codigo'];
        $this->userModel->eliminarProducto($Codigo);
       
    }
}

// ============================
// PRODUCTO: PDF INDIVIDUAL
// ============================
public function generarPDFProducto() {
    $codigo = $_GET['Codigo'] ?? null;

    if (!$codigo) {
        echo "Código del producto no proporcionado.";
        return;
    }

    $producto = $this->userModel->obtenerProductoPorCodigo($codigo);

    if (!$producto) {
        echo "Producto no encontrado.";
        return;
    }

    require_once './Libs/fpdf186/fpdf.php';

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Ficha del Producto', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);

    foreach ($producto as $campo => $valor) {
        $campo = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $campo);
        $valor = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $valor);

        $pdf->Cell(60, 10, $campo . ':', 0, 0);
        $pdf->Cell(100, 10, $valor, 0, 1);
    }

    if (ob_get_length()) {
        ob_end_clean();
    }

    $pdf->Output('I', 'Producto_' . $codigo . '.pdf');
}

// ============================
// PRODUCTO: PDF GENERAL
// ============================
public function generarPDFProductos() {
    $productos = $this->userModel->obtenerTodosLosProductos();

    if (!$productos || count($productos) === 0) {
        echo "No hay productos para mostrar.";
        return;
    }

    require_once './Libs/fpdf186/fpdf.php';

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Listado General de Productos', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);

    foreach ($productos as $producto) {
        foreach ($producto as $campo => $valor) {
            $campo = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $campo);
            $valor = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $valor);

            $pdf->Cell(60, 10, $campo . ':', 0, 0);
            $pdf->Cell(100, 10, $valor, 0, 1);
        }
        $pdf->Ln(8);

        if ($pdf->GetY() > 260) {
            $pdf->AddPage();
        }
    }

    if (ob_get_length()) {
        ob_end_clean();
    }

    $pdf->Output('I', 'Listado_Productos.pdf');
}
     //FIN TODO LO DE PRODUCTO
       
 // ============================
// PEDIDO
// ============================

// Consulta
public function getAllPedido($idPedido = '') {
    return $this->userModel->listaPedidos($idPedido);
}


public function listaPedidos() {
    $idPedido = $_GET['idPedido'] ?? '';  // ✅ Captura del formulario
    $pedidos = $this->userModel->listaPedidos($idPedido);  // ✅ Usa el modelo con filtro
    // Lógica para pasar $pedidos a la vista aquí, si la tienes
}


// INSERTAR
public function insertPedido() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $FechaPedido = $_POST["FechaPedido"] ?? null;
        $PedidoPor = $_POST["PedidoPor"] ?? null;          // DocEmpleado
        $DocProveedor = $_POST["DocProveedor"] ?? null;

        if ($FechaPedido && $PedidoPor && $DocProveedor) {
            try {
                $this->userModel->insertPedido($FechaPedido, $PedidoPor, $DocProveedor);
                $_SESSION['message'] = "Pedido insertado correctamente.";
                header("Location: index3.php?action=listaPedidos");
                exit();
            } catch (PDOException $e) {
                $_SESSION['message'] = "Error al insertar pedido: " . $e->getMessage();
            }
        } else {
            $_SESSION['message'] = "Todos los campos son obligatorios.";
        }
    }

    // Para llenar selects (si necesitas, asumo que puedes traer empleados y proveedores)
    $empleados = $this->userModel->getEmpleados();
    $proveedores = $this->userModel->getProveedores();
    include './views/Pedido/insert_Pedido.php';
}

// Actualizar: mostrar formulario
public function openFormPedido() {
    if (!isset($_GET['idPedido'])) {
        echo "ID de pedido no especificado.";
        return;
    }

    $idPedido = $_GET['idPedido'];
    $pedido = $this->userModel->getPedidoPorId($idPedido);

    if (!$pedido) {
        echo "Pedido no encontrado.";
        return;
    }

    $empleados = $this->userModel->getEmpleados();
    $proveedores = $this->userModel->getProveedores();
    include('./views/Pedido/actualizarPedido.php');
}

// Actualizar: procesar
public function actualizarPedido() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $idPedidoOriginal = $_POST['idPedidoOriginal'];
        $FechaPedido = $_POST['FechaPedido'];
        $PedidoPor = $_POST['PedidoPor'];
        $DocProveedor = $_POST['DocProveedor'];

        try {
            $this->userModel->actualizarPedido(
                $idPedidoOriginal, $FechaPedido, $PedidoPor, $DocProveedor
            );

            $_SESSION['message'] = "Pedido actualizado correctamente.";
            header("Location: index3.php?action=listaPedidos");
            exit();
        } catch (Exception $e) {
            $_SESSION['message'] = "Error al actualizar el pedido: " . $e->getMessage();
        }
    }
}

// ELIMINAR
public function eliminarPedido() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $idPedido = $_POST['idPedido'] ?? null;

        if ($idPedido) {
            try {
                $this->userModel->eliminarPedido($idPedido);
                $_SESSION['message'] = "Pedido eliminado correctamente.";
            } catch (Exception $e) {
                $_SESSION['message'] = "Error al eliminar el pedido: " . $e->getMessage();
            }
        } else {
            $_SESSION['message'] = "ID de pedido no proporcionado.";
        }
        header("Location: index3.php?action=listaPedidos");
        exit();
    }
}
// ============================
// PEDIDO: PDF INDIVIDUAL
// ==========================='
public function generarPDFPedido() {
    $id = $_GET['idPedido'] ?? null;
    if (!$id) {
        echo "ID de pedido no proporcionado.";
        return;
    }

    $pedido = $this->userModel->obtenerPedidoPorId($id);
    $detalles = $this->userModel->obtenerDetallesPedido($id);

    if (!$pedido || empty($detalles)) {
        echo "Pedido no encontrado o sin detalles.";
        return;
    }

    require_once './Libs/fpdf186/fpdf.php';

    // Limpieza de cualquier salida anterior
    if (ob_get_length()) {
        ob_end_clean();
    }

    $pdf = new FPDF('P', 'mm', array(215.9, 279.4));
    $pdf->AddPage();

    // Encabezado empresa
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'TELAS Y COSTURAS', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 7, 'Direccion: Av. Central #123', 0, 1, 'C');
    $pdf->Cell(0, 7, 'Tel: (505) 1234-5678', 0, 1, 'C');
    $pdf->Ln(10);

    // Datos del pedido
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 8, 'Numero de Factura:', 0, 0);
    $pdf->Cell(0, 8, $pedido['idPedido'], 0, 1);
    $pdf->Cell(40, 8, 'Fecha:', 0, 0);
    $pdf->Cell(0, 8, $pedido['FechaPedido'], 0, 1);

    $nombreProveedor = $pedido['NombreProveedor'] ?: $pedido['DocProveedor'];
    $pdf->Cell(40, 8, 'Proveedor:', 0, 0);
    $pdf->Cell(0, 8, $nombreProveedor, 0, 1);
    $pdf->Ln(8);

    // Tabla de productos
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, 'Cant', 1, 0, 'C');
    $pdf->Cell(90, 10, 'Producto', 1, 0, 'C');
    $pdf->Cell(35, 10, 'P. Uni.', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Subtotal', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 12);
    $total = 0;

    foreach ($detalles as $item) {
        $cant = $item['cant'];
        $nombre = $item['NombreProducto'] ?? $item['Codigo'];
        $precio = $item['PrecioUni'];
        $subtotal = $cant * $precio;
        $total += $subtotal;

        $pdf->Cell(20, 8, $cant, 1, 0, 'C');
        $pdf->Cell(90, 8, $nombre, 1, 0);
        $pdf->Cell(35, 8, number_format($precio, 0, ',', '.'), 1, 0, 'R');
        $pdf->Cell(40, 8, number_format($subtotal, 0, ',', '.'), 1, 1, 'R');
    }

    // Total final
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(145, 10, 'TOTAL', 1, 0, 'R');
    $pdf->Cell(40, 10, number_format($total, 0, ',', '.'), 1, 1, 'R');

    $pdf->Output('I', 'Pedido_' . $id . '.pdf');
}
public function generarPDFPedidos() {
    $pedidos = $this->userModel->obtenerTodosLosPedidos();

    if (!$pedidos || count($pedidos) === 0) {
        echo "No hay pedidos para mostrar.";
        return;
    }

    require_once './Libs/fpdf186/fpdf.php';

    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Listado General de Pedidos', 0, 1, 'C');
    $pdf->Ln(5);

    $this->dibujarTablaPDF($pdf, $pedidos);

    if (ob_get_length()) {
        ob_end_clean();
    }

    $pdf->Output('I', 'Pedidos_Listado.pdf');
}



// ============================
// PEDIDO: PDF GRUPAL
// ============================

// Reusa la función dibujarTablaPDF igual que con proveedores

// ============================
// DETALLE PEDIDO
// ============================

// Consulta
public function getAllDetallePedidos($idDetalle = '') {
    return $this->userModel->listaDetallePedidos($idDetalle);
}


// Función que obtiene el detalle o todos si no hay filtro
public function listaDetallePedidos() {
    $idDetalle = $_GET['idDetalle'] ?? '';
    $detalles = $this->userModel->listaDetallePedidos($idDetalle);
    return $detalles;
}






// INSERTAR

public function insertDetallePedido() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $cantidades = $_POST["cant"] ?? [];
        $precios = $_POST["PrecioUni"] ?? [];
        $codigos = $_POST["Codigo"] ?? [];
        $IdPedido = $_POST["IdPedido"] ?? null;

        if ($IdPedido && count($cantidades) > 0) {
            try {
                $this->userModel->insertMultiplesDetalles($cantidades, $precios, $IdPedido, $codigos);
                $_SESSION['message'] = "Detalles insertados correctamente.";
                header("Location: index3.php?action=listaDetallePedidos");
                exit();
            } catch (PDOException $e) {
                $_SESSION['message'] = "Error al insertar detalles: " . $e->getMessage();
            }
        } else {
            $_SESSION['message'] = "Todos los campos son obligatorios.";
        }
    }

    $pedidos = $this->userModel->getPedidos();
    $productos = $this->userModel->getProductos();
    include './views/Detalle/insert_DetallePedido.php';
}


// Mostrar formulario de actualización
public function openFormDetallePedido() {
    if (!isset($_GET['idDetalle'])) {
        echo "ID de detalle no especificado.";
        return;
    }

    $idDetalle = $_GET['idDetalle'];
    $detalle = $this->userModel->getDetallePedidoPorId($idDetalle);

    if (!$detalle) {
        echo "Detalle no encontrado.";
        return;
    }

    $pedidos = $this->userModel->getPedidos();
    $productos = $this->userModel->getProductos();

    include('./views/Detalle/actualizarDetallePedido.php');
}

public function actualizarDetallePedido() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $idDetalle = $_POST['idDetalleOriginal'] ?? null;
        $cant = $_POST['cant'] ?? null;
        $PrecioUni = $_POST['PrecioUni'] ?? null;
        $IdPedido = $_POST['IdPedido'] ?? null;
        $Codigo = $_POST['Codigo'] ?? null;

        if ($idDetalle && $cant && $PrecioUni && $IdPedido && $Codigo) {
            try {
                $this->userModel->actualizarDetallePedido($idDetalle, $cant, $PrecioUni, $IdPedido, $Codigo);
                $_SESSION['message'] = "✅ Detalle actualizado correctamente.";
                header("Location: index3.php?action=listaDetallePedidos");
                exit();
            } catch (Exception $e) {
                $_SESSION['message'] = "❌ Error al actualizar: " . $e->getMessage();
            }
        } else {
            $_SESSION['message'] = "Todos los campos son obligatorios.";
        }
    }
}


// ELIMINAR
public function eliminarDetallePedido() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $idDetalle = $_POST['idDetalle'] ?? null;

        if ($idDetalle) {
            try {
                $this->userModel->eliminarDetallePedido($idDetalle);
                $_SESSION['message'] = "Detalle eliminado correctamente.";
            } catch (Exception $e) {
                $_SESSION['message'] = "Error al eliminar el detalle: " . $e->getMessage();
            }
        } else {
            $_SESSION['message'] = "ID de detalle no proporcionado.";
        }
        header("Location: index3.php?action=listaDetallePedidos");
        exit();
    }
}

// ============================
// DETALLE: PDF INDIVIDUAL
// ============================
public function generarPDFDetallePedido() {
    $id = $_GET['idDetalle'] ?? null;

    if (!$id) {
        echo "ID de detalle no proporcionado.";
        return;
    }

    $detalle = $this->userModel->obtenerDetallePedidoPorId($id);

    if (!$detalle) {
        echo "Detalle no encontrado.";
        return;
    }

    require_once './Libs/fpdf186/fpdf.php';

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Ficha del Detalle de Pedido', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);

    foreach ($detalle as $campo => $valor) {
        $campo = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $campo);
        $valor = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $valor);

        $pdf->Cell(60, 10, $campo . ':', 0, 0);
        $pdf->Cell(100, 10, $valor, 0, 1);
    }

    if (ob_get_length()) {
        ob_end_clean();
    }

    $pdf->Output('I', 'DetallePedido_' . $id . '.pdf');
}

// ============================
// DETALLE: PDF GRUPAL
// ============================
public function generarPDFDetallePedidos() {
    $detalles = $this->userModel->obtenerTodosLosDetallePedidos();

    if (!$detalles || count($detalles) === 0) {
        echo "No hay detalles para mostrar.";
        return;
    }

    require_once './Libs/fpdf186/fpdf.php';

    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Listado General de Detalles de Pedidos', 0, 1, 'C');
    $pdf->Ln(5);

    $this->dibujarTablaPDF($pdf, $detalles);

    if (ob_get_length()) {
        ob_end_clean();
    }

    $pdf->Output('I', 'DetallePedidos_Listado.pdf');
}


}
?>
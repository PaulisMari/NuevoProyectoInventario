<?php
class UserModel {
    private $conn;
    private $table6 = "producto";

    public function __construct($db) {
        $this->conn = $db;
    }


public function registerUser($username, $password): bool {
    // 1. Verificar que el DocEmpleado existe
    $checkEmpleado = $this->conn->prepare('SELECT 1 FROM empleado WHERE DocEmpleado = :doc');
    $checkEmpleado->bindParam(':doc', $username, PDO::PARAM_INT);
    $checkEmpleado->execute();

    if ($checkEmpleado->rowCount() === 0) {
        return false; // El documento no existe en empleados
    }

    // 2. Verificar que NO exista ya un usuario con ese documento
    $checkUsuario = $this->conn->prepare('SELECT 1 FROM usuarios WHERE usuario = :doc');
    $checkUsuario->bindParam(':doc', $username, PDO::PARAM_INT);
    $checkUsuario->execute();

    if ($checkUsuario->rowCount() > 0) {
        return false; // Ya hay un usuario con ese documento
    }

    // 3. Hashear la contraseña antes de guardar
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 4. Insertar el nuevo usuario
    $stmt = $this->conn->prepare('INSERT INTO usuarios (usuario, contraseña) VALUES (:username, :password)');
    $stmt->bindParam(':username', $username, PDO::PARAM_INT);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

    return $stmt->execute();
}

//RECUPERAR CONTRASEÑA

// En tu modelo UserModel
public function getEmailByDocumento($doc) {
    $stmt = $this->conn->prepare("SELECT Email FROM empleado WHERE DocEmpleado = ?");
    $stmt->execute([$doc]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function guardarToken($usuario, $token, $expiracion) {
    $stmt = $this->conn->prepare("INSERT INTO recovery_tokens (usuario, token, expiracion) VALUES (?, ?, ?)");
    $stmt->execute([$usuario, $token, $expiracion]);
}


public function obtenerCorreoPorDocumento($documento) {
    $stmt = $this->conn->prepare("SELECT Email FROM empleado WHERE DocEmpleado = ?");
    $stmt->execute([$documento]);
    return $stmt->fetchColumn();  // Te devuelve solo el email
}
    // Buscar email en tabla empleado por documento (usuario)
    public function getEmailByUsuario($usuario) {
        $stmt = $this->conn->prepare("SELECT Email FROM empleado WHERE DocEmpleado = ?");
        $stmt->execute([$usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insertar token de recuperación
    public function saveRecoveryToken($usuario, $token, $expiracion) {
        $stmt = $this->conn->prepare("INSERT INTO recovery_tokens (usuario, token, expiracion) VALUES (?, ?, ?)");
        return $stmt->execute([$usuario, $token, $expiracion]);
    }

    // Validar token válido y no usado
    public function getValidToken($token) {
        $stmt = $this->conn->prepare("SELECT * FROM recovery_tokens WHERE token = ? AND usado = FALSE AND expiracion > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar contraseña para un usuario
    public function updatePassword($usuario, $password) {
        // Aquí conviene usar password_hash($password, PASSWORD_DEFAULT)
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE usuarios SET contraseña = ? WHERE usuario = ?");
        return $stmt->execute([$hash, $usuario]);
    }

    // Marcar token como usado
    public function markTokenUsed($id) {
        $stmt = $this->conn->prepare("UPDATE recovery_tokens SET usado = TRUE WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Método para validar credenciales login (con hash)
  public function checkCredentials($username, $password) {
    $stmt = $this->conn->prepare('SELECT * FROM usuarios WHERE usuario = :username');
    $stmt->bindParam(':username', $username, PDO::PARAM_INT); // IMPORTANTE: tipo INT
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['contraseña'])) {
        return $user;
    }
    return false;
}

public function listaUsuarios($usuario = '') {
    if (!empty($usuario)) {
        $query = "SELECT * FROM usuarios WHERE usuario LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%$usuario%"]);
    } else {
        $query = "SELECT * FROM usuarios";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getUsuariosByNumberDocument($usuario) {
    $query = "SELECT * FROM usuarios WHERE usuario LIKE :usuario";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([':usuario' => '%' . $usuario . '%']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getUsuarios() {
    $query = "SELECT id, usuario FROM usuarios";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function eliminarUsuario($id) {
        $query = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    // ============================
    // EMPLEADO: CONSULTAR
    // ============================

    public function listaEmpleados($docEmpleado = '') {
    if (!empty($docEmpleado)) {
        $query = "SELECT e.*, t.tipo AS TipoDocumentoNombre 
                  FROM empleado e 
                  INNER JOIN tipodocumento t ON e.TipoDoc = t.id
                  WHERE e.DocEmpleado LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%$docEmpleado%"]);
    } else {
        $query = "SELECT e.*, t.tipo AS TipoDocumentoNombre 
                  FROM empleado e 
                  INNER JOIN tipodocumento t ON e.TipoDoc = t.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function getEmpleados() {
    $query = "SELECT e.*, t.tipo AS TipoDocumentoNombre 
              FROM empleado e 
              INNER JOIN tipodocumento t ON e.TipoDoc = t.id";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function getTiposDocumento() {
        $query = "SELECT id, tipo FROM tipodocumento";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     public function getEmpleadoPorDocumento($docEmpleado) {
         $query = "SELECT * FROM empleado WHERE DocEmpleado = ?";
         $stmt = $this->conn->prepare($query);
         $stmt->execute([$docEmpleado]);
         return $stmt->fetch(PDO::FETCH_ASSOC);
     }

    // ============================
    // EMPLEADO: INSERTAR
    // ============================

    public function insertEmpleado($docEmpleado, $TipoDoc, $Nombre, $FechaNaci, $Telefono, $Direccion, $Email) {
        try {
            $query = "INSERT INTO empleado (DocEmpleado, TipoDoc, Nombre, FechaNaci, Telefono, Direccion, Email)
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$docEmpleado, $TipoDoc, $Nombre, $FechaNaci, $Telefono, $Direccion, $Email]);
            $_SESSION['message'] = "Empleado insertado correctamente.";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Error al insertar el empleado: " . $e->getMessage();
        }
    }

    // ============================
    // EMPLEADO: ACTUALIZAR
    // ============================

    public function actualizarEmpleado($docEmpleadoOriginal, $docEmpleado, $tipoDoc, $nombre, $fechaNaci, $telefono, $direccion, $email) {
        try {
            $query = "UPDATE empleado 
                      SET DocEmpleado = ?, TipoDoc = ?, Nombre = ?, FechaNaci = ?, Telefono = ?, Direccion = ?, Email = ?
                      WHERE DocEmpleado = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$docEmpleado, $tipoDoc, $nombre, $fechaNaci, $telefono, $direccion, $email, $docEmpleadoOriginal]);
        } catch (PDOException $e) {
            error_log("❌ Error en la actualización: " . $e->getMessage());
            throw $e;
        }
    }

    // ============================
    // EMPLEADO: ELIMINAR
    // ============================

    public function eliminarEmpleado($docEmpleado) {
        $sql = "DELETE FROM empleado WHERE DocEmpleado = :docEmpleado";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':docEmpleado', $docEmpleado, PDO::PARAM_STR);
        $stmt->execute();
    }

     // ============================
    // EMPLEADO: PDF
    // ============================

    public function obtenerEmpleadoPorDocumento($docEmpleado) {
    $stmt = $this->conn->prepare("SELECT * FROM empleado WHERE DocEmpleado = ?");
    $stmt->execute([$docEmpleado]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function obtenerTodosLosEmpleados() {
    // Suponiendo que tienes una propiedad $this->db que es la conexión PDO
    $sql = "SELECT DocEmpleado, TipoDoc, Nombre, FechaNaci, Telefono, Direccion, Email FROM empleado";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    // Obtener todos los registros como array asociativo
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $empleados;
}

    // ============================
    // PROVEEDOR
    // ============================

  
  public function listaProveedores($docProveedor = '') {
    if (!empty($docProveedor)) {
        $query = "SELECT p.*, t.tipo AS TipoDocumentoNombre 
                  FROM proveedor p
                  INNER JOIN tipodocumento t ON p.TipoDoc = t.id
                  WHERE p.DocProveedor LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%$docProveedor%"]);
    } else {
        $query = "SELECT p.*, t.tipo AS TipoDocumentoNombre 
                  FROM proveedor p
                  INNER JOIN tipodocumento t ON p.TipoDoc = t.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getProveedores() {
    $query = "SELECT * FROM proveedor";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getProveedorPorDocumento($docProveedor) {
    $query = "SELECT * FROM proveedor WHERE DocProveedor = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$docProveedor]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//INSERTAR
public function insertProveedor($DocProveedor, $TipoDoc, $Nombre, $Telefono, $Direccion, $Email) {
    try {
        $query = "INSERT INTO proveedor (DocProveedor, TipoDoc, Nombre, Telefono, Direccion, Email)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$DocProveedor, $TipoDoc, $Nombre, $Telefono, $Direccion, $Email]);
    } catch (PDOException $e) {
        throw $e; // Lo vuelves a lanzar para que el controlador lo capture
    }
}

//ACTUALIZAR
public function actualizarProveedor($docProveedorOriginal, $docProveedor, $tipoDoc, $nombre, $telefono, $direccion, $email) {
    try {
        $query = "UPDATE proveedor 
                  SET DocProveedor = ?, TipoDoc = ?, Nombre = ?, Telefono = ?, Direccion = ?, Email = ?
                  WHERE DocProveedor = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$docProveedor, $tipoDoc, $nombre, $telefono, $direccion, $email, $docProveedorOriginal]);
    } catch (PDOException $e) {
        error_log("❌ Error en la actualización del proveedor: " . $e->getMessage());
        throw $e;
    }
}

//ELIMINAR
public function eliminarProveedor($docProveedor) {
    $sql = "DELETE FROM proveedor WHERE DocProveedor = :docProveedor";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':docProveedor', $docProveedor, PDO::PARAM_STR);
    $stmt->execute();
}
// ============================
// PROVEEDOR: PDF
// ============================

public function obtenerProveedorPorDocumento($docProveedor) {
    $stmt = $this->conn->prepare("SELECT * FROM proveedor WHERE DocProveedor = ?");
    $stmt->execute([$docProveedor]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function obtenerTodosLosProveedores() {
    $sql = "SELECT DocProveedor, TipoDoc, Nombre, Telefono, Direccion, Email FROM proveedor";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    // Obtener todos los registros como array asociativo
    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $proveedores;
}

     // ============================
    // FIN PROVEEDOR
    // ============================
    

//INICIO ENTRADA

// Lista de entradas (con búsqueda opcional por ID)
public function listaEntradas($idEntrada = '') {
    if (!empty($idEntrada)) {
        $query = "SELECT * FROM entrada WHERE idEntrada LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%$idEntrada%"]);
    } else {
        $query = "SELECT * FROM entrada";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Consulta una entrada exacta por ID
public function getEntradaById($idEntrada) {
    $query = "SELECT * FROM entrada WHERE idEntrada = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$idEntrada]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener todas las entradas sin filtro (uso general)
public function getEntradas() {
    $query = "SELECT * FROM entrada";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function obtenerEntradaPorId($idEntrada) {
    $stmt = $this->conn->prepare("SELECT * FROM entrada WHERE idEntrada = ?");
    $stmt->execute([$idEntrada]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function obtenerTodasLasEntradas() {
    $sql = "SELECT idEntrada, DescripcionEntrada, CantidadEntrada, FechaEntrada, PrecioUni, Codigo FROM entrada";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    $entradas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $entradas;
}

//Insertar entrada

public function insertEntrada($descripcionEntrada, $cantidadEntrada, $fechaEntrada, $precioUni, $codigo) {
    try {
        $checkQuery = "SELECT COUNT(*) FROM producto WHERE codigo = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->execute([$codigo]); // codigo del formulario de producto
        if ($checkStmt->fetchColumn() == 0) {
            $_SESSION['message'] = "Error: El codigo del producto no existe.";
            return;
        }

        $query = "INSERT INTO entrada (descripcionEntrada, cantidadEntrada, fechaEntrada, precioUni, codigo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([$descripcionEntrada, $cantidadEntrada, $fechaEntrada, $precioUni, $codigo])) {
            $updateQuery = "UPDATE producto SET CantDis = CantDis + ? WHERE Codigo = ?";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->execute([$cantidadEntrada, $codigo]);

            $_SESSION['message'] = "Entrada insertado correctamente.";
        } else {
            $_SESSION['message'] = "Error al insertar entrada.";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error al insertar entrada: " . $e->getMessage();
    }
}

//Seleccionar codigo de producto
public function getCodigoP(){//codigo de producto
    $query= "SELECT * FROM producto";
    $stmt= $this->conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//Actualiza entrada
public function actualizarEntra($descripcionEntrada, $cantidadEntrada, $fechaEntrada, $precioUni, $codigo, $idEntrada) {
    $query = "UPDATE entrada SET descripcionEntrada = ?, cantidadEntrada = ?, fechaEntrada = ?, precioUni = ?, codigo = ? 
        WHERE idEntrada = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$descripcionEntrada, $cantidadEntrada, $fechaEntrada, $precioUni, $codigo, $idEntrada]);
}

public function eliminarEntrada($idEntrada){
    try {
        $query = "DELETE FROM entrada WHERE idEntrada = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idEntrada]);
    } catch (PDOException $e) {
        echo "Error al eliminar: " . $e->getMessage();
    }
}

//INICIO SALIDA

// Lista de salidas, con o sin filtro por ID
public function listaSalidas($idSalida = '') {
    if (!empty($idSalida)) {
        $query = "SELECT * FROM salida WHERE idSalida LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%$idSalida%"]);
    } else {
        $query = "SELECT * FROM salida";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Consulta una salida exacta por ID
public function getSalidaById($idSalida) {
    $query = "SELECT * FROM salida WHERE idSalida = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$idSalida]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener todas las salidas sin filtro (uso general)
public function getSalidas() {
    $query = "SELECT * FROM salida";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function obtenerSalidaPorId($idSalida) {
    $stmt = $this->conn->prepare("SELECT * FROM salida WHERE idSalida = ?");
    $stmt->execute([$idSalida]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function obtenerTodasLasSalidas() {
    $sql = "SELECT idSalida, MotivoSalida, CantidadSalida, FechaSalida, Codigo FROM salida";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    $salidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $salidas;
}

public function insertSalida($motivoSalida, $cantidadSalida, $fechaSalida, $codigo) {
    try {
            $checkQuery = "SELECT COUNT(*) FROM producto WHERE codigo = ?";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([$codigo]); // codigo del formulario de producto
            if ($checkStmt->fetchColumn() == 0) {
                $_SESSION['message'] = "Error: El codigo del producto no existe.";
                return;
            }
             // 2. Verificar que hay suficiente stock
            $stmt = $this->conn->prepare("SELECT CantDis FROM producto WHERE Codigo = ?");
            $stmt->execute([$codigo]);
            $stock = $stmt->fetchColumn();

            if ($stock < $cantidadSalida) {
            $_SESSION['message'] = "Error: No hay suficiente stock para esta salida.";
            return;
            }

            $query = "INSERT INTO salida (motivoSalida, cantidadSalida, fechaSalida, codigo) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            if ($stmt->execute([$motivoSalida, $cantidadSalida, $fechaSalida, $codigo])) {
                // 4. Actualizar el stock disponible
            $updateQuery = "UPDATE producto SET CantDis = CantDis - ? WHERE Codigo = ?";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->execute([$cantidadSalida, $codigo]);
            
                $_SESSION['message'] = "Salida insertado correctamente.";
            } else {
                $_SESSION['message'] = "Error al insertar salida.";
            }
    } catch (PDOException $e) {
            $_SESSION['message'] = "Error al insertar salida: " . $e->getMessage();
    }
}
        
//Seleccionar codigo de producto
public function getCodigoProS(){//codigo de producto
    $query= "SELECT * FROM producto";
    $stmt= $this->conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    
public function actualizarSal($motivoSalida, $cantidadSalida, $fechaSalida, $codigo, $idSalida) {
    $query = "UPDATE salida SET motivoSalida = ?, cantidadSalida = ?, fechaSalida = ?, codigo = ? WHERE idSalida = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$motivoSalida, $cantidadSalida, $fechaSalida, $codigo, $idSalida]);
}

//Eliminar salida
public function eliminarSalida($idSalida){
    try {
            $query = "DELETE FROM salida WHERE idSalida = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$idSalida]);
    } catch (PDOException $e) {
        echo "Error al eliminar: " . $e->getMessage();
    }
}

//TODO LO DE PRODUCTO

public function getAllProducto()
    {
        $sql = "SELECT * FROM producto";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

// Método para insertar producto
public function insertproducto($Codigo, $NombreProducto, $Descripcion, $Precio, $CantMin, $CantMax, $CantDis, $CreadoPor)
{
    try {
        $query = "INSERT INTO producto (Codigo, NombreProducto, Descripcion, Precio, CantMin, CantMax, CantDis, CreadoPor) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute([$Codigo, $NombreProducto, $Descripcion, $Precio, $CantMin, $CantMax, $CantDis, $CreadoPor])) {
            $_SESSION['message'] = "Producto insertado correctamente.";
        } else {
            $_SESSION['message'] = "Error al insertar producto.";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error al insertar producto: " . $e->getMessage();
    }
}

public function buscarProducto($Codigo) {
    try {
        $query = "SELECT * FROM producto 
                  WHERE CAST(Codigo AS CHAR) LIKE ? 
                     OR NombreProducto LIKE ? 
                     OR Descripcion LIKE ?
                     OR Precio LIKE ?
                     OR CantMin LIKE ?
                     OR CantMax LIKE ?
                     OR CantDis LIKE ?
                     OR CreadoPor LIKE ?";
        $param = "%" . $Codigo . "%";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$param, $param, $param]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error al buscar producto: " . $e->getMessage();
        return[];
}
}
    // ACTUALIZAR

    public function getproductoById($Codigo)
    {
        $query = "SELECT * FROM producto WHERE Codigo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$Codigo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getproducto()
    {
        $query = "SELECT * FROM producto";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarpro($CodigoOriginal, $Codigo, $NombreProducto, $Descripcion, $Precio, $CantMax, $CantMin, $CantDis, $CreadoPor)
    {
        $query = "UPDATE producto
                      SET Codigo = ?, NombreProducto = ?, Descripcion = ?, Precio = ?,  CantMax = ?, CantMin = ?, CantDis = ?, CreadoPor = ?
                      WHERE Codigo = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$Codigo, $NombreProducto, $Descripcion, $Precio, $CantMax,  $CantMin, $CantDis, $CreadoPor, $CodigoOriginal]);
    }

    // ELIMINAR PRODUCTO

    public function getproductoByn($Codigo)
    {
        $query = "SELECT * FROM producto WHERE Codigo LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['%' . $Codigo . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarProducto($Codigo)
    {
        try {
            $query = "DELETE FROM producto WHERE Codigo=?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$Codigo]);
        } catch (PDOException $e) {
            echo "Error al eliminar: " . $e->getMessage();
        }
    }


public function cedulaExiste($cedula) {
    $sql = "SELECT COUNT(*) FROM empleado WHERE DocEmpleado = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$cedula]);
    return $stmt->fetchColumn() > 0;
}

public function getProductos() {
    $query = "SELECT * FROM producto";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// ============================
// PRODUCTO: obtener por código
// ============================
public function obtenerProductoPorCodigo($codigo) {
    $stmt = $this->conn->prepare("SELECT * FROM producto WHERE Codigo = ?");
    $stmt->execute([$codigo]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna un único producto
}

// ============================
// PRODUCTO: obtener todos
// ============================
public function obtenerTodosLosProductos() {
    $sql = "SELECT Codigo, NombreProducto, Descripcion, Precio, CantMin, CantMax, CantDis, CreadoPor FROM producto";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos los productos
}


// ============================
// PEDIDO
// ============================

// Listar pedidos (con opción a filtrar por idPedido)
public function listaPedidos($idPedido = '') {
    if (!empty($idPedido)) {
        $query = "SELECT p.*, e.Nombre AS EmpleadoNombre, pr.Nombre AS ProveedorNombre
                  FROM pedido p
                  INNER JOIN empleado e ON p.PedidoPor = e.DocEmpleado
                  INNER JOIN proveedor pr ON p.DocProveedor = pr.DocProveedor
                  WHERE p.idPedido LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%$idPedido%"]);
    } else {
        $query = "SELECT p.*, e.Nombre AS EmpleadoNombre, pr.Nombre AS ProveedorNombre
                  FROM pedido p
                  INNER JOIN empleado e ON p.PedidoPor = e.DocEmpleado
                  INNER JOIN proveedor pr ON p.DocProveedor = pr.DocProveedor";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener todos los pedidos sin filtro
public function getPedidos() {
    $query = "SELECT * FROM pedido";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener un pedido por su id
public function getPedidoPorId($idPedido) {
    $query = "SELECT * FROM pedido WHERE idPedido = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$idPedido]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Insertar un nuevo pedido
public function insertPedido($FechaPedido, $PedidoPor, $DocProveedor) {
    try {
        $query = "INSERT INTO pedido (FechaPedido, PedidoPor, DocProveedor) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$FechaPedido, $PedidoPor, $DocProveedor]);
    } catch (PDOException $e) {
        throw $e;
    }
}

// Actualizar un pedido existente
public function actualizarPedido($idPedidoOriginal, $FechaPedido, $PedidoPor, $DocProveedor) {
    try {
        $query = "UPDATE pedido 
                  SET FechaPedido = ?, PedidoPor = ?, DocProveedor = ?
                  WHERE idPedido = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$FechaPedido, $PedidoPor, $DocProveedor, $idPedidoOriginal]);
    } catch (PDOException $e) {
        error_log("❌ Error en la actualización del pedido: " . $e->getMessage());
        throw $e;
    }
}

// Eliminar un pedido por su id
public function eliminarPedido($idPedido) {
    $sql = "DELETE FROM pedido WHERE idPedido = :idPedido";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
    $stmt->execute();
}


public function obtenerPedidoPorId($idPedido) {
    $sql = "SELECT p.*, pr.Nombre AS NombreProveedor
            FROM pedido p
            LEFT JOIN proveedor pr ON p.DocProveedor = pr.DocProveedor
            WHERE p.idPedido = :idPedido";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function obtenerDetallesPedido($idPedido) {
    $sql = "
        SELECT dp.cant, dp.PrecioUni, dp.Codigo, p.NombreProducto
        FROM detallepedido dp
        LEFT JOIN producto p ON dp.Codigo = p.Codigo
        WHERE dp.IdPedido = :idPedido
    ";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function obtenerTodosLosPedidos() {
    $sql = "SELECT idPedido, FechaPedido, PedidoPor, DocProveedor FROM pedido";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $pedidos;
}

// ============================
// FIN PEDIDO
// ============================

// ============================
// DETALLE PEDIDO
// ============================

// Listar detallepedidos (con opción a filtrar por idDetalle)
// Modelo
public function listaDetallePedidos($idDetalle = '') {
    if (!empty($idDetalle)) {
        $query = "SELECT dp.*, p.FechaPedido, pr.NombreProducto
                  FROM detallepedido dp
                  LEFT JOIN pedido p ON dp.IdPedido = p.idPedido
                  LEFT JOIN producto pr ON dp.Codigo = pr.Codigo
                  WHERE dp.idDetalle LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['%' . $idDetalle . '%']);
    } else {
        $query = "SELECT dp.*, p.FechaPedido, pr.NombreProducto
                  FROM detallepedido dp
                  LEFT JOIN pedido p ON dp.IdPedido = p.idPedido
                  LEFT JOIN producto pr ON dp.Codigo = pr.Codigo";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Obtener todos los detallepedidos sin filtro
public function getDetallePedidos() {
    $query = "SELECT * FROM detallepedido";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener un detallepedido por su ID
public function getDetallePedidoPorId($idDetalle) {
    $query = "SELECT * FROM detallepedido WHERE idDetalle = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$idDetalle]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Insertar un nuevo detallepedido
public function insertDetallePedido($cant, $PrecioUni, $IdPedido, $Codigo) {
    try {
        $query = "INSERT INTO detallepedido (cant, PrecioUni, IdPedido, Codigo) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$cant, $PrecioUni, $IdPedido, $Codigo]);
    } catch (PDOException $e) {
        throw $e;
    }
}

public function insertMultiplesDetalles($cantidades, $precios, $IdPedido, $codigos) {
    $this->conn->beginTransaction();

    try {
        $stmt = $this->conn->prepare("INSERT INTO detallepedido (cant, PrecioUni, IdPedido, Codigo) VALUES (?, ?, ?, ?)");

        for ($i = 0; $i < count($cantidades); $i++) {
            $stmt->execute([
                $cantidades[$i],
                $precios[$i],
                $IdPedido,
                $codigos[$i]
            ]);
        }

        $this->conn->commit();
    } catch (PDOException $e) {
        $this->conn->rollBack();
        throw $e;
    }
}



// Actualizar un detallepedido existente
public function actualizarDetallePedido($idDetalle, $cant, $PrecioUni, $IdPedido, $Codigo) {
    try {
        $query = "UPDATE detallepedido 
                  SET cant = ?, PrecioUni = ?, IdPedido = ?, Codigo = ?
                  WHERE idDetalle = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$cant, $PrecioUni, $IdPedido, $Codigo, $idDetalle]);
    } catch (PDOException $e) {
        error_log("❌ Error al actualizar detallepedido: " . $e->getMessage());
        throw new Exception("No se pudo actualizar el detalle.");
    }
}


// Eliminar un detallepedido por su id
public function eliminarDetallePedido($idDetalle) {
    $sql = "DELETE FROM detallepedido WHERE idDetalle = :idDetalle";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':idDetalle', $idDetalle, PDO::PARAM_INT);
    $stmt->execute();
}

// Obtener un detallepedido para PDF individual
public function obtenerDetallePedidoPorId($idDetalle) {
    $stmt = $this->conn->prepare("SELECT * FROM detallepedido WHERE idDetalle = ?");
    $stmt->execute([$idDetalle]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener todos los detallepedidos para PDF grupal
public function obtenerTodosLosDetallePedidos() {
    $sql = "SELECT idDetalle, cant, PrecioUni, IdPedido, Codigo FROM detallepedido";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}







}
?>
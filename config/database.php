    <?php
class database{
    private $host= "Localhost";
    private $db_name="inventarionuevo";
    private $username= "root";
    private $password= "";
    public $conn;

    public function getConnection(){
        $this->conn = null;
        try{
            $this->conn = new PDO("mysql:host=". $this->host .";dbname=".$this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            //echo "Conexion correcta";

        } catch(PDOException $exception){
            echo "Error de conexion: ".$exception->getMessage();
        }
        return $this->conn;
    }
}



?>
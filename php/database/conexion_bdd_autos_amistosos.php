<?php

class ConexionBDautosAmistosos{
    private $conexion;
    private $host = "localhost:3306";
    private $usuario = "dianita";
    private $password = "dianita";
    private $bd = "autos_amistosos";

    public function __construct(  ){
        $this-> conexion = mysqli_connect($this->host,
    $this->usuario, $this->password, $this->bd );

    if(!$this->conexion)
        die("Error en la conexion a la BD". mysqli_connect_error());
    }
 
    public function getConexion(){
        return $this->conexion;
    }

}

?>
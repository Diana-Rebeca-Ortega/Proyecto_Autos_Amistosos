<?php
    class ConexionBDUsuarios{
        private $conexion;
        private $host = "localhost:3306";
        private $usuario = "dianita";
        private $password = "dianita";
        private $bd = "BD_Usuarios_AutosAmistosos_2025";
        
        public function __construct(){
            $this->conexion = mysqli_connect($this->host, $this->usuario, $this->password,
                            $this->bd);
            if(!$this->conexion)
                die ("Error en la conexion a la BD de USUARIOS" . mysqli_connect_error());
        }

        public function getConexion(){
            return $this->conexion;
        }
    }
?>
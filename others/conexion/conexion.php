<?php
class Conexion
{
   private $host = "10.100.99.1";
   private $port = "5432";
   private $dbname = "SysGym";
   private $user = "postgres";
   private $password = "Pokemon29";
   private $conexion;

   function getConexion()
   {
      $this->conexion = pg_connect("host=$this->host port=$this->port dbname=$this->dbname user=$this->user password=$this->password");
      return $this->conexion;
   }

   function close()
   {
      pg_close($this->conexion);
   }

}

?>
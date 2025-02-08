<?php
class Conexion
{
   private $host = "localhost";
   private $port = "5433";
   private $dbname = "SysGym";
   private $user = "postgres";
   private $password = "123";
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
<?php
   class DbConnect {
      private $server;

      function __construct() {
      }

      function connect() {
         include_once 'Config.php';

         $this->server = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

         if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
         }

         return $this->server;
      }
   }
?>

<?php
   class DbHandler {
      private $server_connection;

      function __construct() {
         require_once 'DbConnect.php';

         $db = new DbConnect();
         $this->server_connection = $db->connect();
      }

      /*
       *  Read operation.  Queries the WaitingRoom table using the UserId
       *  to get the QueuePosition.
       *
       *  Returns the QueuePosition encode as JSON
       */
      function getQueuePosition($UserId) {

         $result = mysqli_query($this->server_connection, 
          "SELECT QueuePosition FROM WaitingRoom WHERE UserId = $UserId");

         if ($result->num_rows > 1) {
            echo "Error: Mutiple users with same ID";
         }
         else if ($result->num_rows == 0) {
            echo "Error: No user has this ID";
         }
         else {         
            $queuePosition = $result->fetch_object();
            echo json_encode($queuePosition);
         }
      }
   }
?>

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
      
      /*
       * Read operation.  Queries the Doctors table for all Doctors.  
       *
       * Returns all doctor data encoded as JSON.
       */
      function getAllDoctors() {
         
         $result = mysqli_query($this->server_connection, 
          "SELECT * FROM Doctors");
         
         $doctors = array();
         while($row = mysqli_fetch_assoc($result))
         {
            $doctors[] = $row;
         }
         echo json_encode($doctors);
      }

      /*
       * Read operation. Queries the Users table to check whether or not
       * the specified user exists. 
       *
       * If the user exists, return the user.
       * If the user does not exist, return "No Such User Found"
       */
      function check($Username, $Password) {
         
         $result = mysqli_query($this->server_connection,
          "SELECT * FROM Users WHERE Username = '$Username' AND Password = '$Password'");
 
         if ($result->num_rows == 0)
            echo "No Such User Found";
         else {
            $foundUser = $result->fetch_object();
            echo json_encode($foundUser);
         }
      }
   }
?>

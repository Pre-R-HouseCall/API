<?php
   class DbHandler {
      private $server_connection;

      function __construct() {
         require_once 'DbConnect.php';

         $db = new DbConnect();
         $this->server_connection = $db->connect();
      }

      /*
       * Read operation. Queries the Forms table using the DoctorId and UserId
       * to get the Queue Position.
       *
       * Returns the DoctorId, UserId, and Position encode as JSON
       */
      function getQueuePosition($DoctorId, $UserId) {
         // local sql variable for row index
         $set_query = "SET @Index = 0";
         mysqli_query($this->server_connection, $set_query);
         
         $query = "SELECT Position FROM (SELECT DoctorId as DoctorId, UserId as UserId, @Index := @Index + 1 as Position FROM Forms WHERE DoctorId = '$DoctorId' ORDER BY DateTime) as f WHERE f.UserId = '$UserId'";
         $result = mysqli_query($this->server_connection, $query);

         if ($result->num_rows == 0) {
            echo "Error: No doctor/user set found";
         }
         else {
            $queuePosition = $result->fetch_object();
            echo json_encode($queuePosition);
         }                  
      }

      /* Read operation.  Queries the Doctors table for all Doctors.  
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

      /* Read operation. Queries the Forms table to find all Users in the Doctors Queue
       *
       * Returns all the users in JSON format
       */
      function getPatients($DoctorId) {
         
         $result = mysqli_query($this->server_connection,
          "SELECT * FROM Forms WHERE DoctorId = '$DoctorId' ORDER BY DateTime ASC");
         
         $users = array();
         while($row = mysqli_fetch_assoc($result)) 
         {
            $users[] = $row;
         }
         echo json_encode($users);
      }

      /*
       * Read operation. Queries the Users table to check whether or not
       * the specified user exists. 
       *
       * If the user exists, return the user.
       * If the user does not exist, return "No Such User Found"
       */
      function checkUser($Email, $Password) {
         
         $result = mysqli_query($this->server_connection,
          "SELECT * FROM Users WHERE Email = '$Email' AND Password = '$Password'");
 
         if ($result->num_rows == 0)
            echo "No Such User Found";
         else {
            $foundUser = $result->fetch_object();
            echo json_encode($foundUser);
         }
      }

      /*
       * Read operation. Queries the Users table to check whether or not
       * the specified user exists. 
       *
       * If the user exists, return the user.
       * If the user does not exist, return "No Such User Found"
       */
      function checkDoctor($Email, $Password) {
         
         $result = mysqli_query($this->server_connection,
          "SELECT * FROM Doctors WHERE Email = '$Email' AND Password = '$Password'");
 
         if ($result->num_rows == 0)
            echo "No Such User Found";
         else {
            $foundUser = $result->fetch_object();
            echo json_encode($foundUser);
         }
      }

      /*
       * Update operation. Queries the Doctor table to check whether or not
       * the specified user exists. 
       *
       * If the user exists, update the availablity.
       * If the user does not exist, return "No Such User Found"
       */
      function updateAvailability($DoctorId, $Availability) {
         
         $check = mysqli_query($this->server_connection,
          "SELECT * FROM Doctors WHERE DoctorId = '$DoctorId'");
         if ($check->num_rows == 0)
            echo "No Such User Found";
         else {
            $result = mysqli_query($this->server_connection,
             "UPDATE Doctors SET Availability = '$Availability'  WHERE DoctorId = '$DoctorId'");
            echo "Update Successful";
         }
      }
      
      //this function assumes it is given all of the corrrect information
      //the apps need to ensure that all of the required info is sent
      function addUser($name, $email, $password, $phoneNum, $address, $city, $state, $zip) {

         /*$firstName = $_POST['firstName'];
         $lastName = $_POST['lastName'];
         $email = $_POST['email'];
         $password = $_POST['password'];
         $phoneNum = $_POST['phoneNum'];
         $address = $_POST['address'];
         $city = $_POST['city'];
         $state = $_POST['state'];
         $zip = $_POST['zip'];
         */
         $search_query = "SELECT * from Users WHERE Email = '$email'";
         
         $search_result = mysqli_query($this->server_connection, $search_query);

         if ($search_result->num_rows == 0) {

            $insert_query = "INSERT into Users (Name, Email, Password, PhoneNumber, StreetAddress, City, State, Zip) 
               values ('$name', '$email', '$password', '$phoneNum', '$address', '$city', '$state', '$zip')"; 

            $insert_result = mysqli_query($this->server_connection,$insert_query);
            $grab_userId = mysqli_query($this->server_connection,"select UserId from Users where Email = '$email'");         
            $var = $grab_userId->fetch_object();
            echo json_encode($var); 
         }
         else {
            echo "User Exists";
         }
      }

      function addForm($doctorId, $userId, $name, $email, $number, $symptoms, $lat, $long, $street, $city, $state, $dateTime, $tag) {
         $insert_query = "INSERT into Forms (DoctorID, UserID, Name, Email, Number, Symptoms, Latitude, Longitude, StreetAddress, City, State, DateTime) values('$doctorId', '$userId', '$name', '$email', '$number', '$symptoms', '$lat', '$long', '$street','$city', '$state', '$dateTime', '$tag')"; 
         $insert_result = mysqli_query($this->server_connection,$insert_query);
         echo "Successful Insert";
      }
      
      function deleteForm($UserId, $DateTime) {
         $check_exists_query = "SELECT * FROM Forms WHERE UserId = '$UserId' AND DateTime = '$DateTime'";
         $check_exists_result = mysqli_query($this->server_connection, $check_exists_query);
         
         if ($check_exists_result->num_rows == 0)
            echo "Form Does Not Exist";
         else {
            $delete_query = "DELETE FROM Forms WHERE UserId = '$UserId' AND DateTime = '$DateTime'";
            $delete_result = mysqli_query($this->server_connection, $delete_query);
         
            $check_query = "SELECT * FROM Forms WHERE UserId = '$UserId' AND DateTime = '$DateTime'";
            $check_result = mysqli_query($this->server_connection, $check_query);
         
            if ($check_result->num_rows == 0)
               echo "Successful Delete";
            else {
               echo "Failed to Delete Form"; 
            }
         }
      }

      function checkFormExists($UserId) {
         $check_query = "SELECT * FROM Forms WHERE UserId = '$UserId'";
         $check_result = mysqli_query($this->server_connection, $check_query);
         
         if ($check_result->num_rows == 0)
            echo "No";
         else
            echo "Yes";
      }

      function updateUserInformation($UserId, $Name, $Email, $PhoneNum, $Password) {
         $update_query = "UPDATE Users SET Name = '$Name', Email = '$Email', PhoneNumber = '$PhoneNum', Password = '$Password' WHERE UserId = '$UserId'";
         $update_result = mysqli_query($this->server_connection, $update_query);
         
      }
   }
?>

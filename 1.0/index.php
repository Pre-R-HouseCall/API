<?php
   require '../include/Config.php';
   require '../include/DbConnect.php';
   require '../include/DbHandler.php';
   require '../libs/Slim/Slim.php';
   
   \Slim\Slim::registerAutoloader();

   $app = new \Slim\Slim();

   $app->response()->header("Content-Type", "application/json");
   
   $app->get('/hello/:name', function ($name) {
      echo "Hello, $name";
   });
   
   /*
    * 'use ($app)' gives callback function a reference to Slim project
    *  '$app'
    */
   $app->post('/post', function() use ($app) {
      $paramValue = $app->request->post('lantern'); 
      print($paramValue);      
   });

   $app->get('/getQueuePosition/:DoctorId/:UserId', function($DoctorId, $UserId) {
      
      //helper class that does CRUD (Create, Read, Update, Delete) operations
      $dbh = new DbHandler();

      $dbh->getQueuePosition($DoctorId, $UserId);
   });

   /*
    * url for this function: http://54.191.98.90/api/1.0/getPatients/$DoctorId
    * This will query the Froms table and return all users in the doctor's queue
    */
   $app->get('/getPatients/:DoctorId', function($DoctorId) {
      $dbh = new DbHandler();
      
      $dbh->getPatients($DoctorId);
   });

   /*
    * url for this function: http://54.191.98.90/api/1.0/getAllDoctors
    * This will query the Doctors table and return all doctors
    */
   $app->get('/getAllDoctors', function() {
      $dbh = new DbHandler();
      
      $dbh->getAllDoctors();
   });

   /*
    * url for this function: http://54.191.98.90/api/1.0/checkUser/$Username/$Password
    * Replace $Username with Username to search and $Password with Password to search
    * This will query the Users table and return a user if exists, 
    * 'No Such Users Found' otherwise.
    */
   $app->get('/checkUser/:Email/:Password', function($Email, $Password) {
      
      $dbh = new DbHandler();
 
      $dbh->checkUser($Email, $Password);
   });

   /*
    * url for this function: http://54.191.98.90/api/1.0/checkDoctor/$Username/$Password
    * Replace $Username with Username to search and $Password with Password to search
    * This will query the Users table and return a user if exists, 
    * 'No Such Users Found' otherwise.
    */
   $app->get('/checkDoctor/:Email/:Password', function($Email, $Password) {
      
      $dbh = new DbHandler();
 
      $dbh->checkDoctor($Email, $Password);
   });

   /*
    * url for this function: 
    * http://54.191.98.90/api/1.0/updateAvailability/$DoctorId/$Availability
    * Replace $DoctorId with DoctorId to search and 
    * Availability with Availability to set
    * This will query the Doctor table and return a user if exists, 
    * 'No Such Users Found' otherwise.
    */
   $app->get('/updateAvailability/:DoctorId/:Availability', function($DoctorId, $Availability) {
      
      $dbh = new DbHandler();
 
      $dbh->updateAvailability($DoctorId, $Availability);
   });

   $app->get('/addUser/:name/:email/:password/:phoneNum/:address/:city/:state/:zip', function($name, $email, $password, $phoneNum, $address, $city, $state, $zip) {

      $dbh = new DbHandler();

      $dbh->addUser($name, $email, $password, $phoneNum, $address, $city, $state, $zip);
   });

   /*
    * url for this function: http://54.191.98.90/api/1.0/addForm/:doctorId/:userId/:name/:email/:number/:symptoms/:lat/
    * :long/:street/:city/:state/:formSeen
    * 
    * This will query the Forms table and add the specified form
    */
   $app->get('/addForm/:doctorId/:userId/:name/:email/:number/:symptoms/:lat/:long/:street/:city/:state/:dateTime/:tag', 
      function($doctorId, $userId, $name, $email, $number, $symptoms, $lat, $long, $street, $city, $state, $dateTime, $tag) {
      
      $dbh = new DbHandler();

      $dbh->addForm($doctorId, $userId, $name, $email, $number, $symptoms, $lat, $long, $street, $city, $state, $dateTime, $tag); 

   });

   /*
    * url for this function: http://54.191.98.90/api/1.0/deleteForm/$UserId/
    * Replace $UserId with UserId to search
    * This will query the Forms table and delete the form
    */
   $app->get('/deleteForm/:UserId/:DateTime', function($UserId, $DateTime) {
      $dbh = new DbHandler();

      $dbh->deleteForm($UserId, $DateTime);
   });

   $app->get('/checkFormExists/:UserId', function($UserId) {
      $dbh = new DbHandler();

      $dbh->checkFormExists($UserId);
   });

   $app->get('/updateUserInformation/:UserId/:Name/:Email/:PhoneNum/:Password', function($UserId, $Name, $Email, $PhoneNum, $Password) {
      $dbh = new DbHandler();

      $dbh->updateUserInformation($UserId, $Name, $Email, $PhoneNum, $Password);
   });
   $app->run();
?>

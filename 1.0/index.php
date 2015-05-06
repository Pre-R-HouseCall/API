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

   /*
    *  url for this function: http://54.191.98.90/api/1.0/getQueuePosition/1
    *  UserId is the parameter, in this example UserId = 1 
    *  the url parameter for "/getQueuePosition/:UserId" is "UserId" and 
    *   should match the function parameter "$UserId" for clarity
    */
   $app->get('/getQueuePosition/:UserId', function($UserId) {
      
      //helper class that does CRUD (Create, Read, Update, Delete) operations
      $dbh = new DbHandler();

      $dbh->getQueuePosition($UserId);
   });

   $app->get('/getQueuePositionNew/:DoctorId/:UserId', function($DoctorId, $UserId) {
      
      //helper class that does CRUD (Create, Read, Update, Delete) operations
      $dbh = new DbHandler();

      $dbh->getQueuePositionNew($DoctorId, $UserId);
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
    * url for this function: http://54.191.98.90/api/1.0/check/$Username/$Password
    * Replace $Username with Username to search and $Password with Password to search
    * This will query the Users table and return a user if exists, 
    * 'No Such Users Found' otherwise.
    */
   $app->get('/check/:Email/:Password', function($Email, $Password) {
      
      $dbh = new DbHandler();
 
      $dbh->check($Email, $Password);
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
   $app->get('/addForm/:doctorId/:userId/:name/:email/:number/:symptoms/:lat/:long/:street/:city/:state/:dateTime', 
      function($doctorId, $userId, $name, $email, $number, $symptoms, $lat, $long, $street, $city, $state, $dateTime) {
      
      $dbh = new DbHandler();

      $dbh->addForm($doctorId, $userId, $name, $email, $number, $symptoms, $lat, $long, $street, $city, $state, $dateTime); 

   });

   /*
    * url for this function: http://54.191.98.90/api/1.0/deleteForm/$UserId/
    * Replace $UserId with UserId to search
    * This will query the Forms table and delete the form
    */
   $app->get('/deleteForm/:UserId', function($UserId) {
      $dbh = new DbHandler();

      $dbh->deleteForm($UserId);
   });

   $app->get('/checkFormExists/:UserId', function($UserId) {
      $dbh = new DbHandler();

      $dbh->checkFormExists($UserId);
   });

   $app->run();
?>

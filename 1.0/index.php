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
   $app->get('/check/:Username/:Password', function($Username, $Password) {
      
      $dbh = new DbHandler();
 
      $dbh->check($Username, $Password);
   });

   $app->run();
?>

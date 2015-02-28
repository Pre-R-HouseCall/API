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

   $app->run();
?>

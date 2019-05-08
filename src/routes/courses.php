<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;


// List All Courses

$app->get('/courses', function (Request $request, Response $response) {

   $db = new Db();


   try{

       $db = $db->connect();


       $courses = $db->query("SELECT * FROM courses")->fetchAll(PDO::FETCH_OBJ);

       //print_r($courses);

       return $response
           ->withStatus(200)
           ->withHeader("Content-Type","application-json")
           ->withJson($courses);

       $db = null;

   }
   catch (PDOException $e){

       return $response->withJson(
           array(
               "error" => array(
                   "text" => $e->getMessage(),
                   "code" => $e->getCode()
               )
           )
       );

   }


});

// Course Detail

$app->get('/course/{id}', function (Request $request, Response $response) {


    $id = $request->getAttribute("id");

    if($id){

        $db = new Db();

        try{

            $db = $db->connect();


            $course = $db->query("SELECT * FROM courses WHERE id = $id")->fetch(PDO::FETCH_OBJ);

            //print_r($courses);

            return $response
                ->withStatus(200)
                ->withHeader("Content-Type","application-json")
                ->withJson($course);

            $db = null;

        }
        catch (PDOException $e){

            return $response->withJson(
                array(
                    "error" => array(
                        "text" => $e->getMessage(),
                        "code" => $e->getCode()
                    )
                )
            );

        }

    }
    else{

        return $response
            ->withStatus(500)
            ->withHeader("Content-Type","application-json")
            ->withJson(array(
                    "error" => array(
                        "text" => "ID Bilgisi eksik"
                    )
                )
            );

    }


});

// Add New Course

$app->post('/course/add', function (Request $request, Response $response) {

    $title      =  $request->getParam("title");
    $couponCode =  $request->getParam("couponCode");
    $price      =  $request->getParam("price");

    $db = new Db();

    try{

        $db = $db->connect();

        $statement = "INSERT INTO courses (title,couponCode,price) VALUES (:title,:couponCode,:price)";

        $prepare = $db->prepare($statement);


        $prepare->bindParam("title",$title);
        $prepare->bindParam("couponCode",$couponCode);
        $prepare->bindParam("price",$price);

        $course = $prepare->execute();

        if($course){

            return $response
                ->withStatus(200)
                ->withHeader("Content-Type","application-json")
                ->withJson(array(
                    "text" => "Kurs başarıyla eklendi."
                ));

        }
        else{

            return $response
                ->withStatus(500)
                ->withHeader("Content-Type","application-json")
                ->withJson(array(
                    "text" => "Ekleme işlemi sırasında bir problem oluştu"
                ));

        }

        $db = null;


    }
    catch (PDOException $e){

        return $response->withJson(
            array(
                "error" => array(
                    "text" => $e->getMessage(),
                    "code" => $e->getCode()
                )
            )
        );

    }


});

// Update Course

$app->put('/course/update/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute("id");


    if($id){

        $title      =  $request->getParam("title");
        $couponCode =  $request->getParam("couponCode");
        $price      =  $request->getParam("price");


        $db = new Db();

        try{

            $db = $db->connect();

            $statement = "UPDATE courses SET title = :title,couponCode = :couponCode,price = :price WHERE id = $id";

            $prepare = $db->prepare($statement);


            $prepare->bindParam("title",$title);
            $prepare->bindParam("couponCode",$couponCode);
            $prepare->bindParam("price",$price);

            $course = $prepare->execute();

            if($course){

                return $response
                    ->withStatus(200)
                    ->withHeader("Content-Type","application-json")
                    ->withJson(array(
                        "text" => "Kurs başarıyla güncellenmiştir."
                    ));

            }
            else{

                return $response
                    ->withStatus(500)
                    ->withHeader("Content-Type","application-json")
                    ->withJson(array(
                        "text" => "Güncellleme işlemi sırasında bir problem oluştu"
                    ));

            }




        }
        catch (PDOException $e){

            return $response->withJson(
                array(
                    "error" => array(
                        "text" => $e->getMessage(),
                        "code" => $e->getCode()
                    )
                )
            );

        }

        $db = null;

    }
    else{

        return $response
            ->withStatus(500)
            ->withHeader("Content-Type","application-json")
            ->withJson(array(
                "error" => array(
                    "text" => "ID Bilgisi eksik"
                )
            )
        );

    }


});


// Delete Course

$app->delete('/course/delete/{id}', function (Request $request, Response $response) {

    $id      =  $request->getAttribute("id");

    $db = new Db();

    try{

        $db = $db->connect();

        $statement = "DELETE FROM courses WHERE id = :id";

        $prepare = $db->prepare($statement);

        $prepare->bindParam("id",$id);

        $course = $prepare->execute();

        if($course){

            return $response
                ->withStatus(200)
                ->withHeader("Content-Type","application-json")
                ->withJson(array(
                    "text" => "Kurs başarıyla silindi."
                ));

        }
        else{

            return $response
                ->withStatus(500)
                ->withHeader("Content-Type","application-json")
                ->withJson(array(
                    "text" => "Silme işlemi sırasında bir problem oluştu"
                ));

        }

    }
    catch (PDOException $e){

        return $response->withJson(
            array(
                "error" => array(
                    "text" => $e->getMessage(),
                    "code" => $e->getCode()
                )
            )
        );

    }

    $db = null;

});
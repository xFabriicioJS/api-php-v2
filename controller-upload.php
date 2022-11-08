<?php 

    require("./config.php");  


     $response = array();
     $upload_dir = '../AppWeb/src/img/fotos_de_tudo/';
     $server_url = 'localhost/api-php-v2/controller-upload.php';
     
     if($_FILES['avatar'])
     {
         $avatar_name = $_FILES["avatar"]["name"];
         $avatar_tmp_name = $_FILES["avatar"]["tmp_name"];
         $error = $_FILES["avatar"]["error"];
     
         if($error > 0){
             $response = array(
                 "status" => "error",
                 "error" => true,
                 "message" => "Error uploading the file!"
             );
         }else 
         {
             $random_name = rand(1000,1000000)."-".$avatar_name;
             $upload_name = $upload_dir.strtolower($random_name);
             $upload_name = preg_replace('/\s+/', '-', $upload_name);
         
             if(move_uploaded_file($avatar_tmp_name , $upload_name)) {
                 $response = array(
                     "status" => "success",
                     "error" => false,
                     "message" => "Arquivo movido com sucesso!",
                     "url" => $server_url."/".$upload_name,
                     "nomeArquivo" => $random_name
                   );
             }else
             {
                 $response = array(
                     "status" => "error",
                     "error" => true,
                     "message" => "Error uploading the file!"
                 );
             }
         }
     }else{
         $response = array(
             "status" => "error",
             "error" => true,
             "message" => "No file was sent!"
         );
     }
     
     echo json_encode($response);



?>
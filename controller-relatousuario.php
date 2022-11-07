<?php

// Esse controller será responsável por enviar um email do cliente para o administrador, no caso, eu mesmo


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

include './PHPMailer/PHPMailer.php';
include './PHPMailer/SMTP.php';

require("./config.php");    

//variável que recebe o conteúdo da requisição do App decodificando-a
$postjson = json_decode(file_get_contents('php://input', true), true);


if($postjson['requisicao'] == 'add'){

    $mail = new PHPMailer();

            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = "true";
            $mail->Port = "587";
            $mail->Username="fabriciocostamonteiro@gmail.com";
            $mail->Password = "dhechamsmevwngph";
            $mail->Subject = "Novo relato de problema ocorrido - Cliente:  ".$postjson['nome_cliente'];
            $mail->setFrom = "fabriciocostamonteiro@gmail.com";
            $mail->Body = "Um novo relato de problema no sistema acabou de ser enviado.
            Dados do problema:
            Nome do cliente : ".$postjson['nome_cliente']."
            Data do envio do relato: ".$postjson['data_relato']."
            Tipo do problema: ".$postjson['tipoProblema']."
            Mensagem do problema: ".$postjson['msg_problema']."
            Email do cliente: ".$postjson['email_cliente']." 
            ";
            $mail->addAddress("fabriciocostamonteiro@gmail.com");

            //Inserindo o sistema de email PHPMailer

            if($mail->Send()){
                $result = json_encode(array('success' => true, 'msg'=>"Feedback enviado com sucesso!"));
            }else {
                $result = json_encode(array('success' => false, 'msg'=>"Falha ao enviar o feedback!"));
            }
            print $result;

            $mail->smtpClose();
}




?>
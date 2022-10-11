<?php 


//Arquivo de configuração para conseguirmos acessar as classes.
require("../config.php");    


//include que pode ser necessário caso utilizemos PDO.
// include_once('conn.php');

//variável que recebe o conteúdo da requisição do App decodificando-a
$postjson = json_decode(file_get_contents('php://input', true), true);


if($postjson['requisicao'] == 'add'){
    
    //lógica para pegar pegar a imagem na requisição
    // if($postjson['avatar'] !== ''){
    //     $avatar_name = $_FILES["avatar"]["name"];
    //     $avatar_tmp_name = $_FILES["avatar"]["tmp_name"];
    
    //     $pasta_img = "./images/". $avatar_name;
    //     move_uploaded_file($avatar_tmp_name, $pasta_img);    
    //     }


    //Pegando a senha do cliente e criptografando-a

    
    
    $chamado = new Chamado(
         $postjson['protocolo'], $postjson['descricao'], $postjson['titulo'], $postjson['id_cliente'], $postjson['data-abertura'], null, $postjson['data_limite'], $postjson['foto_erro'], $postjson['status'], $postjson['prioridade'], $postjson['local_atend']
    );

    $id = $cliente->insert();

    if(isset($id)){
        $result = json_encode(array('success' => true, 'id' => $id));
    }else{
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao inserir o cliente!"));
        echo $id;
    }

    echo $result;
}


// else if($postjson['requisicao'] == 'addImagem'){
//     if($_FILES['avatar']){
//         $avatar_name = $_FILES["avatar"]["name"];
//         $avatar_tmp_name = $_FILES["avatar"]["tmp_name"];
    
//         $pasta_img = "./images/". $avatar_name;
//         move_uploaded_file($avatar_tmp_name, $pasta_img);    
//         }
// }


// Final requisição add

    else if($postjson['requisicao']=='listar'){

        $chamado = new Chamado();

        if($postjson['titulo'] == ''){
            $res = Chamado::getList();
        } else{
            $res = $chamado->search($postjson['titulo']);
        }
    

    for($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id_chamado'=> $res[$i]['id_chamado'],
            'protocolo'=> $res[$i]['protocolo_chamado'],
            'descricao'=> $res[$i]['descri_chamado'],
            'titulo'=> $res[$i]['titulo_chamado'],
            'id_cliente'=> $res[$i]['id_cliente_chamado'],
            'data_abertura'=> $res[$i]['data_abertura_chamado'],
            'data_finalizacao'=> $res[$i]['data_finalizacao_chamado'],
            'data_limite'=> $res[$i]['data_limite_chamado'],
            'foto_erro'=> $res[$i]['foto_erro_chamado'],
            'status'=> $res[$i]['status_chamado'],
            'prioridade'=> $res[$i]['prioridade_chamado'],
            'local_atend'=> $res[$i]['local_atend_chamado']
        );
    }
    if(count($res)){
        $result = json_encode(array('success'=>true, 'result'=>$dados));
    }else{
        $result = json_encode(array('success'=>false, 'result'=>'0'));
    }

    echo ($result);

    }

    // else if($postjson['requisicao']=='editar'){
    //     $query = $pdo->prepare("UPDATE usuarios SET nome=:nome, usuario=:usuario, senha= :senha, senha_original = :senha_original, nivel=:nivel WHERE id = :id");
    //     $query->bindValue(":nome",$postjson['nome']);
    //     $query->bindValue(":usuario",$postjson['usuario']);
    //     $query->bindValue(":senha",$postjson['senha']);
    //     $query->bindValue(":senha_original",$postjson['senha']);
    //     $query->bindValue(":nivel",$postjson['nivel']);
    //     $query->bindValue(":id",$postjson['id']);
    //     $query->execute();
    //     if ($query){
    //         $result = json_encode(array('success'=>true, 'msg'=>"Deu tudo certo com alteração!"));
    //     }else{
    //         $result = json_encode(array('success'=>false,'msg'=>"Dados incorretos! Falha ao atualizar o usuário! (WRH014587)"));
    //     }
    //     echo $result;
    // }

    else if($postjson['requisicao'] == 'excluir'){


        $chamado = new Cliente();
        $chamado->setId($postjson['id_chamado']);

        $res = $cliente->delete();

        if($res){
            $result = json_encode(array('success' => true, 'msg'=>"Exclusão feita com sucesso"));
        }else{
            $result = json_encode(array('success' => false, 'msg'=>"Dados incorretos" )); 
        }
        echo $result;

    }
    //final do excluir
    else if($postjson['requisicao'] == 'findAllByClienteId'){

       $res = Chamado::getAllChamadosByCliente($postjson['id_cliente']);
        
       for($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id_chamado'=> $res[$i]['id_chamado'],
            'protocolo'=> $res[$i]['protocolo_chamado'],
            'descricao'=> $res[$i]['descri_chamado'],
            'titulo'=> $res[$i]['titulo_chamado'],
            'id_cliente'=> $res[$i]['id_cliente_chamado'],
            'data_abertura'=> $res[$i]['data_abertura_chamado'],
            'data_finalizacao'=> $res[$i]['data_finalizacao_chamado'],
            'data_limite'=> $res[$i]['data_limite_chamado'],
            'foto_erro'=> $res[$i]['foto_erro_chamado'],
            'status'=> $res[$i]['status_chamado'],
            'prioridade'=> $res[$i]['prioridade_chamado'],
            'local_atend'=> $res[$i]['local_atend_chamado']
        );
    }
    if(count($res)){
        $result = json_encode(array('success'=>true, 'result'=>$dados));
    }else{
        $result = json_encode(array('success'=>false, 'result'=>'0'));
    }

    echo ($result);
    }
 

    
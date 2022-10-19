<?php 


//Arquivo de configuração para conseguirmos acessar as classes.
require("./config.php");    


//include que pode ser necessário caso utilizemos PDO.
// include_once('conn.php');

//variável que recebe o conteúdo da requisição do App decodificando-a
$postjson = json_decode(file_get_contents('php://input', true), true);


if($postjson['requisicao'] == 'add'){

    
    //Pegando a senha do usuário e criptografando-a

    $senhaCrypt = password_hash($postjson['senha'], PASSWORD_DEFAULT);
    
    
    $user = new Usuario($postjson['nome_usuario'], $postjson['email_usuario'], $postjson['id_nivel_usuario'], $postjson['login_usuario'], $senhaCrypt,$postjson['foto_usuario']);


    

    $id = $user->insert();

    if(isset($id)){
        $result = json_encode(array('success' => true, 'id' => $id));
    }else{
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao inserir!"));
        echo $id;
    }

    echo $result;
}

// Final requisição add

    else if($postjson['requisicao']=='listar'){

        $user = new Usuario();

        if($postjson['nome'] == ''){
            $res = Usuario::getList();
        } else{
            $res = $user->search($postjson['nome']);
        }
    

    for($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id_usuario'=>$res[$i]['id_usuario'],
            'nome_usuario'=>$res[$i]['nome_usuario'],
            'email_usuario'=>$res[$i]['email_usuario'],
            'id_nivel_usuario'=>$res[$i]['id_nivel_usuario'],
            'senha_usuario'=>$res[$i]['senha_usuario']
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


        $user = new Usuario();
        $user->setId($postjson['id_usuario']);

        $res = $user->delete();

        if($res){
            $result = json_encode(array('success' => true, 'msg'=>"deu tudo certo com a exclusão"));
        }else{
            $result = json_encode(array('success' => false, 'msg'=>"Dados incorretos" )); 
        }
        echo $result;

    }
    //final do excluir

    else if($postjson['requisicao'] == 'login'){
        $usuario = new Usuario();

        $usuario->efetuarLogin($postjson['email'], $postjson['senha']);
    
    
        $dados = array(
            'id_usuario' => $usuario->getId(),
            'nome_usuario' => $usuario->getNome(),
            'email_usuario' => $usuario->getEmail(),
            'id_nivel_usuario' => $usuario->getNivel(),
            'login_usuario' => $usuario->getLogin(),
            'foto_usuario' => $usuario->getFotoUsuario(),
            'tipo_usuario_sistema' => 'Usuario'
        );
    
        if ($dados['id_usuario'] > 0) {
            $result = json_encode(array('success' => true, 'result' => $dados));
        } else {
            $result = json_encode(array('success' => false, 'msg' => "Dados incorretos, tente novamente por favor."));
        }
    
        echo $result;

    }

    





?>
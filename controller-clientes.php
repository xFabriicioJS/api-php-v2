<?php 


//Arquivo de configuração para conseguirmos acessar as classes.
require("./config.php");    


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

    $senhaCrypt = password_hash($postjson['senha'], PASSWORD_DEFAULT);
    
    
    $cliente = new Clientes($postjson['nome'], $postjson['cpf'], $postjson['telefone'], $postjson['cnpj'],$postjson['razaoSocial'], $postjson['idTipo'], $postjson['email'], $senhaCrypt);

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

        $cliente = new Clientes();

        if($postjson['nome'] == ''){
            $res = Clientes::getList();
        } else{
            $res = $cliente->search($postjson['nome']);
        }
    

    for($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id_cliente'=>$res[$i]['id_cliente'],
            'nome_cliente'=>$res[$i]['nome_cliente'],
            'cpf_cliente'=>$res[$i]['cpf_cliente'],
            'telefone_cliente'=>$res[$i]['telefone_cliente'],
            'cnpj_cliente'=>$res[$i]['cnpj_cliente'],
            'razaoSocial_cliente'=>$res[$i]['razao_social_cliente'],
            'id_tipo_cliente'=>$res[$i]['id_tipo_cliente'],
            'email_cliente'=>$res[$i]['email_cliente'],
            'senha_cliente'=>$res[$i]['senha_cliente'],
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


        $cliente = new Clientes();
        $cliente->setId($postjson['id_cliente']);

        $res = $cliente->delete();

        if($res){
            $result = json_encode(array('success' => true, 'msg'=>"Exclusão feita com sucesso"));
        }else{
            $result = json_encode(array('success' => false, 'msg'=>"Dados incorretos" )); 
        }
        echo $result;

    }
    //final do excluir

    else if($postjson['requisicao'] == 'login'){
        $cliente = new Clientes();

        $cliente->efetuarLogin($postjson['email'], $postjson['senha']);

        $dados = array(
            'id_cliente'=>$cliente->getId(),
            'nome_cliente'=>$cliente->getNome(),
            'cpf_cliente'=>$cliente->getCpf(),
            'telefone_cliente'=>$cliente->getTelefone(),
            'cnpj_cliente'=>$cliente->getCnpj(),
            'razaoSocial_cliente'=>$cliente->getRazaoSocial(),
            'id_tipo_cliente'=>$cliente->getIdTipo(),
            'email_cliente'=>$cliente->getEmail(),
            'senha_cliente'=>$cliente->getSenha()
        );

        if(count($dados)> 0){
            $result = json_encode(array('success'=>true, 'result'=>$dados));
        }
        else{
            $result = json_encode(array('success' => false, 'msg'=> "Dados incorretos, tente novamente por favor."));
        }

        echo $result;

    }

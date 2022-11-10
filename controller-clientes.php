<?php


//Arquivo de configuração para conseguirmos acessar as classes.
require("./config.php");


//include que pode ser necessário caso utilizemos PDO.
// include_once('conn.php');

//variável que recebe o conteúdo da requisição do App decodificando-a
$postjson = json_decode(file_get_contents('php://input', true), true);


if ($postjson['requisicao'] == 'add') {



    //Pegando a senha do cliente e criptografando-a

    $senhaCrypt = password_hash($postjson['senha'], PASSWORD_DEFAULT);


    $cliente = new Clientes($postjson['nome'], $postjson['cpf'], $postjson['telefone'], $postjson['cnpj'], $postjson['razaoSocial'], $postjson['idTipo'], $postjson['email'], $senhaCrypt);

    $id = $cliente->insert();

    if (isset($id)) {
        $result = json_encode(array('success' => true, 'id' => $id));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao inserir o cliente!"));
        echo $id;
    }

    echo $result;
}





// Final requisição add

//Requisição excluisiva para troca de senha
else if($postjson['requisicao'] == 'alterarSenha'){
    $senhaCrypt = password_hash($postjson['senhaNova'], PASSWORD_DEFAULT);

    $cliente = new Clientes();
    $res = $cliente->updateSenha($postjson['senhaAntiga'], $postjson['id_cliente'], $senhaCrypt);

    if($res == 'dados corretos'){
        $result = json_encode(array('success' => true, 'msg' => "Senha alterada com sucesso!"));
    }else{
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao alterar a senha!"));
    }

    echo $result;

}

else if ($postjson['requisicao'] == 'listar') {

    $cliente = new Clientes();

    if ($postjson['nome'] == '') {
        $res = Clientes::getList();
    } else {
        $res = $cliente->search($postjson['nome']);
    }


    for ($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id_cliente' => $res[$i]['id_cliente'],
            'nome_cliente' => $res[$i]['nome_cliente'],
            'cpf_cliente' => $res[$i]['cpf_cliente'],
            'telefone_cliente' => $res[$i]['telefone_cliente'],
            'cnpj_cliente' => $res[$i]['cnpj_cliente'],
            'razaoSocial_cliente' => $res[$i]['razao_social_cliente'],
            'id_tipo_cliente' => $res[$i]['id_tipo_cliente'],
            'email_cliente' => $res[$i]['email_cliente'],
            'senha_cliente' => $res[$i]['senha_cliente'],
            'foto_cliente' => $res[$i]['foto_cliente'],
        );
    }
    if (count($res)) {
        $result = json_encode(array('success' => true, 'result' => $dados));
    } else {
        $result = json_encode(array('success' => false, 'result' => '0'));
    }

    echo ($result);
}

else if($postjson['requisicao'] == 'editar'){
        
    $cliente = new Clientes();
    $cliente->setId($postjson['id']);
    $cliente->setNome($postjson['nome']);
    $cliente->setCpf($postjson['cpf']);
    $cliente->setTelefone($postjson['telefone']);
    $cliente->setCnpj($postjson['cnpj']);
    $cliente->setRazaoSocial($postjson['razaoSocial']);
    $cliente->setIdTipo($postjson['idTipo']);
    $cliente->setEmail($postjson['email']);



    $res = $cliente->update();

    if($res){
        $result = json_encode(array('success' => true, 'msg'=>"Cliente editado com sucesso!"));

    }else{
        $result = json_encode(array('success' => false, 'msg'=>"Não foi possível editar o cliente"));
    }

    echo $result;
}

else if ($postjson['requisicao'] == 'excluir') {


    $cliente = new Clientes();
    $cliente->setId($postjson['id_cliente']);

    $res = $cliente->delete();

    if ($res) {
        $result = json_encode(array('success' => true, 'msg' => "Exclusão feita com sucesso"));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Dados incorretos"));
    }
    echo $result;
}
//final do excluir
else if($postjson['requisicao'] == 'findById'){
    $cliente = new Clientes();

    $res = $cliente->loadById($postjson['id_cliente']);

    for ($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id_cliente' => $res[$i]['id_cliente'],
            'nome_cliente' => $res[$i]['nome_cliente'],
            'cpf_cliente' => $res[$i]['cpf_cliente'],
            'telefone_cliente' => $res[$i]['telefone_cliente'],
            'cnpj_cliente' => $res[$i]['cnpj_cliente'],
            'razaoSocial_cliente' => $res[$i]['razao_social_cliente'],
            'id_tipo_cliente' => $res[$i]['id_tipo_cliente'],
            'email_cliente' => $res[$i]['email_cliente'],
            'senha_cliente' => $res[$i]['senha_cliente'],
            'foto_cliente' => $res[$i]['foto_cliente'],
            'num_endereco' => $res[$i]['num_endereco'],
            'cep_endereco' => $res[$i]['cep_endereco'],
            'complemento_endereco' => $res[$i]['complemento_endereco'],
            'logradouro_endereco' => $res[$i]['logradouro_endereco'],
            'cidade_endereco' => $res[$i]['cidade_endereco'],
            'id_contrato_cliente' => $res[$i]['id_contrato_cliente'],
        );

        if(count($res)){
            $result = json_encode(array('success' => true, 'result' => $dados));
        }else{
            $result = json_encode(array('success' => false, 'result' => '0'));
        }

        echo $result;
    }
    

}



else if ($postjson['requisicao'] == 'login') {
    $cliente = new Clientes();

  
  

    $cliente->efetuarLogin($postjson['email'], $postjson['senha']);


    $dados = array(
        'id_cliente' => $cliente->getId(),
        'nome_cliente' => $cliente->getNome(),
        'cpf_cliente' => $cliente->getCpf(),
        'telefone_cliente' => $cliente->getTelefone(),
        'cnpj_cliente' => $cliente->getCnpj(),
        'razaoSocial_cliente' => $cliente->getRazaoSocial(),
        'id_tipo_cliente' => $cliente->getIdTipo(),
        'email_cliente' => $cliente->getEmail(),
        'foto_cliente' => $cliente->getFoto(),
        'tipo_usuario_sistema' => 'Cliente'
    );

    if ($dados['id_cliente'] > 0) {

        $result = json_encode(array('success' => true, 'result' => $dados));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Dados incorretos, tente novamente por favor."));
    }

    echo $result;
}

else if($postjson['requisicao'] == 'atualizaEmail'){


    

    $res = Clientes::atualizaEmail($postjson['id_cliente'],$postjson['email_novo'], $postjson['senha_atual']);

    if($res == 'dados corretos'){
        $result = json_encode(array('success' => true, 'msg' => "Email alterado com sucesso!"));
    }
    else if($res == 'dados incorretos'){
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao alterar o email!"));
    }else if($res == 'Esse email já pertence a outro usuário'){
        $result = json_encode(array('success' => false, 'msg' => "Esse email já pertence a outro usuário"));
    }else{
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu um erro!"));
    }

    echo $result;
}

else if($postjson['requisicao'] == 'atualizaTelefone'){

    $res = Clientes::atualizaTelefone($postjson['id_cliente'], $postjson['telefone_cliente']);

    if($res){
        $result = json_encode(array('success' => true, 'msg'=>"Telefone atualizado com sucesso!"));
    }else{
        $result = json_encode(array('success' => false, 'msg'=>"Não foi possível atualizar o telefone"));
    }
    print $result;
}



else if($postjson['requisicao'] == 'atualizaFoto'){
    
    $res = Clientes::atualizaFoto($postjson['id_cliente'], $postjson['foto_cliente']);

    if($res == 'foto atualizada'){
        $result = json_encode(array('success' => true, 'msg'=>"Foto atualizada com sucesso!"));
    }else{
        $result = json_encode(array('success' => false, 'msg'=>"Não foi possível atualizar a foto"));
    }

    echo $result;

}

else if($postjson['requisicao'] == 'recuperarPlano'){

    $res = Clientes::recuperaPlanoByIdCliente($postjson['id_cliente']);


    if($res == 'erro'){
        $result = json_encode(array('success' => false, 'msg'=>"Falha ao recuperar o plano"));
        echo $result;

        //Cortará a função para não executar o resto do código
        return false;
    }

    if(count($res)){
        $result = json_encode(array('success' => true, 'result' => $res[0]));
    }
    
    echo ($result);
}

else if($postjson['requisicao'] == 'ativarPlano'){

    $res = Clientes::ativarPlano($postjson['id_cliente'], $postjson['id_contrato']);

    if($res == 'dados atualizados'){
        $result = json_encode(array('success' => true, 'msg'=>"Plano ativado com sucesso!"));
    }else{
        $result = json_encode(array('success' => false, 'msg'=>"Não foi possível ativar o plano"));
    }

    print $result;

}


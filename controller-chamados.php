<?php


//Arquivo de configuração para conseguirmos acessar as classes.
require("./config.php");


//include que pode ser necessário caso utilizemos PDO.
// include_once('conn.php');

//variável que recebe o conteúdo da requisição do App decodificando-a
$postjson = json_decode(file_get_contents('php://input', true), true);


if ($postjson['requisicao'] == 'add') {
    $chamado = new Chamado(
        $postjson['descricao'],
        $postjson['titulo'],
        $postjson['id_cliente'],
        $postjson['data_abertura'],
        null,
        $postjson['data_limite'],
        $postjson['foto_erro_chamado'],
        $postjson['status'],
        $postjson['prioridade'],
        $postjson['local_atend']
    );

    $id = $chamado->insert();

    if (isset($id)) {
        $result = json_encode(array('success' => true, 'id' => $id));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao inserir o chamado!"));
        echo $id;
    }

    echo $result;
}

// Final requisição add

else if ($postjson['requisicao'] == 'listar') {

    $chamado = new Chamado();

    if ($postjson['titulo'] == '' && $postjson['protocolo'] == '' && $postjson['descricao'] == '' ) {
        $res = Chamado::getList();
    } else if($postjson['protocolo'] != ''){
        $res = Chamado::search('protocolo_chamado', $postjson['protocolo']);
    } else if($postjson['descricao'] != ''){
        $res = Chamado::search('descri_chamado', $postjson['descricao']);
    } else if($postjson['titulo'] != ''){
        $res = Chamado::search('titulo_chamado', $postjson['titulo']);
    }

    for ($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id_chamado' => $res[$i]['id_chamado'],
            'protocolo' => $res[$i]['protocolo_chamado'],
            'descricao' => $res[$i]['descri_chamado'],
            'titulo' => $res[$i]['titulo_chamado'],
            'id_cliente' => $res[$i]['id_cliente_chamado'],
            'data_abertura' => $res[$i]['data_abertura_chamado'],
            'data_finalizacao' => $res[$i]['data_finalizacao_chamado'],
            'data_limite' => $res[$i]['data_limite_chamado'],
            'foto_erro' => $res[$i]['foto_erro_chamado'],
            'status' => $res[$i]['status_chamado'],
            'prioridade' => $res[$i]['prioridade_chamado'],
            'local_atend' => $res[$i]['local_atend_chamado'],
            'nome_cliente' => $res[$i]['nome_cliente'],
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

// else if ($postjson['requisicao'] == 'excluir') {


//     $chamado = new Cliente();
//     $chamado->setId($postjson['id_chamado']);

//     $res = $cliente->delete();

//     if ($res) {
//         $result = json_encode(array('success' => true, 'msg' => "Exclusão feita com sucesso"));
//     } else {
//         $result = json_encode(array('success' => false, 'msg' => "Dados incorretos"));
//     }
//     echo $result;
// }
//final do excluir
else if ($postjson['requisicao'] == 'listarTodosPorCliente') {

   
    $chamado = new Chamado();

    if ($postjson['titulo'] == '' && $postjson['protocolo'] == '' && $postjson['descricao'] == '' ) {
        $res = Chamado::getAllChamadosByCliente($postjson['id_cliente']);
    } else if($postjson['protocolo'] != ''){
        $res = Chamado::searchCliente('protocolo_chamado', $postjson['protocolo'], $postjson['id_cliente']);
    } else if($postjson['descricao'] != ''){
        $res = Chamado::searchCliente('descri_chamado', $postjson['descricao'], $postjson['id_cliente']);
    } else if($postjson['titulo'] != ''){
        $res = Chamado::searchCliente('titulo_chamado', $postjson['titulo'], $postjson['id_cliente']);
    }

    for ($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id_chamado' => $res[$i]['id_chamado'],
            'protocolo' => $res[$i]['protocolo_chamado'],
            'descricao' => $res[$i]['descri_chamado'],
            'titulo' => $res[$i]['titulo_chamado'],
            'id_cliente' => $res[$i]['id_cliente_chamado'],
            'data_abertura' => $res[$i]['data_abertura_chamado'],
            'data_finalizacao' => $res[$i]['data_finalizacao_chamado'],
            'data_limite' => $res[$i]['data_limite_chamado'],
            'foto_erro' => $res[$i]['foto_erro_chamado'],
            'status' => $res[$i]['status_chamado'],
            'prioridade' => $res[$i]['prioridade_chamado'],
            'local_atend' => $res[$i]['local_atend_chamado'],
            'nome_cliente' => $res[$i]['nome_cliente'],
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

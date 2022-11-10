<?php
//Arquivo de configuração para conseguirmos acessar as classes.
require("./config.php");


//include que pode ser necessário caso utilizemos PDO.
// include_once('conn.php');

//variável que recebe o conteúdo da requisição do App decodificando-a
$postjson = json_decode(file_get_contents('php://input', true), true);


if ($postjson['requisicao'] == 'add') {

    $descarte = new Descarte($postjson['descricao'], $postjson['nome_hard'], $postjson['id_cliente'], $postjson['data_abertura'],$postjson['prazo'], $postjson['foto'], $postjson['status']);

    $id = $descarte->insert();

    if (isset($id)) {
        $result = json_encode(array('success' => true, 'id' => $id));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao inserir o descarte!"));
        echo $id;
    }

    echo $result;
}

// Final requisição add

else if ($postjson['requisicao'] == 'listar') {

    $res = Descarte::findAll();

    for ($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id_descarte' => $res[$i]['id_descarte'],
            'protocolo' => $res[$i]['protocolo_descarte'],
            'descricao' => $res[$i]['descri_descarte'],
            'nome_hardware' => $res[$i]['nome_hard_chamado'],
            'id_cliente' => $res[$i]['id_cliente_descarte'],
            'data_abertura' => $res[$i]['data_abertura_descarte'],
            'data_retirada' => $res[$i]['data_retirada_descarte'],
            'prazo' => $res[$i]['prazo_retirada_descarte'],
            'foto_hardware' => $res[$i]['foto_hard_descarte'],
            'status'=> $res[$i]['status_descarte']
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

//Cancelando o chamado pela visão do cliente
else if($postjson['requisicao'] == 'cancelar'){

    $res = Descarte::cancelarDescarte($postjson['id_descarte']);

    if($res == 'Descarte cancelado'){
        $result = json_encode(array('success' => true, 'result' => $res));
    }
    else{
        $result = json_encode(array('success' => false, 'result' => 'Ocorreu uma falha.'));
    }
    print $result;

}



else if ($postjson['requisicao'] == 'listarTodosPorCliente') {

    $res = Descarte::findAllDescartesByIdCliente($postjson['id_cliente']);

    for ($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id_descarte' => $res[$i]['id_descarte'],
            'protocolo' => $res[$i]['protocolo_descarte'],
            'descricao' => $res[$i]['descri_descarte'],
            'nome_hardware' => $res[$i]['nome_hard_chamado'],
            'id_cliente' => $res[$i]['id_cliente_descarte'],
            'data_abertura' => $res[$i]['data_abertura_descarte'],
            'data_retirada' => $res[$i]['data_retirada_descarte'],
            'prazo' => $res[$i]['prazo_retirada_descarte'],
            'foto_hardware' => $res[$i]['foto_hard_descarte'],
            'status'=> $res[$i]['status_descarte']
        );
    }
    if (count($res)) {
        $result = json_encode(array('success' => true, 'result' => $dados));
    } else {
        $result = json_encode(array('success' => false, 'result' => '0'));
    }

    echo ($result);
} else if($postjson['requisicao'] == "alterar"){
    //controlador responsável por alterar o status do descarte
    $descarte = new Descarte();
    $descarte->setIdDescarte($postjson['id_descarte']);
    $descarte->setStatus($postjson['status']);
    $descarte->setDataRetirada($postjson['data_retirada']);
    $res = $descarte->update();

    if($res){
        $result = json_encode(array('success'=>true, 'msg'=>"Status alterado com sucesso!"));
    }else{
        $result = json_encode(array('success'=>false, 'msg'=>"Falha ao alterar o status!"));
    }

    echo($result);

}

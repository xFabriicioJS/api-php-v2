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

//Update status chamado, vamos atualizar o status do chamado e também vamos adicionar uma nova linha na tabela de histórico de chamado (hist_atend)
else if($postjson['requisicao'] == 'updateStatus'){

    if($postjson['data_finalizacao'] !== null || $postjson['data_finalizacao'] !== ''){
       $res = Chamado::updateStatus($postjson['id_chamado'], $postjson['status'], $postjson['id_usuario'], $postjson['comentario_hist'], $postjson['data_hist'], $postjson['data_finalizacao']);
    }else{
        $res = Chamado::updateStatus($postjson['id_chamado'], $postjson['status'], $postjson['id_usuario'], $postjson['comentario_hist'], $postjson['data_hist']);
    }

    if($res == 'Chamado atualizado.'){
        $result = json_encode(array('success' => true, 'result' => $res));
    }
    else if($res == 'Chamado atualizado e finalizado.'){
        $result = json_encode(array('success' => true, 'result' => $res));
    }
    else{
        $result = json_encode(array('success' => false, 'result' => 'Ocorreu uma falha.'));
    }

    print($result);
}

else if($postjson['requisicao'] == 'requestAllHistoricos'){

    $res = Chamado::requestAllHistoricos($postjson['id_chamado']);

    if ($res != null) {

        for ($i = 0; $i < count($res); $i++) {

            $dados[][] = array(
                'id_hist_atend' => $res[$i]['id_hist_atend'],
                'id_chamado' => $res[$i]['id_chamado_hist_atend'],
                'id_usuario' => $res[$i]['id_usuario_hist_atend'],
                'comentario' => $res[$i]['comentario_hist'],
                'data_hist' => $res[$i]['data_historico_chamado'],
            );
        }

        $result = json_encode(array('success' => true, 'result' => $dados));
    } else {
        $result = json_encode(array('success' => false, 'result' => '0'));
    }

    print $result;
}

//Cancelando o chamado pela visão do cliente
else if($postjson['requisicao'] == 'cancelar'){

    $res = Chamado::cancelar($postjson['id_chamado']);

    if($res == 'Chamado cancelado'){
        $result = json_encode(array('success' => true, 'result' => $res));
    }
    else{
        $result = json_encode(array('success' => false, 'result' => 'Ocorreu uma falha.'));
    }
    print $result;

}

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

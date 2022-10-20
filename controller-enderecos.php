<?php 
    //Controlador da entidade Endereco

    require("./config.php");    


//include que pode ser necessário caso utilizemos PDO.
// include_once('conn.php');

//variável que recebe o conteúdo da requisição do App decodificando-a
$postjson = json_decode(file_get_contents('php://input', true), true);


if($postjson['requisicao'] == 'add'){


    // $id_cliente_endereco="", $cep="", $logradouro_endereco="", $num_endereco="", $complemento_endereco="", $bairro_endereco="", $cidade_endereco="", $estado_endereco=""

    $endereco = new Endereco($postjson['id_cliente_endereco'], $postjson['cep'], $postjson['logradouro_endereco'], $postjson['num_endereco'], $postjson['complemento_endereco'], $postjson['bairro_endereco'], $postjson['cidade_endereco'], $postjson['estado_endereco']);
    
    $id = $endereco->insert();

    if(isset($id)){
        $result = json_encode(array('success' => true, 'id' => $id));
    }else{
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao inserir!"));
        echo $id;
    }

    echo $result;
}// Final requisição add

    else if($postjson['requisicao'] == 'listarPorCliente'){

    
    $res = Endereco::getEnderecoByIdCliente($postjson['id_cliente_endereco']);
    

    for($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id_endereco'=>$res[$i]['id_endereco'],
            'id_cliente_endereco'=>$res[$i]['id_cliente_endereco'],
            'cep_endereco'=>$res[$i]['cep_endereco'],
            'logradouro_endereco'=>$res[$i]['logradouro_endereco'],
            'num_endereco'=>$res[$i]['num_endereco'],
            'complemento_endereco'=>$res[$i]['complemento_endereco'],
            'bairro_endereco'=>$res[$i]['bairro_endereco'],
            'cidade_endereco'=>$res[$i]['cidade_endereco'],
            'estado_endereco'=>$res[$i]['estado_endereco']
        );
    }
    if(count($res)){
        $result = json_encode(array('success'=>true, 'result'=>$dados));
    }else{
        $result = json_encode(array('success'=>false, 'result'=>'Nenhum endereço encontrado!'));
    }

    echo ($result);

    }//Final do listar

    
    else if($postjson['requisicao'] == 'editar'){
        
        $endereco = new Endereco();
        $endereco->setIdEndereco($postjson['id_endereco']);
        $endereco->setCep($postjson['cep_endereco']);
        $endereco->setLogradouroEndereco($postjson['logradouro_endereco']);
        $endereco->setNumEndereco($postjson['num_endereco']);
        $endereco->setComplementoEndereco($postjson['complemento_endereco']);
        $endereco->setBairroEndereco($postjson['bairro_endereco']);
        $endereco->setCidadeEndereco($postjson['cidade_endereco']);
        $endereco->setEstadoEndereco($postjson['estado_endereco']);

        $res = $endereco->update();

        if($res){
            $result = json_encode(array('success' => true, 'msg'=>"Endereço editado com sucesso!"));

        }else{
            $result = json_encode(array('success' => false, 'msg'=>"Não foi possível editar o endereço"));
        }

        echo $result;

    }


 

?>
<?php 
    //Controlador da entidade Nível

    require("./config.php");    


//include que pode ser necessário caso utilizemos PDO.
// include_once('conn.php');

//variável que recebe o conteúdo da requisição do App decodificando-a
$postjson = json_decode(file_get_contents('php://input', true), true);


if($postjson['requisicao'] == 'add'){

    $nivel = new Nivel($postjson['nome']);
    
    $id = $nivel->insert();

    if(isset($id)){
        $result = json_encode(array('success' => true, 'id' => $id));
    }else{
        $result = json_encode(array('success' => false, 'msg' => "Ocorreu uma falha ao inserir!"));
        echo $id;
    }

    echo $result;
}// Final requisição add

    else if($postjson['requisicao'] == 'listar'){

        $res = Nivel::findAll();
    

    for($i = 0; $i < count($res); $i++) {

        $dados[][] = array(
            'id_nivel'=>$res[$i]['id_nivel'],
            'nome_nivel'=>$res[$i]['nome_nivel']
        );
    }
    if(count($res)){
        $result = json_encode(array('success'=>true, 'result'=>$dados));
    }else{
        $result = json_encode(array('success'=>false, 'result'=>'Nenhum nível encontrado!'));
    }

    echo ($result);

    }//Final do listar

    else if($postjson['requisicao'] == 'excluir'){

        $res = Nivel::deleteById($postjson['id_nivel']);


        if($res){
            $result = json_encode(array('success' => true, 'msg'=>"Nivel excluido com sucesso!"));
        }else{
            $result = json_encode(array('success' => false, 'msg'=>$res)); 
        }
        echo $result;

    }//final do excluir
    else if($postjson['requisicao'] == 'editar'){
        $res = Nivel::updateNomeById($postjson['id_nivel'], $postjson['nome_nivel']);
    
        if($res){
            $result = json_encode(array('success' => true, 'msg'=>"Nivel editado com sucesso!"));

        }else{
            $result = json_encode(array('success' => false, 'msg'=>"Não foi possível editar o nivel"));
        }

        echo $result;

    }


 

?>
<?php 

    class Nivel{
        private $id;
        private $nome;


    //métodos getter e setters
    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getNome(){
        return $this->nome;    
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    //método construtor
    public function __construct($nome=""){   
        $this->nome = $nome;
    }

    public function setData($data){
        $this->setId($data['id']);
        $this->setNome($data['nome']);
    }


    //Função para recuperar nivel por id
    public function getNivelById($_id){
        $sql = new Sql();

        $result = $sql->select("SELECT * FROM tbnivel WHERE id_nivel = :ID", array(
            ":ID"=>$_id
        ));

        if(count($result) > 0){
            $this->setData($result[0]);
        }
    }

    //Função para carregar todos os níveis

    public static function findAll(){

        $sql = new Sql();

        $result = $sql->select("SELECT * FROM tbnivel");

        return $result;
    }

    //Função para inserção de nível (Precisamos instanciar o nivel)
    public function insert(){
        $sql = new Sql();

        $result = $sql->select("CALL sp_nivel_insert(:NOME)", array(
            ":NOME" => $this->getNome()
        ));

        if(count($result) > 0){
            $this->setId($result[0]['id_nivel']);
        }

        return $this->getId();
    }

    //Função para deleção do nível
    public static function deleteById($_id) : bool{
        $sql = new Sql();

         $res = $sql->querySql("DELETE FROM tbnivel WHERE id_nivel = :ID", array(
            ":ID"=>$_id
        ));

        if($res){
            return true;
        }else{
            return false;
        }

    }

    public static function updateNomeById($_id, $_nome) : bool{
        $sql = new Sql();

        $res = $sql->querySql("UPDATE tbnivel SET nome_nivel = :NOME WHERE id_nivel = :ID", array(
            ":NOME"=>$_nome,
            ":ID"=>$_id
        ));

        if($res){
            return true;
        }else{
            return false;
        }

    }






}




?>
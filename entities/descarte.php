<?php 

class Descarte{
    private $id_descarte;
    private $protocolo;
    private $descricao;
    private $nome_hard;
    private $id_cliente;
    private $data_abertura;
    private $data_retirada;
    private $prazo;
    private $foto;
    private $status;


    //métodos getter e setters
    public function getIdDescarte(){
        return $this->id_descarte;
    }
    public function setIdDescarte($_id_descarte){
        $this->id_descarte = $_id_descarte;
    }
    public function getProtocolo(){
        return $this->protocolo;
    }
    public function setProtocolo($_protocolo){
        $this->protocolo = $_protocolo;
    }
    public function getDescricao(){
        return $this->descricao;
    }
    public function setDescricao($_descricao){
        $this->descricao = $_descricao;
    }
    public function getNomeHard(){
        return $this->nome_hard;
    }
    public function setNomeHard($_nome_hard){
        $this->nome_hard = $_nome_hard;
    }
    public function getIdCliente(){
        return $this->id_cliente;
    }
    public function setIdCliente($_id_cliente){
        $this->id_cliente = $_id_cliente;
    }
    public function getDataAbertura(){
        return $this->data_abertura;
    }
    public function setDataAbertura($_data_abertura){
        $this->data_abertura = $_data_abertura;
    }
    public function getDataRetirada(){
        return $this->data_retirada;
    }
    public function setDataRetirada($_data_retirada){
        $this->data_retirada = $_data_retirada;
    }
    public function getPrazo(){
        return $this->prazo;
    }
    public function setPrazo($_prazo){
        $this->prazo = $_prazo;
    }
    public function getFoto(){
        return $this->foto;
    }
    public function setFoto($_foto){
        $this->foto = $_foto;
    }
    public function getStatus(){
        return $this->status;
    }
    public function setStatus($_status){
        $this->status = $_status;
    }


    // método construtor

    public function __construct($_descricao="", $_nome_hard="", $_id_cliente="", $_data_abertura="",$_prazo="", $_foto="", $_status=""){
        $this->descricao = $_descricao;
        $this->nome_hard = $_nome_hard;
        $this->id_cliente = $_id_cliente;
        $this->data_abertura = $_data_abertura;
        $this->data_retirada = 'null';
        $this->prazo = $_prazo;
        $this->foto = $_foto;
        $this->status = $_status;
    }
   
    public function setData($data){
        $this->setProtocolo($data['protocolo']);
        $this->setDescricao($data['descricao']);
        $this->setNomeHard($data['nome_hard']);
        $this->setIdCliente($data['id_cliente']);
        $this->setDataAbertura($data['data_abertura']);
        $this->setDataRetirada($data['data_retirada']);
        $this->setPrazo($data['prazo']);
        $this->setFoto($data['foto']);
        $this->setStatus($data['status']);
    }

    //métodos para os controladores

    public function insert(){

        $protocoloUnico = uniqid();

        $sql = new Sql();

        $results = $sql->select("CALL sp_descarte_insert(:PROT, :DESC, :NOME, :IDCLI, :DATAABER, :PRAZO, :FOTO_HARD, :STATUS)", array(
            ":PROT"=>$protocoloUnico,
            ":DESC"=>$this->getDescricao(),
            ":NOME"=>$this->getNomeHard(),
            ":IDCLI"=>$this->getIdCliente(),
            ":DATAABER"=>$this->getDataAbertura(),
            ":PRAZO"=>$this->getPrazo(),
            ":FOTO_HARD"=>$this->getFoto(),
            ":STATUS"=>$this->getStatus()
        ));
        if(count($results) > 0){
            $this->setIdDescarte($results[0]['id_descarte']);
        }
        //retornarará o id do chamado instanciado para o controlador
        return $this->getIdDescarte();

    }

    //Função para cancelar o descarte
    public static function cancelarDescarte($_id_descarte){
        $sql = new Sql();
        $sql->select("UPDATE tbdescarte SET status_descarte = 'Cancelado' WHERE id_descarte = :ID", array(
            ":ID"=>$_id_descarte
        ));
        return 'Descarte cancelado';
        
    }

    //função responsável por listar os descartes de TODOS os clientes

    public static function findAll(){
        $sql = new Sql();

        return $sql->select("SELECT * FROM tbdescarte ORDER BY id_descarte DESC");
    }

    // função responsável por listar todos os descartes de um único cliente

    public static function findAllDescartesByIdCliente($_id){
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tbdescarte WHERE id_cliente_descarte = :ID", array(
            ":ID"=>$_id
        ));

        return $results;

    }

    //função responsável por listar um descarte específico
    public function loadById($_id){
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_descarte WHERE id_descarte = :ID", array(
            ":ID"=>$_id
        ));
    }

    //função responsável por atualizar o status do descarte
    public function update() : bool{
        $sql = new Sql();

        
        $res = $sql->querySql("UPDATE tbdescarte SET status_descarte = :STATUSDESCARTE, data_retirada_descarte = :DATARETIRADA WHERE id_descarte = :ID", array(
            ":STATUSDESCARTE"=>$this->getStatus(),
            ":ID"=>$this->getIdDescarte(),
            ":DATARETIRADA"=>$this->getDataRetirada()
        ));
        
        
        if($res){
            return true;
        }else{
            return false;
        }

    }

    

}

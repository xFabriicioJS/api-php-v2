<?php 
class Endereco{
    private $id_endereco;
    //Foreign key (Chave estrangeira) do cliente
    private $id_cliente_endereco;

    private $cep;
    private $logradouro_endereco;
    private $num_endereco;
    private $complemento_endereco;
    private $bairro_endereco;
    private $cidade_endereco;
    private $estado_endereco;


    //getters e setters
    public function getIdEndereco(){
        return $this->id_endereco;
    }
    public function setIdEndereco($id_endereco){
        $this->id_endereco = $id_endereco;
    }
    public function getIdClienteEndereco(){
        return $this->id_cliente_endereco;
    }
    public function setIdClienteEndereco($id_cliente_endereco){
        $this->id_cliente_endereco = $id_cliente_endereco;
    }
    public function getCep(){
        return $this->cep;
    }
    public function setCep($cep){
        $this->cep = $cep;
    }
    public function getLogradouroEndereco(){
        return $this->logradouro_endereco;
    }
    public function setLogradouroEndereco($logradouro_endereco){
        $this->logradouro_endereco = $logradouro_endereco;
    }
    public function getNumEndereco(){
        return $this->num_endereco;
    }
    public function setNumEndereco($num_endereco){
        $this->num_endereco = $num_endereco;
    }
    public function getComplementoEndereco(){
        return $this->complemento_endereco;
    }
    public function setComplementoEndereco($complemento_endereco){
        $this->complemento_endereco = $complemento_endereco;
    }
    public function getBairroEndereco(){
        return $this->bairro_endereco;
    }
    public function setBairroEndereco($bairro_endereco){
        $this->bairro_endereco = $bairro_endereco;
    }
    public function getCidadeEndereco(){
        return $this->cidade_endereco;
    }
    public function setCidadeEndereco($cidade_endereco){
        $this->cidade_endereco = $cidade_endereco;
    }
    public function getEstadoEndereco(){
        return $this->estado_endereco;
    }
    public function setEstadoEndereco($estado_endereco){
        $this->estado_endereco = $estado_endereco;
    }

    //método construtor
    public function __construct($id_cliente_endereco="", $cep="", $logradouro_endereco="", $num_endereco="", $complemento_endereco="", $bairro_endereco="", $cidade_endereco="", $estado_endereco=""){
        $this->id_cliente_endereco = $id_cliente_endereco;
        $this->cep = $cep;
        $this->logradouro_endereco = $logradouro_endereco;
        $this->num_endereco = $num_endereco;
        $this->complemento_endereco = $complemento_endereco;
        $this->bairro_endereco = $bairro_endereco;
        $this->cidade_endereco = $cidade_endereco;
        $this->estado_endereco = $estado_endereco;
    }

    public function setData($data){
        $this->setIdClienteEndereco($data['id_cliente_endereco']);
        $this->setCep($data['cep']);
        $this->setLogradouroEndereco($data['logradouro_endereco']);
        $this->setNumEndereco($data['num_endereco']);
        $this->setComplementoEndereco($data['complemento_endereco']);
        $this->setBairroEndereco($data['bairro_endereco']);
        $this->setCidadeEndereco($data['cidade_endereco']);
        $this->setEstadoEndereco($data['estado_endereco']);
    }

    public function insert(){
        $sql = new Sql();

        $result = $sql->select("CALL sp_endereco_insert(:ID_CLIENTE_ENDERECO, :CEP, :LOGRADOURO_ENDERECO, :NUM_ENDERECO, :COMPLEMENTO_ENDERECO, :BAIRRO_ENDERECO, :CIDADE_ENDERECO, :ESTADO_ENDERECO)", array(
            ":ID_CLIENTE_ENDERECO"=>$this->getIdClienteEndereco(),
            ":CEP"=>$this->getCep(),
            ":LOGRADOURO_ENDERECO"=>$this->getLogradouroEndereco(),
            ":NUM_ENDERECO"=>$this->getNumEndereco(),
            ":COMPLEMENTO_ENDERECO"=>$this->getComplementoEndereco(),
            ":BAIRRO_ENDERECO"=>$this->getBairroEndereco(),
            ":CIDADE_ENDERECO"=>$this->getCidadeEndereco(),
            ":ESTADO_ENDERECO"=>$this->getEstadoEndereco()
        ));

        if(count($result) > 0){
            $this->setIdEndereco($result[0]['id_endereco']);
        }

        return $this->getIdEndereco();
    }

    public static function getEnderecoByIdCliente($_id_cliente){
        $sql = new Sql();
        
        $result = $sql->select("SELECT * FROM tbendereco WHERE id_cliente_endereco = :ID_CLIENTE_ENDERECO", array(
            ":ID_CLIENTE_ENDERECO"=>$_id_cliente
        ));

        return $result;
    }

    public function update(){
        $sql = new Sql();

        $sql->query("UPDATE tbendereco SET cep = :CEP, logradouro_endereco = :LOGRADOURO_ENDERECO, num_endereco = :NUM_ENDERECO, complemento_endereco = :COMPLEMENTO_ENDERECO, bairro_endereco = :BAIRRO_ENDERECO, cidade_endereco = :CIDADE_ENDERECO, estado_endereco = :ESTADO_ENDERECO WHERE id_endereco = :ID_ENDERECO", array(
            ":ID_ENDERECO"=>$this->getIdEndereco(),
            ":CEP"=>$this->getCep(),
            ":LOGRADOURO_ENDERECO"=>$this->getLogradouroEndereco(),
            ":NUM_ENDERECO"=>$this->getNumEndereco(),
            ":COMPLEMENTO_ENDERECO"=>$this->getComplementoEndereco(),
            ":BAIRRO_ENDERECO"=>$this->getBairroEndereco(),
            ":CIDADE_ENDERECO"=>$this->getCidadeEndereco(),
            ":ESTADO_ENDERECO"=>$this->getEstadoEndereco()
        ));
    }

}

?>


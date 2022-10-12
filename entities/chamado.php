<?php 

class Chamado{
    private $id;
    private $protocolo;
    private $descricao;
    private $titulo;
    private $idCliente;
    private $dataAbertura;
    private $dataFinalizacao; 
    private $dataLimite = new DateTime('+ 1 month'); 
    private $foto_erro;
    private $status = "EM ABERTO";
    private $prioridade;
    private $local_atend;    


//métodos getter e setters
public function getId(){
    return $this->id;
}
public function setId($id){
    $this->id = $id;
}
public function getProtocolo(){
    return $this->protocolo;    
}
public function setProtocolo($protocolo){
    $this->protocolo = $protocolo;
}
public function getDescricao(){
    return $this->descricao;
}
public function setDescricao($descricao){
    $this->descricao = $descricao;
}
public function getTitulo(){
    return $this->titulo;
}
public function setTitulo($titulo){
    $this->titulo = $titulo;
}
public function getIdCliente(){
    return $this->idCliente;
}
public function setIdCliente($idCliente){
    $this->idCliente = $idCliente;
}
public function getDataAbertura(){
    return new DateTime();
}
public function setDataAbertura($dataAbertura){
    $this->dataAbertura = $dataAbertura;
}
public function getDataFinalizacao(){
    return $this->dataFinalizacao;
}
public function setDataFinalizacao($dataFinalizacao){
    $this->dataFinalizacao = $dataFinalizacao;
}
public function getDataLimite(){
    return new DateTime('+ 1 month');
}
public function setDataLimite($dataLimite){
    $this->dataLimite = $dataLimite;
}
public function getFotoErro(){
    return $this->foto_erro;
}
public function setFotoErro($foto_erro){
    $this->foto_erro = $foto_erro;
}
public function getStatus(){
    return $this->status;
}
public function setStatus($status){
    $this->status = $status;
}
public function getPrioridade(){
    return $this->prioridade;
}
public function setPrioridade($prioridade){
    $this->prioridade = $prioridade;
}
public function getLocalAtend(){
    return $this->local_atend;
}
public function setLocalAtend($local_atend){
    $this->local_atend = $local_atend;
}

//método construtor
public function __construct($id="", $protocolo="", $descricao="", $titulo="", $idCliente="", $dataAbertura="", $dataFinalizacao="", $dataLimite="", $foto_erro="", $status="", $prioridade="", $local_atend=""){
    $this->id = $id;
    $this->protocolo = $protocolo;
    $this->descricao = $descricao;
    $this->titulo = $titulo;
    $this->idCliente = $idCliente;
    $this->dataAbertura = $dataAbertura;
    $this->dataFinalizacao = $dataFinalizacao;
    $this->dataLimite = $dataLimite;
    $this->foto_erro = $foto_erro;
    $this->status = $status;
    $this->prioridade = $prioridade;
    $this->local_atend = $local_atend;
}




//Carregar por id
public function loadById($id){
    $sql = new Sql();
    $results = $sql->select("SELECT * FROM tbchamados WHERE id = :ID", array(
        ":ID"=>$id
    ));
    if(count($results) > 0){
        $this->setData($results[0]);
    }
}

//Função para setar Dados
public function setData($data){
    $this->setId($data['id']);
    $this->setProtocolo($data['protocolo']);
    $this->setDescricao($data['descricao']);
    $this->setTitulo($data['titulo']);
    $this->setIdCliente($data['idCliente']);
    $this->setDataAbertura(new DateTime($data['dataAbertura']));
    $this->setDataFinalizacao(new DateTime($data['dataFinalizacao']));
    $this->setDataLimite(new DateTime($data['dataLimite']));
    $this->setFotoErro($data['foto_erro']);
    $this->setStatus($data['status']);
    $this->setPrioridade($data['prioridade']);
    $this->setLocalAtend($data['local_atend']);
}


//Função para inserção de dados
public function insert(){



    $sql = new Sql();
    $results = $sql->select("CALL sp_chamados_insert(:PROT, :DESC, :TIT, :IDCLI, :DATAAB, :DATALIM, :FOTO, :STAT, :PRIOR, :LOCAL)", array(
        ":PROT"=>$this->getProtocolo(),
        ":DESC"=>$this->getDescricao(),
        ":TIT"=>$this->getTitulo(),
        ":IDCLI"=>$this->getIdCliente(),
        ":DATAAB"=>$this->getDataAbertura()->format("Y-m-d H:i:s"),
        ":DATALIM"=>$this->getDataLimite()->format("Y-m-d H:i:s"),
        ":FOTO"=>$this->getFotoErro(),
        ":STAT"=>$this->getStatus(),
        ":PRIOR"=>$this->getPrioridade(),
        ":LOCAL"=>$this->getLocalAtend()
    ));
    if(count($results) > 0){
        $this->setData($results[0]);
    }
}


//Função para atualizar o status do chamado
public function update() : bool{
    $sql = new Sql();

    // Pegando a senha que vier no json e codificando-a

    $res = $sql->query("UPDATE tbclientes SET status_chamado = :STATUSCHAMADO WHERE id = :ID", array(
        ":ID" => $this->getId(),
        ":STATUSCHAMADO" => $this->getStatus()
    ));

    //Retornará um booleano para o controller
    if($res){
        return true;
    }else{
        return false;
    }
}

//Função para atualizar/adicionar a data de finalização do chamado
public function updateDataFinalizacao($_dataFinalizacao) : bool{
    $sql = new Sql();

    //Gerando um objeto DateTime com a data que veio no parâmetro    
    $dataAserFormatada  = new DateTime($_dataFinalizacao);
    //Formatando a data para o formato do banco de dados
    $dataFormatada = $dataAserFormatada->format("Y-m-d H:i:s");


    

    $res = $sql->query("UPDATE tbchamados SET data_finalizacao_chamado = :DATAFINALIZACAO WHERE id = :ID", array(
        ":ID" => $this->getId(),
        ":DATAFINALIZACAO" => $dataFormatada
    ));

    //Retornará um booleano para o controller
    if($res){
        return true;
    }else{
        return false;
    }
} 




//Função par listar todos os chamados
//Função estática
public static function getList(){
    $sql = new Sql();

    return $sql->select("SELECT * FROM tbchamados ORDER BY titulo_chamado");
}


public static function search($_titulo){
    $sql = new Sql();
    return $sql->select("SELECT * FROM tbchamados WHERE titulo_chamaod LIKE :NOME ORDER BY titulo_chamado", array(
        ":NOME" => "%".$_titulo."%"
    ));
}

}



?>
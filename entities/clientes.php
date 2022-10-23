<?php 

class Clientes{
 
        private $id;
        private $nome;
        private $cpf;
        private $telefone;
        private $cnpj;
        private $razao_social;
        private $idTipo;
        private $email;
        private $senha;
    

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
    public function getCpf(){
        return $this->cpf;
    }
    public function setCpf($cpf){
        $this->cpf = $cpf;
    }
    public function getTelefone(){
        return $this->telefone;
    }
    public function setTelefone($telefone){
        $this->telefone = $telefone;
    }
    public function getCnpj(){
        return $this->cnpj;
    }
    public function setCnpj($cnpj){
        $this->cnpj = $cnpj;
    }
    public function getRazaoSocial(){
        return $this->razao_social;
    }
    public function setRazaoSocial($razao_social){
        $this->razao_social = $razao_social;
    }
    public function getIdTipo(){
        return $this->idTipo;
    }
    public function setIdTipo($idTipo){
        $this->idTipo = $idTipo;
    }
    public function getEmail(){
        return $this->email;
    }
    public function setEmail($email){
        $this->email = $email;
    }
    public function getSenha(){
        return $this->senha;
    }
    public function setSenha($senha){
        $this->senha = $senha;
    }

    //método construtor
    public function __construct($nome="", $cpf="", $telefone="", $cnpj="", $razao_social="", $idTipo="", $email="", $senha=""){
        
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->telefone = $telefone;
        $this->cnpj = $cnpj;
        $this->razao_social = $razao_social;
        $this->idTipo = $idTipo;
        $this->email = $email;
        $this->senha = $senha;
    }
   

    //Carregar por id
    public function loadById($id){
        $sql = new Sql();

        $result = $sql->select("SELECT * FROM tbcliente WHERE id = :ID", array(
            ":ID"=>$id
        ));
        if(count($result) > 0){
            $this->setData($result[0]);
        }
    }

    //Função para setar Dados
    public function setData($data){
        $this->setId($data['id_cliente']);
        $this->setNome($data['nome_cliente']);
        $this->setCpf($data['cpf_cliente']);
        $this->setTelefone($data['telefone_cliente']);
        $this->setCnpj($data['cnpj_cliente']);
        $this->setRazaoSocial($data['razao_social_cliente']);
        $this->setIdTipo($data['id_tipo_cliente']);
        $this->setEmail($data['email_cliente']);
        $this->setSenha($data['senha_cliente']);
    }


    //Função para inserção de dados
    public function insert(){
        $sql = new Sql();

        //criando a procedure
        $res = $sql->select("CALL sp_cliente_insert(:nome, :cpf, :telefone, :cnpj, :razaoSocial, :id_tipo, :email_cliente, :senha_cliente )", array(
            ":nome" => $this->getNome(),
            ":cpf" => $this->getCpf(),
            ":telefone" => $this->getTelefone(),
            ":cnpj" => $this->getCnpj(),
            ":razaoSocial" => $this->getRazaoSocial(),
            ":id_tipo" => $this->getIdTipo(),
            ":email_cliente" => $this->getEmail(),
            ":senha_cliente" => $this->getSenha()
        ));
        if(count($res)>0){
            $this->setId($res[0]['id_cliente']);   
        }
        //retornará o id para o controller
        return $this->getId();
    }
    //Função para alterar a senha do cliente
    public function updateSenha($_senhaAntiga, $_id_cliente, $_senhaNova){

        //Precisamos primeiro verificar a senha mandada pelo cliente, e se estiver certa, alterar a senha para a nova senha enviada

        $sql = new Sql();

        //verificando a senha antiga enviada
        $res = $sql->select("SELECT * FROM tbcliente WHERE id_cliente = :ID ", array(
            ":ID" => $_id_cliente,
        ));

        if(count($res) > 0){

    


            if(password_verify($_senhaAntiga, $res[0]['senha_cliente'])){   
                //Senha antiga está correta, vamos alterar a senha para a nova senha enviada
                $sql->querySql("UPDATE tbcliente SET senha_cliente = :SENHA WHERE id_cliente = :ID", array(
                    ":SENHA" => $_senhaNova,
                    ":ID" => $_id_cliente
                ));
                return 'dados corretos';
                
            }else{
                return 'dados incorretos';
            }
        }else{
            return 'dados incorretos';
        }
    }

    //Função para atualizar os dados SEM A SENHA, pois terá seu próprio método
    public function update() : bool{
        $sql = new Sql();

        // Pegando a senha que vier no json e codificando-a
        // $senhaCrypt = password_hash($this->getSenha(), PASSWORD_DEFAULT);

        $res = $sql->querySql("UPDATE tbcliente SET nome_cliente = :NOME, cpf_cliente = :CPF, telefone_cliente = :TELEFONE, cnpj_cliente = :CNPJ, razao_social_cliente = :RAZAOSOCIAL, id_tipo_cliente = :IDTIPO, email_cliente = :EMAIL WHERE id_cliente = :ID", array(
            ":NOME" => $this->getNome(),    
            ":ID" => $this->getId(),
            ":CPF" => $this->getCpf(),
            ":TELEFONE" => $this->getTelefone(),
            ":CNPJ" => $this->getCnpj(),
            ":RAZAOSOCIAL" => $this->getRazaoSocial(),
            ":IDTIPO" => $this->getIdTipo(),
            ":EMAIL" => $this->getEmail(),
        ));

        //Retornará um booleano para o controller
        if($res){
            return true;
        }else{
            return false;
        }
    }


    //Função para deleção de dados do cliente
    //Precisamos instanciar um cliente para deletar
    public function delete(){
        $sql = new Sql();

        //precisamos instanciar um usuário para deleta-lo
        $sql->query("DELETE FROM tbcliente WHERE id = :id", array(":id"=>$this->getId()));
    }

    //Função par listar todos os clientes
    //Função estática
    public static function getList(){
        $sql = new Sql();

        return $sql->select("SELECT * FROM tbcliente ORDER BY nome_cliente");
    }
    
    public static function search($_nome){
        $sql = new Sql();
        return $sql->select("SELECT * FROM tbcliente WHERE nome_cliente LIKE :NOME ORDER BY nome_cliente", array(
            ":NOME" => "%".$_nome."%"
        ));
    }

    public function efetuarLogin($_user, $_senha){
        $sql = new Sql();


        $res = $sql->select("SELECT * FROM tbcliente WHERE email_cliente = :EMAIL ", array(
            ":EMAIL" => $_user,
        ));

        if(count($res) > 0){
            if(password_verify($_senha, $res[0]['senha_cliente'])){
                $this->setData($res[0]);
            }else{
                return 'dados incorretos';
            }
        }else{
            return 'dados incorretos';
        }

    }

    //Precisamos passar a senha já criptografada lá no controller, então aqui já vamos receber a senha criptografada
    public static function atualizaEmail($_id_cliente, $_emailNovo, $_senhaAtual){
        $sql = new Sql();

        $res = $sql->select("SELECT * FROM tbcliente WHERE id_cliente = :ID ", array(
            ":ID" => $_id_cliente,
        ));

        if(count($res) > 0){


            if(password_verify($_senhaAtual, $res[0]['senha_cliente'])){

               
                //Se a senha estiver correta, vamos atualizar o email

                $sql->querySql("UPDATE tbcliente SET email_cliente = :EMAIL WHERE id_cliente = :ID", array(
                    ":EMAIL" => $_emailNovo,
                    ":ID" => $_id_cliente
                ));
                return 'dados corretos';
            }else{
                return 'dados incorretos';
            }
        }else{
            return 'dados incorretos';
        }
    }


        public static function atualizaTelefone($_id_cliente, $_telefone) : bool{
            $sql = new Sql();

            $sql->querySql("UPDATE tbcliente SET telefone_cliente = :TELEFONE WHERE id_cliente = :ID", array(
                ":TELEFONE" => $_telefone,
                ":ID" => $_id_cliente
            ));

            if($sql){
                return true;
            }
            return false;
        }
}

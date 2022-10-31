<?php
    
    //Nome da tabela tbusuarios
    class Usuario{
        private $id;
        private $nome;
        private $email;
        private $nivel;
        private $login;
        private $senha;
        private $foto_usuario;
    

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
    public function getEmail(){
        return $this->email;
    }
    public function setEmail($email){
        $this->email = $email;
    }
    public function getNivel(){
        return $this->nivel;
    }
    public function setNivel($nivel){
        $this->nivel = $nivel;
    }
    public function getLogin(){
        return $this->login;
    }
    public function setLogin($login){
        $this->login = $login;
    }
    public function getSenha(){
        return $this->senha;
    }
    public function setSenha($senha){
        $this->senha = $senha;
    }
    public function getFotoUsuario(){
        return $this->foto_usuario;
    }
    public function setFotoUsuario($foto_usuario){
        $this->foto_usuario = $foto_usuario;
    }

    //método construtor
    public function __construct($nome="", $email="", $nivel="", $login="", $senha="", $foto_usuario=""){
        
        $this->nome = $nome;
        $this->email = $email;
        $this->nivel = $nivel;
        $this->login = $login;
        $this->senha = $senha;
        $this->foto_usuario = $foto_usuario;
    }

    //Carregar por id
    public function loadById($id){
        $sql = new Sql();

        $result = $sql->select("SELECT * FROM tbusuarios WHERE id_usuario = :ID", array(
            ":ID"=>$id
        ));
        if(count($result) > 0){
            $this->setData($result[0]);
        }

        return $result;
    }

    //Função para setar Dados
    public function setData($data){
        $this->setId($data['id_usuario']);
        $this->setNome($data['nome_usuario']);
        $this->setEmail($data['email_usuario']);
        $this->setNivel($data['id_nivel_usuario']);
        $this->setLogin($data['login_usuario']);
        $this->setSenha($data['senha_usuario']);
        $this->setFotoUsuario($data['foto_usuario']);
    }


    //Função para inserção de dados
    public function insert(){
        $sql = new Sql();

        //Vamos verificar primeiro se o usuário já existe
        $verifyUserEmail = $sql->select("SELECT * FROM tbusuarios WHERE email_usuario = :EMAIL", array(
            ":EMAIL"=>$this->getEmail()
        ));

        if(count($verifyUserEmail) > 0){
            return 'usuario cadastrado';
        }
            
        //criando a procedure
        $res = $sql->select("CALL sp_user_insert(:nome, :email, :idnivel, :login, :senha, :foto_usuario)", array(
            ":nome" => $this->getNome(),
            ":email" => $this->getEmail(),
            ":idnivel" => $this->getNivel(),
            ":login" => $this->getLogin(),
            ":senha" => $this->getSenha(),
            ":foto_usuario" => $this->getFotoUsuario()
        ));
        if(count($res)>0){
            $this->setId($res[0]['id_usuario']);   
        }
    
        //retornará o id para o controller
        return $this->getId();
    }

    //Função para efetuar o login do usuário administrativo
    public function efetuarLogin($_loginUsuario, $_senha){
        $sql = new Sql();

        $result = $sql->select("SELECT * FROM tbusuarios WHERE login_usuario = :LOGIN", array(
            ":LOGIN"=>$_loginUsuario
        ));
        if(count($result) > 0){
            if(password_verify($_senha, $result[0]['senha_usuario'])){
                $this->setData($result[0]);                
            }else{
                return 'dados incorretos';
            }
        }else{
            return 'dados incorretos';
        }
    }

    //Função para atualizar os dados
    public function update() : bool{
        $sql = new Sql();

        $res = $sql->query("UPDATE tbusuarios SET nome_usuario = :NOME, email_usuario = :EMAIL, id_nivel_usuario = :ID_NIVEL, login_usuario = :LOGIN_USUARIO, senha_usuario = :SENHA, foto_usuario = :FOTO WHERE id = :ID", array(
            ":NOME" => $this->getNome(),
            ":ID" => $this->getId(),
            ":SENHA" => md5($this->getSenha()),
            ":ID_NIVEL" => $this->getNivel(),
            ":EMAIL" => $this->getEmail(),
            ":LOGIN_USUARIO" => $this->getLogin(),
            ":FOTO" => $this->getFotoUsuario()
        ));

        if($res){
            return true;
        }else{
            return false;
        }
    }

    //Função para deleção de dados do usuário
    public function delete(){
        $sql = new Sql();

        //precisamos instanciar um usuário para deleta-lo
        $sql->query("DELETE FROM tbusuarios WHERE id = :id", array(":id"=>$this->getId()));
    }

    //Função par listar todos os usuários
    public static function getList(){
        $sql = new Sql();

        return $sql->select("SELECT * FROM tbusuarios ORDER BY nome_usuario");
    }

    public static function atualizaNome($_idUsuario, $_novoNomeUsuario){
        $sql = new Sql();

        $sql->querySql("UPDATE tbusuarios SET nome_usuario = :NOME WHERE id_usuario = :ID", array(
            ":NOME" => $_novoNomeUsuario,
            ":ID" => $_idUsuario
        ));

        if($sql){
            return true;
        }
        return false;
    }

    

    //Função para atualizar o email do usuário já vericando se o email já existe
    public static function atualizaEmail($_id_usuario, $_novoEmail){
        $sql = new Sql();

        $procedure = $sql->select("CALL sp_usuario_update_Email(:EMAILNOVO, :IDUSUARIO)
               ", array(
                ":EMAILNOVO" => $_novoEmail,
                ":IDUSUARIO" => $_id_usuario
               ));

               //retornando os dados para o controller

               if($procedure[0]['ROW_COUNT()'] > 0){
                return 'Email cadastrado com sucesso';
               }else{
                return 'Esse email já pertence a outro usuário';
               }
    }

    //Função para atualizar o login do usuário
    public static function atualizaLoginUsuario($_id_usuario, $_loginUsuario){
        $sql = new Sql();

        $procedure = $sql->select("CALL sp_usuario_update_login (:LOGINNOVO, :IDUSUARIO)", array(
            ":LOGINNOVO" => $_loginUsuario,
            ":IDUSUARIO" => $_id_usuario
        ));

        //retornando os dados para o controller

        if($procedure[0]['ROW_COUNT()'] > 0){
            return 'Login atualizado com sucesso';
        }else{
            return 'Esse login já pertence a outro usuário';
        }
        
    }

    //Função para atualizar o nível do usuário

    public static function atualizaNivelUsuario($_id_usuario, $_nivelUsuario){
        $sql = new Sql();

        //Não vamos retornar nada pois essa requisição sempre será aceita

        $sql->select("CALL sp_usuario_update_nivel (:NIVELNOVO, :IDUSUARIO)", array(
            ":NIVELNOVO" => $_nivelUsuario,
            ":IDUSUARIO" => $_id_usuario
        ));

    }


    
    public static function search($_nome){
        $sql = new Sql();
        return $sql->select("SELECT * FROM tbusuarios WHERE nome_usuario LIKE :NOME ORDER BY nome_usuario", array(
            ":NOME" => "%".$_nome."%"
        ));
    }

   



}

?>
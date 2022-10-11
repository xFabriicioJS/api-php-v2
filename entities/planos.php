<!-- <?php 
      class Plano{
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

        $result = $sql->select("SELECT * FROM tbusuarios WHERE id = :ID", array(
            ":ID"=>$id
        ));
        if(count($result) > 0){
            $this->setData($result[0]);
        }
    }

    //Função para setar Dados
    public function setData($data){
        $this->setId($data['id']);
        $this->setNome($data['nome']);
        $this->setEmail($data['email']);
        $this->setNivel($data['nivel']);
        $this->setLogin($data['login']);
        $this->setSenha($data['senha']);
        $this->setFotoUsuario($data['foto_usuario']);
    }


    //Função para inserção de dados
    public function insert(){
        $sql = new Sql();

       

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
            $this->setId($res[0]['id']);   
        }
    
        //retornará o id para o controller
        return $this->getId();
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

    
    public static function search($_nome){
        $sql = new Sql();
        return $sql->select("SELECT * FROM tbusuarios WHERE nome_usuario LIKE :NOME ORDER BY nome_usuario", array(
            ":NOME" => "%".$_nome."%"
        ));
    }



?> -->
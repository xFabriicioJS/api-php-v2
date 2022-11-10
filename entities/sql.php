<?php 
class Sql extends PDO{
    private $cn;
    public function __construct(){
        $this->cn = new PDO("mysql:host=127.0.0.1;port=3306;dbname=tecnoodb","root","");
    }
    // método que atribui parametros para uma query sql
    public function setParams($comando, $parametros = array()){
        foreach($parametros as $key => $value){
            $this->setParam($comando,$key, $value);
        }
    }
    // método para tratar o parâmetro
    public function setParam($cmd, $key, $value){
        $cmd->bindParam($key, $value); 
    }
    // executa comandos SQL no banco
    public function querySql($comandoSql, $params = array()){
       $cmd = $this->cn->prepare($comandoSql);
       $this->setParams($cmd, $params);
       $cmd->execute();

       //mostra o comando SQL que será executado, muito útil por sinal melhor coisa ja feita nesse php
    //    $cmd->debugDumpParams();
       return $cmd;
    }
    public function select($comandoSql, $params = array()){
        

        $cmd = $this->querySql($comandoSql, $params);

        //printa a query que será executada no banco de dados (MUITO ÚTIL)
        // $cmd->debugDumpParams();
        return $cmd->fetchAll(PDO::FETCH_ASSOC);

    }

    
}
?>

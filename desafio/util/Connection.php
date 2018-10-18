<?php
ini_set('default_socket_timeout', 60);

class Connection
{
    // TODO: deveria ser removido daqui as configurações
    // host
    private $host = '127.0.0.1';
    // usuário
    private $user = 'root';
    // nome do banco
    private $db = 'desafio';
    // senha 
    private $pass = '';

    private $charset = 'utf8';
    private $pdo;

    /**
     * Constructor da classe Connection
     */
    public function __construct()
    {
        if (!$this->pdo) {
            $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
            $opt = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true
            );
            try {
                $this->pdo = new PDO($dsn, $this->user, $this->pass, $opt);
            } catch (Exception $e) {
                var_dump($e->getMessage());
                die();
            }
            

        }
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * @param PDO $pdo
     */
    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }
}
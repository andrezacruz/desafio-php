<?php
include_once "util/Connection.php";

abstract class AbstractModel
{
    /**
     * @var PDO
     */
    protected $pdo;

    protected $tableName;

    protected $primaryKey;

    protected $routename;

    /**
     * AbstractModel constructor.
     */
    public function __construct()
    {
        $conn = new Connection();
        $this->pdo = $conn->getPdo();
    }

    /**
     * @return PDO
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

    public function getTableName()
    {
        return $this->tableName;
    }

    public function save($id, $arrayParams)
    {
        $separador = '';

        if ($id == null) {
            $sql = 'INSERT INTO ' . $this->getTableName() . ' (';
            foreach ($arrayParams as $key => $val) {
                $sql .= $separador . ' ' . $key;
                $separador = ',';
            }

            $separador = '';
            $sql .= ') VALUES (';
            foreach ($arrayParams as $key => $val) {
                $sql .= $separador . ' :' . $key;
                $separador = ',';
            }
            $sql .= ');';

        } else {
            $sql = 'UPDATE ' . $this->getTableName() . ' SET ';

            foreach ($arrayParams as $key => $val) {
                if ($key !== 'id') {
                    $sql .= $separador . ' ' . $key . ' = :' . $key;
                    $separador = ', ';
                }                
            }

            $sql .= ' WHERE id = ' . $id;
        }
        
        $stmt = $this->getPdo()->prepare($sql);
        foreach ($arrayParams as $param => $val) {
            $stmt->bindValue(':' . $param, $val);
        }

        try {
            if ($id === null) {
                $sql .= '; SELECT LAST_INSERT_ID();';
            }

            $stmt->execute();
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
        if ($id === null) {
            $searchSql = 'SELECT id FROM ' . $this->tableName . ' ORDER BY id DESC LIMIT 1 OFFSET 0;';

            $searchStatement = $this->getPdo()->prepare($searchSql);
            $searchStatement->execute();

            $id = $searchStatement->fetchAll()[0]['id'];
        }
        return $this->get($id);
    }

    public function getAll($arrayParams = array())
    {
        $order_by = array_key_exists('order_by', $arrayParams) ? $arrayParams['order_by'] : array();
        $query = array_key_exists('query', $arrayParams) ? $arrayParams['query'] : array();

        $sql = 'SELECT * FROM ' . $this->tableName;
        $sqlCount = 'SELECT count(1) FROM ' . $this->tableName;
        $separador = '';
        if (count($query) > 0) {
            $sql .= ' WHERE ';
            $sqlCount .= ' WHERE ';
            foreach ($query as $param => $val) {
                $param = str_replace('query[', '', str_replace(']', '', $param));
                $sql .= $separador . ' ' . $param . ' = :' . $param;
                $sqlCount .= $separador . ' ' . $param . ' = :' . $param;
                $separador = ' AND ';

            }
        }

        if (count($order_by) > 0) {
            $sql .= ' ORDER BY ';
            foreach ($order_by as $param => $val) {
                $sql .= $separador . ' ' . $param . ' ' . $val;
                $separador = ',';
            }
        }

        try {
            $stmt = $this->getPdo()->prepare($sql);
            $stmtCount = $this->getPdo()->prepare($sqlCount);
        } catch (Exception $e) {
            var_dump($e);
            die();
        }

        if (count($query) > 0) {
            foreach ($query as $param => $val) {
                if ($val !== 'null' && $val !== 'notnull') {
                    $param = str_replace('query[', '', str_replace(']', '', $param));
                    $stmt->bindParam(':' . $param, urlencode($val));
                    $stmtCount->bindParam(':' . $param, urlencode($val));
                }
            }
        }

        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $result[$this->tableName] = $stmt->fetchAll();
        $stmtCount->execute();
        $result['total'] = $stmtCount->fetchColumn();
        return $result;
    }

    public function get($id)
    {
        $sql = "SELECT * FROM $this->tableName WHERE  id = ?";
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM $this->tableName WHERE id = ?";
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

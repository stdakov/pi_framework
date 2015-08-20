<?php

namespace App\Core;

use Aura\Sql\ExtendedPdo as PDO; //@reference https://github.com/auraphp/Aura.Sql

/**
 * Class DBRepository
 *
 * @package App\Core
 *
 * @author Stanislav Dakov st.dakov@gmail.com
 */
abstract class DBRepository implements RepositoryInterface
{
    /**
     * @var \Aura\Sql\ExtendedPdo|null
     */
    protected $pdo = null;

    public function __construct()
    {
        // Define PDO instance
        if ($this->pdo === null) {
            try {

                $config = Config::getInstance();

                $dbConfig = $config->load('app')->get("database");

                $this->pdo = new PDO('mysql:dbname=' . $dbConfig['DB_DATABASE'] . ';host=' . $dbConfig['DB_HOST'], $dbConfig['DB_USERNAME'], $dbConfig['DB_PASSWORD']);

                // Set default fetch mode
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                // Set MySQL specific attributes
                if ($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
                    $this->pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
                }

            } catch (\PDOException $exception) {
                throw new \Exception($exception->getMessage());
            }
        }
    }


    /**
     * @param string $table
     * @param array $data
     *
     * @return bool|int
     */
    final protected function insert($table, array $data)
    {
        if (empty($data)) {
            return false;
        }

        $keys = array_keys($data);

        $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $keys) . ') VALUES (:' . implode(', :', $keys) . ');';

        $query = $this->pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $query->bindValue(':' . $key, $data[$key]);
        }

        $query->execute();

        $insert = $query->rowCount();

        if ($insert) {
            return (int)$this->pdo->lastInsertId();
        }

        return false;
    }

    /**
     * @param string $table
     * @param array $data
     * @param array $where
     *
     * @return bool
     */
    final protected function update($table, array $data, $where = array())
    {
        if (empty($data)) {
            return false;
        }

        $sql = 'UPDATE ' . $table . ' SET ';


        $pieces = array();
        $binds = array();
        foreach ($data as $key => $value) {
            $pieces[] = $key . ' = :sPref' . $key;
            $binds['sPref' . $key] = $value;
        }

        $sql .= implode(', ', $pieces) . ' WHERE 1 ';

        if (is_array($where)) {
            if (empty($where)) {
                return false;
            } else {
                foreach ($where as $key => $value) {
                    $sql .= ' AND ' . $key . ' = :' . $key;
                    $binds[$key] = $value;
                }
            }
        } elseif (is_int($where)) {
            $sql .= ' AND Id = :Id';
            $binds['Id'] = $where;
        }

        $this->pdo->perform($sql, $binds);

    }

    /**
     * @param string $table
     * @param array $where
     *
     * @return bool
     */
    final protected function delete($table, $where = array())
    {
        $sql = 'DELETE FROM ' . $table . ' WHERE 1 ';

        $binds = array();

        if (is_array($where)) {
            if (empty($where)) {
                return false;
            } else {
                foreach ($where as $key => $value) {
                    $sql .= ' AND ' . $key . ' = :' . $key;
                    $binds[$key] = $value;
                }
            }
        } elseif (is_int($where)) {
            $sql .= ' AND Id = :Id';
            $binds['Id'] = $where;
        }

        $this->pdo->perform($sql, $binds);
    }
}

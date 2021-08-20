<?php

require_once('config.php');

class Player extends Database {
    protected $TBL_PLAYER = 'player_tb';

    public function add_player($data) {

        if (!empty($data)) {
            $fields = $placeholders = [];
            foreach ($data as $field => $value) {
                $fields[] = $field;
                $placeholders[] = ":{$field}";
            }
        }

        $sql = "INSERT INTO {$this->TBL_PLAYER} (". implode(',', $fields).") VALUES (". implode(',', $placeholders) .")";
        $stm = $this->conn->prepare($sql);
        try {
            $this->conn->beginTransaction();
            $stm->execute($data);
            $lastInsertedId = $this->conn->lastInsertId();
            $this->conn->commit();
            return $lastInsertedId;
        } catch (PDOException $e) {
            echo "Error: ". $e->getMessage();
            $this->conn->rollBack();
        }
    }

    public function update($data, $id)
    {
        if (!empty($data)) {
            $fields = '';
            $x = 1;
            $fieldsCount = count($data);
            foreach ($data as $field => $value) {
                $fields .= "{$field}=:{$field}";
                if ($x < $fieldsCount) {
                    $fields .= ", ";
                }
                $x++;
            }
        }
        $sql = "UPDATE {$this->TBL_PLAYER} SET {$fields} WHERE player_id=:player_id";
        $stm = $this->conn->prepare($sql);
        try {
            $this->conn->beginTransaction();
            $data['player_id'] = $id;
            $stm->execute($data);
            $this->conn->commit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $this->conn->rollback();
        }

    }

    public function getRows($start=0, $limit=4) {
        $sql = "SELECT * FROM {$this->TBL_PLAYER} ORDER BY player_score DESC LIMIT {$start}, {$limit}";
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        if ($stm->rowCount() > 0) {
            $results = $stm->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $results = [];
        }
        return $results;
    }

    public function getCount() {
        $sql = "SELECT count(*) as player_count FROM {$this->TBL_PLAYER}";
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result['player_count'];
    }

    public function getRow($field, $value)
    {

        $sql = "SELECT * FROM {$this->TBL_PLAYER}  WHERE {$field}=:{$field}";
        $stm = $this->conn->prepare($sql);
        $stm->execute([":{$field}" => $value]);
        if ($stm->rowCount() > 0) {
            $result = $stm->fetch(PDO::FETCH_ASSOC);
        } else {
            $result = [];
        }

        return $result;
    }

    public function deleteRow($value)
    {

        $sql = "DELETE FROM {$this->TBL_PLAYER}  WHERE player_id=:id";
        $stm = $this->conn->prepare($sql);
        $stm->execute([":id" => $value]);
        if ($stm->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}

?>
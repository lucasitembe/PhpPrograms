<?php
//include 'constants.php';

class dbconfig_remote {

    public $db = null;

    function dbconfig_remote() {
        if (is_null($this->db)) {
            try {
                $DB_con = new PDO("mysql:host=" . HOST_REM . ";port=".PORT_REM.";dbname=" . DBNAME_REM, USER_REM, PASSWORD_REM);
                $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $DB_con->exec("SET CHARACTER SET utf8");
                $this->db = $DB_con;
            } catch (PDOException $exception) {
                echo $exception->getMessage();
            }
        } else {
            $this->db = $db;
        }
    }
    
    function getRecord($sql) {
        try {
            $query = $this->db->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            $this->errorHandiling($ex->getMessage());
        } catch (Exception $ex) {
            $this->errorHandiling($ex->getMessage());
        }
    }

//Save user infor temporary

    function saveUserInfor($sql) {
        try {
            $affectedRow = $this->db->exec($sql);
            return $affectedRow;
        } catch (PDOException $ex) {
            $this->errorHandiling($ex->getMessage());
        } catch (Exception $ex) {
            $this->errorHandiling($ex->getMessage());
        }
    }

    function transactionalQueryExcute($sqlArray) {
        $success = false;
        try {
            $this->db->beginTransaction();

            foreach ($sqlArray as $sql) {
                $this->db->exec($sql);
            }

            $this->db->commit();

            $success = true;
        } catch (PDOException $ex) {
            $this->db->rollBack();
            $this->errorHandiling($ex->getMessage());
            $success = false;
        } catch (Exception $ex) {
            $this->db->rollBack();
            $this->errorHandiling($ex->getMessage());
            $success = false;
        }

        return $success;
    }

    function errorHandiling($error_message) {
        echo $error_message;
    }

}

<?php

class MySqlDatabase extends mysqli {
    private $connection;

    public function __construct($serverName, $userName, $password, $databaseName) {
        parent::__construct($serverName, $userName, $password, $databaseName);
        $this->connection = mysqli_connect(
            $serverName,
            $userName,
            $password,
            $databaseName);

        if (!$this->connection) {
            die('Connection failed: ' . mysqli_connect_error());
        }
    }
    public function escape($string): string
    {
        return mysqli_real_escape_string($this->connection, $string);
    }
    public function __destruct() {
        mysqli_close($this->connection);
    }

    /*public function query($sql) {
        $result = mysqli_query($this->connection, $sql);
        return mysqli_fetch_all($result, MYSQLI_BOTH);
    }*/
    public function query(string $sql, int $resultmode = MYSQLI_STORE_RESULT)
    {        Logger::info('Ejecutando query: ' . $sql);

        return parent::query($sql, $resultmode);
    }

    public function execute($sql) {
        Logger::info('Ejecutando query: ' . $sql);
        mysqli_query($this->connection, $sql);
    }

}
//La clase MySqlDatabase es responsable de establecer y cerrar la conexión con la base de datos MySQL,
// y también proporciona un método para ejecutar consultas SQL en la base de datos. método para ejecutar consultas SQL en la base de datos.


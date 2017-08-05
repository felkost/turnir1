<?php

/**
 * Клас описує з'єднання з БД.
 * Клас є базовим для інших класів, що взаємодіють з БД.
 * Конструктор створює з'єднання з БД, а якщо воно неуспішне, то закриває.
 * Деструктор закриває з'єднання з БД.
 * Ці методи наслідуються всіма класами, що взаємодують з БД.
 */
class BaseConnection 
{
    const USERNAME = 'user1';
    const PASSWORD = 'user1';
    const DBNAME   = 'turnir1';
    const SERVER   = 'localhost';

    //Конструктор класса устанавливает соединение с базой данных
    function __construct()
    {
        if ($mysqli = new mysqli(self::SERVER, self::USERNAME, self::PASSWORD,
            self::DBNAME))
        {
            $this->connection = $mysqli;
        }
        else
        {
            echo "Не удается соединиться с сервером MySQL";
            exit;
        }

    }

    function __destruct()
    {
        $this->connection->close();
    }
}
<?php

/**
 * 1. Клас описує методи взаємодії з БД для модифікації інформації про елементи живлення.
 * 2. Піключення require_once "BaseConnection.php" - є обов'язковим.
 * 3. Для контролю за синтаксисом написання SQL-запитів, потрібно підключитись до БД (права панель).
 *    В контекстному меню для підключення є команди, які візуалізують БД.
 * 4. В книгах написано, що батьківський конструктор автоматично не викликається, тому в конструкторі класа
 *    потрібно прописати parent::__construct();
 * 5. Поля для збереження дійсних чисел в БД матимуть тип DECIMAL(5, 2) - 5 цифр, з них 2 після коми.
 *    Такий підхід дозволяє виконувати пошук по полях, чого не може зробити FLOAT та DOUBLE.
 * 6. Якщо в SQL-запиті використані PHP-змінні, то їх краще еранувати \", а не '. Тип екранування підбирати по ситуації,
 *    перевіряючи в Debug-режимі вид побудованого SQL-запиту. Можна екранувати і через ...".$var."...
 * 7. В усіх методах вхідні параметри або SQL-запити залежать від властивостей класу DBBattery, 
 *    тому методи не відносяться до базового класу BaseConnection. Всі методи, крім selectID,
 *    повертають true - коли запит виконано успішно та false - в протилежному випадку.
 *    createTable - створити таблицю з полями відповідних типів.
 *    insertObject - якщо для об'єкту не знайдено ID, тоді вставити цей об'єкт в БД.
 *    selectID - за полями об'єкту знайти id об'єкта в БД або повернути false (якщо потрібно первірити чи
 *              знайдено(!) об'єкт, то краще перевіряти на id!=false, бо id починаються з 1).
 *    delObject_ID  - вилучити об'єкт з БД за його ID (застосовується лише після виконання selectID.
 *    delObject - вилучити об'єкт з БД: для зручності об'єднано пошук об'єкта по ID та його вилучення по ID.
 *              PS: функція delObject (вилучити об'єкт з БД за його параметрами) некоректно(!) виконується, оскільки шукає запис
 *                  за всіма полями, крім ID; тому логічніше за допомогою selectID знайти ID для відповідного об'єкту,
 *                  а потім за знайденим ID вилучити об'єкт. Слід пам'ятати, що для відсутнього ID функція selectID повертає false.
 *    updateAllObject - знайти вказаний об'єкт в БД та оновити всі його поля.
 *    updateField  - знайти вказаний об'єкт та оновити вказане поле.
 *    getListBatteries - список (масив з числовими індексами) об'єктів DataBattery на основі ВСІХ записів в БД.
 */
require_once "BaseConnection.php";
class DBBattery extends BaseConnection
{
    function __construct()
    {
        parent::__construct();
    }

    function createTable()
    {
        $query =  "DROP TABLE IF EXISTS batteries;";
        parent::queryMysql($query);
        $query =  "CREATE TABLE batteries (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         u DECIMAL(5,2) NOT NULL,
                         capacity DECIMAL(5,2)  NOT NULL,
                         i DECIMAL(5,2)  NOT NULL,
                         maxI DECIMAL(5,2)  NOT NULL,
                         INDEX (u)
                         );";
        return  parent::queryMysql($query);
    }

    function insertObject(DataBattery $dataBattery)
    {
        $result = $this->selectID($dataBattery);

        if ($result!=false) return false; //false: Сбой при доступе к базе данных (такий об'єкт є в БД)
        
        $query =  "INSERT INTO batteries (u, capacity, i, maxI) 
                     VALUES(\"$dataBattery->u\", \"$dataBattery->capacity\",
                      \"$dataBattery->i\", \"$dataBattery->maxI\");";
        return parent::queryMysql($query);
    }
    
    function delObject(DataBattery $dataBattery)
    {
        $id = $this->selectID($dataBattery);
        if ($id == false) return false;
        $this->delObject_ID($id);
        return true;
    }

    function selectID(DataBattery $dataBattery)
    {
        $query =  "SELECT id FROM batteries WHERE u=".$dataBattery->u. 
                         " AND capacity=".$dataBattery->capacity.
                         " AND i=".$dataBattery->i.
                         " AND maxI=".$dataBattery->maxI.";";
        $result = parent::queryMysql($query);

        if (!$result->num_rows) return false; //FALSE: Сбой при доступе к базе данных (ID відсутнє)

        $battery = $result->fetch_assoc();
        $battery_id = $battery['id'];
        return $battery_id;
    }

    function delObject_ID(int $id)
    {
        $query =  "DELETE FROM batteries WHERE id=\"$id\";";
        parent::queryMysql($query);
    }

    function updateAllObject(DataBattery $dataOld, DataBattery $dataNew){
        $result = $this->selectID($dataOld);

        if ($result==false) return false; //false: Сбой при доступе к базе данных (такого об'єкту не має в БД)

        $query =  "UPDATE batteries SET u=\"$dataNew->u\",
                          capacity=\"$dataNew->capacity\", 
                          i=\"$dataNew->i\", 
                          maxI=\"$dataNew->maxI\"
                          WHERE id=\"$result\";";
        parent::queryMysql($query);
        return true;
    }

    function updateField(DataBattery $dataOld, $field, $value){
        $result = $this->selectID($dataOld);

        if ($result==false) return false; //false: Сбой при доступе к базе данных (такого об'єкту не має в БД)

        $query =  "UPDATE batteries SET ".$field."=".$value." WHERE id=".$result.";";
        parent::queryMysql($query);
        return true;
    }

    function getListBatteries(){
        $query = "SELECT * FROM batteries";;
        $result = parent::queryMysql($query);

        if ($result==false) return false;
        
        $listDataBattery = [];
        $rows = $result->num_rows;
        for($i=0; $i<$rows; $i++){
            $result->data_seek($i);
            $row=$result->fetch_assoc();
            $data = new DataBattery($row['u'], $row['capacity'], $row['i'], $row['maxI']);
            $listDataBattery[] = $data;
        }

        return $listDataBattery;
    }
}
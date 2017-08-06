<?php

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    /*
     * Тест перевіряє методи класу DBBattery для створення таблиці, вставки записів, пошук записів по ID,
     * та вилучення по ID відповідного запису.
     */
    public function testData1()
    {
        $data1 = new DataBattery(3.6, 2.6, 2, 50);
        $data2 = new DataBattery(3.6, 1.2, 1, 35);
        $data3 = new DataBattery(24, 7.2, 2.88, 23);
        $data4 = new DataBattery(3.6, 17, 5, 250);
        $data_false = new DataBattery(0.1, 0.1, 0.1, 0.1);
        
        $dataDB = new DBBattery();
        
        $this->assertEquals(true,$dataDB->createTable());//створити таблицю

        //вставити дані в таблицю
        $this->assertEquals(true,$dataDB->insertObject($data1));
        $this->assertEquals(false,$dataDB->insertObject($data1));//спроба вставити повторно
        $this->assertEquals(true,$dataDB->insertObject($data2));
        $this->assertEquals(true,$dataDB->insertObject($data3));
        $this->assertEquals(true,$dataDB->insertObject($data4));

        print_r($dataDB->getListBatteries()); //вивести весь список записів у вигляді масиву об'єктів DataBattery

        $this->assertEquals(3,$dataDB->selectID($data3));//знайти існуючі дані (краще перевіряти під час використання id!=false)
        $this->assertEquals(false,$dataDB->selectID($data_false));//знайти НЕіснуючі дані

        $this->assertEquals(true, $dataDB->delObject($data2)); //вилучити об'єкт
        
        $this->assertEquals(false, $dataDB->delObject($data2));//повторно(!) вилучити об'єкт
 
        $this->assertEquals(true,$dataDB->insertObject($data2));//вставити об'єкт, який раніше був вилученим

        $dataForUpdate = new DataBattery(1.1, 10.1, 11.1, 250);
        $this->assertEquals(true,$dataDB->updateAllObject($data4, $dataForUpdate));//оновити всі поля для об'єкт $data4

        $this->assertEquals(true,$dataDB->updateField($dataForUpdate, "u", 100));//оновити поле U для об'єкт $dataForUpdate
        
        $list = $dataDB->getListBatteries();
        $this->assertEquals(true,$dataDB->updateField($list[count($list)-1], "i", 0.01));//оновити поле i для останнього об'єкта таблиці 
    }
}

<?php

/**
 * Клас описує модель елемента живлення.
 * напруга В,
 * питома ємність, Аh
 * струм розрядки, стандартний, мА
 * струм розрядки, максимальний, мА
 * Cайт з тех. хар-стиками: http://www.compel.ru/infosheet/SAFT/LS14500%20-E-
 * Значення будуть тип double, але зберігатимуться в БД в DECIMAL (пояснення в DBBattery.php)
 */
class DataBattery
{
    public $u;
    public $capacity;
    public $i;
    public $maxI;

    function __construct($u, $capacity, $i, $maxI){
        $this->u = $u;
        $this->capacity = $capacity;
        $this->i = $i;
        $this->maxI = $maxI;
    }
}
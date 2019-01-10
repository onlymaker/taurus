<?php

namespace spec\_201809;

class ReportStage
{
    const INIT = 0;
    const ORDER = 1;
    const LOGISTICS = 2;
    const RECEIPTS = 3;
    const INVENTORY = 4;
    const WAYBILL = 5;
    const FINISH = 100;

    static function all() : array
    {
        $reflect = new \ReflectionClass(__CLASS__);
        $data = $reflect->getConstants();
        return array_change_key_case($data);
    }

    static function getName($stage)
    {
        return array_search($stage, self::all());
    }
}

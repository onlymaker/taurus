<?php

namespace spec\_201809;

class OrderHead
{
    public $guid;//C.36
    public $appType = '1';//C1
    public $appTime;//YYYYMMDDhhmmss
    public $appStatus = '2';//C1
    public $orderType = 'E';//C1
    public $orderNo;//C..60
    public $ebpCode;//C..50
    public $ebpName;//C..100
    public $ebcCode;//C..18
    public $ebcName;//C..100
    public $goodsValue;//N19,5
    public $freight;//N19,5
    public $currency;//C3
    public $note;
}

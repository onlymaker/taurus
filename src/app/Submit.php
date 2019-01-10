<?php

namespace app;

use db\Mysql;
use db\SqlMapper;
use Ramsey\Uuid\Uuid;
use spec\_201809\BaseTransfer;
use spec\_201809\InventoryHead;
use spec\_201809\InventoryList;
use spec\_201809\Logistics;
use spec\_201809\OrderHead;
use spec\_201809\OrderList;
use spec\_201809\Receipts;
use spec\_201809\WaybillHead;
use spec\_201809\WaybillList;

class Submit
{
    private $data;
    private $report;

    function order()
    {
        $head = new OrderHead();
        $head->guid = $this->getGuid();
        $head->appType = '1';
        $head->appTime = date('YmdHis');
        $head->appStatus = '2';
        $head->orderType = 'E';
        $head->orderNo = $this->report['oid'];
        $head->ebpCode = '9151010032742290X5';
        $head->ebpName = '成都欧魅时尚科技有限责任公司';
        $head->ebcCode = '9151010032742290X5';
        $head->ebcName = '成都欧魅时尚科技有限责任公司';
        $head->goodsValue = floatval($this->data['price']);
        $head->freight = 0;
        $head->currency = 142;
        $head->note = '';
        $list = new OrderList();
        $list->gnum = $this->getGnum();
        $list->itemNo = $this->data['model'];
        $list->itemName = 'pu女鞋';
        $list->itemDescribe = 'fashion Women shoes';
        $list->barCode = $this->report['oid'];
        $list->unit = 011;
        $list->currency = 142;
        $list->qty = 1;
        $list->price = floatval($this->data['price']);
        $list->totalPrice = floatval($this->data['price']);
        $list->note = '';
        $baseTransfer = new BaseTransfer();
        $f3 = \Base::instance();
        $f3->set('order', [
            [
                'head' => (array) $head,
                'list' => (array) $list,
            ]
        ]);
        $f3->set('baseTransfer', (array) $baseTransfer);
        echo \Template::instance()->render('spec/_201809/order.xml', 'application/xml');
    }

    function logistics()
    {
        $logistics = new Logistics();
        $logistics->guid = $this->getGuid();
        $logistics->appType = '1';
        $logistics->appTime = date('YmdHis');
        $logistics->appStatus = '1';
        $logistics->logisticsCode = '510198Z006';
        $logistics->logisticsName = '中国邮政速递物流股份有限公司';
        $logistics->logisticsNo = $this->data['express'];
        $logistics->freight = 0;
        $logistics->insuredFee = 0;
        $logistics->currency = 142;
        $logistics->grossWeight = 1;
        $logistics->packNo = 1;
        $logistics->goodsInfo = 'pu女鞋';
        $logistics->ebcCode = '9151010032742290X5';
        $logistics->ebcName = '成都欧魅时尚科技有限责任公司';
        $logistics->ebcTelephone = '';
        $logistics->note = '';
        $baseTransfer = new BaseTransfer();
        $f3 = \Base::instance();
        $f3->set('logistics', [(array) $logistics]);
        $f3->set('baseTransfer', (array) $baseTransfer);
        echo \Template::instance()->render('spec/_201809/logistics.xml', 'application/xml');
    }

    function receipts()
    {
        $receipts = new Receipts();
        $receipts->guid = $this->getGuid();
        $receipts->appType = '1';
        $receipts->appTime = date('YmdHis');
        $receipts->appStatus = '2';
        $receipts->ebpCode = '9151010032742290X5';
        $receipts->ebpName = '成都欧魅时尚科技有限责任公司';
        $receipts->ebcCode = '9151010032742290X5';
        $receipts->ebcName = '成都欧魅时尚科技有限责任公司';
        $receipts->orderNo = $this->report['oid'];;
        $receipts->payCode = '';
        $receipts->payName = '杭州呯嘭智能技术有限公司';
        $receipts->payNo = '';
        $receipts->charge = $this->data['price'];
        $receipts->currency = 142;
        $receipts->accountingDate = date('YmdHis');
        $receipts->note = '';
        $baseTransfer = new BaseTransfer();
        $f3 = \Base::instance();
        $f3->set('receipts', [(array) $receipts]);
        $f3->set('baseTransfer', (array) $baseTransfer);
        echo \Template::instance()->render('spec/_201809/receipts.xml', 'application/xml');
    }

    function inventory()
    {
        $head = new InventoryHead();
        $head->guid = $this->getGuid();
        $head->appType = '1';
        $head->appTime = date('YmdHis');
        $head->appStatus = '2';
        $head->customsCode = 7901;
        $head->ebpCode = '9151010032742290X5';
        $head->ebpName = '成都欧魅时尚科技有限责任公司';
        $head->orderNo = $this->report['oid'];
        $head->logisticsCode = '510198Z006';
        $head->logisticsName = '中国邮政速递物流股份有限公司';
        $head->logisticsNo = $this->data['express'];
        $head->copNo = $this->report['oid'];;
        $head->preNo = '';
        $head->ivtNo = '';
        $head->ieFlag = 'E';
        $head->portCode = 7901;
        $head->ieDate =  date('Ymd');
        $head->statisticsFlag = 'A';
        $head->agentCode = '510196552A';
        $head->agentName = '成都欧魅时尚科技有限责任公司';
        $head->ebcCode = '9151010032742290X5';
        $head->ebcName = '成都欧魅时尚科技有限责任公司';
        $head->ownerCode = '9151010032742290X5';
        $head->ownerName = '成都欧魅时尚科技有限责任公司';
        $head->iacCode = '';
        $head->iacName = '';
        $head->emsNo = '';
        $head->tradeMode = 9610;
        $head->trafMode = 5;
        $head->trafName = '';
        $head->voyageNo = '';
        $head->billNo = '';
        $head->totalPackageNo = '';
        $head->loctNo = '';
        $head->licenseNo = '';
        $head->country = 'USA';
        $head->POD = 'USA';
        $head->freight = 0;
        $head->fCurrency = 142;
        $head->fFlag = 3;
        $head->insuredFee = 0;
        $head->iCurrency = 142;
        $head->iFlag = 3;
        $head->wrapType = 2;
        $head->packNo = 1;
        $head->grossWeight = 1;
        $head->netWeight = 1;
        $head->note = '';
        $list = new InventoryList();
        $list->gnum = $this->getGnum();
        $list->itemNo = $this->data['model'];
        $list->itemRecordNo = '';
        $list->itemName = '';
        $list->gcode = '6402992900';
        $list->gname = 'PU女鞋';
        $list->gmodel = $this->data['model'];
        $list->barCode = $this->report['oid'];
        $list->country = 'USA';
        $list->currency = 142;
        $list->qty = 1;
        $list->qty1 = 1;
        $list->qty2 = '';
        $list->unit = 011;
        $list->unit1 = 011;
        $list->unit2 = '';
        $list->price = $this->data['price'];
        $list->totalPrice = $this->data['price'];
        $list->note = '';
        $baseTransfer = new BaseTransfer();
        $f3 = \Base::instance();
        $f3->set('inventory', [
            [
                'head' => (array) $head,
                'list' => (array) $list,
            ]
        ]);
        $f3->set('baseTransfer', (array) $baseTransfer);
        echo \Template::instance()->render('spec/_201809/inventory.xml', 'application/xml');
    }

    function waybill()
    {
        $head = new WaybillHead();
        $head->guid = $this->getGuid();
        $head->appType = '1';
        $head->appTime = date('YmdHis');
        $head->appStatus = '2';
        $head->customsCode = 7901;
        $head->copNo = '9151010032742290X5';
        $head->preNo = '';
        $head->agentCode = '9151010032742290X5';
        $head->agentName = '成都欧魅时尚科技有限责任公司';
        $head->loctNo = '';
        $head->trafMode = 5;
        $head->trafName = '飞机';
        $head->voyageNo = 'CA1419';
        $head->billNo = $this->data['express'];
        $head->domesticTrafNo = 'E66N7';
        $head->grossWeight = 1;
        $head->logisticsCode = '510198Z006';
        $head->logisticsName = '中国邮政速递物流股份有限公司';
        $head->msgCount = 1;
        $head->msgSeqNo = '';
        $head->note = '';
        $list = new WaybillList();
        $list->gnum = $this->getGnum();
        $list->totalPackageNo = '';
        $list->logisticsNo = $this->data['express'];
        $list->invtNo = '';
        $list->note = '';
        $baseTransfer = new BaseTransfer();
        $f3 = \Base::instance();
        $f3->set('waybill', [
            [
                'head' => (array) $head,
                'list' => (array) $list,
            ]
        ]);
        $f3->set('baseTransfer', (array) $baseTransfer);
        echo \Template::instance()->render('spec/_201809/waybill.xml', 'application/xml');
    }

    function __construct($report)
    {
        $this->report = $report;
        $traceId= $this->report['oid'];
        $db = Mysql::instance()->get();
        $query = $db->exec("select o.price, p.model, d.distribution_number as express from order_item o left join prototype p  on o.prototype_id=p.ID left join distribution d on o.distribution_id=d.ID where o.trace_id='$traceId' limit 1");
        $this->data = $query[0] ?: [];
    }

    private function getGuid()
    {
        if ($this->report['guid'] == '') {
            $this->report['guid'] = Uuid::uuid1()->toString();
        }
        return $this->report['guid'];
    }

    private function getGnum()
    {
        if ($this->report['gnum'] == 0) {
            $mapper = new SqlMapper('report_upload');
            $mapper->maxGnum = 'max(gnum)';
            $mapper->load();
            $this->report['gnum'] = $mapper['maxGnum'] + 1;
        }
        return $this->report['gnum'];
    }
}

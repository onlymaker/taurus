<?php

namespace app;

use db\SqlMapper;
use Httpful\Mime;
use Httpful\Request;
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
    private $url;
    private $data;

    function order()
    {
        $head = new OrderHead();
        $head->guid = $this->getGuid();
        $head->appType = '1';
        $head->appTime = date('YmdHis');
        $head->appStatus = '2';
        $head->orderType = 'E';
        $head->orderNo = $this->data['oid'];
        $head->ebpCode = '510196552A';
        $head->ebpName = '成都欧魅时尚科技有限责任公司';
        $head->ebcCode = '510196552A';
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
        $list->barCode = $this->data['oid'];
        $list->unit = '011';
        $list->currency = 142;
        $list->qty = 1;
        $list->price = floatval($this->data['price']);
        $list->totalPrice = floatval($this->data['price']);
        $list->note = '';
        $baseTransfer = new BaseTransfer();
        $baseTransfer->copCode = '510196552A';
        $baseTransfer->copName = '成都欧魅时尚科技有限责任公司';
        $baseTransfer->dxpMode = 'DXP';
        $baseTransfer->dxpId = 'DXPENT0000020478';
        $baseTransfer->note = '';
        $f3 = \Base::instance();
        $f3->set('order', [
            [
                'head' => (array) $head,
                'list' => (array) $list,
            ]
        ]);
        $f3->set('baseTransfer', (array) $baseTransfer);
        $xml = \Template::instance()->render('spec/_201809/order.xml', 'application/xml');
        /*$response = \Web::instance()->request($this->url, [
            'header'  => [
                'Content-type: application/x-www-form-urlencoded',
            ],
            'method' => 'post',
            'content' => http_build_query(['xml' => $xml], null, '&'),
        ]);
        var_dump($response);*/
        $response = Request::post($this->url, ['xml' => $xml], Mime::FORM)->send();
        $result = $this->parseReturnXml($response->raw_body);
        $rLog = new SqlMapper('report_log');
        $rLog['type'] = __FUNCTION__;
        $rLog['content'] = $xml;
        $rLog['response'] = $response->raw_body;
        $rLog['status'] = $result['return_status'];
        $rLog['message'] = $result['return_info'];
        $rLog->save();
        $report = new SqlMapper('report_upload');
        $report->load(['id=?', $this->data['id']]);
        $report->copyfrom($this->data);
        $report['report_' . __FUNCTION__ . '_status'] = $rLog['status'];
        $report['report_' . __FUNCTION__ . '_log'] = $rLog['id'];
        $report->save();
        echo $result['return_status'], ':', $result['return_info'];
    }

    function logistics()
    {
        $logistics = new Logistics();
        $logistics->guid = $this->getGuid();
        $logistics->appType = '1';
        $logistics->appTime = date('YmdHis');
        $logistics->appStatus = '1';
        $logistics->logisticsCode = '5101982029';
        $logistics->logisticsName = '四川省邮政速递服务有限公司';
        $logistics->logisticsNo = $this->data['express'];
        $logistics->freight = 0;
        $logistics->insuredFee = 0;
        $logistics->currency = 142;
        $logistics->grossWeight = 1;
        $logistics->packNo = 1;
        $logistics->goodsInfo = 'pu女鞋';
        $logistics->ebcCode = '510196552A';
        $logistics->ebcName = '成都欧魅时尚科技有限责任公司';
        $logistics->ebcTelephone = '';
        $logistics->note = '';
        $baseTransfer = new BaseTransfer();
        $baseTransfer->copCode = '5101982029';
        $baseTransfer->copName = '四川省邮政速递服务有限公司';
        $baseTransfer->dxpMode = 'DXP';
        $baseTransfer->dxpId = 'DXPENT0000020478';
        $baseTransfer->note = '';
        $f3 = \Base::instance();
        $f3->set('logistics', [(array) $logistics]);
        $f3->set('baseTransfer', (array) $baseTransfer);
        $xml = \Template::instance()->render('spec/_201809/logistics.xml', 'application/xml');
        $response = Request::post($this->url, ['xml' => $xml], Mime::FORM)->send();
        $result = $this->parseReturnXml($response->raw_body);
        $rLog = new SqlMapper('report_log');
        $rLog['type'] = __FUNCTION__;
        $rLog['content'] = $xml;
        $rLog['response'] = $response->raw_body;
        $rLog['status'] = $result['return_status'];
        $rLog['message'] = $result['return_info'];
        $rLog->save();
        $report = new SqlMapper('report_upload');
        $report->load(['id=?', $this->data['id']]);
        $report->copyfrom($this->data);
        $report['report_' . __FUNCTION__ . '_status'] = $rLog['status'];
        $report['report_' . __FUNCTION__ . '_log'] = $rLog['id'];
        $report->save();
        echo $result['return_status'], ':', $result['return_info'];
    }

    function receipts()
    {
        $receipts = new Receipts();
        $receipts->guid = $this->getGuid();
        $receipts->appType = '1';
        $receipts->appTime = date('YmdHis');
        $receipts->appStatus = '2';
        $receipts->ebpCode = '510196552A';
        $receipts->ebpName = '成都欧魅时尚科技有限责任公司';
        $receipts->ebcCode = '510196552A';
        $receipts->ebcName = '成都欧魅时尚科技有限责任公司';
        $receipts->orderNo = $this->data['oid'];
        $receipts->payCode = '';
        $receipts->payName = '杭州呯嘭智能技术有限公司';
        $receipts->payNo = '';
        $receipts->charge = $this->data['price'];
        $receipts->currency = 142;
        $receipts->accountingDate = date('YmdHis');
        $receipts->note = '';
        $baseTransfer = new BaseTransfer();
        $baseTransfer->copCode = '510196552A';
        $baseTransfer->copName = '成都欧魅时尚科技有限责任公司';
        $baseTransfer->dxpMode = 'DXP';
        $baseTransfer->dxpId = 'DXPENT0000020478';
        $baseTransfer->note = '';
        $f3 = \Base::instance();
        $f3->set('receipts', [(array) $receipts]);
        $f3->set('baseTransfer', (array) $baseTransfer);
        $xml = \Template::instance()->render('spec/_201809/receipts.xml', 'application/xml');
        $response = Request::post($this->url, ['xml' => $xml], Mime::FORM)->send();
        $result = $this->parseReturnXml($response->raw_body);
        $rLog = new SqlMapper('report_log');
        $rLog['type'] = __FUNCTION__;
        $rLog['content'] = $xml;
        $rLog['response'] = $response->raw_body;
        $rLog['status'] = $result['return_status'];
        $rLog['message'] = $result['return_info'];
        $rLog->save();
        $report = new SqlMapper('report_upload');
        $report->load(['id=?', $this->data['id']]);
        $report->copyfrom($this->data);
        $report['report_' . __FUNCTION__ . '_status'] = $rLog['status'];
        $report['report_' . __FUNCTION__ . '_log'] = $rLog['id'];
        $report->save();
        echo $result['return_status'], ':', $result['return_info'];
    }

    function inventory()
    {
        $head = new InventoryHead();
        $head->guid = $this->getGuid();
        $head->appType = '1';
        $head->appTime = date('YmdHis');
        $head->appStatus = '2';
        $head->customsCode = 7901;
        $head->ebpCode = '510196552A';
        $head->ebpName = '成都欧魅时尚科技有限责任公司';
        $head->orderNo = $this->data['oid'];
        $head->logisticsCode = '5101982029';
        $head->logisticsName = '四川省邮政速递服务有限公司';
        $head->logisticsNo = $this->data['express'];
        $head->copNo = $this->data['oid'];
        $head->preNo = '';
        $head->ivtNo = '';
        $head->ieFlag = 'E';
        $head->portCode = 7901;
        $head->ieDate =  date('Ymd');
        $head->statisticsFlag = 'A';
        $head->agentCode = '510196552A';
        $head->agentName = '成都欧魅时尚科技有限责任公司';
        $head->ebcCode = '510196552A';
        $head->ebcName = '成都欧魅时尚科技有限责任公司';
        $head->ownerCode = '510196552A';
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
        $head->country = 502;
        $head->POD = 'LAX';
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
        $list->barCode = $this->data['oid'];
        $list->country = 502;
        $list->currency = 142;
        $list->qty = 1;
        $list->qty1 = 1;
        $list->qty2 = '';
        $list->unit = '011';
        $list->unit1 = '011';
        $list->unit2 = '';
        $list->price = $this->data['price'];
        $list->totalPrice = $this->data['price'];
        $list->note = '';
        $baseTransfer = new BaseTransfer();
        $baseTransfer->copCode = '510196552A';
        $baseTransfer->copName = '成都欧魅时尚科技有限责任公司';
        $baseTransfer->dxpMode = 'DXP';
        $baseTransfer->dxpId = 'DXPENT0000020478';
        $baseTransfer->note = '';
        $f3 = \Base::instance();
        $f3->set('inventory', [
            [
                'head' => (array) $head,
                'list' => (array) $list,
            ]
        ]);
        $f3->set('baseTransfer', (array) $baseTransfer);
        $xml = \Template::instance()->render('spec/_201809/inventory.xml', 'application/xml');
        $response = Request::post($this->url, ['xml' => $xml], Mime::FORM)->send();
        $result = $this->parseReturnXml($response->raw_body);
        $rLog = new SqlMapper('report_log');
        $rLog['type'] = __FUNCTION__;
        $rLog['content'] = $xml;
        $rLog['response'] = $response->raw_body;
        $rLog['status'] = $result['return_status'];
        $rLog['message'] = $result['return_info'];
        $rLog->save();
        $report = new SqlMapper('report_upload');
        $report->load(['id=?', $this->data['id']]);
        $report->copyfrom($this->data);
        $report['report_' . __FUNCTION__ . '_status'] = $rLog['status'];
        $report['report_' . __FUNCTION__ . '_log'] = $rLog['id'];
        $report->save();
        echo $result['return_status'], ':', $result['return_info'];
    }

    function waybill()
    {
        $head = new WaybillHead();
        $head->guid = $this->getGuid();
        $head->appType = '1';
        $head->appTime = date('YmdHis');
        $head->appStatus = '2';
        $head->customsCode = 7901;
        $head->copNo = '510196552A';
        $head->preNo = '';
        $head->agentCode = '510196552A';
        $head->agentName = '成都欧魅时尚科技有限责任公司';
        $head->loctNo = '';
        $head->trafMode = 5;
        $head->trafName = '飞机';
        $head->voyageNo = 'CA1419';
        $head->billNo = $this->data['express'];
        $head->domesticTrafNo = 'E66N7';
        $head->grossWeight = 1;
        $head->logisticsCode = '5101982029';
        $head->logisticsName = '四川省邮政速递服务有限公司';
        $head->msgCount = 1;
        $head->msgSeqNo = 1;
        $head->note = '';
        $list = new WaybillList();
        $list->gnum = $this->getGnum();
        $list->totalPackageNo = '';
        $list->logisticsNo = $this->data['express'];
        $list->invtNo = '';
        $list->note = '';
        $baseTransfer = new BaseTransfer();
        $baseTransfer->copCode = '510196552A';
        $baseTransfer->copName = '成都欧魅时尚科技有限责任公司';
        $baseTransfer->dxpMode = 'DXP';
        $baseTransfer->dxpId = 'DXPENT0000020478';
        $baseTransfer->note = '';
        $f3 = \Base::instance();
        $f3->set('waybill', [
            [
                'head' => (array) $head,
                'list' => (array) $list,
            ]
        ]);
        $f3->set('baseTransfer', (array) $baseTransfer);
        $xml = \Template::instance()->render('spec/_201809/waybill.xml', 'application/xml');
        $response = Request::post($this->url, ['xml' => $xml], Mime::FORM)->send();
        $result = $this->parseReturnXml($response->raw_body);
        $rLog = new SqlMapper('report_log');
        $rLog['type'] = __FUNCTION__;
        $rLog['content'] = $xml;
        $rLog['response'] = $response->raw_body;
        $rLog['status'] = $result['return_status'];
        $rLog['message'] = $result['return_info'];
        $rLog->save();
        $report = new SqlMapper('report_upload');
        $report->load(['id=?', $this->data['id']]);
        $report->copyfrom($this->data);
        $report['report_' . __FUNCTION__ . '_status'] = $rLog['status'];
        $report['report_' . __FUNCTION__ . '_log'] = $rLog['id'];
        $report->save();
        echo $result['return_status'], ':', $result['return_info'];
    }

    function __construct($data)
    {
        $this->url = \Base::instance()->get('REPORT_URL');
        $this->data = $data;
    }

    private function getGuid()
    {
        if (!$this->data['guid']) {
            $this->data['guid'] = Uuid::uuid1()->toString();
        }
        return $this->data['guid'];
    }

    private function getGnum()
    {
        if (!$this->data['gnum']) {
            $mapper = new SqlMapper('report_upload');
            $mapper->maxGnum = 'max(gnum)';
            $mapper->load();
            $this->data['gnum'] = $mapper['maxGnum'] + 1;
        }
        return $this->data['gnum'];
    }

    private function parseReturnXml($data)
    {
        try {
            $parser = xml_parser_create();
            xml_parse_into_struct($parser, $data, $result, $index);
            $returnInfo = $result[$index['RETURNINFO'][0]]['value'];
            $returnStatus = $result[$index['RETURNSTATUS'][0]]['value'];
            return [
                'return_info' => $returnInfo,
                'return_status' => $returnStatus,
            ];
        } catch (\Exception $e) {
            return [
                'return_info' => $e->getMessage(),
                'return_status' => $e->getCode(),
            ];
        }
    }
}

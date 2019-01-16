<?php

namespace app;

use app\common\AppBase;
use db\SqlMapper;

class Index extends AppBase
{
    private $defaultPageSize = 20;

    function get(\Base $f3, array $args)
    {
        $data = [];
        $filter = [];
        $option = [];
        $pageSize = $this->defaultPageSize;

        $mapper = new SqlMapper('report_upload');
        $pageCount = ceil($mapper->count($filter, $option) / $pageSize);

        $pageNo = $args['pageNo'] ?: 1;
        if ($pageNo >= 1 && $pageNo <= $pageCount){
            $option = array_merge(
                $option,
                [
                    'order' => 'create_time desc',
                ]
            );
            $page = $mapper->paginate($pageNo - 1, $pageSize, $filter, $option);
            foreach ($page['subset'] as $item) {
                $data[] = array_merge($item->cast(), [
                    'report_order_status' => $this->getStatus($item['report_order_log']),
                    'report_logistics_status' => $this->getStatus($item['report_logistics_status']),
                    'report_receipts_status' => $this->getStatus($item['report_receipts_status']),
                    'report_inventory_status' => $this->getStatus($item['report_inventory_status']),
                    'report_waybill_status' => $this->getStatus($item['report_waybill_status']),
                ]);
            }
        }
        $f3->set('pageNo', $pageNo);
        $f3->set('pageCount', $pageCount);
        $f3->set('data', $data);
        echo \Template::instance()->render('index.html');
    }

    function delete(\Base $f3, array $args)
    {
        $id = $args['id'];
        $f3->get('LOGGER')->write("request to delete $id");
        $mapper = new SqlMapper('report_upload');
        $mapper->erase(['id=?', $id]);
        echo 'success';
    }

    private function getStatus($id)
    {
        $rLog = new SqlMapper('report_log');
        $rLog->load(['id=?', $id], ['limit' => 1]);
        if (!$rLog->dry()) {
            return $rLog['status'] . ' ' . $rLog['message'];
        } else {
            return 0;
        }
    }
}

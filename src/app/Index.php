<?php

namespace app;

use app\common\AppBase;
use db\SqlMapper;

class Index extends AppBase
{
    private $defaultPageSize = 50;

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
                    'init' => $item['report_order_log'] + $item['report_logistics_log'] + $item['report_receipts_log'] + $item['report_inventory_log'] + $item['report_waybill_log']
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
}

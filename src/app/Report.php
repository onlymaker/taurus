<?php

namespace app;

use app\common\AppBase;
use db\Mysql;
use db\SqlMapper;
use spec\_201809\ReportStage;

class Report extends AppBase
{
    private $defaultPageSize = 50;

    function pagination(\Base $f3, array $args)
    {
        $data = [];
        $type = $args['type'];
        $status = ReportStage::all()[$type];
        $filter = ['status=?', $status];
        $option = [];
        $pageSize = $this->defaultPageSize;

        $mapper = new SqlMapper('report_upload');
        $pageCount = ceil($mapper->count($filter, $option) / $pageSize);

        $pageNo = $args['pageNo'] ?: 1;
        if ($pageNo >= 1 && $pageNo <= $pageCount){
            $db = Mysql::instance()->get();
            $reportTypeLog = 'report_' . $type . '_log';
            $reportTypeStatus = 'report_' . $type . '_status';
            $offset = $pageSize * ($pageNo - 1);
            $data = $db->exec(<<<SQL
            select u.*, l.content, l.response, l.status, l.message
            from report_upload u left join report_log l
            on u.$reportTypeLog=l.id
            where u.status='$status'
            order by $reportTypeStatus, create_time desc
            limit $pageSize offset $offset
            SQL);
            foreach ($data as &$item) {
                $item['response'] = $item['status'] . $item['message'];
            }
        }
        $f3->set('pageNo', $pageNo);
        $f3->set('pageCount', $pageCount);
        $f3->set('data', $data);
        $f3->set('type', $type);
        echo \Template::instance()->render('report.html');
    }

    function update(\Base $f3, array $args)
    {
        $id = $args['id'];
        $report = new SqlMapper('report_upload');
        $report->load(['id=?', $id], ['limit' => 1]);
        if (!$report->dry()) {
            switch ($f3->get('VERB')) {
                case 'GET':
                    $f3->set('data', json_decode($report['data']));
                    $f3->set('id', $id);
                    $f3->set('type', $f3->get('GET.type'));
                    $f3->set('pageNo', $f3->get('GET.pageNo'));
                    echo \Template::instance()->render('update.html');
                    break;
                case 'POST':
                    $report['data'] = json_encode($f3->get('POST'), JSON_UNESCAPED_UNICODE);
                    $report->save();
                    echo 'success';
                    break;
            }
        } else {
            $f3->error(404, "Report [$id] not found");
        }
    }

    function submit(\Base $f3, array $args)
    {
        $id = $args['id'];
        $report = new SqlMapper('report_upload');
        $report->load(['id=?', $id], ['limit' => 1]);
        if (!$report->dry()) {
            $type = $args['type'];
            $submit = new Submit($report);
            $submit->$type();
        }
    }

    function callback(\Base $f3)
    {
        ob_start();
        var_dump($_REQUEST);
        $f3->get('LOGGER')->write(ob_get_clean());
    }
}

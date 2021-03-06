<?php

namespace app;

use app\common\AppBase;
use db\Mysql;
use db\SqlMapper;

class Report extends AppBase
{
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

    function submit(\Base $f3)
    {
        $id = $f3->get('PARAMS.id');
        $db = Mysql::instance()->get();
        list($data) = $db->exec(<<<SQL
        select r.*, o.price, p.model, d.distribution_number as express
        from report_upload r
        left join order_item o on r.oid=o.trace_id
        left join prototype p on o.prototype_id=p.ID
        left join distribution d on o.distribution_id=d.ID
        where r.id=$id
        limit 1
        SQL);
        if (empty($data['model'])) {
            echo 'SKU not found';
        } else if (empty($data['express'])) {
            echo 'Express No not found';
        } else {
            (new Submit($data))->{$f3->get('PARAMS.type')}();
        }
    }
}

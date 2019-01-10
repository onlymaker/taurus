<?php

namespace app;

use app\common\AppHelper;
use db\SqlMapper;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Upload extends \Web
{
    use AppHelper;

    const MAX_ALLOWED_RECORD = 100;
    const ACCEPT_HEADER = ['trace_id'];
    private $logger;
    private $fileName;

    function beforeRoute(\Base $f3)
    {
        if (!$this->auth($f3)) {
            $f3->reroute($this->url('/Login'));
        } else {
            $this->logger = $f3->get('LOGGER');
            if ($f3->get('POST.name')) {
                $this->fileName = $f3->get('POST.name');
            } else {
                $start = strpos($f3->get('URI'), '/upload/');
                if ($start !== false) {
                    $start += strlen('/upload/');
                    $end = strpos($f3->get('URI'), '?');
                    if ($end === false) {
                        $end = strlen($f3->get('URI'));
                    }
                    $this->fileName = urldecode(substr($f3->get('URI'), $start, $end));
                }
            }
        }
    }

    function get(\Base $f3)
    {
        if (empty($this->fileName)) {
            echo \Template::instance()->render("upload.html");
        } else {
            $file = $f3->get('UPLOADS') . $this->fileName;
            if (is_file($file)) {
                $this->send($f3->get('UPLOADS') . $this->fileName);
            } else {
                $f3->error(404);
            }
        }
    }

    function upload()
    {
        $receive = $this->receive(null, true, false);
        if ($receive) {
            try {
                $spreadsheet = IOFactory::load(array_key_first($receive));
                $data = $spreadsheet->getSheet(0)->toArray();
                $head = $data[0];
                if ($this->accept($head)) {
                    unset($data[0]);
                    if (count($data) > self::MAX_ALLOWED_RECORD) {
                        echo 'Too many records! Max allowed [' . self::MAX_ALLOWED_RECORD . ']';
                    } else {
                        $mapper = new SqlMapper('report_upload');
                        foreach ($data as $item) {
                            $mapper->load(['oid=?', $item[0]]);
                            $mapper['data'] = json_encode(array_combine(self::ACCEPT_HEADER, $item));
                            $mapper->save();
                        }
                        echo 'success';
                    }
                } else {
                    echo 'Invalid format! Header must be [' . implode(',', self::ACCEPT_HEADER) . ']';
                }
            } catch (Exception $e) {
                ob_start();
                var_dump($e);
                $this->logger->write(ob_get_clean());
                echo $e->getTraceAsString();
            }
        }
    }

    private function accept($head)
    {
        foreach ($head as $key => $value) {
            if (strtolower($value) !== self::ACCEPT_HEADER[$key]) {
                return false;
            }
        }
        return true;
    }
}

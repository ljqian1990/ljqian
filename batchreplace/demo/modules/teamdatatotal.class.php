<?php

class PlgDemoTeamdatatotal implements IPlugin
{

    public static function init()
    {}

    public function exec()
    {}

    public function shutdown()
    {}

    public function importScript()
    {
        $file = Func::g('f');
        
        header("Content-type: application/x-javascript");
        echo file_get_contents(dirname(__FILE__) . '/../scripts/' . $file);
    }

    public function index()
    {
        $where = '1';
        
        $model = new CDemoteamdatatotal($this->site_id);
        $where .= " order by `id` desc";
        $total = $model->countBy($where);
        $size = 60;
        $page = new Page($total, $size);
        
        $list = $model->listBy($where, $size, $page->offset());
        
        $teamodel = new CDemobanner($this->site_id);
        $teamlist = $teamodel->listBy('`fish`="team"', 100, 0);
        $teamarr = array();
        if (! empty($teamlist)) {
            foreach ($teamlist as $value) {
                $teamarr[$value['id']] = $value;
            }
        }
        
        if (! empty($list)) {
            foreach ($list as &$value) {
                $value['teamicon'] = $teamarr[$value['teamid']]['img'];
            }
        }
        
        $this->view['curr_site_id'] = $this->site_id;
        $this->view['model'] = $_GET['model'];
        $this->view['list'] = $list;
        $this->view['navpage'] = $page->make(null, 5, 'bluepage');
    }

    public function del()
    {
        $id = Func::p('del_id');
        if (empty($id)) {
            $resp['returnflag'] = 'NO_ID';
            $resp['message'] = 'no id assign';
        } else {
            $model = new CDemoteamdatatotal($this->site_id);
            try {
                $model->deleteByIDs($id);
                $resp['returnflag'] = 'OK';
            } catch (Exception $e) {
                $resp['returnflag'] = 'DB_ERRER';
                $resp['message'] = $e->getMessage();
            }
        }
        
        echo json_encode($resp);
    }
}
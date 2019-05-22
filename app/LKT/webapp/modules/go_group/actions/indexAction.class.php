<?php

/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */
require_once(MO_LIB_DIR . '/DBAction.class.php');

class indexAction extends Action {

    public function getDefaultView() {
        
        $db = DBAction::getInstance();

        $request = $this->getContext()->getRequest();
        $status = trim($request->getParameter('status'));
//显示之前查询过期活动，改为未执行

        $sql001 = "select * from lkt_group_buy where is_show = 1 ";
        $res001 = $db -> select($sql001);
        if(!empty($res001)){
            foreach ($res001 as $k01 => $v01) {
               // $timee = date('Y-m-d H:i:s',$v -> endtime);
                if(time() > $v01 -> endtime){
                    $sql = 'update lkt_group_buy set is_show=0 where id="'.$v01 ->id.'"';
                    // print_r($sql);die;
                    $res = $db -> update($sql);
                }
            }
        }
        $and = '';
        $time = time();
        if($status == 1){
            $and .= "  and endtime > '$time' and is_show='0'";
        }else if($status == 2){
            $and .= " and starttime < '$time' and endtime > '$time' and is_show='1'";
        }else if($status == 3){
            $and .= " and endtime < '$time'";
        }
        // 查询插件表
        $condition = '';
        $sql = "select * from lkt_group_buy where 1=1  $and order by is_show desc ";
        // print_r($sql);die;
        $res = $db -> select($sql);
        foreach ($res as $k => $v) {
            $res[$k] -> time = date('Y-m-d H:i:s',$v -> starttime).' 至 '.date('Y-m-d H:i:s',$v -> endtime);
            $arr = explode(':', $v -> time_over);
            $res[$k] -> time_over = $arr[0].'小时'.$arr[1].'分钟';

            if(time() < $v -> starttime){//未开始
                $res[$k] -> code = 1;
            }else if(time() > $v -> starttime && time() < $v -> endtime){//进行中
                $res[$k] -> code = 2;
            }else if(time() > $v -> endtime){//结束
                $res[$k] -> code = 3;
            }
        }

        $showsql='select count(*) from lkt_group_buy where is_show=1 ';
        $showres = $db -> selectarray($showsql);
        list($showres) = $showres[0];

      // $this -> arraySort($res,'code','SORT_DESC');  //排序

        $request->setAttribute("is_show",$showres);
        $request->setAttribute("list",$res);

        if(isset($_GET['use']) && $_GET['use'] == 1){
            $this -> delgroup();
        }else if(isset($_GET['use']) && $_GET['use'] == 2){
            $this -> startgroup();
        }else if(isset($_GET['use']) && $_GET['use'] == 3){
            $this -> stopgroup();
        }
        $request->setAttribute("status",$status);

        return View :: INPUT;
    }
    public function delgroup() {
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $id = addslashes(trim($request->getParameter('id')));
        
        $sql = 'delete from lkt_group_buy where status="'.$id.'"';
        $res = $db -> delete($sql);
        $sql1 = 'delete from lkt_group_product where group_id="'.$id.'"';
        $res1 = $db -> delete($sql1);

        if($res > 0 && $res1 > 0){
            echo json_encode(array('status' => 1));exit;
        }
    }

    public function startgroup() {
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $id = addslashes(trim($request->getParameter('id')));
        
        $sql = 'update lkt_group_buy set is_show=1 where status="'.$id.'"';
        $res = $db -> update($sql);
        
        if($res > 0){
            echo json_encode(array('status' => 1));exit;
        }
    }
    
    public function stopgroup() {
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $id = addslashes(trim($request->getParameter('id')));
        
        $sql = 'update lkt_group_buy set is_show=0 where status="'.$id.'"';
        $res = $db -> update($sql);
        
        if($res > 0){
            echo json_encode(array('status' => 1));exit;
        }
    }
    /*
    $array:需要排序的数组
    $keys:需要根据某个key排序
    $sort:倒叙还是顺序
　　sort 对数组的值按照升序排列(rsort降序)，不保留原始的键

　　ksort 对数组的键按照升序排列(krsort降序) 保留键值关系

　　asort 对数组的值按照升序排列(arsort降序)，保留键值关系
    */
    public function arraySort($arrUsers,$keys,$sort) {
      
        $sort = array(
                'direction' => $sort, //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field'     => $keys,       //排序字段
        );
        $arrSort = array();
        foreach($arrUsers AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }

        if($sort['direction']){
           $newArr= array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arrUsers);
        }
// print_r($arrSort[$sort['field']]);die;
        return $newArr;
    }
    public function execute() {

    }

    public function getRequestMethods(){
        return Request :: NONE;
    }

}

?>
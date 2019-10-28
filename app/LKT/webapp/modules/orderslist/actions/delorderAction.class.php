<?php

/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */

require_once(MO_LIB_DIR . '/DBAction.class.php');


class delorderAction extends Action {

	public function getDefaultView() {

       return View :: INPUT;

	}


	public function execute(){
	   $db = DBAction::getInstance();
	   $request = $this->getContext()->getRequest();
	   $ids = trim($request->getParameter('ids'));
     $ids=rtrim($ids,',');
     $sql = "select o.id,o.drawid,o.sNo,o.ptcode,o.pid from lkt_order as o where o.id in ($ids)";
	   $res = $db -> select($sql);
	   $gcode = $db -> select("select group_id,ptstatus,sNo from lkt_group_open where ptstatus=1");
	   $group = array();
	   $draw = array();

	   if($gcode){
        foreach ($res as $k => $v) {   
         foreach ($gcode as $key => $value) {
                         if($value->sNo == $v -> sNo){
                           $group[] = $v -> sNo;
                           unset($res[$k]);
                         }
                      }             //过滤掉还没结束的拼团订单，和还没得到结果的抽奖订单

         }
     }
       
       $msg = '删除了 '.count($res).' 笔订单';
       if(!empty($group)){
          $msg .= ',已保留了 '.count($group).' 笔活动未结束的拼团订单';
       }
       
      foreach ($res as $key => $value) {
          $delo = $db -> delete("delete from lkt_order where sNo='$value->sNo'");
          $deld = $db -> delete("delete from lkt_order_details where r_sNo='$value->sNo'");
          $delg = $db -> delete("delete from lkt_group_open where group_id='$value->ptcode'");

      }

       	echo json_encode(array('code' => 1,'msg' => $msg));exit;

	}



	public function getRequestMethods(){

		return Request :: POST;

	}



}
?>
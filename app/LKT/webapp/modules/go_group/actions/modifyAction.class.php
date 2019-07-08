<?php

/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */
require_once(MO_LIB_DIR . '/DBAction.class.php');

class modifyAction extends Action {

    public function getDefaultView() {
        $db = DBAction::getInstance();

        $request = $this->getContext()->getRequest();
        $id = intval(trim($request->getParameter('id')));
        $set = addslashes(trim($request->getParameter('set')));
        if($set == 'msg'){
            $this -> setgroupmsg();
        }else if($set == 'msgsubmit'){
            $this -> msgsubmit();
        }else if($set == 'gpro'){
            $this -> modifypro();
        }else if($set == 'delpro'){
            $this -> delpro();
        }
        $status = trim($request->getParameter('status')) ? 1:0;
        // print_r($status);die;
        $request->setAttribute("status",$status);
        return View :: INPUT;
    }
    public function setgroupmsg() {
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $id = addslashes(trim($request->getParameter('id')));
        
        $sql = "select * from lkt_group_buy where status='$id'";
        $res = $db -> select($sql);
        
        $res = $res[0];
        list($hour,$minute) = explode(':', $res -> time_over);
        $res -> hour = $hour;
        $res -> minute = $minute;
        $res -> starttime = date('Y-m-d H:i:s',$res -> starttime);
        $res -> endtime = date('Y-m-d H:i:s',$res -> endtime);

        // 接收信息
 
        $sql = "select m.*,l.product_title as pro_name from (select p.id,p.product_id,p.group_id,c.img as image,p.group_price,p.member_price,c.price as market_price,c.name as attr_name,c.color,c.size as guige,p.classname ,c.attribute from lkt_group_product as p left join lkt_configure as c on p.attr_id=c.id where p.group_id='$id' order by p.classname) as m left join lkt_product_list as l on m.product_id=l.id";

        $res1 = $db -> select($sql);

        $len = count($res1);
                // 查询系统参数
        $sql1 = "select * from lkt_config where id = 1";
        $r_1 = $db->select($sql1);
        $uploadImg_domain = $r_1[0]->uploadImg_domain; // 图片上传域名
        $uploadImg = $r_1[0]->uploadImg; // 图片上传位置
        if(strpos($uploadImg,'../') === false){ // 判断字符串是否存在 ../
            $img = $uploadImg_domain . $uploadImg; // 图片路径
        }else{ // 不存在
            $img = $uploadImg_domain . substr($uploadImg,2); // 图片路径
        }
        foreach ($res1 as $k => $v) {
            $res1[$k] -> image = $img.$v -> image;
            $attribute_2 = unserialize($v->attribute); // 属性
            if(!empty($attribute_2)){
                foreach ($attribute_2 as $key => $value) {
                    $d[]= '<tt style="text-align: center;">'.$key.' : '. $value .'</tt><br/>';
                }
                if (!empty($d)) {
                    $dd = implode( ' ', $d);
                }else{
                    $dd = '';
                }
                $res1[$k]->attribute =  $dd;
                 unset($dd);
                 unset($d);
            } 
        } 
           // print_r($res1);die;
        $status = trim($request->getParameter('status')) ? 1:0;
        $request->setAttribute("status",$status);
        $request->setAttribute("list1",$res1);
        $request->setAttribute("len",$len);
        $request->setAttribute("list",$res);
    }

    public function msgsubmit() {
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        
        $id = addslashes(trim($request->getParameter('id')));
        $groupname = addslashes(trim($request->getParameter('groupname')));
        $peoplenum = addslashes(trim($request->getParameter('peoplenum')));
        $timehour = addslashes(trim($request->getParameter('timehour')));
        $timeminite = addslashes(trim($request->getParameter('timeminite')));
        $starttime = addslashes(trim($request->getParameter('starttime')));
        $overtime = addslashes(trim($request->getParameter('overtime')));
        $groupnum = addslashes(trim($request->getParameter('groupnum')));
        $productnum = addslashes(trim($request->getParameter('productnum')));
        $otype = addslashes(trim($request->getParameter('otype')));
        $gprice = $request->getParameter('gprice');//团长价格
        $mprice = $request->getParameter('mprice');//拼团价格
        $pieces = array_filter(explode(",", $gprice));
        $mpieces = array_filter(explode(",", $mprice));
        // print_r($pieces);die;
      if(!empty($pieces)){
        foreach ($pieces as $k => $v) {
            $r = explode(":", $v);
            $gsql = "update lkt_group_product set member_price=$r[1] where id=$r[0]";
            $gres = $db -> update($gsql);
        }
      }else{
         $gcode = 1;
      }
      if(!empty($mpieces)){
        foreach ($mpieces as $k => $v) {
            $r = explode(":", $v);
            $msql = "update lkt_group_product set group_price=$r[1] where id=$r[0]";
            $mres = $db -> update($msql);
         }
        }else{
            $mcode = 1;
        }

        
        if($overtime == '0') $overtime = date('Y-m-d H:i:s',strtotime('+1year'));
        $grouptime = $timehour.':'.$timeminite;
        $starttime = strtotime($starttime);
        $overtime = strtotime($overtime);

        $sql = "update lkt_group_buy set groupname='$groupname',man_num=$peoplenum,time_over='$grouptime',starttime='$starttime',endtime='$overtime',groupnum=$groupnum,productnum=$productnum,overtype='$otype' where id=$id";
        $res = $db -> update($sql);
        
        if($res >= 0){
            echo json_encode(array('code' => 1));exit;
        }
    }
    
    public function modifypro() {
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $gprice = (array)json_decode($request->getParameter('gprice'));
        $mprice = (array)json_decode($request->getParameter('mprice'));
      
      if(!empty($gprice)){
        foreach ($gprice as $k => $v) {
            $gsql = "update lkt_group_product set group_price=$v where id=$k";
            $gres = $db -> update($gsql);
        }
        if($gres >= 0){
            $gcode = 1;
        }
      }else{
         $gcode = 1;
      }
      if(!empty($mprice)){
        foreach ($mprice as $k => $v) {
            $msql = "update lkt_group_product set member_price=$v where id=$k";
            $mres = $db -> update($msql);
         }
         if($mres >= 0){
            $mcode = 1;
        }
        }else{
            $mcode = 1;
        }
        echo json_encode(array('gcode' => $gcode,'mcode' => $mcode));exit;
    }
    
    public function delpro() {
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $id = intval($request->getParameter('id'));
        
        $sql = "delete from lkt_group_product where id=$id";
        $res = $db -> delete($sql);
        
        if($res > 0){
            echo json_encode(array('code' => 1));exit;
        }
        
    }

    public function execute() {

    }

    public function getRequestMethods(){
        return Request :: NONE;
    }

}

?>
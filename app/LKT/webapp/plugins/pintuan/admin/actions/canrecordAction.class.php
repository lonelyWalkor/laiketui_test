<?php
/**
 * [Laike System] Copyright (c) 2017-2020 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */
require_once(MO_WEBAPP_DIR . "/plugins/PluginAction.class.php");

class canrecordAction extends PluginAction {
    public function getDefaultView() {
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $store_id = $this->getContext()->getStorage()->read('store_id'); // 商城id
        $ptcode = $request->getParameter('ptcode');
        $bstatus = $request->getParameter('bstatus');

        // 导出
        $pagesize = $request->getParameter('pagesize');
        $pagesize = $pagesize ? $pagesize : 10;
        // 每页显示多少条数据
        $page = $request->getParameter('page');

        if ($page) {
            $start = ($page - 1) * $pagesize;
        } else {
            $start = 0;
        }


        //查询是参团下的该用户下的拼团信息
       $sql = "SELECT u.user_name,d.p_name,d.p_id as goods_id,d.sid as attr_id,o.* 
                    from lkt_order as o
                    LEFT JOIN lkt_user as u on o.user_id = u.user_id
                    LEFT JOIN lkt_order_details as d on o.sNo = d.r_sNo
                    where  ptcode = '$ptcode'
                    ORDER BY add_time desc";
        $can_res = $db->select($sql);

        if($can_res){
            foreach ($can_res as $k => $v){
                $order_no = $v->sNo;
                $sql = "SELECT d.*,c.price
                from lkt_order_details as d
                LEFT JOIN lkt_configure as c on d.sid = c.id
                WHERE r_sNo = '$order_no'";
                $res1 = $db->select($sql);
                $v->can_price = $v->z_price;
                if($res1){
                    $v->price = $res1[0]->price;
                }
            }
        }

        $request->setAttribute("list", $can_res);
        $request->setAttribute("bstatus", $bstatus);
        return View :: INPUT;
    }


    public function execute() {

    }

    public function getRequestMethods(){
        return Request :: NONE;
    }

}

?>
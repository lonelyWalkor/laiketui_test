<?php

/**

 * [Laike System] Copyright (c) 2017-2020 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */

require_once(MO_WEBAPP_DIR."/plugins/PluginAction.class.php");
            
/**
 * 插件拼团测试类
 * 请求路径构造
 * http://localhost/open/app/LKT/index.php?module=api&action=pi&p=pintuan&c=groupbuy&m=grouphome&sort=asc&select=0&page=1
 */

class groupbuy extends PluginAction {

    public function grouphome(){
        $db = DBAction::getInstance();
        $sort = addslashes(trim($_REQUEST['sort'])); // 排序方式  1 asc 升序   0 desc 降序
        if($sort){
             $sort = SORT_ASC ; 
        }else{
             $sort = SORT_DESC ; 
        }

        $select = addslashes(trim($_REQUEST['select'])); //  选中的方式 0 默认  1 销量   2价格
        if($select == 0){
            $select = 'id'; 
            $sort = SORT_DESC ; 
        }elseif ($select == 1) {
             $select = 'sum'; 
        }else{
             $select = 'group_price'; 
        }
        
        $appConfig = $this->getAppInfo();
        $img = $appConfig['imageRootUrl'];
        
        $pagesize =  10;
        // 每页显示多少条数据
        $page = addslashes($_REQUEST['page']);
        if ($page) {
            $start = ($page - 1) * $pagesize;
        } else {
            $start = 0;
        }

        $sqltime =  "select min(g.attr_id) AS attr_id,min(g.id) AS id,min(g.group_title) AS group_title,min(g.product_id) AS product_id,min(g.group_level) AS group_level,min(g.group_data) AS group_data,min(p.product_title) AS product_title,min(p. STATUS) AS STATUS,min(p.product_class) AS product_class,min(p.imgurl) AS imgurl,min(c.price) AS price,min(c.num) AS num,g.group_id from lkt_group_product as g 
                left join lkt_product_list as p on g.product_id=p.id 
                left join lkt_configure as c on g.attr_id=c.id 
                where g.is_show=1 and g.g_status =2 and p.num >0 and p.status = 0 and g.recycle = 0 and c.recycle = 0
                group by g.group_id 
                order by min(g.id) desc limit  $start,$pagesize";
         $restime = $db -> select($sqltime);
         if(!empty($restime)){
                foreach ($restime as $key => $v) {
                    $sumsql = "select count(sNo) as sum from lkt_order where pid =$v->group_id";
                    $sumres = $db -> select($sumsql);
                    $group_data = unserialize($v -> group_data);
                    $group_level = unserialize($v -> group_level);
                    $min_man = 1;
                    $min_bili = 100;

                    foreach ($group_level as $k_ => $v_){
                        $biliArr = explode('~',$v_);
                                $min_man = $k_;   //几人团  

                        $nn= $db -> select("select open_discount from lkt_group_config ");
                        if($nn){
                            $open_discount = $nn[0]->open_discount;//是否开启团长优惠，开启就显示团长价格，未开启就显示拼团价1 是 0 否
                            if($open_discount == 1){//开启
                                   $min_price = $biliArr[1] * $v->price / 100;
                            }else{
                                   $min_price = $biliArr[0] * $v->price / 100;
                            }
                        }else{
                                   $min_price = $biliArr[0] * $v->price / 100;
                            }
                        
                    }
                    $v->min_man = $min_man;
                    $ve['id']=$v ->id;
                    $ve['attr_id']=$v ->attr_id;
                    $ve['product_id']=$v ->product_id;
                    $ve['group_price']=sprintf("%.2f", $min_price);
                    $ve['group_id']=$v->group_id;
                    $ve['image']=$img.$v ->imgurl;
                    $ve['market_price']=$v ->price;
                    $ve['pro_name']=$v ->product_title;
                    $ve['imgurl']=$img.$v->imgurl;
                    $ve['sum']= $sumres[0]->sum;
                    $re[]= $ve;
                    
                   
                }  
            array_multisort(array_column($re,$select),$sort,$re);//排序
            echo json_encode(array('code' => 1,'list' => $re));exit;
        }else{
            echo json_encode(array('code' => 0));exit;
        }
    }


    public function morepro() {
        $db = DBAction::getInstance();
        $page = addslashes($_REQUEST['page']);
        $groupid = addslashes(trim($_REQUEST['groupid']));
        
        $total = $page*8;
        $sql = "select min(attr_id) as attr_id,product_id,min(group_price) group_price,min(group_id) group_id,min(pro_name) pro_name,min(image) image,min(market_price) market_price from lkt_group_product where group_id='$groupid' group by product_id limit $total,8";
        $res = $db -> select($sql);
        
        $appConfig = $this->getAppInfo();
        $img = $appConfig['imageRootUrl'];

     	if(!empty($res)){
	        $groupid = $res[0] -> group_id;
	        $sqlsum = "select sum(m.num) as sum,m.p_id from (select o.num,d.p_id from lkt_order as o left join lkt_order_details as d on o.sNo=d.r_sNo where o.pid='$groupid' and o.status>0) as m group by m.p_id"; 
	        $ressum = $db -> select($sqlsum);
        
	        foreach ($res as $k => $v) {
	            $v -> sum = 0;
	            $res[$k] = $v;
	            $res[$k] -> imgurl = $img.$v -> image;
	            if(!empty($ressum)){
	                foreach ($ressum as $ke => $val) {
	                    if($val -> p_id == $v -> product_id){
	                       $res[$k] -> sum = $val -> sum;
	                    }
	                }
	            }  
	        }     
        echo json_encode(array('code' => 1,'list' => $res));exit;
     }else{
        echo json_encode(array('code' => 1,'list' => false));exit;
     }
    } 

}

?>
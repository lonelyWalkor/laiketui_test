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

    //默认执行方法
    public function execute() {
        $request = $this->getContext()->getRequest();
        $test = addslashes(trim($request->getParameter('oh'))); //调用哪个方法
        echo $test;
        return ;
    }

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


    public function getgoodsdetail(){

        $db = DBAction::getInstance();
        $gid = addslashes(trim($_REQUEST['gid']));
        $group_id = addslashes(trim($_REQUEST['group_id']));
        $user_id = addslashes(trim($_REQUEST['userid']));
        
        $appConfig = $this->getAppInfo();
        $img = $appConfig['imageRootUrl'];
        $uploadImg_domain =  $appConfig['uploadImgUrl'];

        $guigesql = "select a.group_title as pro_name, a.*,b.* from lkt_group_product as a,lkt_product_list as b where a.group_id = $group_id and a.product_id = b.id";
        $guigeres = $db -> select($guigesql);
        list($guigeres) = $guigeres;
        if($guigeres){
            $content = $guigeres -> content;
        }else{

            $content = '';
        }

        $str = $uploadImg_domain;
        $search = '~^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?~i';
        $url = $uploadImg_domain;
        $url = trim($url);
        preg_match_all($search, $url ,$matches);
        $newa = $matches[1][0].$matches[3][0];

        $guigeres -> content = preg_replace('/(<img.+?src=")(.*?)/',"$1$newa$2", $content);
        $imgsql = 'select product_url from lkt_product_img where product_id='.$gid;
        $imgres = $db -> select($imgsql);
        
        $imgarr = [];
        if($guigeres -> imgurl){
            $im = $guigeres -> imgurl;
        }else{
            $im = $guigeres -> imgurl;
        }
        if(!empty($imgres)){
            foreach ($imgres as $k => $v) {
                $imgarr[$k] = $img.$v -> product_url;
            }

            $guigeres -> image = $img.$im; 
            $guigeres -> images = $imgarr;
        }else{

            $guigeres -> image = $img.$im; 
            $imgarr[0] = $img.$im;
            $guigeres -> images = $imgarr;
        }
        $contsql = 'select * from lkt_group_product where group_id="'.$group_id.'"';
        $contres = $db -> select($contsql);
        if($contres){
            $cfg = unserialize($contres[0]->group_data);
                    $contres[0]->start_time = $cfg->starttime;                   
                    if ($cfg -> endtime =='changqi') {
                    $dt=$cfg -> starttime;
                    $dt=date('Y-m-d H:i:s',strtotime("$dt+1year"));
                    $end_time = strtotime($dt);                
                }else{
                    $end_time = strtotime($cfg -> endtime);                   
                }
                    $contres[0]->endtime = $end_time;
        }
        list($contres) = $contres;
        
        $commodityAttr = [];
            $sql_size = "select g.*,p.attribute,p.num,p.price,p.yprice,p.img,p.id from lkt_group_product as g left join lkt_configure as p on g.attr_id=p.id where g.product_id = '$gid' and group_id='$group_id'";
            $r_size = $db->select($sql_size);
            $array_price = [];
            $array_yprice = [];
            $skuBeanList = [];
            $attrList = [];
            if ($r_size) {

                $attrList = [];
                $a = 0;
                $attr = [];
                
                foreach ($r_size as $key => $value) {
                    $array_price[$key] = $value->price;
                    $array_yprice[$key] = $value->yprice;
                    $attribute = unserialize($value->attribute);
                    $attnum = 0;
                    $arrayName = [];
                    foreach ($attribute as $k => $v) {
                        if(!in_array($k, $arrayName)){
                            array_push($arrayName, $k);
                            $kkk = $attnum++;
                            $attrList[$kkk] = array('attrName' => $k,'attrType' => '1','id' => md5($k),'attr' => [],'all'=>[]);
                        }
                    }
                    $group_level = unserialize($value->group_level);
                    $min_man = 1;
                    $min_bili = 100;
                    foreach ($group_level as $k_ => $v_){
                        $biliArr = explode('~',$v_);
                            if($biliArr[0] < $min_bili){
                                $min_man = $k_;
                                $min_bili = $biliArr[0];

                            }
                    }
                    $min_man = $min_man;//几人团
                    
                }
                
                $guigeres -> man_num = $min_man;
                $guigeres -> market_price = $r_size[0]->price;//原价
                
                foreach ($r_size as $key => $value) {
                    $attribute = unserialize($value->attribute);
                    $attributes = [];
                    $name = '';
                    foreach ($attribute as $k => $v) {
                       $attributes[] = array('attributeId' => md5($k), 'attributeValId' => md5($v));
                       $name .= $v;
                    }
                    $cimgurl = $img.$value->img;
                     
                $nn= $db -> select("select open_discount,rule from lkt_group_config where id =1");
                if($nn){
                    $open_discount = $nn[0]->open_discount;//是否开启团长优惠，开启就显示团长价格，未开启就显示拼团价
                    if($open_discount == 1){//开启
                        $openmoney =($biliArr[1]*$r_size[0]->price)/100;//开团 人价格
                        $guigeres -> member_price = sprintf("%.2f", $openmoney);//团长价
                        $min_price = $biliArr[1] * $value->price / 100;//参团价格
                        $openmoney = sprintf("%.2f", $min_price);
                        $canmoney =$biliArr[1]*$r_size[0]->price/100;//参团 人价格
                        $guigeres -> group_price = sprintf("%.2f", $canmoney);;//拼团价
                    }else{
                        $openmoney =($biliArr[0]*$r_size[0]->price)/100;//开团 人价格
                        $guigeres -> member_price = sprintf("%.2f", $openmoney);//团长价
                        $min_price = $biliArr[0] * $value->price / 100;//参团价格
                        $openmoney = sprintf("%.2f", $min_price);
                        $canmoney =$biliArr[0]*$r_size[0]->price/100;//参团 人价格
                        $guigeres -> group_price = sprintf("%.2f", $canmoney);;//拼团价
                    }
                     $guigeres -> rule = $nn[0]->rule;
                }else{
                        $openmoney =($biliArr[0]*$r_size[0]->price)/100;//开团 人价格
                        $guigeres -> member_price = sprintf("%.2f", $openmoney);//团长价
                        $min_price = $biliArr[0] * $value->price / 100;//参团价格
                        $openmoney = sprintf("%.2f", $min_price);
                        $canmoney =$biliArr[0]*$r_size[0]->price/100;//参团 人价格
                        $guigeres -> group_price = sprintf("%.2f", $canmoney);;//拼团价
                         $guigeres -> rule = '';
                }
                    
                    $skuBeanList[$key] = array('name' => $name,'imgurl' => $cimgurl,'cid' => $value->id,'member_price' => $openmoney,'price' => $value->price,'count' => $value->num,'attributes' => $attributes);
                    for ($i=0; $i < count($attrList); $i++) {
                        $attr = $attrList[$i]['attr'];
                        $all = $attrList[$i]['all'];
                        foreach ($attribute as $k => $v) {
                            if($attrList[$i]['attrName'] == $k){
                                $attr_array = array('attributeId' => md5($k), 'id' =>md5($v), 'attributeValue' => $v, 'enable' => false, 'select' => false);

                                if(empty($attr)){
                                    array_push($attr, $attr_array);
                                    array_push($all, $v);
                                }else{
                                    if(!in_array($v, $all)){
                                        array_push($attr, $attr_array);
                                        array_push($all, $v);
                                    }
                                }

                            }
                        }
                        $attrList[$i]['all'] =$all;
                        $attrList[$i]['attr'] =$attr;
                    }
                    
                }

            }
        
        //查询此商品评价记录
        $sql_c = "select a.id,a.add_time,a.content,a.CommentType,a.size,m.user_name,m.headimgurl from lkt_comments AS a LEFT JOIN lkt_user AS m ON a.uid = m.user_id where a.pid = '$gid' and m.wx_id != '' limit 2";
            $r_c = $db->select($sql_c);
            $arr=[];
        if(!empty($r_c)){
            foreach ($r_c as $key => $value) {
                $va = (array)$value;
                $va['time'] = substr($va['add_time'],0,10);
                //-------------2018-05-03  修改  作用:返回评论图片
                $comments_id = $va['id'];
                $comments_sql = "select comments_url from lkt_comments_img where comments_id = '$comments_id' ";
                $comment_res = $db->select($comments_sql);
                $va['images'] ='';
                if($comment_res){
                    $va['images'] = $comment_res;
                    $array_c = [];
                    foreach ($comment_res as $kc => $vc) {
                       $url = $vc->comments_url;
                       $array_c[$kc] = array('url' =>$img.$url);
                    }
                    $va['images'] = $array_c;
                }
                //-------------2018-07-27  修改
                $ad_sql = "select content from lkt_reply_comments where cid = '$comments_id' and uid = 'admin' ";
                $ad_res = $db->select($ad_sql);
                if($ad_res){
                    $reply_admin = $ad_res[0]->content;
                }else{
                    $reply_admin = '';
                }

                $va['reply'] = $reply_admin;
                $obj = (object)$va;
                $arr[$key] = $obj;
            }
         }
           if(!empty($r_c)){
                $goodsql = "select count(*) as num from lkt_comments where pid='$gid' and CommentType='GOOD'";
                $goodnum = $db -> select($goodsql);
                $com_num = array();
                $com_num['good'] = $goodnum[0] -> num;
                $badsql = "select count(*) as num from lkt_comments where pid='$gid' and CommentType='BAD'";
                $badnum = $db -> select($badsql);
                $com_num['bad'] = $badnum[0] -> num;
                $notbadsql = "select count(*) as num from lkt_comments where pid='$gid' and CommentType='NOTBAD'";
                $notbadnum = $db -> select($notbadsql);
                $com_num['notbad'] = $notbadnum[0] -> num;
            }else{
                $com_num = array('bad'=>0,'good'=>0,'notbad'=>0);
            }

        $sql_kt = "select g.id, g.ptcode,g.ptnumber,g.endtime,u.user_name,u.headimgurl,g.group_id from lkt_group_open as g left join lkt_user as u on g.uid=u.wx_id where g.group_id='$group_id' and g.ptgoods_id=$gid and g.ptstatus=1 ";
        $res_kt = $db -> select($sql_kt);
        $groupList = [];
        if(!empty($res_kt)){
            foreach ($res_kt as $key => $value) {
                $idddd = $value->id;
                $ptcode = $value->ptcode;
                if( $guigeres -> man_num - $value->ptnumber <1){
                    up_su_status($db,$idddd,$ptcode);//过期修改拼团成功订单
                    unset($value);
                }else{
                    $res_kt[$key] -> leftTime = strtotime($value -> endtime) - time();
                    if(strtotime($value -> endtime) - time() > 0){
                        array_push($groupList, $res_kt[$key]);
                    }else{
                         up_status($db,$idddd,$ptcode);//过期修改拼团订单

                    }
                }
            }
        }
        $plugsql = "select status from lkt_plug_ins where type = 0 and software_id = 3 and code='PT'";
        $plugopen = $db -> select($plugsql);
        $plugopen = !empty($plugopen)?$plugopen[0] -> status:0;
        
        $share = array('friends' => true, 'friend' => false);

        echo json_encode(array('control' =>$contres,'share'=>$share,'detail' => $guigeres,'attrList'=>$attrList,'skuBeanList'=>$skuBeanList,'comments'=>$arr,'comnum' => $com_num,'groupList' => $groupList,'isplug' => $plugopen));exit;
    } 

}

?>
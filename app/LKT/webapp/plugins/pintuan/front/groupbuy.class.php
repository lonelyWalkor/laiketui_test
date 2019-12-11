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


    public function getcomment(){
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $pid = intval($request->getParameter('pid'));
        $page = intval($request->getParameter('page'));
        $checked = intval($request->getParameter('checked'));

        $page = $page*8;
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
            $condition = '';
            switch ($checked) {
                case 1:
                    $condition .= " and a.CommentType='GOOD'";
                    break;
                case 2:
                    $condition .= " and a.CommentType='NOTBAD'";
                    break;
                case 3:
                    $condition .= " and a.CommentType='BAD'";
                    break;
                default:
                    $condition = '';
                    break;
            }
        
        
        //查询此商品评价记录
        $sql_c = "select a.id,a.add_time,a.content,a.CommentType,a.size,m.user_name,m.headimgurl from lkt_comments AS a LEFT JOIN lkt_user AS m ON a.uid = m.user_id where a.pid = '$pid'".$condition." limit $page,8";

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
              
        echo json_encode(array('comment' =>$arr));exit;
      }else{
        echo json_encode(array('comment' =>false));exit;
      }
    }
    
    public function getformid(){
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $uid = addslashes(trim($request->getParameter('userid')));
        $formid = addslashes(trim($request->getParameter('from_id')));
        
        $fromidsql = "select count(*) as have from lkt_user_fromid where open_id='$uid'";
        $fromres = $db -> select($fromidsql);
        $fromres = intval($fromres[0] -> have);
        $lifetime = date('Y-m-d H:i:s',time() + 7*24*3600);
        if($formid != 'the formId is a mock one'){
            if($fromres < 8){           
                $addsql = "insert into lkt_user_fromid(open_id,fromid,lifetime) values('$uid','$formid','$lifetime')";
                $addres = $db -> insert($addsql);
            }else{
                return false;
            }
        }

    }

    public function payfor(){

        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $uid = addslashes(trim($request->getParameter('uid')));
        $oid = addslashes(trim($request->getParameter('oid')));
        $num = addslashes(trim($request->getParameter('num')));
        $groupid = addslashes(trim($request->getParameter('groupid')));
        $sizeid = intval(trim($request->getParameter('sizeid')));

         // 根据用户id,查询开团数
        $r_a1 = $db->select('select * from lkt_group_config');
        if($r_a1){
           $dat['open_num'] =$r_a1[0]->open_num;//开团人数 
           $dat['can_num'] =$r_a1[0]->can_num;//参团人数 
        }else{
            $dat['open_num'] =10;//设置的开团数 
           $dat['can_num'] =10;//设置的参团数 
        }
        $r_a2 = $db->selectrow("select id from lkt_group_open where uid='$uid' and ptstatus = 1 ");
        $r_a3 = $db->selectrow("select id from lkt_order where uid=(select user_id from lkt_user where wx_id='$uid' and ptstatus = 1 ");
        if($r_a2){
            $dat['num'] = $r_a2;
        }else{
             $dat['num'] = 0;//自己已开团数
        }
        if($r_a2){
            $dat['cnum'] = $r_a3;
        }else{
             $dat['cnum'] = 0;//自己已参团数
        }
        // 根据用户id,查询收货地址
        $sql_a = 'select * from lkt_user_address where uid=(select user_id from lkt_user where wx_id="'.$uid.'") and is_default = 1';
        $r_a = $db->select($sql_a);
        if($r_a){
            $arr['addemt']=0; // 有收货地址
            // 根据用户id、默认地址,查询收货地址信息
             $arr['adds'] =!empty($r_a)?(array)$r_a['0']:array(); // 收货地址
        }else{
            $arr['addemt']=1; // 没有收货地址
            $arr['adds'] = ''; // 收货地址
        }
        
        $attrsql = "select m.*,l.product_title as pro_name ,l.freight from (select c.attribute,c.img as image,g.*,c.num,c.price from lkt_group_product as g left join lkt_configure as c on g.attr_id=c.id where g.group_id='$groupid' and g.attr_id=$sizeid) as m left join lkt_product_list as l on m.product_id=l.id";
        $attrres = $db -> select($attrsql);

        list($attrres) = $attrres;

        //团长价与参团价
          $group_level = unserialize($attrres -> group_level);
          foreach ($group_level as $k_ => $v_){
                $biliArr = explode('~',$v_);
                $man_num = $k_;

                $nn= $db -> select("select open_discount from lkt_group_config where id =1");
                if($nn){
                    $open_discount = $nn[0]->open_discount;//是否开启团长优惠，开启就显示团长价格，未开启就显示拼团价
                    if($open_discount == 1){//开启
                            $openmoney =($biliArr[1]*$attrres->price)/100;//开团 人价格
                    }else{                
                            $openmoney =($biliArr[0]*$attrres->price)/100;//开团 人价格
                    }
                }else{                
                            $openmoney =($biliArr[0]*$attrres->price)/100;//开团 人价格
                    }
                

            }
               
                $canmoney =$biliArr[0]*$attrres->price/100;//参团 人价格
                $attrres->group_price = sprintf("%.2f", $canmoney);  //拼团价格
                $attrres->member_price =sprintf("%.2f", $openmoney);//团长价格
        //计算运费
        $yunfei = 0;
        $yunfei = $yunfei + $this->freight1($attrres->freight,$num,$arr['adds'],$db);
        $attribute = unserialize($attrres->attribute);
        $size = '';
        foreach ($attribute as $ka => $va) {
            $size .= ' '.$va;
        }
        $attrres->size = $size;

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
        $attrres -> image = $img.$attrres -> image;

        $moneysql = 'select user_id,user_name,money from lkt_user where wx_id="'.$uid.'" ';
        $moneyres = $db -> select($moneysql);
        
        if(!empty($moneyres)){
            list($moneyres) = $moneyres;
            $money = $moneyres -> money;
            $user_name = $moneyres -> user_name;
            $userid = $moneyres -> user_id;
        }
        $selfsql = "select count(*) as isset from lkt_order where user_id='$userid' and ptcode='$oid'";
        $is_self = $db -> select($selfsql);
        $is_self = $is_self[0] -> isset;
        
        $groupsql = "select * from lkt_group_config ";
        $groupres = $db -> select($groupsql);
        if(!empty($groupres)){
            $groupres[0]->groupnum=$groupres[0]->can_num;//可同时进行的参团数
            $groupres[0]->man_num=$man_num;//拼团人数
            $groupres[0]->status=$groupid;//活动编号
            $groupres[0]->time_over=$groupres[0]->group_time.":0";//活动时限
            list($groupres) = $groupres;
        }else{

            $groupres[0] = new stdClass();
            $groupres[0]->groupnum = '10';//可同时进行的参团数
            $groupres[0]->man_num=$man_num;//拼团人数
            $groupres[0]->status=$groupid;//活动编号
            $groupres[0]->time_over="1:0";//活动时限
            list($groupres) = $groupres;
        }

        $havesql = "select count(*) as have from lkt_order where pid='$groupid' and user_id='$userid' and ptstatus=1";
        $haveres = $db -> select($havesql);
        
        if(!empty($haveres)){
           $have = $haveres[0] -> have;
        }
        $attrres -> have = $have;
        
        echo json_encode(array('is_add' => $arr['addemt'],'buymsg' => $arr['adds'],'proattr' => $attrres,'money' => $money,'user_name' => $user_name,'groupres' => $groupres,'isself' => $is_self,'yunfei' => $yunfei,'dat'=>$dat));exit;

    }


    public function creatgroup(){
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $uid = addslashes(trim($request->getParameter('uid')));
        $form_id = addslashes(trim($request->getParameter('fromid')));
        $pro_id = intval(trim($request->getParameter('pro_id')));//商品ID
        $man_num =  intval(trim($request->getParameter('man_num')));
        $time_over = addslashes(trim($request->getParameter('time_over')));
        $sizeid = intval(trim($request->getParameter('sizeid')));//规格id
        $groupid = addslashes(trim($request->getParameter('groupid')));
        $pro_name = addslashes(trim($request->getParameter('ptgoods_name')));
        $price = (float)(trim($request->getParameter('price')));
        $y_price = (float)(trim($request->getParameter('d_price')));
        $name = addslashes(trim($request->getParameter('name')));
        $sheng = intval(trim($request->getParameter('sheng')));
        $shi = intval(trim($request->getParameter('shi')));
        $quyu = intval(trim($request->getParameter('quyu')));
        $address = addslashes(trim($request->getParameter('address')));
        $tel = addslashes(trim($request->getParameter('tel')));
        $lack = intval(trim($request->getParameter('lack')));
        $buy_num = intval(trim($request->getParameter('num')));
        $paytype = addslashes(trim($request->getParameter('paytype')));
        $trade_no = addslashes(trim($request->getParameter('trade_no')));
        $status = intval(trim($request->getParameter('status')));
        $ordstatus = $status == 1?9:0;
        $db->begin();
        $num = substr(time(),5).mt_rand(10000,99999);
        $num1 = 'KT'.$num;
        $sql_user = "select count(id) as a from lkt_order where sNo = '$num1'";
        $ordernum = 'PT'.mt_rand(10000,99999).date('Ymd').substr(time(),5);
        $n = $db->select($sql_user);
        $aa = $n[0]->a ;
        do {
          $group_num = 'KT'.$num;
        } while ($aa > 0);

        $creattime = date('Y-m-d H:i:s');
        $time_over = explode(':', $time_over);
        $time_over = date('Y-m-d H:i:s',$time_over[0]*3600 + $time_over[1]*60 + time());
        //运费
        $freight = $this ->friends($pro_id,$sheng,$buy_num);
        if($freight == -1){
            $freight  = 0;
        }
        $pro_size = $db -> select("select attribute from lkt_configure where id=$sizeid");
       //写入配置
        $attribute = unserialize($pro_size[0]->attribute);
        $size = '';
        foreach ($attribute as $ka => $va) {
              $size .= $va.' ';
        }

        $istsql1 = "insert into lkt_group_open(uid,ptgoods_id,ptcode,ptnumber,addtime,endtime,ptstatus,group_id,sNo) values('$uid',$pro_id,'$group_num',1,'$creattime','$time_over',$status,'$groupid','$ordernum')";
        $res1 = $db -> insert($istsql1);

        $nu = $db -> update("update lkt_product_list set volume=volume+$buy_num,num=num-$buy_num where id='$pro_id'");
        $nu11 = $db -> update("update lkt_configure set num=num-$buy_num where id='$sizeid'");//改变库存和销量

        if($res1 < 1){
            $db->rollback();
            echo json_encode(array('code' => 0,'sql'=>$istsql1));exit;
        }
        

        $user_id = $db -> select("select user_id from lkt_user where wx_id='$uid' ");
        $uid = $user_id[0] -> user_id;
        $istsql2 = "insert into lkt_order(user_id,name,mobile,num,z_price,sNo,sheng,shi,xian,address,pay,add_time,status,otype,ptcode,pid,ptstatus,trade_no,source) values('$uid','$name','$tel',$buy_num,$price,'$ordernum',$sheng,$shi,$quyu,'$address','$paytype','$creattime',$ordstatus,'pt','$group_num','$groupid',$status,'$trade_no','1')";
        $res2 = $db -> insert($istsql2);
        if($res2 < 1){
            $db->rollback();
            echo json_encode(array('code' => 0,'sql'=>$istsql2));exit;
        }
        

        $istsql3 = "insert into lkt_order_details(user_id,p_id,p_name,p_price,num,r_sNo,add_time,r_status,size,sid,freight) values('$uid',$pro_id,'$pro_name',$y_price,$buy_num,'$ordernum','$creattime','$ordstatus','$size',$sizeid,'$freight')";
        $res3 = $db -> insert($istsql3);
        if($res3 < 1){
            $db->rollback();
            echo json_encode(array('code' => 0,'sql'=>$istsql3));exit;
        }
               
        
        $idres = $db -> select("select id from lkt_order where sNo='$ordernum'");
        if(!empty($idres)) $idres = $idres[0] -> id;
        if($res1 > 0 && $res2 > 0 && $res3 > 0){
            $db->commit();
            echo json_encode(array('order' => $ordernum,'gcode' => $group_num,'group_num' => $group_num,'id' => $idres,'code' => 1));exit;
        }else{
            $db->rollback();
            echo json_encode(array('code' => 0));exit;
        }
        
       
    } 


    public function can_group(){
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $oid = addslashes(trim($request->getParameter('oid')));
        $groupid = addslashes(trim($request->getParameter('groupid')));
        $gid = addslashes(trim($request->getParameter('gid')));
        $user_id = addslashes(trim($request->getParameter('userid')));
        
        $appConfig = $this->getAppInfo();
        $img = $appConfig['imageRootUrl'];

        $groupmsg = $db -> select("select * from lkt_group_open where ptcode='$oid'");
        if($user_id && $user_id!='undefined'){
            $userid = $db -> select("select user_id from lkt_user where wx_id='$user_id' ");
            $userid = $userid[0] -> user_id;
            $isrecd = $db -> select("select count(*) as recd from lkt_order where ptcode='$oid' and pid='$groupid' and user_id='$userid'");
            $recd = $isrecd[0] -> recd;
        }else{
            $recd=0;
        }

        
        if($recd > 0){
            $sql = "select m.*,d.p_name,d.p_price,d.sid from (select k.*,p.name,p.num,p.sheng,p.shi,p.xian,p.address,p.mobile,p.status from lkt_group_open as k right join lkt_order as p on k.ptcode=p.ptcode where p.ptcode='$oid' and p.user_id='$userid') as m left join lkt_order_details as d on m.sNo=d.r_sNo";
            $res = $db -> select($sql);
       
        if($res){
            $ptgoods_id = $res[0]->ptgoods_id;
            $aa = $db -> select("select group_level from lkt_group_product where group_id='$groupid' and product_id=$ptgoods_id");
            $res = $res[0];
            $image = $db -> select("select img,yprice,price from lkt_configure where id=$res->sid");
            $group_level = unserialize($aa[0] -> group_level);
            foreach ($group_level as $k_ => $v_){
                        $biliArr = explode('~',$v_);
                        $nn1= $db -> select("select open_discount from lkt_group_config where id =1");
                        if($nn1){
                            $open_discount = $nn1[0]->open_discount;//是否开启团长优惠，开启就显示团长价格，未开启就显示拼团价
                            if($open_discount == 1){//开启
                                    $min_price = $biliArr[1] * $image[0] -> price / 100;//团长价格
                                    $res -> p_price = sprintf("%.2f", $min_price);
                            }else{                
                                    $min_price = $biliArr[0] * $image[0] -> price / 100;//团长价格
                                    $res -> p_price = sprintf("%.2f", $min_price);
                            }
                        }else{                
                                    $min_price = $biliArr[0] * $image[0] -> price / 100;//团长价格
                                    $res -> p_price = sprintf("%.2f", $min_price);
                        }

            }
                
            $res -> img = $img.$image[0] -> img;
            $res -> yprice = $image[0] -> price;
            $res -> p_price = sprintf("%.2f", $min_price);
            }else{
                $res = (object)array();
            }
            $res -> isSelf = true;
         
         }else{
            $res = $groupmsg[0];
            $goodsql = "select z.*,l.product_title as pro_name from (select m.*,c.num,c.img as image,c.yprice,c.price from (select * from lkt_group_product where group_id='$groupid' and product_id=$res->ptgoods_id) as m left join lkt_configure as c on m.attr_id=c.id) as z left join lkt_product_list as l on z.product_id=l.id";
            $goods = $db -> select($goodsql);
            $res -> p_name = $goods[0] -> pro_name;

            $res -> yprice = $goods[0] -> price;
            $res -> price = $goods[0] -> price;
            $res -> img = $img.$goods[0] -> image;
            $res -> p_num = $goods[0] -> num;
            $res -> isSelf = false;

            $group_level = unserialize($goods[0]-> group_level);
            foreach ($group_level as $k_ => $v_){
                            $biliArr = explode('~',$v_);

                            $nn1= $db -> select("select open_discount from lkt_group_config where id =1");
                            if($nn1){
                                $open_discount = $nn1[0]->open_discount;//是否开启团长优惠，开启就显示团长价格，未开启就显示拼团价
                                if($open_discount == 1){//开启
                                        $openmoney =($biliArr[1]*$res -> price)/100;//开团 人价格
                                        $res -> p_price = sprintf("%.2f", $openmoney);
                                }else{                
                                        $openmoney =($biliArr[0]*$res -> price)/100;//开团 人价格
                                        $res -> p_price = sprintf("%.2f", $openmoney);
                                }
                            }else{                
                                        $openmoney =($biliArr[0]*$res -> price)/100;//开团 人价格
                                        $res -> p_price = sprintf("%.2f", $openmoney);
                                }
                            
            }
            $min_price = $biliArr[0] * $res -> price / 100;//拼团价格
            $res -> gprice = sprintf("%.2f", $min_price);
         }

        $memsql = "select i.user_id,u.headimgurl from lkt_order as i left join lkt_user as u on i.user_id=u.user_id where i.ptcode='$oid' and i.pid='$groupid'  order by i.id asc";
        $groupmember = $db -> select($memsql);
 
        $man_num = $db -> select("select * from lkt_group_config where id='1'");//用户参团可购买产品数
        $is_overdue = is_overdue($db,$groupid);//查询该拼团活动是否过期 1 过期，0 没有
        if(isset($man_num[0]) && $is_overdue==0){
                $nn= $man_num[0] -> open_num + $man_num[0] -> can_num;
               $res -> productnum = $nn;//用户参团可购买产品数
               $res -> groupmember = $groupmember;
               $sumsql = "select count(m.sNo) as sum from (select o.sNo from lkt_order as o left join lkt_order_details as d on o.sNo=d.r_sNo where d.p_id='$res->ptgoods_id') as m";
               $sumres = $db -> select($sumsql);
               
               if(!empty($sumres)) $res -> sum = $sumres[0] -> sum;
                switch ($res -> ptstatus) {
                    case 1:
                        $res -> groupStatus = '拼团中';
                        break;
                    case 2:
                        $res -> groupStatus = '拼团成功';
                        break;
                    case 3:
                        $res -> groupStatus = '拼团失败';
                        break;
                    default:
                        $res -> groupStatus = '未付款';
                        break;
                }
                
                $res -> leftTime = strtotime($res -> endtime) - time();    
                $sql_size = "select g.*,p.attribute,p.num,p.img,p.yprice,p.price,p.id from lkt_group_product as g left join lkt_configure as p on g.attr_id=p.id where g.product_id = '$gid' and group_id='$groupid'";
                $r_size = $db->select($sql_size);
                $skuBeanList = [];
                $attrList = [];
                    if ($r_size) {
                        $attrList = [];
                        $a = 0;
                        $attr = [];
                        foreach ($r_size as $key => $value) {
                            $tuanzhang = $biliArr[1] * $value->price / 100;//团长价
                            $value->member_price = sprintf("%.2f", $tuanzhang);
                            $pin = $biliArr[0] * $value->price / 100;//拼团价
                            $value->price = sprintf("%.2f", $pin);
                            
                            $array_price[$key] = $value->price;
                            $array_yprice[$key] = $value->price;
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
                        }
                        foreach ($r_size as $key => $value) {
                            $attribute = unserialize($value->attribute);
                            $attributes = [];
                            $name = '';
                            foreach ($attribute as $k => $v) {
                               $attributes[] = array('attributeId' => md5($k), 'attributeValId' => md5($v));
                               $name .= $v;
                            }
                            $cimgurl = $img.$value->img;
                            $skuBeanList[$key] = array('name' => $name,'imgurl' => $cimgurl,'cid' => $value->id,'price' => $value->price,'count' => $value->num,'attributes' => $attributes);
                            
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

                $plugsql = "select status from lkt_plug_ins where type = 0 and software_id = 3 and code = 'PT'";
                $plugopen = $db -> select($plugsql);
                $plugopen = !empty($plugopen)?$plugopen[0] -> status:0;

                $prostatus = $db -> select("select status from lkt_product_list where id='$gid'");
                $prostatus = $prostatus[0] -> status;
                
                echo json_encode(array('groupmsg' => $res,'groupMember' => $groupmember,'skuBeanList' => $skuBeanList,'attrList' => $attrList,'isplug' => $plugopen,'prostatus' => $prostatus));exit;
        }else{
           echo json_encode(array('groupmsg' => 0,'groupMember' => 0,'skuBeanList' => 0,'attrList' => 0,'isplug' => 0));exit; 
        }


    } 

}

?>
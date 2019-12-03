<?php

/**

 * [Laike System] Copyright (c) 2017-2020 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */
require_once('BaseAction.class.php');

class noticeAction extends BaseAction {

    public function index(){
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $id = addslashes(trim($request->getParameter('id')));
        if($id){
           $con= 'id = '.$id;
        }else{
            $con= '1=1 order by time desc';
        }
        // 获取信息
        $sql = "select * from lkt_config where id = 1";
        $r_1 = $db->select($sql);
        $uploadImg_domain = $r_1[0]->uploadImg_domain; // 图片上传域名
        $uploadImg = $r_1[0]->uploadImg; // 图片上传位置
        if(strpos($uploadImg,'../') === false){ // 判断字符串是否存在 ../
            $img = $uploadImg_domain . $uploadImg; // 图片路径
        }else{ // 不存在
            $img = $uploadImg_domain . substr($uploadImg,2); // 图片路径
        }
        
        $sql = "select * from lkt_set_notice where $con";
        $r = $db->select($sql);
        if(!empty($r)){
            foreach ($r as $k => $v) {
                if($v->img_url == ''){
                    $v->img_url = 'nopic.jpg';
                }else{
                     $v->img_url = $img.$v->img_url;
                }

                $newa = substr($uploadImg_domain,0,strrpos($uploadImg_domain,'/'));
                if($newa == 'http:/' || $newa == 'https:/' ){
                    $newa = $uploadImg_domain;
                }
                $new_content = preg_replace('/(<img.+?src=")(.*?)/',"$1$newa$2", $v->detail);
                $r[$k]->detail= $new_content;

            }
        }
       
        echo json_encode(array('notice'=>$r));
        exit();
        return;
    }
  
}
?>
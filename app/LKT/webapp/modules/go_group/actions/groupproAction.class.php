<?php

/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */
require_once(MO_LIB_DIR . '/DBAction.class.php');

class groupproAction extends Action {

    public function getDefaultView() {
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $store_id = $this->getContext()->getStorage()->read('store_id');

        // 接收信息
        $id = addslashes(trim($request->getParameter('id'))); // 插件id
        $sql = "select m.*,l.product_title as pro_name from (select p.id,p.product_id,p.group_id,c.img as image,p.group_price,p.member_price,c.price as market_price,c.name as attr_name,c.color,c.size as guige,p.classname from lkt_group_product as p left join lkt_configure as c on p.attr_id=c.id where p.store_id = '$store_id' and p.group_id='$id' order by p.classname) as m left join lkt_product_list as l on m.product_id=l.id";

        $res = $db -> select($sql);
        $len = count($res);

        foreach ($res as $k => $v) {
            $res[$k] -> image = $this -> getimgpath($v -> image);
        }
        $status = trim($request->getParameter('status')) ? 1:0;
        $request->setAttribute("status",$status);
        $request->setAttribute("list",$res);
        $request->setAttribute("len",$len);
        return View :: INPUT;
    }

    public function getimgpath($img){
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $store_id = $this->getContext()->getStorage()->read('store_id');

        $sql = "select * from lkt_files_record where store_id = '$store_id' and image_name='$img'";
        $res = $db -> select($sql);

        $serverURL = $this->getContext()->getStorage()->read('serverURL');
        $uploadImg = $this->getContext()->getStorage()->read('uploadImg');


        if(!empty($res)){
            $store_id = $res[0] -> store_id;
            $store_type = $res[0] -> store_type;
            $upload_mode = $res[0] -> upload_mode;
            if($upload_mode == 2){
                $image = $serverURL['OSS'] . '/' . $store_id . '/' . $store_type . '/' .$img;
            }else if($upload_mode == 3){
                $image = $serverURL['tenxun'] . '/' . $store_id . '/' . $store_type . '/' .$img;
            }else if($upload_mode == 4){
                $image = $serverURL['qiniu'] . '/' . $store_id . '/' . $store_type . '/' .$img;
            }else{
                $image = $uploadImg . $img;
            }
        }else{
            $image = $uploadImg . $img;
        }

        return $image;
    }

    public function execute(){


    }

    public function getRequestMethods(){
        return Request :: POST;
    }

}

?>
<?php
/**

 * [Laike System] Copyright (c) 2017-2020 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */

require_once(MO_LIB_DIR . '/DBAction.class.php');
require_once(MO_LIB_DIR . '/ShowPager.class.php');
require_once(MO_LIB_DIR . '/Tools.class.php');

/*
功能：插件请求基础类
*/
class PluginAction {

    public $context = null;

    function __construct($context)
    {
        $this->context = $context;
    }

    //默认执行方法
    public function execute() {
        echo "hello";
        return ;
    }

    //封装请求参数方法
    public function getParameter($string){
        return $_REQUEST[$string];
    }
    
    //获取系统配置信息
    public function getAppInfo(){
        $db = DBAction::getInstance();
        $img = "";
        $sql = "select * from lkt_config where id = 1";
        $r_1 = $db->select($sql);
        $uploadImg_domain = $r_1[0]->uploadImg_domain; // 图片上传域名
        $uploadImg = $r_1[0]->uploadImg; // 图片上传位置
        if(strpos($uploadImg,'../') === false){ // 判断字符串是否存在 ../
            $img = $uploadImg_domain . $uploadImg; // 图片路径
        }else{ // 不存在
            $img = $uploadImg_domain . substr($uploadImg,2); // 图片路径
        }

        $title = $r_1[0]->company;
        $logo = $img.$r_1[0]->logo;
        $appid = $r_1[0]->appid; // 小程序唯一标识
        $appsecret = $r_1[0]->appsecret; // 小程序的 app secret
        
        $array = array();
        $array['imageRootUrl'] = $img;
        $array['appName'] = $title;
        $array['logo'] = $logo;
        $array['uploadImgUrl'] = $uploadImg_domain;
        $array['appid'] = $appid;
        $array['appsecret'] = $appsecret;

        return $array;
    }

    public function getContext ()
    {

        return $this->context;

    }

    
}

?>
<?php
$path =dirname(__FILE__);
$newa = substr($path,0,strrpos($path,'install'));
define('WEB_PATH',str_replace('\\',"/",$newa));
class step4Action extends Action
{

    public function execute ()


    {


        echo "string";


        return View::INPUT;


    }


    public function getDefaultView ()
    {

        $step = $this -> getContext() -> getStorage() -> read('step');

        if ($step < 3 || !isset($step)) {

            header("Content-type: text/html;charset=utf-8");

            echo "<script language='javascript'>" . "alert('安装失败，请重新开始！');" . "location.href='index.php?action=step1';</script>";

            return;

        }

        file_put_contents(WEB_PATH . 'data/install.lock',"laiketui".date("Y-m-d h:i:s",time()));


        return View::INPUT;


    }


    public function getRequestMethods ()


    {


        return Request::NONE;


    }

}
?>
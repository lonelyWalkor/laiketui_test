<?php
class step2Action extends Action
{
public function execute ()

    {

    }

    public function getDefaultView ()



    {



        $type = $this->getContext()->getStorage()->read('error');



    	if(!empty($type)){ 



			     header("Content-type: text/html;charset=utf-8");



					echo"<script language='javascript'>" .



						"alert('环境检测没有通过，请调整环境后重试！');" .



						"location.href='index.php?action=step1';</script>";



					return ;



			}



		    $num = 3;



        $url = $this->curPageURL();



        $request = $this->getContext()->getRequest();



		$request->setAttribute('url', isset($url) ? $url : '');



        $this->getContext()->getStorage()->write('step',$num);



        return View::INPUT;



    }







    public function getRequestMethods ()



    {

        return Request::NONE;



    }







    function curPageURL() 

    {
      $http_type =((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https' : 'http';

      
      $hh=$_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);
      $uri = $http_type.'://'.$_SERVER['HTTP_HOST'].substr($hh, 0, strrpos($hh, '/')+1);
      $pageURL = substr($uri,0,strrpos(substr($uri,0,strrpos($uri,"/")),"/"));
      return $pageURL;
    }







}







?>
 <?php
/**
 * [Laike System] Copyright (c) 2017-2020 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */
require_once(MO_LIB_DIR . '/DBAction.class.php');
require_once(MO_LIB_DIR . '/Tools.class.php');
class IndexAction extends Action {

	public function getDefaultView() {
		$request = $this->getContext()->getRequest();
		$this->getContext()->getUser()->setAuthenticated(false);
		$request->setAttribute("name",$request->getParameter("name"));
        $request->setAttribute("password",$request->getParameter("password"));
		return View :: INPUT;
	}

	function get_client_ip($type = 0,$client=true) 
	{
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($client){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // 防止IP伪造
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

	public function execute(){
		$db=DBAction::getInstance();
		// 获取输入的信息 
		$request = $this->getContext()->getRequest();
		// 获取输入的用户名
		$name = addslashes(trim($request->getParameter("login")));
		// 获取输入的密码
		$password = md5($request->getParameter("pwd"));

        if($name == '' || $password == ''){
			$this ->jump('index.php?module=Login','登录失败！');
        };
        
        // 查询表lkt_admin里的用户名,密码,权限.根据输入的用户名在数据库存在，而且输入的密码要跟数据库密码一样
		$sql = "select id,name,password,admin_type,permission,status from lkt_admin where BINARY name = '$name' and password = '$password' ";
		$result = $db->select($sql);
		if(!$result){
			// 没有查询到匹配值就在lkt_record表里添加一组数据
			$sql="insert into lkt_record (user_id,event) values ('$name','登录密码错误') ";
			$r= $db -> update($sql);
			$this ->jump('index.php?module=Login','登录失败！');
		}

		// 获取管理员id、管理员类型和管理员许可信息
		$admin_id = $result[0]->name;
		$admin_type = $result[0]->admin_type;
		$admin_permission = unserialize($result[0]->permission);
        $status = $result[0]->status;
        if($status == 1){
            $this ->jump('index.php?module=Login','登录失败！');
        }
        // 生成session_id
        $access_token = session_id();
        $ip = $this->get_client_ip();
        $aid = $result[0]->id;
        //修改token
        $sql = "update lkt_admin set token = '$access_token',ip = '$ip' where id = '$aid'";
        $db -> update($sql);
        // 在lkt_record表里添加一条消息
		$sql="insert into lkt_record (user_id,event) values ('$name','登录成功')";
		$r= $db -> update($sql);
        $db->admin_record($name,' 登录成功 ',0);

        // 设置该用户为登录状态
		$this->getContext()->getUser()->setAuthenticated(true);
		// 将数据存储起来
		$sql = "select * from lkt_config where id = '1' ";
        $r2 = $db->select($sql);
		$uploadImg = "";
		if($r2){
			 $uploadImg = $r2[0]->uploadImg; // 图片上传位置
			 
		}
		$this->getContext()->getStorage()->write('uploadImg',$uploadImg);
        $login_time = time();

        $this->getContext()->getStorage()->write('login_time',$login_time);
		$this->getContext()->getStorage()->write('admin_id',$admin_id);
		$this->getContext()->getStorage()->write('admin_type',$admin_type);
		$this->getContext()->getStorage()->write('admin_permission',$admin_permission);
		// 登录成功后跳转地址
		$this ->jump('index.php?module=AdminLogin');
        return;
    }

	public function getRequestMethods(){
		return Request :: POST;
	}

	function jump($url,$msg=null){
		if($msg){
			echo "<script type='text/javascript'>" .
					"alert('$msg');" .
					"location.href='$url';</script>";
		}else{
			echo "<script type='text/javascript'>" .
					"location.href='$url';</script>";
		}
		exit;
	}

	
}

?>
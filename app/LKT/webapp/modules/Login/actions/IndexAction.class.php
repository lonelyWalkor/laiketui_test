<?php
/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */

require_once (MO_LIB_DIR . '/DBAction.class.php');

class IndexAction extends Action {

	// getContext() 检索当前应用程序上下文。

	// getRequest() 检索请求。

	// getUser() 检索用户。

	// setAuthenticated() 设置该用户的身份验证状态。

	// setAttribute() 设置一个属性。

	// strtolower() 把所有字符转换为小写

	// getParameter() 获取参数

	// unserialize() 从已存储的表示中创建 PHP 的值

	// getStorage() 检索存储。

	// write() 将数据写入此存储。

	// redirect() 将请求重定向到另一个URL。

	// isset() 检测变量是否设置

	// trim() 去除字符串首尾处的空白字符

	public function getDefaultView() {

		$request = $this -> getContext() -> getRequest();
// print_r(123);die;
		$this -> getContext() -> getUser() -> setAuthenticated(false);

		$request -> setAttribute("name", $request -> getParameter("name"));

		$request -> setAttribute("password", $request -> getParameter("password"));

		return View::INPUT;

	}

	public function execute() {

		$db = DBAction::getInstance();
// print_r(456);die;
		// 获取输入的信息

		$request = $this -> getContext() -> getRequest();

		$m = $request -> getParameter("m");

		if ($m == 'verify_num') {

			$this -> verify_num();

			exit ;

		}

		// 获取输入的用户名

		$name = addslashes(trim(strtolower($request -> getParameter("login"))));

		// 获取输入的密码

		$password = md5(strtolower($request -> getParameter("pwd")));
// print_r($name);
// print_r($password);die;
		if ($name == '' || $password == '') {
			 header("Content-type:text/html;charset=utf-8");
            echo "<script type='text/javascript'>" .
                "alert('用户名或密码不能为空');" .
                "</script>";
            return $this->getDefaultView();

			// echo 0;

			// exit ;

		};

		// 查询表lkt_admin里的用户名,密码,权限.根据输入的用户名在数据库存在，而且输入的密码要跟数据库密码一样

		$sql = "select name,password,admin_type,permission from lkt_admin where " . "name = '$name' and password = '$password'";

		$result = $db -> select($sql);

		if ($result == false) {

			// 没有查询到匹配值就在lkt_record表里添加一组数据

			$sql = "insert into lkt_record (user_id,event) values ('$name','登录密码错误') ";

			$r = $db -> update($sql);
			header("Content-type:text/html;charset=utf-8");
            echo "<script type='text/javascript'>" .
                "alert('登录密码错误');" .
                "</script>";
            return $this->getDefaultView();

			// echo 0;

			// exit ;

		}

		// 获取管理员id、管理员类型和管理员许可信息

		$admin_id = $result[0] -> name;

		$admin_type = $result[0] -> admin_type;

		$admin_permission = unserialize($result[0] -> permission);

		// 在lkt_record表里添加一条消息

		$sql = "insert into lkt_record (user_id,event) values ('$name','登录成功')";

		$r = $db -> update($sql);

		// 设置该用户为登录状态

		$this -> getContext() -> getUser() -> setAuthenticated(true);

		// 将数据存储起来

		$this -> getContext() -> getStorage() -> write('admin_id', $admin_id);

		$this -> getContext() -> getStorage() -> write('admin_type', $admin_type);

		$this -> getContext() -> getStorage() -> write('admin_permission', $admin_permission);

		// 登录成功后跳转地址
		  header("Content-type:text/html;charset=utf-8");
        echo "<script type='text/javascript'>" .
            "alert('登录成功');" .
            "location.href='index.php?module=AdminLogin';</script>";
        return;
		// echo $r;
		// exit ;

	}

	public function getRequestMethods() {

		return Request::POST;

	}

	public function verify_num() {

		$db = DBAction::getInstance();
		// print_r(789);die;

		$request = $this -> getContext() -> getRequest();

		$verify_num = strtolower($request -> getParameter("user_num"));

		$time = date("Y-m-d H:i:s");

		$sql = "select user_id,email from lkt_customer where user_id = '$verify_num' and status =0 and end_date > '$time'";

		$result = $db -> select($sql);

		if (empty($result)) {

			echo json_encode(array('status' => 0, 'err' => '信息错误!'));

			exit ;

		} else {

			echo json_encode(array('status' => 1, 'succ' => md5($result[0] -> email)));

			exit ;

		}

		exit ;

	}

}
?>
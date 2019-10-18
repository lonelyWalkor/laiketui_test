<?php
//转换到HTML
function converToHTML($a){
	$a = HTMLSpecialChars($a);
	$a = str_replace(" ","&nbsp;",$a);
	$a = nl2br($a);
	return $a;
}

//生成凭证号
function confirmNum($pre){
	return $pre . date("Ymd") . 
		(date("i")*100+date("s")*10+floor(microtime()*1000)) ;
}

//格式化钱币格式
function moneyFormat($m){
	$i = strpos($m, '.');
	if ($i === false) {
		return "{$m}.00";
	} else {
		return substr("{$m}00", 0, $i + 3);
	}
}

function jump($url,$msg=null){
	//header("Content-type:text/html;charset=utf-8");
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


?>
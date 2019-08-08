<?php
/*
    时间2019年01月16日
    修改人：周文
    主要功能：处理图片上传，路径获取,图片删除
    备注：api-JSON
     */
require_once(MO_LIB_DIR . '/DBAction.class.php');

class ServerPath{
    public static $serverURL = null;
    public static $imgpath = null;

    // 后台获取图片路径
    public static function getimgpath($img,$store_id = 1){
        $db = DBAction::getInstance();
        $store_id = isset($_GET['store_id'])?$_GET['store_id']:$store_id;

        if($img == ''){
            return $img;
        }
        $sql = "select * from lkt_files_record where store_id = '$store_id' and image_name='$img'";
        $res = $db -> select($sql);
        if($res){
            $store_type = $res[0] -> store_type;
            $upload_mode = $res[0]->upload_mode;
            if($upload_mode == 1){
                $sql1 = "select attr,attrvalue from lkt_upload_set where type = '本地'";
                $r1 = $db->select($sql1);
                foreach ($r1 as $k => $v){
                    if($v->attr == 'uploadImg_domain'){
                        $uploadImg_domain = $v->attrvalue; // 图片上传域名
                    }else if($v->attr == 'uploadImg'){
                        $uploadImg = $v->attrvalue; // 图片上传位置
                    }
                }
                if($store_id){
                    $uploadImg = $uploadImg.'image_'.$store_id.'/';
                }else{
                    $uploadImg = $uploadImg.'image_0/';
                }
                if(strpos($uploadImg,'./') === false){ // 判断字符串是否存在 ../
                    $imgpath = $uploadImg_domain . $uploadImg; // 图片路径
                }else{ // 不存在
                    $imgpath = $uploadImg_domain . substr($uploadImg,1); // 图片路径
                }
                if(self::$imgpath == null){
                    self::$imgpath = $imgpath;
                }
                $image = self::$imgpath . $img;
            }else{
                $serverURL = array(
                    'OSS' => 'https://',
                    'qiniu' => 'https://',
                    'tenxun' => 'https://'
                );
                if(self::$serverURL == null){
                    $serversql = "select * from lkt_upload_set where attr in ('Bucket','Endpoint') and type != '本地'";
                    $serverres = $db -> select($serversql);
                    if(!empty($serverres)) {
                        foreach ($serverres as $k => $v) {
                            if($v -> type == '阿里云OSS'){
                                if($v -> attr == 'Bucket'){
                                    $OSS['Bucket'] = $v -> attrvalue;
                                }
                                if($v -> attr == 'Endpoint'){
                                    $OSS['Endpoint'] = $v -> attrvalue;
                                }
                            }
                            if($v -> type == '七牛云'){
                                if($v -> attr == 'Bucket'){
                                    $qiniu['Bucket'] = $v -> attrvalue;
                                }
                                if($v -> attr == 'Endpoint'){
                                    $qiniu['Endpoint'] = $v -> attrvalue;
                                }
                            }
                            if($v -> type == '腾讯云'){
                                if($v -> attr == 'Bucket'){
                                    $tenxun['Bucket'] = $v -> attrvalue;
                                }
                                if($v -> attr == 'Endpoint'){
                                    $tenxun['Endpoint'] = $v -> attrvalue;
                                }
                            }
                        }
                        $serverURL['OSS'] .= $OSS['Bucket'] . '.' . $OSS['Endpoint'];
                        $serverURL['qiniu'] .= $qiniu['Bucket'] . '.' . $qiniu['Endpoint'];
                        $serverURL['tenxun'] .= $tenxun['Bucket'] . '.' . $tenxun['Endpoint'];
                    }
                    self::$serverURL = $serverURL;
                }

                if($upload_mode == 2){
                    $image = self::$serverURL['OSS'] . '/' . $store_id . '/' . $store_type . '/' .$img;
                }else if($upload_mode == 3){
                    $image = self::$serverURL['tenxun'] . '/' . $store_id . '/' . $store_type . '/' .$img;
                }else if($upload_mode == 4){
                    $image = self::$serverURL['qiniu'] . '/' . $store_id . '/' . $store_type . '/' .$img;
                }
            }
        }else{
            $image = self::$imgpath . $img;
        }

        return $image;
    }

    /**
     * @param $imgBase64 获取图片base64字符串
     * return String '返回阿里云上的图片路径
     */
    public static function base64_OSSupload($imgBase64,$store_id,$store_type)
    {
        $db = DBAction::getInstance();
        $store_type = $store_type != ''?$store_type:'0';
        $dir = $store_id . '/' . $store_type . '/';
        require_once(MO_LIB_DIR . '/aliyun-oss-php-sdk-2.3.0/samples/Object.php');
        $ossClient = Common::getOssClient();
        $common = new Common();
        // 图片上传
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/',$imgBase64,$res)) {
            //获取图片类型
            $type = $res[2];
            //图片名字
            $fileName = time().mt_rand(1,1000).$type;
            // 临时文件
            $tmpfname = tempnam("/tmp/", "FOO");
            $handle = fopen($tmpfname, "w");
            if (fwrite($handle,base64_decode(str_replace($res[1],'', $imgBase64)))){
                $dir = $store_id . '/' . $store_type . '/';
                $path = $dir . $fileName;
                try {
                    $ossClient->uploadFile($common->bucket, $path, "/tmp/".$tmpfname);
                } catch (OssException $e) {
                    printf(__FUNCTION__ . ": FAILED\n");
                    printf($e->getMessage() . "\n");
                    return false;
                }
                fclose($handle);
                unlink($tmpfname);
                $fsql = " INSERT INTO `lkt_files_record` ( `store_id`, `store_type`, `group`, `upload_mode`, `image_name`) VALUES ('$store_id', '$store_type', '1', '2', '$fileName') ";
                $res = $db->insert($fsql);
                return $fileName;
            }
        }
    }

    //本地上传
    public static function base64_image_contents($base64_image_content,$path){
        $db = DBAction::getInstance();
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2];
            $new_file = $path."/";
            if(!file_exists($new_file)){
                mkdir($new_file, 0700);
            }
            $imgname = time() . mt_rand(1, 1000);
            $new_file =  $path.'/'.$imgname.".{$type}";
            $storage_path =$imgname.".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                $fsql = " INSERT INTO `lkt_files_record` ( `store_id`, `store_type`, `group`, `upload_mode`, `image_name`) VALUES ('$store_id', '$store_type', '1', '1', '$storage_path') ";
                $res = $db->insert($fsql);
                return $storage_path;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 阿里云上传普通文件
     * @param $store_id String  商户id
     * @param $store_type String  平台类型
     * @param $returnpath bool  是否返回全路径
     * return String '返回阿里云上的图片路径
     */
    public static function file_OSSupload($store_id,$store_type = 'app',$returnpath = false)
    {
        $db = DBAction::getInstance();
        $store_type = $store_type != ''?$store_type:'app';
        $name = array_keys($_FILES);
        if(!empty($name) && count($name) == 1){
            list($name) = $name;
            //获取图片类型
            $type = str_replace('image/', '.', $_FILES[$name]['type']);
            $tmpname = $_FILES[$name]['tmp_name'];
        }
        require_once(MO_LIB_DIR . '/aliyun-oss-php-sdk-2.3.0/samples/Object.php');
        $ossClient = Common::getOssClient();
        $common = new Common();
        $imgtype = array('.png','.jpg','.jpeg','.gif');
        if(!in_array($type,$imgtype)){
            return false;
        }
        //图片名字
        $fileName = time().mt_rand(1,1000).$type;
        $dir = $store_id . '/' . $store_type . '/';
        $path = $dir . $fileName;

        try {
            $ossClient->uploadFile($common->bucket, $path, $tmpname);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return false;
        }
        $url = 'https://' . $common->bucket . '.' . $common->endpoint . '/' . $path;
        $fsql = " INSERT INTO `lkt_files_record` ( `store_id`, `store_type`, `group`, `upload_mode`, `image_name`) VALUES ('$store_id', '$store_type', '1', '2', '$fileName') ";
        $res = $db->insert($fsql);

        if($returnpath == false){
            return $fileName;
        }else{
            return $url;
        }


    }

    /**
     * @param $store_id String  商户id
     * @param $uploadImg String  图片上传位置
     * @param $uploadImg_domain String  服务器域名
     * @param $returnpath bool  是否返回全路径
     * return String '返回阿里云上的图片路径
     */
    public static function file_upload($store_id,$uploadImg,$uploadImg_domain,$store_type = 'app',$returnpath = false)
    {
        $db = DBAction::getInstance();

        $name = array_keys($_FILES);
        if(!empty($name) && count($name) == 1){
            list($name) = $name;
            //获取图片类型
            $type = str_replace('image/', '.', $_FILES[$name]['type']);
            $tmpname = $_FILES[$name]['tmp_name'];
        }

        $imgtype = array('.png','.jpg','.jpeg','.gif');
        if(!in_array($type,$imgtype)){
            return false;
        }
        $sql1 = "select attr,attrvalue from lkt_upload_set where type = '本地'";
        $r1 = $db->select($sql1);
        foreach ($r1 as $k => $v){
            if($v->attr == 'uploadImg_domain'){
                $uploadImg_domain = $v->attrvalue; // 图片上传域名
            }else if($v->attr == 'uploadImg'){
                $uploadImg = $v->attrvalue; // 图片上传位置
            }
        }
        if($store_id){
            $uploadImg = $uploadImg.'image_'.$store_id.'/';
        }else{
            $uploadImg = $uploadImg.'image_0/';
        }

        if(is_dir($uploadImg) == ''){ // 如果文件不存在
            mkdir($uploadImg); // 创建文件
        }
        if(strpos($uploadImg,'./') === false){ // 判断字符串是否存在 ../
            $img = $uploadImg_domain . $uploadImg; // 图片路径
        }else{
            $img = $uploadImg_domain . substr($uploadImg,1); // 图片路径
        }

        //图片名字
        $fileName = time().mt_rand(1,1000).$type;
        move_uploaded_file($tmpname,$uploadImg.$fileName);
        $url = $img.$fileName;

        $fsql = " INSERT INTO `lkt_files_record` ( `store_id`, `store_type`, `group`, `upload_mode`, `image_name`) VALUES ('$store_id', '$store_type', '1', '1', '$fileName') ";
        $res = $db->insert($fsql);

        if($returnpath == false){
            return $fileName;
        }else{
            return $url;
        }

    }

    public function removeimg($img,$store_id = 1){
        $db = DBAction::getInstance();
        //$request = $this->getContext()->getRequest();
        $store_id = isset($_GET['store_id'])?$_GET['store_id']:$store_id;

        $sql = "select * from lkt_files_record where store_id = '$store_id' and image_name='$img'";
        $res = $db -> select($sql);
        if(!empty($res)){
            $store_type = $res[0] -> store_type;
            $upload_mode = $res[0] -> upload_mode;
        }

    }
}


?>

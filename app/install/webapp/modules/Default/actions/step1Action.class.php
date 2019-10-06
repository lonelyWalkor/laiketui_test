<?php
define('REAL_PATH', realpath('./') . '/');
define('WEB_PATH',dirname(dirname(__FILE__)));
class step1Action extends Action
{

    public function execute ()
    {
        // we don't need any data here because this action doesn't serve
        // any request methods, so the processing skips directly to the view
    }

    public function getDefaultView ()
    {
        unset($_SESSION);
        session_destroy();
        session_start();

        //设置目录权限
        @chmod(WEB_PATH, 0777);
        $request = $this->getContext()->getRequest();
        $type = true;
        $_SESSION['install_step'] = '1';
        // $_SESSION['install_error'] = false;
        //环境检测

        $config = array(


            array('操作系统', '不限制', '类Unix', PHP_OS, 'success'),


            array('PHP版本', '5.4', '5.4+', PHP_VERSION, 'success'),


            array('MYSQL版本', '5.5', '5.6+', '未知', 'success'), //PHP5.5不支持mysql版本检测


            array('附件上传', '不限制', '2M+', '未知', 'success'),


            array('GD库', '2.0', '2.0+', '未知', 'success'),


            array('磁盘空间', '80M', '不限制', '未知', 'success'),


        );





        //PHP版本检测


        if(PHP_VERSION < 5.4){


            $config[1][4] = 'error';


            $_SESSION['install_error'] = $type;


        }





        //数据库检测


        // if(function_exists('mysql_get_server_info')){


        //     $config[2][3] = mysql_get_server_info();


        // if($config[2][3] < $config[2][1]){


        //     $config[2][4] = 'error';


        //         $_SESSION['install_error'] = $type;


        //     }


        // }





        //附件上传检测


        if(@ini_get('file_uploads'))


            $config[3][3] = ini_get('upload_max_filesize');


        //GD库检测


        $tmp = function_exists('gd_info') ? gd_info() : array();


        if(empty($tmp['GD Version'])){


            $config[4][3] = '未安装';


            $config[4][4] = 'error';


            $_SESSION['install_error'] = $type;


        } else {


            $config[4][3] = $tmp['GD Version'];


        }


        unset($tmp);





        //磁盘空间检测


        if(function_exists('disk_free_space')) {


            $config[5][3] = floor(disk_free_space(REAL_PATH) / (1024*1024)).'M';


        }


        $request->setAttribute("functions",$config);


        


            //目录文件读写检测


        unset($config);


            //函数检测


        $config = array(


            array('dir',  '[√]可写', 'success', '../upfile/data'),


            array('dir',  '[√]可写', 'success', '../upfile'),


            // array('dir',  '[√]可写', 'success', './uploads/Editor'),


            array('dir',  '[√]可写', 'success', './style'),


            array('dir',  '[√]可写', 'success', './static'),


        );

        foreach ($config as &$val) {


            if('dir' == $val[0]){


                if(!is_writable(REAL_PATH . $val[3])) {


                    if(is_dir($val[3])) {


                        $val[1] = '<span>[√]可读</span>';


                        $val[2] = 'error';


                        // $_SESSION['install_error'] = $type;


                    } else {


                        $val[1] = '<span style="color:#f30">[×]不存在</span>';


                        $val[2] = 'error';


                        $_SESSION['install_error'] = $type;

                    }


                    


                }


            } else {


                if(file_exists(REAL_PATH . $val[3])) {


                    if(!is_writable(REAL_PATH . $val[3])) {


                        $val[1] = '<span style="color:#f30">[×]不可写</span>';


                        $val[2] = 'error';


                        // $_SESSION['install_error'] = $type;


                    }


                } else {


                    if(!is_writable(dirname(REAL_PATH . $val[3]))) {


                        $val[1] = '<span style="color:#f30">[×]不存在</span>';


                        $val[2] = 'error';


                        $_SESSION['install_error'] = $type;

                    }


                }


            }


        }


        


            $request->setAttribute("files",$config);


            unset($config);


            $config = array(


            array('mysqli_connect',     '[√]支持', 'success'),


            array('file_get_contents', '[√]支持', 'success'),


            array('mb_strlen',         '[√]支持', 'success'),

            array('curl_init',         '[√]支持', 'success'),

        );


        foreach ($config as &$val) {

            if(!function_exists($val[0])){


                $val[1] = '<span style="color:#f30">[×]不支持</span>';


                $val[2] = 'error';


                $val[3] = '开启';

                $_SESSION['install_error'] = $type;

            }


        }


        


        $request->setAttribute("func",$config);


        $num = 2;

        $this->getContext()->getStorage()->write('step',$num);

       return View :: INPUT;

    }


    public function getRequestMethods ()

    {

        return Request::NONE;

    }

}
?>
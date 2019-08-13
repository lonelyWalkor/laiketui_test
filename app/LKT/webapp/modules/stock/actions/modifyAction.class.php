<?php
require_once(MO_LIB_DIR . '/DBAction.class.php');

class modifyAction extends Action {
    /**
     * <p>Copyright (c) 2018-2019</p>
     * <p>Company: www.laiketui.com</p>
     * @author 段宏波
     * @date 2018/12/12  16:07
     * @version 1.0
     */
    public function getDefaultView() {
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        // 接收信息
        $admin_id = $this->getContext()->getStorage()->read('admin_id'); // 管理员id
        $store_id = $this->getContext()->getStorage()->read('store_id'); // 商城id

        $id = $request->getParameter("id");
        $type = $request->getParameter('type'); // 类型
        $type1 = $request->getParameter('type1'); // 类型
        $tid = $request->getParameter('tid'); // 模板id
        $rew = '<option value="0">请选择短信模块</option>';
        if($type != ''){
            $type = $type - 1;
            if($type == 0){
                // 查询模板
                $sql = "select id,name from lkt_message where store_id = '$store_id' and type = 0";

            }else if($type == 1){
                // 查询模板
                if($type1 == ''){
                    $type1 = 0;
                }
                $sql = "select id,name from lkt_message where store_id = '$store_id' and type = 1 and type1 = '$type1'";
            }
            $r = $db->select($sql);
            if($r){
                foreach ($r as $k => $v){
                    $rew .= "<option value='$v->id'>$v->name</option>";
                }
            }else{
                $rew = '<option value="0">暂无模板</option>';
            }
            echo json_encode($rew);exit;

        }
        if($tid){
            // 查询模板
            $sql = "select type,content from lkt_message where store_id = '$store_id' and id = '$tid'";
            $r = $db->select($sql);
            if($r){
                $content = $r[0]->content;
                if($r[0]->type == 1){
                    $res1 = "<input type='text' class='input-text' name='content[]' style='width: 100px;'>";
                    $res = preg_replace('/\$.*?\}/', $res1, $content);
                    $res .= "<input type='hidden' class='input-text' name='content1' value='$content'>";
                }else{
                    $res = '<label class="col-6" style="font-size: 14px;color:#97A0B4;tex;text-align: left;">'.$content.'</label>'.
                        '<input type="hidden" name="content[]" value="">'.
                        '<input type="hidden" name="content1" value="'.$content.'">';
                }
                echo json_encode($res);exit;
            }
        }

        // 根据id查询管理员信息
        $sql = "select * from lkt_message_list where store_id = '$store_id' and id = '$id'";
        $r = $db->select($sql);
        if($r){
            $type = $r[0]->type;
            $type1 = $r[0]->type1;
            $Template_id = $r[0]->Template_id;
            $content1 = unserialize($r[0]->content);
            // 查询模板
            if($type == 0){
                $sql = "select id,name from lkt_message where store_id = '$store_id' and type = '0'";
            }else if($type == 1){
                $sql = "select id,name from lkt_message where store_id = '$store_id' and type = '$type' and type1 = '$type1'";
            }
            $rr = $db->select($sql);
            if($rr){
                foreach ($rr as $k => $v){
                    $rew .= "<option value='$v->id' selected>$v->name</option>";
                }
            }
            // 查询模板
            $sql = "select content from lkt_message where store_id = '$store_id' and id = '$Template_id'";
            $rrr = $db->select($sql);
            if($rrr){
                $content2 = $rrr[0]->content;
                $content = "<input type='hidden' class='input-text' name='content1' value='$content2'>";
                if($type == 0){
                    $content .= $content2;
                }else if($type == 1){
                    foreach ($content1 as $ke => $va){
                        $res = "<input type='text' class='input-text' name='content[]' value='{$va}' style='width: 100px;'>";
                        $content2 = str_replace('${'.$ke.'}', $res, $content2);
                    }
                    $content .= $content2;

                }
            }
        }

        $request->setAttribute('id', $id);
        $request->setAttribute("type",$type);
        $request->setAttribute("list",$rew);
        $request->setAttribute("content",$content);

        return View :: INPUT;
    }
    /**
     * <p>Copyright (c) 2018-2019</p>
     * <p>Company: www.laiketui.com</p>
     * @author 段宏波
     * @date 2018/12/12  16:07
     * @version 1.0
     */
    public function execute(){
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $admin_id = $this->getContext()->getStorage()->read('admin_id');
        $admin_name = $this->getContext()->getStorage()->read('admin_name');
        $store_id = $this->getContext()->getStorage()->read('store_id'); // 商城id

        // 接收数据 
        $id = $request->getParameter("id");
        $type = addslashes(trim($request->getParameter('type'))); // 类型
        $type1 = addslashes(trim($request->getParameter('type1'))); // 类型
        $tid = addslashes(trim($request->getParameter('tid'))); // 模板id
        $content = $request->getParameter('content'); // 发送内容
        $content1 = $request->getParameter('content1'); // 发送内容
        // 根据管理员id,查询管理员类型
        $sql = "select type from lkt_admin where id = '$admin_id'";
        $r = $db->select($sql);
        if($r){
            if($r[0]->type == 4){ // 是店主
                $message_admin_id = $admin_id;
            }else{ // 商城人员
                $sql = "select id from lkt_admin where type = 1 and store_id = '$store_id'";
                $r = $db->select($sql);
                $message_admin_id = $r[0]->id;
            }
        }
        if($tid == 0){
            echo json_encode(array('status' =>'请选择短信模板！' ));exit;
        }
        if($type == 0){
            $code = rand(100000,999999);
            $TemplateParam = array('code'=>$code);
            // 根据商城id、管理员id、短信模板，查询该管理员是否重复添加该短信模板
            $sql = "select id from lkt_message_list where store_id = '$store_id' and admin_id = '$message_admin_id' and type = '$type' and id != '$id' ";
        }else if($type == 1){
            if(in_array('', $content)){
                echo json_encode(array('status' =>'请短信内容！' ));exit;
            }
            preg_match_all("/(?<={)[^}]+/",$content1, $result);
            $TemplateParam = array_combine($result[0],$content);
            // 根据商城id、管理员id、短信模板，查询该管理员是否重复添加该短信模板
            $sql = "select id from lkt_message_list where store_id = '$store_id' and admin_id = '$message_admin_id' and type = '$type' and type1 = '$type1' and id != '$id' ";
        }
        $TemplateParam = serialize($TemplateParam);
        $r0 = $db->select($sql);
        if($r0){
            echo json_encode(array('status' =>'该类短信模板已添加,请勿重复添加！' ));exit;
        }else{
            $sql = "update lkt_message_list set type='$type',type1='$type1',Template_id='$tid',content='$TemplateParam',add_time=CURRENT_TIMESTAMP where store_id = '$store_id' and admin_id = '$message_admin_id' and id = '$id'";
            $r1 = $db->update($sql);
            if ($r1 == -1) {
                $db->admin_record($store_id,$admin_name,'修改短信失败',2);

                echo json_encode(array('status' =>'未知原因，修改失败！' ));exit;
            } else {
                $db->admin_record($store_id,$admin_name,'修改短信成功',2);

                echo json_encode(array('status' =>'修改成功！' ,'suc'=>'1'));exit;
            }
        }
        return;
    }

    public function getRequestMethods(){
        return Request :: POST;
    }

}

?>
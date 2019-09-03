<?php

/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */
require_once(MO_LIB_DIR . '/DBAction.class.php');
require_once(MO_LIB_DIR . '/ShowPager.class.php');
require_once(MO_LIB_DIR . '/Tools.class.php');

class distributionAction extends Action {

    public function getDefaultView() {
        $request = $this->getContext()->getRequest();
        $m = addslashes(trim($request->getParameter('m')));
        if($m){
            $this->$m();
        }
        return;
    }

    public function execute(){
        $request = $this->getContext()->getRequest();
        $m = addslashes(trim($request->getParameter('m')));

        if($m){
            $this->$m();
        }
      
        return;
    }

    public function getRequestMethods(){
        return Request :: POST;
    }

   public function detailed_commission(){//确认收货后增加佣金明细

        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $order_id = $request->getParameter('order_id'); // 订单号
        $user_id = $request->getParameter('userid'); // 微信id
        echo json_encode(array('res'=>'请购买商业授权!','status'=>1));
        exit();
        

   }
   public function pt_detailed_commission(){//拼团确认收货后增加佣金明细

        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $ptcode = $request->getParameter('ptcode'); // 开团号
       echo json_encode(array('res'=>'请购买商业授权!','status'=>1));
        exit();
    
   }

   public function commission(){//返现
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $order_id = $request->getParameter('order_id'); // 订单号
        echo json_encode(array('res'=>'请购买商业授权!','status'=>1));
        exit();
   }

   public function membership(){//会员人数
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
         $pagesize = 10;
        // 每页显示多少条数据
        $page = $request -> getParameter('page');

        // 页码
        if($page){
            $start = ($page-1)*$pagesize;
        }else{
            $start = 0;
        }
        $openid = $request -> getParameter('openid');
       echo json_encode(array('res'=>'请购买商业授权!','status'=>1));
        exit();

   }
    public function money(){//预计佣金
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
         $pagesize = 10;
        // 每页显示多少条数据
        $page = $request -> getParameter('page');

        // 页码
        if($page){
            $start = ($page-1)*$pagesize;
        }else{
            $start = 0;
        }
        $openid = $request -> getParameter('openid');
          echo json_encode(array('res'=>'请购买商业授权!','status'=>1));
        exit();

   }
      public function show(){//佣金详情
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $id = $request -> getParameter('id');
           echo json_encode(array('res'=>'请购买商业授权!','status'=>1));
        exit();
    }
}
?>
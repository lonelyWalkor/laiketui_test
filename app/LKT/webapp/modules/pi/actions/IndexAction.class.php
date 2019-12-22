<?php
require_once(MO_LIB_DIR . '/DBAction.class.php');
require_once(MO_LIB_DIR . '/ShowPager.class.php');
require_once(MO_LIB_DIR . '/Tools.class.php');

class IndexAction extends Action {

    public function getDefaultView() {
        $request = $this->getContext()->getRequest();
        $methodName = addslashes(trim($request->getParameter('m'))); //调用哪个方法
        $className = addslashes(trim($request->getParameter('c'))); //调用哪个类文件
        $pluginName = addslashes(trim($request->getParameter('p'))); //插件名称，文件名
        if($pluginName){
            require_once(MO_WEBAPP_DIR."/plugins/".$pluginName."/admin/actions/".$className.".class.php");
            $plugin = new $className($this->getContext());
            if ($methodName) {
                $plugin->$methodName();
            }else{
                $plugin->getDefaultView();
            }

        }

        $request -> setAttribute("c", $className);
        $request -> setAttribute("p", $pluginName);

        return View :: INPUT;
    }

    public function execute() {

    }

    public function getRequestMethods(){
        return Request :: POST;
    }

}

?>
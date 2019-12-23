<?php
/**
 * [Laike System] Copyright (c) 2017-2020 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */
class IndexInputView extends SmartyView {
    public function execute() {
		$request = $this->getContext()->getRequest();

        $className = $request->getAttribute("c") ; //调用哪个类文件
        $pluginName = $request->getAttribute("p") ; //插件名称，文件名
        if($pluginName){
            require_once(MO_WEBAPP_DIR."/plugins/".$pluginName."/admin/views/".$className."InputView.class.php");
            $className = $className."InputView";
            $pluginview = new $className($this,$pluginName);
            $pluginview->execute();

        }

    }
}
?>

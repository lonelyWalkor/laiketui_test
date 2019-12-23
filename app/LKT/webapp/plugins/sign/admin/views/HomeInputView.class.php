<?php

/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */
require_once(MO_WEBAPP_DIR . "/plugins/PluginInputView.class.php");
class HomeInputView extends PluginInputView {
    public function execute() {
		$request = $this->getContext()->getRequest();
		$this->setAttribute("uploadImg",$request->getAttribute("uploadImg"));
        $this->setAttribute("list",$request->getAttribute("list"));
        $this->setAttribute("pages_show",$request->getAttribute("pages_show"));
		$this->setTemplate("home.tpl");
    }
}
?>

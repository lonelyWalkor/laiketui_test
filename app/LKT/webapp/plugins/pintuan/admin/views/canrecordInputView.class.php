<?php

/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */
class canrecordInputView extends PluginInputView {
    public function execute() {
		$request = $this->getContext()->getRequest();
		$this->setAttribute("list",$request->getAttribute("list"));
		$this->setAttribute("bstatus",$request->getAttribute("bstatus"));
        $this->setTemplate("canrecord.tpl");
    }
}
?>
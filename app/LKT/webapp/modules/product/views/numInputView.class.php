﻿<?php

/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */
class numInputView extends SmartyView {
    public function execute() {
		$request = $this->getContext()->getRequest();
		$this->setAttribute("product_title",$request->getAttribute("product_title"));
		$this->setAttribute("uploadImg",$request->getAttribute("uploadImg"));
		$this->setAttribute("class",$request->getAttribute("class"));
		$this->setAttribute("list",$request->getAttribute("list"));
		$this->setTemplate("num.tpl");
    }
}
?>

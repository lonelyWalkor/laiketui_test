<?php

/**
 * [Laike System] Copyright (c) 2017-2020 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */
require_once(MO_WEBAPP_DIR . "/plugins/PluginAction.class.php");

class addAction extends PluginAction {

  public function getDefaultView() {
    $db = DBAction::getInstance();
    $request = $this->getContext()->getRequest();
    $sql = "select * from lkt_config where id = '1'";
    $r = $db->select($sql);


    return View :: INPUT;
  }

  public function execute() {

  }

  public function getRequestMethods(){
    return Request :: POST;
  }

}

?>
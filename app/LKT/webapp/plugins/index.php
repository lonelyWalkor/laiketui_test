<?php
/**
 * [Laike System] Copyright (c) 2017-2020 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 * User: ketter123
 * Date: 19/12/22
 * Time: 下午8:56
 */

function LaikeAuto($class)
{
    require_once(MO_LIB_DIR . '/DBAction.class.php');
    require_once(MO_LIB_DIR . '/ShowPager.class.php');
    require_once(MO_LIB_DIR . '/Tools.class.php');
    require_once(MO_WEBAPP_DIR . "/plugins/PluginAction.class.php");
    require_once(MO_WEBAPP_DIR . "/plugins/PluginInputView.class.php");
}

spl_autoload_register('LaikeAuto');

//我想在这里做更多的事情，，，，在慢慢构思中
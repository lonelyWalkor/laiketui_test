<?php
// auth-generated by FilterConfigHandler
// date: 10/19/2019 11:49:15
require_once('/Users/ketter123/phpsrc/open/app/install/webapp/filter/LoginFilter.class.php');
require_once('/Users/ketter123/phpsrc/open/app/install/webapp/filter/ThdFilter.class.php');
require_once('/Users/ketter123/phpsrc/open/app/install/webapp/filter/SedFilter.class.php');
$filters = array();
$filter = new ExecutionTimeFilter();
$filter->initialize($this->context, array('comment' => true));
$filters[] = $filter;
$filter = new LoginFilter();
$filter->initialize($this->context, array('effect' => true));
$filters[] = $filter;
$filter = new ThdFilter();
$filter->initialize($this->context, array('effect' => true));
$filters[] = $filter;
$filter = new SedFilter();
$filter->initialize($this->context, array('effect' => true));
$filters[] = $filter;
$list[$moduleName] =& $filters;
?>
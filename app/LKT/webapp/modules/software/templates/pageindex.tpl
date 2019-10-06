<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />

<meta http-equiv="Cache-Control" content="no-siteapp" />
<link href="style/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="style/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="style/css/style.css" rel="stylesheet" type="text/css" />
<link href="style/lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />

<title>首页布局管理</title>
{literal}
<style>
   	td a{
        width: 44%;
        margin: 2%!important;
        float: left;
    }
</style>
{/literal}

</head>

<body>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe646;</i>软件管理 <span class="c-gray en">&gt;</span> 小程序首页 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="pd-20">

    

    <div class="mt-20">

        <table class="table table-border table-bordered table-bg table-hover table-sort">

            <thead>

                <tr class="text-c">

                    <th>序</th>

                    <th>图片/分类</th>

                    <th>链接/分类id</th>

                    <th>排序号</th>

                    <th>发布时间</th>

                    <th style="width:140px">操作</th>

                </tr>

            </thead>

            <tbody>

            {foreach from=$list item=item name=f1}

                <tr class="text-c">

                    <td>{$smarty.foreach.f1.iteration}</td>

                    <td>
                        {if $item->type=='img'}
                        <image class='pimg' src="{$item->image}" style="width: 150px;height:80px;"/>
                         {else}
                         {$item->name}
                         {/if}
                    </td>

                    <td>{$item->url}</td>

                    <td>{$item->sort}</td>

                    <td>{$item->add_date}</td>

                    <td>

                        <a style="text-decoration:none" class="ml-5" href="index.php?module=software&action=pagedel&id={$item->id}&yimage={$item->image}" onclick="return confirm('确定要删除此首页模块吗?')">
                        	<div style="align-items: center;font-size: 12px;display: flex;">
                            	<div style="margin:0 auto;;display: flex;align-items: center;"> 
                                <img src="images/icon1/del.png"/>&nbsp;删除
                            	</div>
                    		</div>
                        </a>

                    </td>

                </tr>

            {/foreach}

            </tbody>

        </table>

    </div>

</div>



</body>

</html>
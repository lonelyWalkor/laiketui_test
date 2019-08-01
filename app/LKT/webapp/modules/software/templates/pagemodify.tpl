

<!DOCTYPE HTML>

<html>

<head>

<meta charset="utf-8">

<meta name="renderer" content="webkit|ie-comp|ie-stand">

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />

<meta http-equiv="Cache-Control" content="no-siteapp" />



<link href="style/lib/icheck/icheck.css" rel="stylesheet" type="text/css" />

<link href="style/lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />

<link href="style/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css" />



<link href="style/kjfs/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="http://www.jq22.com/jquery/font-awesome.4.6.0.css">

<link href="style/kjfs/css/style.css" rel="stylesheet" type="text/css" />

{literal}

<style type="text/css">

.pd-20 {

    padding: 20px;

}

.form-horizontal .row {

    display: table;

    width: 100%;

}

.form .row {

    margin-top: 15px;

}

.form-horizontal .form-label {

    margin-top: 3px;

    cursor: text;

    text-align: right;

    padding-right: 10px;

}

@media (min-width: 768px)

.col-sm-2 {

    width: 16.66666667%;

}

@media (min-width: 768px)

.col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9 {

    float: left;

}

.col-xs-4 {

    width: 20%;

}

.c-red, .c-red a, a.c-red {

    color: red;

}

.form-horizontal .formControls {

    padding-right: 10px;

}

@media (min-width: 768px)

.col-sm-8 {

    width: 66.66666667%;

}

@media (min-width: 768px)

.col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9 {

    float: left;

}

.col-xs-8 {

    width: 66.66666667%;

}

.form-horizontal .row {

    display: table;

    width: 100%;

}

.form .row {

    margin-top: 15px;

}

.row {

    margin-right: -15px;

    margin-left: -15px;

}

.row {

    box-sizing: border-box;

}

.cl, .clearfix {

    zoom: 1;

    margin-top: 15px;

}

.form-horizontal .row {

    display: table;

    width: 22%;

    margin-left: 40%;

}

.col-lg-3{

    float: right;

}

</style>

{/literal}

<title>修改模块</title>

</head>

<body>

<nav class="breadcrumb" style="    line-height: 3;"><i class="Hui-iconfont">&#xe646;</i> 小程序首页管理 <span class="c-gray en">&gt;</span> 模块 <span class="c-gray en">&gt;</span> 修改模块 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px;float: right;" href="#" onclick="location.href='index.php?module=software&action=pageindex';" title="关闭" ><i class="Hui-iconfont">&#xe6a6;</i></a></nav>

<div class="pd-20">

    <form name="form1" action="index.php?module=software&action=pagemodify&id={$id}" class="form form-horizontal" method="post" enctype="multipart/form-data" >

      <input type="hidden" name="uploadImg" value="{$uploadImg}" >

<div class="row cl">
    <label class="form-label col-2">类型：</label>
    <div class="formControls col-10 ">
      <input type="radio" name="type"  {if $type=='category'}checked="checked"{/if} value="category">分类 &nbsp;&nbsp; 
    </div>
</div>


<div class="row cl " >
            <label class="form-label col-2"><span class="c-red">*</span>产品类别：</label>
            <div class="formControls col-2"> <span class="select-box">
                <select name="product_class" class="select">
                  {foreach from=$list item=item name=f1}
                    {if $url == $item->cid}
                    <option selected="selected" value="{$item->cid}">{$item->str}{$item->pname}</option>
                    {else}
                    <option value="{$item->cid}">{$item->str}{$item->pname}</option>
                    {/if}
                   {/foreach}
                </select>
                </span>
            </div>
 </div>



      <div class="row cl">

        <label class="">排序号：</label>

        <div class="">

          <input type="text" class="input-text" value="{$sort}" placeholder="" id="" name="sort">

        </div>

      </div>

        <div class="row cl">

          <div class="">

            <button class="btn btn-primary radius" type="submit" name="Submit"><i class="Hui-iconfont">&#xe632;</i> 提 交</button>

            <button class="btn btn-secondary radius" type="reset" name="reset"><i class="Hui-iconfont">&#xe632;</i> 重 写</button>

          </div>

        </div>

    </form>

</div>



<div class="row">

<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="margin: 100px auto;">

<div class="ibox float-e-margins">



</div>

</div>

</div>




<script type="text/javascript" src="modpub/js/check.js" > </script>

<script type="text/javascript" src="style/js/jquery.js"></script>



<!-- 新增编辑器引入文件 -->

<link rel="stylesheet" href="style/kindeditor/themes/default/default.css" />

<script src="style/kindeditor/kindeditor-min.js"></script>

<script src="style/kindeditor/lang/zh_CN.js"></script>



<script src="style/kjfs/js/jquery.min.js"></script>

<script src="style/kjfs/js/bootstrap.min.js"></script>

<script src="style/kjfs/js/index.js"></script>



{literal}

<script>

KindEditor.ready(function(K) {

  var editor = K.editor({

      allowFileManager : true,       

      uploadJson : "index.php?module=system&action=uploadImg", //上传功能

      fileManagerJson : 'kindeditor/php/file_manager_json.php', //网络空间

    });

  //上传背景图片

  K('#image').click(function() {

    editor.loadPlugin('image', function() {

      editor.plugin.imageDialog({

        //showRemote : false, //网络图片不开启

        //showLocal : false, //不开启本地图片上传

        imageUrl : K('#picurl').val(),

          clickFn : function(url, title, width, height, border, align) {

          K('#picurl').val(url);

          $('#thumb_url').attr("src",url);

          editor.hideDialog();

        }

      });

    });

  });

});

//选择链接

$(".select_link").click( function () { 

  $(this).hide(); 

});



  function show(obj) {
    var kzxs = $(".kzxs");
    var type = $(obj).val();
    if(type == 'img'){
      $(".tcategory").hide();
      $(".timg").show();
    }else{
      $(".tcategory").show();
      $(".timg").hide();
    }
    console.log($(obj),type);
  }

var tt = $('input[name="type"]:checked').val();
if(tt == 'img'){
  $(".tcategory").hide();
  $(".timg").show();
}else{
  $(".tcategory").show();
  $(".timg").hide();
}
</script>

{/literal}

</body>

</html>
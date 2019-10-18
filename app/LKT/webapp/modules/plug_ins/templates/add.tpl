<!--
 * @Description: In User Settings Edit
 * @Author: your name
 * @Date: 2019-08-26 13:55:25
 * @LastEditTime: 2019-09-10 14:07:38
 * @LastEditors: Please set LastEditors
 -->

<!DOCTYPE HTML>
<html>

<head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit|ie-comp|ie-stand">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport"
    content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
  <meta http-equiv="Cache-Control" content="no-siteapp" />

  <link href="style/css/H-ui.min.css" rel="stylesheet" type="text/css" />
  <link href="style/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
  <link href="style/lib/icheck/icheck.css" rel="stylesheet" type="text/css" />
  <link href="style/lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />
  <link href="style/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css" />
  {literal}
  <style>
    .button-conter {
      display: flex;
      justify-content: center;
    }

    #btn1 {
      margin-right: 5px;
    }
  </style>
  <script>
    function change() {
      var type = $('input[name="type"]:checked').val();
      $.ajax({
        type: "GET",
        url: location.href + '&action=ajax&type=' + type,
        data: "",
        success: function (msg) {
          $(".select").html(msg);
        }
      });
    }
  </script>
  {/literal}
  <title>添加插件</title>
</head>

<body>
  <nav class="breadcrumb"><i class="Hui-iconfont">&#xe654;</i> 插件管理 <span class="c-gray en">&gt;</span> 插件列表 <span
      class="c-gray en">&gt;</span> 添加插件 <a class="btn btn-success radius r mr-20"
      style="line-height:1.6em;margin-top:3px" href="#" onclick="location.href='index.php?module=plug_ins';"
      title="关闭"><i class="Hui-iconfont">&#xe6a6;</i></a></nav>
  <div class="pd-20">
    <form id="plugAddForm" name="form1" action="index.php?module=plug_ins&action=add" class="form form-horizontal" method="post" enctype="multipart/form-data" onsubmit="return check(this);">

       <label class="form-label col-4">上传软件包：</label>
      <div class="formControls col-4">
          <input type="file" name="file" accept=".zip"/>
      </div>

      <input type="submit" name="submit" value="上传文件并解压">

      <div style="width: 300px;float: right;">
        <samp style="color: red;font-size: 12px;">安装插件可能会修改数据库，请在安装前备份好数据资料</samp>
      </div>
      
    </form>
    <!-- <input type="hidden" id="pic" value="{$pic}"> -->
  </div>

  <script type="text/javascript" src="modpub/js/check.js"> </script>
  <script type="text/javascript" src="style/lib/jquery/1.9.1/jquery.min.js"></script>

  <!-- 新增编辑器引入文件 -->
  <link rel="stylesheet" href="style/kindeditor/themes/default/default.css" />
  <script src="style/kindeditor/kindeditor-all.js"></script>
  <script src="style/kindeditor/lang/zh_CN.js"></script>

  {literal}
  <script>
    function check(vm){
      if(vm[0].value != ''){
        return true
      }
      alert('请选择插件安装包！')
      return false
    }
  </script>
  {/literal}
</body>

</html>
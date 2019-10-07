
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
        
<title>修改活动</title>
</head>
<body>
<nav class="breadcrumb" style="margin-top: 0;"><i class="Hui-iconfont">&#xe6ca;</i> 优惠券管理 <span class="c-gray en">&gt;</span> 活动列表 <span class="c-gray en">&gt;</span> 修改活动 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="#" onclick="location.href='index.php?module=sign';" title="关闭" ><i class="Hui-iconfont">&#xe6a6;</i></a></nav>
<div class="pd-20">
    <form name="form1" action="index.php?module=sign&action=modify" class="form form-horizontal" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="id" value="{$id}">
        <input type="hidden" name="uploadImg" value="{$uploadImg}">
        <input type="hidden" name="status" class="status" value="{$status}">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>活动图片：</label>
            <div class="formControls col-xs-8 col-sm-8"> 
                <img id="thumb_url" src='{$uploadImg}{$image}' style="height:100px;width:150px;">
                <input type="hidden"  id="picurl" name="image" datatype="*" nullmsg="请选择图片"/> 
                <input type="hidden" name="oldpic" value="{$image}">
                <button class="btn btn-success" id="image"  type="button" class="image" >选择图片</button>
            </div>
            <div class="col-4"> </div>
        </div>
        <div class="row cl">
            <label class="form-label col-2">活动时间：</label>
            <div class="formControls col-10">
                <input id="start_time" name="starttime" value="{$starttime}" class="scinput_s" style="width: 160px; height:26px;font-size: 14px;vertical-align: middle;" placeholder="开始时间">
-
                <input id="end_time" name="endtime" value="{$endtime}" class="scinput_s" style="width: 160px; height:26px;font-size: 14px;vertical-align: middle;" placeholder="结束时间">


                <div>如：选择2018.05.29——2018.05.30,活动日期为 2018.05.29 00:00:00——2018.05.30 23:59:59</div>
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-2">活动介绍：</label>
            <div class="formControls col-10"> 
                <script id="editor" type="text/plain" style="width:100%;height:400px;" name="detail" >{$detail}</script> 
            </div>
        </div>
        <div class="row cl">
            <div class="col-10 col-offset-2">
                <button class="btn btn-primary radius" type="submit" name="Submit"><i class="Hui-iconfont">&#xe632;</i> 提 交</button>
                <button class="btn btn-secondary radius" type="reset" name="reset"><i class="Hui-iconfont">&#xe632;</i> 重 写</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript" src="style/lib/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="style/lib/layer/2.1/layer.js"></script>
    <script type="text/javascript" src="style/lib/Validform/5.3.2/Validform.min.js"></script>
    <script type="text/javascript" src="style/js/H-ui.js"></script>
    <script type="text/javascript" src="style/js/H-ui.admin.js"></script>

    <script type="text/javascript" src="style/js/laydate/laydate.js"></script>


    <script type="text/javascript" src="style/lib/ueditor/1.4.3/ueditor.config.js"></script>
    <script type="text/javascript" src="style/lib/ueditor/1.4.3/ueditor.all.min.js"></script>
    <script type="text/javascript" src="style/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
    <!-- 新增编辑器引入文件 -->
    <link rel="stylesheet" href="style/kindeditor/themes/default/default.css"/>
    <script src="style/kindeditor/kindeditor-min.js"></script>
    <script src="style/kindeditor/lang/zh_CN.js"></script>

{literal}
<script>
laydate.render({
          elem: '#start_time', //指定元素
           trigger: 'click',
          type: 'datetime',

        });
       
        laydate.render({
          elem: '#end_time',
          trigger: 'click',
          type: 'datetime'
        });


        


        KindEditor.ready(function (K) {
            var pic = $("#pic").val();

            var editor = K.editor({

                fileManagerJson: 'style/kindeditor/php/file_manager_json.php?dirpath=' + pic, //网络空间
                allowFileManager: true,
                uploadJson: "index.php?module=system&action=uploadImg", //上传功能

            });
            //上传背景图片
            K('#image').click(function () {
                editor.loadPlugin('image', function () {
                    editor.plugin.imageDialog({
                        //showRemote : false, //网络图片不开启
                        //showLocal : false, //不开启本地图片上传
                        imageUrl: K('#picurl').val(),
                        clickFn: function (url, title, width, height, border, align) {
                            K('#picurl').val(url);
                            $('#thumb_url').attr("src", url);
                            editor.hideDialog();
                        }
                    });
                });
            });
        });

</script>
<script type="text/javascript">
var isShow = false
$(function(){
    var ue = UE.getEditor('editor');
    

});

function mobanxuanze(){
    
}
</script>
{/literal}
</body>
</html>
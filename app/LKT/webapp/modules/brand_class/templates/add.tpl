
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
{php}include BASE_PATH."/modules/assets/templates/top.tpl";{/php}

{literal}
<script type="text/javascript">
function check(f){
    if(Trim(f.pname.value)==""){
        alert("品牌名称不能为空！");
        f.pname.value = '';
        return false;
    }
    return true;
}
</script>
{/literal}
<title>添加产品分类</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe616;</i> 商品管理 <span class="c-gray en">&gt;</span> <a href="index.php?module=brand_class" style="text-decoration:none;" onmouseover="this.style.color='#333'">品牌管理</a> <span class="c-gray en">&gt;</span> 添加品牌分类 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="#" onclick="location.href='index.php?module=brand_class';" title="关闭" ><i class="Hui-iconfont">&#xe6a6;</i></a></nav>
<div class="pd-20">
    <form name="form1" action="index.php?module=brand_class&action=add" class="form form-horizontal" method="post" enctype="multipart/form-data" >
        <div class="row cl">
            <label class="form-label col-4"><span class="c-red">*</span>品牌名称:</label>
            <div class="formControls col-6">
                <input type="text" class="input-text" name="pname" datatype="*6-18" style="width: 260px;">
            </div>
            <div class="col-4"> </div>
        </div>
<!--         <div class="row cl">
            <label class="form-label col-4"><span class="c-red"></span>英文名称：</label>
            <div class="formControls col-6">
                <input type="text" class="input-text" name="y_pname" datatype="*6-18" style="width: 260px;">
            </div>
            <div class="col-4"> </div>
        </div> -->
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red"></span>品牌logo：</label>
            <div class="formControls col-xs-8 col-sm-6"> 
                <img id="thumb_url" src='{$uploadImg}nopic.jpg' style="height:100px;width:150px;">
                <input type="hidden"  id="picurl" name="image" datatype="*" nullmsg="请选择图片"/> 
                <input type="hidden" name="oldpic" >
                <button class="btn btn-success" id="image"  type="button" >选择图片</button>
            </div>
            <div class="col-4"> </div>
        </div>
        <div class="row cl">
            <label class="form-label col-4"><span class="c-red"></span>所属国家/市</label>
            <div class="formControls col-6">
                <input type="text" class="input-text" name="producer" datatype="*6-18" style="width: 260px;">
            </div>
            <div class="col-4"></div>
        </div>
        <div class="row cl">
            <label class="form-label col-4"><span class="c-red"></span>排序：</label>
            <div class="formControls col-6">
                <input type="text" class="input-text" name="sort" datatype="*6-18" value="100" style="width: 260px;">
            </div>
            <div class="col-4"></div>
        </div>
        <div class="row cl">
            <label class="form-label col-4"><span class="c-red"></span>备注：</label>
            <div class="formControls col-6">
                <input type="text" class="input-text" name="remarks" datatype="*6-18" style="width: 260px;">
            </div>
            <div class="col-4"></div>
        </div>
        <div class="row cl">
            <label class="form-label col-4"></label>
            <div class="formControls col-6">
                <input type="submit" name="Submit" value="提 交" class="btn btn-primary radius">
                <input type="reset" name="reset" value="取消"  class="btn btn-primary radius" onclick="javascript :history.back(-1);">
            </div>
        </div>
    </form>
</div>


{php}include BASE_PATH."/modules/assets/templates/footer.tpl";{/php}


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
        showRemote : false, //网络图片不开启
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
</script>
{/literal}
</body>
</html>
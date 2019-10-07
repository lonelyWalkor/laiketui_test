
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
{literal}
<script type="text/javascript">
function show(obj){
    if(obj.value=='2'){ // 节日/活动
        document.getElementById("name").readOnly = false; // 活动名称
        document.getElementById('txt').style.display = ""; // 不显示
        document.getElementById('txt_1').style.display = ""; // 金额不显示
        document.getElementById('product_class_id').style.display = ""; // 优惠劵类型id
        document.getElementById('num').style.display = ""; // 数量不显示
        document.getElementById('z_money').style.display = "none"; // 满金额不显示
        // document.getElementById('time').style.display = ""; // 时间显示
        document.getElementById('time1').style.display = ""; // 时间显示
        // document.getElementById('time2').style.display = ""; // 时间显示
    }else if(obj.value=='1'){ // 注册
        document.getElementById("name").readOnly = false; // 活动名称
        document.getElementById('txt').style.display = ""; // 显示
        document.getElementById('txt_1').style.display = ""; // 金额显示
        document.getElementById('product_class_id').style.display = "none"; // 优惠劵类型id
        document.getElementById('num').style.display = "none"; // 数量
        document.getElementById('z_money').style.display = "none"; // 满金额不显示
        // document.getElementById('time').style.display = "none"; // 时间不显示
        document.getElementById('time1').style.display = "none"; // 时间不显示
        // document.getElementById('time2').style.display = "none"; // 时间不显示
    }
}

function change(){
    var product_class_id = $('select[name="product_class_id"]').children('option:selected').val();
    var product_id = $("#product_id1").val();

    $.ajax({
        type: "GET",
        url: location.href+'&action=ajax&product_class_id='+product_class_id,
         data: {product_id :product_id },
        success: function(msg){
            if(msg == 0){
                document.getElementById('product_id').style.display = 'none';
            }else{
                document.getElementById('product_id').style.display = '';
                $(".select2").html(msg);
            }
        }
    });
}



</script>
{/literal}
<title>添加活动</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe6ca;</i> 优惠券管理 <span class="c-gray en">&gt;</span> 活动列表 <span class="c-gray en">&gt;</span> 添加活动 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="#" onclick="location.href='index.php?module=coupon';" title="关闭" ><i class="Hui-iconfont">&#xe6a6;</i></a></nav>
<div class="pd-20">
    <form name="form1" action="index.php?module=coupon&action=add" class="form form-horizontal" method="post" enctype="multipart/form-data">
         <input type="hidden" id="activity_type" value="{$activity_type}" >
         <input type="hidden" id="product_class_id1" value="{$product_class_id}" >
         <input type="hidden" id="product_id1" value="{$product_id}" >
        <div class="row cl">
            <label class="form-label col-4"><span class="c-red">*</span>活动名称：</label>
            <div class="formControls col-4">
                <input type="text" class="input-text" value="{$name}" placeholder="" id="name" name="name">
            </div>
        </div>
        
        <div class="row cl">
            <label class="form-label col-4"><span class="c-red">*</span>活动类型：</label>
            <div class="formControls col-4 skin-minimal" >
                <div class="radio-box">
   
                     <input name="activity_type" type="radio" value="1" {if $activity_type == 1}checked="checked" {/if} onClick="show(this)"/>

                    <label for="sex-1">注册</label>
                </div>
                <div class="radio-box">
                    <input name="activity_type" type="radio" value="2" {if $activity_type == 2}checked="checked" {/if} onClick="show(this)"/>
                    <label for="sex-2">节日/活动</label>
                </div>
            </div>
            <div class="col-4"> </div>
        </div>
        <div class="row cl" style="display:none;" id="product_class_id" onchange="change()">
            <label class="form-label col-4">活动指定商品类型：</label>
            <select name="product_class_id" class="select1" style="width: 80px;height: 31px;vertical-align: middle;">
                {$list}
            </select>
            全部代表通用
        </div>
        <div class="row cl" style="display:none;" id="product_id">
            <label class="form-label col-4">活动指定商品：</label>
            <select name="product_id" class="select2" style="width: 80px;height: 31px;vertical-align: middle;">
                
            </select>
            全部代表该分类下面商品通用
        </div>
        <div class="row cl" id="txt">
            <label class="form-label col-4" id="txt_1"><span class="c-red">*</span>金额：</label>
            <div class="formControls col-4">
                <input type="number" class="input-text" placeholder="{$money}" id="money" name="money" >
            </div>
        </div>

        <div class="row cl" style="display:none;" id="z_money">
            <label class="form-label col-4"><span class="c-red">*</span>金额满：</label>
            <div class="formControls col-4">
                <input type="number" class="input-text" placeholder="" id="z_money1" name="z_money">
            </div>
        </div>
        
        <div class="row cl" id="num" style="display:none;">
            <label class="form-label col-4">数量：</label>
            <div class="formControls col-2">
                <input type="number" class="input-text" placeholder="" id="numId" value="{$num}" name="num">
            </div>
            <text style="line-height:30px;">0表示没限制数量</text>
        </div>
        <div class="row cl"  style="display:none;" id="time1">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>活动时间：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{$start_time}" autocomplete="off" placeholder="" id="group_start_time" name="starttime" style="width:150px;">
                 -
                 <input type="text" class="input-text" value="{$end_time}" autocomplete="off" placeholder="" id="group_end_time" name="group_end_time" style="width:150px;">
            </div>
            <div class="col-3">
            </div>
        </div>
        <div class="row cl">
            <div class="col-10 col-offset-4">
                <button class="btn btn-primary radius" type="submit" name="Submit"><i class="Hui-iconfont">&#xe632;</i> 提 交</button>
                <input type="button" name="reset" value="返回"  class="btn btn-primary radius" id="resetId" onclick="javascript :history.back(-1);">
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="style/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="style/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="style/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="style/lib/icheck/jquery.icheck.min.js"></script> 
<script type="text/javascript" src="style/lib/Validform/5.3.2/Validform.min.js"></script> 
<script type="text/javascript" src="style/lib/webuploader/0.1.5/webuploader.min.js"></script> 
<script type="text/javascript" src="style/js/H-ui.js"></script> 
<script type="text/javascript" src="style/js/H-ui.admin.js"></script> 

<script type="text/javascript" src="style/js/laydate/laydate.js"></script>


<!-- 新增编辑器引入文件 -->
<link rel="stylesheet" href="style/kindeditor/themes/default/default.css" />
<script src="style/kindeditor/kindeditor-min.js"></script>
<script src="style/kindeditor/lang/zh_CN.js"></script>
{literal}
<script>

      var group_end_time = $('#group_end_time');
          group_end_time.val('');
          var startdate = $("#group_start_time").val();
          if(startdate != '' && startdate.length == 19){
            var day = startdate.split(' ');
            var str = startdate.replace(/-/g,'/');
            var d = new Date(str);
            var oneYear = oneYearPast(d);
            oneYear = oneYear + ' ' + day[1];
            $("#end_year").val(oneYear)
          }
       laydate.render({
          elem: '#group_start_time', //指定元素
           trigger: 'click',
          type: 'datetime',

        });
       
        laydate.render({
          elem: '#group_end_time',
          trigger: 'click',
          type: 'datetime'
        });


var activity_type = $("#activity_type").val()
if(activity_type == 2){
    document.getElementById("name").readOnly = false; // 活动名称
    document.getElementById('txt').style.display = ""; // 不显示
    document.getElementById('txt_1').style.display = ""; // 金额不显示
    document.getElementById('product_class_id').style.display = ""; // 优惠劵类型id
    document.getElementById('num').style.display = ""; // 数量不显示
    document.getElementById('z_money').style.display = "none"; // 满金额不显示
    document.getElementById('time1').style.display = ""; // 时间显示
}

var product_class_id = $("#product_class_id1").val();
var product_id = $("#product_id1").val();

if(product_class_id){
        $.ajax({
        type: "GET",
        url: location.href+'&action=ajax&product_class_id='+product_class_id,
        data: {product_id :product_id },
        success: function(msg){
            if(msg == 0){
                document.getElementById('product_id').style.display = 'none';
            }else{
                document.getElementById('product_id').style.display = '';
                $(".select2").html(msg);
            }
        }
    });
}

 
</script>
{/literal}
</body>
</html>
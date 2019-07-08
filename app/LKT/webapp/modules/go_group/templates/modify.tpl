
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link rel="Bookmark" href="/favicon.ico" >
<link rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5shiv.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="style/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="style/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="style/lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="style/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="style/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<!--/meta 作为公共模版分离出去-->

{literal}
<style type="text/css">
   .content{
      border:2px red solid;
   }
/*   .kznone{
    display: none !important;
   }*/
     .product_title{
      width:98px;
      overflow:hidden;
      white-space:nowrap;
      text-overflow:ellipsis;
      -webkit-text-overflow:ellipsis;

  }
</style>
{/literal}
<title>编辑活动</title>
</head>
<body>
<div class="page-container">
    <form class="form form-horizontal" id="form-category-add" enctype="multipart/form-data">
        <div id="tab-category" class="HuiTab">
            <div class="tabBar cl" style="border-bottom: 2px #ff9900 solid;">       
                <span style="background: #ff9900;">编辑活动</span>
            </div>
            
            <div class="tabCon">
                
                <div class="row cl {if $status > 0}kznone{/if}">
                    <label class="form-label col-xs-4 col-sm-3">
                        <span class="c-red">*</span>
                        拼团名称：</label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <input type="text" class="input-text" value="{$list->groupname}" placeholder="" id="{$list->id}" name="groupname" style="width:200px;">
                        <span style="margin-left: 3px;font-size: 10px;color:red;">*必填项</span>
                        <span style="margin-left: 10px;font-size: 10px;color:#666666;">仅后台显示,方便管理</span>
                        
                    </div>
                    
                </div>
                <div class="row cl {if $status > 0}kznone{/if}">
                    <label class="form-label col-xs-4 col-sm-3">拼团人数：</label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <input type="number" max="5" min="1" class="input-text" value="{$list->man_num}" placeholder="" id="" name="peoplenum" style="width:60px;">
                        <span style="margin-left: 3px;font-size: 10px;color:red;">*必填项</span>
                        <span style="margin-left: 10px;font-size: 10px;color:#666666;">只能为大于0且小于等于50的数字</span>
                    </div>
                    <div class="col-3">
                    </div>
                </div>
                <div class="row cl {if $status > 0}kznone{/if}">
                    <label class="form-label col-xs-4 col-sm-3">拼团时限：</label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <input type="number" min="1" class="input-text" value="{$list->hour}" placeholder="" id="" name="timehour" style="width:60px;"><span> 小时 </span>
                        <input type="number" max="59" min="0" class="input-text" value="{$list->minute}" placeholder="" id="" name="timeminite" style="width:60px;"><span> 分钟 </span>
                        <span style="margin-left: 3px;font-size: 10px;color:red;">*必填项</span>
                        <span style="margin-left: 10px;font-size: 10px;color:#666666;">建议不要超过24小时,小时数不能小于1,分钟数为0~59之间</span>
                    </div>
                    <div class="col-3">
                    </div>
                </div>
                <div class="row cl">
                    <label class="form-label col-xs-4 col-sm-3">活动时间：</label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <div class="{if $status > 0}kznone{/if}">
                          <span>开始时间</span><input type="text" class="input-text" value="{$list->starttime}" placeholder="" id="group_start_time" name="starttime" style="width:150px;margin-left: 10px;">
                          <span style="margin-left: 3px;font-size: 10px;color:red;">*必填项</span>
                        </div>
                        <div style="margin-top: 10px;">
                          <span>结束时间</span>
                          {if $list->overtype==1}
                            <input type="hidden" data-time='{$list->endtime}' name='ischang'>
                          {else}
                            <input type="hidden" data-time='0' name='ischang'>
                          {/if}
                            <input type="radio" value="1" placeholder="" id="ctime" name="endtime" onchange="radioChange(1)" style="width:50px;" {if $list->overtype=='1'}checked{/if}><span style="margin-left: -10px;">长期</span>
                            <span style="margin-left: 10px;font-size: 10px;color:#666666;">长期的默认期限是一年</span>
                          <div style="margin-left: 60px;">
                            <input type="radio" value="2" placeholder="" id="dtime" name="endtime" onchange="radioChange(2)" style="width:50px;" {if $list->overtype=='2'}checked{/if}><span style="margin-left: -10px;">定期结束</span><input type="text" class="input-text" {if $list->overtype=='2'}value="{$list->endtime}"{else}value=""{/if} placeholder="" id="group_end_time" name="group_end_time" style="width:150px;margin-left: 10px;">
                            <span style="margin-left: 3px;font-size: 10px;color:red;">*必填项</span>
                            <span style="margin-left: 10px;font-size: 10px;color:#666666;">结束日期至少选此时的一小时后</span>
                          </div>
                        </div>
                    </div>
                    <div class="col-3">
                    </div>
                </div>
                <div class="row cl {if $status > 0}kznone{/if}" style="margin-left: 110px;">
                    <label class="form-label col-xs-4 col-sm-3">每位用户可同时进行的团数：</label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <input type="number" min="1" class="input-text" value="{$list->groupnum}" placeholder="" id="" name="groupnum" style="width:60px;">
                        <span style="margin-left: 3px;font-size: 10px;color:red;">*必填项</span>
                        <span style="margin-left: 10px;font-size: 10px;color:#666666;">数字不能小于1。(为保证商家利益,请对团数进行限制。<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注: 已成功或已失败的团不计入团数。)</span>
                    </div>
                    <div class="col-3">
                    </div>
                </div>
                <div class="row cl {if $status > 0}kznone{/if}" style="margin-left: 110px;">
                    <label class="form-label col-xs-4 col-sm-3">用户每次参团时可购买件数：</label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <input type="number" min="1" class="input-text" value="{$list->productnum}" placeholder="" id="" name="productnum" style="width:60px;">
                        <span style="margin-left: 3px;font-size: 3px;color:red;">*必填项</span>
                        <span style="margin-left: 10px;font-size: 10px;color:#666666;">数字不能小于1。(为保证商家利益,请对用户参团时可购买件数进行限。)</span>
                    </div>
                    <div class="col-3">
                    </div>
                </div>

                   <div class="mt-20">
                  <table class="table table-border table-bordered table-bg table-hover table-sort" style="width:95%;margin:0 auto;">
                     <thead>
                   <tr class="text-c">
                      <th width="50">团号</th>
                      <th width="100">商品名称</th>
                      <th width="80">商品图片</th>
                      <th width="100">属性</th>
                      <th>商品价格</th>
                      <th width="110" style="padding:0px;">拼团价格</th>
                      <th width="110" style="padding:0px;">团长价格</th>
                    {if $status == 1}<th width="50">操作</th>{/if}
                    
                   </tr>
                     </thead>
                     <tbody>
                     {foreach from=$list1 item=item name=f1}
                       <tr class="text-c" style="height:20px;">
                         <td>{$item->group_id}</td>
                         <td>
                          <div class="product_title">{$item->pro_name}</div></td>
                         <td><image src="{$item->image}" style="width: 90%;height:60px;"/></td>

                         <td width="100" style="text-align: center;">
                          {$item->attribute}
                         </td>
                         

                         <td>{$item->market_price}</td>
                         {if $status == 1}
                         <td id="{$item->id}">
                            <input type="number" name="modify_group_{$item->id}" class="pt_price" data-id = "{$item->id}" id="set_group_{$item->id}" value="{$item->group_price}" style="text-align: center; ">  
                         </td>
                         <td id="m{$item->id}">
                            <input type="number" name="modify_member_{$item->id}" class="tz_price" data-id = "{$item->id}" id="set_member_{$item->id}" value="{$item->member_price}" style="text-align: center;"> 
                         </td>
                         {else}
                         <td id="{$item->id}">
                            <div name="modify_group_{$item->id}" >{$item->group_price}</div>
                         </td>
                         <td id="m{$item->id}">
                            <div name="modify_member_{$item->id}" >{$item->member_price}</div>
                         </td>
                         {/if}   
                         {if $status == 1}
                         <td><a title="删除产品" href="javascript:;" onclick="system_category_del(this,{$item->id},1)" class="ml-5" style="color:blue;">删除</a></td>
                         {/if}
                       </tr>
                     {/foreach}
                    </tbody>
                 </table>
              </div>


            </div>
        <div class="row cl">
            <div class="col-9 col-offset-3">
                <input class="btn btn-primary radius" type="button" value="&nbsp;&nbsp;提交&nbsp;&nbsp;" onclick="baocungroup()">
                <input class="btn btn-primary radius" type="button" value="&nbsp;&nbsp;返回&nbsp;&nbsp;" onclick="javascript:history.back(-1);" style="background: #EDEDED;border:0px;color:#fff;">
            </div>
        </div>
    </form>
</div>

<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="style/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="style/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="style/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="style/h-ui.admin/js/H-ui.admin.js"></script> <!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="style/lib/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="style/lib/jquery.validation/1.14.0/jquery.validate.js"></script> 
<script type="text/javascript" src="style/lib/jquery.validation/1.14.0/validate-methods.js"></script> 
<script type="text/javascript" src="style/lib/jquery.validation/1.14.0/messages_zh.js"></script>
<script type="text/javascript" src="style/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="style/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript" src="style/laydate/laydate.js"></script>
{literal}
<script type="text/javascript">
//双击修改
var setgp = new Object;
  var tuanZ = new Object;
  function set_group_price(i){
    var pid = i;
    
    $('div[name=modify_group_'+i+']').hide();
    var set_price = $('#set_group_'+i).val();
    $('.text-c td[id='+i+']').append('<input type="text" name="modify'+i+'" style="width:80px;" onkeyup="gDecimal(this,'+pid+')">');
    $('.text-c input[name=modify'+i+']').attr("value",set_price);
    $('.text-c input[name=modify'+i+']').blur(function(){
      var price = $('.text-c input[name=modify'+i+']').val();
      var abc = price.indexOf('.');
      var efg = price.indexOf('0');
      if(efg == 0 && abc < 0){
        price = price.substring(1);
      }
      if(abc == 0 && price != ''){
        price = '0' + price;
      }
      if(abc < 0 && price != ''){
        price = price + '.00';
      }else if(abc == (price.length-1)){
        price = price + '00';
      }
       price = parseFloat(price).toFixed(2);
       setgp[i] = price;
       
       $('.text-c input[name=modify'+i+']').remove();
       $('.text-c div[name=modify_group_'+i+']').text(price);
       $('#set_group_'+i).val(price);
       $('.text-c div[name=modify_group_'+i+']').show(); 
    });
    
 }

  function set_member_price(i){
    var pid = i;
    
    $('div[name=modify_member_'+i+']').hide();
    var set_price = $('#set_member_'+i).val();
    $('.text-c td[id=m'+i+']').append('<input type="text" name="modifytoo'+i+'" style="width:80px;" onkeyup="checkDecimal(this,'+pid+')">');
    $('.text-c input[name=modifytoo'+i+']').attr("value",set_price);
    $('.text-c input[name=modifytoo'+i+']').blur(function(){
      var price = $('.text-c input[name=modifytoo'+i+']').val();
      var abc = price.indexOf('.');
      var efg = price.indexOf('0');
      if(efg == 0 && abc < 0){
        price = price.substring(1);
      }
      if(abc == 0 && price != ''){
        price = '0' + price;
      }
      if(abc < 0 && price != ''){
        price = price + '.00';
      }else if(abc == (price.length-1)){
        price = price + '00';
      }
       price = parseFloat(price).toFixed(2);
       tuanZ[i] = price;
       
       $('.text-c input[name=modifytoo'+i+']').remove();
       $('.text-c div[name=modify_member_'+i+']').text(price);
       $('#set_member_'+i).val(price);
       $('.text-c div[name=modify_member_'+i+']').show(); 
    });
    
 }
  var radio = 1;
  var otype = $("input[name='endtime']:checked").val();
  
  if(otype == '1'){
    $('#group_end_time').attr('disabled','disabled');
  }
   function radioChange(i){
    var asd = $("input[name='endtime']:checked").val();
      if(i == 1){
          $('#group_end_time').attr('disabled','disabled');
          $('#group_end_time').val('');
          radio = 1;
      }else{
          $('#group_end_time').removeAttr('disabled');
          radio = 2;
      }
      otype = asd;
   }

 $("input[name=endtime]").change(function(){
            if($("input[name=endtime]:checked").val() == 1){
              $('input[name=group_end_time]').removeClass('content');
          }
    });

 function baocungroup(){
    

    var content = 1;
    var id = $("input[name=groupname]").attr('id');
    var groupname = $("input[name=groupname]").val();
    var peoplenum = $("input[name=peoplenum]").val();
    var timehour = $("input[name=timehour]").val();
    var timeminite = $("input[name=timeminite]").val();
    var starttime = $("input[name=starttime]").val();
    var endtime = $("input[name=group_end_time]").val();
    var overtime = otype=='2'?endtime:$('input[name=ischang]').attr('data-time');
    var groupnum = $("input[name=groupnum]").val();
    var productnum = $("input[name=productnum]").val();
    var tz_price_str = "";
      $(".tz_price").each(function(){
          let id = $(this).attr("data-id");
          let val = $(this).val();
          tz_price_str +=  id+":"+val+",";
      })
       // tz_price_str += "}";

      var pt_price_str = "";
      $(".pt_price").each(function(){
          let id = $(this).attr("data-id");
          let val = $(this).val();
          pt_price_str += id+":"+val+",";
      })
       // pt_price_str += "}";

      console.log(tz_price_str);
      console.log(pt_price_str);
    if(groupname == ''){
        $("input[name=groupname]").addClass('content');
        $("input[name=groupname]").change(function(){
            $(this).removeClass('content');
        });
    }else if(peoplenum == '' || parseInt(peoplenum)<1 || parseInt(peoplenum)>50){
        $("input[name=peoplenum]").addClass('content');
        $("input[name=peoplenum]").change(function(){
            $(this).removeClass('content');
        });
    }else if(timehour == '' || parseInt(timehour)<1){
        $("input[name=timehour]").addClass('content');
        $("input[name=timehour]").change(function(){
            $(this).removeClass('content');
        });
    }else if(timeminite == '' || parseInt(timeminite)<0 || parseInt(timeminite)>59){
        $("input[name=timeminite]").addClass('content');
        $("input[name=timeminite]").change(function(){
            $(this).removeClass('content');
        });
    }else if(starttime == ''){
        $("input[name=starttime]").addClass('content');
        $("input[name=starttime]").change(function(){
            $(this).removeClass('content');
        });
    }else if($("input[name=endtime]:checked").val() == 2 && endtime == ''){
        
        $("input[name=group_end_time]").addClass('content');
        $("input[name=group_end_time]").change(function(){
            $(this).removeClass('content');
        });
    }else if(groupnum == '' || parseInt(groupnum)<1){
        $("input[name=groupnum]").addClass('content');
        $("input[name=groupnum]").change(function(){
            $(this).removeClass('content');
        });
    }else if(productnum == '' || parseInt(productnum)<1){
        $("input[name=productnum]").addClass('content');
        $("input[name=productnum]").change(function(){
            $(this).removeClass('content');
        });
    }else{
        content = 2;
    }
   if(content == 2){

      $.ajax({
               url:"index.php?module=go_group&action=modify&set=msgsubmit",
               type:"post",
               data:{
                      id:id,
                      groupname:groupname,
                      peoplenum:peoplenum,
                      timehour:timehour,
                      timeminite:timeminite,
                      starttime:starttime,
                      overtime:overtime,
                      groupnum:groupnum,
                      productnum:productnum,
                      otype:otype,
                      gprice:tz_price_str,
                      mprice:pt_price_str
                    },
               dataType:"json",
               success:function(data) {
                   if(data.code == 1){
                       location.href = 'index.php?module=go_group&action=index';
                   }
               },
             })
        
   }
 }

 //删除属性
     var prolen = '{/literal}{$len}{literal}';
        prolen = parseInt(prolen);
function system_category_del(obj,id,control){
        if(prolen <= 1){
           layer.msg('删除失败,至少得保留一款产品!');
           return false;
        }
  layer.confirm('确认要删除吗？',function(index){        
    $.ajax({
      type: "post",
      url: "index.php?module=go_group&action=modify&set=delpro",
      dataType: "json",
      data:{id:id},
      success: function(data){
        if(data.code == 1){
              layer.msg('已删除!',{icon:1,time:800});
              location.reload();
          }
      },
      error:function() {
          layer.msg('网络出错!',{icon:1,time:800});
      }
    });
  });

  }

 
 // 得到当前日期
function getFormatDate(){
    var nowDate = new Date();
    var year = nowDate.getFullYear();
    var month = nowDate.getMonth() + 1 < 10 ? "0" + (nowDate.getMonth() + 1) : nowDate.getMonth() + 1;
    var date = nowDate.getDate() < 10 ? "0" + nowDate.getDate() : nowDate.getDate();
    var hour = nowDate.getHours()< 10 ? "0" + nowDate.getHours() : nowDate.getHours();
    var minute = nowDate.getMinutes()< 10 ? "0" + nowDate.getMinutes() : nowDate.getMinutes();
    var second = nowDate.getSeconds()< 10 ? "0" + nowDate.getSeconds() : nowDate.getSeconds();

    return year + "-" + month + "-" +date+" "+(hour+1)+":"+minute+":"+second;
}
var _nowDate = getFormatDate();

 laydate.render({
  elem: '#group_start_time', //指定元素
  type: 'datetime'
});
 laydate.render({
  elem: '#group_end_time', 
  type: 'datetime',
  min: _nowDate,
  btns: ['clear', 'confirm']
});


    $('.table-sort').dataTable({
    "aaSorting": [[ 1, "desc" ]],//默认第几个排序
    "bStateSave": true,//状态保存
    "aoColumnDefs": [
      {"orderable":false,"aTargets":[0,4]}// 制定列不参与排序
    ]
});

$(function(){
    $('.skin-minimal input').iCheck({
        checkboxClass: 'icheckbox-blue',
        radioClass: 'iradio-blue',
        increaseArea: '20%'
    });
    
    $("#tab-category").Huitab({
        index:0
    });
    
});

</script>
{/literal}
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>
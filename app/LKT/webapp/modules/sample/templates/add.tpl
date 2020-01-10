<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport"
content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
<meta http-equiv="Cache-Control" content="no-siteapp"/>

{php}include BASE_PATH."/modules/assets/templates/top.tpl";{/php}

{literal}
<style type="text/css">
    form[name=form1] input{
        margin: 0px;
    }
    .inputC+label{
        width: 50px;
        height: 20px;
        line-height: 20px;
        border: none;
    }
    .inputC:checked +label::before{
        display: inline-block;
    }
       
</style>
<script type="text/javascript">
function check(f) {

    if (Trim(f.product_title.value) == "") {
        alert("商品名称不能为空！");
        f.product_title.value = '';
        return false;
    }
    return true;
}
</script>
{/literal}

{literal}
<style type="text/css">
.input-text, .scinput_s{
    width: 300px;
}
.wrap {
    width:60px;
    height:30px;
    background-color:#ccc;
    border-radius:16px;
    position:relative;
    transition:0.3s;
    margin-left: 10px;
}

.wrap_box{
    display: none;
}
.ra1{
    position: relative;
    width: 70px;
    margin-right: 20px;
    border: 1px solid #eee;
    border-radius: 5px;
    height: 30px;
    line-height: 30px;
    float: left;
}
.ra1 label{
    height: 30px;
    text-align: center;
    line-height: 30px;
    margin: 0 auto;
    width: 90%;
    left: 6px;
    background: none;
}
.ra1 input{
    float: left;
    position: absolute;
    height: 30px;
    line-height: 30px;
}
.inputC:checked +label::before{
    top: 8px;
}
.formListSD {
    color: #414658;
}

.formContentSD {
    padding: 0;
    padding-top: 10px;
    position: relative;
}

.formTextSD {
    margin-right: 8px;
    width: 11%!important;
}

.formInputSD input,
.formInputSD select {
    width: 287px;
}

.formInputSD input[type='number'] {
    padding-left: 10px;
    margin-bottom: 10px;
}

.formInputSD select,
.formInputSD select {
    padding-left: 8px;
    margin-bottom: 10px;
}

.inputC:checked+label::before {
    top: 8px;
}
    .switch{
        appearance: none;
        -moz-appearance:button;
        -webkit-appearance: none;
    }
    .switch {
        position: relative;
        margin: 0;
        width: 40PX;
        height: 24PX;
        border: 1PX solid #EBEBF9;
        outline: 0;
        border-radius: 16PX;
        box-sizing: border-box;
        background-color: #EBEBF9;
        -webkit-transition: background-color 0.1s, border 0.1s;
        transition: background-color 0.1s, border 0.1s;
        visibility: inherit !important;
    }

    .switch:before {
        content: " ";
        position: absolute;
        top: 0;
        left: 0;
        width: 38PX;
        height: 22PX;
        border-radius: 19PX;
        background-color: #EBEBF9;
        -webkit-transition: -webkit-transform 0.35s cubic-bezier(0.45, 1, 0.4, 1);
        transition: -webkit-transform 0.35s cubic-bezier(0.45, 1, 0.4, 1);
        transition: transform 0.35s cubic-bezier(0.45, 1, 0.4, 1);
    }

    .switch:after {
        content: " ";
        position: absolute;
        top: 0;
        left: 1px;
        width: 22PX;
        height: 22PX;
        border-radius: 15PX;
        background-color: #FFFFFF;
        /*box-shadow: 0 1PX 3PX rgba(0, 0, 0, 0.4);*/
        -webkit-transition: -webkit-transform 0.35s cubic-bezier(0.4, 0.4, 0.25, 1.35);
        transition: -webkit-transform 0.35s cubic-bezier(0.4, 0.4, 0.25, 1.35);
        transition: transform 0.35s cubic-bezier(0.4, 0.4, 0.25, 1.35);
    }

    .switch:checked{
        background: #00D287;
        border: solid 1px #00D287;
    }

    .switch:checked:before{
        transform: scale(0);
    }

    .switch:checked:after{
        transform: translateX(15PX);
    }

    .blg{
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .blg span {
        padding-left: 5px;
    }
    #masks {
        width: 100%;
        height: 100vh;
        position: absolute;
        z-index: 10002;
        background-color: #FFF;
        justify-content: center;
        align-items: center;
        display: flex;
    }

    #masks img {
        width: 50px;
    }
</style>
{/literal}

<title>添加商品</title>
</head>
<body>
<div id="masks">
    <img src="images/icon1/loads.gif">
</div>

<nav class="breadcrumb">
    商品管理 <span class="c-gray en">&gt;</span> 
    <a href="index.php?module=product">商品列表</a> <span class="c-gray en">&gt;</span> 
    发布商品 <span class="c-gray en">&gt;</span> 
    <a href="javascript:history.go(-1)">返回</a>
</nav>


<div class="pd-20" id="page">
    <form id="form1" name="form1" action="" class="form form-horizontal" method="post" enctype="multipart/form-data" onsubmit="return check(this);">
        <input type="hidden" name="attribute" class="attribute" id="attribute" value='{$attribute}'/>
        <input type="hidden" name="uploadImg" value="{$uploadImg}"/>
        <input type="hidden" name="attribute_num" class="attribute_num" id="attribute_num" value='{$attribute_num}'/>

        <div class="row cl">
            <label class="form-label col-2"><span class="c-red">*</span>商品标题：</label>
            <div class="formControls col-4" style="width: 16.8%;">
                <input type="text" class="input-text" value="{$product_title}" placeholder="" id="product_titleId" name="product_title">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-2"><span class="c-red"></span>副标题：</label>
            <div class="formControls" style="display: inline-block;">
                <input type="text" class="input-text" value="{$subtitle}" placeholder="" id="subtitleId" name="subtitle">
                
            </div>
            <text style="line-height:30px;position: relative;">*简洁表达商品，用来显示在首页商品，避免截取时不能表达是什么商品。</text>
        </div>

        <div class="row cl">
            <label class="form-label col-2"><span class="c-red">*</span>商品类别：</label>
            <div class="formControls col-2"><!--  <span class="select-box"> -->
                
                <select name="product_class" id="product_classId" class="select">
                    <option selected="selected" value="0">请选择类别</option>
                    {$ctype}
                </select>
                <!-- </span> -->
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-2"><span class="c-red">*</span>商品品牌：</label>
            <div class="formControls col-2">
                <!-- <span class="select-box"> -->
                    <select name="brand_class" id="brand_classId" class="select">
                        <option selected="selected" value="0">请选择品牌</option>
                        {$brand}
                    </select>
               <!--  </span> -->
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-2"><span class="c-red">*</span>商品主图：</label>
            <div class="formControls col-xs-8 col-sm-10">

                {if $image}
                    <img id="thumb_url" src='{$image}' style="height:160px;width:160px">
                    <input type="hidden" name="oldpic" value="{$image}">
                {else}
                    <img id="thumb_url" src='../LKT/images/nopic.jpg' style="height:160px;width:160px">
                    <input type="hidden" name="oldpic" value="">
                {/if}

                <input type="hidden" id="picurl" value="{$image}" name="image" datatype="*" nullmsg="请选择图片"/>
                <button class="btn btn-success" id="image" type="button">选择图片</button>

（建议上传160px*160px）
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-2">商品展示图：</label>

            <div class="formControls col-10" style="width: 40%;">

                <div class="uploader-thum-container">
                    <input name="imgurls[]" id="imgurls" multiple='multiple' type="file" style="width:210px;" accept="upload_image/x-png,image/gif,image/jpeg"/>注:最多五张
                </div>
                （建议上传375px*375px）

            </div>
        </div>

        <div class="row cl" style="display:none">
            <label class="form-label col-2"><span class="c-red">*</span>重量：</label>
            <div class="formControls col-4" style="width: 16.8%;">
                <input type="text" class="input-text" value="{$weight}" placeholder="请填入数字" id="weightId" name="weight">
            </div>
            <text style="line-height:30px;">克</text>
        </div>

        <div class="formDivSD">
            <div class="formContentSD">
                <div class="formListSD">
                    <div class="formTextSD"><span class="must">*</span><span>成本价：</span></div>
                    <div class="formInputSD">
                        <input type="number" name="initial[cbj]" onkeypress="return noNumbers(event)" min="0" step="0.01" onblur="set_cbj(this);" value="{$initial->cbj}" placeholder="请设置商品的默认成本价" >
                    </div>
                </div>
                <div class="formListSD">
                    <div class="formTextSD"><span class="must">*</span><span>原价：</span></div>
                    <div class="formInputSD"><input type="number" name="initial[yj]" onkeypress="return noNumbers(event)" min="0" step="0.01" onblur="set_yj(this);" value="{$initial->yj}" placeholder="请设置商品的默认原价" ></div>
                </div>
                <div class="formListSD">
                    <div class="formTextSD"><span class="must">*</span><span>售价：</span></div>
                    <div class="formInputSD"><input type="number" name="initial[sj]" onkeypress="return noNumbers(event)" min="0" step="0.01" onblur="set_sj(this);" value="{$initial->sj}" placeholder="请设置商品的默认售价" ></div>
                </div>
                <div class="formListSD">
                    <div class="formTextSD"><span class="must">*</span><span>单位：</span></div>

                    <div class="formInputSD">
                        <select name="initial[unit]" class="select " style="width: 300px;" id="unit">
                           
                            {if $initial->unit != ''}
                                <option selected="selected" value="{$initial->unit}">{$initial->unit}</option>
                            {else}
                                <option value="">请选择单位</option>
                                <option value="盒">盒</option>
                                <option value="篓">篓</option>
                                <option value="箱">箱</option>
                                <option value="个">个</option>
                                <option value="套">套</option>
                                <option value="包">包</option>
                                <option value="支">支</option>
                                <option value="条">条</option>
                                <option value="根">根</option>
                                <option value="本">本</option>
                                <option value="瓶">瓶</option>
                                <option value="块">块</option>
                                <option value="片">片</option>
                                <option value="把">把</option>
                                <option value="组">组</option>
                                <option value="双">双</option>
                                <option value="台">台</option>
                                <option value="件">件</option>
                          {/if}
             
                                   
                        </select>
                    </div>
                </div>
                <div class="formListSD">
                    <div class="formTextSD"><span class="must">*</span><span>库存：</span></div>
                    <div class="formInputSD">
                        <input type="number" name="initial[kucun]" oninput="value=value.replace(/[^\d]/g,'')" min="0" step="1" onblur="set_kucun(this);" value="{$initial->kucun}" placeholder="请设置商品的默认库存" >
                    </div>
                </div>
                        {literal}
                        <!-- 有规格 -->
                        <div>
                            <div class="arrt_block">
                                <div class="formTextSD"><span class="must">*</span>属性名称：</div>
                                <div class="formInputSD">
                                    <div class="arrt_flex">
                                        <div class="arrt_froup">
                                            <input type="text" class="add-attr-group-input" placeholder="请输入属性名称">
                                            <a class="add-attr-group-btn arrt_add" href="javascript:" style="display: none;"><span>添加属性</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="arrt_block arrt_two">
                                <div class="arrt_width">
                                    <div v-for="(attr_group,i) in attr_group_list" class="attr-group">
                                        <div class="attr-list">
                                            <div class="attr-input-group">
                                                <div class="attr_input_group">
                                                    <span class="arrt_span">{{attr_group.attr_group_name}}：</span>
                                                    <input type="text" class="add-attr-input add_input" ref="input_value" placeholder="请输入属性值" style="padding-left: 10px;">
                                                    <a v-bind:index="i" class="add-attr-btn adds_ntn" href="javascript:"><span>添加属性值</span></a>
                                                    <a v-bind:index="i" href="javascript:" class="attr-group-delete dels_btn"><span>删除属性</span></a>
                                                </div>
                                                <div class="arrt_bgcolor">
                                                    <div v-for="(attr,j) in attr_group.attr_list" class="attr_input_group arrt_clear arrt_zi">
                                                        <span>属性值：</span>
                                                        <input class="add-attr-input" :value="attr.attr_name" readonly="readonly" style="padding-left: 10px;background-color: #F8F8F8 !important;">
                                                        <a v-bind:group-index="i" v-bind:index="j" class="attr-delete" href="javascript:">
                                                            <img src="images/iIcon/jh.png" class="form_plus_u" />
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="arrt_bgcolor arrt_fiv">
                                <div v-if="attr_group_list && attr_group_list.length>0">
                                    <table class="attr-table attr_table">
                                        <thead>
                                            <tr>
                                                <th v-for="(attr_group,i) in attr_group_list" v-if="attr_group.attr_list && attr_group.attr_list.length>0">
                                                    {{attr_group.attr_group_name}}
                                                </th>
                                                <th>成本价</th>
                                                <th>原价</th>
                                                <th>售价</th>
                                                <th>库存</th>
                                                <th>单位</th>
                                                <th>上传图片</th>
                                            </tr>
                                        </thead>
                                        <tr v-for="(item,index) in checked_attr_list" class="arrt_tr">
                                            <td v-for="(attr,attr_index) in item.attr_list">
                                                <input type="hidden" v-bind:name="'attr['+index+'][attr_list]['+attr_index+'][attr_id]'" v-bind:value="attr.attr_id">

                                                <input type="hidden" v-bind:name="'attr['+index+'][attr_list]['+attr_index+'][attr_name]'" v-bind:value="attr.attr_name">

                                                <input type="hidden" v-bind:name="'attr['+index+'][attr_list]['+attr_index+'][attr_group_name]'" v-bind:value="attr.attr_group_name">
                                                <span>{{attr.attr_name}}</span>
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm" type="number" onkeypress="return noNumbers(event)" min="0" step="0.01" v-bind:name="'attr['+index+'][costprice]'" :value="cbj">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm" type="number" onkeypress="return noNumbers(event)" min="0" step="0.01" v-bind:name="'attr['+index+'][yprice]'" :value="yj">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm" type="number" onkeypress="return noNumbers(event)" min="0" step="0.01" v-bind:name="'attr['+index+'][price]'" :value="sj">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm" oninput="value=value.replace(/[^\d]/g,'')" v-bind:name="'attr['+index+'][num]'" :value="kucun" onkeypress="return noNumbers(event)" min="0" step="1">
                                            </td>
                                            <td>
                                                <input class="unit" v-bind:name="'attr['+index+'][unit]'" :value="unit" style="border: 0px;background-color: transparent;" readOnly="readOnly">
                
                                            </td>
                                            <td>
                                                <div class="upload-group form_group form_flex">
                                                    <div class="form_attr_img " style="border: 1px solid;
    background-color: black;">
                                                        <input type="hidden" :id="'picurl2'+index"  v-bind:name="'attr['+index+'][img]'" datatype="*" nullmsg="请选择图片"/>
                                                        <img src="images/icon1/add_g_t.png" :id="'pic2'+index" class="upload-preview-img form_att select-file" @click="handleImageClick(item,index)" onclick="setTimeoutClick() ">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {/literal}
                    </div>
                </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>显示类型：</label>
            <div class="formControls col-xs-8 col-sm-8 skin-minimal">
            
                <div class="ra1">
                    <input name="s_type[]" type="checkbox" id="sex-1" class="inputC" value="1" {if in_array(1,$s_type)}checked="checked"{/if}>
                    <label for="sex-1">新品</label>
                </div>
                
                <div class="ra1">
                    <input type="checkbox" id="sex-2" name="s_type[]" class="inputC" value="2" {if in_array(2,$s_type)}checked="checked"{/if}>
                    <label for="sex-2">热销</label>
                </div>
                
                <div class="ra1">
                    <input type="checkbox" id="sex-3" name="s_type[]" class="inputC" value="3" {if in_array(3,$s_type)}checked="checked"{/if}>
                    <label for="sex-3">推荐</label>
                </div>
                
                <div class="ra1" style="width:100px;">
                    <input type="checkbox" id="sex-4" name="s_type[]" class="inputC" value="4" {if in_array(3,$s_type)}checked="checked"{/if}>
                    <label for="sex-4">首页推荐</label>
                </div>
                
            </div>
        </div>


        <div class="row cl">
            <label class="form-label col-2">拟定销量：</label>
            <div class="formControls col-2">
                <input type="number" class="input-text" value="{$volume}" id="volumeId" name="volume">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-2"><span class="c-red"></span>运费设置：</label>
            <div class="formControls col-2"> <!-- <span class="select-box"> -->
                <select name="freight" id="freightId" class="select">
                    {foreach from=$freight item=item1 name=f2}
                        <option value="{$item1->id}">{$item1->name}</option>
                    {/foreach}
                </select>
                <!-- </span> -->
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">详细内容：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <script id="editor" type="text/plain" name="content" style="width:100%;height:400px;">{$content}</script>
            </div>
        </div>
        <div style="height: 20px;"></div>
        <div class="row cl page_bort_bottom">
            <label class="form-label col-2"></label>
            <div class="formControls col-2">
                <input type="submit" name="Submit" value="提 交" class="btn btn-primary radius btn-right" >
                <input type="button" name="reset" value="返 回" onclick="javascript:history.back(-1);" class="btn btn-primary radius btn-left" >
            </div>
        </div>
    </form>
</div>


</body>
</html>
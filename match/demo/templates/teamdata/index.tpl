{include file="inc/header.tpl"}
<script type="text/javascript" src="js/library.js"></script>
<script type="text/javascript" src="/js/fckeditor.js"></script>
<script type="text/javascript" src="?model=teamdata&plugin=ydmatch&action=importScript&f=fenzu.js"></script>
<script type="text/javascript" src="?model=teamdata&plugin=ydmatch&action=importScript&f=teamdata.js"></script>
<link rel="stylesheet" href="/css/js_color_picker_v2.css" media="screen">
<script type="text/javascript" src="/js/color_functions.js"></script>
<script type="text/javascript" src="/js/js_color_picker_v2.js"></script>

<input type="hidden" class="wx-siteid" value="{$curr_site_id}" />

<div class="toolet-new">
	<form action="?model=teamdata&plugin=ydmatch&action=import" method="post" enctype="multipart/form-data">
	<select name="compday"></select>
	<select name="compdate"></select>
	<input type="file" name="teamdata_file" class="ui-button BTNsearch" icon="search" title="导入：导入初始化数据。" />
	<button class="ui-button BTNsearch" icon="search" title="导入：导入初始化数据。" type="submit">导入</button>	   
	</form>               
	
	<script>
	{literal}
	var obj = fenzu;
    	
	var initcompdayhtml = "";
	$.each(obj.date, function(k,v){
		initcompdayhtml += "<option index='"+k+"' value='"+v+"'>"+v+"</option>";
	});

	$("select[name='compday']").html(initcompdayhtml);

	var initcompdatehtml = "";
	$.each(obj.list[0], function(k,v){
		initcompdatehtml += "<option index='"+k+"' value='"+v.name+","+v.sort+"'>"+v.name+"</option>";
	});
	$("select[name='compdate']").html(initcompdatehtml);
	
	$("body").on("change", "select[name='compday']", function(){
    		var index = $(this).find("option:selected").attr("index");
    		var compdate = obj.list[index];
    		var html = "";
    		$.each(compdate, function(k,v){
    			html += "<option value='"+v.name+","+v.sort+"'>"+v.name+"</option>";
    		});
    		
    		$("select[name='compdate']").html(html);
    	});
	{/literal}
	</script> 
</div>

<div class="toolet-new">    
    <span class="ui-button" icon="plusthick" onclick="Teamdata.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
    <span class="ui-button" icon="trash" onclick="Teamdata.del(0)" title="删除：彻底清除掉所选内容。">删除</span>
    <span class="ui-button" icon="trash" onclick="Teamdata.clearmc(0)" title="更新：立即清除缓存数据。">更新缓存</span>        
</div>

<table class="ui-table">
    <tr>
        <th style="width: 30px;"><input type="checkbox" class="selid" /></th>
        <th>队伍图标</th>
        <th>英文标识符</th>
		<th>ID昵称</th>        
		<th>选手头像</th>
		<th>场均积分</th>	
		<th>历史最大积分</th>	
		<th>场均吞噬</th>
		<th>场均扎刺</th>	
		<th>场均死亡</th>	
		<th>MVP次数</th>		
		<th style="width: 35px;">显示</th>
		<th>操作</th>
    </tr>
    
    {section name=x loop=$list}
        <tr class="{cycle values="bz,bx"}">
            {if $list[x].id neq ''}
                <td><input type="checkbox" class="di" value="{$list[x].id}" {if $list[x].ishot eq '1'}sort="{$list[x].sort}"{/if} title="{$list[x].id}"/></td>
                <td><img src="{$list[x].teamicon}" style="width:100px"/></td>                                
                <td>{$list[x].playerid}</td>
                <td>{$list[x].nickname}</td>
                <td><img src="{$list[x].icon}" style="width:100px" /></td>
                <td>{$list[x].score}</td>                
                <td>{$list[x].maxscore}</td>
                <td>{$list[x].eatnum}</td>
                <td>{$list[x].dispersenum}</td>     
                <td>{$list[x].deadnum}</td>           
                <td>{$list[x].mvpnum}</td>            
                <td>
                {if $list[x].active eq '1'}
                    <span class="ui-xicon" skin="highlight" icon="check" flag="active" bid="{$list[x].id}" title="确定"></span>
                {else}
                    <span class="ui-xicon" icon="close" flag="active" bid="{$list[x].id}" title="取消"></span>
                {/if}
            	</td>    
                <td style="text-align: right;">
                    <span class="ui-button" icon="pencil" onclick="Teamdata.xform({$list[x].id})" title="编辑：改变当前状态和内容。">编辑</span>
                    <span class="ui-button" icon="trash" onclick="Teamdata.del({$list[x].id})" title="删除：彻底清除掉所选内容。">删除</span>                    
                </td>
            {else}
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                
            {/if}
        </tr>
    {/section}    
</table>

{$navpage}

{include file="inc/footer.tpl"}
{include file="inc/header.tpl"}
<script type="text/javascript" src="js/library.js"></script>
<script type="text/javascript" src="/js/fckeditor.js"></script>
<script type="text/javascript" src="?model=livingswitch&plugin=demo&action=importScript&f=livingswitch.js"></script>
<link rel="stylesheet" href="/css/js_color_picker_v2.css" media="screen">
<script type="text/javascript" src="/js/color_functions.js"></script>
<script type="text/javascript" src="/js/js_color_picker_v2.js"></script>

<input type="hidden" class="wx-siteid" value="{$curr_site_id}" />
<div class="toolet-new">
    <span class="ui-button" icon="plusthick" onclick="Livingswitch.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
    <span class="ui-button" icon="trash" onclick="Livingswitch.del(0)" title="删除：彻底清除掉所选内容。">删除</span>        
</div>

<table class="ui-table">
	
    <tr>
    	<th style="width: 30px;"><input type="checkbox" class="selid" /></th>
    	<th>标题</th>
    	<th>标识</th>
    	<th>当前直播状态</th>    	
    	<th>直播地址</th>    	
    	<th>下场比赛时间</th>    	
    	<th>是否是iframe</th>
    	<th style="width: 70px;">排序</th>    	
    	<th>操作</th>        
    </tr>
	{section name=x loop=$list}
        <tr class="{cycle values="bz,bx"}">        	
            {if $list[x].id neq ''}
            	{assign var=index value=$smarty.section.x.index}
                {assign var=prev value=$smarty.section.x.index_prev}
                {assign var=next value=$smarty.section.x.index_next}
                
                <td><input type="checkbox" class="di" value="{$list[x].id}"  title="{$list[x].id}"/></td>
                <td style="text-align: left;">
                    <span title="{$list[x].date}_ID:{$list[x].id}">{$list[x].title}</span>
                </td>
                <td><span>{$list[x].fish}</span></td>
                <td style="red">{if $list[x].state eq 1}直播未开始{elseif $list[x].state eq 2}直播中{elseif $list[x].state eq 3}直播休息中{else}直播已结束{/if}</td>
                <td><span>{$list[x].url}</span></td>
                <td><span>{$list[x].nexttime}</span></td>
                <td><span>{if $list[x].isiframe eq 0}否{else}是{/if}</span></td>
                
                <td>
                {if $index eq 0 }
                    <span style="width:30px;float:left; height:10px;"></span>
                {else}
                    <span class="ui-xicon" icon="arrow-1-n" onclick="Livingswitch.chg({$list[x].id},{$list[x].sort},{$list[$prev].id},{$list[$prev].sort});return false;"  title="上移{$list[x].id},{$list[x].sort},{$list[$prev].id},{$list[$prev].sort}"></span>
                {/if}
                {if $list[$next]}
                    <span class="ui-xicon" icon="arrow-1-s" onclick="Livingswitch.chg({$list[x].id},{$list[x].sort},{$list[$next].id},{$list[$next].sort});return false;"  title="下移{$list[x].id},{$list[x].sort},{$list[$next].id},{$list[$next].sort}"></span>
                {else}
                    <span style="width:30px;float:left; height:10px;"></span>
                {/if}
                </td>
                
                <td>
		            <span class="ui-button" icon="pencil" onclick="Livingswitch.edit({$list[x].id}, 1)">直播未开始</span>
		    		<span class="ui-button" icon="pencil" onclick="Livingswitch.edit({$list[x].id}, 2)">直播中</span>
		    		<span class="ui-button" icon="pencil" onclick="Livingswitch.edit({$list[x].id}, 3)">直播休息中</span>
		    		<span class="ui-button" icon="pencil" onclick="Livingswitch.edit({$list[x].id}, 4)">直播已结束</span>
		    		<span class="ui-button" icon="pencil" onclick="Livingswitch.xform({$list[x].id})">编辑</span>    
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
            {/if}
        </tr>
    {/section}    
</table>

<table class="listTool">
    <tr>
        <td>
            <span class="ui-button" icon="plusthick" onclick="Livingswitch.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
            <span class="ui-button" icon="trash" onclick="Livingswitch.del(0)" title="删除：彻底清除掉所选内容。">删除</span>            
        </td>
    </tr>
</table>

{include file="inc/footer.tpl"}
{include file="inc/header.tpl"}
<script type="text/javascript" src="js/library.js"></script>
<script type="text/javascript" src="/js/fckeditor.js"></script>
<script type="text/javascript" src="?model=agenda&plugin=demo&action=importScript&f=fenzu.js"></script>
<script type="text/javascript" src="?model=agenda&plugin=demo&action=importScript&f=agenda.js"></script>
<link rel="stylesheet" href="/css/js_color_picker_v2.css" media="screen">
<script type="text/javascript" src="/js/color_functions.js"></script>
<script type="text/javascript" src="/js/js_color_picker_v2.js"></script>

<input type="hidden" class="wx-siteid" value="{$curr_site_id}" />
<div class="toolet-new">
    <span class="ui-button" icon="plusthick" onclick="Agenda.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
    <span class="ui-button" icon="trash" onclick="Agenda.del(0)" title="删除：彻底清除掉所选内容。">删除</span>        
</div>
<table class="ui-table">
    <tr>
        <th style="width: 30px;"><input type="checkbox" class="selid" /></th>
        <th style="width: 200px;">赛程时间</th>
        <th style="width: 600px;">分组</th>
        <th style="width: 72px;">排序</th>
        <th>操作</th>
    </tr>
    {section name=x loop=$list}
        <tr class="{cycle values="bz,bx"}">
            {if $list[x].id neq ''}
                {assign var=index value=$smarty.section.x.index}
                {assign var=prev value=$smarty.section.x.index_prev}
                {assign var=next value=$smarty.section.x.index_next}
                <td><input type="checkbox" class="di" value="{$list[x].id}"  title="{$list[x].id}"/></td>
                <td style="text-align: left;">&nbsp;
                    <span title="{$list[x].date}_ID:{$list[x].id}">{$list[x].date}</span>
                </td>
                <td>
                	{if $list[x].group != ''}
                		{foreach key=key item=group from=$list[x].group}
                			<span class="ui-button" icon="pencil" title="{$group.groupname}" onclick="Agenda.addGroupForm({$list[x].id}, {$key})">{$group.groupname}</span>
                		{/foreach}
                	{else}
                	$nbsp;
                	{/if}
                </td>
                <td>
                    {if $index eq 0 }
                        <span style="width:30px;float:left; height:10px;"></span>
                    {else}
                        <span class="ui-xicon" icon="arrow-1-n" onclick="Agenda.chg({$list[x].id},{$list[x].sort},{$list[$prev].id},{$list[$prev].sort});return false;"  title="上移{$list[x].id},{$list[x].sort},{$list[$prev].id},{$list[$prev].sort}"></span>
                    {/if}
                    {if $list[$next]}
                        <span class="ui-xicon" icon="arrow-1-s" onclick="Agenda.chg({$list[x].id},{$list[x].sort},{$list[$next].id},{$list[$next].sort});return false;"  title="下移{$list[x].id},{$list[x].sort},{$list[$next].id},{$list[$next].sort}"></span>
                    {else}
                        <span style="width:30px;float:left; height:10px;"></span>
                    {/if}
                </td>
                <td style="text-align: right;">
                	<span class="ui-button" icon="pencil" onclick="Agenda.addGroupForm({$list[x].id}, 0)" title="增加分组：增加赛程当天的分组信息。">增加分组</span>
                    <span class="ui-button" icon="pencil" onclick="Agenda.xform({$list[x].id})" title="编辑：改变当前状态和内容。">编辑</span>
                    <span class="ui-button" icon="trash" onclick="Agenda.del({$list[x].id})" title="删除：彻底清除掉所选内容。">删除</span>                    
                </td>
            {else}
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
            <span class="ui-button" icon="plusthick" onclick="Agenda.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
            <span class="ui-button" icon="trash" onclick="Agenda.del(0)" title="删除：彻底清除掉所选内容。">删除</span>            
        </td>
    </tr>
</table>

{include file="inc/footer.tpl"}
{include file="inc/header.tpl"}
<script type="text/javascript" src="js/library.js"></script>
<script type="text/javascript" src="/js/fckeditor.js"></script>
<script type="text/javascript" src="?model=teamember&plugin=demo&action=importScript&f=teamember.js"></script>
<link rel="stylesheet" href="/css/js_color_picker_v2.css" media="screen">
<script type="text/javascript" src="/js/color_functions.js"></script>
<script type="text/javascript" src="/js/js_color_picker_v2.js"></script>

<input type="hidden" class="wx-siteid" value="{$curr_site_id}" />

<div class="toolet-new">
	<form action="?model=teamember&plugin=demo&action=import" method="post" enctype="multipart/form-data">
	<input type="file" name="teamember_file" class="ui-button BTNsearch" icon="search" title="导入：导入初始化数据。" />
	<button class="ui-button BTNsearch" icon="search" title="导入：导入初始化数据。" type="submit">导入</button>
	</form>                
</div>

<div class="toolet-new">
    <span class="ui-button BTNsearch" icon="search" title="搜索：根据关键字检索内容。" onclick="Teamember.searchForm(this,'top')">搜索</span>
    <span class="ui-button" icon="plusthick" onclick="Teamember.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
    <span class="ui-button" icon="trash" onclick="Teamember.del(0)" title="删除：彻底清除掉所选内容。">删除</span>
    <span class="ui-button" icon="trash" onclick="Teamember.clearmc(0)" title="更新：立即清除缓存数据。">更新缓存</span>            
</div>

<table class="ui-table">
    <tr>
        <th style="width: 30px;"><input type="checkbox" class="selid" /></th>
        <th>选手名字</th>
		<th>队伍图标</th>        
		<th>选手昵称</th>
		<th>选手头像</th>
		<th>总冠军数</th>
		<th>MVP数</th>
		<th>吞噬数</th>
		<th>粉丝数</th>		
		<th>排序</th>
		<th style="width: 35px;">显示</th>
		<th>操作</th>
    </tr>
    {section name=x loop=$list}
        <tr class="{cycle values="bz,bx"}">
            {if $list[x].id neq ''}
                {assign var=cid value=$list[x].type}
                {assign var=index value=$smarty.section.x.index}
                {assign var=prev value=$smarty.section.x.index_prev}
                {assign var=next value=$smarty.section.x.index_next}
                <td><input type="checkbox" class="di" value="{$list[x].id}" {if $list[x].ishot eq '1'}sort="{$list[x].sort}"{/if} title="{$list[x].id}"/></td>
                <td>{$list[x].name}</td>
                <td><img src="{$list[x].teamicon}" style="width:100px"/></td>
                <td>{$list[x].nickname}</td>
                <td><img src="{$list[x].icon}" style="width:100px"/></td>
                <td>{$list[x].totalwinnernum}</td>
                <td>{$list[x].mvpnum}</td>
                <td>{$list[x].eatnum}</td>
                <td>{$list[x].fansnum}</td>                                
                <td>
                    {if $index eq 0 }
                        <span style="width:30px;float:left; height:10px;"></span>
                    {else}
                        <span class="ui-xicon" icon="arrow-1-n" onclick="Teamember.chg({$list[x].id},{$list[x].sort},{$list[$prev].id},{$list[$prev].sort});return false;"  title="上移{$list[x].id},{$list[x].sort},{$list[$prev].id},{$list[$prev].sort}"></span>
                    {/if}
                    {if $list[$next]}
                        <span class="ui-xicon" icon="arrow-1-s" onclick="Teamember.chg({$list[x].id},{$list[x].sort},{$list[$next].id},{$list[$next].sort});return false;"  title="下移{$list[x].id},{$list[x].sort},{$list[$next].id},{$list[$next].sort}"></span>
                    {else}
                        <span style="width:30px;float:left; height:10px;"></span>
                    {/if}
                </td>
                <td>
                {if $list[x].active eq '1'}
                    <span class="ui-xicon" skin="highlight" icon="check" flag="active" bid="{$list[x].id}" title="确定"></span>
                {else}
                    <span class="ui-xicon" icon="close" flag="active" bid="{$list[x].id}" title="取消"></span>
                {/if}
            	</td>
                <td style="text-align: right;">
                    <span class="ui-button" icon="pencil" onclick="Teamember.xform({$list[x].id})" title="编辑：改变当前状态和内容。">编辑</span>
                    <span class="ui-button" icon="trash" onclick="Teamember.del({$list[x].id})" title="删除：彻底清除掉所选内容。">删除</span>                    
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
            {/if}
        </tr>
    {/section}
</table>

<table class="listTool">
    <tr>
        <td>
            <span class="ui-button BTNsearch" icon="search" title="搜索：根据关键字检索内容。" onclick="Teamember.searchForm(this,'bottom')">搜索</span>
            <span class="ui-button" icon="plusthick" onclick="Teamember.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
            <span class="ui-button" icon="trash" onclick="Teamember.del(0)" title="删除：彻底清除掉所选内容。">删除</span>
            <span class="ui-button" icon="trash" onclick="Teamember.clearmc(0)" title="更新：立即清除缓存数据。">更新缓存</span>            
        </td>
        <td style="text-align: right;">
            {$navpage}
        </td>
    </tr>
</table>

{include file="inc/footer.tpl"}
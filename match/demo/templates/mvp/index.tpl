{include file="inc/header.tpl"}
<script type="text/javascript" src="js/library.js"></script>
<script type="text/javascript" src="/js/fckeditor.js"></script>
<script type="text/javascript" src="?model=mvp&plugin=ydmatch&action=importScript&f=fenzu.js"></script>
<script type="text/javascript" src="?model=mvp&plugin=ydmatch&action=importScript&f=mvp.js"></script>
<link rel="stylesheet" href="/css/js_color_picker_v2.css" media="screen">
<script type="text/javascript" src="/js/color_functions.js"></script>
<script type="text/javascript" src="/js/js_color_picker_v2.js"></script>

<input type="hidden" class="wx-siteid" value="{$curr_site_id}" />

<!--div class="toolet-new">
	<form action="?model=mvp&plugin=ydmatch&action=import" method="post" enctype="multipart/form-data">
	<input type="file" name="mvp_file" class="ui-button BTNsearch" icon="search" title="导入：导入初始化数据。" />
	<button class="ui-button BTNsearch" icon="search" title="导入：导入初始化数据。" type="submit">导入</button>
</div-->

<div class="toolet-new">
    <span class="ui-button BTNsearch" icon="search" title="搜索：根据关键字检索内容。" onclick="Mvp.searchForm(this,'top')">搜索</span>
    <span class="ui-button" icon="plusthick" onclick="Mvp.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
    <span class="ui-button" icon="trash" onclick="Mvp.del(0)" title="删除：彻底清除掉所选内容。">删除</span>
    <span class="ui-button" icon="trash" onclick="Mvp.clearmc(0)" title="更新：立即清除缓存数据。">更新缓存</span>        
</div>
<table class="ui-table">
    <tr>
        <th style="width: 30px;"><input type="checkbox" class="selid" /></th>
        <th>英文标识符</th>        
		<th>队伍图标</th>        
		<th>选手头像</th>
		<th>比赛日期</th>
		<th>比赛场次</th>
		<th>最高分数</th>
		<th>吞噬数</th>
		<th>死亡次数</th>
		<th>炸刺数</th>		
		<th>排序</th>
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
                <td>{$list[x].playerid}</td>                
                <td><img src="{$list[x].teamicon}" style="width:100px"/></td>
                <td><img src="{$list[x].icon}" style="width:100px"/></td>
                <td>{$list[x].compday}</td>
                <td>{$list[x].compdate}</td>
                <td>{$list[x].maxscore}</td>
                <td>{$list[x].eatnum}</td>
                <td>{$list[x].deadnum}</td>
                <td>{$list[x].dispersenum}</td>                                               
                <td>
                    {if $index eq 0 }
                        <span style="width:30px;float:left; height:10px;"></span>
                    {else}
                        <span class="ui-xicon" icon="arrow-1-n" onclick="Mvp.chg({$list[x].id},{$list[x].sort},{$list[$prev].id},{$list[$prev].sort});return false;"  title="上移{$list[x].id},{$list[x].sort},{$list[$prev].id},{$list[$prev].sort}"></span>
                    {/if}
                    {if $list[$next]}
                        <span class="ui-xicon" icon="arrow-1-s" onclick="Mvp.chg({$list[x].id},{$list[x].sort},{$list[$next].id},{$list[$next].sort});return false;"  title="下移{$list[x].id},{$list[x].sort},{$list[$next].id},{$list[$next].sort}"></span>
                    {else}
                        <span style="width:30px;float:left; height:10px;"></span>
                    {/if}
                </td>
                <td style="text-align: right;">
                    <span class="ui-button" icon="pencil" onclick="Mvp.xform({$list[x].id})" title="编辑：改变当前状态和内容。">编辑</span>
                    <span class="ui-button" icon="trash" onclick="Mvp.del({$list[x].id})" title="删除：彻底清除掉所选内容。">删除</span>                    
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
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            {/if}
        </tr>
    {/section}
</table>

<table class="listTool">
    <tr>
        <td>
            <span class="ui-button BTNsearch" icon="search" title="搜索：根据关键字检索内容。" onclick="Mvp.searchForm(this,'bottom')">搜索</span>
            <span class="ui-button" icon="plusthick" onclick="Mvp.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
            <span class="ui-button" icon="trash" onclick="Mvp.del(0)" title="删除：彻底清除掉所选内容。">删除</span>    
            <span class="ui-button" icon="trash" onclick="Mvp.clearmc(0)" title="更新：立即清除缓存数据。">更新缓存</span>        
        </td>
        <td style="text-align: right;">
            {$navpage}
        </td>
    </tr>
</table>

<div class="searchForm ui-widget ui-corner-all ui-helper-hidden" style="width: 750px;">
    <div class="main ui-widget ui-corner-all" style="height: 50px;">
        <table align="center" width="96%" border="0">
            <tr>
                <td height="45" valign="middle">
                    <span class="tit">新闻分类</span>
                    <select class="srtype">
                        <option value=''>--全部--</option>
                        {html_options options=$cat selected=$smarty.get.type}
                    </select>
                </td>
                <td>
                    <span class="tit">关键字</span>
                    <input type="text" class="srkey" value="{$smarty.get.keyword}" size="18" />
                </td>
                <td>
                    <span class="tit">时间</span>
                    从
                    <input type="text" style="width: 75px;" class="srfrom" maxlength="10" value="{$smarty.get.from}" />
                    到
                    <input type="text" style="width: 75px;" class="srto" maxlength="10" value="{$smarty.get.to}" />
                </td>
                <td>
                    <button class="ui-button" icon="check" onclick="Mvp.doSearch()" title="确定">确定</button>
                    <button class="ui-button" icon="close" onclick="Mvp.cloSearch()" title="取消">取消</button>
                </td>
            </tr>
        </table>
    </div>
</div>

{include file="inc/footer.tpl"}
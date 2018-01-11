{include file="inc/header.tpl"}
<script type="text/javascript" src="js/library.js"></script>
<script type="text/javascript" src="/js/fckeditor.js"></script>
<script type="text/javascript" src="?model=news&plugin=ydmatch&action=importScript&f=news.js"></script>
<link rel="stylesheet" href="/css/js_color_picker_v2.css" media="screen">
<script type="text/javascript" src="/js/color_functions.js"></script>
<script type="text/javascript" src="/js/js_color_picker_v2.js"></script>

<input type="hidden" class="wx-siteid" value="{$curr_site_id}" />
<div class="toolet-new">
    <span class="ui-button BTNsearch" icon="search" title="搜索：根据关键字检索内容。" onclick="News.searchForm(this,'top')">搜索</span>
    <span class="ui-button" icon="plusthick" onclick="News.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
    <span class="ui-button" icon="trash" onclick="News.del(0)" title="删除：彻底清除掉所选内容。">删除</span>        
</div>
<table class="ui-table">
    <tr>
        <th style="width: 30px;"><input type="checkbox" class="selid" /></th>
        <th>新闻标题</th>
        <th style="width: 75px;">类型</th>
        <th style="width: 95px;">发布时间</th>
        <th style="width: 32px;">显示</th>
        <th style="width: 72px;">排序</th>
        <th style="width: 300px;">操作</th>
    </tr>
    {section name=x loop=$list}
        <tr class="{cycle values="bz,bx"}">
            {if $list[x].id neq ''}
                {assign var=cid value=$list[x].type}
                {assign var=index value=$smarty.section.x.index}
                {assign var=prev value=$smarty.section.x.index_prev}
                {assign var=next value=$smarty.section.x.index_next}
                <td><input type="checkbox" class="di" value="{$list[x].id}" {if $list[x].ishot eq '1'}sort="{$list[x].sort}"{/if} title="{$list[x].id}"/></td>
                <td style="text-align: left;">&nbsp;
                    <span title="{$list[x].title}_ID:{$list[x].id}" style="{if $list[x].titleclass neq ''}color: {$list[x].titleclass};{/if}">{$list[x].title|mbxtruncate:46:'...'}</span>
                </td>
                <td>{$cat.$cid}</td>
                <td>{$list[x].publishdate}</td>
                <td>
                    {if $list[x].active eq '1'}
                        <span class="ui-xicon" skin="highlight" icon="check" flag="active" bid="{$list[x].id}" title="确定"></span>
                    {else}
                        <span class="ui-xicon" icon="close" flag="active" bid="{$list[x].id}" title="取消"></span>
                    {/if}
                </td>
                <td>
                    {if $index eq 0 }
                        <span style="width:30px;float:left; height:10px;"></span>
                    {else}
                        <span class="ui-xicon" icon="arrow-1-n" onclick="News.chg({$list[x].id},{$list[x].sort},{$list[$prev].id},{$list[$prev].sort});return false;"  title="上移{$list[x].id},{$list[x].sort},{$list[$prev].id},{$list[$prev].sort}"></span>
                    {/if}
                    {if $list[$next]}
                        <span class="ui-xicon" icon="arrow-1-s" onclick="News.chg({$list[x].id},{$list[x].sort},{$list[$next].id},{$list[$next].sort});return false;"  title="下移{$list[x].id},{$list[x].sort},{$list[$next].id},{$list[$next].sort}"></span>
                    {else}
                        <span style="width:30px;float:left; height:10px;"></span>
                    {/if}
                </td>
                <td style="text-align: right;">
                    <span class="ui-button" icon="pencil" onclick="News.xform({$list[x].id})" title="编辑：改变当前状态和内容。">编辑</span>
                    <span class="ui-button" icon="trash" onclick="News.del({$list[x].id})" title="删除：彻底清除掉所选内容。">删除</span>                    
                </td>
            {else}
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
            <span class="ui-button BTNsearch" icon="search" title="搜索：根据关键字检索内容。" onclick="News.searchForm(this,'bottom')">搜索</span>
            <span class="ui-button" icon="plusthick" onclick="News.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
            <span class="ui-button" icon="trash" onclick="News.del(0)" title="删除：彻底清除掉所选内容。">删除</span>            
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
                    <button class="ui-button" icon="check" onclick="News.doSearch()" title="确定">确定</button>
                    <button class="ui-button" icon="close" onclick="News.cloSearch()" title="取消">取消</button>
                </td>
            </tr>
        </table>
    </div>
</div>

{include file="inc/footer.tpl"}
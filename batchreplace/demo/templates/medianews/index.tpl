{include file="inc/header.tpl"}
<script type="text/javascript" src="js/library.js"></script>
<body>
<div class="main">
	<script type="text/javascript" src="/js/fckeditor.js"></script>
	<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="/js/ckfinder/ckfinder.js"></script>
	<script type="text/javascript" src="/js/ckeditor/adapters/jquery.js"></script>
	<script type="text/javascript" src="/js/ajaxfileupload.js"></script>
	<script type="text/javascript" src="?model=medianews&plugin=demo&action=importScript&f=medianews.js"></script>
	<link rel="stylesheet" href="/css/js_color_picker_v2.css" media="screen">
	<script type="text/javascript" src="/js/color_functions.js"></script>
	<script type="text/javascript" src="/js/js_color_picker_v2.js"></script>
	<input type="hidden" class="wx-siteid" value="{$curr_site_id}" />
	<div class="toolet-new">
	    <span class="ui-button BTNsearch ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" icon="search" title="搜索：根据关键字检索内容。" onclick="Medianews.searchForm(this,'top')" role="button">搜索</span>
	    <span class="ui-button" icon="plusthick" onclick="Medianews.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
        <span class="ui-button" icon="trash" onclick="Medianews.del(0)" title="删除：彻底清除掉所选内容。">删除</span>	        
	</div>
	<table class="ui-table">
	    <tbody>
	    <tr>
	        <th style="width: 30px;"><input type="checkbox" class="selid"></th>
	        <th>标题</th>
	        <th>链接地址</th>
	        <th style="width: 35px;">显示</th>	        
	        <th style="width: 70px;">排序</th>
	        <th>操作</th>
	    </tr>
	    {section name=x loop=$list}	  
	    {if !empty($list[x])}  
		<tr class="{if $smarty.section.x.index%2==1}bx{else}bz{/if}">
	    	<td><input type="checkbox" class="di" value="{$list[x].id}" title="{$list[x].id}"></td>
	    	<td style="text-align: left;">&nbsp;
	    		<span title="{$list[x].name}_ID:{$list[x].id}" style="">{$list[x].title}</span>
	        </td>	        
	        <td>{$list[x].link}</td>
	        <td>
                {if $list[x].stat eq '1'}
                    <span class="ui-xicon" skin="highlight" icon="check" flag="active" bid="{$list[x].id}" title="确定"></span>
                {else}
                    <span class="ui-xicon" icon="close" flag="active" bid="{$list[x].id}" title="取消"></span>
                {/if}
            </td>
            <td>
            	{assign var=index value=$smarty.section.x.index}
                {assign var=prev value=$smarty.section.x.index_prev}
                {assign var=next value=$smarty.section.x.index_next}
                {if $index eq 0 }
                    <span style="width:30px;float:left; height:10px;"></span>
                {else}
                    <span class="ui-xicon" icon="arrow-1-n" onclick="Medianews.chg({$list[x].id},{$list[x].sort},{$list[$prev].id},{$list[$prev].sort});return false;"  title="上移{$list[x].id},{$list[x].sort},{$list[$prev].id},{$list[$prev].sort}"></span>
                {/if}
                {if $list[$next]}
                    <span class="ui-xicon" icon="arrow-1-s" onclick="Medianews.chg({$list[x].id},{$list[x].sort},{$list[$next].id},{$list[$next].sort});return false;"  title="下移{$list[x].id},{$list[x].sort},{$list[$next].id},{$list[$next].sort}"></span>
                {else}
                    <span style="width:30px;float:left; height:10px;"></span>
                {/if}
            </td>
	        <td style="text-align: right;">
	        	<span class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" icon="pencil" onclick="Medianews.xform({$list[x].id})" title="编辑：改变当前状态和内容。" role="button">编辑</span>
	        	<span class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" icon="trash" onclick="Medianews.del({$list[x].id})" title="删除：彻底清除掉所选内容。" role="button">删除</span>                    
			</td>	        
	    </tr>
	    {/if}
	    {/section}	                
	    </tbody>
	</table>
	
	<table class="listTool">
	    <tbody>
	    <tr>
	        <td>
	            <span class="ui-button BTNsearch ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" icon="search" title="搜索：根据关键字检索内容。" onclick="Medianews.searchForm(this,'bottom')" role="button">搜索</span>
	            <span class="ui-button" icon="plusthick" onclick="Medianews.xform(0)" title="添加：在列表中添加新的条目。">添加</span>
            	<span class="ui-button" icon="trash" onclick="Medianews.del(0)" title="删除：彻底清除掉所选内容。">删除</span>                                  
	        </td>
	        <td style="text-align: right;">
            	{$navpage}
        	</td>
	    </tr>
		</tbody>
	</table>
	
	<div class="searchForm ui-widget ui-corner-all ui-helper-hidden" style="width: 750px;">
	    <div class="main ui-widget ui-corner-all" style="height: 50px;">
	        <table align="center" width="96%" border="0">
	            <tbody>
	            <tr>
	                <td height="45" valign="middle">
	                    <span class="tit">新闻标题</span>
	                    <input type="text" class="srkey" value="" size="18">
	                </td>	                
	                <td>
	                    <button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" icon="check" onclick="Medianews.doSearch()" title="确定" role="button">确定</button>
	                    <button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" icon="close" onclick="Medianews.cloSearch()" title="取消" role="button">取消</button>
	                </td>
	            </tr>
	        	</tbody>
	        </table>
	    </div>
	</div>

</div>

<div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>
</body>
</html>
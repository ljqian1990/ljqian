{include file="inc/header.tpl"}
<script type="text/javascript" src="?model=banner&plugin=demo&action=importScript&f=library.js"></script>	
<body style="cursor: auto;">
<div class="main">
	
	<script type="text/javascript" src="/js/fckeditor.js"></script>
	<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="/js/ckfinder/ckfinder.js"></script>
	<script type="text/javascript" src="/js/ckeditor/adapters/jquery.js"></script>
	<script type="text/javascript" src="/js/ajaxfileupload.js"></script>
	<script type="text/javascript" src="?model=banner&plugin=demo&action=importScript&f=index.inc.js"></script>	
	<script type="text/javascript" src="/js/model/builder.comm.js"></script>
	
	<input type="hidden" class="wx-siteid" value="{$curr_site_id}" />
	<div class="tabmenu ui-tabs ui-widget ui-widget-content ui-corner-all" style="height: 650px;padding: 15px 18px 0;">		
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		{foreach key=cat item=catname from=$catinfo name=sa}
			<li class="ui-state-default ui-corner-top"><a href="#tab-{$cat}">{$catname.name}</a></li>
		{/foreach}
		</ul>
		
		{foreach key=cat item=catname from=$catinfo name=sa}		
		<div id="tab-{$cat}" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
			<div class="mainpic ui-widget ui-corner-all ui-sortable">
				{section name=x loop=$list}
				{if $list[x].fish == $cat}					
				<div class="ui-box" xid="{$list[x].id}">
					<p><img src="{$list[x].img}"></p>
					<span>{$list[x].title}</span>
					<div style="width:180px;text-align:center; position:relative; top:6px; left:-5px;">
						<input type="hidden" class="id" value="{$list[x].id}">
						<input type="hidden" class="fish" value="{$list[x].fish}">
						<input type="hidden" class="title" value="{$list[x].title}">
						<input type="hidden" class="img" value="{$list[x].img}">
						<input type="hidden" class="link" value="{$list[x].link}">
                    	<input type="hidden" class="memo" value="{$list[x].memo}">
                    	{if $list[x].stat == 1}
						<span class="ui-xicon ucms-state-icon ui-corner-all ui-state-highlight" icon="check" skin="highlight" flag="stat" bid="{$list[x].id}" title="是否显示" style="cursor: pointer; float: left; margin: 2px; padding: 4px 1px; position: relative;"></span>
						{else}
						<span class="ui-xicon ucms-state-icon ui-corner-all ui-state-default" icon="close" skin="default" flag="stat" bid="{$list[x].id}" title="取消" style="cursor: pointer; float: left; margin: 2px; padding: 4px 1px; position: relative;"></span>
						{/if}
						<a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" icon="pencil" onclick="ediform(this,'{$list[x].fish}')" title="编辑：改变当前状态和内容。" role="button">编辑</a>								
						<a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" icon="trash" onclick="Idd.imgdel(this)" title="删除：彻底清除掉所选内容。" role="button">删除</a>					
					</div>
				</div>				
				{/if}			
				{/section}
				
				<div style="clear:both"></div>
			</div>
		
			<div style="margin-top: 10px;">
				<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" icon="arrowthickstop-1-n" onclick="$.formbox('{$cat}', getData(false))" title="上传图片" role="button">上传图片</button>			
			</div>
			<div style="text-align: left;" class="ui-widget">
				<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0pt 0.7em;">
					<p>
						<span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
						<strong>注意!</strong> 
						上传图片时，请先处理好图片的大小，以免影响浏览速度！
					</p>
				</div>
				<br>
			</div>	
		</div>
		{/foreach}		
	</div>			
</div>

</body></html>
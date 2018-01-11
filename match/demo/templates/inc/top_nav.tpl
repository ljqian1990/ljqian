
<!--<a href="/?site_id={$curr_site_id}&plugin=signconfig">签名奖励配置</a>-->

<div style="text-align: left; line-height: 38px; padding-left: 18px;" class="ui-widget-header ui-corner-all">	
	<a class="ui-button" icon="link" href="/index.php?plugin=ydmatch&model=banner&action=index" {if $model=='banner' || $model==''}style="color: #e17009;"{/if}>图文编辑</a>
	<a class="ui-button" icon="link" href="/index.php?plugin=ydmatch&model=agenda&action=index" {if $model=='agenda'}style="color: #e17009;"{/if}>赛程编辑</a>
	<a class="ui-button" icon="link" href="/index.php?plugin=ydmatch&model=medianews&action=index" {if $model=='medianews'}style="color: #e17009;"{/if}>媒体合作新闻</a>
	<a class="ui-button" icon="link" href="/index.php?plugin=ydmatch&model=livingswitch&action=index" {if $model=='livingswitch'}style="color: #e17009;"{/if}>直播开关</a>
	<a class="ui-button" icon="link" href="/index.php?plugin=ydmatch&model=teamdata&action=index" {if $model=='teamdata'}style="color: #e17009;"{/if}>队伍数据</a>
	<a class="ui-button" icon="link" href="/index.php?plugin=ydmatch&model=teamdatatotal&action=index" {if $model=='teamdatatotal'}style="color: #e17009;"{/if}>导入的队伍数据</a>
	<a class="ui-button" icon="link" href="/index.php?plugin=ydmatch&model=mvp&action=index" {if $model=='mvp'}style="color: #e17009;"{/if}>MVP</a>
	<a class="ui-button" icon="link" href="/index.php?plugin=ydmatch&model=teamember&action=index" {if $model=='teamember'}style="color: #e17009;"{/if}>参赛选手</a>
</div>
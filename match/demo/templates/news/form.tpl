<div class="infoForm">
	<form action="?" method="post" onsubmit="return News.save(this);">
	<input type="hidden" name="site_id" value="{$site_id}" />
	<input type="hidden" name="model" value="news" />
    <input type="hidden" name="sort" value="{$info.sort}" />
	<input type="hidden" name="action" value="{if $info.id neq ''}edit{else}add{/if}" />
	<input type="hidden" name="id" value="{$info.id}" />
	<table>
		<tr>
			<td class="ll" style="width: 80px;">新闻标题</td>
			<td class="rr" colspan="5">
				<input type="text" name="title" value="{$info.title}" size="75" />
			</td>
			<td class="ll" style="width: 80px;">发布时间</td>
			<td class="rr">
				<input type="text" name="publishdate" class="_datepicker" size="12" readonly value="{$info.publishdate|default:$smarty.now|date_format:'%Y-%m-%d'}" />
			</td>
		</tr>
		<tr>
			<td class="ll">所属类型</td>
			<td class="rr">
				<select name="type">
					{html_options options=$catopt selected=$info.type}
				</select>
			</td>
			<td class="ll" style="width: 80px;">是否显示</td>
			<td class="rr">
				<input type="checkbox" value="1" name="active" {if $info.active neq '0'}checked{/if} />
			</td>
			<td class="ll" style="width: 80px;">新帖标记</td>
			<td class="rr">
				<input type="checkbox" value="1" name="isnew" {if $info.isnew eq 1}checked{/if} />
			</td>
			<td class="ll">是否置顶</td>
			<td class="rr">
				<input type="checkbox" value="1" name="ishot" {if $info.ishot eq 1}checked{/if} />
			</td>
		</tr>
		<tr>
			<td class="ll" style="width: 80px;">标题颜色</td>
			<td class="rr">
				<input type="text" name="titleclass" size="12" value="{$info.titleclass}" onclick="showColorPicker(this,this)" />
			</td>
			<td class="ll" style="width: 80px;">是否加粗</td>
			<td class="rr">
				<input type="checkbox" value="1" name="bold" {if $info.bold eq 1}checked{/if} />
			</td>
			<td class="ll" style="width: 80px;">首页显示</td>
			<td class="rr">
				<input type="checkbox" value="1" name="index_show_all" {if $info.index_show eq 3 or $info.index_show eq 1}checked{/if} />
				&nbsp;
				分类:<input type="checkbox" value="2" name="index_show_cat" {if $info.index_show eq 3 or $info.index_show eq 2}checked{/if} />
			</td>
			<td class="ll">超首显示</td>
			<td class="rr">
				<input type="checkbox" value="1" name="super_show" {if $info.super_show eq 1}checked{/if} />
			</td>
		</tr>
		<tr>
			<td class="ll" style="width: 80px;">关键字</td>
			<td class="rr" style="padding-right: 0px;">
				<input type="text" name="seo_keyword" value="{$info.seo_keyword}" style="margin-right: 0; width: 180px;" />
			</td>
			<td class="ll" style="width: 80px;">简短描述</td>
			<td class="rr" class="rr" colspan="5" style="padding-right: 0px;">
				<input type="text" name="seo_description" value="{$info.seo_description}" style="margin-right: 0; width: 400px;" />
			</td>
		</tr>
		<tr id="_imgbrowser" {if $info.img eq ''}class="ui-helper-hidden"{/if}>
			<td class="ll" style="width: 80px;">封面预览</td>
			<td class="rr" colspan="7">
				<div style="width: 278px; height: 158px;">
					<img src="{$info.img}" style="max-width: 278px; max-height: 158px; border: 0;" />
				</div>
			</td>
		</tr>
		<tr>
			<td class="ll" style="width: 80px;">封面图片</td>
			<td class="rr" colspan="7">
				<input id="_uploadimg" type="text" name="img" value="{$info.img}" size="28" readonly />
				<button type="button" onmouseover="$.browpop(event,this,imgcoreback)" icon="folder-open" class="ui-button" title="浏览">浏览</button>
				<span style="color:red">*此功能无法自动生成缩略图，上传前请自行裁剪</span>
			</td>
		</tr>
		<tr>
			<td class="ll">设为链接</td>
			<td colspan="7" class="rr">
				<input type="checkbox" name="islink" value="1" onclick="News.chglink(this)" {if $info.islink eq 1}checked{/if} />
				<span id="linkPanel" {if $info.islink neq 1}class="ui-helper-hidden"{/if}>
					&nbsp;&nbsp;链接地址:
					<input type="text" name="linkaddr" value="{if $info.islink eq 1}{$info.content}{/if}" style="width: 635px;" />
				</span>
			</td>
		</tr>
		<tr id="txtPanel" {if $info.islink eq 1}class="ui-helper-hidden"{/if}>
			<td colspan="8">
				<textarea id="_editor" name="content">{if $info.islink eq 0}{$info.content}{/if}</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="8">
				<button type="submit" class="ui-button" icon="check" title="确定">确定</button>
				<button type="button" class="ui-button close" icon="close" title="取消">取消</button>
				<span>
					&nbsp;&nbsp;&nbsp;&nbsp;
					定时发布<input type="checkbox" name="timerflag" value="ON" onclick="News.triTimer(this.checked)" {if $timerflag eq 'ON'}checked{/if} />
					<span class="xtimer {if $timerflag neq 'ON'}ui-helper-hidden{/if}">
						<input type="text" class="_datepicker" name="pub_timer_date" readonly style="width: 83px;" value="{$pub_timer_date}" />
						<input type="text" class="_dateslider" name="pub_timer_hour" readonly style="width: 21px;" value="{$pub_timer_hour}" maxvalue="23" />:<input type="text" class="_dateslider" name="pub_timer_minute" readonly style="width: 21px;" value="{$pub_timer_minute}" maxvalue="59" />
					</span>
				</span>
			</td>
		</tr>
	</table>
	</form>
</div>
{include file="inc/header.tpl"}
<script type="text/javascript" src="js/library.js"></script>
<script type="text/javascript" src="/js/fckeditor.js"></script>
<script type="text/javascript" src="?model=teamdatatotal&plugin=demo&action=importScript&f=teamdatatotal.js"></script>
<link rel="stylesheet" href="/css/js_color_picker_v2.css" media="screen">
<script type="text/javascript" src="/js/color_functions.js"></script>
<script type="text/javascript" src="/js/js_color_picker_v2.js"></script>

<input type="hidden" class="wx-siteid" value="{$curr_site_id}" />

<div class="toolet-new">    
    <span class="ui-button" icon="trash" onclick="Teamdatatotal.del(0)" title="删除：彻底清除掉所选内容。">删除</span>        
</div>

<table class="ui-table">
    <tr>
        <th style="width: 30px;"><input type="checkbox" class="selid" /></th>
        <th>场次</th>					
        <th>队伍</th>
        <th>英文标识符</th>
        <th>积分</th>
		<th>最大积分</th>        
		<th>吞噬数</th>
		<th>炸刺数</th>	
		<th>死亡次数</th>	
		<th>mvp数</th>		
		<th>操作</th>
    </tr>
    
    {section name=x loop=$list}
        <tr class="{cycle values="bz,bx"}">
            {if $list[x].id neq ''}
                <td><input type="checkbox" class="di" value="{$list[x].id}" {if $list[x].ishot eq '1'}sort="{$list[x].sort}"{/if} title="{$list[x].id}"/></td>                                                               
                <td>{$list[x].changci}</td>        
                <td><img src="{$list[x].teamicon}"/></td>
                <td>{$list[x].playerid}</td>
                <td>{$list[x].score}</td>                                               
                <td>{$list[x].maxscore}</td>
                <td>{$list[x].eatnum}</td>
                <td>{$list[x].dispersenum}</td>     
                <td>{$list[x].deadnum}</td>           
                <td>{$list[x].mvpnum}</td>                               
                <td style="text-align: right;">                    
                    <span class="ui-button" icon="trash" onclick="Teamdatatotal.del({$list[x].id})" title="删除：彻底清除掉所选内容。">删除</span>                    
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

<div class="toolet-new">    
    <span class="ui-button" icon="trash" onclick="Teamdatatotal.del(0)" title="删除：彻底清除掉所选内容。">删除</span>      
</div>
{$navpage}      


{include file="inc/footer.tpl"}
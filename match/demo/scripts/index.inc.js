var g_site_id;

$(function(){
	Idd.init();
	Idd.init_btn();
	g_site_id = $(':input.wx-siteid').val();
});

//图片列表中条件记录的数据
function getData(o){
	var data = {};
	data.show = {
		title: 1,
		img: 1,
		link: 1,
        memo: 1
	};
	data.func = 'cbuiform';
	if(o){
		data.id = $(':input.id', o).val();
		data.title = $(':input.title', o).val();
		data.img = $(':input.img', o).val();
		data.link = $(':input.link', o).val();
		data.fish = $(':input.fish', o).val();
        data.memo = $(':input.memo', o).val();
	}
	return data;
}

//图片列表保存操作后的回调
function cbuiform(rp, ui){
	if(rp.returnflag!='OK'){
		$.msgbox(rp.message);
		return false;
	}
	mainDiv = 'div#tab-'+rp.flag+' div.mainpic';
	if($(mainDiv+' div[xid='+rp.id+']').length>0){
		var p = $(mainDiv+' div[xid='+rp.id+']');
		$('p img', p).attr('src', rp.img);
		$('p:has(img)', p).next().text(rp.title);
		$(':input.title', p).val(rp.title);
		$(':input.img', p).val(rp.img);
		$(':input.link', p).val(rp.link);
		$(':input.memo', p).val(rp.memo);
		$(':input.stat', p).val(rp.stat);
	} else {
		var html = '<div class="ui-box" xid="'+rp.id+'">';
		html += '<p><img src="'+rp.img+'" /></p>';
		html += '<span>'+rp.title+'</span>';
		html += '<div>';
		html += '<input type="hidden" class="id" value="'+rp.id+'" />';
		html += '<input type="hidden" class="title" value="'+rp.title+'" />';
		html += '<input type="hidden" class="img" value="'+rp.img+'" />';
		html += '<input type="hidden" class="link" value="'+rp.link+'" />';
		html += '<input type="hidden" class="memo" value="'+rp.memo+'" />';
		html += '<input type="hidden" class="stat" value="'+rp.stat+'" />';
		if(rp.stat == 1){
			html += '<span title="确定" bid="' + rp.id + '" flag="stat" skin="highlight" icon="check" class="ui-xicon ui-corner-all ui-state-highlight" style="cursor: pointer; float: left; margin: 2px; padding: 4px 1px; position: relative;"><span class="ui-icon ui-icon-check" style="float:left;margin:0 4px;"></span></span>';			
		}else{
			html += '<span title="取消" bid="' + rp.id + '" flag="stat" icon="close" class="ui-xicon ui-corner-all ui-state-default" style="cursor: pointer; float: left; margin: 2px; padding: 4px 1px; position: relative;"><span class="ui-icon ui-icon-close" style="float:left;margin:0 4px;"></span></span>';
		}
		html += '<input type="hidden" class="stat" value="'+rp.stat+'" />';
		html += '<a class="ui-button" icon="pencil" onclick="ediform(this, \''+rp.fish+'\')">编辑</a>';
		html += '<a class="ui-button" icon="trash" onclick="Idd.imgdel(this)">删除</a>';
		html += '</div>';
		html += '</div>';
		var box = $(mainDiv).prepend(html);
		$('.ui-button', box).xbutton();		
	}
	Idd.init_btn();
	//销毁对话框前，先销毁编辑器
	$(ui).find('.memo').dropeditor();
	ui.remove();
}
function chg(){
		var me = this;
		$.ajax({
			url:'?model=banner&plugin=ydmatch&action=stat',
			data: {
				'site_id': g_site_id,
				'id': $(me).attr('bid')
			},
			success: function(rp){
				if(rp.returnflag!='OK'){
					$.msgbox('更改失败，请稍后重试！<br />'+rp.text);
					return false;
				}
				if(rp.flag==1){
					$(me).chicon('check', 'highlight');
				} else {
					$(me).chicon('close', 'default');
				}
			}
		});
	}

//新增、编辑图片记录对话框
function ediform(o, fish){
	var p = $(o).parent().parent();
	var data = getData(p);
	$.formbox(fish, data);
}

//拼装文字列表中的记录数据
function getDataTxt(o){
	var data = {};
	data.show = {
		title: 1,
		link: 1,
        time: 1,
		memo: 1
	};
	data.func = 'cbtxtform';
	if(o){
		data.id = $('th.id', o).attr('xid');
		data.title = $('td.title', o).text();
		data.link = $('td.link', o).text();
        data.time = $('td.time', o).text();
		data.memo = $('.memo-hidden', o).val();
	}
	return data;
}

//新增、编辑文字记录对话框
function cbtxtform(rp,ui){
	if(rp.returnflag!='OK'){
		$.msgbox(rp.message);
		return false;
	}
	
	/*
	添加成功以后，直接刷新页面，不执行后面的js了。原因是，在2015年4月加了文本排行功能，如果不刷新，就要处理很多dom状态
	*/
	location.href=location.href;
	return false;
	
	var $tr = $('tr.di-'+rp.id);
	if(rp.stat == 1){
		stat_html = '<span title="确定" bid="' + rp.id + '" flag="stat" skin="highlight" icon="check" class="ui-xicon ui-corner-all ui-state-highlight" style="cursor: pointer; float: left; margin: 2px; padding: 4px 1px; position: relative;"><span class="ui-icon ui-icon-check" style="float:left;margin:0 4px;"></span></span>';
	}else{
		stat_html = '<span title="取消" bid="' + rp.id + '" flag="stat" icon="close" class="ui-xicon ui-corner-all ui-state-default" style="cursor: pointer; float: left; margin: 2px; padding: 4px 1px; position: relative;"><span class="ui-icon ui-icon-close" style="float:left;margin:0 4px;"></span></span>';
	}
	if($tr.size()>0){		// ** 更新
		$('td.title',$tr).text(rp.title);
		$('td.link',$tr).html('<a href="'+rp.link+'" target="_blank">'+rp.link+'</a>');
        $('td.time',$tr).text(rp.time);
		$('td.memo',$tr).text(removeHTMLTag(rp.memo));
		$('.memo-hidden',$tr).val(rp.memo);
		$('td.stat',$tr).html(stat_html);
		
	} else {	// ** 新增
		var $div=$('div#tab-'+rp.flag);
		var isInsert = false;
		$('table.ui-table tr:gt(0)',$div).each(function(ni,mo){
			if(!isInsert && $('th.id',this).attr('xid')==undefined){
				$(this).addClass('di-'+rp.id);
				$('th.id',this).attr('xid',rp.id);
				$('th.id',this).text(ni+1);
				$('td.title',this).text(rp.title);
				$('td.link',this).html('<a href="'+rp.link+'" target="_blank">'+rp.link+'</a>');
                $('td.time',this).text(rp.time);
				$('td.memo',this).text(removeHTMLTag(rp.memo));
				$('.memo-hidden', this).val(rp.memo);
				$('td.stat',this).html(stat_html);
				var button = '<span class="ui-button" icon="pencil" onclick="editxtform(this, \''+rp.fish+'\')">编辑</span>';
				button += '<span class="ui-button" icon="trash" onclick="txtdel(this)">删除</span>';
				$('td.op',this).html(button).find('.ui-button').xbutton();
				isInsert = true;
			}
		});
		// 插入新行
		if(!isInsert){
			var counter = $('table.ui-table tr',$div).size();
			var clax = counter%2==1 ? 'bz' : 'bx';
			var trline = '<tr class="'+clax+' di-'+rp.id+'">';
			trline += '<th class="id" xid="'+rp.id+'">'+counter+'</th>';
			trline += '<td class="title">'+rp.title+'</td>';
			trline += '<td class="link">'+rp.link+'</td>';
			trline += '<td class="memo">'+removeHTMLTag(rp.memo)+'</td>';
			trline += '<td class="stat">'+stat_html+'</td>';
			trline += '<td class="op">';
			trline += '<span class="ui-button" icon="pencil" onclick="editxtform(this, \''+rp.fish+'\')">编辑</span>';
			trline += '<span class="ui-button" icon="trash" onclick="txtdel(this)">删除</span>';
			trline += '</td>';
			trline += '<input type="hidden" value="'+rp.memo+'" class="memo-hidden">';
			trline += '</tr>';
			$('table.ui-table',$div).append(trline).find('tr:last .ui-button').xbutton();			
		}
	}	
	Idd.init_btn();
	//销毁对话框前，先销毁编辑器
	$(ui).find('.memo').dropeditor();
	ui.remove();
}

function editxtform(o,fish){
	var p = $(o).parent().parent();
	var data = getDataTxt(p);
	$.formbox(fish, data);
}

var Idd = (function($){
	this.init_btn = function(){
		$('table.ui-table span.ucms-state-icon').unbind();
		$('div.ui-box span.ucms-state-icon').unbind();
		$('table.ui-table span.ucms-state-icon').click(chg);
		$('div.ui-box span.ucms-state-icon').click(chg);		
	}
	$.ajaxSetup({
		'url': '?',
		'type': 'post',
		'dataType': 'json'
	});
	
	this.init = function(){
		var tabsParas = {header: 'h3'};
		//每次点击选项卡的时候，都会记录ID，以后刷新以后，可以直接显示之前的选项卡
		var cookie = new $.cookie();
		if(cookie.get('ucms_index_tabIndex')){
			tabsParas.selected = cookie.get('ucms_index_tabIndex');
		}
		tabsParas.select = function(event,ui){
			cookie.set('ucms_index_tabIndex',ui.index);
		}
		$('div.tabmenu').tabs(tabsParas);
		$('textarea').fckeditor({
			'toolbar':myfulTool,
			'height': '415px',
			'folder':'indexdata'
		});
		$("div .mainpic").sortable({
			'items': '.ui-box',
			'stop': function(ev,ui){
				var $ro=$(this);
				var data=[];
				$('.ui-box',$ro).each(function(){
					data.push($(this).attr('xid'));
				});
				$.ajax({
					'url':'?model=banner&plugin=ydmatch&action=zort',
					'data': {
						'site_id': g_site_id,
						'sort_id': data
					},
					'success': function(rp){
						
					}
				});
			}
		}).disableSelection();
	};
	
	this.imgdel = function(o){
		if(!window.confirm('你确定要删除该图片吗？')){
			return false;
		}
		var p = $(o).parent().parent();
		$.ajax({
			url:'?model=banner&plugin=ydmatch&action=del',
			data: {
				'site_id': g_site_id,
				'del_id': $(':input.id', p).val()
			},
			success: function(rp){
				if(rp.returnflag=='OK'){
					p.remove();
				} else {
					$.msgbox(rp.message);
				}
			}
		});
	};
		
	
	return this;
	
})(jQuery);

(function($){
	
	var me = this;
	
	var _uriparam = null;
	
	this.init = function(){
		
		// ** 全选及取消
		$(':input.selid').click(function(){
			$(':input.di').attr('checked', $(this).attr('checked')?true:false);
		});
		
		// ** 按钮样式
		$('.ui-button').each(function(){
			var icon=$(this).attr('icon');
			if(icon!=undefined){
				$(this).button({
					'icons': {
						primary: 'ui-icon-'+icon
					},
					'create': function(ev,ui){
						$(ui).css({
							'font-size': '10px'
						});
					}
				});
			} else {
				$(this).button();
			}
		});
		
		// ** 表格样式
		$('.ui-table tr:gt(0):not(".page")').hover(function(){
			$(this).addClass('ui-tr-hover');
		},function(){
			$(this).removeClass('ui-tr-hover');
		});
		
		// ** 图标模式
		$('.ui-xicon').xicon();
		
		// ** 工具栏
		$('div.toolet ul#icons li').hover(function(){
			$(this).addClass('ui-state-hover');
			$(this).data('title', $(this).attr('title'));
			var s = '<div class="__box_icon_info">'+$(this).data('title')+'</div>';
			var xy = $(this).offset();
			$(this).attr('title', '');
			$(s).css({
				'position': 'absolute',
				'border': '2px solid #998898',
				'float': 'left',
				'clear': 'none',
				'padding': '5px 8px',
				'background': '#FFF',
				'left': xy.left,
				'top': xy.top+25
			}).appendTo('body');
		}, function(){
			$(this).removeClass('ui-state-hover');
			$('div.__box_icon_info').remove();
			$(this).attr('title', $(this).data('title'));
		});
		$.ajaxSetup({
			'error': function(rp){
				$.msgbox("<span style='color:#FF0000;font-weight:bold;'>Error Message:</span>"+rp.responseText, "Ajax Error");
			}
		});
	};
	
	
	
	
	
	
	$.fn.xicon = function(cfg){
		cfg = $.extend({}, cfg);
		this.each(function(){
						  
			var skin = "";	
			if($(this).attr('skin')=='error'){
				skin = "error";
			}
			else if($(this).attr('skin')=='highlight'){
				skin = "highlight";
			}
			else{
				skin = "default";
			}
			$(this).css({
				cursor: 'pointer',
				float: 'left',
				margin: '2px',
				padding: '4px 1px',
				position: 'relative'
			}).addClass('ui-corner-all ui-state-'+skin)
			.hover(function(){
				$(this).addClass('ui-state-hover');
			}, function(){
				$(this).removeClass('ui-state-hover');
			}).append('<span style="float:left;margin:0 4px;" class="ui-icon ui-icon-'+$(this).attr('icon')+'"></span>');
		});
	};
	
	
	$.fn.xbutton = function(cfg){
		cfg = $.extend({}, cfg);
		this.each(function(){
			var icon=$(this).attr('icon');
			if(icon!=undefined){
				$(this).button({
					'icons': {
						primary: 'ui-icon-'+icon
					},
					'create': function(ev,ui){
						$(ui).css({
							'font-size': '10px'
						});
					}
				});
			} else {
				$(this).button();
			}
		});
	};
	
	
	
	
	$.msgbox = function(msg,tit,cfg){
		if(tit==undefined || tit==''){
			tit = '提示信息';
		}
		var uuid = (new Date()).getTime();
		cfg = $.extend({
			modal: true,
			width: 450,
			title: tit,
			open: function(ev,ui){
				if($.browser.msie){
					$('div.ui-widget-overlay:last').css({
						width: document.body.clientWidth,
						height: document.body.clientHeight
					});
				}
				$('div.g-button span').xbutton();
			},
			close: function(ev,ui){
				$('div#_'+uuid+'_').remove();
			}
		}, cfg);
		var css="text-align: left; padding: 10px 25px;";
		var g_button="<div class='g-button'><span>确定</span></div>";
		var w = $('<div id="_'+uuid+'_" style="'+css+'">'+msg+'</div>').dialog(cfg);
	};
	
	
	
	$.fn.chicon = function(icon, skin, cfg){
		cfg = $.extend({}, cfg);
		this.each(function(){
			if(!$(this).hasClass('ui-xicon')){
				return true;
			}
			var type = $(this).attr("state");
			if(type == "show-hide"){
				if(skin=='error'){
					skin = "error";
					cfg.title = '隐藏';
				}
				else if(skin=='highlight'){
					skin = "highlight";
					if(cfg.title==undefined){
						cfg.title = '显示';
					}
				}
				else{
					skin = "default";
					if(cfg.title==undefined){
						cfg.title = '取消';
					}
				}
			}
			else{
				if(skin=='error'){
					skin = "error";
					cfg.title = '错误';
				}
				else if(skin=='highlight'){
					skin = "highlight";
					if(cfg.title==undefined){
						cfg.title = '确定';
					}
				}
				else{
					skin = "default";
					if(cfg.title==undefined){
						cfg.title = '取消';
					}
				}		
			}
				
			var o_skin = "";
			
			if($(this).attr('skin')=='error'){
				o_skin = "error";
			}
			else if($(this).attr('skin')=='highlight'){
				o_skin = "highlight";
			}
			else{
				o_skin = "default";
			}
		
			
			var o_icon = $(this).attr('icon');
			if(skin!=o_skin){
				$(this).removeClass('ui-state-'+o_skin);
				$(this).addClass('ui-state-'+skin);
				$(this).attr('skin', skin);
				$(this).attr('title', cfg.title);
			}
			if(icon!=o_icon){
				$('span', this).removeClass('ui-icon-'+o_icon);
				$('span', this).addClass('ui-icon-'+icon);
				$(this).attr('icon', icon);
			}
		});
	}
	
	
	
	$.formbox = function(fish, data, cfg){
		data = $.extend({
			id: '',
			title: '',
			img: '',
			link: '',
            time: (new Date()).getUTCFullYear()+'-'+((new Date()).getUTCMonth()+1)+'-'+(new Date()).getUTCDate(),
			memo: '',
			show: {
				title: 1,
				img: 1,
				link: 1,
                time: 1,
				memo: 1
			},
			func: function(rp){}
		}, data);
		cfg = $.extend({
			width: 435,
			position: ['center',70],
			open: function(ev,ui){
				$('div#_'+uuid+'_ .ui-button').xbutton();
				$('div#_'+uuid+'_ :input.img').bind('keyup', function(ev){
					$('div#_'+uuid+'_ img').attr('src', $(this).val());
				});
			},
			beforeclose: function(ev,ui){
				$(this).find('.memo').dropeditor();
				$('div#_'+uuid+'_').remove();
			}
		}, cfg);
		var uuid = (new Date()).getTime();
		var html = '<div id="_'+uuid+'_" class="formbox">';
		if(data.show.title){
			html += '<div>';
			html += '<span>标题：</span>';
			html += '<input type="text" class="title w350" value="'+data.title+'" />';
			html += '</div>';
		} else {
			html += '<input type="hidden" class="title" value="" />';
		}
		if(data.show.img){
			html += '<div class="imgshow"><img src="'+data.img+'" /></div>';
			html += '<div>';
			html += '<span>图片：</span>';
			html += '<input type="text" class="img w280" value="'+data.img+'" />&nbsp;';
/*			html += '<button class="ui-button" onclick="$.browserv({func:function(u){';					*/
			html += '<button class="ui-button" onmouseover="$.browpop(event,this,{folder:\'indexdata\',func:function(u){';
			html += '$(\'div#_'+uuid+'_ :input.img\').val(u);';
			html += '$(\'div#_'+uuid+'_ img\').attr(\'src\', u);';
			html += '}})" icon="folder-open">浏览</button>';
			html += '</div>';
		} else {
			html += '<input type="hidden" class="img" value="" />';
		}
		if(data.show.link){
			html += '<div>';
			html += '<span>链接：</span>';
			html += '<input type="text" class="link w350" value="'+data.link+'" />';
			html += '</div>';
		} else {
			html += '<input type="hidden" class="link" value="" />';
		}
        if(data.show.time){
            html += '<div>';
            html += '<span>时间：</span>';
            html += '<input type="text" class="time" value="'+data.time+'" readonly/>';
            html += '</div>';
        } else {
            html += '<input type="hidden" class="time" value="" />';
        }
		if(data.show.memo){
			html += '<div>';
			html += '<span style="vertical-align: top;">备注：</span><br>';
			html += '<span style="vertical-align: top;">简单文本<input type="radio" name="memo_type" onclick="$(this).parent().nextAll(\'.memo\').dropeditor()"> 富文本<input type="radio" name="memo_type" onclick="$(this).parent().nextAll(\'.memo\').fckeditor({\'toolbar\':myfulTool,\'folder\':\'indexdata\'})"></span><br>';
			html += '<textarea class="memo w350" style="height: 150px;">'+data.memo+'</textarea>';
			html += '</div>';
		} else {
			html += '<input type="hidden" class="memo" value="" />';
		}
		html += '<div style="margin-top: 10px; text-align: center;">';
		html += '<input type="hidden" class="id" value="'+data.id+'" />';
		html += '<input type="hidden" class="fish" value="'+fish+'" />';
		html += '<button class="ui-button" icon="check" title="确定" onclick="$(\'div#_'+uuid+'_\').formsave('+data.func+')">确定</button>';
		html += '<button class="ui-button" title="取消" icon="close" onclick="$(this).parents(\'.ui-dialog\').find(\'.ui-icon-closethick\').click()">取消</button>';
		html += '</div>';
		html += '</div>';
		var win = $(html).dialog(cfg);
        $(':input.time').datepicker({
            'dateFormat': 'yy-mm-dd'
        });
        //如果备注中含有标签，则使用编辑器，否则使用textarea
        if(data.memo.match(/<\/?[^>]*>/g)){
        	$('input[name="memo_type"]:last',win).click();
        }else{
        	$('input[name="memo_type"]:first',win).click();
        }
	};
	
	
	$.fn.formsave = function(func) {
		var me = this;
		$.ajax({
			url: '?model=banner&plugin=demo&action=edit',
			type: 'post',
			dataType: 'json',
			data: {				
				site_id: g_site_id,
				id: $(':input.id', me).val(),				
				fish: $(':input.fish', me).val(),
				title: $(':input.title', me).val(),				
				img: $(':input.img', me).val(),
				link: $(':input.link', me).val(),
                time: $(':input.time', me).val(),
				memo: $('textarea.memo', me).val()
			},
			success: function(rp){
				func(rp, me);
			}
		});
	}
	
	
	$.docp = function(cpdata){
		if (window.clipboardData) {
			window.clipboardData.clearData();
			window.clipboardData.setData("Text", cpdata)
		} else if (window.netscape) {
			try {
				var flag = netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			} catch (e) {
				if (flag == 0) {
					alert("You are using the Firefox browser, copy the function browser refuse！\nPlease in the browser address bar enter'about:config' and Enter \n and set'signed.applets.codebase_principal_support' to 'true'");
				} else {
					alert("你使用的是Firefox 浏览器,复制功能被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'");
				}
				return false;
			}
			var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
			if (!clip) {
				return;
			}
			var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
			if (!trans) {
				return;
			}
			trans.addDataFlavor('text/unicode');
			var str = new Object();
			var len = new Object();
			var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
			str.data = cpdata;
			trans.setTransferData("text/unicode", str, cpdata.length * 2);
			var clipid = Components.interfaces.nsIClipboard;
			if (!clip) {
				return false;
			}
			clip.setData(trans, null, clipid.kGlobalClipboard);
		}
	};
	
	
	$.uriParam = function(key){
		if(_uriparam!=null){
			return _uriparam[key];
		}
		_uriparam = new Array;
		var s=window.location.search.substring(1);
		var a=s.split('&');
		for(var i in a){
			var w=a[i];
			var v=w.indexOf('=');
			if(w.substring(0,v)==''){
				_uriparam['_'].push(w.substring(v+1));
			}
			else{
				_uriparam[w.substring(0,v)]=w.substring(v+1);
			}
		}
		return _uriparam[key];
	};
	
	
	
	$.cookie = function(){
		var $cki = this;
		var data = null;
		this.get = function(key){
			var f = key=='' || key==undefined || key==null;
			if(data!=null){
				if(f){
					return data;
				}
				return eval('data.'+key);
			} else {
				data = {};
			}
			var vx;
			var ck = document.cookie;
			var ar = ck.split(';');
			for(var i=0; i<ar.length; i++){
				var t = $.trim(ar[i]);
				var a = t.split('=');
				var ak = $.trim(a[0]);
				var av = $.trim(a[1]);
				eval('data.'+ak+'="'+av+'"');
				if(!f && ak==key){
					vx = av;
				}
			}
			if(!f){
				return vx;
			}
			return data;
		};
		this.set = function(key, vx){
			if(data==null){
				$cki.get();
			}
			var date = new Date();
            date.setTime(date.getTime() + (7 * 24 * 60 * 60 * 1000));
			var host=window.location.host;
			document.cookie = key+"="+encodeURIComponent(vx)+"; expires="+date.toUTCString()+"; path=/; domain="+host.substr(host.indexOf('.'));
		};
		return this;
	};
	
	
	
	$.fn.inp2slider = function(cfg){
		cfg = $.extend({}, cfg);
		this.each(function(ni,mo){
			$(this).click(function(){
				var me = this;
				var xy = $(this).offset();
				var w = $(this).width();
				var maxval = $(this).attr('maxvalue');
				var id = '_slider_'+(new Date()).getTime();
				$('<div id="'+id+'" />').css({
					position: 'absolute',
					left: xy.left+w+5,
					top: xy.top-105,
					height: 125,
					'z-index': 9999
				})
				.appendTo('body').slider({
					orientation: "vertical",
					range: "min",
					min: 0,
					max: maxval,
					value: parseInt($(me).val()),
					slide: function(event, ui){
						var uival = parseInt(ui.value);
						if(uival<10){
							$(me).val('0'+ui.value);
						} else {
							$(me).val(ui.value);
						}
					},
					stop: function(event, ui){
						$('div#'+id).remove();
					}
				});
			});
		});
	};

	
	
	
	$.isNumbo = function(ev){
		var oo = ev.srcElement ? ev.srcElement : ev.target;
		if($(oo).data('blur')!='init'){
			$(oo).data('blur', 'init');
			$(oo).bind('blur', function(){
				if($(this).val().search(/^\d*$/)==-1){
					$(this).trigger('focus');
				}
			});
		}
		var key = window.event ? ev.keyCode:ev.which;
		if(key==8 || key==0 || key==46 || key==37 
				|| key==38 || key==39 || key==40
				|| key==35 || key==36){
			return true;
		}
		// ** 小键盘输入
		if(key>=96 && key<=105){
			return true;
		}
		// ** Ctrl + V (86)  , Ctrl+C (67)
		if((ev.ctrlKey&&key==86)||(ev.ctrlKey&&key==67)){
			return true;
		}
		var keychar = String.fromCharCode(key);
		var reg = /\d/;
		return reg.test(keychar);
	};
	
	
	$.mbtruncate = function(str,limit,sep){
		var len = str.length;
		var idx=0, n=0, asc=0;
		if(limit>=len){
			return str;
		}
		var utfcn = /[^\x00-\xff]/g; 
		while(n<limit && idx<len){
			asc = str.substr(idx,1).charCodeAt(0);
			if(asc.toString().match(utfcn)!= null){
				idx += 2;
			} else {
				idx += 1;
			}
			n++;
		}
		return (idx<len) ? str.substr(0,idx).concat('...') : str.substr(0,idx);
	};
	
	
	$.fn.bubbleup = function(cfg){
		cfg = $.extend({
			'start': 'focus',
			'stop': 'blur',
			'text': '',
			'popText': ''
		}, cfg);
		var bubble = "<div class='ui-widget ui-corner-all ui-helper-hidden ui-state-highlight'";
		bubble += "style='text-align:left; float: left; padding: 8px; position: absolute;'></div>";
		this.each(function(ni,mo){
			cfg.popText = $(this).attr('popText');
			if(cfg.popText==undefined || cfg.popText==''){
				if(cfg.text!=''){
					cfg.popText = cfg.text;
				} else {
					cfg.popText = 'Text';
				}
			}
			var $win = null, $p=null;
			$(mo).bind(cfg.start, function(){
				if($(mo).data('bubbleup')=='on'){
					return false;
				}
				$(mo).parents('div').each(function(){
					if($p==null && $(this).css('position')!='static'){
						$p = $(this);
					}
				});
				var pleft=0, ptop=0;
				var height=parseInt($(mo).height());
				if($p==null){
					$win=$(bubble).appendTo('body');
				}
				else {
					var pxy = $p.offset();
					pleft = parseInt(pxy.left);
					ptop = parseInt(pxy.top);
					$win=$(bubble).appendTo($p);
				}
				var xy = $(mo).offset();
				var left = parseInt(xy.left);
				var top = parseInt(xy.top);
				$win.css({
					'left': left-pleft,
					'top': top-ptop+height+5
				}).html(cfg.popText).show();
				$(mo).data('bubbleup', 'on');
			});
			$(mo).bind(cfg.stop, function(){
				if($win!=null){
					$win.remove();
				}
				$(mo).data('bubbleup', 'off');
			});
			$(window).resize(function(){
				$(mo).trigger('blur').trigger('focus');
			});
		});
	};
	
	$(window).bind('load', me.init);


})(jQuery);
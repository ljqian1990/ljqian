$(function ()
{
    Mvp.init();
});

var imgcoreback = {
	input: ':input#_uploadimg',
	folder:'mvp',
	func: function(uri){
		$(':input#_uploadimg').val(uri);
		$('#_imgbrowser img').attr('src', uri);
		$('#_imgbrowser').show();
	}
};

var Mvp = (function ($)
{

    var me = this;
    var site_id = 0;
    var po_data = {};
    this.actimer = null;
    this.timefrq = 120000;
    $.ajaxSetup({
        'url': '/',
        'type': 'post',
        'dataType': 'json'
    });

    this.init = function ()
    {
        site_id = $(':input.wx-siteid').val();
        po_data.site_id = site_id;
        po_data.model = 'mvp';
        
        $(':input.srfrom').datepicker({
            'dateFormat': 'yy-mm-dd'
        });
        $(':input.srto').datepicker({
            'dateFormat': 'yy-mm-dd'
        });
    };

    this.xform = function (nid)
    {
    	if (nid==0 && !confirm("现在mvp数据为自动生成的，理论上不需要手动添加，确定需要添加？")) {
    		return true;
    	}
        if ($('div._mvp_infoForm').length > 0)
        {
            return false;
        }
        delete po_data.id;
        if (nid != 0)
        {
            po_data.id = nid;
        }
        po_data.action = 'form';
        $.ajax({
        	url:'?plugin=demo&model=mvp&action=form',
            data: po_data,
            dataType:"json",
            success: function (rp)
            {
                var winform = $('<div class="_mvp_infoForm">' + rp.html + '</div>').appendTo('body');
                winform.dialog({
                    title: '新闻信息',
                    width: 920,
                    position: [50, 50],
                    open: function (ev, ui)
                    {
                        $('textarea#_editor', winform).fckeditor({
                            'toolbar': myfulTool,
                            'folder' : 'mvp'
                        });
                        $('div.infoForm button', winform).xbutton();
                        $('div.infoForm :input._datepicker', winform).datepicker({
                            'dateFormat': 'yy-mm-dd'
                        });
                        $('div.infoForm button.close', winform).click(function ()
                        {
                            winform.dialog('close');
                        });
                        $('div.infoForm :input._dateslider', winform).inp2slider();
                        if (nid == 0) me.actimer = window.setInterval(me.autosave, me.timefrq);
                        
                        initSelect();
                    },
                    close: function ()
                    {
                        if (nid == 0) me.autosave();
                        winform.remove();
                        $.dropeditor();
                        if (me.actimer) window.clearInterval(me.actimer);
                    }
                });
            }
        });
    };
    
    this.initSelect = function(){
    	var obj = fenzu;
    	
    	var initcompdayhtml = "";
    	var compdayselect = $("select[name='compday']").attr("data");
    	var compday_k = 0;
    	$.each(obj.date, function(k,v){
    		if (compdayselect == v) {
    			compday_k = k;
    		}
    		initcompdayhtml += "<option index='"+k+"' value='"+v+"' "+((compdayselect==v)?"selected='selected'":"")+">"+v+"</option>";
    	});

    	$("select[name='compday']").html(initcompdayhtml);

    	var initcompdatehtml = "";
    	var compdateselect = $("select[name='compdate']").attr("data");    	
    	$.each(obj.list[compday_k], function(k,v){
    		initcompdatehtml += "<option index='"+k+"' value='"+v.name+"' "+((compdateselect==v.name)?"selected='selected'":"")+">"+v.name+"</option>";
    	});
    	$("select[name='compdate']").html(initcompdatehtml);

    	$("body").on("change", "select[name='compday']", function(){
    		var index = $(this).find("option:selected").attr("index");
    		var compdate = obj.list[index];
    		var html = "";
    		$.each(compdate, function(k,v){
    			html += "<option value='"+v.name+"'>"+v.name+"</option>";
    		});
    		
    		$("select[name='compdate']").html(html);
    	});
    }

    this.save = function (f)
    {
        if (f.name.value == '')
        {
            alert('请填写标题！');
            f.name.focus();
            return false;
        }
        
        
        $.ajax({
        	url:'?model=mvp&plugin=demo&action=edit',
            data: $(f).serialize(),
            success: function (rp)
            {
                var txt = '';
                if (rp.returnflag == 'OK')
                {
                    window.location.reload();
                    return true;
                } else if (rp.returnflag == 'DB_ERROR')
                {
                    txt = '失败：保存记录时数据库有错误发生，请稍后重试！';
                } else if (rp.returnflag == 'NO_PUBLISH')
                {
                    txt = '生成文件失败：未到发布时间！';
                } else
                {
                    txt = '保存记录失败！';
                }
                var w = $('<div>' + txt + '</div>').dialog({
                    width: 450,
                    title: '新闻提示',
                    close: function ()
                    {
                        w.remove();
                    }
                });
            }
        });
        return false;
    };

    this.triTimer = function (x)
    {
        if (x)
        {
            $('div.infoForm span.xtimer').show();
        } else
        {
            $('div.infoForm span.xtimer').hide();
        }
    };

    this.del = function (di)
    {
        var data = [];
        if (di == 0)
        {
            $('table.ui-table :checkbox:checked.di').each(function ()
            {
                data.push($(this).val());
            });
            if (data.length < 1)
            {
                alert('请选择你要删除的选项！');
                return false;
            } else if (!window.confirm('你确定删除选定的' + data.length + '项记录吗？'))
            {
                return false;
            }
        } else
        {
            if (!window.confirm("你确定要删除该条新闻吗？"))
            {
                return false;
            } else
            {
                data.push(di);
            }
        }
        $.ajax({
        	url:'?model=mvp&plugin=demo&action=del',
            data: {
                'site_id': site_id,
                'del_id': data
            },
            success: function (rp)
            {
                if (rp.returnflag == 'OK')
                {
                    alert('删除成功！');
                    window.location.reload();
                } else if (rp.returnflag == '__DENY')
                {
                    $.msgbox(rp.message);
                } else
                {
                    $.msgbox('删除失败！<br ></1>' + rp.text);
                }
            }
        });
    };

    this.searchForm = function (o, str)
    {
        if ($(o).attr('open') != 'on')
        {

            var xy = $(o).offset();
            if (str == "top")
            {
                $('div.searchForm').css({
                    'position': 'absolute',
                    'float': 'left',
                    'left': xy.left,
                    'top': xy.top + 35
                }).show(0);
            }

            else if (str == "bottom")
            {
                $('div.searchForm').css({
                    'position': 'absolute',
                    'float': 'left',
                    'left': xy.left,
                    'top': xy.top - 80
                }).show(0);
            }
            $('span.BTNsearch').attr('open', 'off');
            $(o).attr('open', 'on');
        } else
        {
            $('div.searchForm').hide(0);
            $(o).attr('open', 'off');
        }
    };

    this.doSearch = function ()
    {
        window.location.href = "/index.php?model=mvp&plugin=demo&site_id=" + site_id + "&type=" + $(':input.srtype').val() + "&keyword=" + $(':input.srkey').val() + "&from=" + $(':input.srfrom').val() + "&to=" + $(':input.srto').val();
    };

    this.cloSearch = function ()
    {
        $('div.searchForm').hide(0);
        $('span.BTNsearch').attr('open', 'off');
    };


    this.chg = function (oid, osort, nid, nsort)
    {

        $.ajax({
        	'url':'?model=mvp&plugin=demo&action=zort',
            'data': {
                'site_id': site_id,
                'oid': oid,
                'osort': osort,
                'nid': nid,
                'nsort': nsort
            },
            success: function (rp)
            {
                if (rp.returnflag != 'OK')
                {
                    $.msgbox('更新排序失败:' + rp.message);
                } else
                {
                    window.location.reload();
                }
            }
        });
    };

    this.autosave = function ()
    {
        var strhash = $('div._mvp_infoForm form').serialize();
        strhash = strhash.replace(/action=[^&]+/, 'action=autosave');
        $.ajax({
            'data': strhash,
            'success': function (rp)
            {
            },
            'error': function ()
            {
            }
        });
    };

    this.clearmc = function()
    {
    	$.ajax({
    		'url' : '?model=mvp&plugin=demo&action=clearmc',
    		success : function(rp) {
    			if (rp.returnflag != 'OK')
                {
                    $.msgbox('更新缓存失败:' + rp.message);
                } else
                {
                	$.msgbox('更新缓存成功');
                }
    		}
    	});
    }
    

    return this;

})(jQuery);
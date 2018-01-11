$(function ()
{
    Teamdata.init();
});

var imgcoreback = {
	input: ':input#_uploadimg',
	folder:'teamdata',
	func: function(uri){
		$(':input#_uploadimg').val(uri);
		$('#_imgbrowser img').attr('src', uri);
		$('#_imgbrowser').show();
	}
};

var Teamdata = (function ($)
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
        po_data.model = 'teamdata';       
        
        $('table.ui-table span.ui-xicon').click(function ()
        {
            var me = this;
            if ($(me).find('.ui-icon-arrow-1-n').size())
            {
                return;
            }
            if ($(me).find('.ui-icon-arrow-1-s').size())
            {
                return;
            }
            $.ajax({
            	url:'?model=teamdata&plugin=ydmatch&action=active',
                data: {
                    'site_id': site_id,
                    'id': $(me).attr('bid')
                },
                success: function (rp)
                {
                    if (rp.returnflag != 'OK')
                    {
                        $.msgbox('更改失败，请稍后重试！<br />' + rp.text);
                        return false;
                    }
                    if (rp.flag == 1)
                    {
                        $(me).chicon('check', 'highlight');
                    } else
                    {
                        $(me).chicon('close', 'default');
                    }
                }
            });
        });
    };

    this.xform = function (nid)
    {
        if ($('div._teamdata_infoForm').length > 0)
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
        	url:'?plugin=ydmatch&model=teamdata&action=form',
            data: po_data,
            dataType:"json",
            success: function (rp)
            {
                var winform = $('<div class="_teamdata_infoForm">' + rp.html + '</div>').appendTo('body');
                winform.dialog({
                    title: '队伍数据',
                    width: 920,
                    position: [50, 50],
                    open: function (ev, ui)
                    {
                        $('textarea#_editor', winform).fckeditor({
                            'toolbar': myfulTool,
                            'folder' : 'teamdata'
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

    this.save = function (f)
    {       
        $.ajax({
        	url:'?model=teamdata&plugin=ydmatch&action=save',
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

    this.autosave = function ()
    {
        var strhash = $('div._teamdata_infoForm form').serialize();
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
    		'url' : '?model=teamdata&plugin=ydmatch&action=clearmc',
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
            if (!window.confirm("你确定要删除该条信息吗？"))
            {
                return false;
            } else
            {
                data.push(di);
            }
        }
        $.ajax({
        	url:'?model=teamdata&plugin=ydmatch&action=del',
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
    
    this.import = function()
    {
    	
    }

    return this;

})(jQuery);
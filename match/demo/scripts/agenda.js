$(function ()
{
    Agenda.init();
});

var imgcoreback = {
	input: ':input#_uploadimg',
	folder:'angeda',
	func: function(uri){
		$(':input#_uploadimg').val(uri);
		$('#_imgbrowser img').attr('src', uri);
		$('#_imgbrowser').show();
	}
};

var Agenda = (function ($)
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
        po_data.model = 'agenda';
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
            	url:'?model=agenda&plugin=ydmatch&action=active',
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
        $(':input.srfrom').datepicker({
            'dateFormat': 'yy-mm-dd'
        });
        $(':input.srto').datepicker({
            'dateFormat': 'yy-mm-dd'
        });
    };

    this.xform = function (nid)
    {
        if ($('div._agenda_infoForm').length > 0)
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
        	url:'?plugin=ydmatch&model=agenda&action=form',
            data: po_data,
            dataType:"json",
            success: function (rp)
            {
                var winform = $('<div class="_agenda_infoForm">' + rp.html + '</div>').appendTo('body');
                winform.dialog({
                    title: '新闻信息',
                    width: 920,
                    position: [50, 50],
                    open: function (ev, ui)
                    {
                        $('div.infoForm button', winform).xbutton();
                        $('div.infoForm :input._datepicker', winform).datepicker({
                            'dateFormat': 'yy-mm-dd'
                        });
                        
                        $('div.infoForm :input._datepicker', winform).blur();
                        
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
        if (f.date.value == '')
        {
            alert('请填写赛程时间！');
            f.date.focus();
            return false;
        }
        
        $.ajax({
        	url:'?model=agenda&plugin=ydmatch&action=edit',
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
        	url:'?model=agenda&plugin=ydmatch&action=del',
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


    this.chg = function (oid, osort, nid, nsort)
    {

        $.ajax({
        	'url':'?model=agenda&plugin=ydmatch&action=zort',
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
        var strhash = $('div._agenda_infoForm form').serialize();
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

    this.addGroupForm = function(nid, gid)
    {
    	$.ajax({
        	url:'?plugin=ydmatch&model=agenda&action=addGroupForm',
            data: {id:nid, gid:gid},
            dataType:"json",
            success: function (rp)
            {
                var winform = $('<div class="_agenda_groupForm">' + rp.html + '</div>').appendTo('body');
                winform.dialog({
                    title: '新闻信息',
                    width: 920,
                    position: [50, 50],
                    open: function (ev, ui)
                    {
                        $('div.groupForm button', winform).xbutton();
                        
                        $('div.groupForm button.close', winform).click(function ()
                        {
                            winform.dialog('close');
                        });
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
                

                var compday = $("select[name='compdate']").attr("compday");
                var index = fenzu.date.indexOf(compday);
                var compdatelist = fenzu.list[index];
                
                var initcompdatehtml = "";
            	var compdateselect = $("select[name='compdate']").attr("data");
            	$.each(compdatelist, function(k,v){
            		initcompdatehtml += "<option index='"+k+"' value='"+v.name+","+v.sort+"' "+((compdateselect==v.name)?"selected='selected'":"")+">"+v.name+"</option>";
            	});
            	$("select[name='compdate']").html(initcompdatehtml);
            }
        });
    };
    
    this.addGroup = function(f)
    {
    	if (f.groupname.value == '')
        {
            alert('请填写分组名！');
            f.groupname.focus();
            return false;
        }
        
        $.ajax({
        	url:'?model=agenda&plugin=ydmatch&action=addGroup',
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
    }

    return this;

})(jQuery);
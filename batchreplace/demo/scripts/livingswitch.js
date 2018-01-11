$(function ()
{
    Livingswitch.init();
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

var Livingswitch = (function ($)
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
        po_data.model = 'livingswitch';
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
            	url:'?model=livingswitch&plugin=demo&action=active',
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
    
    this.chg = function (oid, osort, nid, nsort)
    {

        $.ajax({
        	'url':'?model=livingswitch&plugin=demo&action=zort',
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

    this.xform = function (nid)
    {
        if ($('div._livingswitch_infoForm').length > 0)
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
        	url:'?plugin=demo&model=livingswitch&action=form',
            data: po_data,
            dataType:"json",
            success: function (rp)
            {
                var winform = $('<div class="_livingswitch_infoForm">' + rp.html + '</div>').appendTo('body');
                winform.dialog({
                    title: '直播开关信息',
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
        $.ajax({
        	url:'?model=livingswitch&plugin=demo&action=edit',
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
                    title: '直播开关提示',
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
            if (!window.confirm("你确定要删除该条直播开关吗？"))
            {
                return false;
            } else
            {
                data.push(di);
            }
        }
        $.ajax({
        	url:'?model=livingswitch&plugin=demo&action=del',
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
    
    this.edit = function(id, state)
    {
    	$.ajax({
    		url:"?model=livingswitch&plugin=demo&action=editState",
    		data:{id:id, state:state},
    		type:"post",
    		dataType:"json",
    		success:function(rp){
    			if (rp.returnflag == 'OK') {
    				window.location.reload();
    			} else {
    				$.msgbox(rp.message);
    			}
    		}
    	});
    };

    this.autosave = function ()
    {
        var strhash = $('div._livingswitch_infoForm form').serialize();
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

    return this;

})(jQuery);
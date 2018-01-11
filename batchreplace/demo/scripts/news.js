$(function ()
{
    News.init();
});

var imgcoreback = {
	input: ':input#_uploadimg',
	folder:'news',
	func: function(uri){
		$(':input#_uploadimg').val(uri);
		$('#_imgbrowser img').attr('src', uri);
		$('#_imgbrowser').show();
	}
};

var News = (function ($)
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
        po_data.model = 'news';
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
            	url:'?model=news&plugin=demo&action=active',
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
        if ($('div._news_infoForm').length > 0)
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
        	url:'?plugin=demo&model=news&action=form',
            data: po_data,
            dataType:"json",
            success: function (rp)
            {
                var winform = $('<div class="_news_infoForm">' + rp.html + '</div>').appendTo('body');
                winform.dialog({
                    title: '新闻信息',
                    width: 920,
                    position: [50, 50],
                    open: function (ev, ui)
                    {
                        $('textarea#_editor', winform).fckeditor({
                            'toolbar': myfulTool,
                            'folder' : 'news'
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
        if (f.title.value == '')
        {
            alert('请填写标题！');
            f.title.focus();
            return false;
        }
        if (f.articletype.value == '')
        {
        	alert('请选择所属类型！');
            f.articletype.focus();
            return false;
        }
        
        $.ajax({
        	url:'?model=news&plugin=demo&action=edit',
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
        	url:'?model=news&plugin=demo&action=del',
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
        window.location.href = "/index.php?model=news&plugin=demo&site_id=" + site_id + "&type=" + $(':input.srtype').val() + "&keyword=" + $(':input.srkey').val() + "&from=" + $(':input.srfrom').val() + "&to=" + $(':input.srto').val();
    };

    this.cloSearch = function ()
    {
        $('div.searchForm').hide(0);
        $('span.BTNsearch').attr('open', 'off');
    };

    this.chglink = function (o)
    {
        if ($(o).attr('checked'))
        {
            $('span#linkPanel').show();
            $('tr#txtPanel').hide();
        } else
        {
            $('span#linkPanel').hide();
            $('tr#txtPanel').show();
        }
    };

    this.chg = function (oid, osort, nid, nsort)
    {

        $.ajax({
        	'url':'?model=news&plugin=demo&action=zort',
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
        var strhash = $('div._news_infoForm form').serialize();
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

    this.typechange = function(){
        var atype = $('#articletype').val();
        var gd_type = $('#gd_type').val();
        if(atype=='gamedata'){
            $.ajax({
                type: "get",
                'url':'?model=index&plugin=gamedata&action=catehtml&gd_type='+gd_type,
                dataType: "html",
                'success': function (rp){
                    var e = $("#gd_type_slct").length;
                    console.info(e);
                    if(e<=0){
                        $('#atypetd').append(rp);
                    }
                }
            });
        }else{
            $("#gd_type_slct").remove();
        }

    };
    

    return this;

})(jQuery);
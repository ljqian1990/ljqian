<?php
if (ISDEBUG) {
    return [
        'gametypecookie' => 'projectname_gametype', // gametype存放在cookie中的cookie的key名
        'usernamesession' => 'projectname_username', // user登录后username存放在session中的session的key名
        'site_url' => 'http://w1.dev.ztgame.com/', // 设置当前网站后台的url地址
        'htmlurl' => 'http://w1.dev.ztgame.com/projectname/html/', // 开发环境用于预览html生成文件的web地址前缀
        'wx_share_appid' => 'wxefefae06f7bfb308', // 微信分享用的公用appid
        'ucms_sites_dir' => '/usr/local/apache2/ucms/sites/', // 放到正是环境后，html文件的生成路径
        'htmlpath' => REALPATH . 'html/', // 开发环境，html文件的生成路径
        'domain_pri_url' => 'ztgame.com', // 种cookie时的有效域指定
        'act_uploads_uri' => '/upload/projectname/', // 访问上传的图片时的中间路径
        'img_act_url' => 'http://w1.dev.ztgame.com/projectname/', // 访问上传的图片文件的url前缀
        'act_uploads_path' => REALPATH . 'upload/projectname/', // 开发环境上传的图片的真实保存路径
        'act_uploads_path_pro' => '/usr/local/apache2/ucms/portal/upload/', // 生产环境上传的图片的真实保存路径
        'projectlist' => 30, // 项目的每页显示数量
        'userlist' => 30, // 用户每页显示数量
        'sso_passport' => 'http://sso.ztgame.com/passport/soap/soapserver_passport.php?WSDL', // 调用用户sso服务时的wsdl地址
        'ucms_rsync_shell_dir' => '/usr/local/apache2/ucms/rsync_shell/', // 读取svn_xxx_rsync.sh时的路径指定
        'projectname_login_user_info' => 'projectname_login_user_info',// 设置env中当前登录用户信息保存的key的名
        'projectname_cur_gametype_info' => 'projectname_cur_gametype_info',// 设置env中当前被选中的游戏信息保存的key的名
        'system_cache_dir' => '/tmp/.uscms-20120309114247/system_data/',// 设置ucms的systemcache地址路径
        'cdn_sapi_url' => 'http://sapi.ztgame.com/cdn/push',// sapi服务下cdn推送url设置        
    ];
} else {
    return [
        'gametypecookie' => 'projectname_gametype', // gametype存放在cookie中的cookie的key名
        'usernamesession' => 'projectname_username', // user登录后username存放在session中的session的key名
        'site_url' => 'http://w1.dev.ztgame.com/', // 设置当前网站后台的url地址
        'htmlurl' => 'http://w1.dev.ztgame.com/projectname/html/', // 开发环境用于预览html生成文件的web地址前缀
        'wx_share_appid' => 'wxefefae06f7bfb308', // 微信分享用的公用appid
        'ucms_sites_dir' => '/usr/local/apache2/ucms/sites/', // 放到正是环境后，html文件的生成路径
        'htmlpath' => REALPATH . 'html/', // 开发环境，html文件的生成路径
        'domain_pri_url' => 'ztgame.com', // 种cookie时的有效域指定
        'act_uploads_uri' => '/upload/projectname/', // 访问上传的图片时的中间路径
        'img_act_url' => 'http://w1.dev.ztgame.com/projectname/', // 访问上传的图片文件的url前缀
        'act_uploads_path' => REALPATH . 'upload/projectname/', // 开发环境上传的图片的真实保存路径
        'act_uploads_path_pro' => '/usr/local/apache2/ucms/portal/upload/', // 生产环境上传的图片的真实保存路径
        'projectlist' => 30, // 项目的每页显示数量
        'userlist' => 30, // 用户每页显示数量
        'sso_passport' => 'http://sso.ztgame.com/passport/soap/soapserver_passport.php?WSDL', // 调用用户sso服务时的wsdl地址
        'ucms_rsync_shell_dir' => '/usr/local/apache2/ucms/rsync_shell/', // 读取svn_xxx_rsync.sh时的路径指定
        'projectname_login_user_info' => 'projectname_login_user_info',// 设置env中当前登录用户信息保存的key的名
        'projectname_cur_gametype_info' => 'projectname_cur_gametype_info',// 设置env中当前被选中的游戏信息保存的key的名
        'system_cache_dir' => '/tmp/.uscms-20120309114247/system_data/',// 设置ucms的systemcache地址路径      
        'cdn_sapi_url' => 'http://172.30.104.240:8281/cdn/push',// sapi服务下cdn推送url设置
    ];
}
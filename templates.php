<?php
/**
 * 自定模板配置文件
 * 配置中以model及action为键，样例中的 model_name_1和action_name_1等
 * action_name_1下，可以配若干个数组，每个数组代表一个生成规则,每个规则依据一个模板生成一个文件或若干个连续的文件
 *     hash键数组可以配置多个post条件，如配置post中含有post_key键，并且值为post_value时，该条自定义模板规则才会生效
 *            投稿文章、图片中生成所有页面时，使用t_scope键，值为index,list分别对应首页，列表中的数据
 *            游戏、视频中生成页面时，使用t_scope键，值为info,list分别对应内容，列表中的数据
 *            新闻中生成所有页面时，使用t_scope键，值为super,index,list分别对应超首，首页，列表中的数据
 *            p2p中生成页面时，使用fish键，值为p2p_news,p2p_flash,p2p_update分别对应新闻公告，图片公告，最新更新中的数据
 *     scope、main、sub键数据配置生成文件的路径，
 *            scope取值 'site', 'act_s', 'act_d' 分别对应网站，静态活动，动态活动目录
 *            main值为由scope取值后的相对目录，默认为空，该指定目录需要手工建立
 *            sub值为main值指定后的相对目录，该目录若不存在程序会自动生成！
 *     tpl键配置生成文件时所用的模板
 *     html键配置生成的文件名
 *            文件名中有几个变量可以使用，其中详细页用::ID::代替对应记录ID值，列表页用::CAT::代替分类名，::PAGE::代替分页页数
 *            =====================================================================================
 *            新闻中生成单个页面时，html中使用::ID::替换为对应新闻的ID值，以生成不同的页面；
 *            如 'html'=>'news-::ID::.html', 会生成 news-1.html形式文件，::ID::替换为对应记录ID
 *            ====================================================================================
 *            新闻列表页中的html使用 ::CAT:: , ::PAGE:: 作为文件名中分类及分页的替换，
 *            如 'html'=>'list-::CAT::-::PAGE::.html'，会由页码生成 list-news-1.html ....形式
 *            ========================================================================================
 *     plugin值为temp-plugin/目录下自定义数据的插件文件名，不带.class.php后缀
 *
 */
return array (
		/*
		'article' => array (
				'makeall' => array (
						array (
								'hash' => array (
										't_scope' => 'index' 
								),
								'scope' => 'site',
								'main' => 'm',
								'sub' => 'article',
								'tpl' => 'm_article_list.tpl',
								'html' => 'article_index.html' 
						),
						array (
								'hash' => array (
										't_scope' => 'list' 
								),
								'scope' => 'site',
								'main' => 'm',
								'sub' => 'article',
								'tpl' => 'm_article_list.tpl',
								'html' => 'article_::CAT::_::PAGE::.html' 
						) 
				),
				'make' => array (
						array (
								'scope' => 'site',
								'main' => 'm',
								'sub' => 'article',
								'tpl' => 'm_article_info.tpl',
								'html' => 'article_::ID::.html' 
						) 
				) 
		),
		'calendar' => array (
				'make' => array (
						array (
								'scope' => 'site',
								'main' => 'm',
								'sub' => 'ssi/calendar',
								'tpl' => 'calendar_week.tpl',
								'html' => 'calendar_week_test.html' 
						) 
				) 
		),
		'download' => array (
				'make' => array (
						array (
								'scope' => 'site',
								'main' => 'url',
								'sub' => '',
								'tpl' => 'download_m.tpl',
								'html' => 'download_m.html' 
						),
						array (
								'scope' => 'site',
								'main' => 'url',
								'sub' => '',
								'tpl' => 'downloadConfig.tpl',
								'html' => 'downloadConfig.html' 
						) 
				) 
		),
		'gamedata' => array (
				'make' => array (
						array (
								'hash' => array (
										't_scope' => 'info' 
								),
								'scope' => 'site',
								'main' => 'm',
								'sub' => 'game',
								'tpl' => 'm_game_info.tpl',
								'html' => 'gameinfo_::ID::.html' 
						),
						array (
								'hash' => array (
										't_scope' => 'list' 
								),
								'scope' => 'site',
								'main' => 'ssi',
								'sub' => '',
								'tpl' => 'm_game_tree.tpl',
								'html' => 'm_game_tree.html' 
						) 
				) 
		),
		'indexcomm' => array (
				'make' => array (
						array (
								'hash' => array (
										'flag' => 'index_pic_ad' 
								),
								'scope' => 'site',
								'main' => 'm',
								'sub' => 'ssi',
								'tpl' => 'ssi_m_index_focus.tpl',
								'html' => 'index_focus.html' 
						),
						array (
								'hash' => array (
										'flag' => 'index_pic_ad' 
								),
								'scope' => 'act_d',
								'main' => 'appapi',
								'sub' => 'www',
								'tpl' => 'ssi_m_index_focus.tpl',
								'html' => 'index_focus.html' 
						),
						array (
								'hash' => array (
										'flag' => 'index_pic_ad' 
								),
								'scope' => 'act_s',
								'main' => 'appapi',
								'sub' => 'www',
								'tpl' => 'ssi_m_index_focus.tpl',
								'html' => 'index_focus.html' 
						) 
				)
		),
		'news' => array (
				'make' => array (
						array (
								'hash' => array (),
								'scope' => 'site',
								'main' => 'html/news',
								'sub' => 'm',
								'tpl' => 'm_news_info.tpl',
								'html' => 'newsinfo_::ID::.html' 
						),
						array (
								'hash' => array (),
								'scope' => 'site',
								'main' => 'html/news',
								'sub' => 'm',
								'tpl' => 'm_news_info_json.tpl',
								'html' => 'newsinfo_json_::ID::.html' 
						) 
				)
				,
				'makeall' => array (
						array (
								'hash' => array (
										't_scope' => 'list' 
								),
								'scope' => 'site',
								'main' => 'html/news',
								'sub' => 'm',
								'tpl' => 'm_news_list.tpl',
								'html' => 'news_list_::CAT::_::PAGE::.html' 
						),
						array (
								'hash' => array (
										't_scope' => 'super' 
								),
								'scope' => 'site',
								'main' => '',
								'sub' => 'ssi',
								'tpl' => 'ssi_p2p_news_cat.tpl',
								'html' => 'ssi_p2p_news_::CAT::.html' 
						),
						array (
								'hash' => array (
										't_scope' => 'index' 
								),
								'scope' => 'site',
								'main' => '',
								'sub' => 'ssi',
								'tpl' => 'ssi_p2p_news_cat.tpl',
								'html' => 'ssi_p2p_news_::CAT::.html' 
						) 
				) 
		),
		'p2p' => array (
				'make' => array (
						array (
								'hash' => array (
										'fish' => 'p2p_news' 
								),
								'scope' => 'site',
								'main' => 'm',
								'sub' => 'p2p',
								'tpl' => 'ssi_p2p_news_cat.tpl',
								'html' => 'p2p_news.html' 
						),
						array (
								'hash' => array (
										'fish' => 'p2p_flash' 
								),
								'scope' => 'site',
								'main' => 'm',
								'sub' => 'p2p',
								'tpl' => 'ssi_p2p_news_cat.tpl',
								'html' => 'p2p_flash.html' 
						),
						array (
								'hash' => array (
										'fish' => 'p2p_update' 
								),
								'scope' => 'site',
								'main' => 'm',
								'sub' => 'p2p',
								'tpl' => 'ssi_p2p_news_cat.tpl',
								'html' => 'p2p_update.html' 
						) 
				) 
		),
		'picture' => array (
				'make' => array (
						array (
								'scope' => 'site',
								'main' => 'm',
								'sub' => 'picture',
								'tpl' => 'm_picture_list.tpl',
								'html' => 'picture-::CAT::-::PAGE::.html' 
						) 
				) 
		),
		'upimg' => array (
				'make' => array (
						array (
								'hash' => array (
										't_scope' => 'list' 
								),
								'scope' => 'site',
								'main' => 'm',
								'sub' => 'upimg',
								'tpl' => 'm_upimg_list.tpl',
								'html' => 'upimg-::CAT::-::PAGE::.html' 
						),
						array (
								'hash' => array (
										't_scope' => 'index' 
								),
								'scope' => 'site',
								'main' => 'm',
								'sub' => 'upimg',
								'tpl' => 'm_upimg_list.tpl',
								'html' => 'upimg_index.html' 
						) 
				) 
		),
		'video' => array (
				'make' => array (
						array (
								'hash' => array (
										't_scope' => 'list' 
								),
								'scope' => 'site',
								'main' => 'html',
								'sub' => 'video',
								'tpl' => 'm_videos_list.tpl',
								'html' => 'video-::CAT::-::PAGE::.html' 
						),
						array (
								'hash' => array (
										't_scope' => 'info' 
								),
								'scope' => 'site',
								'main' => 'html',
								'sub' => 'video',
								'tpl' => 'm_videos_info.tpl',
								'html' => 'video-::ID::.html' 
						) 
				) 
		) 
		*/
);
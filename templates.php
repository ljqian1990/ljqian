<?php
/**
 * �Զ�ģ�������ļ�
 * ��������model��actionΪ���������е� model_name_1��action_name_1��
 * action_name_1�£����������ɸ����飬ÿ���������һ�����ɹ���,ÿ����������һ��ģ������һ���ļ������ɸ��������ļ�
 *     hash������������ö��post������������post�к���post_key��������ֵΪpost_valueʱ�������Զ���ģ�����Ż���Ч
 *            Ͷ�����¡�ͼƬ����������ҳ��ʱ��ʹ��t_scope����ֵΪindex,list�ֱ��Ӧ��ҳ���б��е�����
 *            ��Ϸ����Ƶ������ҳ��ʱ��ʹ��t_scope����ֵΪinfo,list�ֱ��Ӧ���ݣ��б��е�����
 *            ��������������ҳ��ʱ��ʹ��t_scope����ֵΪsuper,index,list�ֱ��Ӧ���ף���ҳ���б��е�����
 *            p2p������ҳ��ʱ��ʹ��fish����ֵΪp2p_news,p2p_flash,p2p_update�ֱ��Ӧ���Ź��棬ͼƬ���棬���¸����е�����
 *     scope��main��sub���������������ļ���·����
 *            scopeȡֵ 'site', 'act_s', 'act_d' �ֱ��Ӧ��վ����̬�����̬�Ŀ¼
 *            mainֵΪ��scopeȡֵ������Ŀ¼��Ĭ��Ϊ�գ���ָ��Ŀ¼��Ҫ�ֹ�����
 *            subֵΪmainֵָ��������Ŀ¼����Ŀ¼�������ڳ�����Զ����ɣ�
 *     tpl�����������ļ�ʱ���õ�ģ��
 *     html���������ɵ��ļ���
 *            �ļ������м�����������ʹ�ã�������ϸҳ��::ID::�����Ӧ��¼IDֵ���б�ҳ��::CAT::�����������::PAGE::�����ҳҳ��
 *            =====================================================================================
 *            ���������ɵ���ҳ��ʱ��html��ʹ��::ID::�滻Ϊ��Ӧ���ŵ�IDֵ�������ɲ�ͬ��ҳ�棻
 *            �� 'html'=>'news-::ID::.html', ������ news-1.html��ʽ�ļ���::ID::�滻Ϊ��Ӧ��¼ID
 *            ====================================================================================
 *            �����б�ҳ�е�htmlʹ�� ::CAT:: , ::PAGE:: ��Ϊ�ļ����з��༰��ҳ���滻��
 *            �� 'html'=>'list-::CAT::-::PAGE::.html'������ҳ������ list-news-1.html ....��ʽ
 *            ========================================================================================
 *     pluginֵΪtemp-plugin/Ŀ¼���Զ������ݵĲ���ļ���������.class.php��׺
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
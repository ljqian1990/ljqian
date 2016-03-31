<?php
$start = microtime(true);
use Piwik\Ini\IniWriter;
use Piwik\Ini\IniReader;
require_once 'vendor/autoload.php';

$reader = new IniReader();

$array = $reader->readFile('config.ini');
// $mapping = array (
// 		// 狂野社区url返回
// 		'communityurl' => array (
// 				'con' => 'Init',
// 				'act' => 'getCommunityUrl'
// 		),
// 		// 显示轮播列表功能
// 		'bannerlist' => array (
// 				'con' => 'Banner',
// 				'act' => 'list'
// 		),
// 		// 添加收藏、点赞记录
// 		'collectadd' => array (
// 				'con' => 'Collect',
// 				'act' => 'add'
// 		),
// 		// 保存分享日志记录
// 		'saveshare' => array (
// 				'con' => 'Collect',
// 				'act' => 'saveShare'
// 		),
// 		// 删除收藏、点赞记录
// 		'collectdel' => array (
// 				'con' => 'Collect',
// 				'act' => 'del'
// 		),
// 		// 显示收藏列表
// 		'collectcollectlist' => array (
// 				'con' => 'Collect',
// 				'act' => 'collectList'
// 		),
// 		// 发表评论
// 		'commentsave' => array (
// 				'con' => 'Comment',
// 				'act' => 'save'
// 		),
// 		// 显示新闻评论列表
// 		'commentnlist' => array (
// 				'con' => 'Comment',
// 				'act' => 'nlist'
// 		),
// 		'commentnlistpraise' => array (
// 				'con' => 'Comment',
// 				'act' => 'nlistPraise'
// 		),
// 		// 显示回复我的评论列表
// 		'commentmlist' => array (
// 				'con' => 'Comment',
// 				'act' => 'mlist'
// 		),
// 		// 登录操作
// 		'loginlogin' => array (
// 				'con' => 'Login',
// 				'act' => 'login'
// 		),
// 		// 验证密保卡操作
// 		'loginpasscardcheck' => array (
// 				'con' => 'Login',
// 				'act' => 'passcardcheck'
// 		),
// 		// 获取验证码图片操作
// 		'logingetvcode' => array (
// 				'con' => 'Login',
// 				'act' => 'getvcode'
// 		),
// 		// 注册成功回调操作
// 		'loginregistersuccess' => array (
// 				'con' => 'Login',
// 				'act' => 'registersuccess'
// 		),
// 		// 返回注册操作跳转的url
// 		'loginregister' => array (
// 				'con' => 'Login',
// 				'act' => 'register'
// 		),
// 		// 返回快速注册页面用户需知协议URL
// 		'logingetagreement' => array (
// 				'con' => 'Login',
// 				'act' => 'getagreement'
// 		),
// 		// 判断usertoken是否有效，即判断当前用户是否还有效的接口
// 		'loginisusefulusertoken' => array (
// 				'con' => 'Login',
// 				'act' => 'isusefulusertoken'
// 		),
// 		// 登出接口
// 		'loginlogout' => array (
// 				'con' => 'Login',
// 				'act' => 'logout'
// 		),
// 		// 显示任务列表
// 		'missionlist' => array (
// 				'con' => 'Mission',
// 				'act' => 'list'
// 		),
// 		// 显示每日任务的带有完成状态的任务列表
// 		'missioneverydaylist' => array (
// 				'con' => 'Mission',
// 				'act' => 'everydaylist'
// 		),
// 		// 在签到之前判断用户是否已经填写昵称和绑定手机号码，如果已经有对应的数据，就跳转到签到页面，否则，跳转到个人资料页面要求用户完善个人资料
// 		'missionbeforesign' => array (
// 				'con' => 'Mission',
// 				'act' => 'beforesign'
// 		),
// 		// 签到功能
// 		'missionsign' => array (
// 				'con' => 'Mission',
// 				'act' => 'sign'
// 		),
// 		// 判断当前用户当天是否已经签到过了
// 		'missionhavesigned' => array (
// 				'con' => 'Mission',
// 				'act' => 'havesigned'
// 		),
// 		// 显示积分获取或消费日志记录列表
// 		'missionshowlog' => array (
// 				'con' => 'Mission',
// 				'act' => 'showlog'
// 		),
// 		// 显示连续签到天数，签到总天数，总积分，剩余积分，消耗积分
// 		'missionsigndata' => array (
// 				'con' => 'Mission',
// 				'act' => 'signdata'
// 		),
// 		// 绑定手机任务接口
// 		'missionbindmobile' => array (
// 				'con' => 'Mission',
// 				'act' => 'bindmobile'
// 		),
// 		// 判断当前用户是否已经绑定过手机
// 		'missionhavebinded' => array (
// 				'con' => 'Mission',
// 				'act' => 'havebinded'
// 		),
// 		// 获取手机校验码功能
// 		'missiongetsmscode' => array (
// 				'con' => 'Mission',
// 				'act' => 'getsmscode'
// 		),
// 		// 获取新闻列表
// 		'newslist' => array (
// 				'con' => 'News',
// 				'act' => 'list'
// 		),
// 		// 获取新闻详细
// 		'newsdetail' => array (
// 				'con' => 'News',
// 				'act' => 'detail'
// 		),
// 		// 获取新闻详细，其中content已url的形式返回
// 		'newsdetailweb' => array (
// 				'con' => 'News',
// 				'act' => 'detailweb'
// 		),
// 		// 获取新闻类型列表
// 		'newstypelist' => array (
// 				'con' => 'News',
// 				'act' => 'typelist'
// 		),
// 		// 显示个人信息
// 		'personinfo' => array (
// 				'con' => 'Person',
// 				'act' => 'info'
// 		),
// 		// 编辑个人信息
// 		'personedit' => array (
// 				'con' => 'Person',
// 				'act' => 'edit'
// 		),
// 		// 获取省市区的列表信息
// 		'persongetarealist' => array (
// 				'con' => 'Person',
// 				'act' => 'getarealist'
// 		),
// 		// 处理上传头像的照片存储
// 		'personuploadicon' => array (
// 				'con' => 'Person',
// 				'act' => 'uploadicon'
// 		),
// 		// 根据uid返回用户的头像信息
// 		'persongeticonbyuid' => array (
// 				'con' => 'Person',
// 				'act' => 'geticonbyuid'
// 		),
// 		// 获得分类列表，包括父级和子集，放在同一个array中返回
// 		'strategylist' => array (
// 				'con' => 'Strategy',
// 				'act' => 'list'
// 		),
// 		// 获取攻略列表
// 		'strategystrategylist' => array (
// 				'con' => 'Strategy',
// 				'act' => 'strategylist'
// 		),
// 		// 获取攻略详细
// 		'strategydetail' => array (
// 				'con' => 'Strategy',
// 				'act' => 'detail'
// 		),
// 		// 获取攻略详细,其中content以url的形式返回
// 		'strategydetailweb' => array (
// 				'con' => 'Strategy',
// 				'act' => 'detailweb'
// 		),
// 		// 显示帮助与反馈模块中的常见问题列表
// 		'suggesthelplist' => array (
// 				'con' => 'Suggest',
// 				'act' => 'helplist'
// 		),
// 		// 显示帮助与反馈模块中的常见问题详细信息
// 		'suggesthelpdetail' => array (
// 				'con' => 'Suggest',
// 				'act' => 'helpdetail'
// 		),
// 		// 保存反馈信息
// 		'suggestsavesuggest' => array (
// 				'con' => 'Suggest',
// 				'act' => 'savesuggest'
// 		),
// 		// 显示个人的系统通知列表
// 		'sysnoticelist' => array (
// 				'con' => 'Sysnotice',
// 				'act' => 'list'
// 		),
// 		// 用户删除个人系统通知中的信息，假删除
// 		'sysnoticedel' => array (
// 				'con' => 'Sysnotice',
// 				'act' => 'del'
// 		),
// 		// 获取系统通知的详细信息
// 		'sysnoticegetdetail' => array (
// 				'con' => 'Sysnotice',
// 				'act' => 'getdetail'
// 		),
// 		// 获取系统通知的详细信息，其中content以url的形式返回
// 		'sysnoticegetdetailweb' => array (
// 				'con' => 'Sysnotice',
// 				'act' => 'getdetailweb'
// 		),
// 		//技能查询——职业列表显示
// 		'skilljoblist' => array (
// 				'con' => 'Skill',
// 				'act' => 'joblist'
// 		),
// 		//技能查询——专属能力,强袭技能,支援技能,通用技能
// 		'skilllist' => array(
// 				'con' => 'Skill',
// 				'act' => 'skilllist'
// 		),
// 		//技能查询——专属能力,强袭技能,支援技能,通用技能
// 		'skillexclusive' => array(
// 				'con' => 'Skill',
// 				'act' => 'exclusive'
// 		),
// 		//技能查询——专属能力,强袭技能,支援技能,通用技能
// 		'skillattacklist' => array(
// 				'con' => 'Skill',
// 				'act' => 'attacklist'
// 		),
// 		//技能查询——专属能力,强袭技能,支援技能,通用技能
// 		'skillsupportlist' => array(
// 				'con' => 'Skill',
// 				'act' => 'supportlist'
// 		),
// 		//技能查询——专属能力,强袭技能,支援技能,通用技能
// 		'skillcommonlist' => array(
// 				'con' => 'Skill',
// 				'act' => 'commonlist'
// 		),
// 		//积点商城——商品列表
// 		'shopgoodslist' => array(
// 				'con' => 'Shop',
// 				'act' => 'goodslist'
// 		),
// 		//积点商城——商品兑换
// 		'shopexchange' => array(
// 				'con' => 'Shop',
// 				'act' => 'exchange'
// 		),
// 		//试听专区——音乐——获取音乐列表
// 		'musiclist' => array(
// 				'con' => 'Music',
// 				'act' => 'list'
// 		),
// 		//试听专区——电台——获取电台类型列表
// 		'radiotypelist' => array(
// 				'con' => 'Radio',
// 				'act' => 'radiotypelist'
// 		),
// 		//试听专区——音乐——获取电台列表
// 		'radiolist' => array(
// 				'con' => 'Radio',
// 				'act' => 'radiolist'
// 		),
// 		//试听专区——音乐——更新电台播放次数
// 		'radioplaytime' => array(
// 				'con' => 'Radio',
// 				'act' => 'updatesum'
// 		),
// 		//手机壁纸列表
// 		'imagelist' => array(
// 				'con' => 'Image',
// 				'act' => 'list'
// 		),
// 		//试听专区——视频——获取视频分类列表信息
// 		'videotypelist' => array(
// 				'con' => 'Video',
// 				'act' => 'typelist'
// 		),
// 		//试听专区——视频——获取视频列表信息
// 		'videolist' => array(
// 				'con' => 'Video',
// 				'act' => 'list'
// 		),
// );

// define ( 'MAPPING', serialize ( $mapping ) );



var_dump($array);

$end = microtime(true);
echo $end-$start;
<?php
$start = microtime(true);
use Piwik\Ini\IniWriter;
use Piwik\Ini\IniReader;
require_once 'vendor/autoload.php';

$reader = new IniReader();

$array = $reader->readFile('config.ini');
// $mapping = array (
// 		// ��Ұ����url����
// 		'communityurl' => array (
// 				'con' => 'Init',
// 				'act' => 'getCommunityUrl'
// 		),
// 		// ��ʾ�ֲ��б���
// 		'bannerlist' => array (
// 				'con' => 'Banner',
// 				'act' => 'list'
// 		),
// 		// ����ղء����޼�¼
// 		'collectadd' => array (
// 				'con' => 'Collect',
// 				'act' => 'add'
// 		),
// 		// ���������־��¼
// 		'saveshare' => array (
// 				'con' => 'Collect',
// 				'act' => 'saveShare'
// 		),
// 		// ɾ���ղء����޼�¼
// 		'collectdel' => array (
// 				'con' => 'Collect',
// 				'act' => 'del'
// 		),
// 		// ��ʾ�ղ��б�
// 		'collectcollectlist' => array (
// 				'con' => 'Collect',
// 				'act' => 'collectList'
// 		),
// 		// ��������
// 		'commentsave' => array (
// 				'con' => 'Comment',
// 				'act' => 'save'
// 		),
// 		// ��ʾ���������б�
// 		'commentnlist' => array (
// 				'con' => 'Comment',
// 				'act' => 'nlist'
// 		),
// 		'commentnlistpraise' => array (
// 				'con' => 'Comment',
// 				'act' => 'nlistPraise'
// 		),
// 		// ��ʾ�ظ��ҵ������б�
// 		'commentmlist' => array (
// 				'con' => 'Comment',
// 				'act' => 'mlist'
// 		),
// 		// ��¼����
// 		'loginlogin' => array (
// 				'con' => 'Login',
// 				'act' => 'login'
// 		),
// 		// ��֤�ܱ�������
// 		'loginpasscardcheck' => array (
// 				'con' => 'Login',
// 				'act' => 'passcardcheck'
// 		),
// 		// ��ȡ��֤��ͼƬ����
// 		'logingetvcode' => array (
// 				'con' => 'Login',
// 				'act' => 'getvcode'
// 		),
// 		// ע��ɹ��ص�����
// 		'loginregistersuccess' => array (
// 				'con' => 'Login',
// 				'act' => 'registersuccess'
// 		),
// 		// ����ע�������ת��url
// 		'loginregister' => array (
// 				'con' => 'Login',
// 				'act' => 'register'
// 		),
// 		// ���ؿ���ע��ҳ���û���֪Э��URL
// 		'logingetagreement' => array (
// 				'con' => 'Login',
// 				'act' => 'getagreement'
// 		),
// 		// �ж�usertoken�Ƿ���Ч�����жϵ�ǰ�û��Ƿ���Ч�Ľӿ�
// 		'loginisusefulusertoken' => array (
// 				'con' => 'Login',
// 				'act' => 'isusefulusertoken'
// 		),
// 		// �ǳ��ӿ�
// 		'loginlogout' => array (
// 				'con' => 'Login',
// 				'act' => 'logout'
// 		),
// 		// ��ʾ�����б�
// 		'missionlist' => array (
// 				'con' => 'Mission',
// 				'act' => 'list'
// 		),
// 		// ��ʾÿ������Ĵ������״̬�������б�
// 		'missioneverydaylist' => array (
// 				'con' => 'Mission',
// 				'act' => 'everydaylist'
// 		),
// 		// ��ǩ��֮ǰ�ж��û��Ƿ��Ѿ���д�ǳƺͰ��ֻ����룬����Ѿ��ж�Ӧ�����ݣ�����ת��ǩ��ҳ�棬������ת����������ҳ��Ҫ���û����Ƹ�������
// 		'missionbeforesign' => array (
// 				'con' => 'Mission',
// 				'act' => 'beforesign'
// 		),
// 		// ǩ������
// 		'missionsign' => array (
// 				'con' => 'Mission',
// 				'act' => 'sign'
// 		),
// 		// �жϵ�ǰ�û������Ƿ��Ѿ�ǩ������
// 		'missionhavesigned' => array (
// 				'con' => 'Mission',
// 				'act' => 'havesigned'
// 		),
// 		// ��ʾ���ֻ�ȡ��������־��¼�б�
// 		'missionshowlog' => array (
// 				'con' => 'Mission',
// 				'act' => 'showlog'
// 		),
// 		// ��ʾ����ǩ��������ǩ�����������ܻ��֣�ʣ����֣����Ļ���
// 		'missionsigndata' => array (
// 				'con' => 'Mission',
// 				'act' => 'signdata'
// 		),
// 		// ���ֻ�����ӿ�
// 		'missionbindmobile' => array (
// 				'con' => 'Mission',
// 				'act' => 'bindmobile'
// 		),
// 		// �жϵ�ǰ�û��Ƿ��Ѿ��󶨹��ֻ�
// 		'missionhavebinded' => array (
// 				'con' => 'Mission',
// 				'act' => 'havebinded'
// 		),
// 		// ��ȡ�ֻ�У���빦��
// 		'missiongetsmscode' => array (
// 				'con' => 'Mission',
// 				'act' => 'getsmscode'
// 		),
// 		// ��ȡ�����б�
// 		'newslist' => array (
// 				'con' => 'News',
// 				'act' => 'list'
// 		),
// 		// ��ȡ������ϸ
// 		'newsdetail' => array (
// 				'con' => 'News',
// 				'act' => 'detail'
// 		),
// 		// ��ȡ������ϸ������content��url����ʽ����
// 		'newsdetailweb' => array (
// 				'con' => 'News',
// 				'act' => 'detailweb'
// 		),
// 		// ��ȡ���������б�
// 		'newstypelist' => array (
// 				'con' => 'News',
// 				'act' => 'typelist'
// 		),
// 		// ��ʾ������Ϣ
// 		'personinfo' => array (
// 				'con' => 'Person',
// 				'act' => 'info'
// 		),
// 		// �༭������Ϣ
// 		'personedit' => array (
// 				'con' => 'Person',
// 				'act' => 'edit'
// 		),
// 		// ��ȡʡ�������б���Ϣ
// 		'persongetarealist' => array (
// 				'con' => 'Person',
// 				'act' => 'getarealist'
// 		),
// 		// �����ϴ�ͷ�����Ƭ�洢
// 		'personuploadicon' => array (
// 				'con' => 'Person',
// 				'act' => 'uploadicon'
// 		),
// 		// ����uid�����û���ͷ����Ϣ
// 		'persongeticonbyuid' => array (
// 				'con' => 'Person',
// 				'act' => 'geticonbyuid'
// 		),
// 		// ��÷����б������������Ӽ�������ͬһ��array�з���
// 		'strategylist' => array (
// 				'con' => 'Strategy',
// 				'act' => 'list'
// 		),
// 		// ��ȡ�����б�
// 		'strategystrategylist' => array (
// 				'con' => 'Strategy',
// 				'act' => 'strategylist'
// 		),
// 		// ��ȡ������ϸ
// 		'strategydetail' => array (
// 				'con' => 'Strategy',
// 				'act' => 'detail'
// 		),
// 		// ��ȡ������ϸ,����content��url����ʽ����
// 		'strategydetailweb' => array (
// 				'con' => 'Strategy',
// 				'act' => 'detailweb'
// 		),
// 		// ��ʾ�����뷴��ģ���еĳ��������б�
// 		'suggesthelplist' => array (
// 				'con' => 'Suggest',
// 				'act' => 'helplist'
// 		),
// 		// ��ʾ�����뷴��ģ���еĳ���������ϸ��Ϣ
// 		'suggesthelpdetail' => array (
// 				'con' => 'Suggest',
// 				'act' => 'helpdetail'
// 		),
// 		// ���淴����Ϣ
// 		'suggestsavesuggest' => array (
// 				'con' => 'Suggest',
// 				'act' => 'savesuggest'
// 		),
// 		// ��ʾ���˵�ϵͳ֪ͨ�б�
// 		'sysnoticelist' => array (
// 				'con' => 'Sysnotice',
// 				'act' => 'list'
// 		),
// 		// �û�ɾ������ϵͳ֪ͨ�е���Ϣ����ɾ��
// 		'sysnoticedel' => array (
// 				'con' => 'Sysnotice',
// 				'act' => 'del'
// 		),
// 		// ��ȡϵͳ֪ͨ����ϸ��Ϣ
// 		'sysnoticegetdetail' => array (
// 				'con' => 'Sysnotice',
// 				'act' => 'getdetail'
// 		),
// 		// ��ȡϵͳ֪ͨ����ϸ��Ϣ������content��url����ʽ����
// 		'sysnoticegetdetailweb' => array (
// 				'con' => 'Sysnotice',
// 				'act' => 'getdetailweb'
// 		),
// 		//���ܲ�ѯ����ְҵ�б���ʾ
// 		'skilljoblist' => array (
// 				'con' => 'Skill',
// 				'act' => 'joblist'
// 		),
// 		//���ܲ�ѯ����ר������,ǿϮ����,֧Ԯ����,ͨ�ü���
// 		'skilllist' => array(
// 				'con' => 'Skill',
// 				'act' => 'skilllist'
// 		),
// 		//���ܲ�ѯ����ר������,ǿϮ����,֧Ԯ����,ͨ�ü���
// 		'skillexclusive' => array(
// 				'con' => 'Skill',
// 				'act' => 'exclusive'
// 		),
// 		//���ܲ�ѯ����ר������,ǿϮ����,֧Ԯ����,ͨ�ü���
// 		'skillattacklist' => array(
// 				'con' => 'Skill',
// 				'act' => 'attacklist'
// 		),
// 		//���ܲ�ѯ����ר������,ǿϮ����,֧Ԯ����,ͨ�ü���
// 		'skillsupportlist' => array(
// 				'con' => 'Skill',
// 				'act' => 'supportlist'
// 		),
// 		//���ܲ�ѯ����ר������,ǿϮ����,֧Ԯ����,ͨ�ü���
// 		'skillcommonlist' => array(
// 				'con' => 'Skill',
// 				'act' => 'commonlist'
// 		),
// 		//�����̳ǡ�����Ʒ�б�
// 		'shopgoodslist' => array(
// 				'con' => 'Shop',
// 				'act' => 'goodslist'
// 		),
// 		//�����̳ǡ�����Ʒ�һ�
// 		'shopexchange' => array(
// 				'con' => 'Shop',
// 				'act' => 'exchange'
// 		),
// 		//����ר���������֡�����ȡ�����б�
// 		'musiclist' => array(
// 				'con' => 'Music',
// 				'act' => 'list'
// 		),
// 		//����ר��������̨������ȡ��̨�����б�
// 		'radiotypelist' => array(
// 				'con' => 'Radio',
// 				'act' => 'radiotypelist'
// 		),
// 		//����ר���������֡�����ȡ��̨�б�
// 		'radiolist' => array(
// 				'con' => 'Radio',
// 				'act' => 'radiolist'
// 		),
// 		//����ר���������֡������µ�̨���Ŵ���
// 		'radioplaytime' => array(
// 				'con' => 'Radio',
// 				'act' => 'updatesum'
// 		),
// 		//�ֻ���ֽ�б�
// 		'imagelist' => array(
// 				'con' => 'Image',
// 				'act' => 'list'
// 		),
// 		//����ר��������Ƶ������ȡ��Ƶ�����б���Ϣ
// 		'videotypelist' => array(
// 				'con' => 'Video',
// 				'act' => 'typelist'
// 		),
// 		//����ר��������Ƶ������ȡ��Ƶ�б���Ϣ
// 		'videolist' => array(
// 				'con' => 'Video',
// 				'act' => 'list'
// 		),
// );

// define ( 'MAPPING', serialize ( $mapping ) );



var_dump($array);

$end = microtime(true);
echo $end-$start;
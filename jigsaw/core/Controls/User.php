<?php
/**
 * 用户管理类
 *
 * @author qianlijia <qianlijia@ztgame.com>
 * @copyright Copyright (c) 巨人网络 (http://www.ztgame.com)
 * @package core.library
 * @version 1.0.0
 */
namespace Jigsaw\Controls;

use SoapClient;
use Jigsaw\Models\User as UserModel;
use Jigsaw\Models\User2site as User2siteModel;
use Jigsaw\Models\Gametype as GametypeModel;

class User extends Base
{

    private $userModel = null;

    private $user2siteModel = null;

    private $gametypeModel = null;

    private $ssoService = null;

    public function __construct()
    {
        $this->userModel = UserModel::getInstance();
        $this->user2siteModel = User2siteModel::getInstance();
        $this->gametypeModel = GametypeModel::getInstance();
        $this->ssoService = $this->loadService('sso');
        parent::__construct();
    }

    public function Login()
    {
        $username = trim($this->request->getRequest('username', 'string', false));
        $passwd = trim($this->request->getRequest('password', 'string', false));
        
        $ret = $this->ssoService->ValidateAdOnlyByPasswd($username, $passwd);
        
        if ($ret->return_flag) {
            $_SESSION[$this->config->constant('usernamesession')] = $username;
            $this->initUser($username);
            $this->initGametype();
        } else {
            $this->exception->throwUserException($this->config->error('PASSWORD_ERROR'));
        }
        return true;
    }

    public function Logout()
    {
        session_destroy();
        return true;
    }

    /**
     * 通过sso帐号获取相关用户信息
     */
    public function findUserByName()
    {
        $account = $this->request->getRequest('account', 'string', false);
        
        $ret = $this->ssoService->QueryAdUserInfoByName($account);
        
        $userinfo = [
            'account' => $ret->user_name,
            'realname' => $ret->display_name,
            'department' => $ret->department,
            'staffnum' => $ret->staffnum
        ];
        return $userinfo;
    }

    /**
     * 获取指定id用户能访问到的游戏列表
     *
     * @return unknown
     */
    public function getGametypeListForUser()
    {
        $uid = $this->request->getRequest('uid', 'int', false);
        $info = $this->userModel->getUserById($uid);
        if (empty($info)) {
            $this->exception->throwUserException(sprintf($this->config->error('USER_NOT_EXIST'), $uid));
        }
        
        $gametypeids = $this->getGametypeidsByUid($uid);
        $gametypes = $this->gametypeModel->getGametypesByIds($gametypeids);
        return $gametypes;
    }
    /**
     * 获取当前登录用户能访问到的游戏列表
     *
     * @return unknown
     */
    public function getGametypeListForCuruser()
    {
        $curuser = $this->env->get($this->config->constant('jigsaw_login_user_info'), false);        
        
        $gametypeids = $this->getGametypeidsByUid($curuser['id']);
        $gametypes = $this->gametypeModel->getGametypesByIds($gametypeids);
        return $gametypes;
    }

    /**
     * 改变用户的游戏查看权限
     *
     * @return boolean
     */
    public function changeAuth()
    {
        $username = $this->request->getRequest('username', 'string', false);
        $auth = $this->request->getRequest('auth', 'array', false);
        $info = $this->userModel->getUserByName($username);
        if (empty($info)) {
            $this->exception->throwUserException(sprintf($this->config->error('USER_NOT_EXIST'), $username));
        }
        
        $uid = $info['id'];
        $gametypeids = $this->getGametypeidsByUid($uid);
        
        /**
         * 计算用户新设置的访问权限auths与用户现在拥有的访问权gametypeids的交集intersects
         * 计算用户新设置的访问权限auths与交集intersects的差集，得出需要新增的权限集合needAddAuths
         * 计算用户现在拥有的访问权限与交集intersects的差集，得出需要删除的权限集合needDelAuths
         */
        $intersects = array_intersect($auth, $gametypeids);
        $needAddAuths = array_diff($auth, $intersects);
        $needDelAuths = array_diff($gametypeids, $intersects);
        
        if (! empty($needAddAuths)) {
            foreach ($needAddAuths as $auth) {
                $this->user2siteModel->add($uid, $auth);
            }
        }
        
        if (! empty($needDelAuths)) {
            foreach ($needDelAuths as $auth) {
                $this->user2siteModel->delByUidAndGametypeid($uid, $auth);
            }
        }
        
        /**
         *
         * @deprecated
         *
         */
        // $this->userModel->updateAuth($uid, $auth);
        
        return true;
    }

    private function getGametypeidsByUid($uid)
    {
        $ret = $this->user2siteModel->findByUid($uid);
        $gametypeids = array_map(function ($val) {
            return $val['gametypeid'];
        }, $ret);
        return $gametypeids;
    }

    /**
     * 建厂当前登录的状态
     */
    public function checkLoginStatus()
    {
        return $this->func->getCurrentUser();
    }

    /**
     * 初始化用户信息
     *
     * @param unknown $username            
     */
    private function initUser($username)
    {
        $info = $this->userModel->getUserByName($username);
        if (empty($info)) {
            $user = [
                'name' => $username
            ];
            $this->userModel->addUser($user);
        } else {
            $this->userModel->updateLastlogintime($info['id']);
        }
        
        return true;
    }
    
    /**
     * 初始化gametype的cookie记录，如果cookie中已经存在gametype，就不再记录
     */
    private function initGametype()
    {
        $curGametype = $this->env->get($this->config->constant('jigsaw_cur_gametype_info'));
        if (empty($curGametype)) {
            $gametypeid = $this->getFirstGametypeForCuruser();            
            $this->func->setCookie($this->config->constant('gametypecookie'), $gametypeid);
        }
    }
    
    private function getFirstGametypeForCuruser()
    {
        $username = $this->func->getCurrentUser();
        $userinfo = UserModel::getInstance()->getUserByName($username);
        $uid = $userinfo['id'];
        $gametypeid = $this->user2siteModel->getFirstGametypeIdByUid($uid);
        if (empty($gametypeid)) {
            $this->exception->throwUserException($this->config->error('CUR_USER_HAS_NO_GAMETYPE_AUTH'));
        }
        return $gametypeid;
    }
    
    public function addUser()
    {
        $name = $this->request->getRequest('name', 'string', false);
        if ($this->userModel->getUserByName($name)) {
            $this->exception->throwUserException($this->config->error('USER_ALREADY_EXIST'));
        }
        $this->userModel->addUser(['name'=>$name]);
        return true;
    }
    
    public function getList()
    {
        $page = $this->request->getRequest('page', 'int', true, 1);
        $page = $page <= 1 ? 1 : $page;
        $size = $this->config->constant('userlist');
        $offset = ($page - 1) * $size;
        
        $username = $this->request->getRequest('username', 'string', true);
        $total = $this->userModel->getCount($username);
        $totalpage = ceil($total / $size);
        $list = $this->userModel->getUserList($offset, $size, $username);
        
        return [
            'total' => $total,
            'list' => $list
        ];
    }
    
    /**
     * 移除用户并移除用户权限表中的相应权限
     * @return boolean
     */
    public function removeUser()
    {
        $id = $this->request->getRequest('id', 'string', true);
        $this->userModel->delUser($id);
        
        $this->user2siteModel->delByUid($uid);
        return true;
    }
    
    /**
     * 更改用户角色
     * @return boolean
     */
    public function changeRoler()
    {
        $uid = $this->request->getRequest('uid');
        $roler = $this->request->getRequest('roler');
        
        if ($roler == userModel::ROLER_ADMIN) {
            $this->userModel->changeRolerToAdmin($uid);
        }
        
        if ($roler == userModel::ROLER_PM) {
            $this->userModel->changeRolerToPm($uid);
        }
        
        return true;
    }
    
    public function getUserDetail()
    {
        $uid = $this->request->getRequest('uid');
        $user = $this->userModel->getUserById($uid);
        $user2site = $this->user2siteModel->findByUid($uid);
        
        return ['user'=>$user, 'user2site'=>$user2site];
    }
}
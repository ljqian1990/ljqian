<?php
namespace Jigsaw\Controls;

use Jigsaw\Libraries\Factory as LibFactory;
use Jigsaw\Services\Factory as SerFactory;
use Jigsaw\Models\Log as LogModel;
use Jigsaw\Models\User as UserModel;
use Jigsaw\Models\User2site as User2siteModel;
use Jigsaw\Models\Gametype as GametypeModel;

class Base
{

    protected $config;

    protected $env;

    protected $exception;

    protected $func;

    protected $mcrypt;

    protected $request;
    
    protected $cache;

    public function __construct()
    {
        $this->config = LibFactory::loadConfig();
        $this->env = LibFactory::loadEnv();
        $this->exception = LibFactory::loadException();
        $this->func = LibFactory::loadFunc();
        $this->mcrypt = LibFactory::loadMcrypt();
        $this->request = LibFactory::loadRequest();
        $this->cache = LibFactory::loadCache();
        
        $this->InitLoginUserInfo();
        $this->InitCurGametypeInfo();
    }
    
    /**
     * 初始化目前登录用户的信息并保存到env中
     */
    private function InitLoginUserInfo()
    {
        if ($this->func->isLogin()) {
            $key = $this->config->constant('jigsaw_login_user_info');
            if (! $this->env->get($key)) {
                $username = $this->func->getCurrentUser();
                $userinfo = UserModel::getInstance()->getUserByName($username);
                $this->env->set($key, $userinfo);
            }
        }
    }

    /**
     * 初始化目前选中的gametype的项目信息，并保存到env中
     */
    private function InitCurGametypeInfo()
    {
        $gametype = $this->env->get('gametype');
        if (! empty($gametype)) {
            $key = $this->config->constant('jigsaw_cur_gametype_info');
            if (! $this->env->get($key)) {
                $gametypeinfo = GametypeModel::getInstance()->getGametypeById($gametype);
                $this->env->set($key, $gametypeinfo);
            }
        }
    }

    public function loadService($servicename)
    {
        $method_name = 'load' . ucfirst($servicename);
        if (! method_exists('Jigsaw\\Services\\Factory', $method_name)) {
            $this->exception->throwSystemException(sprintf($this->config->error('SERVICE_NOT_FOUND'), $servicename));
        }
        $serviceobj = call_user_func([
            'Jigsaw\\Services\\Factory',
            $method_name
        ]);
        return $serviceobj;
    }

        /**
     * 检查当前登录用户是否对当前选择的gametype有访问权限
     * @return boolean
     */
    public function checkAuth()
    {
        $user = $this->env->get($this->config->constant('jigsaw_login_user_info'), false);
        $gametype = $this->env->get($this->config->constant('jigsaw_cur_gametype_info'), false);
        
        if ($user['roler'] == UserModel::ROLER_ADMIN) {
            return true;
        }
        
        $uid = $user['id'];
        $gametypeid = $gametype['id'];
        if (empty($uid) || empty($gametypeid)) {
            $this->exception->throwUserException($this->config->error('UNLOGIN_OR_NO_GAMETYPE_ERROR'));
        }
        $ret = User2siteModel::getInstance()->findByUidAndGametypeid($uid, $gametypeid);
        if (empty($ret)) {
            $this->exception->throwUserException($this->config->error('NO_POWER_TO_VIEW_THIS_GAMETYPE'));
        } else {
            return true;
        }
    }

    /**
     * 检查当前登录用户是否具有管理员权限
     */
    public function checkAdmin()
    {
        $user = $this->env->get($this->config->constant('jigsaw_login_user_info'), false);
        if ($user['roler'] == UserModel::ROLER_ADMIN) {
            return true;
        } else {
            $this->exception->throwUserException($this->config->error('NEED_ADMIN_POWER'));
        }
    }
    
    public function __destruct()
    {
        LogModel::getInstance()->write();
    }
}
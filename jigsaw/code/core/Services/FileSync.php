<?php
/**
 * 提供调用ucms下的rsync同步脚本的方法
 * @author qianlijia<qianlijia@ztgame.com>
 * @date 2017.09.22
 * 
 */
namespace Projectname\Services;

class FileSync extends Base
{

    /**
     * 在env中获取gametype的信息后，推送指定额rsync脚本
     */
    public function Sync($gametype)
    {        
        $scriptname = 'svn_' . $gametype . "_rsync.sh";
        $fileScript = $this->config->constant('ucms_rsync_shell_dir') . $scriptname;       
		
        if (! file_exists($fileScript)) {
            return false;
        }
        $result = @exec("/bin/sh $fileScript", $out, $status);
        if (stripos($result, 'ok') !== false && $status == 0) {
            @exec("echo 'rsync_ok at ' `date +%H:%M:%S` >> " . $this->config->constant('system_cache_dir') . "filesync_`date +%F`.log");
            return true;
        } else {
            @exec("echo 'rsync_false at ' `date +%H:%M:%S` >> " . $this->config->constant('system_cache_dir') . "filesync_`date +%F`.log");
            return false;
        }
    }
}
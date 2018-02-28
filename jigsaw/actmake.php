<?php
ob_end_flush();
#flush();


$config = json_decode(file_get_contents("./actmakeconfig.json"), true);
$projectname = $config['projectname'];
_echo("解析出projectname为{$projectname},开始拷贝code...");

$src = '/usr/local/apache2/ucms/sites/actmake/code';
$dst = '.';
recurse_copy($src, $dst);

recurse_replace($dst, 'projectname', $projectname);


echo 'OK';


/**
 * 深拷贝
 */
function recurse_copy($src,$dst)
{
    $dir = opendir($src); 
    @mkdir($dst, 0755); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } else { 
                copy($src . '/' . $file,$dst . '/' . $file);
                _echo("拷贝 {$src}/{$file} 到 {$dst}/{$file}");
            }
        }
    }
    closedir($dir);
}

/*
 * 深替换
 */
function recurse_replace($src, $search, $replace)
{
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (!in_array($file, ['.', '..', 'actmake.php', 'actmakeconfig.json'])) {
            if ( is_dir($src . '/' . $file) ) { 
                recurse_replace($src . '/' . $file, $search, $replace); 
            } else {
                $content = file_get_contents($src . '/' . $file);
                $content = str_replace($search, $replace, $content);
                $content = str_replace(ucfirst($search), ucfirst($replace), $content);
                
                if ($file == 'autoload_static.php') {
                    $content = str_replace("'".ucfirst($search[0])."'", ucfirst($replace[0]), $content);
                }
                
                file_put_contents($src . '/' . $file, $content);
                
                _echo("修改 {$src}/{$file} 的{$search}为{$replace}");
            }
        }
    }
    closedir($dir);
}

function _echo($str)
{
    echo $str."<br>";
    flush();
}
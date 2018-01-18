<?php

define('CORE_REDIS_CONFIGS',serialize([
    // 主库
    'master' => ['host' => '192.168.150.116', 'port'  => '6379'],
    // 从库
    'slave' => [
        ['host' => '192.168.150.116', 'port'  => '6379']
    ]
]));

(new class{
    public $mpid=0;
    public $works=[];
    public $max_precess=2000;
    public $new_index=0;
    
    private $starttime = '';
    private $endtime = '';

    public function __construct(){
        $this->starttime = microtime(true);
        try {
            swoole_set_process_name(sprintf('php-ps:%s', 'master'));
            $this->mpid = posix_getpid();
            $this->run();
            $this->processWait();
        }catch (\Exception $e){
            die('ALL ERROR: '.$e->getMessage());
        }
    }

    public function run(){
        for ($i=0; $i < $this->max_precess; $i++) {
            $this->CreateProcess($i);
        }
    }

    public function CreateProcess($index=null){
        $process = new swoole_process(function(swoole_process $worker)use($index){
            if(is_null($index)){
                $index=$this->new_index;
                $this->new_index++;
            }
            
            $configs = unserialize(CORE_REDIS_CONFIGS);
            $rediscli = new CRedis($configs);
            
            swoole_set_process_name(sprintf('php-ps:%s',$index));
            for ($j = 0; $j < 10000; $j++) {                
                
                $this->checkMpid($worker);
                
                $memkey = 'act_test_ljqian_swoole';                               
                $str = $rediscli->get($memkey);
                //sleep(1);
                $str = '{"data":{"signupdesc":{"desc":"<div>&nbsp;\u6bd4\u8d5b\u6d41\u7a0b<br \/>\n1\u3001\u6218\u961f\u961f\u957f\u53ef\u4ee5\u901a\u8fc7\u8d5b\u4e8b\u4e2d\u5fc3\u7684\u8d5b\u4e8b\u62a5\u540d\u9875\u9762\u62a5\u540d\u53c2\u8d5b\uff0c\u62a5\u540d\u65f6\u9700\u4fdd\u8bc1\u6218\u961f\u6210\u5458\u57285\u540d\u6216\u4ee5\u4e0a\uff0c\u4e14\u6210\u5458\u90fd\u5df2\u7ecf\u7ecf\u8fc7\u8ba4\u8bc1\u3002<br \/>\n2\u3001\u6218\u961f\u4e00\u65e6\u62a5\u540d\u6210\u529f\u5c06\u6709\u8d44\u683c\u5728\u6bd4\u8d5b\u670d\u4e2d\u8fdb\u884c\u8d5b\u4e8b\u9884\u9009\uff0c\u4f46\u53c2\u4e0e\u6bd4\u8d5b\u6210\u5458\u9700\u8981\u548c\u62a5\u540d\u65f6\u4e00\u81f4\uff0c\u5426\u5219\u5c06\u4e0d\u4f1a\u8bb0\u5f55\u6210\u7ee9\u3002<br \/>\n3\u3001\u6240\u6709\u62a5\u540d\u6210\u529f\u7684\u6218\u961f\u5c06\u4f1a\u9996\u5148\u53c2\u4e0e\u7ebf\u4e0a\u79ef\u5206\u8d5b\uff0c\u73a9\u5bb6\u9700\u8981\u5728\u6307\u5b9a\u65f6\u95f4\u5185\u53c2\u4e0e\u5339\u914d\uff0c\u6839\u636e\u89c4\u5b9a\u573a\u6b21\u7ed3\u7b97\u6700\u7ec8\u79ef\u5206\u3002<br \/>\n4\u3001\u73a9\u5bb6\u6839\u636e\u7ebf\u4e0a\u7684\u79ef\u5206\u8d5b\u51b3\u5b9a\u664b\u7ea7\u6dd8\u6c70\u8d5b\u540d\u989d<br \/>\n<br \/>\n\u6bd4\u8d5b\u7ec6\u5219<br \/>\n1\uff1a\u6240\u6709\u9009\u624b\u53c2\u8d5b\u9009\u624b\u5fc5\u987b\u5728\u5b98\u65b9\u8d5b\u4e8b\u4e2d\u5fc3\u8fdb\u884c\u8ba4\u8bc1\u3002<br \/>\n2\uff1a\u6240\u6709\u8d5b\u4e8b\u4e00\u5f8b\u7981\u6b62\u4f7f\u7528\u63d2\u4ef6\u3001\u7b2c\u4e09\u65b9\u7a0b\u5e8f\uff0c\u4ee5\u53ca\u624b\u6e38\u6a21\u62df\u5668\u7b49\u5de5\u5177\uff0c\u4e00\u7ecf\u53d1\u73b0\u5c06\u53d6\u6d88\u672c\u6b21\u6bd4\u8d5b\u8d44\u683c\u3002<br \/>\n3\uff1a\u8d5b\u524d\u9009\u624b\u6709\u8d23\u4efb\u8c03\u8bd5\u597d\u6bd4\u8d5b\u7528\u673a\uff0c\u786e\u4fdd\u624b\u673a\u5728\u6bd4\u8d5b\u8fc7\u7a0b\u4e2d\u6b63\u5e38\u4f7f\u7528\u3002<br \/>\n4\uff1a\u5173\u4e8e\u5f00\u5c40\u6389\u7ebf\uff0c\u5f00\u5c40\u672a\u8fde\u63a5\uff0c\u5c11\u4eba\u4e00\u65b9\u9700\u5728\u672a\u4ea7\u751f\u6bd4\u5206\u524d\uff0c\u7acb\u5373\u5168\u90e8\u9000\u51fa\u6bd4\u8d5b\uff0c\u5e76\u91cd\u65b0\u5f00\u5c40\u3002<br \/>\n5\uff1a\u5173\u4e8e\u4e2d\u9014\u6389\u7ebf\uff0c\u73a9\u5bb6\u5728\u6bd4\u8d5b\u4e2d\u9014\u6389\u7ebf\u540e\u4e14\u6bd4\u8d5b\u65f6\u95f4\u8d85\u8fc7\u4e09\u5206\u949f\uff0c\u5219\u9700\u8981\u81ea\u5df1\u91cd\u65b0\u94fe\u63a5\u3002<br \/>\n6\uff1a\u6bd4\u8d5b\u8fc7\u7a0b\u4e2d\u7531\u4e8e\u9009\u624b\u4e2a\u4eba\u56e0\u7d20\uff08\u5982\u8eab\u4f53\u6761\u4ef6\uff09\u5f15\u8d77\u7684\u6bd4\u8d5b\u65e0\u6cd5\u6b63\u5e38\u8fdb\u884c\uff0c\u7ec4\u59d4\u4f1a\u5c06\u4e0d\u5bf9\u6b64\u8d1f\u8d23\uff0c\u9009\u624b\u5c06\u4ee5\u5f03\u6743\u8bba\u5904\u3002<br \/>\n7\uff1a\u6bd4\u8d5b\u4e2d\u6218\u961f\u5982\u679c\u53d7\u5230\u4e24\u6b21\u72af\u89c4\u8b66\u544a\u540e\u5c06\u5931\u53bb\u6bd4\u8d5b\u8d44\u683c\u3002<br \/>\n8\uff1a\u9009\u624b\u53c2\u4e0e\u7403\u7403\u5927\u4f5c\u6218\u8d5b\u4e8b\u6240\u4ea7\u751f\u7684\u7167\u7247\u3001\u5f71\u50cf\u3001Replay\u7b49\u8d44\u6e90\u4f7f\u7528\u6743\u5f52\u5de8\u4eba\u7f51\u7edc\u6240\u6709\uff0c\u6240\u6709\u53c2\u8d5b\u9009\u624b\u5fc5\u987b\u63a5\u53d7\u540e\u65b9\u53ef\u53c2\u8d5b\u3002<br \/>\n9\uff1a\u5927\u8d5b\u7684\u6700\u7ec8\u89e3\u91ca\u6743\u5f52\u5de8\u4eba\u7f51\u7edc\u6240\u6709\u3002&nbsp;<\/div>\n"},"typelist":{"video":{"yd":{"title":"\u5e74\u7ec8\u603b\u51b3\u8d5b","tid":"142","abbr":"yd"},"bpl":{"title":"\u804c\u4e1a\u8054\u8d5b","tid":"103","abbr":"bpl","children":{"qbbpl":{"title":"\u5168\u90e8","tid":"103","abbr":"qbbpl"},"jm":{"title":"\u8282\u76ee","tid":"179","abbr":"jm"},"619team":{"title":"619","tid":"164","abbr":"619team"},"JOK":{"title":"JOK","tid":"165","abbr":"JOK"},"EOT":{"title":"EOT","tid":"167","abbr":"EOT"},"LK":{"title":"LK","tid":"171","abbr":"LK"},"AY":{"title":"AY","tid":"163","abbr":"AY"},"FT":{"title":"FT","tid":"169","abbr":"FT"},"TY":{"title":"TY","tid":"166","abbr":"TY"},"TWS":{"title":"TWS","tid":"173","abbr":"TWS"},"CMD":{"title":"CMD","tid":"172","abbr":"CMD"},"FOX":{"title":"FOX","tid":"168","abbr":"FOX"},"FLY":{"title":"FLY","tid":"177","abbr":"FLY"},"TB":{"title":"TB","tid":"178","abbr":"TB"},"RBT":{"title":"RBT","tid":"188","abbr":"RBT"},"SR":{"title":"SR","tid":"189","abbr":"SR"},"STS":{"title":"STS","tid":"190","abbr":"STS"},"SY":{"title":"SY","tid":"191","abbr":"SY"}}},"tt":{"title":"\u5854\u5766\u676f","tid":"85","abbr":"tt"},"xs":{"title":"\u7ebf\u4e0a\u516c\u5f00\u8d5b","tid":"124","abbr":"xs"}},"news":{"qb":{"title":"\u5168\u90e8","tid":"78","abbr":"qb"},"yd":{"title":"\u5e74\u7ec8\u603b\u51b3\u8d5b","tid":"149","abbr":"yd"},"bpl":{"title":"\u804c\u4e1a\u8054\u8d5b","tid":"105","abbr":"bpl"},"tt":{"title":"\u5854\u5766\u676f","tid":"112","abbr":"tt"},"xs":{"title":"\u7ebf\u4e0a\u516c\u5f00\u8d5b","tid":"123","abbr":"xs"}}},"menu":[{"title":"\u8d5b\u4e8b\u7ade\u731c","img":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/indexdata\/2017-04\/838624135.jpg","link":"http:\/\/ingame.superpopgames.com\/front\/GuessActivity\/index"},{"title":"\u804c\u4e1a\u6218\u961f","img":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/indexdata\/2017-04\/1.png","link":"profteam.html"}],"banner":[{"title":"\u51b0\u51b0","img":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/indexdata\/2017-12\/bb.jpg","link":"http:\/\/v.qq.com\/iframe\/player.html?vid=e0519d09w90&amp;tiny=0&amp;auto=0","isvideo":1},{"title":"12\u5f3a\u6218\u961f\u5de1\u793c","img":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/indexdata\/2018-01\/12.jpg","link":"newsdetails.html?id=2561","isvideo":0},{"title":"GOBGF\u7b2c\u4e00\u671f","img":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/indexdata\/2017-12\/660-214.jpg","link":"http:\/\/v.qq.com\/iframe\/player.html?vid=y0519hpz297&amp;tiny=0&amp;auto=0","isvideo":1},{"title":"\u864e\u7259\u52a9\u5a01BGF","img":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/indexdata\/2018-01\/0112.jpg","link":"newsdetails.html?id=2586","isvideo":0}],"videolist":[{"title":"\u300aGO\uff01BGF\u300b\u7b2c\u56db\u671f\uff1a\u76ee\u6807\uff0c\u6885\u5954\uff01","author":"Superpop&amp;Lollipop","img":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180112\/5a585a0a93e47.jpg?ver=1.3","date":"01\/12","viewnum":"1835","url":"http:\/\/v.qq.com\/iframe\/player.html?vid=c0532rdtrhh&tiny=0&auto=0"},{"title":"\u3010BGF\u3011\u5927\u6218\u4e00\u89e6\u5373\u53d1\uff0c\u9009\u624b\u8d5b\u524d\u5ba3\u8a00diss\u4e0d\u505c","author":"Superpop&amp;Lollipop","img":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180110\/5a55fd8a5f2a3.jpg?ver=1.3","date":"01\/10","viewnum":"1839","url":"http:\/\/v.qq.com\/iframe\/player.html?vid=c05313ib0tq&tiny=0&auto=0"},{"title":"\u3010BGF\u301112\u5f3a\u6218\u961f\u5b9a\u5986\u7167\u51fa\u7089\uff0c\u76d8\u70b9Joker\u804c\u4e1a\u5386\u7a0b","author":"Superpop&amp;Lollipop","img":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180104\/5a4de56b00b8e.jpg?ver=1.3","date":"01\/04","viewnum":"3895","url":"http:\/\/v.qq.com\/iframe\/player.html?vid=s0528sxf6qw&tiny=0&auto=0"},{"title":"\u300aGO\uff01BGF\u300b\u7b2c\u4e8c\u671f\uff1a\u4e2d\u56fd\u533a\u9884\u9009\u8d5b\u6536\u5b98 LEA\u8001\u70ae\u5f52\u6765","author":"Superpop&amp;Lollipop","img":"http:\/\/cdn.superpopgames.com\/data\/upload\/20171228\/5a44b1324f8c9.jpg?ver=1.3","date":"12\/28","viewnum":"6902","url":"http:\/\/v.qq.com\/iframe\/player.html?vid=e0525l81yky&tiny=0&auto=0"},{"title":"2017BGF\u4e2d\u56fd\u533a\u9884\u9009\u8d5b\u7b2c\u4e09\u8f6e\u7b2c\u4e00\u7ec4\u7b2c\u4e00\u573a","author":"Superpop&amp;Lollipop","img":"http:\/\/cdn.superpopgames.com\/data\/upload\/20171218\/5a378947e179a.jpg?ver=1.3","date":"12\/18","viewnum":"4424","url":"http:\/\/v.qq.com\/iframe\/player.html?vid=c05205zk8ou&tiny=0&auto=0"},{"title":"2017BGF\u4e2d\u56fd\u533a\u9884\u9009\u8d5b\u7b2c\u4e09\u8f6e\u7b2c\u4e00\u7ec4\u7b2c\u4e8c\u573a","author":"Superpop&amp;Lollipop","img":"http:\/\/cdn.superpopgames.com\/data\/upload\/20171218\/5a37892a7c1d8.jpg?ver=1.3","date":"12\/18","viewnum":"2890","url":"http:\/\/v.qq.com\/iframe\/player.html?vid=f052015szg5&tiny=0&auto=0"}],"newslist":[{"id":"2591","title":"\u4e09\u519b\u4eca\u665a\u5f00\u6218\uff0c\u51b3\u8d5b\u591c\u95e8\u7968\u9884\u7ea6\u5f00\u542f\uff01","date":"01\/12","viewnum":"1064","smeta":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180112\/5a587316de179.jpg?ver=1.3","istop":"1","url":""},{"id":"2594","title":"\u3010BGF\u3011\u534a\u51b3\u8d5b\u6218\u62a5\uff1a\u516d\u5f3a\u51fa\u7089\uff0c\u56fd\u533a\u961f\u4f0d\u9f50\u805a\u6885\u5954","date":"20\u5206\u524d","viewnum":"2","smeta":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180115\/5a5c11974b148.png?ver=1.3","istop":"0","url":""},{"id":"2593","title":"\u3010BGF\u3011\u6dd8\u6c70\u8d5b\u6218\u62a5\uff1aRBT\u3001619\u7387\u5148\u664b\u7ea7\u603b\u51b3\u8d5b","date":"16\u5c0f\u65f6\u524d","viewnum":"6","smeta":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180114\/5a5b288d23418.jpg?ver=1.3","istop":"0","url":""},{"id":"2592","title":"\u3010BGF\u3011\u5c0f\u7ec4\u8d5b\u6218\u62a5\uff1a619,RBT,JOK,LEA\u8fdb\u5165\u80dc\u8005\u7ec4","date":"01\/13","viewnum":"1","smeta":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180113\/5a59ccc905aa7.jpg?ver=1.3","istop":"0","url":""},{"id":"2589","title":"\u300aGO\uff01BGF\u300b\u7b2c\u56db\u671f\uff1a\u76ee\u6807\uff0c\u6885\u5954\uff01","date":"01\/12","viewnum":"1831","smeta":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180112\/5a585a0a93e47.jpg?ver=1.3","istop":"0","url":"http:\/\/v.qq.com\/iframe\/player.html?vid=c0532rdtrhh&tiny=0&auto=0"},{"id":"2576","title":"\u3010BGF\u3011\u5927\u6218\u4e00\u89e6\u5373\u53d1\uff0c\u9009\u624b\u8d5b\u524d\u5ba3\u8a00diss\u4e0d\u505c","date":"01\/10","viewnum":"1839","smeta":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180110\/5a55fd8a5f2a3.jpg?ver=1.3","istop":"0","url":"http:\/\/v.qq.com\/iframe\/player.html?vid=c05313ib0tq&tiny=0&auto=0"},{"id":"2575","title":"\u3010BGF\u3011\u5168\u7403\u603b\u51b3\u8d5b\u5c0f\u7ec4\u8d5b1\u670812\u65e5\u7387\u5148\u5f00\u6218","date":"01\/10","viewnum":"72","smeta":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180110\/5a55fda03b2ad.jpg?ver=1.3","istop":"0","url":""},{"id":"2574","title":"\u3010\u660e\u661f\u9762\u5bf9\u9762\u3011\u4e13\u8bbf619\u548c\u5c1a\uff0c\u56e2\u961f\u81f3\u4e0a\u6297\u538b\u4e4b\u738b","date":"01\/10","viewnum":"5","smeta":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180110\/5a55fdf47e56f.jpg?ver=1.3","istop":"0","url":""},{"id":"2578","title":"\u3010BGF\u3011\u97e9\u56fdODS\u6218\u961f\u4e13\u8bbf\uff1a\u6c42\u7a33\u4e3a\u4e3b\uff0c\u4f3a\u673a\u800c\u52a8","date":"01\/11","viewnum":"0","smeta":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180111\/5a570fe1714e2.jpg?ver=1.3","istop":"0","url":""},{"id":"2577","title":"\u3010BGF\u3011\u97e9\u56fdDJB\u6218\u961f\uff1a\u9762\u5bf9JOK\u3001619\u6709\u9488\u5bf9\u6027\u6218\u672f","date":"01\/11","viewnum":"0","smeta":"http:\/\/cdn.superpopgames.com\/data\/upload\/20180111\/5a570ec6cc5fc.jpg?ver=1.3","istop":"0","url":""}],"viewlist":[{"id":"25","name":"2017\u5e74\u5168\u7403\u603b\u51b3\u8d5b","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/competition\/2018-01\/123.jpg","rule":"\u5168\u7403\u603b\u51b3\u8d5b\u505a\u4e3a\u4e00\u5e74\u4e00\u5ea6\u7684\u5927\u578b\u79fb\u52a8\u7535\u7ade\u8d5b\u4e8b\u76db\u4f1a\uff0c\u662f\u4ee3\u8868\u300a\u7403\u7403\u5927\u4f5c\u6218\u300b\u6700\u9ad8\u7ade\u6280\u6c34\u5e73\u3001\u6700\u9ad8\u8363\u8a89\u7684\u6bd4\u8d5b\u3002\u6765\u81ea\u4e16\u754c\u5404\u5730\u768412\u652f\u7cbe\u82f1\u6218\u961f\u7ecf\u8fc7\u6fc0\u70c8\u89d2\u9010\uff0c\u6311\u9009\u51fa6\u652f\u4ee3\u8868\u6700\u5f3a\u6218\u529b\u7684\u961f\u4f0d\uff0c\u5c06\u4e8e2018\u5e742\u670810\u65e5\u5728\u4e0a\u6d77\u6885\u8d5b\u5fb7\u65af\u5954\u9a70\u6587\u5316\u4e2d\u5fc3\u5411\u6ce2\u62c9\u54e9\u52c7\u8005\u5723\u676f\u53d1\u8d77\u6700\u540e\u7684\u51b2\u51fb\u3002","shortname":"BGF","index_show":"1","firstprize":"-","secondprize":"-","thridprize":"-","agtypelist":[{"atid":"19","atname":"\u603b\u51b3\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2018-02","month":"02"}]},{"atid":"18","atname":"\u6dd8\u6c70\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2018-01","month":"01"}]},{"atid":"17","atname":"\u5c0f\u7ec4\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2018-01","month":"01"}]}]},{"id":"26","name":"2017BGF\u4e2d\u56fd\u533a\u9884\u9009\u8d5b","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/competition\/2018-01\/234.png","rule":"24\u652f\u56fd\u5185\u9876\u5c16\u6218\u961f\u4e3a2017\u5e74\u5168\u7403\u603b\u51b3\u8d5b\uff08BGF\uff09\u4e2d\u56fd\u8d5b\u533a\u76846\u4e2a\u5e2d\u4f4d\u5c55\u5f00\u6fc0\u70c8\u4e89\u593a\u3002\u53c2\u8d5b\u6218\u961f\u5305\u542b16\u652fBPL\u79cb\u5b63\u8d5b\u804c\u4e1a\u6218\u961f\u4ee5\u53ca2017\u5e74\u4e0b\u534a\u8d5b\u5b63\u79ef\u5206\u6392\u540d\u524d8\u7684\u961f\u4f0d\u3002","shortname":"\u9884\u9009\u8d5b","index_show":"1","firstprize":"-","secondprize":"-","thridprize":"-","agtypelist":[{"atid":"20","atname":"\u6dd8\u6c70\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2017-12","month":"12"}]},{"atid":"21","atname":"\u5c0f\u7ec4\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2017-12","month":"12"}]}]},{"id":"21","name":"2017\u5e74BPL\u79cb\u5b63\u8d5b","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/competition\/2017-08\/bpl.jpg","rule":"\u7403\u7403\u5927\u4f5c\u6218\u804c\u4e1a\u8054\u8d5b\uff08BPL\uff09\u662f\u5b98\u65b9\u6700\u9ad8\u7ea7\u522b\u7684\u804c\u4e1a\u8054\u8d5b\uff0c\u5c06\u670916\u652f\u7403\u7403\u9876\u5c16\u6218\u961f\u5bf9\u8d5b\u5b63\u51a0\u519b\u8363\u8a89\u4ee5\u53ca\u8d5b\u4e8b\u5956\u91d1\u5c55\u5f00\u6fc0\u70c8\u4e89\u593a\u300217\u5e74\u79cb\u5b63\u8d5b\u5c06\u4e8e9\u67087\u65e5\u5728\u4e0a\u6d77\u661f\u7403\u5f71\u68da\u6b63\u5f0f\u6253\u54cd\uff01","shortname":"BPL","index_show":"1","firstprize":"\u00a5666,666","secondprize":"\u00a5100,000","thridprize":"\u00a5100,000","agtypelist":[{"atid":"15","atname":"\u603b\u51b3\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2017-10","month":"10"}]},{"atid":"13","atname":"\u5b63\u540e\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2017-10","month":"10"}]},{"atid":"14","atname":"\u4fdd\u7ea7\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2017-10","month":"10"}]},{"atid":"12","atname":"\u5e38\u89c4\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2017-10","month":"10"},{"yearmonth":"2017-09","month":"09"}]}]},{"id":"18","name":"2017\u5e74\u5854\u5766\u676f\u7cbe\u82f1\u6311\u6218\u8d5b","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/competition\/2017-06\/TTC(1).png","rule":"2017\u5e74\u5854\u5766\u676f\u7cbe\u82f1\u6311\u6218\u8d5b\u662f\u5de8\u4eba\u7f51\u7edc\u548c\u963f\u91cc\u4f53\u80b2\u8054\u5408\u4e3b\u529e\u7684\u5b98\u65b9\u5927\u578b\u6bd4\u8d5b\uff0c\u5c06\u57287\u670822\u65e5-7\u670823\u65e5\u671f\u95f4\u5728\u6e56\u5357\u957f\u6c99\u706b\u70ed\u5f00\u6218\u3002\u6c47\u805aBPL\u9876\u5c16\u804c\u4e1a\u6218\u961f\u53ca\u8d5b\u4e8b\u79ef\u5206\u4f53\u7cfb\u6218\u961f\u53c2\u8d5b\uff0c\u65e8\u5728\u52a0\u6df1\u804c\u4e1a\u6218\u961f\u4e0e\u4e1a\u4f59\u6218\u961f\u95f4\u7684\u4ea4\u6d41\u548c\u78b0\u649e\u3002","shortname":"TTC","index_show":"1","firstprize":"\u00a5233,333","secondprize":"\u00a560,000","thridprize":"\u00a560,000","agtypelist":[{"atid":"11","atname":"\u603b\u51b3\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2017-07","month":"07"}]},{"atid":"10","atname":"\u6dd8\u6c70\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2017-07","month":"07"}]},{"atid":"9","atname":"\u5c0f\u7ec4\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2017-07","month":"07"}]}]},{"id":"3","name":"2017\u5e74BPL\u6625\u5b63\u8d5b","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/competition\/2017-04\/BPL.png","rule":"\u7403\u7403\u5927\u4f5c\u6218\u804c\u4e1a\u8054\u8d5b\uff08BPL\uff09\u662f\u300a\u7403\u7403\u5927\u4f5c\u6218\u300b\u5728\u56fd\u5185\u6700\u9ad8\u7ea7\u522b\u7684\u804c\u4e1a\u8054\u8d5b\uff0c\u5c06\u670916\u652f\u7403\u7403\u9876\u5c16\u6218\u961f\u5bf9\u8d5b\u5b63\u51a0\u519b\u8363\u8a89\u4ee5\u53ca\u8d5b\u4e8b\u5956\u91d1\u5c55\u5f00\u6fc0\u70c8\u4e89\u593a\u3002\u4e13\u4e1a\u7684\u8d5b\u4e8b\u821e\u53f0\uff0c\u5168\u65b0\u7684\u5206\u7ec4\u8d5b\u5236\uff0c\u661f\u5149\u56db\u6ea2\u7684\u89e3\u8bf4\u9635\u5bb9\uff0c\u8fd9\u662f\u4e00\u573a\u4e0d\u5bb9\u9519\u8fc7\u7684\u7cbe\u5f69\u8d5b\u4e8b\u3002","shortname":"BPL","index_show":"0","firstprize":"\u00a5666,666","secondprize":"\u00a5100,000","thridprize":"\u00a5100,000","agtypelist":[{"atid":"2","atname":"\u5b63\u540e\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2017-06","month":"06"},{"yearmonth":"2017-05","month":"05"}]},{"atid":"1","atname":"\u5e38\u89c4\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2017-05","month":"05"},{"yearmonth":"2017-04","month":"04"}]},{"atid":"3","atname":"\u4fdd\u7ea7\u8d5b","cstate":"1","yearmonthlist":[{"yearmonth":"2017-06","month":"06"}]}]},{"id":"6","name":"2016\u5e74BGF\u5168\u7403\u603b\u51b3\u8d5b","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/competition\/2017-05\/1.jpg","rule":"2016\u5e74\u300a\u7403\u7403\u5927\u4f5c\u6218\u300b\u5168\u7403\u603b\u51b3\u8d5b\u662f\u5de8\u4eba\u7f51\u7edc\u300a\u7403\u7403\u5927\u4f5c\u6218\u300b\u4e3a\u56de\u9988\u73a9\u5bb6\u3001\u804c\u4e1a\u9009\u624b\u53ca\u5168\u7403\u79fb\u52a8\u7535\u7ade\u7231\u597d\u8005\u800c\u4e3e\u529e\u7684\u4e00\u573a\u5927\u578b\u7efc\u5408\u6027\u8d5b\u4e8b\u76db\u4f1a\u3002\u8fd9\u573a\u76db\u4f1a\u4ee5\u201c\u6ce2\u62c9\u54e9\u4fdd\u536b\u6218\u201d\u6545\u4e8b\u4e3b\u7ebf\u4e32\u8054\uff0c\u6765\u81ea\u4e16\u754c\u5404\u5730\u6700\u5f3a\u768412\u652f\u300a\u7403\u7403\u5927\u4f5c\u6218\u300b\u7cbe\u82f1\u6218\u961f\u8fdb\u884c\u6fc0\u70c8\u89d2\u9010\uff0c\u6700\u7ec8\u9009\u62d4\u51fa2016\u5e74\u5ea6\u300a\u7403\u7403\u5927\u4f5c\u6218\u300b\u6700\u5f3a\u738b\u8005\uff0c\u534f\u52a9\u6ce2\u62c9\u54e9\u661f\u7403\u51fb\u8d25\u5927\u9b54\u738b\u201c\u5e93\u5e93\u5df4\u201d\u7684\u4fb5\u7565\u3002","shortname":"BGF","index_show":"0","firstprize":"\u00a51,000,000","secondprize":"\u00a5100,000","thridprize":"\u00a5100,000","agtypelist":[]},{"id":"8","name":"2016\u5e74BPL\u804c\u4e1a\u8054\u8d5b","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/competition\/2017-05\/2.jpg","rule":"2016\u5e74BPL\u804c\u4e1a\u8054\u8d5b\u662f\u300a\u7403\u7403\u5927\u4f5c\u6218\u300b\u9996\u5c4a\u804c\u4e1a\u8d5b\u4e8b\uff0c\u5d2d\u65b0\u7684\u7ebf\u4e0b\u573a\u9986\uff0c\u5168\u65b0\u7684\u5206\u7ec4\u8d5b\u4e8b\uff0c\u661f\u5149\u56db\u6ea2\u7684\u89e3\u8bf4\u9635\u5bb9\uff0c\u4ee5\u53ca\u5168\u65b0\u767b\u573a\u7684\u804c\u4e1a\u961f\u4f0d\u3002\u672c\u6b21\u53c2\u8d5b\u768416\u652f\u6218\u961f\u5c06\u901a\u8fc7\u62bd\u7b7e\u5206\u4e3aAB\u4e24\u7ec4\uff0c\u5411\u8d5b\u5b63\u51a0\u519b\u8363\u8a89\u53ca\u8d5b\u4e8b\u5956\u91d1\u5c55\u5f00\u6fc0\u70c8\u4e89\u593a\u3002\u7cbe\u5f69\u5373\u5c06\u5728\u821e\u53f0\u7efd\u653e\uff0c\u4e3a\u4e86\u8fd9\u6700\u7ec8\u7684\u8363\u8000\u800c\u6218\uff01","shortname":"BPL","index_show":"0","firstprize":"\u00a5233,333","secondprize":"\u00a566,666","thridprize":"\u00a566,666","agtypelist":[]},{"id":"7","name":"2016\u5e74\u5854\u5766\u676f\u7cbe\u82f1\u6311\u6218\u8d5b","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/competition\/2017-05\/123(1).jpg","rule":"2016\u5e74\u300a\u7403\u7403\u5927\u4f5c\u6218\u300b\u201c\u5854\u5766\u676f\u201d\u7cbe\u82f1\u6311\u6218\u8d5b\u843d\u5730ChinaJoy\uff0c\u7531\u5b98\u65b9\u4e3b\u529e\uff0c\u53c2\u8d5b\u6218\u961f\u5747\u7531\u6e38\u620f\u5185\u9876\u5c16\u6218\u961f\u548c\u660e\u661f\u73a9\u5bb6\u7ec4\u6210\u3002\u5854\u5766\u676f\u65e2\u662f\u300a\u7403\u7403\u5927\u4f5c\u6218\u300b\u4e00\u5468\u5e74\u4ee5\u6765\u5f00\u542f\u7684\u9996\u4e2a\u4e13\u4e1a\u8d5b\u4e8b\uff0c\u4e5f\u5c06\u4f1a\u662f\u7403\u74032016\u5e74\u8d5b\u4e8b\u7684\u98ce\u5411\u6807\u3002","shortname":"TTC","index_show":"0","firstprize":"\u00a566,666","secondprize":"\u00a520,000","thridprize":"\u00a520,000","agtypelist":[]}],"agenda":[{"abid":"581","sid":"30079","atname":"\u603b\u51b3\u8d5b","abname":"\u603b\u51b3\u8d5b\u7b2c7\u573a","teamallinfolist":[{"teamid":"10","shortname":"JOK","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/specialteam\/2018-01\/JOK.png"},{"teamid":"11","shortname":"LK","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/specialteam\/2018-01\/LK.png"},{"teamid":"16","shortname":"619","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/specialteam\/2018-01\/619.png"},{"teamid":"18","shortname":"RBT","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/specialteam\/2018-01\/RBT.png"},{"teamid":"19","shortname":"LEA","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/specialteam\/2018-01\/LEA.png"},{"teamid":"28","shortname":"SR","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/specialteam\/2018-01\/SR.png"}],"aname":"\u603b\u51b3\u8d5b","starttime":"02-10 15:00","isbo1":"0","aid":"271","a_starttime":"2018-02-10 15:00:00","a_endtime":"2018-02-10 17:20:00","cstate":1,"videolist":[""],"start_day":"02-10","start_time":"15:00","isover":0},{"abid":"574","sid":"30072","atname":"\u6dd8\u6c70\u8d5b","abname":"\u534a\u51b3\u8d5b\u7b2c7\u573a","teamallinfolist":[{"teamid":"19","shortname":"LEA","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/specialteam\/2018-01\/LEA.png"},{"teamid":"11","shortname":"LK","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/specialteam\/2018-01\/LK.png"},{"teamid":"28","shortname":"SR","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/specialteam\/2018-01\/SR.png"},{"teamid":"10","shortname":"JOK","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/specialteam\/2018-01\/JOK.png"},{"teamid":"36","shortname":"DAN","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/specialteam\/2018-01\/DAN.png"},{"teamid":"39","shortname":"ODS","logo":"http:\/\/act.superpopgames.com\/upload\/balls\/images\/specialteam\/2018-01\/ODS.png"}],"aname":"\u534a\u51b3\u8d5b","starttime":"01-14 18:00","isbo1":"0","aid":"270","a_starttime":"2018-01-14 18:00:00","a_endtime":"2018-01-14 20:00:00","cstate":3,"videolist":[""],"start_day":"01-14","start_time":"18:00","isover":1}],"livingswitch":[{"isiframe":"0","nexttime":"","state":"5","title":"2017BGF \u5168\u7403\u603b\u51b3\u8d5b","unstartimg":"\/upload\/balls\/images\/angeda\/2018-01\/0108.jpg","url":"http:\/\/chls.shihoutv.com\/public\/10045_prod\/playlist.m3u8"}]},"ret":1}';
                $rediscli->set($memkey, $str, 1);
                
                $str = $rediscli->get($memkey);
                $arr = json_decode($str, true);
                if ($arr['ret'] != 1) {
                    echo "error1\r\n";
                } else {
                    //echo 'OK';
                    //echo "\r\n";
                    //echo $rediscli->get($memkey);
                }
                //$rediscli->close();
                
                //sleep(1);
            }
        }, false, 1);
        $pid=$process->start();
        $this->works[$index]=$pid;
        return $pid;
    }
    
    public function checkMpid(&$worker){
        if(!swoole_process::kill($this->mpid,0)){
            $worker->exit();
            // 这句提示,实际是看不到的.需要写到日志中
            echo "Master process exited, I [{$worker['pid']}] also quit\n";
        }
    }

    public function rebootProcess($ret){
        $pid=$ret['pid'];
        $index=array_search($pid, $this->works);
        if($index!==false){
            $index=intval($index);
            $new_pid=$this->CreateProcess($index);
            //echo "rebootProcess: {$index}={$new_pid} Done\n";
            return;
        }
        throw new \Exception('rebootProcess Error: no pid');
    }

    public function processWait(){
        while(1) {
            if(count($this->works)){
                $ret = swoole_process::wait();
                if ($ret) {
                    $this->endtime = microtime(true);
                    
                    echo 'use time:'.($this->endtime - $this->starttime);                    
                 //   $this->rebootProcess($ret);
                }
            }else{
                break;
            }
        }
    }
});



class CRedis
{

    private $_ConnectPool = [];

    private $_Link = null;

    private $_Configs = [];


    public function __construct($configs)
    {
        $this->setConfigs($configs);
    }

    private function setConfigs($configs)
    {
        $this->_Configs = $configs;
    }

    private function _setCurrentConnect($isMaster = false)
    {
        if ($isMaster) {
            $_config = $this->getMasterConfig();      
			$key = 'master';
        } else {            
            $_config = $this->getConfig();            
			$key = 'slave';
        }
        
        //if (! isset($this->_ConnectPool[$key])) {
        //    $this->_ConnectPool[$key] = $this->_getConnect($_config);
        //}
        
        return $this->_Link = $this->_getConnect($_config);
        //return $this->_Link = $this->_ConnectPool[$key];
    }

    private function getMasterConfig()
    {
        return $this->_Configs['master'];
    }

    private function getSlaveConfig()
    {
        return $this->_Configs['slave'][array_rand($this->_Configs['slave'])];
    }
    
    private function getConfig()
    {
        $configs = $this->_Configs['slave'];
        $configs[] = $this->_Configs['master'];
        return $configs[array_rand($configs)];
    }

    private function _getConnect($config)
    {
        $redis = new Redis();
        if (empty($config['timeout'])) {
            $config['timeout'] = 5;
        }
        $redis->pconnect($config['host'], $config['port'], $config['timeout']);
        return $redis;
    }

    public function set($key, $val, $ct = 0)
    {
        $this->_setCurrentConnect(true);
        if (empty($ct)) {
            $ret = $this->_Link->set($key, $val);
        } else {
            $ret = $this->_Link->setex($key, $ct, $val);
        }
        //$this->close();
        return $ret;
    }

    public function get($key)
    {
        $this->_setCurrentConnect(false);
        $ret = $this->_Link->get($key);
        //$this->close();
        return $ret;
    }

    public function add($key, $val, $ct = 0)
    {
        $this->_setCurrentConnect(true);
        return $this->_Link->set($key, $val, [
            'nx',
            'ex' => $ct
        ]);
    }

    public function incr($key, $offset = 1)
    {
        $this->_setCurrentConnect(true);
        return $this->_Link->incrBy($key, $offset);
    }

    public function decr($key, $offset = 1)
    {
        $this->_setCurrentConnect(true);
        return $this->_Link->decrBy($key, $offset);
    }

    public function close()
    {
        $this->_Link->close();
    }
}
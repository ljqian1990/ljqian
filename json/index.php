<?php
$str = '{"username":{"name":"ljqian","age":28}}';
var_dump(json_decode($str,true));exit;

$str = '{ "game": "1", "zone": "2", "type": "1", "sort_list": [ 
{ "roleName": "骄阳自我", "zoneName": "封测区", "countryName": "唐国", "value": "1233601" }, 
{ "roleName": "鱼儿x", "zoneName": "封测区", "countryName": "魏国", "value": "1061345" }, 
{ "roleName": "至尊红颜", "zoneName": "封测区", "countryName": "唐国", "value": "1035611" }, 
{ "roleName": "英雄人君", "zoneName": "封测区", "countryName": "唐国", "value": "955935" }, 
{ "roleName": "黑娃oVo狂搞", "zoneName": "封测区", "countryName": "魏国", "value": "949387" }, 
{ "roleName": "梦海马", "zoneName": "封测区", "countryName": "魏国", "value": "941863" }, 
{ "roleName": "天边孤星", "zoneName": "封测区", "countryName": "魏国", "value": "939639" }, 
{ "roleName": "游vi侠", "zoneName": "封测区", "countryName": "周国", "value": "926635" }, 
{ "roleName": "神之0VS0诱惑", "zoneName": "封测区", "countryName": "周国", "value": "911478" }, 
{ "roleName": "海洋之心餓", "zoneName": "封测区", "countryName": "唐国", "value": "887400" }, 
{ "roleName": "皇族0伏地魔", "zoneName": "封测区", "countryName": "魏国", "value": "881199" }, 
{ "roleName": "杭州阿荣", "zoneName": "封测区", "countryName": "魏国", "value": "873372" }, 
{ "roleName": "ZBceshi001", "zoneName": "封测区", "countryName": "燕国", "value": "864336" },
{ "roleName": "圣天A老红军", "zoneName": "封测区", "countryName": "周国", "value": "856263" }, 
{ "roleName": "鹦鹉梦梦", "zoneName": "封测区", "countryName": "唐国", "value": "835299" }, 
{ "roleName": "跨区宝贝", "zoneName": "封测区", "countryName": "魏国", "value": "795130" }, 
{ "roleName": "红颜VS梨涡浅笑", "zoneName": "封测区", "countryName": "魏国", "value": "782019" }, 
{ "roleName": "英雄嫣然", "zoneName": "封测区", "countryName": "唐国", "value": "780582" }, 
{ "roleName": "圣天A天使", "zoneName": "封测区", "countryName": "周国", "value": "779301" }, 
{ "roleName": "天下无箭", "zoneName": "封测区", "countryName": "唐国", "value": "762578" } 
] }';
$data = json_decode($str, true);
var_dump($data);exit;
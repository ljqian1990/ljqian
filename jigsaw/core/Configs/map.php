<?php
/**
 * c：control层的类名
 * f：对应类方法
 * l：规定是否需要登录才能操作
 * g：规定是否需要判断当前登录人是否拥有当前所选gametype的访问权限
 * a:规定是否需要管理员权限才能够访问
 */
return [
    'user' => [
        'login' => [
            'c' => 'User',
            'f' => 'Login'
        ],
        'logout' => [
            'c' => 'User',
            'f' => 'Logout'
        ],
        'findsso' => [
            'c' => 'User',
            'f' => 'findUserByName',
            'l' => true,
            'a' => true
        ],
        'gametypelist' => [
            'c' => 'User',
            'f' => 'getGametypeListForCuruser',
            'l' => true
        ],
        'changeauth' => [
            'c' => 'User',
            'f' => 'changeAuth',
            'l' => true,
            'a' => true
        ],
        'checkstatus' => [
            'c' => 'User',
            'f' => 'checkLoginStatus'
        ],
        'add' => [
            'c' => 'User',
            'f' => 'addUser',
            'l' => true,
            'a' => true
        ],
        'list' => [
            'c' => 'User',
            'f' => 'getList',
            'l' => true,
            'a' => true
        ],
        'remove' => [
            'c' => 'User',
            'f' => 'removeUser',
            'l' => true,
            'a' => true
        ],
        'changeroler' => [
            'c' => 'User',
            'f' => 'changeRoler',
            'l' => true,
            'a' => true
        ],
        'detail' => [
            'c' => 'User',
            'f' => 'getUserDetail',
            'l' => true,
            'a' => true
        ]
    ],
    'gametype' => [
        'list' => [
            'c' => 'Gametype',
            'f' => 'getList',
            'l' => true
        ],
        'change' => [
            'c' => 'Gametype',
            'f' => 'ChangeGametype',
            'l' => true
        ],
        'current' => [
            'c' => 'Gametype',
            'f' => 'getCurrentGametype',
            'l' => true
        ],
        'info' => [
            'c' => 'Gametype',
            'f' => 'getInfo',
            'l' => true
        ],
        'edit' => [
            'c' => 'Gametype',
            'f' => 'save',
            'l' => true,
            'a' => true
        ],
        'del' => [
            'c' => 'Gametype',
            'f' => 'remove',
            'l' => true,
            'a' => true
        ]
    ],
    'project' => [
        'add' => [
            'c' => 'Project',
            'f' => 'save',
            'l' => true,
            'g' => true
        ],
        'edit' => [
            'c' => 'Project',
            'f' => 'save',
            'l' => true,
            'g' => true
        ],
        'del' => [
            'c' => 'Project',
            'f' => 'remove',
            'l' => true,
            'g' => true
        ],
        'list' => [
            'c' => 'Project',
            'f' => 'getList',
            'l' => true,
            'g' => true
        ],
        'info' => [
            'c' => 'Project',
            'f' => 'getInfo',
            'l' => true,
            'g' => true
        ],
        'upload' => [
            'c' => 'Project',
            'f' => 'uploadFile_v2',
            'l' => true,
            'g' => true
        ],
        'export' => [
            'c' => 'Project',
            'f' => 'exportList',
            'l' => true,
            'g' => true
        ],
        'batchsync' => [
            'c' => 'Project',
            'f' => 'batchSync',
            'l' => true,
            'g' => true
        ],
        'batchsave' => [
            'c' => 'Project',
            'f' => 'batchSave',
            'l' => true,
            'g' => true
        ]
    ],
    'channel' => [
        'list' => [
            'c' => 'Channel',
            'f' => 'getList',
            'l' => true
        ],
        'add' => [
            'c' => 'Channel',
            'f' => 'save',
            'l' => true
        ],
        'edit' => [
            'c' => 'Channel',
            'f' => 'save',
            'l' => true
        ],
        'info' => [
            'c' => 'Channel',
            'f' => 'getInfo',
            'l' => true
        ],
        'del' => [
            'c' => 'Channel',
            'f' => 'remove',
            'l' => true
        ]
    ],
    'test' => [
        'service' => [
            'c' => 'Test',
            'f' => 'testservice'
        ],
        'filesync' => [
            'c' => 'Test',
            'f' => 'filesync'
        ]
    ]
];
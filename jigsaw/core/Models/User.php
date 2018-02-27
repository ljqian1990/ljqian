<?php
namespace Jigsaw\Models;

class User extends Base
{

    /**
     * 规定1为管理员权限
     */
    const ROLER_ADMIN = 1;

    /**
     * 规定2为产品专员权限
     */
    const ROLER_PM = 2;

    protected static $_self;

    protected $table = 'users';

    public function addUser($user)
    {
        // $user['auth'] = json_encode([]);
        $user['lastlogintime'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $user);
    }

    public function getUserByName($name)
    {
        $info = $this->db->selectOne($this->table, '*', [
            'where' => [
                'name' => $name
            ]
        ]);
        return $info;
    }

    public function getUserById($id)
    {
        $info = $this->db->selectOne($this->table, '*', [
            'where' => [
                'id' => $id
            ]
        ]);
        return $info;
    }

    public function delUser($id)
    {
        $this->db->delete($this->table, [
            'where' => [
                'id' => $id
            ]
        ]);
    }

    public function updateLastlogintime($id)
    {
        $this->db->update($this->table, [
            'lastlogintime' => date('Y-m-d H:i:s')
        ], [
            'where' => [
                'id' => $id
            ]
        ]);
        return true;
    }

    /*
     * public function updateAuth($id, $auth=[])
     * {
     * $auth = json_encode($auth);
     * $this->db->update($this->table, ['auth'=>$auth], ['where'=>['id'=>$id]]);
     * }
     *
     * public function getAuth($id)
     * {
     * $ret = $this->db->selectOne($this->table, 'auth', ['where'=>['id'=>$id]]);
     * $auth = json_decode($ret['auth'], true);
     * return $auth;
     * }
     */
    
    /**
     * 根据id或者用户名获取对应角色
     *
     * @param unknown $idOrName            
     * @return boolean|unknown
     */
    public function getRoler($idOrName)
    {
        if (empty($idOrName)) {
            return false;
        }
        $int_id = (int) $idOrName;
        if ($int_id == $idOrName) {
            $ret = $this->db->selectOne($this->table, 'roler', [
                'where' => [
                    'id' => $idOrName
                ]
            ]);
        } else {
            $ret = $this->db->selectOne($this->table, 'roler', [
                'where' => [
                    'name' => $idOrName
                ]
            ]);
        }
        
        if (empty($ret)) {
            return false;
        }
        
        $roler = $ret['roler'];
        return $roler;
    }

    /**
     * 将角色权限改为管理员
     *
     * @param unknown $username            
     * @return boolean
     */
    public function changeRolerToAdmin($id)
    {
        $this->db->update($this->table, [
            'roler' => self::ROLER_ADMIN
        ], [
            'where' => [
                'id' => $id
            ]
        ]);
        return true;
    }

    /**
     * 将角色权限改为产品专员
     *
     * @param unknown $username            
     * @return boolean
     */
    public function changeRolerToPm($id)
    {
        $this->db->update($this->table, [
            'roler' => self::ROLER_PM
        ], [
            'where' => [
                'id' => $id
            ]
        ]);
        return true;
    }

    /**
     * 获取用户列表
     *
     * @param unknown $offset            
     * @param unknown $size            
     * @param string $name            
     * @return boolean
     */
    public function getUserList($offset, $size, $name = '')
    {
        $where = [];
        if (! empty($name)) {
            $where = [
                'name' => [
                    'like' => "%$name%"
                ]
            ];
        }
        
        $list = $this->db->select($this->table, '*', [
            'where' => $where,
            'order' => 'id desc',
            'limit' => "$offset,$size"
        ]);
        return $list;
    }

    public function getCount($name = '')
    {
        $where = [];
        if (! empty($name)) {
            $where = [
                'name' => [
                    'like' => "%$name%"
                ]
            ];
        }
        $count = $this->db->count($this->table, '*', [
            'where' => $where
        ]);
        return $count;
    }
}
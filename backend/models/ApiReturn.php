<?php
namespace app\models;

class ApiReturn
{
    public $state;
    public $message;
    public $type;
    public $jump_url;
    public $no_set;
    public $is_empty;
    public $count;
    public $data;

    /**
     * 获取实例
     * @param int $state
     * @param string $message
     * @param string $jump_url
     * @param string $type
     * @param int $no_set
     * @return ApiReturn
     */
    public static function Instance($state = 0,$message = '未知错误',$jump_url = '',$data = [],$count = 0,$is_empty = 0,$type = 'mobile',$no_set = 0){
        $res = new ApiReturn();
        $res->state = $state;
        $res->message = $message;
        $res->type = $type;
        $res->jump_url = $jump_url;
        $res->no_set = $no_set;
        $res->data = $data;
        $res->count = $count;
        $res->is_empty = $is_empty;
        return $res;
    }
}

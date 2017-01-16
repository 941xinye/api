<?php
namespace app\models;

class Saki
{
    public $keys;
    public $list;
    public $source;

    public $east;
    public $south;
    public $west;
    public $north;

    const TYPE_SAKI = 13; //日本麻将张数

    /**
     * 获取实例
     * @param $count
     * @return Saki|array
     */
    public static function Instance($count){
        $mod = new Saki();
        $mod->getSakiValues();
        shuffle($mod->list);
        for($i=0;$i<$count;$i++){
            self::get_res($mod->list,$mod->keys,$mod->east);
            self::get_res($mod->list,$mod->keys,$mod->south);
            self::get_res($mod->list,$mod->keys,$mod->west);
            self::get_res($mod->list,$mod->keys,$mod->north);
        }
        self::sort_res($mod->east);
        self::sort_res($mod->south);
        self::sort_res($mod->west);
        self::sort_res($mod->north);

        return $mod;
    }

    /**
     * 开局数据
     * @return array
     */
    public function getOpening(){
        $player = ['east'=>$this->east,'south'=>$this->south,'west'=>$this->west,'north'=>$this->north];
        $res = ['player'=>$player,'list'=>$this->list];
        return $res;
    }

    /**
     * 取牌
     * @param $list
     * @param $keys
     * @param $data
     */
    function get_res(&$list,$keys,&$data){
        $k = array_rand($list,1);
        $data[] = [$list[$k]=>$keys[$list[$k]],'key'=>$list[$k]];
        unset($list[$k]);
    }

    /**
     * 自动整牌
     * @param $data
     */
    function sort_res(&$data){
        foreach($data as $val){
            $key_arrays[]=$val['key'];
        }
        array_multisort($key_arrays,SORT_ASC,SORT_NUMERIC,$data);
    }

    /**
     * 获取麻将键数据
     * @return array
     */
    public function getSakiKeys(){
        $keys = [];
        $keys['11'] = '一万';
        $keys['12'] = '二万';
        $keys['13'] = '三万';
        $keys['14'] = '四万';
        $keys['15'] = '五万';
        $keys['16'] = '六万';
        $keys['17'] = '七万';
        $keys['18'] = '八万';
        $keys['19'] = '九万';

        $keys['21'] = '一条';
        $keys['22'] = '二条';
        $keys['23'] = '三条';
        $keys['24'] = '四条';
        $keys['25'] = '五条';
        $keys['26'] = '六条';
        $keys['27'] = '七条';
        $keys['28'] = '八条';
        $keys['29'] = '九条';

        $keys['31'] = '一饼';
        $keys['32'] = '二饼';
        $keys['33'] = '三饼';
        $keys['34'] = '四饼';
        $keys['35'] = '五饼';
        $keys['36'] = '六饼';
        $keys['37'] = '七饼';
        $keys['38'] = '八饼';
        $keys['39'] = '九饼';

        $keys['41'] = '东风';
        $keys['42'] = '南风';
        $keys['43'] = '西风';
        $keys['44'] = '北风';

        $keys['51'] = '红中';
        $keys['52'] = '发财';
        $keys['53'] = '白板';
        $this->keys = $keys;
    }

    /**
     * 获取麻将值列表数据
     * @return array
     */
    public function getSakiValues(){
        $this->getSakiKeys();
        $arr = [];
        $arr['1'] = [['11','11','11','11'],['12','12','12','12'],['13','13','13','13'],['14','14','14','14'],['15','15','15','15'],['16','16','16','16'],['17','17','17','17'],['18','18','18','18'],['19','19','19','19']];		//万
        $arr['2'] = [['21','21','21','21'],['22','22','22','22'],['23','23','23','23'],['24','24','24','24'],['25','25','25','25'],['26','26','26','26'],['27','27','27','27'],['28','28','28','28'],['29','29','29','29']];		//条
        $arr['3'] = [['31','31','31','31'],['32','32','32','32'],['33','33','33','33'],['34','34','34','34'],['35','35','35','35'],['36','36','36','36'],['37','37','37','37'],['38','38','38','38'],['39','39','39','39']];		//饼
        $arr['4'] = [['41','41','41','41'],['42','42','42','42'],['43','43','43','43'],['44','44','44','44']];		//字牌
        $arr['5'] = [['51','51','51','51'],['52','52','52','52'],['53','53','53','53']];							//三元牌

        $list = $source = [];
        foreach($arr as $k=>$v){
            foreach($v as $k1=>$v1){
                foreach($v1 as $k2=>$v2){
                    $list[] = $v2;
                    $source[] = $v2;
                }
            }
        }
        $this->list = $list;
        $this->source = $source;
    }
}

<?php
namespace app\backend\libs;
use yii\base\Component;

/**
 * Class Curl
 * @package app\backend\libs
 */
class Curl extends Component
{
    /**
     * @var string 请求url
     */
    public $url;
    /**
     * @var array 请求参数
     */
    public $params;
    /**
     * @var array 请求header
     */
    public $header;
    /**
     * @var int 请求超时时间
     */
    public $timeout;
    /**
     * @var datetime 请求时间
     */
    public $date;
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * 设置参数
     * @param string $url       请求地址
     * @param array $params     请求参数
     * @param array $header     请求header
     * @param int $timeout      超时时间
     */
    public function set_args($url, $params = [],$header = '',$timeout = 5){
        $this->url = $url;
        $this->params = $params;
        $this->timeout = $timeout;
        $this->header = $header;
        $this->date = date('Y-m-d H:i:s');
        if(is_array($params)&&is_array($this->header)&&$this->header[0]=='Content-type: text/json'){
            //判断是传递json格式的参数，有参数并且是数组的转为json
            $this->params = json_encode($params);
        }
    }

    /**
     * Http curl get
     * @param string $url
     * @param array  $headers
     * @param int    $timeout
     * @return mixed
     */
    public function curl_get($url,$headers='',$timeout = 5){
        $this->set_args($url,[],$headers,$timeout);
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $this->url);//请求地址
        if($this->header){
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $this->header);
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        $file_contents = curl_exec($ch);//获得返回值

        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);//获得http状态码
        $total_time = curl_getinfo($ch,CURLINFO_TOTAL_TIME);//获得总执行时间
        curl_close($ch);
        if($this->is_not_json($file_contents)){
            //不是json，异常
            $this->set_error_log($httpCode,$total_time,$url,[],'curl_get');
        }else{
            //正常
            $this->set_info_log($httpCode,$total_time,$url,[],'curl_get');
        }
        //判断返回值是json的话转为数组返回，不是的的话直接返回
        return $this->get_res($file_contents);
    }

    /**
     * Http curl post
     * @param string $url
     * @param array  $params
     * @param array  $headers
     * @param int    $timeout
     * @return mixed
     */
    public function curl_post($url,$params,$headers='',$timeout = 5){
        $this->set_args($url,$params,$headers,$timeout);
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $this->url); //请求地址
        if($this->header){
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $this->header);
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$this->params);
        $file_contents = curl_exec($ch);//获得返回值
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);//获得http状态码
        $total_time = curl_getinfo($ch,CURLINFO_TOTAL_TIME);//获得总执行时间
        curl_close($ch);
        if($this->is_not_json($file_contents)){
            //不是json，异常
            $this->set_error_log($httpCode,$total_time,$url,$params,'curl_post');
        }else{
            //正常
            $this->set_info_log($httpCode,$total_time,$url,$params,'curl_post');
        }
        //判断返回值是json的话转为数组返回，不是的的话直接返回
        return $this->get_res($file_contents);
    }

    /**
     * Http curl put
     * @param string $url
     * @param array  $params
     * @param array  $headers
     * @param int    $timeout
     * @return mixed
     */
    public function curl_put($url,$params,$headers='',$timeout = 5){
        $this->set_args($url,$params,$headers,$timeout);
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $this->url); //请求地址
        if($this->header){
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $this->header);
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$this->params);
        $file_contents = curl_exec($ch);//获得返回值
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);//获得http状态码
        $total_time = curl_getinfo($ch,CURLINFO_TOTAL_TIME);//获得总执行时间
        curl_close($ch);
        if($this->is_not_json($file_contents)){
            //不是json，异常
            $this->set_error_log($httpCode,$total_time,$url,$params,'curl_put');
        }else{
            //正常
            $this->set_info_log($httpCode,$total_time,$url,$params,'curl_put');
        }
        //判断返回值是json的话转为数组返回，不是的的话直接返回
        return $this->get_res($file_contents);
    }

    /**
     * Http curl del
     * @param string $url
     * @param array  $params
     * @param array  $headers
     * @param int    $timeout
     * @return mixed
     */
    public function curl_del($url,$params,$headers='',$timeout = 5){
        $this->set_args($url,$params,$headers,$timeout);
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $this->url);//请求地址
        if($this->header){
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $this->header);
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$this->params);
        $file_contents = curl_exec($ch);//获得返回值
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);//获得http状态码
        $total_time = curl_getinfo($ch,CURLINFO_TOTAL_TIME);//获得总执行时间
        curl_close($ch);
        if($this->is_not_json($file_contents)){
            //不是json，异常
            $this->set_error_log($httpCode,$total_time,$url,$params,'curl_del');
        }else{
            //正常
            $this->set_info_log($httpCode,$total_time,$url,$params,'curl_del');
        }
        //判断返回值是json的话转为数组返回，不是的的话直接返回
        return $this->get_res($file_contents);
    }

    /**
     * Http curl upload file
     * @param string $url
     * @param array  $params
     * @param array  $headers
     * @param int    $timeout
     * @return mixed
     */
    public function curl_upload_file($url,$params,$timeout = 5){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file_contents= curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);//获得http状态码
        $total_time = curl_getinfo($ch,CURLINFO_TOTAL_TIME);//获得总执行时间
        curl_close($ch);

        if($this->is_not_json($file_contents)){
            //不是json，异常
            $this->set_error_log($httpCode,$total_time,$url,$params,'curl_upload_file');
        }else{
            //正常
            $this->set_info_log($httpCode,$total_time,$url,$params,'curl_upload_file');
        }
        //判断返回值是json的话转为数组返回，不是的的话直接返回
        return $this->get_res($file_contents);
    }

    /**
     * 写入异常日志
     * @param int       $http_code    状态码
     * @param datetime  $date         总执行时间
     * @param string    $url          请求地址
     * @param array     $params       请求参数
     * @param string    $category     日志分类
     */
    public function set_error_log($http_code,$date,$url,$params,$category){
        $data = array();
        $data['http_code'] = $http_code;
        $data['date'] = $date;
        $data['url'] = $url;
        $data['params'] = $params;
        $data['func_type'] = $category;
        $data['log_date'] = date('Y-m-d H:i:s');
        \Yii::error('curl error：'.json_encode($data),$category);
    }

    /**
     * 写入日常日志
     * @param int       $http_code    状态码
     * @param datetime  $date         请求时间
     * @param string    $url          请求地址
     * @param array     $params       请求参数
     * @param string    $category     日志分类
     */
    public function set_info_log($http_code,$date,$url,$params,$category){
        $data = array();
        $data['http_code'] = $http_code;
        $data['date'] = $date;
        $data['url'] = $url;
        $data['params'] = $params;
        $data['func_type'] = $category;
        $data['log_date'] = date('Y-m-d H:i:s');
        \Yii::info('curl info：'.json_encode($data),$category);
    }

    /**
     * 判断不是json
     * @param $str
     * @return mixed
     */
    public function is_not_json($str){
        if(is_array($str)){
            return true;
        }
        json_decode($str,true);
        return (json_last_error())?true:false;
    }

    /**
     * 解析返回值并记录日志
     * 解析返回值
     * @param $res
     * @return mixed
     */
    public function get_res($res){
        $res = (!self::is_not_json($res))?json_decode($res,true):$res;
        return $res;
    }
}
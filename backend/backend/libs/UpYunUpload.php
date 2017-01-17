<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/11 10:01
 */

namespace app\backend\libs;

use Yii;
use yii\base\Component;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use yii\web\ForbiddenHttpException;

class UpYunUpload extends Component
{

    public $bucket = 'demo'; //空间名称

    public $operator_name = 'test'; //授权操作员的账号

    public $operator_pwd = 'test'; //授权操作员的密码
    /*
     * 接入点有四个值可选：
     * UpYun::ED_AUTO 根据网络条件自动选择接入点
     * UpYun::ED_TELECOM 电信接入点
     * UpYun::ED_CNC 联通网通接入点
     * UpYun::ED_CTT 移动铁通接入点
     * 默认参数为自动选择API接入点。但是我们推荐根据服务器网络状况，手动设置合理的接入点已获取最佳的访问速度。
     * \app\commands\UpYun::ED_TELECOM
     */
    public $ed_auto = UpYun::ED_AUTO;

    public $past_time = 30; //设置上传请求超时时间（默认30s）

    public static $instanceof = null;

    public $file_name = 'img'; //键名

    public $type = ['jpg', 'jpeg', 'gif', 'png', 'pdf', 'zip', 'rar']; //设置限制上传文件的类型

    public $size = 10485760; //默认大小限制10M

    public $chmod = 0755; //自动创建文件权限

    public $Paths = '/demo/'; //又拍云文件空间路径

    public $file_url = ''; //本地文件url

    public function getObject()
    {
        if (self::$instanceof === null) {
            self::$instanceof = new UpYun($this->bucket, $this->operator_name, $this->operator_pwd, $this->ed_auto, $this->past_time);
        }
        return self::$instanceof;
    }

    /**
     * 直接上传图片
     * @param array $imgs
     * @throws ForbiddenHttpException
     */
    public function ImgUpload($imgs){
        $res = [];
        try {
            foreach($imgs as $k=>$fh){
                if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $fh, $result)){
                    $type = $result[2];
                    $Paths = '/' . trim(trim($this->Paths, '/'), '\\') . '/' . md5(time().$k).'.'.$type;
                    $fh = base64_decode(str_replace($result[1], '', $fh));
                    $this->getObject()->writeFile($Paths, $fh, True);
                    $res[$k] = Yii::$app->params['upUploadsFiles'].$Paths;
                }
            }
        } catch (\Exception $e) {
            throw new ForbiddenHttpException('Caught exception: ' . $e->getMessage());
        }
        return $res;
    }
    /**
     * 直接上传文件
     * @return The file path
     */
    public function DirectUpload()
    {
        try {
            $Path = $this->Upload();
            if (!empty($Path)) {
                $Paths = '/' . trim(trim($this->Paths, '/'), '\\') . '/' . $Path['baseName'];
                $fh = fopen($Path['path'], 'rb');
                $row = $this->getObject()->writeFile($Paths, $fh, True); // 上传图片，自动创建目录
                fclose($fh);
                if (!empty($row) && file_exists($Path['path'])) {
                    unlink($Path['path']);
                }
                return $Paths;
            }
            return "";
        } catch (\Exception $e) {
            throw new ForbiddenHttpException('Caught exception: ' . $e->getMessage());
        }
    }

    /*
     * 上传文件到服务器方法
     * $imageUploadFile->baseName 上传文件的原始name
     * @return The file path
     */
    public function Upload()
    {
        $imageUploadFile = UploadedFile::getInstanceByName($this->file_name);
        if (!empty($imageUploadFile)) {
            $path = Yii::getAlias(dirname(dirname(__DIR__)) . '/web/uploads/');
            if (!file_exists($path)) {
                if (!mkdir($path, $this->chmod, true)) {
                    throw new ServerErrorHttpException('Unable to create the specified directory。');
                }
            }
            $fileName = $this->file_name . '_' . md5(uniqid($this->file_name . '_', true));
            $baseName = $fileName . '_' . md5($imageUploadFile->baseName) . '.' . $imageUploadFile->extension;
            $fileName = $path . $fileName . '.' . $imageUploadFile->extension;
            if (!in_array(strtolower($imageUploadFile->extension), $this->type)) {
                throw new ServerErrorHttpException('File is not allow type。');
            }
            if ($imageUploadFile->size >= $this->size) {
                throw new ServerErrorHttpException('Upload a file over limit size ' . ($imageUploadFile->size - $this->size) . ' B。');
            }
            if (!$imageUploadFile->saveAs($fileName)) {
                throw new ServerErrorHttpException('Unable to save the file。');
            }
            return ['path' => $fileName, 'baseName' => $baseName];
        } else {
            return [];
        }
    }

    /**
     * 多个文件上传
     * @return array $Paths 文件路径
     * update by wuxuye 2016-04-15
     */
    public function moreFileUpload()
    {
        try {
            $Paths = array();
            $Path = $this->UploadMore();
            foreach ($Path as $key => $val) {
                $temp_path = $this->Paths . $val['baseName'];
                $Paths[] = $temp_path;
                $fh = fopen($val['path'], 'rb');
                $row = $this->getObject()->writeFile($temp_path, $fh, True); // 上传图片，自动创建目录
                fclose($fh);
                if (!empty($row) && file_exists($val['path'])) {
                    unlink($val['path']);
                }
            }
            return $Paths;
        } catch (\Exception $e) {
            throw new ForbiddenHttpException('Caught exception: ' . $e->getMessage());
        }
    }

    /**
     * 多个文件上传至服务器方法
     * @return array $result 文件路径结果集
     * update by wuxuye 2016-04-15
     */
    public function UploadMore()
    {
        $result = array();
        $imageUploadFile = UploadedFile::getInstancesByName($this->file_name);
        if (!empty($imageUploadFile)) {

            foreach ($imageUploadFile as $key => $val) {
                $path = Yii::getAlias(dirname(dirname(__DIR__)) . '/web/uploads/');
                if (!file_exists($path)) {
                    if (!mkdir($path, $this->chmod, true)) {
                        throw new ServerErrorHttpException('Unable to create the specified directory。'.$path);
                    }
                }
                $fileName = $this->file_name . '_' . md5(uniqid($this->file_name . '_', true));
                $baseName = $fileName . '_' . md5($val->baseName) . '.' . $val->extension;
                $fileName = $path . $fileName . '.' . $val->extension;
                if (!in_array(strtolower($val->extension), $this->type)) {
                    throw new ServerErrorHttpException('File is not allow type。');
                }
                if ($val->size >= $this->size) {
                    throw new ServerErrorHttpException('Upload a file over limit size ' . ($val->size - $this->size) . ' B。');
                }
                if (!$val->saveAs($fileName)) {
                    throw new ServerErrorHttpException('Unable to save the file。');
                }
                $result[] = ['path' => $fileName, 'baseName' => $baseName];
            }
        }

        return $result;

    }

    /**
     * 本地文件上传
     */
    public function LocalUpload(){

        $result = ['state'=>0,'message'=>'未知错误','url'=>''];

        try {
            $file_url = trim($this->file_url);
            if (!empty($file_url)) {
                $file_info = "work_".basename($file_url);
                $file_path = $file_url;
                $Paths = '/' . trim(trim($this->Paths, '/'), '\\') . '/' . $file_info;
                $fh = fopen($file_path, 'rb');
                $row = $this->getObject()->writeFile($Paths, $fh, True); // 上传图片，自动创建目录
                fclose($fh);
                if (!empty($row)) {
                    if(file_exists($file_path)){
                        unlink($file_path);
                    }

                    $result['state'] = 1;
                    $result['message'] = '操作成功';
                    $result['url'] = $Paths;

                }else{
                    $result['message'] = 'upyun 上传失败';
                }
            }else{
                $result['message'] = 'file_url 为空';
            }
        } catch (\Exception $e) {
            $result['message'] = "操作失败：".$e->getMessage();
        }

        return $result;
    }

}

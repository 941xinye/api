<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%app_version}}".
 *
 * @property string $id
 * @property integer $platform
 * @property string $desc
 * @property string $created
 * @property string $package
 * @property integer $status
 * @property string $version
 * @property integer $is_force_update
 */
class AppVersion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_version}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['platform', 'created', 'status', 'is_force_update'], 'integer'],
            [['version'], 'required'],
            [['desc', 'package'], 'string', 'max' => 255],
            [['version'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'platform' => 'Platform',
            'desc' => 'Desc',
            'created' => 'Created',
            'package' => 'Package',
            'status' => 'Status',
            'version' => 'Version',
            'is_force_update' => 'Is Force Update',
        ];
    }
}

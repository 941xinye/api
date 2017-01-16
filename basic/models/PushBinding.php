<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pre_push_binding".
 *
 * @property integer $id
 * @property integer $mem_id
 * @property string $pushid
 * @property string $inputtime
 * @property string $updatetime
 */
class PushBinding extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pre_push_binding';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mem_id'], 'integer'],
            [['inputtime', 'updatetime'], 'safe'],
            [['pushid'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mem_id' => 'Mem ID',
            'pushid' => 'Pushid',
            'inputtime' => 'Inputtime',
            'updatetime' => 'Updatetime',
        ];
    }
}

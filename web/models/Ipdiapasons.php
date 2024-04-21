<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ipdiapasons".
 *
 * @property int $id
 * @property string $ipaddr
 * @property int $netmask
 * @property string $description
 */
class Ipdiapasons extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ipdiapasons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ipaddr', 'netmask', 'description'], 'required'],
            [['netmask'], 'integer'],
            [['ipaddr', 'description'], 'string', 'max' => 45],
            ['ipaddr', 'ip'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ipaddr' => 'ip адрес',
            'netmask' => 'Маска подсети',
            'description' => 'Описание',
        ];
    }

    /**
     * {@inheritdoc}
     * @return IpdiapasonsQuery the active query used by this AR class.
     */
    // public static function find()
    // {
    //     return new IpdiapasonsQuery(get_called_class());
    // }
}

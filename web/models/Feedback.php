<?php

namespace app\models;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $phone
 * @property string|null $email
 * @property int|null $subject
 * @property string|null $body
 * @property string|null $ipaddr
 * @property string|null $voicefile
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Feedback extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject', 'created_at', 'updated_at'], 'integer'],
            [['body'], 'string'],
            [['name', 'phone', 'ipaddr'], 'string', 'max' => 45],
            [['email', 'voicefile'], 'string', 'max' => 140],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'phone' => 'Номер телефона',
            'subject' => 'Тип сообщения',
            'body' => 'Сообщение',
            'email' => 'E-mail',
            'ipaddr' => 'ip адрес',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
            'voicefile' => 'Звук',
        ];
    }




    /**
     * {@inheritdoc}
     * @return FeedbackQuery the active query used by this AR class.
     */
    // public static function find()
    // {
    //     return new FeedbackQuery(get_called_class());
    // }
}

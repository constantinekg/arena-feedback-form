<?php

namespace app\models;

use Yii;
use yii\base\Model;


/**
 * FeedbackForm is the model behind the contact form.
 */
class FeedbackForm extends Model
{
    public $name;
    public $phone;
    public $email;
    public $subject;
    public $body;
    public $ipaddr;
    public $voicefile;
    public $reCaptcha;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'subject'], 'required'],
            [['name', 'phone', 'subject', 'body'], 'trim'],
            [['phone'], 'string'],
            ['phone', 'match', 'pattern' => '/^(\d{3})[ ](\d{2})[-](\d{2})[-](\d{2})/', 'message' => 'Телефона, должно быть в формате XXX XX-XX-XX'],
            // [['phone'], 'filter', 'filter' => function ($value) {
            //     return preg_replace('/[^0-9]/', '', $value);
            // }],
            [['email'], 'email'],
            [['name',], 'string', 'min' => 2, 'max' => 45],
            [['subject'], 'integer'],
            // ['subject', 'in', 'range' => [0, 1, 2]], // 0 - предложение; 1 - жалоба; 2 - разное
            ['subject', 'in', 'range' => array_keys(Yii::$app->params['feedbackthemes'])], // 0 - предложение; 1 - жалоба; 2 - разное
            [['email'], 'string', 'min' => 6, 'max' => 140],
            [['body'], 'string', 'min' => 3, 'max' => 1024],
            [['ipaddr'], 'default', 'value' => Yii::$app->request->userIP],
            ['ipaddr', 'ip'],
            [['voicefile'], 'string', 'max' => 60],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator2::className(),
            'uncheckedMessage' => 'Пожалуйста, подтвердите что вы не бот.'],
        ];
    }


    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'phone' => 'Номер телефона',
            'subject' => 'Тип сообщения',
            'body' => 'Сообщение',
            'email' => 'Ваш E-mail',
            'reCaptcha' => 'Проверка на робота',
        ];
    }


    /**
     * @return bool return after validation
     */
    public function saveIntoDB() {
        if ($this->validate()) {
            $newfeedback = new Feedback();
            $newfeedback->name = $this->name;
            $newfeedback->phone = preg_replace('/[^0-9]/', '', $this->phone);
            $newfeedback->email = $this->email;
            $newfeedback->subject = $this->subject;
            $newfeedback->body = $this->body;
            $newfeedback->ipaddr = $this->ipaddr;
            $newfeedback->voicefile = $this->voicefile;
            if ($newfeedback->save()) {
                return true;
            }
            return false;
        }
        return false;
    }

}
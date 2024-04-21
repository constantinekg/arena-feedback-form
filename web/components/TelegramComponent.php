<?php
     
     namespace app\components;
     use yii\helpers\Html;
     use app\models\Ipdiapasons;
     use yii\base\Component;
      
class TelegramComponent extends Component {


    public function Sendvoice($filePath, $name, $phone, $email, $subject, $messagebody ) {
        $ipdiapasons = Ipdiapasons::find()->select(["CONCAT(ipaddr, '/', netmask) AS net", 'description'])->
        orderBy(['id' => SORT_DESC])->asArray()->all();
        $ipaddr = \Yii::$app->request->userIP;
        foreach ($ipdiapasons as $ipdiapason) {
            if (\Yii::$app->utils->cidr_match($ipaddr, $ipdiapason['net'])) {
                $ipaddr = $ipaddr . ' (' . $ipdiapason['description'] . ')';
            }
        }
        $caption = Html::encode($name . "\r\n" . "+996" . preg_replace('/[^0-9]/', '', $phone) . "\r\n" . $email . 
        "\r\n" . \Yii::$app->params['feedbackthemes'][$subject] . "\r\n" . $messagebody . "\r\n" . $ipaddr);
        define('BOTAPI', 'https://api.telegram.org/bot' . \Yii::$app->params['telegramBotToken'] . '/');
        $cfile = new \CURLFile(realpath($filePath));
        $data = [
            'chat_id' => \Yii::$app->params['telegramNotificationId'],
            'audio' => $cfile,
            'caption' => $caption
        ];
        $ch = curl_init(BOTAPI . 'sendAudio');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        if (curl_exec($ch)) {
            curl_close($ch);
            return true;
        }
        return false;
    }

    public function SendMessage($name, $phone, $email, $subject, $messagebody ) {
        $ipdiapasons = Ipdiapasons::find()->select(["CONCAT(ipaddr, '/', netmask) AS net", 'description'])->
        orderBy(['id' => SORT_DESC])->asArray()->all();
        $ipaddr = \Yii::$app->request->userIP;
        foreach ($ipdiapasons as $ipdiapason) {
            if (\Yii::$app->utils->cidr_match($ipaddr, $ipdiapason['net'])) {
                $ipaddr = $ipaddr . ' (' . $ipdiapason['description'] . ')';
            }
        }
        $text = Html::encode($name . "\r\n" . "+996" . preg_replace('/[^0-9]/', '', $phone) . 
        "\r\n" . $email . "\r\n" . \Yii::$app->params['feedbackthemes'][$subject] . "\r\n" . $messagebody . "\r\n" . $ipaddr);
        define('BOTAPI', 'https://api.telegram.org/bot' . \Yii::$app->params['telegramBotToken'] . '/');
        $data = [
            'chat_id' => \Yii::$app->params['telegramNotificationId'],
            'text' => $text
        ];
        $ch = curl_init(BOTAPI . 'sendMessage');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        if (curl_exec($ch)) {
            curl_close($ch);
            return true;
        }
        return false;
    }


}
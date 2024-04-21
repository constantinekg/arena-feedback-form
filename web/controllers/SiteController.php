<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\FeedbackForm;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new FeedbackForm();

        if ($model->load(Yii::$app->request->post())) {
            return $model->voicefile;
            if ($model->saveIntoDB()) {
                return $this->render('thankyou');
            }
            else {
                $errors = $model->errors;
                Yii::warning($errors);
            }
            
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Receive form data
     * @return json
     */
    public function actionReceivefeedback()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new FeedbackForm();
        if (Yii::$app->request->post()) {
            $response = Yii::$app->request->post();
            if ($model->load($response)) {
                $model->reCaptcha = $response['reCaptcha'];
                if (array_key_exists('voice', $_FILES)) {
                    $voicefile = $_FILES['voice']['tmp_name'];
                    $voicesize = $_FILES['voice']['size'];
                    if (mime_content_type($voicefile) != 'audio/wav' && $voicesize >= Yii::$app->params['maxvoicefilesize']) {
                        return false;
                    }
                    $randstring = Yii::$app->security->generateRandomString() . time();
                    $model->voicefile = $randstring;
                    $saveOggUrl = Yii::getAlias('@app/web/uploadedogg') . DIRECTORY_SEPARATOR . $randstring . '.wav';
                }
                if ($model->validate()) {
                    if (array_key_exists('voice', $_FILES)) {
                        if (move_uploaded_file($_FILES['voice']['tmp_name'], $saveOggUrl)) {
                            if ($model->saveIntoDB()) {
                                $sendvoice = Yii::$app->telegram->Sendvoice($saveOggUrl, $model->name, $model->phone, $model->email, $model->subject, $model->body);
                                if ($sendvoice == true) {
                                    return true;
                                }
                            }
                            else {
                                return $model->errors;
                            }
                            
                        }
                    }
                    if ($model->saveIntoDB()) {
                        $sendtextmessage = Yii::$app->telegram->SendMessage($model->name, $model->phone, $model->email, $model->subject, $model->body);
                        if ($sendtextmessage == true) {
                            return true;
                        }
                    }
                    else {
                        return $model->errors;
                    }
                }
                else {
                    return $model->errors;
                }
            }
            else{
                return false;
            }
        }
            return false;
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    // public function actionContact()
    // {
    //     $model = new ContactForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
    //         Yii::$app->session->setFlash('contactFormSubmitted');

    //         return $this->refresh();
    //     }
    //     return $this->render('contact', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    /**
     * Displays thankyou page.
     *
     * @return string
     */
    public function actionThankyou()
    {
        return $this->render('thankyou');
    }
}

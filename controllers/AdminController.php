<?php
namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['*'], // Hammasi uchun amal qiladi
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Faqat login qilgan foydalanuvchilar uchun
                    ],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();
        $this->layout = 'admin'; // Admin layoutdan foydalanadi
    }
}

?>
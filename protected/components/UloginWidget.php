<?php

class UloginWidget extends CWidget
{
    //параметры по-умолчанию
    private $params = array(
        'display'       =>  'panel',
        'fields'        =>  'first_name,last_name,email',
        'optional'      =>  '',
        'providers'     =>  'vkontakte,yandex,mailru,facebook,steam',
        'hidden'        =>  'twitter,google,odnoklassniki,livejournal,openid,lastfm,linkedin,liveid,soundcloud',
        'redirect'      =>  '',
        'logout_url'    =>  '/ulogin/logout'
    );

    public function run()
    {
        //подключаем JS скрипт
        Yii::app()->clientScript->registerScriptFile('http://ulogin.ru/js/ulogin.js', CClientScript::POS_HEAD);
        $this->render('uloginWidget', $this->params);
    }

    public function setParams($params)
    {
        $this->params = array_merge($this->params, $params);
    }
}

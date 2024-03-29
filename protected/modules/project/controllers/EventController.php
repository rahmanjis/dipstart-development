<?php

class EventController extends Controller {

    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('index'),
                'users'=>array('admin','manager'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }
    public function actionIndex() {
        if (Yii::app()->request->isAjaxRequest){

            header('Content-Type: application/json');
            echo CJSON::encode(array('success'=>true,'msg'=>ProjectMessages::model()->findByPk(Events::model()->findByPk(Yii::app()->request->getParam('id'))->event_id)->message));
            Yii::app()->end();
        }
        $events = Events::model()->findAll(array(
            'condition' => '',
            'order' => 'timestamp DESC'
        ));
        $this->render('index', array(
            'events' => $events
        ));
        
    }
    
}

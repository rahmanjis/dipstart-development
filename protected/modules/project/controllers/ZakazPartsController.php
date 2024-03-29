<?php


class ZakazPartsController extends Controller
{
    
    protected $_request;
    protected $_response;
    protected $_file_data;
    protected $result;
    
    /*Вызов методов для работы с json*/
    protected function _prepairJson() {
        $this->_request = Yii::app()->jsonRequest;
        $this->_response = new JsonHttpResponse();
    }
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Performs the AJAX validation.
	 * @param ZakazParts $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='zakaz-parts-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function folder() { 	// --- campaign
		$c_id = Campaign::getId();
		if ($c_id) {
			return '/uploads/c'.$c_id.'/parts/';
		}else{
			return '/uploads/additions/';
		}
	} 							// ---
		
        /* Получение списка частей для заказа по ИД*/
        public function actionApiGetAll() {
			// --- campaign
			$folder = $this->folder();
            $this->_prepairJson();
            $zakazId = $this->_request->getParam('orderId');
            if (User::model()->isAdmin() || User::model()->isManager()) {
                $models = ZakazParts::model()->findAll('proj_id = :PROJ_ID',
                    array(":PROJ_ID"=>$zakazId)
                );
                $parts = array();
                foreach ($models as $model) {
                    $part['id'] = $model->id;
                    $part['proj_id'] = $model->proj_id;
                    $part['title'] = $model->title;
                    $part['date'] = Yii::app()->dateFormatter->formatDateTime($model->date, 'medium' ,'');
                    $part['author_id'] = $model->author_id;
                    $part['author'] = $model->getRelated('author')->username;
                    $part['show'] = $model->show;
                    $part['comment'] = $model->comment;
                    $part['file'] = ZakazPartsFiles::model()->findAll('part_id = :PART_ID',
                        array("PART_ID"=>$model->id)
                    );
                    $for_moderation = array_diff(scandir(YiiBase::getPathOfAlias('webroot').$folder.'temp'), array('..', '.'));
                    foreach ($for_moderation as $k=>$v)
                        if(preg_match('/_'.$model->id.'./i',$v)){
                            $for_moderation[$k]=array(
                                'comment'=>0,
                                'file_name'=>0,
                                'id'=>0,
                                'orig_name'=>preg_replace('/_'.$model->id.'/i','',$v),
                                'part_id'=>$model->id,
                                'for_approved'=>'Must approved',
                            );
                        } else unset($for_moderation[$k]);
                    $part['file'] = array_merge($part['file'],$for_moderation);
                    $parts[] = $part;
                }
                $this->_response->setData(array(
                    'parts'=>$parts
                ));
                $this->_response->send();
            } elseif (User::model()->isCustomer() || User::model()->isAuthor()) {
                $model = ZakazParts::model()->findAll('proj_id = :PROJ_ID AND `show` IN (1'.(User::model()->isAuthor()?',0)':')'),
                    array(':PROJ_ID'=>$zakazId)
                );
                $parts = array();
                foreach ($model as $k => $part) {
                    foreach ($part as $kk => $vv) {
                        $parts[$k][$kk]=$vv;
                    }
                    $parts[$k]['file'] = $part->getRelated('files');
                    $parts[$k]['author'] = $part->getRelated('author')->username;
                }
                $this->_response->setData(array('parts'=>$parts));
                $this->_response->send();
            }
        }
        public function actionApiApprove() {
			$folder = $this->folder();
            if (!isset($this->_file_data['req'])) {
                $this->_prepairJson();
                $this->_file_data = $this->_request->getParam('data');
            }
			$list = explode('.', $this->_file_data['orig_name']);
			$extention = array_pop($list);
            $newName = $this->getGuid();
			$filePath = $_SERVER['DOCUMENT_ROOT'].$folder.'temp/'.implode('.',$list).'_'.$this->_file_data['part_id'].'.'.$extention;
            $newDir = $_SERVER['DOCUMENT_ROOT'].$folder.$this->_file_data['part_id'];
            $fileNewPath = $newDir.'/'.$newName.".".$extention;
            if (!file_exists($newDir)) {
				mkdir($newDir,0777);
			}
            if ($this->_file_data['id']==0){
                if (rename($filePath, $fileNewPath)) {
                    $fileModel = new ZakazPartsFiles();
                    $fileModel->part_id = $this->_file_data['part_id'];
                    $fileModel->orig_name = $this->_file_data['orig_name'];
                    $fileModel->file_name = $newName . "." . $extention;
                    $fileModel->comment = '';
                    $fileModel->save();
                    $this->result=array('file_name'=>$folder.$this->_file_data['part_id'].'/'.$newName.".".$extention);
                } else $this->result=array('success'=>false);
            } elseif (rename($_SERVER['DOCUMENT_ROOT'].$this->_file_data['file_name'],$filePath)) {
                $this->result['delete']=ZakazPartsFiles::model()->findByPk($this->_file_data['id'])->delete();
            } else $this->result=array('success'=>false);
            if (!isset($this->_file_data['req'])) {
                $this->_response->setData($this->result);
                $this->_response->send();
            }
        }
        public function actionApiEditPart() {
			$folder = $this->folder();
            $this->_prepairJson();
            $partId = $this->_request->getParam('id');
            $model = ZakazParts::model()->findByPk($partId);
            foreach ($this->_request->_params as $par=>$val)
                $model->$par =$val;
            if ($this->_request->isParam('files')) {
                $files = $this->_request->getParam('files');
                $path = $folder.$partId.'/';
                $this->checkDir($path);
                foreach($files as $file) {
                    $list = explode('.', $file);
                    $newName = $this->getGuid();
                    $filePath = $_SERVER['DOCUMENT_ROOT'].$folder.'temp/'.$file;
                    $fileNewPath = $_SERVER['DOCUMENT_ROOT'].$folder.$partId.'/'.$newName.".".$list['1'];
                    $probe = rename($filePath, $fileNewPath);
                    $fileModel = new ZakazPartsFiles();
                    $fileModel->part_id = $model->id;
                    $fileModel->orig_name = $file;
                    $fileModel->file_name = $newName.".".$list['1'];
                    $fileModel->comment = '';
                    $fileModel->save();
                }
            }

            $this->_response->setData(array(
                'result' => $model->save()
            ));
            $this->_response->send();
        }
        
        private function checkDir($path) {
            if (!file_exists($path)){
                mkdir($path, 0755, true);
            }
        }
        
        protected function getGuid(){
            if (function_exists('com_create_guid')){
                return com_create_guid();
            }else{
                mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
                $charid = strtoupper(md5(uniqid(rand(), true)));
                $hyphen = chr(45);// "-"
                $uuid = substr($charid, 0, 8).$hyphen
                    .substr($charid, 8, 4).$hyphen
                    .substr($charid,12, 4).$hyphen
                    .substr($charid,16, 4).$hyphen
                    .substr($charid,20,12);// "}"
                return $uuid;
            }
        }
        
        public function actionApiEditFilesComment() {
            $this->_prepairJson();
            $fileid = $this->_request->getParam('id');
            $file = new ZakazPartsFiles;
            $id = $file->changeComment($fileid, $this->_request->getParam('comment'));
            $this->_response->setData(array(
                'id' => $id
            ));
            $this->_response->send();
        }
        
        public function actionApiChangeIsShowed() {
            $this->_prepairJson();
            $partId = $this->_request->getParam('id');
            $part = ZakazParts::model()->findByPk($partId);
            if ($part->show == 1) {
                $part->show = 0;
            } else {
                $part->show = 1;
            }
            $part->save();
            $this->_response->setData(array(
                'result' => true
            ));
            $this->_response->send();
        }
        
        public function actionApiDeleteFile() {
			$folder = $this->folder();
            $this->_prepairJson();
            $fileid = $this->_request->getParam('id');
            $file = new ZakazPartsFiles;
            $this->result = $file->deleteFile($fileid);
            unlink($_SERVER['DOCUMENT_ROOT'].$folder.$this->result['part'].'/'.$this->result['file']);
            $this->_response->setData(array(
                'id' => $this->result['part']
            ));
            $this->_response->send();
        }
        
        /*Создание новой части на основе имени и ИД-заказа*/
        public function actionApiCreate() {
            $this->_prepairJson();
            $zakazId = $this->_request->getParam('orderId');
            $zakaz = Zakaz::model()->findByPk($zakazId);
            $model = new ZakazParts;
            $model->proj_id = $zakaz->id;
            $model->title = $this->_request->getParam('name');
            $model->author_id = $zakaz->executor;
            if ( $model->save() ) {
                $this->_response-> setData(array(
                    'result'=>$model->id
                ));
                $this->_response->send();
            } else {
                $this->_response->setData(array(
                    'result'=>false
                ));
                $this->_response->send();
            }
        }
        
        /*Удаление части по ИД*/
        public function actionApiDelete() {
            $this->_prepairJson();
            $id = $this->_request->getParam('id');
            $part =  ZakazParts::model()->findByPk($id);
            
            if ($part->delete()) {
                $this->_response->setData(array(
                    'result'=>true
                ));
                $this->_response->send();
            } else {
                $this->_response->setData(array(
                    'result'=>false
                ));
                $this->_response->send();
            }
        }
        
        /*Получение данных части по ИД для дальнейшего редактирования*/
        public function actionApiGetPart() {
            $this->_prepairJson();
            $id = $this->_request->getParam('id');
            $model = ZakazParts::model()->findByPk($id);
            $files = ZakazPartsFiles::model()->findAll('part_id = :PART_ID',
                        array("PART_ID"=>$model->id)
                    );
            $this->_response->setData(array(
                    'part' => $model,
                    'files' => $files
                ));
            $this->_response->send();
        }
        
        public function actionUpload() {
			$folder = $this->folder();
			$this->_prepairJson();
			$folder = $_SERVER['DOCUMENT_ROOT'].$folder;
            Yii::import("ext.EAjaxUpload.qqFileUploader");
			//chmod($folder, 0777);     // !-----------------------------DeBuG oNlY !!-----------------------------------------
            $folder=$folder.'temp/';
			//chmod($folder, 0777);     // !-----------------------------DeBuG oNlY !!-----------------------------------------
            $config['allowedExtensions'] = array('jpg', 'jpeg', 'png', 'gif', 'txt', 'doc', 'docx');
            $config['disAllowedExtensions'] = array("exe, php");
            $sizeLimit = 10 * 1024 * 1024;
            $pi = pathinfo($_GET['qqfile']);
            $_GET['qqfile']=$pi['filename'].'_'.$_GET['id'].'.'.$pi['extension'];
            $uploader = new qqFileUploader($config, $sizeLimit);
            $this->result = $uploader->handleUpload($folder,true);
            if ($this->result['success']) {
				$part = ZakazParts::model()->findByPk($_GET['id']);
                if (!User::model()->isManager()) EventHelper::partDone($_GET['proj_id'], $part->title);
            }
            chmod($folder.$_GET['qqfile'],0666);
			
            if (User::model()->isManager()||User::model()->isAdmin()) {
				
                $this->_file_data['part_id']=$_GET['id'];
                $this->_file_data['orig_name']=$pi['filename'].'.'.$pi['extension'];
                $this->_file_data['id']=0;
                $this->_file_data['req']=1;
                $this->actionApiApprove();
            }
			
            //$this->result['html']='=)';//'<li>!!!<a href="' . $this->result['file_name'] . '" id="parts_file">' . $_GET['qqfile'] . '</a></li>';
			
			$this->result = array('test' => $this->result['error']);
            $this->_response->setData($this->result);
            $this->_response->send();
        }
}

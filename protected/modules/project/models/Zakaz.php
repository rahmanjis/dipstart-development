<?php

/**
 * This is the model class for table "Projects".
 *
 * The followings are the available columns in table 'Projects':
 * @property string $id
 * @property string $user_id
 * @property integer $category_id
 * @property integer $job_id
 * @property string $title
 * @property string $text
 * @property string $date
 * @property string $max_exec_date
 * @property string $date_finish
 * @property integer $pages
 * @property string $add_demands
 * @property integer $status
 * @property string $executor
 * @property User $user
 * @property User $author
 * @property ProjectStatus $projectStatus
 * @property string $payment_image
 */
class Zakaz extends CActiveRecord
{
    private $_job_name;
    private $_cat_name;
    private $date_finishstart;
    private $date_finishend;

    public $dateTimeIncomeFormat = 'yyyy-MM-dd HH:mm:ss';
    public $dateTimeOutcomeFormat = 'dd.MM.yyyy HH:mm';
    public $dateIncomeFormat = 'yyyy-MM-dd HH:mm:ss';
    public $dateOutcomeFormat = 'dd.MM.yyyy';

    public function getDbdate_finishstart(){
        if ($this->date_finishstart!='') {
            return Yii::app()->dateFormatter->format($this->dateOutcomeFormat, CDateTimeParser::parse($this->date_finishstart, $this->dateTimeIncomeFormat));
        }
    }
    public function setDbdate_finishstart($datetime)
    {
        if ($datetime!=''){
            $this->date_finishstart = Yii::app()->dateFormatter->format($this->dateTimeIncomeFormat, CDateTimeParser::parse($datetime, $this->dateOutcomeFormat));
        }
    }
    public function getDbdate_finishend(){
        if ($this->date_finishend!='') {
            return Yii::app()->dateFormatter->format($this->dateOutcomeFormat, CDateTimeParser::parse($this->date_finishend, $this->dateTimeIncomeFormat));
        }
    }
    public function setDbdate_finishend($datetime)
    {
        if ($datetime!=''){
            $this->date_finishend = Yii::app()->dateFormatter->format($this->dateTimeIncomeFormat, CDateTimeParser::parse($datetime, $this->dateOutcomeFormat));
        }
    }
    public function getDbdate()
    {
        if ($this->date!='') {
            if ($this->date=='0000-00-00 00:00:00') return '';
            if (strlen($this->date) == 19) return Yii::app()->dateFormatter->format($this->dateTimeOutcomeFormat, CDateTimeParser::parse($this->date, $this->dateTimeIncomeFormat));
            elseif (strlen($this->date) == 10) return Yii::app()->dateFormatter->format($this->dateOutcomeFormat, CDateTimeParser::parse($this->date, $this->dateTimeIncomeFormat));
        }
    }
    public function getDbmax_exec_date()
    {
        if ($this->max_exec_date!='') {
            if ($this->max_exec_date=='0000-00-00 00:00:00') return '';
            if (strlen($this->max_exec_date) == 19) return Yii::app()->dateFormatter->format($this->dateTimeOutcomeFormat, CDateTimeParser::parse($this->max_exec_date, $this->dateTimeIncomeFormat));
            elseif (strlen($this->max_exec_date) == 10) return Yii::app()->dateFormatter->format($this->dateOutcomeFormat, CDateTimeParser::parse($this->max_exec_date, $this->dateTimeIncomeFormat));
        }
    }
    public function getDbmanager_informed()
    {
        if ($this->manager_informed!='') {
            if ($this->manager_informed=='0000-00-00 00:00:00') return '';
            if (strlen($this->manager_informed) == 19) return Yii::app()->dateFormatter->format($this->dateTimeOutcomeFormat, CDateTimeParser::parse($this->manager_informed, $this->dateTimeIncomeFormat));
            elseif (strlen($this->manager_informed) == 10) return Yii::app()->dateFormatter->format($this->dateOutcomeFormat, CDateTimeParser::parse($this->manager_informed, $this->dateTimeIncomeFormat));
        }
    }
    public function getDbdate_finish()
    {
        if ($this->date_finish!='') {
            if ($this->date_finish=='0000-00-00 00:00:00') return '';
            if (strlen($this->date_finish) == 19) return Yii::app()->dateFormatter->format($this->dateTimeOutcomeFormat, CDateTimeParser::parse($this->date_finish, $this->dateTimeIncomeFormat));
            elseif (strlen($this->date_finish) == 10) return Yii::app()->dateFormatter->format($this->dateOutcomeFormat, CDateTimeParser::parse($this->date_finish, $this->dateTimeIncomeFormat));
        }
    }
    public function getDbauthor_informed()
    {
        if ($this->author_informed!='') {
            if ($this->author_informed=='0000-00-00 00:00:00') return '';
            if (strlen($this->author_informed) == 19) return Yii::app()->dateFormatter->format($this->dateTimeOutcomeFormat, CDateTimeParser::parse($this->author_informed, $this->dateTimeIncomeFormat));
            elseif (strlen($this->author_informed) == 10) return Yii::app()->dateFormatter->format($this->dateOutcomeFormat, CDateTimeParser::parse($this->author_informed, $this->dateTimeIncomeFormat));
        }
    }
    public function setDbmax_exec_date($datetime)
    {
        if ($datetime!=''){
            if (strlen($datetime) == 16) $this->max_exec_date = Yii::app()->dateFormatter->format($this->dateTimeIncomeFormat, CDateTimeParser::parse($datetime, $this->dateTimeOutcomeFormat));
            elseif (strlen($datetime) == 10) $this->max_exec_date = Yii::app()->dateFormatter->format($this->dateTimeIncomeFormat, CDateTimeParser::parse($datetime, $this->dateOutcomeFormat));
        }
    }
    public function setDbdate_finish($datetime)
    {
        if ($datetime!=''){
            if (strlen($datetime) == 16) $this->date_finish = Yii::app()->dateFormatter->format($this->dateTimeIncomeFormat, CDateTimeParser::parse($datetime, $this->dateTimeOutcomeFormat));
            elseif (strlen($datetime) == 10) $this->date_finish = Yii::app()->dateFormatter->format($this->dateTimeIncomeFormat, CDateTimeParser::parse($datetime, $this->dateOutcomeFormat));
        }
    }
    public function setDbdate($datetime)
    {
        if ($datetime!=''){
            if (strlen($datetime) == 16) $this->date = Yii::app()->dateFormatter->format($this->dateTimeIncomeFormat, CDateTimeParser::parse($datetime, $this->dateTimeOutcomeFormat));
            elseif (strlen($datetime) == 10) $this->date = Yii::app()->dateFormatter->format($this->dateTimeIncomeFormat, CDateTimeParser::parse($datetime, $this->dateOutcomeFormat));
        }
    }
    public function setDbmanager_informed($datetime)
    {
        if ($datetime!=''){
            if (strlen($datetime) == 16) $this->manager_informed = Yii::app()->dateFormatter->format($this->dateTimeIncomeFormat, CDateTimeParser::parse($datetime, $this->dateTimeOutcomeFormat));
            elseif (strlen($datetime) == 10) $this->manager_informed = Yii::app()->dateFormatter->format($this->dateTimeIncomeFormat, CDateTimeParser::parse($datetime, $this->dateOutcomeFormat));
        }
    }
    public function setDbauthor_informed($datetime)
    {
        if ($datetime!=''){
            if (strlen($datetime) == 16) $this->author_informed = Yii::app()->dateFormatter->format($this->dateTimeIncomeFormat, CDateTimeParser::parse($datetime, $this->dateTimeOutcomeFormat));
            elseif (strlen($datetime) == 10) $this->author_informed = Yii::app()->dateFormatter->format($this->dateTimeIncomeFormat, CDateTimeParser::parse($datetime, $this->dateOutcomeFormat));
        }
    }
    public function getJobName()
    {
        if ($this->_job_name === null && $this->job !== null)
        {
            $this->_job_name = $this->job->job_name;
        }
        return $this->_job_name;
    }
    public function setJobName($value)
    {
        $this->_job_name = $value;
    }
    public function getCatName()
    {
        if ($this->_cat_name === null && $this->job !== null)
        {
            $this->_cat_name = $this->category->cat_name;
        }
        return $this->_cat_name;
    }
    public function setCatName($value)
    {
        $this->_cat_name = $value;
    }

    public function init()
    {
        parent::init();
    }
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Projects';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id, title', 'required', 'on'=>'create'),
			array('category_id, job_id, pages, status', 'numerical', 'integerOnly'=>true),
			array('user_id', 'length', 'max'=>11),
			array('title', 'length', 'max'=>255),
			array('executor', 'length', 'max'=>10),
            array('text, date_finishend, date_finishstart, max_exec_date, date_finish, author_informed, manager_informed, date, add_demands, notes, author_notes, time_for_call, edu_dep', 'safe'),
            array('dbdate_finishend, dbdate_finishstart, dbmax_exec_date, dbdate_finish, dbauthor_informed, dbmanager_informed, dbdate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, jobName, catName, title, dateCreation, dateFinish, managerInformed', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::HAS_ONE, 'User', array('id'=>'user_id')),
			'author' => [self::BELONGS_TO, 'User', 'executor'],
			'category'=>array(self::HAS_ONE, 'Categories', array('id'=>'category_id')),
			'job'=>array(self::HAS_ONE, 'Jobs', array('id'=>'job_id')),
            'projectStatus'=>array(self::BELONGS_TO, 'ProjectStatus', 'status')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'jobName'=>'Вид работ',
            'catName'=>'Категория',
			'user_id' => ProjectModule::t('User'),
			'category_id' => ProjectModule::t('Category'),
			'job_id' => ProjectModule::t('Job'),
			'title' => ProjectModule::t('Title'),
			'text' => ProjectModule::t('Text'),
			'date' => ProjectModule::t('Date'),
			'max_exec_date' => ProjectModule::t('Max Date'),
			'date_finish' => ProjectModule::t('Date Finish'),
			'pages' => ProjectModule::t('Pages'),
			'add_demands' => ProjectModule::t('Add Demands'),
			'status' => ProjectModule::t('Status'),
			'executor' => ProjectModule::t('Executor'),
			'manager_informed' => ProjectModule::t('Manager Informed'),
			'author_informed' => ProjectModule::t('Author Informed'),
			'notes' => ProjectModule::t('Notes'),
			'author_notes' => ProjectModule::t('author_notes'),
			'time_for_call' => ProjectModule::t('time_for_call'),
			'edu_dep' => ProjectModule::t('edu_dep'),

		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
    public function search_upd()
    {
        $criteria = new CDbCriteria;
        $criteria->with = array('job', 'category');
        $criteria->offset=$this->id;
        $sort = new CSort();
        $sort->defaultOrder = 't.id ASC';
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'pagination'=>array('pageSize'=>1),
        ));
    }
	public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->with = array('job', 'category');
        $criteria->compare('t.id', $this->id);
        $criteria->compare('job_id', $this->jobName);
        $criteria->compare('category_id', $this->catName);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('DATE_FORMAT(date, "%d.%m.%Y")', substr($this->dbdate,0,10), true);
        $criteria->compare('DATE_FORMAT(manager_informed, "%d.%m.%Y")', substr($this->dbmanager_informed,0,10),true);
        if (isset($this->dbdate_finishend) && isset($this->dbdate_finishstart)) {
            $criteria->addCondition('"' . $this->dbdate_finishstart . '"<=DATE_FORMAT(date_finish, "%d.%m.%Y")<="' . $this->dbdate_finishend . '"');
            $criteria->addCondition('date_finish is not NULL');
        }
        else
            $criteria->compare('DATE_FORMAT(date_finish, "%d.%m.%Y")', substr($this->dbdate_finishstart,0,10), true);
        $criteria->compare('executor',$this->executor);
        $criteria->compare('status',$this->status);
        $sort = new CSort();
        $sort->defaultOrder = 't.id ASC';
        $sort->attributes = array(
            'jobName'=> array(
                'asc' => 'job.job_name',
                'desc' => 'job.job_name desc',
            ),
            'catName'=> array(
                'asc' => 'category.cat_name',
                'desc' => 'category.cat_name desc',
            ),
            'id'=> array(
                'asc' => 't.id',
                'desc' => 't.id desc',
            ),
            'title'=> array(
                'asc' => 't.title',
                'desc' => 't.title desc',
            ),
            'dateCreation'=> array(
                'asc' => 't.date',
                'desc' => 't.date desc',
            ),
            'managerInformed'=> array(
                'asc' => 't.manager_informed',
                'desc' => 't.manager_informed desc',
            ),
            'dateFinish'=> array(
                'asc' => 't.date_finish',
                'desc' => 't.date_finish desc',
            ),
        );

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'pagination'=>false,
        ));
	}

    public static function getExecutor($orderId)
    {
        return self::model()->findByPk($orderId)->executor;
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Zakaz the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

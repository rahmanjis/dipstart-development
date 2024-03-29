<?php

/**
 * хранение изменений из любых таблиц сущностей проекта
 * сохраняется имя класса модели, ид записи в таблице, имя поля, старое и новые значения
 * @property integer $id
 * @property integer $event_id
 * @property string $class_name имя класса модели
 * @property integer $id_record ид записи в таблице
 * @property string $attribute имя аттрибута модели для которого идет сохранение
 * @property string $old_value старое значение
 * @property string $new_value новое значение
 * @property integer $date_update дата обновления
 */

class Moderate extends CActiveRecord
{
	
	public function tableName() {
		return Campaign::getId().'_Moderate';
	}
    
    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public function attributeLabels()
	{
		return array(
			'attribute' => 'Атрибут',
			'old_value' => 'Старое значение',
			'new_value' => 'Новое значение',
			'date_update' => 'Дата изменения',
		);
	}
    
    public function beforeSave()
    {
        if (parent::beforeSave()) {
            
            if ($this->isNewRecord) {
                $this->date_update = new CDbExpression('NOW()');
            }
            
            return true;
        } else {
            return false;
        }
    }
    
    public function approve()
    {
        try {
            
            $model = (new $this->class_name)->findByPk($this->id_record);
            
            if ($this->attribute == 'max_exec_date') {
                $model->max_exec_date = Yii::app()->dateFormatter->format($model->dateTimeIncomeFormat, CDateTimeParser::parse($this->new_value, $model->dateTimeOutcomeFormat));
            } else {
                $model->{$this->attribute} = $this->new_value;
            }

            $model->save(false);

            $this->delete();
            
        } catch (Exception $ex) {
            
        }
    }
    
    public function afterDelete()
    {
        parent::afterDelete();
        
        if ($this->countByAttributes(['event_id'=>$this->event_id]) == 0) {
            Events::model()->deleteByPk($this->event_id);
        }
    }
}
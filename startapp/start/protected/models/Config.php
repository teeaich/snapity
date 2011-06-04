<?php

/**
 * This is the model class for table "{{config}}".
 *
 * The followings are the available columns in table '{{config}}':
 * @property integer $id
 * @property string $rule
 * @property string $value
 */
class Config extends CActiveRecord
  
{
  private $_configValue;
  /**
   * Returns the static model of the specified AR class.
   * @return Config the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return '{{config}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('rule', 'required'),
      array('rule, value', 'length', 'max'=>128),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, rule, value', 'safe', 'on'=>'search'),
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
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'rule' => 'Rule',
      'value' => 'Value',
    );
  }
  
  
  public function getConfigValue($value1) 
  {
    if ($value1===0)
      return 'error';
    else 
    {
      $configValue=Config::model()->findAllByAttributes(array(
      'rule'=> $value1,
      
    ));
      
      foreach ($configValue as $id => $provider)
        foreach ($provider as $providerValue => $values)
          $value = $values;
      return $value;
      
    } 
      
  }
    
  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search()
  {
    // Warning: Please modify the following code to remove attributes that
    // should not be searched.

    $criteria=new CDbCriteria;

    $criteria->compare('id',$this->id);
    $criteria->compare('rule',$this->rule,true);
    $criteria->compare('value',$this->value,true);

    return new CActiveDataProvider(get_class($this), array(
      'criteria'=>$criteria,
    ));
  }
}
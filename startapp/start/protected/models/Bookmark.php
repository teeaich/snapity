<?php

/**
 * This is the model class for table "{{bookmark}}".
 *
 * The followings are the available columns in table '{{bookmark}}':
 * @property integer $id
 * @property string $title
 * @property string $link
 * @property string $pre_image
 * @property integer $create_time
 * @property integer $user_bk_id
 *
 * The followings are the available model relations:
 * @property User $userBk
 */
class Bookmark extends CActiveRecord
  
{
  public $image;
  /**
   * Returns the static model of the specified AR class.
   * @return Bookmark the static model class
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
    return '{{bookmark}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('title, link', 'required'),
      //array('create_time, user_bk_id', 'numerical', 'integerOnly'=>true),
      array('title, link,','length', 'max'=>128),
      //array('pre_image', 'file', 'types'=>'jpg, gif, png'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, title, link, pre_image, create_time, user_bk_id', 'safe', 'on'=>'search'),
    );
  }
  // the abiltity just to show the bookmarks which belongs to a user specified by the user_bk_id.
  //it will only be used when the named scope has been specified in dataprovider query.
  public function scopes()
  {
    return array ('userBookmarks' => array(
      'condition' => 'user_bk_id='.Yii::app()->user->id,
      ));
      
  }
  
  /**
   * @return array relational rules.
   */
  public function relations()
  {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'user' => array(self::BELONGS_TO, 'User', 'user_bk_id'),
    );
  }
  
  /**
   * check the url whether a http is in front of it, otherwise it will append
   */

  public function validateLink($link)
  {
      
    if (!preg_match("@^https?://@i", $link) && !preg_match("@^ftps?://@i", $link)) {
        return  "http://".$link;
    }
    else return $link;
}
  

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'title' => 'Title',
      'link' => 'Link',
      'pre_image' => 'Pre Image',
      'create_time' => 'Create Time',
      'user_bk_id' => 'User Bk',
    );
  }
  protected function beforeSave()
  {
    if(parent::beforeSave())
    {
        if($this->isNewRecord)
        {
          $this->create_time=time();
          $this->user_bk_id=Yii::app()->user->id;
          $this->link= $this->validateLink($this->link);
        }
        
        return true;
    }
    else
        return false;
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
    $criteria->compare('title',$this->title,true);
    $criteria->compare('link',$this->link,true);
    $criteria->compare('pre_image',$this->pre_image,true);
    $criteria->compare('image',$this->pre_image,true);
    $criteria->compare('create_time',$this->create_time);
    $criteria->compare('user_bk_id',$this->user_bk_id);

    return new CActiveDataProvider(get_class($this), array(
      'criteria'=>$criteria,
    ));
  }
}
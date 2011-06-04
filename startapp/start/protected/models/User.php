<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $profile
 *
 * The followings are the available model relations:
 * @property Bookmark[] $bookmarks
 */
class User extends CActiveRecord

{

  public function validatePassword($password)
    {
        return $this->hashPassword($password,$this->salt)===$this->password;
    }
 
    public function hashPassword($password,$salt)
    {
        return md5($salt.$password);
    }
  

  /**
   * Returns the static model of the specified AR class.
   * @return User the static model class
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
    return '{{user}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('username, password, salt', 'required'),
      array('username, password, salt, firstname, lastname', 'length', 'max'=>128),
      array('profile', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, username, password, salt, profile', 'safe', 'on'=>'search'),
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
      'bookmarks' => array(self::HAS_MANY, 'Bookmark', 'user_bk_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'username' => 'Username',
      'password' => 'Password',
      'salt' => 'Salt',
      'firstname' => 'Firstname',
      'lastname' => 'Lastname',
      'profile' => 'Profile',
    );
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
    $criteria->compare('username',$this->username,true);
    $criteria->compare('password',$this->password,true);
    $criteria->compare('salt',$this->salt,true);
    $criteria->compare('profile',$this->profile,true);

    return new CActiveDataProvider(get_class($this), array(
      'criteria'=>$criteria,
    ));
  }
}
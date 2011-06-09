<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
  public $username;
  public $password;
  public $rememberMe;

  private $_identity;

  /**
   * Declares the validation rules.
   * The rules state that username and password are required,
   * and password needs to be authenticated.
   */
  public function rules()
  {
    return array(
      // username and password are required
      array('username, password', 'required'),
      // check if username is valid email
      array('username', 'email'),
      // rememberMe needs to be a boolean
      array('rememberMe', 'boolean'),
      // password needs to be authenticated
      array('password', 'authenticate'),
    );
  }

  /**
   * Declares attribute labels.
   */
  public function attributeLabels()
  {
    return array(
      'rememberMe'=>'Remember me next time',
    );
  }

public function isValidURL($url)
{
return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}
  /**
   * Authenticates the password.
   * This is the 'authenticate' validator as declared in rules().
   */
  public function authenticate($attribute,$params)
  {
    if(!$this->hasErrors())
    {
      $this->_identity=new UserIdentity($this->username,$this->password);
      if (!$this->_identity->authenticate())
      {
          if($this->_identity->errorCode===UserIdentity::ERROR_USERNAME_INVALID)
            //echo 'invalid Username';
            $this->addError('password','Incorrect username.');
          elseif($this->_identity->errorCode===UserIdentity::ERROR_PASSWORD_INVALID)
            //echo 'invalid password';
            $this->addError('password','Incorrect Password.');
      }
    }
  }

  
  public function loginOpenID()
  {
    if($this->_identity===null)
    {
      $this->_identity=new UserIdentityOID();
      $result = $this->_identity->authenticate();
      if ($result == false)
      { 
        Yii::app()->user->login($this->_identity);
        return false;
      } 
      //validate the given URL from authenticate unused at the moment
      /*if (isValidURL($result))
      {
          return $result;
      }*/
    else return $result;
    }
  }
    
  
  /**
   * Logs in the user using the given username and password in the model.
   * @return boolean whether login is successful
   */
  public function login()
  {
    if($this->_identity===null)
    {
      $this->_identity=new UserIdentity($this->username,$this->password);
      $this->_identity->authenticate();
    }
    if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
    {

      $duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
      Yii::app()->user->login($this->_identity,$duration);
      return true;
    }
    else
      return false;
  }
}
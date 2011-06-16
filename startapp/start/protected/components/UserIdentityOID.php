<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentityOID extends CUserIdentity
{
    private $_id;
 
    public function __construct()
       {
           
       }
    public function shortRandomNumber()
    {
    $random = '';
      for ($i = 0; $i < 4; $i++) 
      {
       $random .= chr(rand(ord('0'), ord('9')));
      }
     return $random;
    }
    
   
    public function checkEmail($email) 
    {
      if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])
          ↪*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",
               $email))
        {
          list($username,$domain)=split('@',$email);
          if(!checkdnsrr($domain,'MX'))
            {
             return false;
            }
         return true;
        }
       return false;
    }
  
      public function authenticate()
    {
      $loid = Yii::app()->loid->load();
      Yii::trace('load ok');
       if (!empty($_GET['openid_mode'])) 
       {
         if ($_GET['openid_mode'] == 'cancel') 
         {
         $err = Yii::t('core', 'Authorization cancelled');
         } else 
            {
            try 
                {
                  $loid->validate() ? 'Logged in.' : 'Failed';
                  $attributes = $loid->getAttributes();
                  $email = $attributes['contact/email'];
                  //if(!$this->checkEmail($email) ) at the moment not working/ check "checkEMail above
                  
                    $newUsername = $attributes['contact/email'];
                    if ($model=User::model()->find('LOWER(username)=?',array($newUsername)))
                    {
                      $this->_id=$model->getPrimaryKey();
                      $this->username=$model->getAttribute('username');
                    }
                  
                    else 
                    {
                      $model = new User;
                      $model->username=$attributes['contact/email'];
                      $model->firstname=$attributes['namePerson/first'];
                      $model->lastname=$attributes['namePerson/last'];
                      $model->password=$model->hashPassword('test','28b206548469ce62182048fd9cf91760');
                      $model->salt='28b206548469ce62182048fd9cf91760';
                      $model->save();
                      $this->_id=$model->getPrimaryKey();
                      $this->username=$model->getAttribute('username');
                      
                    }
                    
                  

                      
                  
                  

                  return false;
                  
                }
             catch (Exception $e) 
                {
                   $err = Yii::t('core', $e->getMessage());
                }
            }
        }
        if(!empty($err)) echo $err;
          else 
          {
            Yii::trace('hier landen wäre schon mal cool');
            $loid->identity = "https://www.google.com/accounts/o8/id"; //Setting identifier
            $loid->required = array('namePerson/first','namePerson/last',
                                    'contact/country/home','contact/email'); //Try to get info from openid provider
            $loid->optional = array('openid.ns.ui'=> 'http://specs.openid.net/extensions/pape/1.0',
                'openid.ui.mode'=>'popup');
            $loid->realm     = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
           
            $loid->returnUrl = $loid->realm . $_SERVER['REQUEST_URI']; //getting return URL
             if (empty($err)) {
                  try {
                    
                   // CController::redirect($loid->authUrl());
                    return $loid->authUrl();
                      } catch (Exception $e) {
                        echo $err = Yii::t('core', $e->getMessage());
             
                       }
              }
          } 
    }
 
    public function getId()
    {
        return $this->_id;
    }
}

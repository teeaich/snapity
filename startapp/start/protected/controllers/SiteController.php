<?php

class SiteController extends Controller
{
  public $_wert =0;
  public $layout='//layouts/base';

  /**
   * Declares class-based actions.
   */
  public function actions()
  {
    return array(
      // captcha action renders the CAPTCHA image displayed on the contact page
      'captcha'=>array(
        'class'=>'CCaptchaAction',
        'backColor'=>0xFFFFFF,
      ),
      // page action renders "static" pages stored under 'protected/views/site/pages'
      // They can be accessed via: index.php?r=site/page&view=FileName
      'page'=>array(
        'class'=>'CViewAction',
      ),
    );
  }

  /**
   * This is the default 'index' action that is invoked
   * when an action is not explicitly requested by users.
   */
  public function actionIndex()
  {
    // renders the view file 'protected/views/site/index.php'
    // using the default layout 'protected/views/layouts/main.php'
    $this->render('index');
  }

  /**
   * This is the action to handle external exceptions.
   */
  public function actionError()
  {
      if($error=Yii::app()->errorHandler->error)
      {
        if(Yii::app()->request->isAjaxRequest)
          echo $error['message'];
        else
            $this->render('error', $error);
      }
  }

  /**
   * Displays the contact page
   */
  public function actionContact()
  {
    $model=new ContactForm;
    if(isset($_POST['ContactForm']))
    {
      $model->attributes=$_POST['ContactForm'];
      if($model->validate())
      {
        $headers="From: {$model->email}\r\nReply-To: {$model->email}";
        mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
        Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
        $this->refresh();
      }
    }
    $this->render('contact',array('model'=>$model));
  }

  
  public function actionOpenId()
  {
    //$this->redirect('http://www.google.de');
    $model=new LoginForm;
    $result = $model->loginOpenID();
    if ($result == false)
    {
        $this->redirect('index.php?r=bookmark');
    }
    
    else $this->redirect($result);
  }

  
  public function actionAjaxLogin()
  {
    $model=new LoginForm;

    // Uncomment the following line if AJAX validation is needed
    $this->performAjaxValidation($model);

    if(isset($_POST['LoginForm']))
    {
      $model->attributes=$_POST['LoginForm'];
      
      
      if($model->validate() && $model->login())

        {
        
       
          if (Yii::app()->request->isAjaxRequest)
            {
             echo CJSON::encode(
             "You're logged in successfully"
             );
              exit;               
            }
          
        }
      if(!$model->validate() && $model->login())
        {    

            echo CJSON::encode(
              $this->renderPartial('login', array('model'=>$model),true, true));
            exit;               
          }
    }
         
    
            
    if (Yii::app()->request->isAjaxRequest)
        {

            echo CJSON::encode(
              $this->renderPartial('login', array('model'=>$model),true, true));

            exit;               
        }
    
    
  }
    
  /**
   * Displays the login page
  **/ 
  public function actionLogin()
  {
    $model=new LoginForm;
    $model2=new User;
    $this->performAjaxValidation($model);
    // if it is ajax validation request
    if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
    {
      $model->attributes=$_POST['login-form'];
      // validate user input and redirect to the previous page if valid
      if($model->validate() && $model->login())
      {
        //$this->redirect(Yii::app()->user->returnUrl);
               

      }
      
      else 
      {
        echo CJSON::encode(array(
        $this->renderPartial('login',array('model'=>$model,true, true))));
      }
      Yii::app()->end();
    }
      
    
    echo CJSON::encode(
    $this->renderPartial('login',array('model'=>$model),true,true));
  }
  
  /** 
  public function actionLogin()
  {
    $model=new LoginForm;

    // if it is ajax validation request
    if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }

    // collect user input data
    if(isset($_POST['LoginForm']))
    {
      $model->attributes=$_POST['LoginForm'];
      // validate user input and redirect to the previous page if valid
      if($model->validate() && $model->login())
        $this->redirect(Yii::app()->user->returnUrl);
    }
    // display the login form
    $this->render('login',array('model'=>$model));
  }

  /**
   * Logs out the current user and redirect to homepage.
   */
  public function actionLogout()
  {
    Yii::app()->user->logout();
    $this->redirect(Yii::app()->homeUrl);
  }
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  } 
}​
<?php
  
  
  class BookmarkController extends Controller
  {
    private $_configValue;
    private $_link;
    private $_bid;
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
      );
    }
    
    /**
    * Specifies the access control rules.
    * This method is used by the 'accessControl' filter.
    * @return array access control rules
    */
    public function accessRules()
    {
      return array(
        array('allow',  // allow all users to perform 'index' and 'view' actions
              // no action allowed at the moment for any non authenticated user
              'actions'=>array('getWebthumbID'),
              'users'=>array('*'),
             ),
        array('allow', // allow authenticated user to perform 'create' and 'update' actions
              'actions'=>array('create','update','admin','delete','ajaxcreate','index','view',
                  'getBookmarkTitle','getBookmarkOptions','getBigImage','crop'),
              'users'=>array('@'),
              
             ),
        array('deny',  // deny all users
              'users'=>array('*'),
             ),
      );
    }
    
    /**
    * Displays a particular model.
    * @param integer $id the ID of the model to be displayed
    */
    public function actionView($id)
    {
      $this->render('view',array(
        'model'=>$this->loadModel($id),
      ));
    }
    
    public function actionGetWebthumbID()
    {
      // First of all get the data (link and id) with the token bundled in the post request
      $data = Yii::app()->token->validate('generate', $_POST['token']);
      $job = new webThumbJob($data['link']);
      //for debugging 
      //$job = new webThumbJob('http://www.google.de');

      
      $snapshot = $job->perform();
      $model=Bookmark::model()->findByPk((int)$data['id']); 
      //for debugging
      //$model=Bookmark::model()->findByPk((int)'87');   

      $model->snapshot = $snapshot.'.jpg';
      $model->webthumbID = $snapshot;
      $model->save();
      
    }
    
    public function actionGetBigImage()
    {
        $model = new Bookmark;
        $model = Bookmark::model()->findByPk((int)$_GET['id']);
        //$model = Bookmark::model()->findByPk((int)'188');
        $webthumbID = $model->webthumbID;
        $job = new webThumbJob();
        $snapshot = $job->getBigImage($webthumbID,'large');
        $path=Yii::app()->basePath;
        header("Content-Type: image/jpeg", true);
        //echo CHtml::image($snapshot);
        //echo "<img src='".Yii::app()->getimg->readImage($snapshot)."'>";

        echo $snapshot;
    }
    
    public function actionCrop() {
        $origWidth = '640';
        $origHeight = '480';
        
        
        
        $top = (rtrim ($_POST['top'], 'px')) * -1;
        $left = (rtrim ($_POST['left'], 'px')) * -1;
        $width = rtrim ($_POST['width'], 'px');
        $height = rtrim ($_POST['height'], 'px');
        $ratio = $origWidth/$width;        
        
        
        $model = new Bookmark;
        $model = Bookmark::model()->findByPk((int)$_POST['id']);
        
        $path=Yii::app()->basePath.'/../images/bk_preview/';
        $webthumbID = $model->webthumbID;
        $job = new webThumbJob();
        $srcBigImage = $job->getBigImage($webthumbID,'large');
        
        
        $dstImagePath = $path.''.$model->webthumbID.'-new.jpg';

        $srcImage = imagecreatefromstring($srcBigImage);
        $blankCropImage = imagecreatetruecolor($origWidth,$origHeight);
        imagecopyresized($blankCropImage, $srcImage,0,0, $ratio*$left, $ratio*$top,
                                                  $origWidth,$origHeight,
                                                  $ratio*$origWidth,
                                                  $ratio*$origHeight);
        imagejpeg($blankCropImage, $dstImagePath);
        $model->snapshot = $webthumbID.'-new.jpg';
        $model->save();
        $this->redirect(array('index'));
    }
    
    public function actionRunWorker()
    {
      Yii::import('ext.djjob.DJJob');
      DJJob::configure("mysql:host=localhost;dbname=k17663_3", array(
        "mysql_user" => "k17663_3",
        "mysql_pass" => "Betzepower01",
      ));
      
      //$worker = new DJWorker();
      $worker = new DJWorker(array("count" => 2, "max_attempts" => 2, "sleep" => 10));
      $worker->start();
    }
    
    /**
    * (AJAX) Creates a new model.(AJAX)
    * If creation is successful, the browser will be redirected to the 'view' page.
    */
    public function actionAjaxCreate()
    {
      Yii::import('ext.runactions.components.ERunActions');
      $model=new Bookmark;
      $this->performAjaxValidation($model);
      
      
      if(isset($_POST['Bookmark']))
      {
        $request = Yii::app()->request;
        
        $host = $request->getHostInfo();
        $url = $host.'/startapp/start/index.php?r=bookmark/getWebthumbID';
        $model->attributes=$_POST['Bookmark'];
        $model->snapshot='loading.gif';
        $model->save();
        /*
         * prepare an array to save the link and id from saved model in token table
         */
        $myData = array('link' => $model->link, 'id' => $model->id);
        /*
         *generate and get token with $myDate array 
         */
        $token = Yii::app()->token->create('generate', array(''), 172800, $myData);
        /*
         * generate array which has the token 
         */
        $postData = array ( 'token' => $token);
        /*
         * make touchRequest with the url of actiongetWebthumbID() and the array of saved token
         * With this token the actiongetWebthumbID() again can get the link and id which are saved 
         * in the token table
         */
        ERunActions::touchUrlExt($url,$postData);
        
        
        if($model->validate())
        {
          
          
          if (Yii::app()->request->isAjaxRequest)
          {
            echo CJSON::encode(array(
              'status'=>'success', 
              'div'=>'Your Bookmark is being generated',
            ));
            
            exit;               
          }
        }
        
        if(!$model->validate())
        {    
          
          echo CJSON::encode(array(
            'status'=>'failure', 
            'div'=>$this->renderPartial('_ajaxform', array('model'=>$model),true, true)));
          exit;               
        }
        
      }
      
      
      
      if (Yii::app()->request->isAjaxRequest)
      {
        
        echo CJSON::encode(array(
          'status'=>'failure', 
          'div'=>$this->renderPartial('_ajaxform', array('model'=>$model),true, true)));
        // dont influences the ajaxform in any way.
        //Yii::app()->getClientScript()->scriptMap=array('jquery.js'=>false, 'jquery.min.js'=>false);
        
        exit;               
      }
      
      
      
    }
    public function actiongetBookmarkTitle(){
      $model=new Bookmark;
      
      $model=Bookmark::model()->findByPk((int)$_POST['id']);
      echo CHtml::encode($model->title);
      
    }
    
    public function actiongetBookmarkOptions() {
      $model=new Bookmark;
      
      $model=Bookmark::model()->findByPk((int)$_POST['id']);
      $this->renderPartial('_options', array('model'=>$model));
    }
    
    /**
    * Creates a new model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    */
    public function actionCreate()
    {
      $model=new Bookmark;
      
      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);
      
      if(isset($_POST['Bookmark']))
      {
        $model->attributes=$_POST['Bookmark'];
        $unique = $this->saveFileBatch($model->link);
        
        
        
        $model->setAttribute('snapshot',$unique.'.jpg');
        $model->setAttribute('user_bk_id',Yii::app()->user->id);
        if(!$model->save());
        echo 'jo';
        /*{
        $this->render('create',array(
        'model'=>$model,
        ));
        
      }*/
        exit;
      }
      $this->render('create',array(
        'model'=>$model,
      ));
      
    }
    
    
    
    
    /**
     * OLD FUNCTION, DEACTIVATED SINCE WEBTHUMB WAS IMPLEMENTED
    * Processing to replace the link from the model with the image_provider_link provided by the Config database
    * saves the image from webservice into database with an unique filename
    **/
    /*
    private function saveFileBatch($link)
    {
      $imageProviderLink = Config::getConfigValue('image_provider_link');
      $imageLink = str_replace ("[link]",$link,$imageProviderLink);
      if($content = file_get_contents($imageLink))
        //    echo 'download complete';
        $unique = md5( uniqid() );
      $path0=Yii::app()->basePath;
      //echo $path0.'../images/'.$unique.'.jpg';
      if(file_put_contents('/var/www/vhosts/teeaich.kodingen.com/httpdocs/yii/start/images/bk_preview/'.$unique.'.jpg',$content))
        //  echo 'pfad ok';
        
        return $unique;
    }
    */
    /**
    * Updates a particular model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param integer $id the ID of the model to be updated
    */
    public function actionUpdate($id)
    {
      $model=$this->loadModel($id);
      
      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);
      
      if(isset($_POST['Bookmark']))
      {
        $model->attributes=$_POST['Bookmark'];
        if($model->save())
          $this->redirect(array('view','id'=>$model->id));
      }
      
      $this->render('update',array(
        'model'=>$model,
      ));
    }
    
    /**
    * Deletes a particular model.
    * If deletion is successful, the browser will be redirected to the 'admin' page.
    * @param integer $id the ID of the model to be deleted
    */
    public function actionDelete($id)
    {
      if(Yii::app()->request->isPostRequest)
      {
        // we only allow deletion via POST request
        $this->loadModel($id)->delete();
        
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        /*if(!isset($_GET['ajax']))
          $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));*/
      }
      else
        throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }
    
    /**
    * Lists all models.
    */
    public function actionIndex()
    {
      
      Yii::import('ext.runactions.components.ERunActions');
      //ERunActions::touchUrlExt('http://teeaich.kodingen.com/yii/start/index.php?r=bookmark/runWorker');
      $dataProvider=new CActiveDataProvider(Bookmark::model()->userBookmarks());
      $this->render('index',array(
        'dataProvider'=>$dataProvider,
      ));
      
      
    }
    /**
    * Manages all models.
    */
    public function actionAdmin()
    {
      $model=new Bookmark('search');
      $model->unsetAttributes();  // clear any default values
      if(isset($_GET['Bookmark']))
        $model->attributes=$_GET['Bookmark'];
      
      $this->render('admin',array(
        'model'=>$model,
      ));
    }
    
    
    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer the ID of the model to be loaded
    */
    public function loadModel($id)
    {
      $model=Bookmark::model()->findByPk((int)$id);
      if($model===null)
        throw new CHttpException(404,'The requested page does not exist.');
      return $model;
    }
    
    /**
    * Performs the AJAX validation.
    * @param CModel the model to be validated
    */
    protected function performAjaxValidation($model)
    {
      if(isset($_POST['ajax']) && $_POST['ajax']==='bookmark-form')
      {
        echo CActiveForm::validate($model);
        Yii::app()->end();
      }
    }
  }
    
    
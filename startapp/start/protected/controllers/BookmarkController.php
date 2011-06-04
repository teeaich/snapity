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
              'actions'=>array(),
              'users'=>array('*'),
             ),
        array('allow', // allow authenticated user to perform 'create' and 'update' actions
              'actions'=>array('create','update','admin','delete','ajaxcreate','index','view','go','test','getPre_image'),
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
    
    public function actionGetPre_image()
    {
      Yii::import('ext.webthumb.bluga.Autoload');
      Yii::import('ext.webthumb.bluga.Webthumb');
      
      //$this->_link = 'http://www.google.de';
      //$this->_bid = 104;
      //$job = new webThumbJob($_POST['link']);
            $job = new webThumbJob('http://www.google.de');

      
      $pre_image = $job->perform();
      //$model=Bookmark::model()->findByPk((int)$_POST['id']); 
            $model=Bookmark::model()->findByPk((int)'86');   

      $model->pre_image = $pre_image;
      $model->save();
      
    }
    public function actionGo()
    
    {
      Yii::import('ext.runactions.components.ERunActions');
      if (ERunActions::runBackground(true))
      {
        
        echo 'geht ab2';
        $id = '100';
        $model=Bookmark::model()->findByPk((int)$id);   
        $model->title = 'mitPre';
        $this->getPre_image();
        $model->save();
      }
      else 
      {
        echo 'jo ab';
        
        
      }
    }
    public function actionTest()
    {
      Yii::import('ext.runactions.components.ERunActions');
      Yii::import('ext.runactions.components.EHttpTouchClient');
      
      //Yii::import('ext.httpclient.*');
      //Yii::import('ext.httpclient.adapter.*');
      
      $url = Yii::app()->getBasePath().'/start/index.php?r=bookmark/go';
      $client = new EHttpTouchClient($url,$httpClientConfig);
      $client->setParameterGet('_runaction_touch', 1);
      $client->request();
      //ERunActions::touchUrl($url,$postData=null,$contentType=null);
      //ERunActions::runAction('bookmark/go',$params=array('wert'=>'hallo'),$ignoreFilters=false,
      //$ignoreBeforeAfterAction=true,$logOutput=true,$silent=false);
      //ERunActions::touchUrl($url,$postData=null,$contentType=null);
      /*$request = Yii::app()->request;
      
      $uri = $request->requestUri;
      $port = $request->getPort();
      echo $host = $request->getHostInfo();
      $url =  "$host:$port$uri";  
      
      Yii::import('ext.runactions.components.ERunActionsHttpClient');
      $client = new ERunActionsHttpClient(true);
      
      $verb = empty($postData)? 'GET' : 'POST';
      $getParams = array('_runaction_touch'=>1);
      
      $parts=parse_url($url);
      $port = isset($parts['port']) ? $parts['port'] : 80;
      echo $parts['host'].':'.$port.$parts['path'].'?r=bookmark/go';
      echo $getParams['_runaction_touch'];
      /*if( !$client->request($parts['host'],$port,$parts['path'],$verb,$getParams,$postData,$contentType))
      {
      echo $jo;
    }else echo 'shit';
      $client->request($parts['host'],$port,$parts['path'].$parts['query'],$verb,$getParams,$postData,$contentType);*/
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
        
        //$host = $request->getHostInfo();
        //$baseUrl =  "$host";
        $url = 'http://localhost:8888/start/index.php?r=bookmark/getpre_image';
        $model->attributes=$_POST['Bookmark'];
        $model->save();
        $postData = array ( 'link' => $model->link, 'id' => $model->id);
        ERunActions::touchUrlExt($url,$postData);
        /*if (ERunActions::runBackground(true))
        {
        
        $job = new webThumbJob($this->_link);
        
        $pre_image = $job->perform();
        $model=Bookmark::model()->findByPk((int)$this->$_bid);   
        $model->pre_image = $pre_image;
        $model->save();
      }
        else
        {*/
        
        if($model->validate())
        {
          
          
          if (Yii::app()->request->isAjaxRequest)
          {
            echo CJSON::encode(array(
              'status'=>'success', 
              'div'=>'Your Bookmark is being generated'.$this->_link.$this->_bid,
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
        //echo 'model nicht saved';
        
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
      // else
      //echo 'kein ajax';
      //  $this->render('create',array('model'=>$model,));
      
      
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
        
        
        
        $model->setAttribute('pre_image',$unique.'.jpg');
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
    * Processing to replace the link from the model with the image_provider_link provided by the Config database
    * saves the image from webservice into database with an unique filename
    **/
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
        if(!isset($_GET['ajax']))
          $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
    
    
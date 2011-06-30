<?php
  class webThumbJob {
    
    private $_link;
    // Your apikey goes here
    private $_APIKEY = "f13780c9d3a3eb64f120f005231796a9";
    public $snapshot;
    
    public function __construct($link = NULL) {
      $this->_link = $link;
    }
    
    public function getBigImage($webthumbID,$size){
      // unload the Autoloader from Yii to achieve a proper autoload register for
      // the autoloader in webthumb
      // at the end of this method you'll see that the yii autoloader is loaded again
      spl_autoload_unregister(array('YiiBase','autoload'));
      require_once Yii::app()->getBasePath().'/extensions/webthumb/Bluga/Autoload.php';
      
      ini_set('max_execution_time',90);
      
      try {
        $webthumb = new Bluga_Webthumb();
        $webthumb->setApiKey($this->_APIKEY);
        spl_autoload_register(array('YiiBase','autoload'));
        return $webthumb->fetchToReturn($webthumbID,$size);
                $path=Yii::app()->basePath;

        //$webthumb->fetchToFile($webthumbID,'hallo.jpg',$size,$path.'/../images/bk_preview/');
      }  catch (Exception $e) {
        var_dump($e->getMessage());
      }
      
    }
        
    
    
    
    
    public function perform()
    {
      // unload the Autoloader from Yii to achieve a proper autoload register for
      // the autoloader in webthumb
      // at the end of this method you'll see that the yii autoloader is loaded again
      spl_autoload_unregister(array('YiiBase','autoload'));
      require_once Yii::app()->getBasePath().'/extensions/webthumb/Bluga/Autoload.php';
      
      ini_set('max_execution_time',90);
      
      try {
        $webthumb = new Bluga_Webthumb();
        $webthumb->setApiKey($this->_APIKEY);
        $job = $webthumb->addUrl($this->_link,'large', 1024, 768);
        $webthumb->submitRequests();
        
        while (!$webthumb->readyToDownload()) {
          sleep(3);
          echo "Checking Job Status\n";
          $webthumb->checkJobStatus();
        } // while (!$webthumb->ready_to_download())
        
        $path=Yii::app()->basePath;
        
        $webthumb->fetchToFile($job,NULL,NULL,$path.'/../images/bk_preview/');
        // reload the autoload function from Yii to achieve previous status
        spl_autoload_register(array('YiiBase','autoload'));

        return $job->status->id;
        
        
      }  catch (Exception $e) {
        var_dump($e->getMessage());
      }
      
    }
  }
    ?>

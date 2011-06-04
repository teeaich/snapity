<?php
  class webThumbJob {
    
    private $_link;
    //private $_id;
    public $pre_image;
    
    public function __construct($link) {
      $this->_link = $link;
      //$this->_id = $id;
    }
    
    
    
    
    public function perform()
    {
      
      require_once Yii::app()->getBasePath().'/extensions/webthumb/Bluga/Autoload.php';
      require_once Yii::app()->getBasePath().'/extensions/webthumb/Bluga/Webthumb.php';
      
      
      // Your apikey goes here
      echo $APIKEY = "f13780c9d3a3eb64f120f005231796a9";
      ini_set('max_execution_time',90);
      
      try {
        $webthumb = new Bluga_Webthumb();
        $webthumb->setApiKey($APIKEY);
        $job = $webthumb->addUrl($this->_link,'medium2', 1024, 768);
        $webthumb->submitRequests();
        
        while (!$webthumb->readyToDownload()) {
          sleep(3);
          echo "Checking Job Status\n";
          $webthumb->checkJobStatus();
        } // while (!$webthumb->ready_to_download())
        
        $path=Yii::app()->basePath;
        
        $webthumb->fetchToFile($job,NULL,NULL,$path.'/../images/bk_preview/');
        return $job->status->id.'.jpg';
        
        
      }  catch (Exception $e) {
        var_dump($e->getMessage());
      }
      
    }
  }
    ?>

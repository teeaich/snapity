<?php
  class webThumbJob {
    
    private $_link;
    public $snapshot;
    
    public function __construct($link) {
      $this->_link = $link;
    }
    
    
    
    
    public function perform()
    {
      // unload the Autoloader from Yii to achieve a proper autoload register for
      // the autoloader in webthumb
      // at the end of this method you'll see that the yii autoloader is loaded again
      spl_autoload_unregister(array('YiiBase','autoload'));
      require_once Yii::app()->getBasePath().'/extensions/webthumb/Bluga/Autoload.php';
      
      // Your apikey goes here
      echo $APIKEY = "f13780c9d3a3eb64f120f005231796a9";
      ini_set('max_execution_time',90);
      
      try {
        $webthumb = new Bluga_Webthumb();
        $webthumb->setApiKey($APIKEY);
        $job = $webthumb->addUrl($this->_link,'custom', 1024, 768);
        $job->options->customThumbnail = array('width' => 250, 'height' => 188); 
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

        return $job->status->id.'.jpg';
        
        
      }  catch (Exception $e) {
        var_dump($e->getMessage());
      }
      
    }
  }
    ?>

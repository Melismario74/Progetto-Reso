<?php

/**
 * Logging class:
 * - contains lfile, lopen and lwrite methods
 * - lfile sets path and name of log file
 * - lwrite will write message to the log file
 * - first call of the lwrite will open log file implicitly
 * - message is written with the following format: hh:mm:ss (script name) message
 */
 
class Logging{
    // define default log file
    private $log_file = '/tmp/logfile.log';
    // define file pointer
    private $fp = null;
    
    // set log file (path and name)
    public function lfile($path) {
        $this->log_file = $path;
    }
    
    // write message to the log file
    public function lwrite($message, $useDate = true){
        // if file pointer doesn't exist, then open log file
        if (!$this->fp) $this->lopen($useDate);
        
        // define current time
        $time = date('H:i:s');
        
        // write current time, script name and message to the log file
        fwrite($this->fp, "$time $message\n");
    }
    
    // open log file
    private function lopen($useDate = true){
        // define log file path and name
        $lfile = $this->log_file;
        
        // define the current date (it will be appended to the log file name)
        $today = date('Y-m-d');
        
        // open log file for writing only; place the file pointer at the end of the file
        // if the file does not exist, attempt to create it
        $filename = $lfile;
        if ($useDate) {
        	$filename = $filename . '_' . $today;
        }
        $this->fp = fopen($filename, 'a') or exit("Can't open $lfile!");
    }
}

?>
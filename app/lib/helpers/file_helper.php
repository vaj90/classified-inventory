<?php
class FileHelper{
    private $file;
    public function __construct($file){
        $this->file = $file;
    }
    public function getId(){
        global $config;
        $file_name = $config['DATA_PATH'] . $this->file . '_counter.txt' ;
        if (!file_exists($file_name))
        {
            touch($file_name);
            $handle = fopen($file_name, 'r+');
            $id = 0;
        }
        else
        {
            $handle = fopen($file_name, 'r+');
            $id = fread($handle, filesize($file_name));
            settype($id, "integer");
        }
        rewind($handle);
        fwrite($handle, ++$id);

        fclose($handle);
        return $id;
    }
}
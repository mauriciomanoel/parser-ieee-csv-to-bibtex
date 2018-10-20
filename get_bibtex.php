<?php
    set_time_limit(0);

    spl_autoload_register(function ($class_name) {
        include $class_name . '.php';
    });
    
    $break_line         = "<br>";
    $directory          = trim(@$_GET['directory']);
    

    define('BREAK_LINE', $break_line);

    try {
        if (empty($directory)) {
            throw new Exception("Directory not found");
        } 
        
        $files          = Util::loadFile($directory, array("bib"));
        foreach($files as $file) 
        {
            IEEE::download($file, $directory);
            $sleep = rand(2,5);
            Util::showMessage("In Pause: " . $sleep . " seconds");            
            sleep($sleep);
        }
    } catch(Exception $e) {
        Util::showMessage($e->getMessage());
    }
?>
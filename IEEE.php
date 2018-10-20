<?php

define('URL_IEEE_BIBTEX', 'https://ieeexplore.ieee.org/xpl/downloadCitations?recordIds=[RECORDIDS]&download-format=download-bibtex&citations-format=citation-abstract');

class IEEE {
    
    public static function download($file, $directory) 
    {
        Util::showMessage("File: " . $file);
        $lines = file($file);
        Util::showMessage("Total of Articles: " . count($lines));
        $i=0;
        $recordId = "";
        $bibtex = "";
        foreach($lines as $line) 
        {
            if ($i == 0) {
                $i++;
                continue;
            }

            $dataIEEE = explode('","', $line);
            $recordId .= self::getRecordId($dataIEEE[15]) . "%2C";
            
            if ($i % 30 == 0) {
                $bibtex .= self::get_bibtex($recordId);
                $recordId = "";
                $bibtex .= "\r\n";

                Util::showMessage("In Pause: " . $i . "/" . count($lines));
                $sleep = rand(1,3);
                sleep($sleep);
            }
            $i++;
        }
        if (!empty($recordId))
        {
            $bibtex .= self::get_bibtex($recordId);
        }

        $file = str_replace(".csv", ".bib", $file);
        // save bibtex from file
        file_put_contents($file, $bibtex);
        Util::showMessage("Finish bibtex download: " . $file);
    }

    public static function get_bibtex($recordId) 
    {
        $recordId = rtrim($recordId, "%2C");
        $url = str_replace("[RECORDIDS]", $recordId, URL_IEEE_BIBTEX);
        $temp = Util::loadURL($url);
        return self::clear($temp);
    }
    public static function clear($bibtex) {
        $bibtex = str_replace("<br>@", "\r\n@", $bibtex);
        $bibtex = str_replace(",\r\n<br>", ", ", $bibtex);
        $bibtex = str_replace("<br>", "", $bibtex);
        return $bibtex;
    }

    public static function getRecordId($str) 
    {
        $int = (int) filter_var($str, FILTER_SANITIZE_NUMBER_INT);
        return $int;
    }
    
    public static function getFileName($file) {

    }
}
    
?>

<?php

function readURL($url, $cacheSeconds = null)
{
    $filename = 'cache/'.urlencode($url).'.html';
    
    if($cacheSeconds)
    {
        if(file_exists($filename))
        {
            if(time() - filemtime($filename) < $cacheSeconds)
                return file_get_contents($filename);
        }
    }
    
    // create a new cURL resource
    $ch = curl_init();
    
    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // grab URL and pass it to the browser
    $ret = curl_exec($ch);
    
    // close cURL resource, and free up system resources
    curl_close($ch);
    
    if($cacheSeconds)
    {
        file_put_contents($filename, $ret);
    }
    
    return $ret;
}
?>
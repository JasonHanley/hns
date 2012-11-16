<?php

define('PROJECT_DIR', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

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
    
    if($ret && $cacheSeconds)
    {
        file_put_contents($filename, $ret);
    }
    
    return $ret;
}

class myDB
{
    /** @var PDO */
    public static $pdo = null;

    public static function init()
    {
        $filename = PROJECT_DIR.'db.sqlite';
        self::$pdo = new PDO('sqlite:'.$filename);
        return self::$pdo;
    }

    public static function doQuery($sql, array $params)
    {
        $sth = myDB::$pdo->prepare($sql);
        if($sth->execute($params))
            $ret = $sth->fetchAll();
        else 
            print_r(myDB::$pdo->errorInfo());
        return $ret;
    }
    
    public static function doInsert($sql, array $params)
    {
        $sth = myDB::$pdo->prepare($sql);
        if($sth)
            $ret = $sth->execute($params);
        else 
            print_r(myDB::$pdo->errorInfo());
        return $ret;
    }
}

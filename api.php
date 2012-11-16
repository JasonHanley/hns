<?php

require_once('config.php');
require_once('custom.php');

$pdo = myDB::init();

$action = '';
if(isset($_GET['action']))
    $action = $_GET['action'];

if($action == 'trk')
{
    $json = $_POST['data'];
    $datas = json_decode($json, true);
    foreach($datas as $data)
    {
        $url = $data['url'];
        $fromId = $data['fromId'];
        $fromName = $data['fromName'];
        $urlGuid = uniqid('');
        $sourceGuid = uniqid('');
        
        $results = myDB::doQuery('select * from sources where fbid=?', array($fromId));
        if(!count($results))
        {
            // Insert new source
            myDB::doInsert('insert into sources values (?,?,?)', array($sourceGuid, $fromName, $fromId));
        }
        elseif(count($results) == 1)
        {
            // Get existing source GUID
            $result = $results[0];
            $sourceGuid = $result['guid'];
        }
        
        $results = myDB::doQuery('select * from urls where url=?', array($url));
        if(!count($results))
        {
            // Insert new URL
            myDB::doInsert('insert into urls values (?,?)', array($urlGuid, $url));
        }
        elseif(count($results) == 1)
        {
            $result = $results[0];
            $urlGuid = $result['guid'];
        }
        
        // TODO: Check for duplicates
        
        myDB::doInsert('insert into url_source values (?,?)', array($urlGuid, $sourceGuid));
    }
}

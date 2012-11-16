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
        $guid = uniqid('');
                
        $results = myDB::doQuery('select * from sources where fbid=?', array($fromId));
        if(!count($results))
        {
            // Insert new source
            $sourceGuid = uniqid('');
            myDB::doInsert('insert into sources values (?,?,?)', array($sourceGuid, $fromName, $fromId));
        }
        elseif(count($results) == 1)
        {
            // Get existing source GUID
            $source = $results[0];
            $guid = $source['guid'];
        }
        else
        {
            echo 'ERROR: Multiple sources ('.$fromId.')';
        }
        
        myDB::doInsert('insert into urls values (?,?)', array($guid, $url));
        myDB::doInsert('insert into url_source values (?,?)', array($guid, $sourceGuid));
    }
}

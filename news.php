<h1>The news</h1>
<i>Refreshed every 5 minutes</i>
<ol>
<?php 
    $html = readURL('http://news.ycombinator.com/', 300);
    $doc = new DOMDocument();
    @$doc->loadHTML($html); // NOTE: Suppressing warnings (there's lots)
    
    $body = $doc->firstChild->nextSibling->firstChild->nextSibling;
    assert($body->nodeName == 'body');
    
    $table = $body->firstChild->firstChild;
    assert($table->nodeName == 'table');
    
    $newsTable = $table->firstChild->nextSibling->nextSibling->firstChild->firstChild;
    assert($newsTable->nodeName == 'table');
    
    $nextRow = $newsTable->firstChild;
    $story = 1;
    
    while($nextRow->nextSibling)
    {
        assert($nextRow->nodeName == 'tr');
        
        if($nextRow->firstChild)
        {
            echo '<li>';
            
            $link = $nextRow->firstChild->nextSibling->nextSibling->firstChild;
            assert($link->nodeName == 'a');
            
            $title = $link->nodeValue;
            $url = $link->attributes->getNamedItem('href')->nodeValue;
            
            $results = myDB::doQuery('select * from urls where url like ?', array($url));
            if(count($results) < 1)
            {
                $guid = uniqid('');
                
                myDB::doInsert('insert into urls values(?,?)', array($guid, $url));
                    
                $hnid = '50a58983407aa';
                
                $results = myDB::doQuery('select * from url_source where url like ? and source like ?', 
                    array($guid, $hnid));

                if(count($results) < 1)
                {
                    myDB::doInsert('insert into url_source values(?,?)', array($guid, $hnid));
                }
            } 
            
            echo '<a href="'.$url.'" target="_blank">'.
                $title.'</a><br>';
            
            $nextRow = $nextRow->nextSibling;
            //comments
            
            $nextRow = $nextRow->nextSibling;
            //spacing

            echo '</li>';
        }
        
        $nextRow = $nextRow->nextSibling;
        
        $story++;
    }
?>
</ol>

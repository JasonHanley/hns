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
            
            echo '<a href="'.$link->attributes->getNamedItem('href')->nodeValue.'" target="_blank">'.
                $link->nodeValue.'</a><br>';
            
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

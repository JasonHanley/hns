<h2>Hacker News Social</h2>

<div class="alert alert-block alert-success">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <h4>So how does this work?</h4>
  Click <a href="index.php?page=mine">My Stories</a> to connect to Facebook and
  show recent stories from your newsfeed.
  <br>
  Then, when you come back to this page, it will show you who posted these stories on Facebook.   
  <br>
  <i>This page is refreshed every 5 minutes from <a href="http://news.ycombinator.com/" target="_blank">Hacker News</a></i>
</div>

<ol>
<?php
    $hnid = '50a58983407aa';

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
            $posters = '';
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
                    
                $results = myDB::doQuery('select * from url_source where url like ? and source like ?', 
                    array($guid, $hnid));

                if(count($results) < 1)
                {
                    myDB::doInsert('insert into url_source values(?,?)', array($guid, $hnid));
                }
            }
            else 
            {
                $urlrow = $results[0];
                
                // See if this has been posted by friends
                $results = myDB::doQuery('select * from url_source where url=?', array($urlrow['guid']));
                if(count($results))
                {
                    foreach($results as $result)
                    {
                        $results = myDB::doQuery('select * from sources where guid=?', array($result['source']));
                        if(count($results))
                        {
                            $source = $results[0];
                            if($source['guid'] != $hnid && $source['fbid'])
                                $posters .= '<img class="profile" src="http://graph.facebook.com/'.$source['fbid'].'/picture" /> ';
                        }
                    }
                }
            } 
            
            echo '<span class="post">'.$posters.'<a href="'.$url.'" target="_blank">'.
                $title.'</a><span>';
            
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

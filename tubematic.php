<?php
/*
Plugin Name: TubeMatic
Plugin URI: http://www.griido.it/tubematic-plugin-wordpress/
Description: This plugin is made for automatically embedding videos into a blog post or page.  
Author: Redazione di Griido.it
Version: 1.0
Author URI: http://www.griido.it/
*/

require_once("config.php");

function shortcode_tubematic($attr = array(), $content = NULL) {  
  global $tm_views_label, $tm_duration_label, $tm_datetime_filer, $tm_by_label;
  $showtitle=TRUE;
  $showdate=TRUE;
  $showcount=TRUE;
  $showuser=TRUE;
  $type="";
  $limit=6;
  $spanrow=3;
  $lr='';
  
  if (isset($attr['query'])){$q = ereg_replace('[[:space:]]+', '/', trim(strtolower($attr['query'])));}
  if (isset($attr['limit'])){$limit = (integer) $attr['limit'];} 
  if (isset($attr['spanrow'])){$spanrow = (integer) $attr['spanrow'];}
  if (isset($attr['type'])){$type = strtolower($attr['type']);}
  if (isset($attr['orderby'])){$orderby = strtolower($attr['orderby']);}  	  
  if (isset($attr['keys'])){$keys = urlencode(trim(strtolower($attr['keys'])));}
  if (isset($attr['showtitle'])){if(strtolower($attr['showtitle'])=='n'){$showtitle=FALSE;}} 
  if (isset($attr['showdate'])){if(strtolower($attr['showdate'])=='n'){$showdate=FALSE;}} 
  if (isset($attr['showcount'])){if(strtolower($attr['showcount'])=='n'){$showcount=FALSE;}} 
  if (isset($attr['showuser'])){if(strtolower($attr['showuser'])=='n'){$showuser=FALSE;}} 
  if (isset($attr['lr'])){$lr = strtolower($attr['lr']);}

  if ($spanrow>0) $spanrow--; else $spanrow=2;
  if ($limit<=0) $limit=6;
            
  switch ($type) {
    //The latest videos from a channel or only the videos that match your keywords    
    case "uploads":
      $feedURL=tubematic_composeurl("http://gdata.youtube.com/feeds/api/users/$q/uploads",$limit,$orderby,$lr);                     
      /*
      use this parameter to create a filter for a feed. Obtain only the videos that contain your query in the metadata (title, tags, description).
      */
      if (!empty($keys)) $feedURL.="&q=$keys";
    break;
    // Search in a category
    case "category":    
      $feedURL = tubematic_composeurl("http://gdata.youtube.com/feeds/api/videos/-/{$q}",$limit,$orderby,$lr);      
      break;
    // Search feeds
    case "searchfeed":        
      $feedURL = tubematic_composeurl("http://gdata.youtube.com/feeds/api/videos",$limit,$orderby,$lr);                     
      /*
      use this parameter to create a filter for a feed. Obtain only the videos that contain your query in the metadata (title, tags, description).
      */
      if (!empty($keys)) $feedURL.="&q=$keys";
      break;            
    default:
      $feedURL="";
      break;
  }
    
  if (empty($feedURL)) return ""; // nothing to do
  
  // read feed into SimpleXML object
  $sxml = @simplexml_load_file($feedURL);
  //echo $sxml->title;
  //iterate over entries in feed
  $span=0;
  $vg.="<table>";    
  
  if (!$sxml) return ""; // uhm.. something was bad
  
  foreach ($sxml->entry as $entry) {    
    
    // get date
    $published=$entry->published;
          
    // get nodes in media: namespace for media information
    $media = $entry->children('http://search.yahoo.com/mrss/');
    
    // get video player URL
    $attrs = $media->group->player->attributes();
    $watch = $attrs['url']; 
    
    // get video thumbnail
    $attrs = $media->group->thumbnail[0]->attributes();
    $thumbnail = $attrs['url']; 
          
    // get <yt:duration> node for video length
    $yt = $media->children('http://gdata.youtube.com/schemas/2007');
    $attrs = $yt->duration->attributes();
    $length = $attrs['seconds']; 
    
    // get <yt:stats> node for viewer statistics
    $yt = $entry->children('http://gdata.youtube.com/schemas/2007');
    $attrs = $yt->statistics->attributes();                  
        
    //if (!in_array($orderby,array("published","updated"))) $viewCount = $attrs->viewCount;
    //else $showcount=false;          
    $viewCount = @$attrs->viewCount;
    if (!$viewCount) $showcount=false;
    
    // get <gd:rating> node for video ratings
    /*$gd = $entry->children('http://schemas.google.com/g/2005'); 
    if ($gd->rating) {
      $attrs = $gd->rating->attributes();
      $rating = $attrs['average']; 
    } else {
      $rating = 0; 
    }*/ 
          
    if ($span==0) $vg.="<tr>";
    $vg .= '<td style="border:1px dashed gray;"><table>
    	';
    	$vg.='<tr><td valign="top" style="padding:3px"><a href="'.$watch.'" target="youtube"><img src="'.$thumbnail.'" /></a></td><td valign="top"><table cellspacing="0" cellpadding="0">';
      if($showtitle){$vg.='<tr><td><span style="font-size:10px; font-weight:bold;">'.$media->group->title.'</span></td></tr>';}
    	if($showuser){$vg.='<tr><td><span style="font-size:10px; font-weight:normal;">'.$tm_by_label.'<a href="http://www.youtube.com/user/'.$entry->author->name.'">'.$entry->author->name.'</a></span></td></tr>';}
      if($showdate){$vg.='<tr><td><span style="font-size:10px;">'.strftime($tm_datetime_filer,strtotime($published)).'</span></td></tr>';}
      if($showcount){$vg.='<tr><td><span style="font-size:10px;">'.$tm_views_label.$viewCount.'</span></td></tr>';}        	
    	$vg.='<tr><td><span style="font-size:10px;">'.$tm_duration_label.$length.'s</span></td></tr>';    	
    	$vg.='</table></td></tr></table>
    ';
    if ($span==$spanrow) { $vg.="</td></tr>"; $span=0; }
    else $span++;
  } 
  $vg.="</table>";  
  return $vg;      
}

function tubematic_composeurl($feedURL,$limit,$orderby,$lr='') {     
    /*
    orderby={updated, viewCount, rating, relevance}: sort the items from feed by upload date, number of views, rating or relevance    
    */    
    if (!in_array($orderby,array("updated","rating","relevance","published"))) $orderby="viewCount";
    $feedURL.="?orderby=$orderby";        
    /*
    the maximum number of items from a feed (by default, a feed includes only 25 items)
    */
    $feedURL.="&max-results=$limit";
    /*
    the lr parameter restricts the search to videos that have a title, description or keywords in a specific language. Valid values for the lr parameter are ISO 639-1 two-letter language codes. You can also use the values zh-Hans for simplified Chinese and zh-Hant for traditional Chinese. 
    */
    if (!empty($lr)) $feedURL.="&lr=$lr";
    return $feedURL;
}

add_shortcode('tubematic', 'shortcode_tubematic');
?>
<?php

declare(strict_types=1);

namespace App;

class Utils {

	public static function getRelativeTime($time): string 
	{
		$date = $time->getTimestamp();
		$relative_to = time();
		$delta = $relative_to - $date;
		$delta = $delta < 2 ? 2 : $delta;
		$r = '';
		if ($delta < 60) $r = $delta . 's';
		else if ($delta < 120) $r = 'm';
		else if ($delta < 45*60) $r = round($delta/60) . 'm';
		else if ($delta < 2*60*60) $r = '1h';
		else if ($delta < 24*60*60) $r = round($delta/3600) . 'h';
		else if ($delta < 48*60*60) $r = 'včera';
		else if ($delta < 24*60*60*30) $r = 'před ' . round($delta/86400) . ' dny';
		else {
			$months = array("ledna", "února", "března", "dubna", "května", "června", "července", "srpna", "září", "října", "listopadu", "prosince");
			$r = date("j. ", $date).$months[date("n", $date)].date(" Y", $date);
		}
		return $r;
	}

	public static function getHtmlTweet($tweet, $links=true, $users=true, $hashtags=true): string
	{
	    $return = $tweet->text;

	    $entities = array();

	    if($links && is_array($tweet->entities->urls))
	    {
	        foreach($tweet->entities->urls as $e)
	        {
	            $temp["start"] = $e->indices[0];
	            $temp["end"] = $e->indices[1];
	            $temp["replacement"] = "<a href='".$e->expanded_url."' target='_blank'>".$e->display_url."</a>";
	            $entities[] = $temp;
	        }
	    }
	    if($users && is_array($tweet->entities->user_mentions))
	    {
	        foreach($tweet->entities->user_mentions as $e)
	        {
	            $temp["start"] = $e->indices[0];
	            $temp["end"] = $e->indices[1];
	            $temp["replacement"] = "<a href='https://twitter.com/".$e->screen_name."' target='_blank'>@".$e->screen_name."</a>";
	            $entities[] = $temp;
	        }
	    }
	    if($hashtags && is_array($tweet->entities->hashtags))
	    {
	        foreach($tweet->entities->hashtags as $e)
	        {
	            $temp["start"] = $e->indices[0];
	            $temp["end"] = $e->indices[1];
	            $temp["replacement"] = "<a href='https://twitter.com/hashtag/".$e->text."?src=hash' target='_blank'>#".$e->text."</a>";
	            $entities[] = $temp;
	        }
	    }

	    usort($entities, function($a,$b){return($b["start"]-$a["start"]);});


	    foreach($entities as $item)
	    {
	        $return = Utils::mb_substr_replace($return, $item["replacement"], $item["start"], $item["end"] - $item["start"]);
	    }

	    return($return);
	}

	public static function mb_substr_replace($string, $replacement, $start, $length=NULL): string 
	{
	    if (is_array($string)) {
	        $num = count($string);
	        // $replacement
	        $replacement = is_array($replacement) ? array_slice($replacement, 0, $num) : array_pad(array($replacement), $num, $replacement);
	        // $start
	        if (is_array($start)) {
	            $start = array_slice($start, 0, $num);
	            foreach ($start as $key => $value)
	                $start[$key] = is_int($value) ? $value : 0;
	        }
	        else {
	            $start = array_pad(array($start), $num, $start);
	        }
	        // $length
	        if (!isset($length)) {
	            $length = array_fill(0, $num, 0);
	        }
	        elseif (is_array($length)) {
	            $length = array_slice($length, 0, $num);
	            foreach ($length as $key => $value)
	                $length[$key] = isset($value) ? (is_int($value) ? $value : $num) : 0;
	        }
	        else {
	            $length = array_pad(array($length), $num, $length);
	        }
	        // Recursive call
	        return array_map(__FUNCTION__, $string, $replacement, $start, $length);
	    }
	    preg_match_all('/./us', (string)$string, $smatches);
	    preg_match_all('/./us', (string)$replacement, $rmatches);
	    if ($length === NULL) $length = mb_strlen($string);
	    array_splice($smatches[0], $start, $length, $rmatches[0]);
	    return join($smatches[0]);
	}

}

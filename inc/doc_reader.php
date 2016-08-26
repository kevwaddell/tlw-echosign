<?php 
function cleanEncoding( $text, $type='standard' ){
    $encoding = mb_detect_encoding($text, 'UTF-8, ISO-8859-1');
    	
    if ( $type=='standard' ) {
    $outp_chr = array('...',          "'",            "'",            '"',            '"',            '•',            '-',            '-');     
    } elseif ( $type=='reference' ) {
       $outp_chr = array('&#8230;',      '&#8216;',      '&#8217;',      '&#8220;',      '&#8221;',      '&#8226;',      '&#8211;',      '&#8212;');
    }
    
    $utf8_chr = array("\xe2\x80\xa6", "\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", '\xe2\x80\xa2', "\xe2\x80\x93", "\xe2\x80\x94");
    $winc_chr = array(chr(133),       chr(145),       chr(146),       chr(147),       chr(148),       chr(149),       chr(150),       chr(151));        
    $text = str_replace( $utf8_chr, $outp_chr, $text);
    // Next, replace Windows-1252 characters.
    $text = str_replace( $winc_chr, $outp_chr, $text);
    // even if the string seems to be UTF-8, we can't trust it, so convert it to UTF-8 anyway
    $text = mb_convert_encoding($text, 'UTF-8', $encoding);
    return $text;
}

function parseWord($userDoc) {
    
    $fileHandle = fopen($userDoc, "r");
    $line = fread($fileHandle, filesize($userDoc));  
    
    $lines = explode(chr(0x0D),$line); 
    
    $lineTotal = count($lines);
    $lineCounter = 0;
    $start = false;
    $intro = false;
    $addressStart = false;
    $addressEnd = false;
    $endKey = $lineTotal;
    $addressKey = 0;
    
    $outtext = "";
    
    //return pre($lines);
    
    foreach($lines as $key => $str) {
	    $line = $str;
		$line = cleanEncoding($line);
	    $line = preg_replace( '/[\x00-\x1F\x80-\xFF]/', "", $line );
	    
	    if (trim(strtolower($line)) == "intro") {
		$start = true;  
		$intro = true;
		$outtext .= "<div class=\"letter-intro\">";
		continue;
	    }

	    if (trim(strtolower($line)) == "address") {
		$start = true;  
		$addressKey = $key;  
		$addressStart = true;
		if ($intro) {
		$outtext .= "</div>";	
		}
		$outtext .= "<div class=\"letter-address\">";
		continue;
	    }
	    
	   if (trim(strtolower($line)) == "content") {   
		$start = true;  
		if ($addressStart) {
		$outtext .= "</div>";
		$addressStart = false;
		$addressEnd = true;	
		}
		continue;
	    }
	    
	    if (trim(strtolower($line)) == "finish") {  
		$start = false;
	    }
	    
	    if ($start && strlen($str) > 1) {
		$outtext .= "<p>". trim($str) ."</p>";   
	    }
	   
	   $lineCounter++;
    }
    
    return cleanEncoding($outtext);
    
    //return pre($lines);
} 
?>
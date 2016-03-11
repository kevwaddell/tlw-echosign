<?php 
function getNumPagesPdf($filepath) {
  $pdftext = file_get_contents($filepath);
  $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
  return $num;
 }

function convert_pdf($src, $fg = 1, $lp = 1) {
global $host;
	
	if ($host == 'tlw-esign.dev') {
	$gs_path = '/usr/local/bin/gs';	
	} else if ('tlw-echosign.dev') {
	$gs_path = '/opt/local/bin/gs';			
	} else {
	$gs_path = '/usr/bin/gs';	
	}

	
	$pdf = $src.'.pdf';
    $quality = 90;
    $res = '72';
    $exportPath = $src."_".$fg.".jpg";

     
    set_time_limit(900);
    exec("$gs_path -dNOPAUSE -sDEVICE=jpeg -dUseCIEColor -dFirstPage=$fg -dLastPage=$lp -sPAPERSIZE=a4 -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -o$exportPath -r$res -dJPEGQ=$quality $pdf", $output);
}		
?>
<?php 
 // Function to remove folders and files 
function rrmdir($dir) {	
    
    if ( is_dir($dir) ) {
     
        $files = scandir($dir);
        //echo '<pre>';print_r($dir);echo '</pre>'; 
        
        if ($files) {
       
	        foreach ($files as $file) {
	        //echo 'Remove --------<br>';print_r($file);echo '<br>';  
	         
	            if ($file != "." && $file != "..") {
	            rrmdir($dir. "/" .$file);
				}
	
			}
		
		}

    } else if ( file_exists($dir) ) {
	    unlink($dir);
	}
}

// Function to Copy folders and files       
function rcopy($src, $dst) { 
	 
	 if (file_exists ( $dst )) {
     rrmdir ( $dst );
     }
	 
	 if ( is_dir ( $src ) ) {
     
     $files = scandir ( $src );
	 mkdir ( $dst );	
		   	 	
		foreach ($files as $file) {
			   	
			if ($file != '.' && $file != '..') {
			rcopy ( $src .'/'. $file, $dst .'/'. $file );	
			rrmdir ( $src .'/'. $file );
			}
			
			$iterator = new FilesystemIterator($src);
			$isDirEmpty = !$iterator->valid();	  
		
			if ($isDirEmpty) {
			rmdir($src);	
			} 
		}

        
     } else if ( file_exists ($src) ) {
	 copy ( $src, $dst );   
     }
}	
?>
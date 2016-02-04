<?php 
function pre($pre) {
	echo "<pre class=\"debug\">";
	echo "</br>";
	echo "***----------Start of pre ---------***";
	echo "</br>";
	print_r($pre);
	echo "</br>";
	echo "***----------End of pre ---------***";
	echo "</br>";
	echo "</pre>";	
}	
?>
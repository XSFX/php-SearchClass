<?php
include 'Search.php';
try{
	$s = new Search($_POST['search'],'people', [ 'exclude' =>['id','username']]);
}catch (Exception $e){
	echo 'Cought Exception: '. $e->getMessage();
}
//   echo '<pre>'; print_r($s->searchParams); echo '</pre>';
echo '<pre>'; var_dump($s->result); echo '</pre>';

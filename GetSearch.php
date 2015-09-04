<?php
include 'Search.php';

$s = new Search($_POST['search'],'people', [ 'exclude' =>['id','username']]);
//   echo '<pre>'; print_r($s->searchParams); echo '</pre>';
echo '<pre>'; print_r($s->result); echo '</pre>';

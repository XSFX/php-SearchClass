<?php


class Search{
	
	private $config = [
			'mysql' => [
					'host'=>'localhost',
					'database'=>'database',
					'user'=>'root',
					'password'=>'pass',
				
			],
			
		];
	
	private $searchParams; // search parameters from the form; needs to be private
	public  $result = false; // result send as array;
	private static $connection; // MySqli connection;
	private static $count = 0; // make sure mysqli connecion is not initialized more than one time;
	private $table;	// table in witch to search;
	private $columns;
	private $params;
	
	/*
	 * $search - search params;
	 * $table - table to search into;
	 * $include - columns in witch to search;
	 * $exclude - columns in witch not to search;
	 */ 
	public function __construct($search, $table = null, $params = null){
// 	print_r($params);
		if(isset($params['include']) && isset($params['exclude'])){
			
			throw new Exception('Cannot set include end exclude ot the same time!');
			
			return;
		}
		$this->params = $params;
		if(self::$count === 0){
			$this->initiateConnection();
		}
		self::$count++;
		$this->table=$table;
		$this->getClumns();
		$this->serializeColumns($this->columns);
		$this->getSearchParams($search);
		
	

// 		$this->result = ['a','b','c'];		
		
	}
	
	private function getSearchParams($searchParams){
		$this->searchParams = explode(' ', $searchParams);
		$this->findResults();	
		$this->serializeSearchParams($searchParams);
	}
	
	private function serializeSearchParams($searchParams){
		
	}
	
	private function initiateConnection(){
		self::$connection =  mysqli_connect($this->config['mysql']['host'],$this->config['mysql']['user'],$this->config['mysql']['password'],$this->config['mysql']['database']) or die(mysqli_error(self::$connection));		
		
	}
	
	private function findResults(){
		
		$i = 0;
		foreach ($this->searchParams as $v){
			if($i == 0 ){
				$searchParams = "'%$v%'";
			}else {
				$searchParams .= " OR '%$v%'";
			}
			$i++;
		}
	
		
		$query = "SELECT * FROM  $this->table WHERE CONCAT($this->columns) LIKE $searchParams";
		echo $query;
		$result = mysqli_query(self::$connection,$query) or die(mysqli_error(self::$connection));
		while($row = mysqli_fetch_assoc($result)){
			
			$this->result[] = $row;
		}
	}
	
	private function  getClumns(){
		$result =  mysqli_query(self::$connection, 'SHOW COLUMNS FROM '.$this->table);
		while ( $row = mysqli_fetch_assoc($result)){
			$this->columns[] = $row['Field'];
		}
	}
	
	private function serializeColumns($columns){
		$i=0;
		
		foreach ($columns as $column){
			
			
			if(isset($this->params['include']) && !in_array($column, $this->params['include'])){
				continue;
			}
			if(isset($this->params['exclude']) && in_array($column, $this->params['exclude'])){
				continue;
			}
			
			$i++;
			if($i == 1){
				$column_names = " ' : ' , " . $column;
			}else{
				$column_names .= " , ' : ' , " . $column;
			}
		}
// 		echo '<pre>'; print_r($column_names); echo '</pre>';
		$this->columns = $column_names;
	}
	
	
}
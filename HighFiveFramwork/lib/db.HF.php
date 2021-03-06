<? dependencies();

/*! Contains every class, even CRUD, to manage a MySQL database */	
class HFdb {
	
	/*! Connect to the database with the passed data
		If you want just to connect to the database without choosing a table just leave the $tableName var empty		
	*/
	function connect($ip="localhost",$user="root",$password="root",$tableName=""){
		$link = mysql_connect($ip, $user, $password);
		if (!$link) {
			die('Error connecting to MySQL database on ip '.$ip.' : ' . mysql_error());
		}
		if($tableName!=""){
			$db_selected = mysql_select_db($tableName, $link);
			if (!$db_selected) {
				die ('Db connection is fine, but error connecting to the table '.$tableName.' : ' . mysql_error());
			}
		}
	}
	
	
	/*! Convert any Select Query into an assoc-array - return array */
	function sqlToArray($query){
		$result = mysql_query($query) or die ("Error HFdb::sqlToArray '$query' :<br/>".mysql_error());
		$i=0;
		$array = array();
		while ($row = mysql_fetch_assoc($result)) {
			foreach($row as $key=>$value){
			    $array[$i][$key] = $value;
			}
			$i++;
		}
		return $array;
	}

	/*! Insert values into a tableName (param1) with array's content.
		Keys are the table db fields name and the values are the values to be put in
		This will work like a charm if you set all the fields into a form and pass $_POST as second param!
		C of CRUD
	*/
	function insert($tableName, $array){
		
		if(!is_array($array)){ echo "Array is needed in HFdb::insert() function! The second parameter must be an assoc array. Read the docs!"; die; }
		
		foreach($array as $k=>$v){
			
			if(isset($keys)){ $keys.=","; $values.=","; }
			$keys .= $k;
			if(is_numeric($v)){
				$values .= $v;
			}else{
				$values .= "'".$v."'";
			}
			
		}
		$sql  = "INSERT INTO $tableName ($keys) VALUES ($values)";
		
		mysql_query($sql) or die ("Error with HFdb::insert \"$sql\":

" .mysql_error());
		return $this;
	}

	/*! Select fromTable (param1) the fields * (all) ex."id,name,email"
		Where a field is something like ex."WHERE name = '".$name."'" will output "WHERE name = 'nameIntoVar'" (strings need the single quotes)
		Limit will limit the output ex."LIMIT 0,20" will take 20 fields from index 0
		R of CRUD
	*/
	function select($fromTable,$theFields="*",$where="",$limit=""){
		
		if($where!="") $where = " WHERE ".$where;
		if($limit!="") $limit = " LIMIT ".$limit;
		
		$sql  = "SELECT $theFields FROM $tableName$where$limit";
		
		mysql_query($sql) or die ("Error with HFdb::select '$sql': " .mysql_error());
		return $this;
	}

	/*! Update values of id (param1) into a tableName (param2) with array's content (param3).
		This will work like a charm if you set all the fields into a form and pass $_POST as third param!
		U of CRUD
	*/
	function update($id, $tableName, $values){
		
		if(!is_array($values)) return false;
		
		foreach($values as $k=>$v){
			
			if(isset($val)){ $val.=","; }
			$val .= $k."=";
			if(is_numeric($v)){
				$val .= $v;
			}else{
				$val .= "'".$v."'";
			}
			
		}
		$sql  = "UPDATE $tableName SET $val WHERE id=$id";
		
		mysql_query($sql) or die ("Error with HFdb::update '$sql': " .mysql_error());
		return $this;
	}

	/*! Delete ID fromTable
		D of CRUD
	*/
	function delete($ID,$fromTable){
		$sql  = "DELETE FROM $fromTable WHERE id=$ID";
		mysql_query($sql) or die ("Error with HFdb::delete '$sql': " .mysql_error());
		return $this;
	}
	
	
	
	
	
	
}
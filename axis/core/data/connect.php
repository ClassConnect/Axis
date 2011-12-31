<?php
// mongo connection
// connect
$mongocon = new Mongo();

// select a database
$mdb = $mongocon->dev;


// memcached connection
$memcache = new Memcache;
$memcache->connect('localhost', 11211) or die ("Could not connect");

// retrieve memcache key
function getMemKey($key) {
    global $memcache;
    return $memcache->get($key);
}

// set memcache key
function setMemKey($key, $data, $bool, $time) {
    global $memcache;
    return $memcache->set($key, $data, $bool, $time);
}


// del memcache key
function delMemKey($key) {
    global $memcache;
    return $memcache->delete($key);
}


// mysql connection & functions
DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', 'e40represent');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'cc4');

$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('MySQL Connection Error');


// Generic MySQL query function...made to scale in future.
function good_query($string)
{
	global $dbc;
    $result = @mysqli_query($dbc, $string);
    return $result;
}



// Good query for list

function good_query_list($sql, $debug=0)
{
    // this function require presence of good_query() function
    $result = good_query($sql, $debug);
    
    if($lst = mysqli_fetch_row($result))
    {
        mysqli_free_result($result);
        return $lst;
    }
    mysqli_free_result($result);
    return false;
}



// MySQL query for returning one set of data (login, etc)
function good_query_assoc($sql, $debug=0)
{
    // this function require presence of good_query() function
    $result = good_query($sql, $debug);
    
    if($lst = mysqli_fetch_assoc($result))
    {
        mysqli_free_result($result);
        
        // strip all slashes
        foreach ($lst as $key => $cleanEl) {
				$lst[$key] = htmlentities(stripslashes($cleanEl));
			}

        return $lst;
    }
    mysqli_free_result($result);
    return false;
}



// MySQL query for getting num rows
function good_query_value($sql, $debug=0)
{
    // this function require presence of good_query_list() function
    $lst = good_query_list($sql, $debug);
    return is_array($lst)?$lst[0]:false;
}



// MySQL query for returning a table of results
function good_query_table($sql)
{
    // this function require presence of good_query() function
    $result = good_query($sql);
    
    $table = array();
    if (mysqli_num_rows($result) > 0)
    {
        $i = 0;
        while($table[$i] = mysqli_fetch_assoc($result)) 
            $i++;
        unset($table[$i]);
        
        // clean the data in the table
        foreach ($table as $key => $temp) {
        	foreach ($temp as $assoc_key => $row) {
				$temp[$assoc_key] = htmlentities(stripslashes($row));
			}
			$table[$key] = $temp;
			} 
			// done cleaning!
			
			                                                                                 
    }                                                                                                                                     
    mysqli_free_result($result);
    return $table;
}



// escape sequence
function escape($string) {
	global $dbc;
	$result = mysqli_real_escape_string($dbc, trim($string));
	return $result;
}



// generic update function
function update($table,$changes,$predicate) {
		$query = 'UPDATE '.$table;
		$query .= ' SET ';
		$numChanges = count($changes);
		for($i = 0 ; $i < $numChanges ; $i++) {
			$query .= $changes[$i];
			if($i != $numChanges - 1) {
				$query .= ' , ';
			}
		}
		if($predicate != NULL) {
			$query .= ' WHERE '.$predicate;
		}
		
		$runQ = good_query($query);
		return $runQ;
	}

?>

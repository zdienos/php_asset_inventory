<?php

// config
require_once("config.php");

if($CFG->debug){
    echo debug_dump($SITE, get_var_name($SITE));
}

// check for login
if( !$USER->logged ){
    header('Location: index.php');
}

// variable definitions
$where_sql = "";
$criteria = false;

// check for post and validate
if(!empty($_POST)){
    
    // validate POST
    if( (isset($_POST["valid"])) && (!empty($_POST["valid"])) ){
        if($_POST["valid"] !== $USER->key){
            // possible hack attempt
            // TODO handle scenario
            die("don't hack me bro");
        } else {
            $criteria = true;
        }
    } else {
        // possible hack attempt?
        // TODO handle scenario        
        die("don't hack me bro");
    }
    
}
	

if($criteria !== false){
    
    // TODO pagination page numbers
    //$page = 10 * $_REQUEST['pg'];
    $page = 0;

    // TODO pagination limit
    //$limit = $_POST['limit'];
    $limit = 10;
    
    // build where clause
    $where_conditions = "";
    $where_values = array();

    // build where array
    foreach($_POST as $key => $value){
        if( ($key !== "valid") && (!empty($value)) && ($value !== 'Select Below') ){
            $where_values[] = " assets.$key = '$value' ";
        }
    }

    // combine values
    foreach($where_values as $clause){
        if($clause === end($where_values)){
            $where_conditions .= $clause;
        } else {
            $where_conditions .= $clause." AND ";
        }
    }
    
    if(!empty($where_conditions)){
        $where_sql = " WHERE ".$where_conditions;
    }
    

    
} else {
    
    // no criteria just show last 10
    
    // TODO pagination page numbers
    //$page = 10 * $_REQUEST['pg'];
    $page = 0;

    // TODO pagination limit
    //$limit = $_POST['limit'];
    $limit = 10;
    
}





/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////


// way to vulnerable must replace with something better
if(!empty($_POST['order'])){
    $order = $_POST['order'];
} else {
    $order = "assets.id";
}

// way to vulnerable must replace with something better
if(!empty($_POST['sort'])){
    $sort = $_POST['sort'];
} else {
    $sort = "DESC";
}

// lets limit 
$limit_sql = " LIMIT $page, $limit ";


/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////





// begin building SQL
$search_sql = "SELECT ";
//$search_sql .= " * ";

/*
$search_sql .= "assets.id, ";
$search_sql .= "assets.asset_tag AS 'Tag #', ";
$search_sql .= "assets.serial_number AS 'SN', ";
$search_sql .= "assets.po_number as 'PO', ";
$search_sql .= "asset_types.type as 'Type', ";
$search_sql .= "assets.status_id, ";
$search_sql .= "assets.make_id, ";
$search_sql .= "assets.model_id, ";
$search_sql .= "assets.service_tag, ";
$search_sql .= "assets.purchase_date, ";
$search_sql .= "assets.surplus_date ";
*/

$search_sql .= "assets.id, ";
$search_sql .= "assets.asset_tag as 'Asset Tag', ";
$search_sql .= "assets.serial_number as 'S/N', ";
$search_sql .= "assets.po_number as 'PO', ";
$search_sql .= "asset_types.type as 'Type', ";
$search_sql .= "asset_statuses.status as 'Status', ";
$search_sql .= "asset_makes.make as 'Make', ";
$search_sql .= "asset_models.model as 'Model', ";
$search_sql .= "assets.service_tag as 'Service Tag', ";
$search_sql .= "assets.purchase_date as 'Purchased', ";
$search_sql .= "assets.surplus_date as 'Surplused' ";


$search_sql .= "FROM assets ";
$search_sql .= "LEFT JOIN asset_types ON assets.type_id = asset_types.id ";
$search_sql .= "LEFT JOIN asset_statuses ON assets.status_id = asset_statuses.id ";
$search_sql .= "LEFT JOIN asset_models ON assets.model_id = asset_models.id ";
$search_sql .= "LEFT JOIN asset_makes ON assets.make_id = asset_makes.id ";

$search_sql .= $where_sql;

// TODO order sql
$search_sql .= " ORDER BY $order $sort ";

// TODO limit(pagination) sql
$search_sql .= $limit_sql;

// attempt to execute the search
try {
	$stmt = $SITE->DB->query($search_sql);
	$results_count = $stmt->rowCount();
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
	trigger_error($e->getMessage());
}
/*
if($results_count < 1){
	trigger_error("Records not found problem with DB?", E_USER_ERROR);
}
*/

require_once('header.php');

echo "<!-- $search_sql -->";

if($SITE->error->has_errors()){
    // crap i guess lets show them
    echo $SITE->error->display();
} else {
    // yay no errors proceed
	if($results_count > 0){
		$table_out = generate_html_table($results,"id",FALSE);
		echo "<h3>Browsing $results_count Result(s)</h3>";
		echo $table_out;
	} else {
		echo "<h3>No results found</h3>";
		if($SITE->CFG->debug){
			echo "<p>$search_sql</p>";
		}
	}
    
}



require_once('footer.php');
?>
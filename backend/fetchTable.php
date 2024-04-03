<?php
session_start();
require_once "../include/conn.php";

//In other words, $_REQUEST is an array containing data from $_GET, $_POST, and $_COOKIE.

$request = $_REQUEST;

//create col like table in database
$col = array(
    0  => 'id',
    1  =>  'name',
    2  =>  'lastname',
    3  =>  'email',
    4  =>  'birthday',
    5  =>  'role'
);

$sql = "SELECT * FROM users WHERE role = 'user'";
$query = mysqli_query($conn, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

$sql = "SELECT * FROM users WHERE role = 'user'";

//searchi funksional
if(!empty($request['search']['value'])){
    $sql.=" AND (id Like '%".$request['search']['value']."%' ";
    $sql.=" OR lastname Like '%".$request['search']['value']."%' ";
    $sql.=" OR email Like '%".$request['search']['value']."%' ";
    $sql.=" OR birthday Like '%".$request['search']['value']."%' ";
    $sql.=" OR role Like '%".$request['search']['value']."%' ";
    $sql.=" OR name Like '%".$request['search']['value']."%' )";

}
$query = mysqli_query($conn, $sql);
$totalData = mysqli_num_rows($query);

// order by id the third time
$defaultOrderColumn = 'id';
$defaultOrderDirection = 'ASC';

// nqs parametri order is set bejme order sipas radhes se caktuar
if(isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
    $orderColumn = $col[$_REQUEST['order'][0]['column']];
    $orderDirection = $_REQUEST['order'][0]['dir'];
} else { //ne te kundert bejme order sipas id
    $orderColumn = $defaultOrderColumn;
    $orderDirection = $defaultOrderDirection;
}

//do order
$sql .= " ORDER BY " . $orderColumn . " " . $orderDirection . " LIMIT " .
    intval($_REQUEST['start']) . ", " . intval($_REQUEST['length']);

$query = mysqli_query($conn, $sql);

$data = array();

while($row = mysqli_fetch_array($query)){
    $subdata = array();
    $subdata[] = $row[0]; //id
    $subdata[] = $row[1]; //name
    $subdata[] = $row[2]; //lastname
    $subdata[] = $row[3]; //email
    $subdata[] = $row[4]; //birthday
    $subdata[] = $row[5]; //role
    $subdata[] = '<button type="button" name="update" id="'.$row[0].'" class="btn btn-w-m btn-primary btn-xm update-user-btn"><i class="fa fa-edit">&nbsp;</i>Edit</button>
                  <button type="button" name="delete" id="'.$row[0].'" class="btn btn-w-m btn-danger btn-xm delete" ><i class="fa fa-trash">&nbsp;</i>Delete</button>';
/*                  <a href="backend/delete.php?deleteid='.$row[0].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-w-m btn-danger btn-xm"><i class="fa fa-trash">&nbsp;</i>Delete</a>';*/
    $data[]=$subdata;
}


$json_data = array(
    "draw"              => intval($request['draw']),
    "recordsTotal"      => intval($totalData),
    "recordsFiltered"   => intval($totalFilter),
    "data"              => $data
);

echo json_encode($json_data);
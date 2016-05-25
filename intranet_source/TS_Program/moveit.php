<?

include("connect_data_warehouse2.php");

$conn_postgres = pg_connect("host=$host dbname=$db user=$dbuser");
if(!$conn_postgres){
  die("cannot open gremlin postgres DB");
}

$sql = "insert into fff (select * from cd1);";
$result = pg_query($conn_postgres, $sql) or die("Error with $sql:" . pg_last_error($conn_postgres));

$sql = "delete from cd1";
$result = pg_query($conn_postgres, $sql) or die("Error with $sql:" . pg_last_error($conn_postgres));


?>

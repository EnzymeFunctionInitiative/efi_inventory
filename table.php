<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.1.min.js" > </script> 
<script type="text/javascript" src="http://www.kunalbabre.com/projects/table2CSV.js" > </script>


<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
 $(document).ready(function () {
    $('.table').each(function () {
        var $table = $(this);

        var $button = $("<button type='button'>");
        $button.text("Export to spreadsheet");
        $button.insertAfter($table);

        $button.click(function () {
            var csv = $table.table2CSV({
                delivery: 'value'
            });
            window.location.href = 'data:text/csv;charset=UTF-8,' 
            + encodeURIComponent(csv);
        });
    });
 })
</script>

</head>
<body>
<div class='container'>
<br>
<a href='http://efi.igb.illinois.edu/efi-inventory/'>
<img src='http://enzymefunction.org/sites/all/themes/efi/efi2011/images/efi_logo.png'></a>
<br><br>
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
if(! isset($_GET['uniprot'])){
    echo "<h1><br>There was no input.</h1>";
die;
}
?>

<h1> Query Results </h1>
    <table border=1 id='table' class='table table-striped table-bordered table-hover'>
    <thead>
    <tr>
    <th>Accessions</th>
    <th>Organism</th>
    <th>TaxonID</th>
    <th>Organism Description</th>
    <th>GDNA</th>
    <th>Micro Strain</th>
    </tr>
    </thead>


<?php




$ids = explode (",", $_GET['uniprot']);
$strains = get_strains();
$error = "<font color='red'>No results found for your query <br> {$_GET['uniprot']}</font><br><br>";

foreach ($ids as $id){
    $row = query_uniprot($id);
    if(!isset($row)){
        continue;
    }
    else{
        $error = '';
    }
    print "<br>";
    $acc = $row[0];
    $org = $row[1];
    $taxon = $row[2];
    $gdna = $row[3];
    $description = $row[4];
    $strain = 'False';

    #print_r($strains);

    if(isset($strains[$taxon])){
        $strain = 'True';
    }
    echo 
        "<tr>
        <td>$acc</td>
        <td>$org</td>
        <td>$taxon</td>
        <td>$description</td>
        <td>$gdna</td>
        <td>$strain</td>
        </tr>";

}
print $error;


function query_uniprot($id){
    $database_location = "/home/groups/efi/efiest-0.9.3a/database_20140729/uniprot_combined.db";
    try {
        $dbh = new PDO("sqlite:$database_location");
        $sql = "SELECT accession,Species,Taxonomy_ID,GDNA,Organism FROM annotations where accession= :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_BOTH);
        if(!empty($result)){
            return $result;
        }
        else{
            return null;
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }

    #    print_r($dbh->errorInfo());

}
function get_strains(){
    $strain_location = '/home/groups/efi/check_uniprot/bacterial_strains.txt';
    $strain_location = 'taxons';
    if(! file_exists($strain_location)){
        print "Couldn't open strain file located at $strain_location";
        exit;
    }
    $data = file_get_contents("$strain_location"); //read the file

    $array = explode("\n", $data); //create array separate by new line

    $strains = array();
    foreach(array_values($array) as $taxon){
        $strains[$taxon] =  $taxon;
    }
    return $strains;


}




?>
</table>
    </div>
    <body>
    </html>

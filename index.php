<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

</head>
<body>
<div class='container'>

<form name='submit_uniprot_accessions' action='index.php'>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$content = '';
$error ='';
if(isset($_GET['uniprot']) && strlen($_GET['uniprot']) > 0 ){
    $content = $_GET['uniprot'];
    $error = parse_uniprot($content);
}


$input = "<textarea id='uniprot' name='uniprot' class='form-control' rows=10>$content</textarea>";
$button= "<button type='submit'>Submit List of IDs</button>";
$description = "<h1><p>EFI Genomic DNA and Microbial Strains Checking Tool</p></h1><br>";
$description .= "<h3><p> Please enter a list of Uniprot Accession IDS </p></h3>";


echo $description. "<br>" . $input . "<br><br>". $button . "<br><br><h2><font color='red'>$error</font></h2>" ;



function parse_uniprot($uniprot_ids){
    if(count($uniprot_ids) == 0){
        return "";
    }
    $uniprot_ids = htmlspecialchars($uniprot_ids);
    $uniprot_ids = preg_replace("/[\n\r\s ]/",",",$uniprot_ids);
    $pattern ='/[^A-Za-z0-9\,]/';
    if (preg_match_all($pattern, $uniprot_ids, $matches)) {
        $_GET['uniprot'] = $uniprot_ids;
        return "ERROR: Uniprot IDs can only contain Alphanumeric Characters <br> You entered: [$uniprot_ids]";
    } else {
        header("Location: table.php?uniprot=$uniprot_ids");
    }


}

?>
</form>
</div>
</body>
</html>

<?php 
include 'dbcon.php';
include 'includes\header.php'; 
echo ' | Index';
include 'includes\header2.php';
?>
testing <img src="<?php echo "$storage->getBucket"; ?>" width="500" height="500" alt="alt"/>

<?php 

$reference = $database->getReference('carousel');
$snapshot = $reference->getSnapshot();
$value = $snapshot->getValue();



$buckets = $storage->listBuckets("mysticstarenterprise.appspot.com");

foreach ($buckets['items'] as $bucket) {
    printf("%s\n", $bucket->getName());
}
$filename = $bucket;
echo "$filename"; 
?>

<?php
include 'includes\footer.php';
?>
<?php
include 'functions/functions.php';
backup_tables('localhost','root','','db_filing');


$host='localhost';
$user='root';
$pass='';
$name='db_filing';
function backup_tables($host,$user,$pass,$name,$tables = '*')
{

$link = mysql_connect($host,$user,$pass);
mysql_select_db($name,$link);


if($tables == '*')
{
$tables = array();
$result = mysql_query('SHOW TABLES');
while($row = mysql_fetch_row($result))
{
$tables[] = $row[0];
}
}
else
{
$tables = is_array($tables) ? $tables : explode(',',$tables);
}
foreach($tables as $table)
{
$result = mysql_query("SELECT * FROM $table");
$num_fields = mysql_num_fields($result);

$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
$return.= "\n\n".$row2[1].";\n\n";

for ($i = 0; $i < $num_fields; $i++)
{
while($row = mysql_fetch_row($result))
{
$return.= 'INSERT INTO '.$table.' VALUES(';
for($j=0; $j<$num_fields; $j++)
{
$row[$j] = addslashes($row[$j]);
if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
if ($j<($num_fields-1)) { $return.= ','; }
}
$return.= ");\n";
}
}
$return.="\n\n\n";
}


$data=date("m_d_Y");
$handle = fopen('Back-up/db_backup/'.$data.'.sql','w+');
fwrite($handle,$return);
fclose($handle);
}


// Get real path for our folder
$rootPath = realpath('upload');

// Initialize archive object
$zip = new ZipArchive();
$fname = date('m_d_Y').'.zip';
$zip->open($fname, ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();

rcopy($fname , 'Back-up/uploads/'.$fname );
rrmdir($fname);

header("location:backup_data.php");
?>
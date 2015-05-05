<?php
$dir = '/home/projects/jQuery-File-Upload/slice/uploads';

if (!isset($_SERVER['HTTP_X_FILE_NAME']))
    throw new Exception('Name required');
if (!isset($_SERVER['HTTP_X_INDEX']))
    throw new Exception('Index required');
if (!isset($_SERVER['HTTP_X_TOTAL']))
    throw new Exception('Total chunks required');

if(!preg_match('/^[0-9]+$/', $_SERVER['HTTP_X_INDEX']))
    throw new Exception('Index error');
if(!preg_match('/^[0-9]+$/', $_SERVER['HTTP_X_TOTAL']))
    throw new Exception('Total error');
 
$filename   = $_SERVER['HTTP_X_FILE_NAME'];
$filesize   = $_SERVER['HTTP_X_FILE_SIZE'];
$index      = $_SERVER['HTTP_X_INDEX'];
$total      = $_SERVER['HTTP_X_TOTAL'];

$flag = 0;
if($index>0)
	$flag = FILE_APPEND;

$target = $dir."/".$filename."-".($index-1)."-".$total;

$input = fopen("php://input", "r");
file_put_contents($target, $input, $flag);

$result = array
(
	'filename' => $filename,
	'start' => $index,
	'end' => $total,
	'percent' => intval(($index+1) * 100 / $total)
);

if($index<$total-1)
	rename($target, $dir."/".$filename."-".$index."-".$total);
else
{
	rename($target, $dir."/".$filename);
	$result['percent'] = 100;
}
echo json_encode($result);
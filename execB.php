<?php
require 'FrameMessage.class.php';

$frame = isset($_GET['frame'])? $_GET['frame'] : '';
$func = isset($_GET['func'])? $_GET['func'] : '';
$args = isset($_GET['args'])? $_GET['args'] : '';

$result = FrameMessage::execute($frame, $func, $args);

echo $result;
?>
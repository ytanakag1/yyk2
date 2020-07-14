<?php
$conn = mysqli_connect("localhost","ginzo","Hjkl344300-","yyk") ;

if (!$conn){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
/* 文字セットを utf8 に変更します */
mysqli_set_charset($conn, "utf8"); 

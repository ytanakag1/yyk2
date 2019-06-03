<?php // フィールド値のセキュリティ対策 mojifillter.php
function h(&$a ,$b=true){
    $a= htmlspecialchars($a,ENT_QUOTES);
    $a= str_replace("," , "、" , $a);
    $a= str_replace("`" , "”" , $a);
  if($b) 
    $a= str_replace("." , "｡" , $a); // mailの場合これは省く  
    $a= str_replace("\\" , "&yen;" , $a);
    $a= str_replace("/" , "\/" , $a);
    $a= nl2br($a);
    return $a;
} 
?>
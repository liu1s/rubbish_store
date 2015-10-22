<?php
foreach($apiInfo['data']['tempList'] as $key=>$row){
        $amountLimit[$key] = $row['amountLimit'];
}

array_multisort($amountLimit,SORT_ASC,$apiInfo['data']['tempList']);

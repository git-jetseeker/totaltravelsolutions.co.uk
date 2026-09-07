<?php
$p = 'd:/usman-dev_1/jetseeker.co.uk/jetseaker mainsite/resources/views/frontend/ajax_search_result_lounges.blade.php';
$c = file_get_contents($p);
$c = str_replace('{!! $company->why_bookone !!}', '{!! $whyOne !!}', $c);
$c = str_replace('{!! $company->why_booktwo !!}', '{!! $whyTwo !!}', $c);
$c = str_replace('{!! $company->why_bookthree !!}', '{!! $whyThree !!}', $c);
$c = str_replace('{!! $company->why_bookfour !!}', '{!! ($company->why_bookfour ?? \'\') !!}', $c);
file_put_contents($p, $c);
echo "modal why books patched\n";

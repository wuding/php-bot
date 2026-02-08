<?php

// PRINT_R($_SERVER);die;

// version 1.260208

$page = $_GET['page'] ?? 1;
$request_time_float = $_SERVER['REQUEST_TIME_FLOAT'];
$queryString = $_SERVER['QUERY_STRING'] ?? null;
$strpos = strpos($queryString, '&');
$url = substr($queryString, 0, $strpos);
$query = substr($queryString, $strpos + 1);

$filename = "http://127.0.0.1:17400/api/v2/robot/Apps/apple/Url/locationParse?page=$page";
$filename = "$url?$query";
$json = file_get_contents($filename);
$obj = json_decode($json);
// var_dump(get_defined_vars());die;

$value = [
  'code' => $obj->code ?? 0,
  'msg' => $obj->msg ?? null,
  'data' => $obj->data ?? [],

  'page' => [
    'no' => $page,
    'max' => 100,
  ],
  'info' => [
    'skip' => 0,
    'time' => $request_time_float,
    'http_response_header' => $http_response_header,
  ],

  'ads' => 'ads',
  'user' => [
    'id' => 0,
  ],
];
$json = json_encode($value, JSON_PRETTY_PRINT);
print_r($json);

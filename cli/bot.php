<?php

ignore_user_abort(true);
set_time_limit(0);
date_default_timezone_set('PRC');

define('ROOT', dirname(__DIR__));

global $sleep_total, $proxy_total, $proxy_err, $driver, $cd_path;

$filename = "D:\Server\http\php-app/vendor\wuding\php-ext\src\X\Redis.php";
$filename = "D:\Server\git\php-ext\src\X\Redis.php";
// $filename = "J:\git\github.com\wuding\php-ext\develop\src\X\Redis.php";
$filename = "E:\putz\Server\git\github.com\wuding\php-ext\develop\src\X\Redis.php";
extract(include 'bot-config.php');
$include = include $filename;
// var_dump($include);die;

class cURL
{
    static $ch = null;
    static $url = null;
    static $file_get_contents = null;
    static $filename = null;

    function __construct($var_array)
    {
        $count = count($var_array);
        $extract = extract($var_array);
        self::$ch = $ch = curl_init($url);
        self::$url = $url;
        $curl_setopt_array = curl_setopt_array($ch, $options);
        // print_r(get_defined_vars());

    }

    static function run()
    {
        $curl_exec = curl_exec(self::$ch);
        $curl_close = curl_close(self::$ch);
        return $curl_exec;
        // print_r(get_defined_vars());
    }
}

class Bot
{
  static function test($url, $token = null)
  {
    $var_array = [
      'url' => $url,
      'options' => [
          CURLOPT_RETURNTRANSFER => true,

          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_SSL_VERIFYHOST => false,

          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          // CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
          # CURLPROXY_SOCKS5
          // CURLOPT_PROXY => '127.0.0.1:9910',
          // CURLOPT_PROXYPORT => ,

          CURLOPT_HTTPHEADER => [
              "Authorization: token $token",
              'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',
              // 'accept: application/json',
              // 'cookie: ,
          ],
          // CURLOPT_COOKIE => '',

      ],
    ];
    $cURL = new cURL($var_array);
    return  cURL::run();
  }

  static function mem($url, $suffix = '')
  {
    $parse_url = parse_url($url, PHP_URL_PATH);
    $parse_url = preg_replace("#^/api/#", 'api:', $parse_url);
	$key = $parse_url . $suffix;
    // print_r($key);die;

    $conf = include ROOT .'/conf/develop.php';
    // print_r($conf['mem']['server']['master']);
    $redis_conf = $conf['mem']['server']['master'];
	// print_r(get_defined_vars());die;
	$db = $conf['bot']['mem']['dbindex'];
    $mem = new \Ext\X\Redis($redis_conf);
    $mem->select($db);
    return $mem->set($key, $url);
  }
  
  static function mem_get($url, $suffix = '', $val = null)
  {
    $parse_url = parse_url($url, PHP_URL_PATH);
    $parse_url = preg_replace("#^/api/#", 'api:', $parse_url);
    $key = $parse_url . $suffix;
    // print_r($key);die;

    $conf = include ROOT .'/conf/develop.php';
    // print_r($conf['mem']['server']['master']);
    $redis_conf = $conf['mem']['server']['master'];
	$db = $conf['bot']['mem']['dbindex'];
    $mem = new \Ext\X\Redis($redis_conf);
    $mem->select($db);
    return $mem->get($key);
  }
  
  static function _memory_usage()
    {
      $real = memory_get_peak_usage();
      $total = memory_get_peak_usage(true);
      $r = memory_get_peak_usage();
      $t = memory_get_peak_usage(true);
      $l = ini_get('memory_limit');
      $gc = gc_collect_cycles();
      $variable = [
        // 'memory_limit' => $l,
        'usage' => $real,
        'total' => $total,
		'gc' => $gc
      ];
      if ($r !== $real) {
        $variable['peak'] = $r;
      }
      if ($t !== $total) {
        $variable['peak_total'] = $t;
      }

		return $variable;
    }
	
	static function implode_kv($variable, $sep = ": ")
	{
		$pieces = [];
      foreach ($variable as $key => $value) {
        $pieces[] = $line = "$key$sep$value";
      }
      return $implode = implode(' ', $pieces);
	}
}

function test($var_array = [], $page = null, $max = null, $suffix = '')
{
	global $sleep_total, $proxy_total, $proxy_err, $driver, $cd_path;
	$lines = [
		'for' => null
	];
	$time = microtime(true);
	$time_this = microtime(true);
  $uri = "https://1.mov.red/api/v2/robot/Douban/movie/Subject/subjectTraversal";
  $sleep = 0;
  
  $take = 10;
  $limit = 300;
  $try = 3;
  $for = 16;
  $len = 10000;
  $check = null;
  $wait = null;
  
  $try_times = 0;
  $from_line = null;
  $takes = 0;
  $continue = null;
  $no = 0;
  
  $fast = $slow = $keep = $delay = $err = $error = $dl = $put = $size = $restart = $local = $ignore = $many = $take_min = $take_max = 0;
  $vars = $bot_inf = [];
  extract($var_array);
  extract($vars);
  $date_start = date('Y-m-d H:i:s', $time);
  // print_r([get_defined_vars(), __LINE__]);die;
  
  $host = parse_url($uri, PHP_URL_HOST);
  $port = parse_url($uri, PHP_URL_PORT);
  $scheme = parse_url($uri, PHP_URL_SCHEME);
  $parse_url = parse_url($uri, PHP_URL_QUERY);
	parse_str($parse_url, $query_arr);
	$last = $query_arr['last'] ?? null;
  $hostname = $host;
  if ($port) {
	  $hostname .= ":$port";
  }
  $ends = [
	'start' => 0,
	'end' => 0,
	'csleep' => 3,
  ];
  
  
  out("\nTRY: $try_times line $from_line");
  if (!$page) {
	  $mem_get = Bot::mem_get($uri, $suffix);
	  $parse_url = parse_url($mem_get, PHP_URL_QUERY);
	  parse_str($parse_url, $arr);
	  $parse_url = urldecode($parse_url);
	  
	  $page = $arr['page'] ?? 1;
	  out("\n $page $parse_url");
	  // $page++;
  }
  
  
  
  $next = $diff = null;
  if (!$max) {
	  $max = 10000 + $no;
	  if ($last && $last > $page) {
		  $max = $last - $page + 1;
	  }
  }
  if ($no && $no >= $max) {
	  out("$no >= $max");
	  $max += $no;
  }
  if ($try_times) {
	  
	  if ($try < $try_times) {
		  $page++;
		  $var_array['try_times'] = $try_times = 0;
	  } else {
		  $restart++;
	  }
	  
  // print_r([get_defined_vars(), __LINE__]);die;
  }
  out(" restart $restart no $no max $max page $page");
  $lines[] = __LINE__;
  
  $prev = $prev_take = $take_all = $take_count = $prev_expect = $diff_expect = $break = 0;
  for ( $i = $no; $i < $max; $i++) {
	  $ii = $i + 1;
	  $j = $ii - $no;
	  $lines['for'] = $j;
	  $download = null;
	  
	  __TRY__:
    $microtime = microtime(true);
    $url = "$uri&page=$page";
    // $url = "http://localhost:60508/example/curl/response.php?page=$page";
	$url = urldecode($url);
	if (is_numeric($check)) {
		$url .= "&check=$check";
	}
	if (is_numeric($wait)) {
		$url .= "&wait=$wait";
	}

    // $mem = Bot::mem($url);
    // fwrite(STDOUT, "$i $page $url\n");
	$date = date('Y-m-d H:i:s', $microtime);
	$url_show = preg_replace("#^http(|s)://#", '', $url);
	$precent = bcdiv($j * 100, $max, 3);
	// $precent = round($precent, 2);
	// $precent = substr($precent, 0, 3);
	$n = $no ?: '';
    out("\n $n ($i.) $date $max $precent %\n$url_show");
    $json = Bot::test($url);
    $micro = microtime(true);
	
	$diff_start = round($micro - $time);
	$run_avg = round($diff_start / $ii);
	
	$dl_use = $diff_start - $sleep_total;
	$dl_sec = $dl_use / $ii;
	$dl_sec = round($dl_sec, 2);
	$slp_total = round($sleep_total, 2);
	
    $t = $micro - $microtime;
	$t2 = $micro - $time_this;
	$avg = $t2 / $j;
	$take_all += $t;
	$take_avg = $take_all / $j;
	$take_avg = round($take_avg, 2);
	$take_total = round($take_all);
	
	$stdout = $pagecount = null;
/* 
    $obj = json_decode($json);
	$code = $obj->code ?? null;
	if (is_null($code)) {
		print_r([__LINE__, __FILE__]);
        print_r($json);
        die;
    }
 */	
	$json = trim($json);
	if (!$json) {
		goto __EXP__;
	}
	$patterns = [
		"#Cannot destroy the zip context(.*)#",
		"#<title>Error<#",
		"#Renaming temporary file failed(.*)#",
	];
	foreach ($patterns as $key => $value) {
		if (preg_match($value, $json)) {
			if (1 === $key) {
				$err++;
				// print_r([__LINE__, $json]);die;
			}
			$var_array['page'] = $page;
			$var_array['no'] = $ii;
			$var_array['time'] = $time;
			$var_array['vars'] = [
						'err' => $err,
'error' => $error,
'dl' => $dl,
'ignore' => $ignore,
'many' => $many,
'put' => $put,
'restart' => $restart,
'local' => $local,
'size' => $size,
						'slow' => $slow,
						'fast' => $fast,
						'keep' => $keep,
'delay' => $delay,
						'take_min' => $take_min,
						'take_max' => $take_max,
					];			
			$next = $var_array;
			$next['from_line'] = __LINE__ . " patterns[$key] $value";
			$json = json_encode($next);
			$line = __LINE__;
			out("\n line $line patterns[$key] $value csleep(3) \n$json\n");
			csleep(3, " line $line ");
			goto __MEM__;
		}
	}
	
    $obj = json_decode($json);
	$code = $obj->code ?? null;
	$pattern = [
		"#<title>404 Not Found#",
		"#<center>nginx#",
		"#<title>502 Bad Gateway#",
		"#<title>(404 Not Found|502 Bad Gateway)#",
	];
	if (is_null($code)) {
		$preg_match = preg_match($pattern[0], $json);
		$preg_match1 = preg_match($pattern[1], $json);
		$preg_match2 = preg_match($pattern[2], $json);
		$preg_match3 = preg_match($pattern[3], $json, $matches);
		// if (($preg_match || $preg_match2) && $preg_match1) {
		if ($preg_match1 && $preg_match3) {
			$err++;
			__EXP__:
			$reason = $matches[1] ?? null;
			$var_array['page'] = $page;
			$var_array['no'] = $ii;
			$var_array['time'] = $time;
			$var_array['vars'] = [
				'err' => $err,
'error' => $error,
'dl' => $dl,
'ignore' => $ignore,
'many' => $many,
'put' => $put,
'restart' => $restart,
'local' => $local,
'size' => $size,
				'slow' => $slow,
				'fast' => $fast,
				'keep' => $keep,
'delay' => $delay,
						'take_min' => $take_min,
						'take_max' => $take_max,
			];
			$next = $var_array;
			$next['from_line'] = __LINE__ . " preg_match $reason";
			$json = json_encode($next);
			$line = __LINE__;
			out("\n line $line preg_match $reason csleep(3) \n$json\n");
			csleep(3, " line $line ");
			goto __MEM__;
		}
		print_r([$url, __LINE__, __FILE__]);
		$l = strlen($json);
		if ($len < $l) {
			$json = substr($json, 0, $len);
		}
        print_r($json);
        die;
    }
	
	$msg = $obj->msg ?? null;
    $has = $code || $msg;
    $diff = $obj->data->diff ?? null;
	$pagecount = $obj->data->result->pagecount ?? null;
	$pagecount = $obj->data->pagecount ?? $pagecount;
	$stdout = $obj->data->result->stdout ?? null;
	$stdout = $obj->data->stdout ?? $stdout;
	$continue = $obj->data->result->continue ?? null;
	$download = $obj->data->download ?? null;
	$bot_inf = $obj->data->bot ?? [];
	$bot_chk = $bot_inf->code ?? null;
	$bot_put = $bot_inf->put ?? null;
	$bot_len = $bot_inf->strlen ?? null;
	$bot_loc = $bot_inf->filesize ?? null;
	$bot_pro = $bot_inf->proxy ?? null;
	$bot_err = null;
	$mt_rand = mt_rand(10, 60);//120
	if ($bot_chk) {
		
		if (in_array($bot_chk, [-3])) {
			$many++;
			$m = $try_times ?: 1;
			$s = $mt_rand * $m;
			$s = $sleep ? $s : 3;
			csleep($s, " CODE: $bot_chk ");
		} elseif (in_array($bot_chk, [-6])) {
			$ignore++;
		} else {
			$error++;
			$bot_err = 1;
		}
	}
	if ($bot_put) {
		$put++;
	}
	if ($bot_len) {
		$size += $bot_len;
	}
	if (!is_null($bot_loc)) {
		$local++;
	}
	if ($bot_pro) {
		if (mb_ereg("^([\d]+)(.*)", $bot_pro, $matches)) {
			list($varname, $proxy_no) = $matches;
			
			$proxy_arr = $proxy_total[$proxy_no] ?? null;
			if (!$proxy_arr) {
				$proxy_total[$proxy_no] = $proxy_err[$proxy_no] = 0;
			}
			$proxy_total[$proxy_no]++;
			if ($bot_err) {
				$proxy_err[$proxy_no]++;
			}
			// print_r([$proxy_total, $matches, __LINE__, __FILE__]);die;
		}
	}
	
	$patterns = [
		'' => "#([\"\\\]+)#",
	];
	
    if ($has) {
		// out("\n $code || $msg \n");
		if (!$code && $msg) {
			$path = parse_url($msg, PHP_URL_PATH);
			$parse_url = parse_url($msg, PHP_URL_QUERY);
			parse_str($parse_url, $arr);
			// print_r($arr);
			// print_r($var_array);
			
			$var_array['page'] = $arr['page'];
			$var_array['no'] = $ii;
			$var_array['time'] = $time;
			$var_array['vars'] = [
				'err' => $err,
'error' => $error,
'dl' => $dl,
'ignore' => $ignore,
'many' => $many,
'put' => $put,
'restart' => $restart,
'local' => $local,
'size' => $size,
				'slow' => $slow,
				'fast' => $fast,
				'keep' => $keep,
'delay' => $delay,
						'take_min' => $take_min,
						'take_max' => $take_max,
			];
			unset($arr['page']);
			$path .= "?". http_build_query($arr);
			$var_array['uri'] = "$scheme://$hostname$path";
			// print_r($var_array);
			// break;
			// $i = $max;
			$break = 1;
			$next = $var_array;
			$next['from_line'] = __LINE__ . " !code&&msg";
			$try_times = $next['try_times'] ?? 0;
			$try_times++;
			$next['try_times'] = $try_times;
			$json = json_encode($next);
			foreach ($patterns as $key => $value) {
				$json = preg_replace($value, $key, $json);
			}
			out("\n !code&&msg \n$json\n");
			goto __MEM__;
		}
		
		if ($code) {
			if ('pagecount is null' === $msg) {
				out("\n$code $msg\n");
				goto __TRY__;
			}
			if ($continue) {
				$continue = (array) $continue;
				$json = json_encode($continue);
				$continue['from_line'] = __LINE__ ." continue $json \n";
				$continue['no'] = $ii;
				$continue['time'] = $time;
				$continue['vars'] = [
					'err' => $err,
'error' => $error,
'dl' => $dl,
'ignore' => $ignore,
'many' => $many,
'put' => $put,
'restart' => $restart,
'local' => $local,
'size' => $size,
					'slow' => $slow,
					'fast' => $fast,
					'keep' => $keep,
'delay' => $delay,
						'take_min' => $take_min,
						'take_max' => $take_max,
				];
				$json = json_encode($continue);
				out("\n continue \n$json\n");
				goto __END__;
			}
		}
		print_r([$url, __LINE__, __FILE__]);
        print_r($json);
        die;
    }

	__MEM__:
    $mem = Bot::mem($url, $suffix);
    // fwrite(STDOUT, "$diff $mem\n");
	$remain = $max - $i;
	if (!is_null($last)) {
		$remain = $last - $page;
	} elseif (!is_null($pagecount)) {
		$remain = $pagecount - $page;
	}
	$remain2 = $remain * $avg;
	$remain3 = round($remain2);
	$expect = $remain3 + $micro;
	$date_expect = date('Y-m-d H:i:s', $expect);
	$date = date('Y-m-d H:i:s');
	$t = round($t, 2);
	$t2 = round($t2);
	$avg = round($avg, 2);
	$diff = round($diff, 2);
	$slow_this = null;
	if ($take < $t) {
		$takes += $t;
		$slow++;
		$slow_this = true;
		$keep = 0;
		$delay++;
	} else {
		$fast++;
		$keep++;
		$delay = 0;
	}
	
	
	if ($take < $prev_take) {
		$prev++;
		
	} else {
		$prev--;
	}
	
	if (0 > $prev) {
		$prev = 0;
	}
	
	$prev_take = $t;
	
	if ($download) {
		if ($take_min) {
			if ($t < $take_min) {
				$take_min = $t;
			}
		} else {
			$take_min = $t;
		}
		
		if ($take_max) {
			if ($t > $take_max) {
				$take_max = $t;
			}
		} else {
			$take_max = $t;
		}
		$download = round($download, 2);
		$dl++;
	}
	
	if ($prev_expect) {
		$diff_expect = round($expect - $prev_expect);
		if (0 < $diff_expect) {
			$diff_expect = "+$diff_expect";
		}
	}
	$prev_expect = $expect;
	
	
	
	
	$amount = $slow + $fast;
	$health = $fast / $amount * 100;
	$health = round($health, 2);
	$success = $put / $ii * 100;
	$success = round($success, 2);
	
	$err_total = $err + $error;
	$err_pc = $err_total / $ii * 100;
	$err_pc = round($err_pc, 2);
	
	$dl_pc = $dl / $ii * 100;
	$dl_pc = round($dl_pc, 2);
	
	$loc_pc = $local / $ii * 100;
	$loc_pc = round($loc_pc, 2);

	$p = $pagecount ? " pagecount $pagecount" : '';
	$d = $diff ? "diff $diff" : '';
    out(" PAGE:$p remain $remain restart $restart");
	out(" EXEC: [ $date ] sum $t2 avg $avg take $t $d");
	
	out(" RUN: [ $date_start ] $diff_start avg $run_avg sleep $slp_total evaluate $dl_sec");
	out(" END: [ $date_expect ] $diff_expect remain $remain3");
	
	out(" TIME: $download min $take_min max $take_max | avg $take_avg sum $take_total count $j");
	out(" SPEED: $health % slow $slow fast $fast keep $keep delay $delay overtime $takes prev $prev this $slow_this");
	
	if ($bot_inf) {
		out("\r\nBOT: ");
	}
	foreach ($bot_inf as $k => $v) {
		out(" $k $v");
	}
	
	if ($stdout) {
		$print_r = print_r($stdout, true);
		$strlen = strlen($print_r);
		if ($len < $strlen) {
			$print_r = substr($print_r, 0, $len);
		}
		out("\r\n$print_r");
	}
	
	$srv_err = $err ?"server $err" : '';
	$curl_err = $error ? "curl $error" : '';
	$too_many = $many ? "many $many" : '';
	out("\r\n DOWNLOAD: $dl $dl_pc % | ERR: $err_total $err_pc % $srv_err $curl_err $too_many");
	out(" FILE: $success % put $put size $size ignore $ignore | LOCAL: $loc_pc % total $local");
	if ($proxy_total) {
		ksort($proxy_total);
		$a = [];
		$num = $errnum = 0;
		foreach ($proxy_total as $kk => $vv) {
			$e = $proxy_err[$kk] ?? null;
			$text = "$kk=$vv";
			if ($e) {
				$text .= ":$e";
				$errnum += $e;
			}
			$a[] = $text;
			$num += $vv;
		}
		
		$pc = $num / $ii * 100;
		$pc = round($pc, 2);
		
		$epc = $errnum / $ii * 100;
		$epc = round($epc, 2);
	
		$imp = implode(' ', $a);
		out(" PROXY: $num $pc % err $errnum $epc % $imp");
	}
	
	$_memory_usage = Bot::_memory_usage();
	if (100000000 < $_memory_usage['usage']) {
		$script = <<<HEREDOC
$driver
cd $cd_path
start php bot.php -u"$uri" -p$page
exit
HEREDOC;
		$md5 = md5($uri);
		$filename = "$md5.bat";
		$file_put_contents = file_put_contents($filename, $script);
		$cmd = "$filename";
		out("\r\n $file_put_contents \r\n $cmd \r\n $script");
		exec($cmd);
		die;
	}
	$implode_kv = Bot::implode_kv($_memory_usage, ' ');
	out(" MEMORY: $implode_kv");
	unset($json, $obj, $implode_kv);
    
	
	
	
	csleep($sleep, " ");
	out("\r\n");
	if ($limit < $takes && $prev && $slow_this) {
		$var_array['page'] = $page;
		$var_array['no'] = $ii;
		$var_array['time'] = $time;
		$var_array['vars'] = [
			'err' => $err,
'error' => $error,
'dl' => $dl,
'ignore' => $ignore,
'many' => $many,
'put' => $put,
'restart' => $restart,
'local' => $local,
'size' => $size,
			'slow' => $slow,
			'fast' => $fast,
			'keep' => $keep,
'delay' => $delay,
						'take_min' => $take_min,
						'take_max' => $take_max,
		];
		$continue = $var_array;
		$continue['from_line'] = __LINE__ ." takes $takes > limit $limit";
		$continue['ends'] = [
			'start' => 0,
			'end' => $for,
			'csleep' => 60
		];
		$json = json_encode($continue);
		out("\n continue \n$json\n");
		csleep(3);
		goto __END__;
	}

	// die;
	if ($break) {
		break;
	}
    $page++;
  }
  
  if ($next) {
	  $lines[] = __LINE__;
	  // $next['from_line'] = __LINE__;
	  test($next);
  }
  
  __END__:
  if (is_array($continue)) {
	$ends = $continue['ends'];# ?? $ends
  }
  $ends = (array) $ends;
  extract($ends);
  for ($i2 = $start; $i2 < $end; $i2++) {
	  // out("for $i");
	  csleep($csleep, "for $i2 ");
  }
  
  // out("__END__");
  if (is_array($continue)) {
	  $lines[] = __LINE__;
	  continueNext($continue);
  }
  
  __OK__:
  $lines[] = __LINE__;
 out("BYE: i $i max $max page $page");
unset($var_array['page']);
/*

$var_array['no'] = $ii ?? $no;
$var_array['time'] = $time;
$var_array['vars'] = [
'err' => $err,
'error' => $error,
'dl' => $dl,
'ignore' => $ignore,
'many' => $many,
'put' => $put,
'restart' => $restart,
'local' => $local,
'size' => $size,
'slow' => $slow,
'fast' => $fast,
'keep' => $keep,
'delay' => $delay,
'take_min' => $take_min,
'take_max' => $take_max,
];
*/
 if ($page < $last) {
	 test($var_array);
 }
 print_r(get_defined_vars());
 die;
}

function continueNext($next)
{
	$next = (array) $next;
	out("continue ". $next['uri']);
	$sleep = $next['wait'] ?? 3;
	if ($sleep) {
		csleep($sleep);
	}
	// $next['from_line'] = __LINE__;
	test($next);
}

function csleep($sleep, $prefix = '', $suffix = '')
{
	global $sleep_total;
	if (!$sleep) {
		return $sleep;
	}
	
	$sec = $sleep;
	$u = 0;
	
	$pos = strpos($sleep, '.');
	if ($pos) {
		$sec = substr($sleep, 0, $pos);
		$u = (float) substr($sleep, $pos);
		// out("a$sleep b$pos c$sec d$u");
	}
 
	$us = $stdout = null;
	if ($u) {
		$us = 1000000 * $u;
		$sec += $u;
	}
	if ($sec) {
		$stdout = "SLEEP: $sec";
	}
	if ($stdout) {
		$stdout = "$prefix$stdout$suffix";
		out($stdout);
	}
	// out("\r\n \r\n");
	
	// sleep
	if (!is_null($us)) {
		usleep($us);
	}
	if ($sec) {
		sleep($sec);
	}
	
	$sleep_total += $sleep;
}

function out($expression)
{
    if ('cli' === php_sapi_name()) {
        fwrite(STDOUT, "$expression\n");
    } else {
        print_r($expression);
    }
}

// 获取命令行参数
function options($short = null, $long = null) {
    $shortopts  = "p::j::u::s::f::t::n::m::i::c::w::";
    $longopts  = array(
        "page::",
    );
    $opt = getopt($shortopts, $longopts);
    return $opt;
}

// 参数默认值和类型，支持自定义键名
function args($opts = array()) {
		// print_r([get_defined_vars(), __LINE__]);die;
    // 参数名 => 默认值不可以是 null，键名可选
    $variable = array(
        'p' => array(null, 'page'),
        'j' => array(null, 'max'),
        'u' => array(null, 'uri'),
		's' => array(0, 'sleep'),
		'f' => array(null, 'suffix'),
		
		't' => array(null, 'take'),// 连接时间限制
		'n' => array(null, 'try'),// 重试次数
		'm' => array(null, 'limit'),// 合计时间限制
		'i' => array(null, 'for'),// 延迟次数
		'c' => array(null, 'check'),// 检查次数
		'w' => array(null, 'wait'),// 稍后
    );
    $arr = array(
		'from_line' => __LINE__,
	);
    foreach ($variable as $key => $value) {
        // 仅参数名
        if (is_numeric($key)) {
            $key = $value;
            $value = null;
        }
        $k = $key;
        $v = $value;
        // 配置了键值对
        if (is_array($value)) {
            $k = ($value[1] ?? null) ?: $k;
            $v = $value[0] ?? $v;
        }
        $val = $opts[$key] ?? $v;
        $val = $opts[$k] ?? $val;
        // 整数类型
        if (!in_array($key, array('u', 's'))) {
            if (is_numeric($val)) {
                // $val = (int) $val;
            }
        }
        if (!is_null($val) && !is_array($val)) {
          $arr[$k] = $val;
        }

    }
    return $arr;
}

// test();
test(args(options()));#

/*
php bot.php -p76542 -j10000 -u"https://1.mov.red/api/v2/robot/Douban/movie/Subject/subjectTraversal"
*/

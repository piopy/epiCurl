<!-- gimmeproxy.com for docs -->
<!-- piopy's gimmeproxy  quick API -->
<?php
function set_proxy($get,$post,$cookies,$referer,$useragent,$https,
                    $anonimous,$protocol,$port,$country,$checkperiod,$notCountry,$returnIpPortOnly,$returnCurlOnly){
                      //get a customized proxy URL
  $url='https://gimmeproxy.com/api/getProxy?';
  if($returnIpPortOnly) $url=$url.'&ipPort=true';
  if($returnCurlOnly) $url=$url.'&curl=true';
  if($get) $url=$url.'&get=true';
  if($post) $url=$url.'&post=true';
  if($cookies) $url=$url.'&cookies=true';
  if($referer) $url=$url.'&referer=true';
  if($useragent) $url=$url.'&user-agent=true';
  if($https) $url=$url.'&supportsHttps=true';
  if($anonimous) $url=$url.'&anonymityLevel='.$anonimous; //0-1
  if($protocol) $url=$url.'&protocol='.$protocol; //http/socks4/socks5
  if($port) $url=$url.'&port='.$port;
  if($country) $url=$url.'&country='.$country;
  if($checkperiod) $url=$url.'&maxCheckPeriod='.$checkperiod;
  if($notCountry) $url=$url.'&notCountry='.$notCountry;

  return $url;
}

function quickproxy(){//get a random proxy URL
  return file_get_contents('https://gimmeproxy.com/api/getProxy');
}

// function myproxy($verbose=false){ //proxy i used to ask, just as example
//   $p=set_proxy(true,true,true,true,true,true,1,'http',null,'JP,RU',10000,null,true,null);
//   if($verbose) echo $p; //print request
//   return file_get_contents($p);
// }


function getip($ip_port){ //get proxy ip
  if(str_contains($ip_port,'http')) $ip_port=str_replace('http://','',$ip_port);
  if(str_contains($ip_port,'https')) $ip_port=str_replace('https://','',$ip_port);
  if(str_contains($ip_port,'socks5')) $ip_port=str_replace('socks5://','',$ip_port);
  if(str_contains($ip_port,'socks4')) $ip_port=str_replace('socks4://','',$ip_port);
  return explode(':',$ip_port)[0];
}

function getport($ip_port){ //get proxy port
  if(str_contains($ip_port,'http')) $ip_port=str_replace('http://','',$ip_port);
    if(str_contains($ip_port,'https')) $ip_port=str_replace('https://','',$ip_port);
  if(str_contains($ip_port,'socks5')) $ip_port=str_replace('socks5://','',$ip_port);
  if(str_contains($ip_port,'socks4')) $ip_port=str_replace('socks4://','',$ip_port);
  return explode(':',$ip_port)[1];
}

function getcurlfromjson($verbose=false){ //curl parameter from json
  $p=set_proxy(true,true,true,true,true,true,1,'http',null,'JP,RU',10000,null,null,null); // mod THIS
  if ($verbose) echo 'URL: '.$p.'<br>';
  $data=file_get_contents($p);
  $data=json_decode($data,true);
  foreach(array_keys($data) as $key){
    if($verbose)echo $key.' : '.$data[$key].'<br>';
  }
  return $data['curl'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// piopy's (80%) cf-bypass CURL

function proxycfcurl($url){ //gimmeproxy + mycurl (bypass some cf-protected pages)
  $proxy=getcurlfromjson();
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_PROXY, $proxy);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  //////////////////////////////////////////////
  $headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:13.0) Gecko/20100101 Firefox/13.0.1';
  $headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
  $headers[] = 'Accept-Language: ar,en;q=0.5';
  $headers[] = 'Connection: keep-alive';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/curl.cookie');
  curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/curl.cookie');
  $curl_scraped_page = curl_exec($ch);
  curl_close($ch);

  return $curl_scraped_page;
}

function cfcurl($url){ //bypass some cf-protected pages
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  //////////////////////////////////////////////
  $headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:13.0) Gecko/20100101 Firefox/13.0.1';
  $headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
  $headers[] = 'Accept-Language: ar,en;q=0.5';
  $headers[] = 'Connection: keep-alive';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/curl.cookie');
  curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/curl.cookie');
  $curl_scraped_page = curl_exec($ch);
  curl_close($ch);

  return $curl_scraped_page;
}

function cfcurldata($url,$data){ //like previous, but with data option
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  //////////////////////////////////////////////
  $headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:13.0) Gecko/20100101 Firefox/13.0.1';
  $headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
  $headers[] = 'Accept-Language: ar,en;q=0.5';
  $headers[] = 'Connection: keep-alive';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  
  curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/curl.cookie');
  curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/curl.cookie');
  $curl_scraped_page = curl_exec($ch);
  curl_close($ch);

  return $curl_scraped_page;
}

function cfcurl_effective($url){//get the visited EFFECTIVE URL

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  //////////////////////////////////////////////
  $headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:13.0) Gecko/20100101 Firefox/13.0.1';
  $headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
  $headers[] = 'Accept-Language: ar,en;q=0.5';
  $headers[] = 'Connection: keep-alive';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/curl.cookie');
  curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/curl.cookie');
  curl_exec($ch);
  $effective=curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
  curl_close($ch);

  return  $effective;
}

?>
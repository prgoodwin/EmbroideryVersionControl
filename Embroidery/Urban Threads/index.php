<?php

$di = new RecursiveDirectoryIterator('./Urban Threads/');
foreach (new RecursiveIteratorIterator($di) as $fileName => $file) {
  if(strpos($fileName, '.pes') !== false){
    $fileArray = explode('/', $fileName);
    $trimFileName = rtrim(array_pop($fileArray), ".pes");
    $currentDir = implode('/', $fileArray) . '/';
    $data = get_data("http://www.urbanthreads.com/search.aspx?qs=" . $trimFileName);
    $imagePath = substr($data, strpos($data, "productImages/thumb/"));
    $imagePath = substr($imagePath, 0, strpos($imagePath, '" alt="'));
    print $imagePath . "\n";
    save_image($imagePath, $currentDir);
  }
}

function get_data($url) {
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, $url);
  $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
  curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_AUTOREFERER, true);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

function save_image($imgPath, $localPath){
  $image = get_data("http://www.urbanthreads.com/" . $imgPath);
  $fp = fopen($localPath . substr($imgPath, strpos($imgPath, "/thumb/") + 7), 'w');
  fwrite($fp, $image);
  fclose($fp);
}
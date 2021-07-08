<?php
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    
    $ip_address = $_POST['ip'];
    $port = $_POST['port'];
    $key = $_POST['key'];
    $stram_name = $_POST['name'];
    $client_ip_address = $_POST['clientip'];
    $script_hours = $_POST['time'];
    $desync = $_POST['desync'];
    $link_code = $_POST['code'];

    if(!empty($ip_address) && !empty($port) && !empty($key) &&  !empty($stram_name) &&  !empty($client_ip_address) &&  !empty($script_hours) &&  !empty($desync) &&  !empty($link_code))
    {
        //echo json_encode(array('message'=>$ip_address));


$flussonic = $ip_address.':'.$port; // Flussonic address.
$lifetime = 3600 * $script_hours; // The link will become invalid in 3 hours.

$stream = $stram_name;//$_GET['stream']; // This script gets the stream name from a query. string (script.php?stream=bbc)

$ipaddr = $client_ip_address; // (v20.07) Set $ipaddr = 'no_check_ip' if you want to exclude IP address of client devices from checking.
$desync = $desync; // Allowed time desync between Flussonic and hosting servers in seconds.
$starttime = time() - $desync;
$endtime = $starttime + $lifetime;
$salt = bin2hex(openssl_random_pseudo_bytes(16));

$hashsrt = $stream.$ipaddr.$starttime.$endtime.$key.$salt;
$hash = sha1($hashsrt);

$token = $hash.'-'.$salt.'-'.$endtime.'-'.$starttime;
$link = $flussonic.'/'.$stream.'/'.$link_code.'?token='.$token.'&remote='.$ipaddr;

//$embed = '<iframe allowfullscreen style="width:640px; height:480px;" src="'.$link.'"></iframe>';
//$embed ='<iframe style="width:640px; height:480px;" allowfullscreen src="http://192.168.0.106/live/embed.html"></iframe>';
//echo $embed;
echo json_encode(array('message'=>$link));

    }
    else
    {
        echo json_encode(array('message'=>'Empty data [POST your correct data]'));
    }

}
else
{
    echo json_encode(array('message'=>'Flussonic API error [POST your correct data]'));
}

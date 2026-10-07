<?php
// Receives booking/enquiry forms and emails them to the agency. Edit the two lines below.
$TO   = 'info@yourdomain.com';          // where requests are delivered
$FROM = 'no-reply@yourdomain.com';      // must be an email on YOUR domain (helps deliverability)
header('Content-Type: application/json; charset=utf-8');
function out($a){ echo json_encode($a); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') out(['ok'=>false,'error'=>'Invalid request.']);
if (!empty($_POST['website'])) out(['ok'=>true,'reference'=>'JBD-OK']); // honeypot for bots
function f($k,$n=200){ return isset($_POST[$k]) && !is_array($_POST[$k]) ? trim(mb_substr(strip_tags($_POST[$k]),0,$n)) : ''; }
$name=f('customerName',120); $email=f('customerEmail',180); $phone=f('customerPhone',40);
if ($name===''||$phone===''||!filter_var($email,FILTER_VALIDATE_EMAIL)) out(['ok'=>false,'error'=>'Please fill in your name, phone and a valid email.']);
$ref='JBD-'.strtoupper(substr(bin2hex(random_bytes(4)),0,6));
$item = f('itemSelect') ?: f('itemTitle',240);
$lines=["Reference: $ref","Service: ".f('service',20),"Item: $item","Name: $name","Phone: $phone","Email: $email"];
foreach (['travelDate'=>'Date','travelers'=>'Travelers','price'=>'Unit price (BDT)'] as $k=>$l) if (f($k)!=='') $lines[]="$l: ".f($k);
foreach ($_POST as $k=>$v) if (strpos($k,'x_')===0 && !is_array($v) && trim($v)!=='') $lines[]=substr($k,2).': '.trim(mb_substr(strip_tags($v),0,200));
if (f('notes',2000)!=='') $lines[]="Notes: ".f('notes',2000);
$subject='New '.f('service',20).' request '.$ref;
$headers="From: $FROM\r\nReply-To: $email\r\nContent-Type: text/plain; charset=utf-8";
$sent=@mail($TO,'=?UTF-8?B?'.base64_encode($subject).'?=',implode("\n",$lines),$headers);
@file_put_contents(__DIR__.'/bookings-log.txt',date('c')."\n".implode("\n",$lines)."\n---\n",FILE_APPEND|LOCK_EX);
if (!$sent) out(['ok'=>false,'error'=>'We could not send your request. Please call us instead.']);
out(['ok'=>true,'reference'=>$ref]);

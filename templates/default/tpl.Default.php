<h1><?php //echo Language::hello; 
$ses = new SessionHandler('humanity_string', 'sessions');
$ses->read(1);
print_r($ses);
echo 'ko';
?></h1>
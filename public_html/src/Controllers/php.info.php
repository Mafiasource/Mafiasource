<?PHP
if(DEVELOPMENT && in_array($_SERVER['REMOTE_ADDR'], DEVELOPER_IPS))
{
    print_r(phpinfo());
    exit(0);
}
$route->headTo('not_found');

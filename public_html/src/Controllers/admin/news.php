<?PHP

use src\Business\AdminService;
use src\Business\Logic\admin\Pagination;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 3) $route->headTo('admin');

$table = new AdminService("news");
$pagination = new Pagination($table);
$news = $table->getTableRows($pagination->from, $pagination->to);

require_once __DIR__ . '/.inc.foot.php';
$twigVars['news'] = $news;
$twigVars['pagination'] = $pagination;

print_r($twig->render('/src/Views/admin/news.twig', $twigVars));

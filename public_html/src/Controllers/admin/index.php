<?PHP

require_once __DIR__ . '/.inc.head.php';

require_once __DIR__ . '/.inc.foot.php';

echo $twig->render('/src/Views/admin/index.twig', $twigVars);

<?PHP

use src\Business\MemberService;

$member = new MemberService();
$member->redirectIfLoggedOut();

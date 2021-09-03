<?PHP

if(isset($bankMoneyBefore) && isset($bankMoneyAfter))
{
    $bankVars = array('acc' => "bank", 'bankBefore' => $bankMoneyBefore, 'bankAfter' => $bankMoneyAfter);
    if($bankMoneyBefore < $bankMoneyAfter)
        $bankVars['type'] = "up"; // Plus animation
    elseif($bankMoneyBefore > $bankMoneyAfter)
        $bankVars['type'] = "down"; // Minus animation
}
if(isset($cashMoneyBefore) && isset ($cashMoneyAfter))
{
    $cashVars = array('acc' => "cash", 'cashBefore' => $cashMoneyBefore, 'cashAfter' => $cashMoneyAfter);
    if($cashMoneyBefore < $cashMoneyAfter)
        $cashVars['type'] = "up"; // Plus animation
    elseif($cashMoneyBefore > $cashMoneyAfter)
        $cashVars['type'] = "down"; // Minus animation
}

if(isset($bankVars['type']))
    echo $twig->render("/src/Views/game/js/money.animation.twig", $bankVars);
    
if(isset($cashVars['type']))
    echo $twig->render("/src/Views/game/js/money.animation.twig", $cashVars);

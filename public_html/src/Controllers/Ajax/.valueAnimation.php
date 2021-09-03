<?PHP

if(isset($element) && !empty($element))
{
    if(isset($valueBefore) && isset($valueAfter))
    {
        $valueVars = array('element' => $element, 'valueBefore' => $valueBefore, 'valueAfter' => $valueAfter);
        if($valueBefore < $valueAfter)
            $valueVars['type'] = "up"; // Plus animation
        elseif($valueBefore > $valueAfter)
            $valueVars['type'] = "down"; // Minus animation
    }
}

if(isset($valueVars['type']))
    echo $twig->render("/src/Views/game/js/value.animation.twig", $valueVars);
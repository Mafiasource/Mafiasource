<?PHP

function valueAnimation($element, $valueBefore, $valueAfter, $replaceContent = false)
{
    if(isset($element) && !empty($element))
    {
        if(isset($valueBefore) && isset($valueAfter))
        {
            $valueVars = array('element' => $element, 'valueBefore' => $valueBefore, 'valueAfter' => $valueAfter);
            if($replaceContent)
                $valueVars['replaceContent'] = $replaceContent;
            
            if($valueBefore < $valueAfter)
                $valueVars['type'] = "up"; // Plus animation
            elseif($valueBefore > $valueAfter)
                $valueVars['type'] = "down"; // Minus animation
        }
    }
    if(isset($valueVars['type']))
    {
        global $twig;
        echo $twig->render("/src/Views/game/js/value.animation.twig", $valueVars);
    }
}

function valueAnimationReplace($element, $valueBefore, $valueAfter, $replaceContent)
{
    valueAnimation($element, $valueBefore, $valueAfter, $replaceContent);
}

<?PHP

function valueAnimation($element, $valueBefore, $valueAfter)
{
    if(isset($element) && !empty($element))
    {
        if(isset($valueBefore) && isset($valueAfter))
        {
            if($valueBefore < $valueAfter)
            {
                // Plus animation
                print '
                    <script>
                    $("'.$element.'").fadeOut("fast").hide().delay(4200).fadeIn("fast").show();
                    valueUpFlash("'.$element.'Change", '.($valueAfter - $valueBefore).');
                    $("'.$element.'").html("'.number_format($valueAfter, 0, '', ',').'");
                    </script>
                ';
            }
            elseif($valueBefore > $valueAfter)
            {
                // Minus animation
                print '
                    <script>
                    $("'.$element.'").fadeOut("fast").hide().delay(4200).fadeIn("fast").show();
                    valueDownFlash("'.$element.'Change", '.($valueBefore - $valueAfter).');
                    $("'.$element.'").html("'.number_format($valueAfter, 0, '', ',').'");
                    </script>
                ';
            }
        }
    }
}

function valueAnimationReplace($element, $valueBefore, $valueAfter, $replaceContent)
{
    if(isset($element) && !empty($element))
    {
        if(isset($valueBefore) && isset($valueAfter))
        {
            if($valueBefore < $valueAfter)
            {
                // Plus animation
                print '
                    <script>
                    $("'.$element.'").fadeOut("fast").hide().delay(4200).fadeIn("fast").show();
                    valueUpFlash("'.$element.'Change", '.($valueAfter - $valueBefore).');
                    $("'.$element.'").html("'.$replaceContent.'");
                    </script>
                ';
            }
            elseif($valueBefore > $valueAfter)
            {
                // Minus animation
                print '
                    <script>
                    $("'.$element.'").fadeOut("fast").hide().delay(4200).fadeIn("fast").show();
                    valueDownFlash("'.$element.'Change", '.($valueBefore - $valueAfter).');
                    $("'.$element.'").html("'.$replaceContent.'");
                    </script>
                ';
            }
        }
    }
}

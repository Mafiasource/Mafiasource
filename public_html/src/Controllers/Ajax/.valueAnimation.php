<?PHP

if(isset($element) && !empty($element))
{
    if(isset($valueBefore) && isset($valueAfter))
    {
        if($valueBefore < $valueAfter)
        {
            // Plus animation
            ?>
            <script>
            $("<?=$element;?>").fadeOut("fast").hide().delay(4200).fadeIn("fast").show();
            valueUpFlash("<?=$element;?>Change", <?PHP print($valueAfter - $valueBefore); ?>);
            $("<?=$element;?>").html("<?PHP print "".number_format($valueAfter, 0, '', ','); ?>");
            </script>
            <?PHP
        }
        elseif($valueBefore > $valueAfter)
        {
            // Minus animation
            ?>
            <script>
            $("<?=$element;?>").fadeOut("fast").hide().delay(4200).fadeIn("fast").show();
            valueDownFlash("<?=$element;?>Change", <?PHP print($valueBefore - $valueAfter); ?>);
            $("<?=$element;?>").html("<?PHP print "".number_format($valueAfter, 0, '', ','); ?>");
            </script>
            <?PHP
        }
    }
}

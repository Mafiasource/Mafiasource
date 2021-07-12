<?PHP

if(isset($bankMoneyBefore) && isset($bankMoneyAfter))
{
    if($bankMoneyBefore < $bankMoneyAfter)
    {
        // Plus animation
        ?>
        <script>
        $("#userBank").fadeOut("fast").hide().delay(4200).fadeIn("fast").show();
        moneyUpFlash("#bankChange", <?PHP print($bankMoneyAfter - $bankMoneyBefore); ?>);
        $("#userBank").html("<?PHP print "$".number_format($bankMoneyAfter, 0, '', ','); ?>");
        </script>
        <?PHP
    }
    elseif($bankMoneyBefore > $bankMoneyAfter)
    {
        // Minus animation
        ?>
        <script>
        $("#userBank").fadeOut("fast").hide().delay(4200).fadeIn("fast").show();
        moneyDownFlash("#bankChange", <?PHP print($bankMoneyBefore - $bankMoneyAfter); ?>);
        $("#userBank").html("<?PHP print "$".number_format($bankMoneyAfter, 0, '', ','); ?>");
        </script>
        <?PHP
    }
}
if(isset($cashMoneyBefore) && isset ($cashMoneyAfter))
{
    if($cashMoneyBefore < $cashMoneyAfter)
    {
        // Plus animation
        ?>
        <script>
        $("#userCash").fadeOut("fast").hide().delay(4200).fadeIn("fast").show();
        moneyUpFlash("#cashChange", <?PHP print($cashMoneyAfter - $cashMoneyBefore); ?>);
        $("#userCash").html("<?PHP print "$".number_format($cashMoneyAfter, 0, '', ','); ?>");
        </script>
        <?PHP
    }
    elseif($cashMoneyBefore > $cashMoneyAfter)
    {
        // Minus animation
        ?>
        <script>
        $("#userCash").fadeOut("fast").hide().delay(4200).fadeIn("fast").show();
        moneyDownFlash("#cashChange", <?PHP print($cashMoneyBefore - $cashMoneyAfter); ?>);
        $("#userCash").html("<?PHP print "$".number_format($cashMoneyAfter, 0, '', ','); ?>");
        </script>
        <?PHP
    }
}

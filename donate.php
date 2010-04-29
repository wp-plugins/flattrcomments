<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

define(PAYPAL_LINK,'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GFUK8RJ4DKJQY');

define(PAYPAL_BUTTON,'<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="GFUK8RJ4DKJQY">
<input type="image" src="https://www.paypal.com/en_US/DE/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>');

?>

<div style="float:right; width: 30%;">
    <h2>Donate</h2>
    <p>There is a chance for you to give back if you like.</p>
    <p>Your first option is to donate via <a href="<?php echo PAYPAL_LINK;?>" title="Donate via PayPal" target="_blank">PayPal</a>
       by simply pressing the following button:</p>
    <div style="text-align: center"><?php echo PAYPAL_BUTTON;?></div>
    <p>Your second option is to "share a slice of your pie".
        If you don't know what a pie got to do with it or what flattr is visit
        <a href="http://www.flattr.com" title="Flattr.com" target="_blank">Flattr.com</a>
        for further details or <a href="http://www.youtube.com/watch?v=9zrMlEEWBgY" title="Flattr pie" target="_blank">see the video</a>.</p>

    <p align="center">
    <script type="text/javascript">
        var flattr_uid = "der_michael";
        var flattr_tle = "Wordpress Flattr for comments plugin";
        var flattr_dsc = "This plugin provides flattr-buttons for comments on your blog if the commentator entered his Flattr-ID.";
        var flattr_cat = "software";
        var flattr_tag = "wordpress,plugin,flattr,comment";
        var flattr_url = "http://wordpress.org/extend/plugins/flattrcomments/";
    </script>
    <script src="http://api.flattr.com/button/load.js" type="text/javascript"></script>
    </p>
    <h2>Contact</h2>
    <p>If you have a certain remark, request or simply something you want to let me know feel free to mail me at <a href="mailto:wordpress@allesblog.de?subject=Wordpress Plugin" title="wordpress@allesblog.de">wordpress@allesblog.de</a>.
</div>

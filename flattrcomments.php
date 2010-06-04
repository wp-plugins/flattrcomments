<?php
/**
 * @package FlattrComments
 * @author Michael Henke
 * @version 0.9.6.3
 */
/*
Plugin Name: FlattrComments
Plugin URI: http://wordpress.org/extend/plugins/flattrcomments/
Description: This plugin provides flattr-buttons for comments on your blog if the comment author entered a Flattr user ID. You can flattr the plugin effort <a href="http://flattr.com/thing/542/FlattrComments-Wordpress-Plugin" target="_blank">here</a>.
Version: 0.9.6.3
Author: Michael Henke
Author URI: http://www.allesblog.de
*/

DEFINE("FLATTRCOMMENTS_PLUGIN_VERSION",'0.7.3');
DEFINE("FLATTRCOMMENTS_DB_VERSION",'074');

add_action('admin_menu', 'flattrcomments_config_page');

function flattrcomments_config_page() {
         add_options_page('Flattr Comments', 'Flattr Comments', 8, basename(__FILE__), 'flattrcomments_options');
}

function flattrcomments_options() {

?>
<div class="wrap flattrcomments-wrap" style="width:auto">
    <div style="float: left; width: 69%;">
    <h2><!-- <img src="<?php echo get_bloginfo('wpurl') . '/wp-content/plugins/flattrcomments/img/flattr_button.png' ?>" alt="flattr"/>&nbsp; -->Flattr Comments Options</h2>
<?php 
    if (!function_exists(the_flattr_permalink)) {
        $url = get_bloginfo('wpurl') .'/wp-admin/plugin-install.php?tab=plugin-information&plugin=flattr&TB_iframe=true&width=640&height=840';

        echo "<div id=\"message\" class=\"updated fade\">";
        echo "<p>Flattr plugin not actived!</p>";
        echo "</div>";
        echo "<p>You need the official <a href=\"$url\" title=\"Flattr plugin\" class=\"thickbox onclick\">Flattr.com</a> plugin <strong>installed and activated</strong> for this to work!</p>";

    } ?>

<?php
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix."flattr_comments";
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        echo "<div id=\"message\" class=\"updated fade\">";
        echo "<p>Flattr Comments Database could not be created!</p>";
        echo "</div>";
    }

?>
    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Flattr Button Alignment</th>
                <td><select name="flattrcomments_align">
                        <option value="left"<?php if (get_option('flattrcomments_align')=="left") { echo ' selected'; } ?>>Left</option>
                        <option value="right"<?php if (get_option('flattrcomments_align')=="right") { echo ' selected'; } ?>>Right</option>
                    </select>
                    <br />
                    Should the comment author's Flattr button be to the left or the right of the comment text?

                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Flattr Button Compact Size</th>
                <td><input type="checkbox" name="flattrcomments_button_style"<?php if (get_option('flattrcomments_button_style')) { echo ' checked'; } ?>>
                    <br />
                    Check this box if you want compact style flattr buttons.

                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Custom Style</th>
                <td><input type="checkbox" name="flattrcomments_custom_style"<?php if (get_option('flattrcomments_custom_style')) { echo ' checked'; } ?>>
                    <br />
                    Check this box if you want to include a custom input field in your style. <a href="#how">See how</a>.

                </td>
            </tr>
        </table>

    <p class="submit">
    <input type="submit" class="button-primary" value="Save Changes" />
    <input type="reset" class="button" value="Reset" />
    </p>

    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="flattrcomments_align,flattrcomments_custom_style,flattrcomments_button_style" />
    </form>
        <hr>
    <a name="how"><h2>Custom Style</h2></a>
    <p>You have 2 options to include a custom style input field for the comment authors flattr id. No matter how you decide, you need to tick the checkbox for custom style above.</p>
    <ol>
        <li><h3>Custom PHP Function Call</h3>
            Include the following function call in your theme at the desired position.
            <code>&lt;?php add_flattr_comment_field(); ?&gt;</code>
            This will generate the same input field at this position that is usually generated below the submit button.
            You can make a theme plugin ready by modifiing the function call the following way:<br>
            <code>&lt;?php if(function_exists('add_flattr_comment_field')) { add_flattr_comment_field(); } ?&gt;</code>
        </li>
        <li><h3>Custom HTML Input Field</h3>
            If you are a more experienced theme editor you can generate custom HTML code(<i>e.g. in comments.php</i>).
            You need to include an input field with the name and ID <code>flattrID</code>.
            <h4>Example Code</h4>
            <code>
                &lt;div id="flattrIDfield" style="display: block; clear: both; width: 100%"&gt;<br>
                &nbsp;&nbsp;&nbsp;&lt;p&gt;<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;input type="text" name="flattrID" id="flattrID" size="22" tabindex="3" /&gt;<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;label for="flattrID"&gt;&lt;small&gt;Your Flattr ID&lt;/small&gt;&lt;/label&gt;<br>
                &nbsp;&nbsp;&nbsp;&lt;/p&gt;<br>
                &lt;/div&gt;
            </code>
            
        </li>
    </ol>
    </div>
        <?php require_once 'donate.php'; ?>

</div>
<div style="clear:both;">
<p>Debug: <?php get_option('flattrcomments_db_version'); ?>
</p>
</div>
<?php
}

function save_flattr_id_for_comment_with_id ($theID) {

    $flattrID = $_POST['flattrID'];

    if (trim($flattrID) != "") {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $table_name = $prefix."flattr_comments";

        $comment = get_comment($theID);
        $commentator = $comment->comment_author;

        $insert = "INSERT INTO " . $table_name .
        " (commentatorid, flattrid) " .
        "VALUES ('" . md5($commentator) . "','" . $wpdb->escape($flattrID) . "')";

        $results = $wpdb->query( $insert );

        // I think it is more efficient to query the database for an update rather
        // than for an "expensive" select with an additional update just in case
        $update = "UPDATE $table_name ".
        "SET flattrid = '". $wpdb->escape($flattrID) ."'".
        "WHERE commentatorid = '". md5($commentator) ."';";

        $results = $wpdb->query( $update );

        setcookie( "flattrID_cookie", $flattrID, time() + 3600, '/' );
    }
}

# add_action( $tag, $function_to_add, $priority, $accepted_args );
add_action( "comment_post", "save_flattr_id_for_comment_with_id", 10, 1);

function setup_database() {
    global $wpdb;

    $prefix = $wpdb->prefix;

    $table_name = $prefix."flattr_comments";

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE " . $table_name . " (
	  commentatorid VARCHAR(32) NOT NULL,
	  flattrid VARCHAR(255) NOT NULL,
	  UNIQUE KEY commentatorid (commentatorid)
	);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

    }

    upgrade_DB($table_name);

    add_option('flattrcomments_align', "left");
    add_option('flattrcomments_db_version', FLATTRCOMMENTS_DB_VERSION);
}

register_activation_hook(__FILE__,'setup_database');

function add_flattr_comment_field () {
    global $wpdb;
    global $comment_author;
    global $current_user;
    get_currentuserinfo();
    $comment_author = $current_user->user_login;
    
    $prefix = $wpdb->prefix;
    $table_name = $prefix."flattr_comments";
    $sql = "SELECT flattrid from $table_name where commentatorid = '". md5($comment_author)."' LIMIT 1;";
    $comment_author_flattr_id = $wpdb->get_var($sql);

    if (isset($_COOKIE['flattrID_cookie'])) {
        $comment_author_flattr_id = $_COOKIE['flattrID_cookie'] ;
    }

    if ($comment_author_flattr_id != "") {
        $value = ' value="'.esc_attr($comment_author_flattr_id).'"';
    }
?>
    <div id="flattrIDfield" style="display: block; clear: both; width: 100%">
        <p><input type="text" name="flattrID" id="flattrID" class="formfield"<?php echo $value;?> size="22" tabindex="3" />
        <label for="flattrID"><small>Your Flattr ID</small></label></p>
    </div>
<?php
}

if (!get_option("flattrcomments_custom_style")) {
    add_action( "comment_form", "add_flattr_comment_field");
}

$flattrcomments_button_class = 1;

function add_flattr_button($text) {

    global $wpdb, $flattrcomments_button_class;

    include_once 'http_build_url.php';
    
    $comment_author = get_comment_author();
    $prefix = $wpdb->prefix;
    $table_name = $prefix."flattr_comments";
    $md5 = md5($comment_author);
    $sql = "SELECT flattrid from $table_name where commentatorid = '". $md5."' LIMIT 1;";
    $comment_author_flattr_id = $wpdb->get_var($sql);

    if ($comment_author_flattr_id != "" && !is_admin()) {
    
        $cat = "text";
        $url = http_build_url(get_comment_link(),
                array(
                    "query" => "comment_author_hash=$md5&comment_num=".get_comment_id(),
                ),
                HTTP_URL_JOIN_QUERY
               );

        $align = get_option('flattrcomments_align');
        
        $text = "<div>
                 <div class=\"flattrcomments_button_class\" id=\"flattrcomments_button_id-".$flattrcomments_button_class++."\" style=\"float: $align;\">".
                 flattr_permalink($comment_author_flattr_id, $cat, get_bloginfo('name')." &laquo; ".$comment_author. " (#".get_comment_id().")", $text, 'blog,wordpress,comment,plugin,flattr', $url, get_option('flattr_lng')).
                "</div>
                 <div><p>$text</p></div>
                 <div style=\"clear:both;\"></div>
                 </div>";

    }

    return $text;
}

add_filter( "comment_text", "add_flattr_button");

function upgrade_DB($table_name) {
    
    if (get_option('flattrcomments_db_version') < FLATTRCOMMENTS_DB_VERSION ) {

        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


        $sql = "DROP TABLE '" . $table_name . "'";

        dbDelta($sql);


        $sql = "CREATE TABLE " . $table_name . " (
	  commentatorid VARCHAR(32) NOT NULL,
	  flattrid VARCHAR(255) NOT NULL,
	  UNIQUE KEY commentatorid (commentatorid)
	);";

        dbDelta($sql);
        update_option('flattrcomments_db_version', FLATTRCOMMENTS_DB_VERSION);

    }
}

if (!function_exists("md5")) {

    function md5($i) {
        return $i;
    }
}

function flattr_permalink ($userID, $category, $title, $description, $tags, $url, $language) {

    if (!defined('Flattr::API_SCRIPT')) {
        return "";
    }
    
    $cleaner = create_function('$expression', "return trim(preg_replace('~\r\n|\r|\n~', ' ', addslashes(\$expression)));");

    $s = 0;
    $smiley[$s++] = "/[:8;]-?[\)xpXP|\(D\?]/";
    $smiley[$s++] = "/:\w+:/";
    
    $description = preg_replace($smiley, "", $description);
    $description = substr($description, 0, @strpos($description, ' ', 512));

    $output = "<script type=\"text/javascript\">\n";
    if ( defined('Flattr::VERSION') ) { $output .= "var flattr_wp_ver = '" . Flattr::VERSION  . "';\n"; }
    $output .= "var flattr_uid = '" . $cleaner($userID)      . "';\n";
    $output .= "var flattr_url = '" . $cleaner($url)         . "';\n";
    $output .= "var flattr_lng = '" . $cleaner($language)    . "';\n";
    $output .= "var flattr_cat = '" . $cleaner($category)    . "';\n";
    if($tags) { $output .= "var flattr_tag = '". $cleaner($tags) ."';\n"; }
    if (get_option('flattrcomments_button_style', false)) { $output .= "var flattr_btn = 'compact';\n"; } else { $output .= "var flattr_btn = 'large';\n"; }
    $output .= "var flattr_tle = '". $cleaner($title) ."';\n";
    $output .= "var flattr_dsc = '". $cleaner($description) ."';\n";
    $output .= "</script>\n";
    $output .= '<script src="' . Flattr::API_SCRIPT . '" type="text/javascript"></script>';

    return $output;

}
?>

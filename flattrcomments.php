<?php
/**
 * @package FlattrComments
 * @author Michael Henke
 * @version 0.7
 */
/*
Plugin Name: FlattrComments
Plugin URI: http://wordpress.org/extend/plugins/flattrcomments/
Description: This plugin provides flattr-buttons for comments on your blog if the comment author entered his Flattr user ID.
Version: 0.7
Author: Michael Henke
Author URI: http://www.allesblog.de
*/

DEFINE("FLATTRCOMMENTS_PLUGIN_VERSION",'0.7');
DEFINE("FLATTRCOMMENTS_DB_VERSION",'0.6');

add_action('admin_menu', 'flattrcomments_config_page');

function flattrcomments_config_page() {
         add_options_page('Flattr Comments', 'Flattr Comments', 8, basename(__FILE__), 'flattrcomments_options');
}

function flattrcomments_options() {

?>
<div class="wrap flattrcomments-wrap" style="width:auto">
    <div style="float: left; width: 69%;">
    <h2><img src="<?php echo get_bloginfo('wpurl') . '/wp-content/plugins/flattrcomments/img/flattr_button.png' ?>" alt="flattr"/>&nbsp;Flattr Comments Options</h2>
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
        </table>

    <p class="submit">
    <input type="submit" class="button-primary" value="Save Changes" />
    <input type="reset" class="button" value="Reset" />
    </p>

    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="flattrcomments_align" />
    </form>
    </div>
        <?php require_once 'donate.php'; ?>

</div>
<?php
}

function save_flattr_id_for_comment_with_id ($theID) {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix."flattr_comments";

    $comment = get_comment($theID);
    $commentator = $comment->comment_author;

    $flattrID = $_POST['flattrID'];

    $insert = "INSERT INTO " . $table_name .
    " (commentatorid, flattrid) " .
    "VALUES ('" . $commentator . "','" . $wpdb->escape($flattrID) . "')";

    $results = $wpdb->query( $insert );

    // I think it is more efficient to query the database for an update rather
    // than for an "expensive" select with an additional update just in case
    $update = "UPDATE $table_name ".
    "SET flattrid = '". $wpdb->escape($flattrID) ."'".
    "WHERE commentatorid = '$commentator';";

    $results = $wpdb->query( $update );
    
}

# add_action( $tag, $function_to_add, $priority, $accepted_args );
add_action( "comment_post", "save_flattr_id_for_comment_with_id", 10, 1);

function setup_database() {
    global $wpdb;

    $prefix = $wpdb->prefix;

    $table_name = $prefix."flattr_comments";

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE " . $table_name . " (
	  commentatorid VARCHAR(255) NOT NULL,
	  flattrid VARCHAR(255) NOT NULL,
	  UNIQUE KEY commentatorid (commentatorid)
	);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

    }

    add_option('flattrcomments_align', "left");
    add_option('flattrcomments_db_version', FLATTRCOMMENTS_DB_VERSION);
}

register_activation_hook(__FILE__,'setup_database');

function add_comment_field ($param) {
    global $wpdb;

    $comment_author = get_comment_author();
    #echo $comment_author;
    $prefix = $wpdb->prefix;
    $table_name = $prefix."flattr_comments";
    $sql = "SELECT flattrid from $table_name where commentatorid LIKE '$comment_author' LIMIT 1;";
    $comment_author_flattr_id = $wpdb->get_var($sql);
    
?>
    <div id="flattrIDfield" style="display: block; clear: both; width: 100%">
        <p><input type="text" name="flattrID" id="flattrID" value="<?php echo esc_attr($comment_author_flattr_id); ?>" size="22" tabindex="3" />
        <label for="flattrID"><small>Your Flattr ID</small></label></p>
    </div>
<?php
}
add_action( "comment_form", "add_comment_field");

function add_flattr_button($text) {
    global $wpdb;
    
    $comment_author = get_comment_author();
    $prefix = $wpdb->prefix;
    $table_name = $prefix."flattr_comments";
    $sql = "SELECT flattrid from $table_name where commentatorid LIKE '$comment_author' LIMIT 1;";
    $comment_author_flattr_id = $wpdb->get_var($sql);

    if ($comment_author_flattr_id != "" && !is_admin()) {
    
        $cat = "text";
        $url = get_comment_link();

        $align = get_option('flattrcomments_align');
    ?>
        <div>
        <div style="float: <?php echo $align ?>;">
    <?php echo flattr_permalink($comment_author_flattr_id, $cat, get_bloginfo('name'), $text, 'blog,wordpress,comment,plugin,flattr', $url, get_option('flattr_lng')); ?>
        </div>
        <div><p><?php echo $text; ?></p></div>
    <div style="clear:both;"></div>
        </div>
    <?php
    } else {
        echo "<p>".$text."</p>";
    }
}

add_action( "comment_text", "add_flattr_button");
?>

<?php
/**
 * Author:      Meyer Auslander
 * Descrirtion: Create a menu in the settings section of the admin for the user to enter toc settings
 */

defined( 'ABSPATH' ) or die( "Cannot access pages directly." ); //protect from direct access

class maus_toc_info_manager {
    public function __construct() { 
        add_action( "admin_menu", array($this,"settings_link" )); // Register the menu in WP admin
        add_action( 'admin_post_update_settings', array($this,'save_settings' )); //save the toc settings
    }

    public function settings_link() {
        add_submenu_page( "options-general.php",  // Which menu parent
            "Table of Contetns",            // Page title
            "Toc Information Manager",            // Menu title
            "manage_options",       // Minimum capability (manage_options is an easy way to target administrators)
            "maus-toc-manager",            // Menu slug
            array($this,"adminUI")     // Callback that prints the markup
        );
    }

    // Print the markup for the TOC manager
    public function adminUI() {
        if ( ! current_user_can( "manage_options" ) ) {
            wp_die( __( "You do not have sufficient permissions to access this page." ) );
        }
        
        $first_update   = get_option('maus_toc_updated');  //determine if this is the first update
        $tags_1         = get_option('maus_toc_html_tags_1');
        $tags_2         = get_option('maus_toc_html_tags_2');
        $classes_1      = get_option('maus_toc_classes_1');
        $classes_2      = get_option('maus_toc_classes_2');
        $remove_class   = get_option('maus_toc_delete');
        
        
        if ( empty($first_update) ) { //set defaults if the admin did not yet make any updates
            $tags_1         = "h1";
            $tags_2         = "h2, h3, h4, strong";
            $classes        = "toc_heading_1, toc1";   
            $classes_2    = "toc_heading_2, toc2";
            $remove_class   = "toc_delete";
        }
        
        //Show the resluts of the users request:  settings update
        //show a success message after settings were saved
        if (  $_GET['status'] == 'success' && $_GET['request']=='htmltags1') {
            ?>
            <div id="message" class="updated notice is-dismissible">
                <p><?php _e( "HTML tag settings for headlines updated to '$tags_1'.", "maus_toc" ); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( "Dismiss this notice.", "maus-toc" ); ?></span>
                </button>
            </div>
            <?php
        } elseif ( $_GET['status'] == 'error' && $_GET['request']=='htmltags1' ) {
            ?>
            <div id="message" class="updated  error notice is-dismissible">
                <p><?php _e( "Couldn't update tags. "); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( "Dismiss this notice.", "maus-toc" ); ?></span>
                </button>
            </div>
            <?php
        }  elseif ( $_GET['status'] == 'success' && $_GET['request']=='classes_1' ){
            ?>
            <div id="message" class="updated notice is-dismissible">
                <p><?php _e( "Class name settings updated to $classes_1", "maus-toc" ); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( "Dismiss this notice.", "maus-toc" ); ?></span>
                </button>
            </div>
            <?php
        }  elseif ( $_GET['status'] == 'error' && $_GET['request']=='classes_1' ){
            ?>
            <div id="message" class="updated error notice is-dismissible">
                <p><?php _e( "Error: Couldn't update classes.", "maus-toc" ); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( "Dismiss this notice.", "maus-toc" ); ?></span>
                </button>
            </div>
            <?php
        }   elseif ( $_GET['status'] == 'success' && $_GET['request']=='subclasses' ){
            ?>
            <div id="message" class="updated notice is-dismissible">
                <p><?php _e( "Sub Class name settings updated to $classes_2", "maus-toc" ); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( "Dismiss this notice.", "maus-toc" ); ?></span>
                </button>
            </div>
            <?php
        }  elseif ( $_GET['status'] == 'error' && $_GET['request']=='subclasses' ){
            ?>
            <div id="message" class="updated error notice is-dismissible">
                <p><?php _e( "Error: Couldn't update sub classes.", "maus-toc" ); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( "Dismiss this notice.", "maus-toc" ); ?></span>
                </button>
            </div>
            <?php
        }
        ?>
<!--
        forms to update htmls, classes, and subclasses settings
        default WP classes were used for ease
-->
<!--        html tags-->
        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">

            <input type="hidden" name="action" value="update_settings"/>

            <h3><?php _e( "HTML tags to indicate headings", "maus-toc" ); ?></h3>
            <p>
                <label><?php _e( "List tag names separated by a comma", "maus-toc" ); ?></label>
                <input class="regular-text" type="text" name="html_tags_1" value="<?php echo $tags_1; ?>"/>
            </p>
            <p>
                <?php
                    $tags_1 = ( $tags_1 != '' ) ? $tags_1 : "No tags have been specified.";  
                    _e( "Current Tag Settings are: $tags_1", "maus-toc" );
                ?>
            </p>

            <input class="button button-primary" type="submit" value="<?php _e( "Save", "maus-toc" ); ?>"/>
        </form><br>
<!--        classes-->
        
        <?php
    } //end of adminUI function

    //seve the entered information and vailidate it through the My zmanim API
    public function save_settings() {
        $first_update = get_option('maus_toc_updated');
        // Get the tag settings to update
        $tags_1 = ( ! empty( $_POST["html_tags_1"] ) ) ? $_POST["html_tags_1"] : null;
        
        // Update the html tag settings in the database
        update_option( "maus_toc_html_tags_1", $tags_1, true );
    
        if ( ! ($first_update = get_option('maus_toc_updated'))) update_option( "maus_toc_updated", true, true );
        // Redirect back to settings page
        $redirect_url = get_bloginfo( "url" ) . "/wp-admin/options-general.php?page=maus-toc-manager&request=htmltags1&status=success";
        header( "Location: " . $redirect_url );
        exit;
    } //end of function save tag settings
} //end of maus_toc_info_manager

$tm = new maus_toc_info_manager;
unset( $tm );

?>
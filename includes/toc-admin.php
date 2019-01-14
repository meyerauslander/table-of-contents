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
        
        $first_update       = get_option('maus_toc_updated');  //determine if this is the first update
        $tags_1             = get_option('maus_toc_html_tags_1');
        $tags_2             = get_option('maus_toc_html_tags_2');
        $classes_1          = get_option('maus_toc_classes_1');
        $classes_2          = get_option('maus_toc_classes_2');
        $remove_class       = get_option('maus_toc_delete');  //currently inopperable
        $highlight_color    = get_option('maus_toc_highlight_color');
        $highlight_font_size= get_option('maus_toc_highlight_font_size');
        $highlight_offset   = get_option('maus_toc_highlight_offset');
        
        if ( empty($first_update) ) { //set defaults if the admin did not yet make any updates
            $tags_1                 = "h1";
            $tags_2                 = "h2, h3, h4, strong";
            $classes_1              = "toc_heading_1, toc1";   
            $classes_2              = "toc_heading_2, toc2";
            $remove_class           = "toc_delete";
            $highlight_offset       = 0; 
        }
        //always set these 2 defaults
        $highlight_color        = ($highlight_color != '') ? $highlight_color : get_background_color();
        $highlight_font_size    = ($highlight_font_size != '') ? $highlight_font_size : '16';
        $highlight_offset       = ($highlight_offset != ''|| $highlight_offset == 0) ? $highlight_offset : '420';
        
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
                <p><?php _e( "Couldn't update heading tags. "); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( "Dismiss this notice.", "maus-toc" ); ?></span>
                </button>
            </div>
            <?php
        } elseif (  $_GET['status'] == 'success' && $_GET['request']=='htmltags2') {
            ?>
            <div id="message" class="updated notice is-dismissible">
                <p><?php _e( "HTML tag settings for sub-seadlines updated to '$tags_2'.", "maus_toc" ); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( "Dismiss this notice.", "maus-toc" ); ?></span>
                </button>
            </div>
            <?php
        } elseif ( $_GET['status'] == 'error' && $_GET['request']=='htmltags2' ) {
            ?>
            <div id="message" class="updated  error notice is-dismissible">
                <p><?php _e( "Couldn't update sub-seading tags. "); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( "Dismiss this notice.", "maus-toc" ); ?></span>
                </button>
            </div>
            <?php    
        }  elseif ( $_GET['status'] == 'success' && $_GET['request']=='classes_1' ){
            ?>
            <div id="message" class="updated notice is-dismissible">
                <p><?php _e( "Heading class name settings updated to '$classes_1'.", "maus-toc" ); ?></p>
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
        }   elseif ( $_GET['status'] == 'success' && $_GET['request']=='classes_2' ){
            ?>
            <div id="message" class="updated notice is-dismissible">
                <p><?php _e( "Sub-heading class name settings updated to '$classes_2'.", "maus-toc" ); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( "Dismiss this notice.", "maus-toc" ); ?></span>
                </button>
            </div>
            <?php
        }  elseif ( $_GET['status'] == 'error' && $_GET['request']=='classes_2' ){
            ?>
            <div id="message" class="updated error notice is-dismissible">
                <p><?php _e( "Error: Couldn't update sub classes.", "maus-toc" ); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( "Dismiss this notice.", "maus-toc" ); ?></span>
                </button>
            </div>
            <?php
        }   elseif ( $_GET['status'] == 'success' && $_GET['request']=='highlight_link' ){
            ?>
            <div id="message" class="updated notice is-dismissible">
                <p><?php _e( "Highlighted link Information updated:  Color is '$highlight_color'.  Font size is '$highlight_font_size' <br>
                                Offset is '$highlight_offset'.", "maus-toc" ); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text"><?php _e( "Dismiss this notice.", "maus-toc" ); ?></span>
                </button>
            </div>
            <?php
        }  elseif ( $_GET['status'] == 'error' && $_GET['request']=='classes_2' ){
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
        form for updating font size and font color for highlights
        default WP classes were used for ease
-->
<!--        html tags-->
<!--       for headings-->
        <table> <tr><td>
        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
            <input type="hidden" name="action" value="update_settings"/>
            <input type="hidden" name="setting_type" value="html1"/>
             <h3><?php _e( "HTML tags to indicate headings", "maus-toc" ); ?></h3>
            <p>
                <label><?php _e( "List tag names separated by a comma<br>
                                  (ex. h1, h2)", "maus-toc" ); ?></label>
                <input class="regular-text" type="text" name="html_tags_1" value="<?php echo $tags_1; ?>"/>
            </p>
            <p>
                <?php
                    $tags_1 = ( $tags_1 != '' ) ? $tags_1 : "No tags have been specified.";  
                    _e( "Current tag settings are: <span style='color: green; font-size: 16px'>$tags_1</span>", "maus-toc" );
                ?>
            </p>
            <input class="button button-primary" type="submit" value="<?php _e( "Save HTML Heading Tags", "maus-toc" ); ?>"/>
            <a href="<?php echo get_bloginfo( "url" ); ?>">Visit Site</a>
        </form><br><br>
<!--        for subheadings-->
        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
            <input type="hidden" name="action" value="update_settings"/>
            <input type="hidden" name="setting_type" value="html2"/>
             <h3><?php _e( "HTML tags to indicate sub-headings (indented)", "maus-toc" ); ?></h3>
            <p>
                <label><?php _e( "List tag names separated by a comma<br>
                                  (ex. h2, h3, h4, strong)", "maus-toc" ); ?></label>
                <input class="regular-text" type="text" name="html_tags_2" value="<?php echo $tags_2 ?>"/>
            </p>
            <p>
                <?php
                    $tags_2 = ( $tags_2 != '' ) ? $tags_2 : "No tags have been specified.";  
                    _e( "Current tag settings are: <span style='color: green; font-size: 16px'>$tags_2</span>", "maus-toc" );
                ?>
            </p>
            <input class="button button-primary" type="submit" value="<?php _e( "Save HTML Sub-heading Tags", "maus-toc" ); ?>"/>
            <a href="<?php echo get_bloginfo( "url" ); ?>">Visit Site</a>
        </form><br><br>
<!--           put classes in the next column-->
            </td><td> 
<!--        classes--heading-->
        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
            <input type="hidden" name="action" value="update_settings"/>
            <input type="hidden" name="setting_type" value="classes1"/>
             <h3><?php _e( "Class names to indicate headings", "maus-toc" ); ?></h3>
            <p>
                <label><?php _e( "List class names separated by a comma<br>
                                  (ex. toc1, toc)", "maus-toc" ); ?></label>
                <input class="regular-text" type="text" name="classes_1" value="<?php echo $classes_1; ?>"/>
            </p>
            <p>
                <?php
                    $classes_1 = ( $classes_1 != '' ) ? $classes_1 : "No class names have been specified.";  
                    _e( "Current class name settings for headings are: <span style='color: green; font-size: 16px'>$classes_1</span>", "maus-toc" );
                ?>
            </p>
            <input class="button button-primary" type="submit" value="<?php _e( "Save TOC Class 1 Names", "maus-toc" ); ?>"/>
            <a href="<?php echo get_bloginfo( "url" ); ?>">Visit Site</a>
        </form><br><br>
        
        <!--        classes--sub-heading-->
        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
            <input type="hidden" name="action" value="update_settings"/>
            <input type="hidden" name="setting_type" value="classes2"/>
             <h3><?php _e( "Class names to indicate sub-headings (indented)", "maus-toc" ); ?></h3>
            <p>
                <label><?php _e( "List sub-heading class names separated by a comma<br>
                                  (ex. toc2, toc_indent)", "maus-toc" ); ?></label>
                <input class="regular-text" type="text" name="classes_2" value="<?php echo $classes_2; ?>"/>
            </p>
            <p>
                <?php
                    $classes_2 = ( $classes_2 != '' ) ? $classes_2 : "No class names have been specified.";  
                    _e( "Current class name settings for sub-headings are: <span style='color: green; font-size: 16px'>$classes_2</span>", "maus-toc" );
                ?>
            </p>
            <input class="button button-primary" type="submit" value="<?php _e( "Save TOC Class 2 Names", "maus-toc" ); ?>"/>
            <a href="<?php echo get_bloginfo( "url" ); ?>">Visit Site</a>
        </form><br>
<!--           end of table of tags and classes-->
            </td></tr>
</table> 
<!--       table for link-highlight settings-->
        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
        <input type="hidden" name="action" value="update_settings"/>
        <input type="hidden" name="setting_type" value="link_highlights"/>
        <h3><?php _e( "Highlighted Links Information", "maus-toc" ); ?></h3>
        <table><tr>
        <td style="valign='top'; halign='center'">
<!--             color-->
            <p>
                <label><?php _e( "Color: <br>
                                  (ex. blue or 4a2cfa or yellowgreen)", "maus-toc" ); ?></label>
                <input size='25' type="text" name="highlight_color" value="<?php echo $highlight_color; ?>"/>
            </p>
            <p>
                <?php
                    $highlight_color = ( $highlight_color != '' ) ? $highlight_color : "No color has been specified.";  
                    _e( "Current color: <span style='color: green; font-size: 16px'>$highlight_color</span>", "maus-toc" );
                ?>
            </p>
<!--            font size-->
            <p>
                <label><?php _e( "Font size: <br>
                                  (ex. 16)", "maus-toc" ); ?></label>
                <input  type="text" size="5" name="highlight_font_size" value="<?php echo $highlight_font_size; ?>"/>
            </p>
            <p>
                <?php
                    $highlight_font_size = ( $highlight_font_size != '' ) ? $highlight_font_size : "No font size has been specified.";  
                    _e( "Current font size: <span style='color: green; font-size: 16px'>$highlight_font_size</span>", "maus-toc" );
                ?>
            </p>
            <input class="button button-primary" type="submit" value="<?php _e( "Save Highlighted Link Info", "maus-toc" ); ?>"/>
            <a href="<?php echo get_bloginfo( "url" ); ?>">Visit Site</a>
<!--            end for link-highlights table-->
        </td>
<!--        highlights offset-->
        <td valign="top">
            <p>
                <label><?php _e( "Offset: <br>
                                  (ex. 183 or 420)", "maus-toc" ); ?></label>
                <input size="6" type="text" name="highlight_offset" value="<?php echo $highlight_offset; ?>"/>
            </p>        
            <p>
                <?php
                    $highlight_offset = ( $highlight_offset != '' ) ? $highlight_offset : "No offset has been specified.";  
                    _e( "Current offset: <span style='color: green; font-size: 16px'>$highlight_offset</span>", "maus-toc" );
                ?>
            </p>
        </td>
        </tr></table>
        </form>
        <?php  
    } //end of adminUI function

    //seve the entered information and vailidate it through the My zmanim API
    public function save_settings() {
        $type=$_POST["setting_type"];
        $first_update = get_option('maus_toc_updated');
        // Get the tag settings to update
        switch($type) {
            case 'html1';
                $update = ( ! empty( $_POST["html_tags_1"] ) ) ? $_POST["html_tags_1"] : null;
                // Update the html heading tag settings in the database
                $update = str_replace(' ', '', $update); //trim out whitespaces
                update_option( "maus_toc_html_tags_1", $update, true );
                $status_url="request=htmltags1&status=success";
            break;
            case 'html2';
                $update = ( ! empty( $_POST["html_tags_2"] ) ) ? $_POST["html_tags_2"] : null;
                // Update the html sub-heading tag settings in the database
                $update = str_replace(' ', '', $update); //trim out whitespaces
                update_option( "maus_toc_html_tags_2", $update, true );
                $status_url="request=htmltags2&status=success";
            break;     
            case 'classes1';
                $update = ( ! empty( $_POST["classes_1"] ) ) ? $_POST["classes_1"] : null;
                // Update the headline class settings in the database
                $update = str_replace(' ', '', $update); //trim out whitespaces
                update_option( "maus_toc_classes_1", $update, true );
                $status_url="request=classes_1&status=success";
            break;

            case 'classes2';
                $update = ( ! empty( $_POST["classes_2"] ) ) ? $_POST["classes_2"] : null;
                // Update the subheadline class settings in the database
                $update = str_replace(' ', '', $update); //trim out whitespaces
                update_option( "maus_toc_classes_2", $update, true );
                $status_url="request=classes_2&status=success";
            break;

            case 'link_highlights';
                $update = ( ! empty( $_POST["highlight_color"] ) ) ? $_POST["highlight_color"] : null;
                // Update the subheadline class settings in the database
                $update = str_replace(' ', '', $update); //trim out whitespaces
                update_option( "maus_toc_highlight_color", $update, true );
                
                $update = ( ! empty( $_POST["highlight_font_size"] ) ) ? $_POST["highlight_font_size"] : null;
                // Update the subheadline class settings in the database
                $update = str_replace(' ', '', $update); //trim out whitespaces
                update_option( "maus_toc_highlight_font_size", $update, true );
                
                $update = ( ! empty( $_POST["highlight_offset"] ) || $_POST["highlight_offset"] == 0) ? $_POST["highlight_offset"] : null;
                // Update the subheadline class settings in the database
                $update = str_replace(' ', '', $update); //trim out whitespaces
                update_option( "maus_toc_highlight_offset", $update, true );
                $status_url="request=highlight_link&status=success";
            break;
                
            default:
                echo "Error: invalid setting type.<br>";
            break;
        }         
        
        //first update has been requested
        if ( ! ($first_update = get_option('maus_toc_updated'))) update_option( "maus_toc_updated", true, true );
        
        // Redirect back to settings page
        $redirect_url = get_bloginfo( "url" ) . "/wp-admin/options-general.php?page=maus-toc-manager&$status_url";
        header( "Location: " . $redirect_url );
        exit;
    } //end of function save tag settings
} //end of maus_toc_info_manager

$tm = new maus_toc_info_manager;
unset( $tm );

?>
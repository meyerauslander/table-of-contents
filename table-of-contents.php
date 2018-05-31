<?php
/*
Plugin Name: Table of Contents
Description: Displays a list of links to headlines of the post content in a widget
Version: 1.0
Author: Meyer Auslander 
*/

$dir = plugin_dir_path( __FILE__ );
$include_path = "$dir/includes/";
include "$include_path" . "toc-admin.php";    //page in admin for "TOC Manager"

class tstn_toc_widget extends WP_Widget {

    function __construct() {
        add_action( 'widgets_init', array($this,'register'));           //register this widget class
        add_action( 'wp_enqueue_scripts', array($this,'enqueue_js_css'));   //activate the java script that creates the toc   
        $widget_options=array( 'classname' => 'tstn_toc_widget', 
                               'description' => __( 'Creates a table of contents for all post pages', 'toc_widget_domain' ));
                            // widget ID       widget name                                widget description
        parent::__construct( 'toc_widget', __('Table Of Contents', ' toc_widget_domain'), $widget_options);      
    } 
    
    //enqueue the java script and styles needed for the toc
    public function enqueue_js_css() {
        if( is_single()){
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'trst_toc_widget_js', plugin_dir_url(__FILE__) . 'toc.js' );
//            wp_register_style( 'trst_toc_widget_css',   plugin_dir_url(__FILE__) . 'style.css'  ); //in case styles are moved to a separate file
//            wp_enqueue_style ( 'trst_toc_widget_css' );
        }
    }
    
    public function register() {
        register_widget( 'tstn_toc_widget' );
    }
    
    public function widget( $args, $instance ) {
        $classes_1=get_option('maus_toc_classes_1');
        $classes_2=get_option('maus_toc_classes_2');
        $tags_1=   get_option('maus_toc_html_tags_1');
        $tags_2=   get_option('maus_toc_html_tags_2');
        $highlight_color = get_option('maus_toc_highlight_color');
        $highlight_font_size = get_option('maus_toc_highlight_font_size');
        $highlight_offset = get_option('maus_toc_highlight_offset');
        
        //put a # sign for hex colors
        if (ctype_xdigit($highlight_color)){
            $highlight_color = '#' . $highlight_color;
        }
        
        if (empty($highlight_offset)) $highlight_offset=0;
        
        $has_classes=false;
        $has_tags=false;
        
        $title = apply_filters( 'widget_title', $instance['title'] );
        
        //check for classes and tags
        if (is_single()){
            $cont=get_the_content();  //get content to check for toc tags.
            //check if it has any toc classes if so set $has_classes
            if ( !(empty($classes_1)) ){
                $classes_1_array = explode(",", $classes_1);
                foreach ($classes_1_array as $class){
                    //if ($has_classes=strpos( $cont, $class)){
                    if ($has_classes=preg_match("/<.*class=[\"\'].*\b$class\b.*[\"\'].*>/",$cont)){
                        break;
                    }
                }
            }
            if (!$has_classes && !(empty($classes_2) )){//it may only have subclasses 
                $classes_2_array = explode(",", $classes_2);
                foreach ($classes_2_array as $class){
                    if ($has_classes=preg_match("/<.*class=[\"\'].*\b$class\b.*[\"\'].*>/",$cont)){
                        break;
                    }
                }
            }
            
            if (!$has_classes && !empty($tags_1)){
                $tags_1_array=explode(",",$tags_1);
                foreach ($tags_1_array as $tag){
                    if ($has_tags=preg_match("/<\b$tag\b.*>(.*)<\/$tag>/i", $cont)){
                        break;
                    }
                }
            }
            if (!$has_classes && !$has_tags && !empty($tags_2)){
                $tags_2_array=explode(",",$tags_2);
                foreach ($tags_2_array as $tag){
                    if ($has_tags=preg_match("/<\b$tag\b.*>(.*)<\/$tag>/i", $cont)){
                        break;
                    }
                }
            }
        }
        
        
        if ( $has_classes || $has_tags ){ //only produce output for single posts that have toc tags
            echo $args['before_widget'];
            echo $args['before_title'] . $title . $args['after_title'];
            
            //output the html tags into a java script so they can be accessed by toc.js
            if ( !(empty($tags_1))){
                $tags_1_array = explode(",", $tags_1);
                $output_script = "<script>var tags_1 = [";
                foreach ($tags_1_array as $tag){ 
                    $tag = strtoupper($tag);    //in javascript all the tag names are uppercase
                    $output_script .= "'" . $tag . "', ";
                }
                $output_script .= "];</script>";
                $output_script = str_replace( ', ]', ']', $output_script ); //remove the extra comma at the end
                echo $output_script;
            } else echo "<script>var tags_1 = [];</script>"; //no tag 1s are specified
            if ( !(empty($tags_2))){
                $tags_2_array = explode(",", $tags_2);
                $output_script = "<script>var tags_2 = [";
                foreach ($tags_2_array as $tag){ 
                    $tag = strtoupper($tag);    //in javascript all the tag names are uppercase
                    $output_script .= "'" . $tag . "', ";
                }
                $output_script .= "];</script>";
                $output_script = str_replace( ', ]', ']', $output_script ); //remove the extra comma at the end
                echo $output_script;
            } else echo "<script>var tags_2 = [];</script>"; //no tag 2s are specified
            
            //output the heading class names into a java script so they can be accessed by toc.js
            if ( !(empty($classes_1))){           
                $output_script = "<script>var classes_1 = [";
                foreach ($classes_1_array as $class){ 
                    $output_script .= "'" . $class . "', ";
                }
                $output_script .= "];</script>";
                $output_script = str_replace( ', ]', ']', $output_script ); //remove the extra comma at the end
                echo $output_script;
            } else echo "<script>var classes_1 = [];</script>"; //no class names are specified
            
            //output the sub heading class names into a java script so they can be accessed by toc.js
            if ( !(empty($classes_2))){           
                $output_script = "<script>var classes_2 = [";
                if ( empty($classes_2_array) )
                    $classes_2_array = explode(",", $classes_2);
                foreach ($classes_2_array as $class){ 
                    $output_script .= "'" . $class . "', ";
                }
                $output_script .= "];</script>";
                $output_script = str_replace( ', ]', ']', $output_script ); //remove the extra comma at the end
                echo $output_script;
            } else echo "<script>var classes_2 = [];</script>"; //no class names are specified
            
            //output the offset to use for highlighting links
            echo "<script>var highlight_offset = $highlight_offset;</script>";
            
            //output the style for active links and fixed postion toc list
            echo    "<style>
                        a.maus_active {
                            color: $highlight_color  !important;
                            font-size: $highlight_font_size" . "px !important;
                        }
                        ul.tstn_toc_list.maus_sticky {
                           // position: fixed;  uncomment this to make it functional
                            top: 20px;
                        }
                    </style>";
            
            //echo an empty list (to be populated by toc.js)
            echo '<ul class="tstn_toc_list widget_nav_menu"></ul>';
            echo $args['after_widget'];  
        }  //end of widget output  
        else { //output empty arrays in order to avoid java script errors in the console
            echo "<script>  var tags_1 = [];
                            var classes_1 = [];
                            var classes_2 = [];
                  </script>";
        }
    } //end of widget function

    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) )
            $title = $instance[ 'title' ];
        else
            $title = __( 'class-based TOC', 'toc_widget_domain' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }
} //end of class tstn_toc_widget

//create one in order to add an action that regiesters it
$toc = new tstn_toc_widget;
unset( $toc );
?>
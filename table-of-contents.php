<?php
/*
Plugin Name: Table of Contents
Description: Displays a list of links to headlines of the post content in a widget
Version: 1.0
Author: Meyer Auslander 
*/

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
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo $args['before_widget'];
        $cont=get_the_content();  //get content to check for toc tags.  The logic here will need to be modified to accommodate a page that has not toc tags but does have html tags that were specified to automatically be included in the toc
        if ( is_single() && strpos( $cont, "trst_toc_heading")){ //only produce output for single posts that have toc tags
              echo $args['before_title'] . $title . $args['after_title'];
        }      
        echo __( '<ul class="tstn_toc_list widget_nav_menu"></ul>', 'toc_widget_domain' );
        echo $args['after_widget']; 
    }

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
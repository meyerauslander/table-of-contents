<?php 

/*
Plugin Name: Table of contents
Description: Adding list of headlines from content with anchor to sidebar widget
*/

function table_of_contents_plugin_js() {
  if( is_single()){
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'plugin_js', plugin_dir_url(__FILE__) . 'script.js' );
  }
}
add_action( 'wp_enqueue_scripts', 'table_of_contents_plugin_js' );

function table_of_contents_register_widget() {
  register_widget( 'table_of_contents_widget' );
}
add_action( 'widgets_init', 'table_of_contents_register_widget' );

class table_of_contents_widget extends WP_Widget {

  function __construct() {
    parent::__construct(
    // widget ID
    'table_of_contents_widget',
    // widget name
    __('Table Of Contents', ' table_of_contents_widget_domain'),
    // widget description
    array( 'description' => __( 'Create the list of headlines with anchors', 'table_of_contents_widget_domain' ), )
    );
  }

  public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] );
    echo $args['before_widget'];
    //if title is present
    if ( is_single() && ! empty( $title ) )
      echo $args['before_title'] . $title . $args['after_title'];
      //output
      echo __( '<ul class="table_of_contents_list widget_nav_menu"></ul>', 'table_of_contents_widget_domain' );
      echo $args['after_widget']; 
  }

  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) )
      $title = $instance[ 'title' ];
    else
      $title = __( 'Contents', 'table_of_contents_widget_domain' );
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <?php
  }

  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    return $instance;
  }
}

?>
<?php
// Register and load the widget
function oro_cat_slider_widget() {
    register_widget( 'oro_cat_slider' );
}
add_action( 'widgets_init', 'oro_cat_slider_widget' );

// Creating the widget
class oro_cat_slider extends WP_Widget {

    function __construct() {
        parent::__construct(

// Base ID of your widget
            'oro_cat_slider',

// Widget name will appear in UI
            esc_html__('ORO - Category Slider', 'oro'),

// Widget description
            array( 'description' => esc_html__( 'Display posts from a particular category in the form of a Slider.', 'oro' ), )
        );
    }

// Creating widget front-end

    public function widget( $args, $instance ) {

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $post_count             = isset( $instance['post_count'] ) ? $instance['post_count'] : 4;
        $category_slider 		= isset( $instance['category_slider'] ) ? $instance['category_slider'] : 0;
        
        $export	= [
	        'id'	=>	$this->id
        ];
        
        wp_localize_script('oro-custom-js', 'cat_slider_' . $this->number, $export);
        
                echo $args['before_widget'];
                if ( ! empty( $title ) )
                    echo $args['before_title'] . $title . $args['after_title'];
            ?>

            <?php
            	$widget_args = array(
	            	'cat'					=>	$category_slider,
	            	'ignore_sticky_posts'	=>	true,
	            	'posts_per_page'		=>	$post_count
            	);
            	
            	$widget_query	=	new WP_Query($widget_args);
            	
            	if ( $widget_query->have_posts() ) : ?>
            		<div class="cat-slider owl-carousel owl-theme">
	            	<?php
	            		while ($widget_query->have_posts() ) : $widget_query->the_post();
	            		?>
	            		<div class="cat-slide">
		            		<div class="slide-image">
			            		<?php if ( has_post_thumbnail() ): ?>
									<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('oro_slide'); ?></a>
								<?php
								else :
								?>	<a href="<?php the_permalink(); ?>"><img class="wp-post-image" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ph-slide-cats.svg'); ?>"></a>
								<?php endif; ?>
							</div>
							<div class="slide-title">
								<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' ); ?>
							</div>
						</div>
						<?php
						endwhile; ?>
					</div>
				<?php
				endif;
				wp_reset_postdata();
				
	echo $args['after_widget'];

    }

// Widget Backend
    public function form( $instance ) {

        /* Set up some default widget settings. */
        $defaults = array(
        	'title'              => esc_html__( 'Featured Posts', 'oro' ),
			'post_count'         => 4,
			'category_slider'    => 0
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'oro' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>


        <h3><?php esc_html_e('Slider', 'oro'); ?></h3>

        <p>
            <label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e( 'Number of Posts:', 'oro' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="number" value="<?php echo esc_attr( $instance['post_count'] ); ?>" />
        </p>

        <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category_slider' ) ); ?>"><?php _e('Category for Slider:', 'oro'); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'category_slider' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category_slider' ) ); ?>">
				<option value="0" <?php if ( !$instance['category_slider'] ) echo 'selected="selected"'; ?>><?php _e('--None--', 'oro'); ?></option>
				<?php
				$categories = get_categories(array('type' => 'post'));

				foreach( $categories as $cat ) {
					echo '<option value="' . esc_attr( $cat->cat_ID ) . '"';

					if ( $cat->cat_ID == $instance['category_slider'] ) echo  ' selected="selected"';

					echo '>' . esc_html( $cat->cat_name . ' (' . $cat->category_count . ')' );

					echo '</option>';
				}
				?>
			</select>
		</p>

        <?php
    }


    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']      =   ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['post_count']         =   ( ! empty( $new_instance['post_count'] ) ) ? (int)$new_instance['post_count'] : 4;
        $instance['category_slider']    =   ( ! empty( $new_instance['category_slider'] ) ) ? (int)$new_instance['category_slider'] : 0;
        return $instance;
    }
} // Class post author ends here
<?php
// Register and load the widget
function oro_recent_posts_widget() {
    register_widget( 'oro_recent_posts' );
}
add_action( 'widgets_init', 'oro_recent_posts_widget' );

// Creating the widget
class oro_recent_posts extends WP_Widget {

    function __construct() {
        parent::__construct(

// Base ID of your widget
            'oro_recent_posts',

// Widget name will appear in UI
            esc_html__('ORO - Recent Posts', 'oro'),

// Widget description
            array( 'description' => esc_html__( 'This Widget will show Most Recent Posts.', 'oro' ), )
        );
    }

// Creating widget front-end

    public function widget( $args, $instance ) {

        $title	= apply_filters( 'widget_title', empty( $instance['title'] ) ? __('Recent Posts', 'oro') : $instance['title'], $instance, $this->id_base );
        $post_count	= isset( $instance['post_count'] ) ? $instance['post_count'] : 4;
        $align	= isset( $instance['align'] ) ? $instance['align'] : 'vertical';


                echo $args['before_widget'];
                if ( ! empty( $title ) )
                    echo $args['before_title'] . $title . $args['after_title'];
            
					$widget_args	=	array(
						'posts_per_page'		=>	$post_count,
						'ignore_sticky_posts'	=>	true
					);
					
					$widget_query	=	new WP_Query( $widget_args );
					
					if ( $widget_query->have_posts() ) : ?>
					
					
						<div class="oro-widget-posts <?php echo $align == 'horizontal' ? 'row is-horizontal align-items-start' : ''; ?>">
							<?php
		            		while ($widget_query->have_posts() ) : $widget_query->the_post();
		            		?>
		            		
			            		<div class=" oro-widget-post row no-gutters <?php echo $align == 'horizontal' ? 'col-md-3' : 'align-items-center'; ?>">
				            		
				            		<div class="oro-widget-post-thumb <?php echo $align == 'horizontal' ? '' : 'col-md-4'; ?>">
					            		<?php if ( has_post_thumbnail() ): ?>
											<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('oro_list_thumb'); ?></a>
										<?php
										else :
										?>	<a href="<?php the_permalink(); ?>"><img class="wp-post-image" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ph_list.png'); ?>"></a>
										<?php endif; ?>
				            		</div>
				            		
				            		<div class="oro-widget-post-title <?php echo $align == 'horizontal' ? '' : 'col-md-8'; ?>">
					            		<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' ); ?>
						            	<cite class="recent-date"><?php echo get_the_date('d F, Y'); ?></cite>
				            		</div>
			            		</div>
			            		
							<?php
							endwhile;
							?>
						</div>
					<?php
					endif;
            
    	   echo $args['after_widget'];

    }

// Widget Backend
    public function form( $instance ) {

        /* Set up some default widget settings. */
       $defaults = array(
           'title'              => '',
		   'post_count'         => 4,
		   'align'				=> 'vertical'
       );
       $instance = wp_parse_args( (array) $instance, $defaults );
         ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'oro' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>


        <p>
            <label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e( 'Number of Posts:', 'oro' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'post_count' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="number" value="<?php echo esc_attr( $instance['post_count'] ); ?>" />
        </p>
        
        <p>
			<span><b><?php _e('Widget Alignment', 'oro'); ?></b></span><br />
				<p>
					<input type="radio" id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>-vertical" name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>" class="widefat" value="vertical" <?php checked($instance['align'], 'vertical') ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>-vertical"><span><?php _e('Vertical', 'oro'); ?></span></label>
				</p>
			
				<p>
					<input type="radio" id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>-horizontal" name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>" class="widefat" value="horizontal" <?php checked($instance['align'], 'horizontal') ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>-horizontal"><span><?php _e('Horizontal', 'oro'); ?></span></label>
				</p>
		</p>

        <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']              =   ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : __('Recent Posts', 'oro');
        $instance['post_count']         =   ( ! empty( $new_instance['post_count'] ) ) ? absint($new_instance['post_count']) : 4;
        $instance['align']              =   ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['align'] ) : 'vertical';
        return $instance;
    }
}
    
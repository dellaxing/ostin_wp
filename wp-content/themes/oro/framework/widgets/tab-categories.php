<?php
// Register and load the widget
function oro_cats_tab_widget() {
    register_widget( 'oro_cats_tab' );
}
add_action( 'widgets_init', 'oro_cats_tab_widget' );

// Creating the widget
class oro_cats_tab extends WP_Widget {

    function __construct() {
        parent::__construct(

// Base ID of your widget
            'oro_cats_tab',

// Widget name will appear in UI
            esc_html__('ORO - Tab Categories Widget', 'oro'),

// Widget description
            array( 'description' => esc_html__( 'This Widget will add a tabbed categories widget.', 'oro' ), )
        );
    }

// Creating widget front-end

    public function widget( $args, $instance ) {

        $title              = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        for( $i = 1; $i <= 3; $i++ ) {
        	${"category_$i"}	= isset( $instance['category_' . $i] ) ? $instance['category_' . $i] : 0;
        }
        $align				= isset( $instance['align'] ) ? $instance['align'] : 'vertical';
        
        $direction = $align == 'vertical' ? 'column' : 'row';
        $columns = $align == 'vertical' ? 'col-12' : 'col-md-3';
        

            echo $args['before_widget'];
            if ( ! empty( $title ) )
                echo $args['before_title'] . $title . $args['after_title']; ?>
                
                <div id="tab-category-wrapper-<?php echo $this->number ?>">
					<ul class="category_titles <?php echo $align == 'horizontal' ? 'is-horizontal' : 'is-vertical'?>">
					<?php
						$cat_ids = [];
						for( $i = 1; $i <= 3; $i++ ) { 
							
					        if ( !empty( ${"category_$i"} ) ) {
					        	$cat_id = '#category_panel_' . esc_attr(preg_replace( "/\s+/", "_", strtolower( get_cat_name( ${"category_$i"} ) ) ) ) . '_' . $this->number . "_" . $i ?>
				        		<li><a href="<?php echo $cat_id; ?>"><?php echo get_cat_name( ${"category_$i"} ); ?></a></li>
				        		<?php array_push( $cat_ids, $cat_id );
				        	}
				        } ?>
				    </ul>
				    <div class="tabs-slider"></div>
	
					<?php
	                for( $i = 1; $i <= 3; $i++ ) {
		                if ( !empty( ${"category_$i"} ) ) { ?>
			                <div id="category_panel_<?php echo preg_replace( "/\s+/", "_", strtolower( get_cat_name( ${"category_$i"} ) ) ) . '_' . $this->number . "_" . $i ?>" class="category_panel row <?php echo $align == 'horizontal' ? 'is-horizontal' : 'is-vertical'?>">
				                <?php
									$params = array( 
											'posts_per_page' 		=>  4, 
											'ignore_sticky_posts' 	=> true,
											'cat' 					=> ${"category_$i"}
										);
										
									$tab_cat   =   new WP_Query( $params );
									
									if ( $tab_cat->have_posts() ) {
							            while ( $tab_cat->have_posts() ) {
							                $tab_cat->the_post();
							                
							                get_template_part('framework/layouts/content', 'card', array('columns'	=>	$columns) );
							            
							            }
							        }
							        wp_reset_postdata();
								?>
							</div>
						<?php
	                	}
	                } ?>
                </div>
                
            <?php
    	   echo $args['after_widget'];
    	   
    	   $export	=	array(
	    	   "number"	=>	$this->number,
	    	   "align"	=>	$direction
    	   );
    	   wp_localize_script( 'oro-custom-js', 'tab_widget_' . $this->number, $export );
    }

	// Widget Backend
    public function form( $instance ) {

        /* Set up some default widget settings. */
       $defaults = array(
           'title'              => esc_html__( 'Featured Categories', 'oro' ),
           'category_1'           => 0,
           'category_2'           => 0,
           'category_3'           => 0,
           'align'			  	  => 'vertical'
       );
       $instance = wp_parse_args( $instance, $defaults );
         ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
            <span><b><?php _e('Title', 'oro'); ?></b></span>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
            </label>
        </p>
		
		
		<?php for( $i = 1; $i <= 3; $i++ ) { ?>
	        <p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'category_' . $i ) ); ?>">
					<span><b><?php echo __('Category ' . $i, 'oro' ); ?></b></span><?php // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText ?>
					<select id="<?php echo esc_attr( $this->get_field_id( 'category_' . $i ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category_' . $i ) ); ?>" class="widefat">
						<option value="0" <?php if ( !$instance['category_' . $i] ) echo 'selected="selected"'; ?>><?php _e('--None--', 'oro'); ?></option>
						<?php
						$categories = get_categories(array('type' => 'post'));
		
						foreach( $categories as $cat ) {
							echo '<option value="' . esc_attr( $cat->cat_ID ) . '"';
		
							if ( $cat->cat_ID == $instance['category_' . $i] ) echo  ' selected="selected"';
		
							echo '>' . esc_html( $cat->cat_name . ' (' . $cat->category_count . ')' );
		
							echo '</option>';
						}
						?>
					</select>
				</label>
			</p>
        <?php
		}
		?>
		<br/>
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
        $instance['title']      	=   ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        
        for( $i = 1; $i <= 3; $i++ ) {
        $instance['category_' . $i]	=   ( ! empty( $new_instance['category_' . $i] ) ) ? absint( $new_instance['category_' . $i] ) : 0;
        }
         $instance['align']      	=   ( ! empty( $new_instance['align'] ) ) ? sanitize_text_field( $new_instance['align'] ) : 'vertical';
        
        return $instance;
    }
} // Class post author ends here
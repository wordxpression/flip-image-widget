<?php

/*
Plugin Name: wxp-flip-image-widget
Plugin URI: 
Description: 
Version: 1.0.0
Author: Wilko van der Ploeg / WordXPression
Author URI: http://wordxpression.com/
License: GPLv3
*/

/* 
Copyright (C) 2016 WordXPression

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

add_action('admin_enqueue_scripts', 'wxp_flip_image_script_init');

function wxp_flip_image_script_init()        {
    wp_enqueue_media();
    wp_register_script('wxp-upload-image', plugin_dir_url( __FILE__ ) . 'js/main.js', Array('jquery'));
    wp_enqueue_script('wxp-upload-image');
}

function wxp_register_flipimage_widget() {
    register_widget( 'WXP_FlipImage_Widget' );
}
add_action( 'widgets_init', 'wxp_register_flipimage_widget' );

class WXP_FlipImage_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
	
			'description' => 'Widget with a flipping image',
		);
		parent::__construct( 'wxp_flipimage', 'WXP FlipImage', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
            //get and prepare data
            //Get the image URL. If no ID is set, we take the URL, since it is 
            //most likely not an image from the Media Lib. If an ID is set,
            //we retrieve the URL by ID and format
            
            if (!isset($instance['image_id'])) {
                $image_url = $instance['image'];
            } else {
                $image_data = wp_get_attachment_image_src($instance['image_id'], $instance['image_size']);
                //print_r($image_data);
                //die();
                $image_url = $image_data[0];
            }
            



// outputs the content of the widget
                
            
                echo $args['before_widget'];
                echo $args['before_title'];
                echo $instance['title'];
                echo $args['after_title'];
                echo '<div class="wxp-fiw-front">';
                echo "<img src=\"{$image_url}\"/>";
                echo '</div>'; //wxp-fiw-front
                echo '<div class="wxp-fiw-back">';
                echo '</div>'; //wxp-fiw-back
                echo $args['after_widget'];
              
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
                $title = !empty( $instance['title'] ) ? $instance['title'] :  '';//__( 'New title', 'text_domain' );
                $desc = !empty($instance['desc']) ? $instance['desc'] : '';
                $image = !empty($instance['image']) ? $instance['image'] : '';
                $image_size = !empty($instance['image_size']) ? $instance['image_size'] : '';
                $image_id = !empty($instance['image_id']) ? $instance['image_id'] : '';
                $style = !empty($instance['style']) ? $instance['style'] : '';
                $alignment = !empty($instance['alignment']) ? $instance['alignment'] : '';
		?>
		<p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"></label>
                    <strong><?php _e( esc_attr( 'Title:' ),'wxp_fiw' ); ?></strong></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
                       type="text" value="<?php echo esc_attr( $title ); ?>">
		
                
                <label for="<?php echo esc_attr($this->get_field_id('desc'));?>">
                    <strong><?php _e( esc_attr( 'Description'),'wxp_fiw'); ?></strong></label>
                <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('desc') ); ?>" 
                          name="<?php echo esc_attr($this->get_field_name('desc') ); ?>"
                          ><?php echo $desc;?></textarea>
                <label for="<?php echo esc_attr($this->get_field_id('image'));?>">
                    <strong><?php _e('Image','wxp_fiw');?></strong>
                <input id="<?php echo esc_attr($this->get_field_id('image'));?>" class="wxp_image_field" type="text" 
                       size="30" name="<?php echo esc_attr($this->get_field_name('image'));?>" value="<?php echo $image;?>" /> 
                <input id="upload_image_button" class="button wxp-image-button" 
                       type="button" value="<?php _e('Upload Image','wxp_fiw');?>" />
                <input class="wxp_image_id" type="hidden" name="<?php echo esc_attr($this->get_field_name('image_id'))?>"
                       value="<?php echo $image_id?>">
                       <br /><br/><em><?php _e('Enter a URL or upload an image','wxp_fiw');?></em><br/>
                </label>
                <label for="<?php echo esc_attr($this->get_field_id('image_size'));?>">
                    <strong><?php _e('Image size','wxp_fiw');?></strong></label><br/>
                <select name="<?php echo esc_attr($this->get_field_name('image_size'));?>" 
                        id="<?php echo esc_attr($this->get_field_id('image_size'));?>">
                    <?php
                    $options = get_intermediate_image_sizes();
                    foreach ($options as $key=>$value) {
                    ?>
                    <option value="<?php echo esc_attr($key);?>" 
                        <?php selected($image_size,$value);?>><?php echo esc_attr($value);?></option>
                    <?php
                    }
                    ?>
                </select><br/>
                <label><strong><?php _e('Alignment','wxp_fiw');?></strong><br>
                    <input type="radio" value="1" <?php checked($alignment,1);?>
                           name="<?php echo esc_attr($this->get_field_name('alignment'));?>"
                           id="<?php echo esc_attr($this->get_field_id('alignment') . 
                             '_1');?>"> <?php _e('Left','wxp_fiw');?><br/>
                    <input type="radio" value="2" <?php checked($alignment,2);?>
                           name="<?php echo esc_attr($this->get_field_name('alignment'));?>"
                           id="<?php echo esc_attr($this->get_field_id('alignment') . 
                             '_2');?>"> <?php _e('Center','wxp_fiw');?><br/>
                    <input type="radio" value="3" <?php checked($alignment,3);?>
                           name="<?php echo esc_attr($this->get_field_name('alignment'));?>"
                           id="<?php echo esc_attr($this->get_field_id('alignment') . 
                             '_3');?>"> <?php _e('Right','wxp_fiw');?><br/>
                </label>
                <label>
                    <strong><?php _e('Flipping style','wxp_fiw');?><br/></strong>
                    <input type="radio" value="1" <?php checked($style,1);?> 
                           name="<?php echo esc_attr($this->get_field_name('style'));?>"
                           id="<?php echo esc_attr($this->get_field_id('style') . '_1');?>"
                           > <?php _e('Vertical clockwise','wxp_fiw');?><br/>
                    <input type="radio" value="2" <?php checked($style,2);?>
                           name="<?php echo esc_attr($this->get_field_name('style'));?>"
                           id="<?php echo esc_attr($this->get_field_id('style') . '_2');?>"
                           > <?php _e('Vertical counter clockwise','wxp_fiw');?><br/>
                    <input type="radio" value="3" <?php checked($style,3);?>
                           name="<?php echo esc_attr($this->get_field_name('style'));?>"
                           id="<?php echo esc_attr($this->get_field_id('style') . '_3');?>"
                           > <?php _e('Horizontal clockwise','wxp_fiw');?><br/>
                    <input type="radio" value="4" <?php checked($style,4);?>
                           name="<?php echo esc_attr($this->get_field_name('style'));?>"
                           id="<?php echo esc_attr($this->get_field_id('style') . '_4');?>"
                           > <?php _e('Horizontal counter clockwise','wxp_fiw');?><br/>                    
                </label>
                </p>
		<?php 
            

            
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
                $instance['desc'] = (!empty( $new_instance['desc'])) ? strip_tags($new_instance['desc']) : '';
                $instance['image'] = (!empty( $new_instance['image'])) ? strip_tags($new_instance['image']) : '';
                $instance['image_size'] = (!empty( $new_instance['image_size'])) ? strip_tags($new_instance['image_size']) : '';
                $instance['image_id'] = (!empty( $new_instance['image_id'])) ? strip_tags($new_instance['image_id']) : '';
                $instance['style'] = (!empty($new_instance['style'])) ? strip_tags( $new_instance['style']) : '';
                $instance['alignment'] = (!empty($new_instance['alignment'])) ? strip_tags($new_instance['alignment']) : '';
                
		return $instance;
                
	}
}

<?php

class WPMUDEV_Field_User_Role_Select extends WPMUDEV_Field {
	/**
	 * Runs on parent construct
	 *
	 * @since 1.0
	 * @access public
	 */
	public function on_creation( $args ) {
		$this->args = wp_parse_args($args, array(
			'multiple' => true,
			'placeholder' => __('Select User Role', 'mp'),
		));
		
		$this->args['class'] .= ' wpmudev-user-role-select';			
	}

	/**
	 * Prints scripts
	 *
	 * @since 3.0
	 * @access public
	 */	
	public function print_scripts() {
		$roles = get_editable_roles();
		
		foreach ( $roles as $role_name => $role_info ) {
			$data[] = array('id' => $role_name, 'text' => $role_name);
		}
	?>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('input.wpmudev-user-role-select').each(function(){
		var $this = $(this);
		
		$this.select2({
			"multiple" : <?php echo $this->args['multiple']; ?>,
			"placeholder" : "<?php echo $this->args['placeholder']; ?>",
			"initSelection" : function(element, callback){
				var data = [];
				
				$(element.attr('data-select2-value').split('||')).each(function(){
					var val = this.split('->');
					data.push({ "id" : val[0], "text" : val[1] });
				});
				
				callback(data);
			},			
			"data" : <?php echo json_encode($data); ?>,
			"width" : "100%"
		}) 
	});
});
</script>
	<?php		
	}
		
	/**
	 * Displays the field
	 *
	 * @since 1.0
	 * @access public
	 * @param int $post_id
	 * @param bool $echo
	 */
	public function display( $post_id, $echo = true ) {
		$value = $this->get_value($post_id);
		$data = array();
		$roles = explode(',', $value);
		
		foreach ( $roles as $role ) {
			$data[] = $role . '->' . $role;
		}
		
		$this->args['custom']['data-select2-value'] = implode('||', $data);
	
		echo '<input type="hidden" ' . $this->parse_atts() . ' value="' . esc_attr($value) . '" />';
		
		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}
	
	/**
	 * Enqueues the field's scripts
	 *
	 * @since 1.0
	 * @access public
	 */
	public function enqueue_scripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('wpmudev-field-select2', WPMUDEV_Metabox::class_url('ui/select2/select2.min.js'), array('jquery'), '3.4.8');
	}
	
	/**
	 * Enqueues the field's styles
	 *
	 * @since 1.0
	 * @access public
	 */
	public function enqueue_styles() {
		wp_enqueue_style('wpmudev-field-select2',  WPMUDEV_Metabox::class_url('ui/select2/select2.css'), array(), '3.4.8');
	}
}
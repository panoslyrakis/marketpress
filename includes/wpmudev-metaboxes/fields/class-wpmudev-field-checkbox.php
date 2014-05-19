<?php

class WPMUDEV_Field_Checkbox extends WPMUDEV_Field {
	/**
	 * Runs on construct of parent class
	 *
	 * @since 3.0
	 * @access public
	 */
	public function on_creation( $args ) {
		$this->args = wp_parse_args($args, array(
			'value' => 1,			//the value that gets saved to the database
			'message' => '',	//the message that should be displayed next to the checkbox (e.g. Yes, No, etc)
		));
	}
	
	/**
	 * Sanitizes the field value before saving to database
	 *
	 * @since 1.0
	 * @access public
	 * @param $value
	 */	
	public function sanitize_for_db( $value ) {
		$value = ( empty($value) ) ? 0 : $value;
		return apply_filters('wpmudev_field_sanitize_for_db', $value, $post_id, $this);
	}

	/**
	 * Displays the field
	 *
	 * @since 3.0
	 * @access public
	 * @param int $post_id
	 * @param bool $echo
	 */
	public function display( $post_id, $echo = true ) {
		$value = $this->get_value($post_id);

		if ( $value === false ) {
			$checked = checked($this->args['value'], $this->args['default_value'], false);
		} else {
			$checked = checked($this->args['value'], $value, false);
		}

		$html  = '<label class="' . $this->args['label']['class'] . '" for="' . $this->get_id(). '">';
		$html .= '<input type="checkbox" ' . $this->parse_atts() . ' ' . $checked . ' /> <span>' . $this->args['message'] . '</span></label>';
		
		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}
}
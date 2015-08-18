<?php
/**
 * Plugin Name: Password Pointer
 * Plugin URI: http://www.billerickson.net/
 * Description: Creates a pointer that nags users to change their password.
 * Version: 1.1
 * Author: Bill Erickson
 * Author URI: http://www.billerickson.net
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package Password Pointer
 * @version 1.1
 * @author Bill Erickson <bill@billerickson.net>
 * @copyright Copyright © 2011, Bill Erickson
 * @link http://www.billerickson.net/shortcode-to-display-posts/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
 
/**
 * Initializes the password pointer
 *
 * Refer to /wp-admin/includes/template.php to see how the default pointer is done.
 */
function be_password_pointer_enqueue( $hook_suffix ) {
	$enqueue = false;

	$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

	if ( ! in_array( 'be_password', $dismissed ) ) {
		$enqueue = true;
		add_action( 'admin_print_footer_scripts', 'be_password_pointer_print_admin_bar' );
	}

	if ( $enqueue ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}
}
add_action( 'admin_enqueue_scripts', 'be_password_pointer_enqueue' );

function be_password_pointer_print_admin_bar() {
	$pointer_content  = '<h3>' . 'Change your password!' . '</h3>';
	$pointer_content .= '<p>' . 'Change your randomly generated password to one that you will remember.' . '</p>';
	$pointer_content = apply_filters( 'password_pointer_message', $pointer_content );

?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready( function($) {
	$('#wp-admin-bar-my-account').pointer({
		content: '<?php echo $pointer_content; ?>',
		position: 'top',
		pointerWidth: 200,
		close: function() {
			$.post( ajaxurl, {
					pointer: 'be_password',
					action: 'dismiss-wp-pointer'
			});
		}
	}).pointer('open');
});
//]]>
</script>
<?php
}

<?php

class WPLithoThemeAdmin {

	public static $singleton = null;
	public static function singleton() {
		if ( is_null( self::$singleton ) ) {
			self::$singleton = new self;
		}
		return self::$singleton;
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'save_post', array( $this, 'onsave' ), 1, 2 );
		add_action( 'delete_post', array( $this, 'ondelete' ) );
	}

	public function admin_menu() {
		add_meta_box( 'wplitho-options', __( 'WP Litho Options', 'wplitho' ), array( $this, 'metabox' ), 'post', 'side', 'core' );
		add_meta_box( 'wplitho-options', __( 'WP Litho Options', 'wplitho' ), array( $this, 'metabox' ), 'page', 'side', 'core' );
	}

	public function onsave( $post_id, $post ) {
		if ( !$post_id ) $post_id = $_POST['post_ID'];
		if ( !$post_id ) return $post;

		if ( !isset( $_POST['wplitho_noncename'] ) || !wp_verify_nonce( $_POST['wplitho_noncename'], plugin_basename(__FILE__) ) )
		{
			return $post;
		}

		$data = $_POST['wplitho'];
		$data['show_title'] = !!@$data['show_title'];

		update_post_meta( $post_id, 'wplitho', $data );
	}

	public function ondelete( $post_id ) {
		delete_post_meta( $post_id, 'wplitho' );
	}

	public function metabox() {
		global $post_id;
		$data = get_post_meta( $post_id, 'wplitho', true );
		if ( !isset( $data['show_title'] ) ) {
			$data['show_title'] = WPLithoThemeOptions::option( 'show_title' );
		}
		wp_nonce_field( plugin_basename(__FILE__), 'wplitho_noncename' ); ?>
<div>
	<style type="text/css" scoped>
		#wplitho-options-table {
			width: 100%;
			margin: 0;
			background-color: #F9F9F9;
			border: 1px solid #DFDFDF;
			border-spacing: 8px;
			-moz-border-radius: 3px;
			-webkit-border-radius: 3px;
			border-radius: 3px;
			font-size: 1.1em;
		}
		#wplitho-options-table th {
			text-align: right;
			vertical-align: middle;
			font-weight: normal;
		}
	</style>
	<table id="wplitho-options-table">
		<tr>
			<th scope="row"><?php _e( 'Show Title', 'wplitho' ); ?></th>
			<td>
				<input type="checkbox" name="wplitho[show_title]" <?php echo checked( 1, @$data['show_title'], false ); ?>>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e( 'Content Width', 'wplitho' ); ?></th>
			<td>
				<input type="text" name="wplitho[width]" placeholder="default" value="<?php echo esc_attr( @$data['width'] ); ?>">
			</td>
		</tr>
	</table>
</div>
<?php
	}

}
return WPLithoThemeAdmin::singleton();

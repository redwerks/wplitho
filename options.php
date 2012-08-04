<?php

class WPLithoThemeOptions {

	public static $singleton = null;
	public static function singleton() {
		if ( is_null( self::$singleton ) ) {
			self::$singleton = new self;
		}
		return self::$singleton;
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'after_setup_theme', array( $this, 'theme_init' ) );
	}

	public function admin_menu() {
		add_theme_page(
			__( 'WP Litho Theme Options', 'wplitho' ),
			__( 'WP Litho Options', 'wplitho' ),
			'edit_theme_options',
			'wplitho-theme-options',
			array( $this, 'options_page' )
		);
	}

	public function defaults() {
		return array(
			'gap' => 32,
			'overlap' => false,
			'width' => '',
			'padding' => '',
			'show_title' => true,
			'show_meta' => true,
			'anchor_menu' => false,
			'logo' => null,
			'logo_mobile' => null,
			'css' => '',
			'font_body' => '',
			'font_header' => '',
			'font_navigation' => '',
			'line_height' => '',
		);
	}

	public function theme_init() {
		$options = get_option( 'theme_wplitho' );
		if ( !is_array( $options ) ) {
			$options = array();
		}
		foreach ( $this->defaults() as $key => $value ) {
			if ( !array_key_exists( $key, $options ) ) {
				$options[$key] = $value;
			}
		}
		update_option( 'theme_wplitho', $options );
	}

	public function get_option( $key ) {
		$options = get_option( 'theme_wplitho' );
		if ( isset( $options[$key] ) ) {
			return $options[$key];
		}
		$defaults = $this->defaults();
		return $defaults[$key];
	}

	public static function option( $key ) {
		return self::singleton()->get_option( $key );
	}

	public static function show_title( $post_id ) {
		$data = get_post_meta( $post_id, 'wplitho', true );
		if ( isset( $data['show_title'] ) ) {
			return $data['show_title'];
		}
		return self::option( 'show_title' );
	}

	public static function post_style_attr( $post_id ) {
		$data = get_post_meta( $post_id, 'wplitho', true );
		$width = isset( $data['width'] ) ? $data['width'] : self::option( 'width' );
		if ( $width ) {
			return ' style="max-width: ' . esc_attr( $width ) . ';"';
		}
		return '';
	}

	public function admin_init() {
		add_settings_section( 'default', '', array( $this, 'no_op' ), 'theme-wplitho' );
		add_settings_section( 'fonts', '', array( $this, 'font_section' ), 'theme-wplitho' );
		add_settings_field( 'theme_wplitho_gap', __( 'Horizontal Panel Gap', 'wplitho' ), array( $this, 'number_input' ), 'theme-wplitho', 'default', array( 'key' => 'gap' ) );
		add_settings_field( 'theme_wplitho_overlap', __( 'Left panel overlap', 'wplitho' ), array( $this, 'overlap_input' ), 'theme-wplitho', 'default', array( 'key' => 'overlap' ) );
		add_settings_field( 'theme_wplitho_width', __( 'Content Width', 'wplitho' ), array( $this, 'text_input' ), 'theme-wplitho', 'default', array( 'key' => 'width' ) );
		add_settings_field( 'theme_wplitho_padding', __( 'Padding', 'wplitho' ), array( $this, 'text_input' ), 'theme-wplitho', 'default', array( 'key' => 'padding' ) );
		add_settings_field( 'theme_wplitho_line_height', __( 'Line Height', 'wplitho' ), array( $this, 'text_input' ), 'theme-wplitho', 'default', array( 'key' => 'line_height' ) );
		add_settings_field( 'theme_wplitho_show_title', __( 'Show title (post default)', 'wplitho' ), array( $this, 'bool_input' ), 'theme-wplitho', 'default', array( 'key' => 'show_title' ) );
		add_settings_field( 'theme_wplitho_show_meta', __( 'Show meta', 'wplitho' ), array( $this, 'bool_input' ), 'theme-wplitho', 'default', array( 'key' => 'show_meta' ) );
		add_settings_field( 'theme_wplitho_anchor_menu', __( 'Menu type', 'wplitho' ), array( $this, 'anchor_menu_input' ), 'theme-wplitho', 'default', array( 'key' => 'anchor_menu' ) );
		add_settings_field( 'theme_wplitho_logo', __( 'Logo URL', 'wplitho' ), array( $this, 'text_input' ), 'theme-wplitho', 'default', array( 'key' => 'logo' ) );
		add_settings_field( 'theme_wplitho_logo_mobile', __( 'Mobile Logo URL', 'wplitho' ), array( $this, 'text_input' ), 'theme-wplitho', 'default', array( 'key' => 'logo_mobile' ) );
		add_settings_field( 'theme_wplitho_css', __( 'CSS', 'wplitho' ), array( $this, 'css_input' ), 'theme-wplitho', 'default', array( 'key' => 'css' ) );
		add_settings_field( 'theme_wplitho_font_body', __( 'Body font', 'wplitho' ), array( $this, 'font_input' ), 'theme-wplitho', 'fonts', array( 'key' => 'font_body' ) );
		add_settings_field( 'theme_wplitho_font_header', __( 'Header font', 'wplitho' ), array( $this, 'font_input' ), 'theme-wplitho', 'fonts', array( 'key' => 'font_header' ) );
		add_settings_field( 'theme_wplitho_font_navigation', __( 'Navigation font', 'wplitho' ), array( $this, 'font_input' ), 'theme-wplitho', 'fonts', array( 'key' => 'font_navigation' ) );
		register_setting( 'theme-wplitho', 'theme_wplitho', array( $this, 'sanitize' ) );
	}

	public function no_op() {}
	public function font_section() { ?>
		<p>These are raw css font-family lists they follow the same rules as in css.</p>
		<ul class="ul-disc">
			<li>Use a comma separated list of fonts.</li>
			<li>Any font name containing spaces should be wrapped in quotes.</li>
			<li>Many fonts are only on some types of computers. When you pick a font check what computers its on and include similar looking fonts that exist on other types of computers as fallbacks.</li>
			<li>Always include either <code>sans-serif</code>, <code>serif</code>, <code>cursive</code>, <code>monospace</code>, <code>cursive</code>, or <code>fantasy</code> at the end of the list as a final fallback.</li>
			<li>Some useful examples:
				<ul class="ul-disc">
					<li><code style="font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, &quot;Geneva&quot;, sans-serif;">"Helvetica", "Arial", "Geneva", sans-serif</code></li>
					<li><code style="font-family: &quot;Times&quot;, &quot;Times New Roman&quot;, &quot;Georgia&quot;, serif;">"Times", "Times New Roman", "Georgia", serif</code></li>
					<li><code style="font-family: &quot;Consolas&quot;, &quot;Lucida Console&quot;, &quot;Monaco&quot;, monospace;">"Consolas", "Lucida Console", "Monaco", monospace</code></li>
					<li><code style="font-family: &quot;Courier New&quot;, &quot;Courier&quot;, monospace;">"Courier New", "Courier", monospace</code></li>
				</ul>
			</li>
		</ul>
<?php
	}
	public function overlap_input( $args ) {
		echo '<p>Leave empty to have panels automatically centered.</p>';
		$this->number_input( $args );
		echo 'px';
	}
	public function css_input( $args ) {
		echo '<p>You can insert raw css into this box. This can be useful for example to insert @import statements for WebFonts that you want to use in font families.</p>';
		echo '<textarea name="theme_wplitho[' . $args['key'] . ']">' . esc_html( $this->get_option( $args['key'] ) ) . '</textarea>';
	}
	public function font_input( $args ) {
		$this->text_input( $args );
	}
	public function anchor_menu_input( $args ) {
		echo '<p>A normal menu will output a WordPress driven menu controllable by the menu tab. An anchor menu will output navigation links pointing to individual posts/pages displayed.</p>';
		echo '<label><input type="radio" name="theme_wplitho[' . $args['key'] . ']" value="" ' . checked( false, $this->get_option( $args['key'] ), false ) . '> ';
		echo 'Navigation menu</label> ';
		echo '<label><input type="radio" name="theme_wplitho[' . $args['key'] . ']" value="1" ' . checked( true, $this->get_option( $args['key'] ), false ) . '> ';
		echo 'Anchor menu</label>';
	}
	public function text_input( $args ) {
		echo '<input type="text" name="theme_wplitho[' . $args['key'] . ']" value="' . esc_attr( $this->get_option( $args['key'] ) ) . '">';
	}
	public function number_input( $args ) {
		echo '<input type="number" name="theme_wplitho[' . $args['key'] . ']" value="' . esc_attr( $this->get_option( $args['key'] ) ) . '">';
	}
	public function bool_input( $args ) {
		echo '<input type="checkbox" name="theme_wplitho[' . $args['key'] . ']" ' . checked( 1, $this->get_option( $args['key'] ), false ) . '>';
	}

	public function sanitize( $input ) {
		$defaults = $this->defaults();

		foreach ( $defaults as $key => $default ) {
			$in = isset( $input[$key] ) ? $input[$key] : '';
			if ( $key === 'overlap' ) {
				$in = is_numeric( $in ) ? (int)$in : false;
			} elseif ( is_bool( $default ) ) {
				$in = !!$in;
			} elseif ( is_int( $default ) ) {
				$in = (int)$in;
			}
			if ( $key == 'gap' ) {
				$in = max( 0, $in );
			}
			if ( strpos( $key, 'font_' ) === 0 ) {
				$in = preg_replace( '/^font-family:\s*|;$/', '', $in );
			}
			$output[$key] = $in;
		}

		return $output;
	}

	public function options_page() { ?>
<div class="wrap">
	<div class="icon32" id="icon-themes"><br></div>	<h2><?php _e( 'WPLitho Theme Options', 'wplitho' ); ?></h2>
<?php if ( isset( $_GET['settings-updated'] ) ) { ?>
		<div class='updated'><p><?php _e( 'Theme settings updated successfully.', 'wplitho' ); ?></p></div>
<?php } ?>
	<form action="options.php" method="post">
		<?php settings_fields( 'theme-wplitho' ); ?>
		<?php do_settings_sections( 'theme-wplitho' ); ?>

		<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'wplitho'); ?>">
	</form>
</div>
<?php
	}

}
WPLithoThemeOptions::singleton();

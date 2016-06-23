<?php
/**
 * Implements the UMW Online Tools class
 * @version 0.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
  die( 'You should not access this file directly' );
}

class UMW_Online_Tools {
  public $v = '0.5.2.3';
  public $icons = array();
  public $options = array();

  /**
   * Instantiate our object
   */
  function __construct() {
    add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
    add_action( 'init', array( $this, 'gather_icons' ) );
	add_action( 'admin_init', array( $this, 'admin_init' ) );
  }
  
	/**
	* Retrieve and return the root URL
	* @param int $id the site ID to retrieve if UMW_IS_ROOT is not defined
	* @param bool $echo whether to echo the URL or not
	* @return string the URL
	*/
	function get_site_url( $id=1, $echo=false ) {
		if ( defined( 'UMW_IS_ROOT' ) ) {
			if ( is_numeric( UMW_IS_ROOT ) ) {
				$url = get_site_url( UMW_IS_ROOT );
			} else {
				$url = UMW_IS_ROOT;
			}
		} else {
			$url = get_site_url( $id );
		}
		
		$url = str_replace( array( 'http://', 'https://' ), array( '//', '//' ), esc_url( $url ) );
		
		if ( $echo ) {
			echo $url;
		}
		
		return $url;
	}

  /**
   * Test whether this is the main UMW theme or not
   */
  function is_main_umw_theme() {
	  return function_exists( 'umw_is_full_header' );
  }

  /**
   * Perform any actions that need to happen once the theme is initiated
   * This is where we set up all of the various insertions that occur in the top of the theme
   * @uses UMW_Online_Tools::is_main_umw_theme() to determine whether this site is using the main UMW theme
   */
  function after_setup_theme() {
    if ( ! function_exists( 'genesis' ) ) {
      return false;
    }

    if ( has_action( 'genesis_before_header', 'umw_do_help_section' ) ) {
      remove_action( 'genesis_before_header', 'umw_do_help_section' );
    }

    add_action( 'genesis_before', array( $this, 'do_toolbar' ), 2 );
	add_action( 'genesis_before', array( $this, 'do_header_bar' ), 5 );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	add_action( 'umw-main-header-bar', array( $this, 'do_audience_menu' ), 11 );

	add_action( 'umw-main-header-bar', array( $this, 'do_wordmark' ), 5 );
	if ( has_action( 'genesis_header', 'umw_do_global_header' ) ) {
		remove_action( 'genesis_header', 'umw_do_global_header' );
	}

	$this->get_options();

	if ( false === $this->is_main_umw_theme() ) {
		add_filter( 'body_class', array( $this, 'non_main_body_class' ) );

		if ( false === $this->options['global-bar'] ) {
			remove_action( 'genesis_before', array( $this, 'do_header_bar' ), 5 );
		}
	}

	remove_all_actions( 'umw_footer_nav' );
  }

  /**
   * Setup any style sheets and extraneous CSS we need
   */
  function enqueue_styles() {
	  wp_enqueue_script( 'umw-online-tools', plugins_url( '/scripts/umw-online-tools.js', dirname( __FILE__ ) ), array( 'jquery' ), $this->v, true );
	wp_register_style( 'umw-online-tools-font', plugins_url( '/images/icons/svg-online-tools/icon-font/style.css', dirname( __FILE__ ) ), array(), $this->v, 'all' );
    wp_enqueue_style( 'umw-online-tools', plugins_url( '/styles/umw-online-tools.css', dirname( __FILE__ ) ), array( 'umw-online-tools-font' ), $this->v, 'all' );
	#wp_add_inline_style( 'umw-online-tools', 'body > .umw-helpful-links { background: rgb( 77, 107, 139 ); color: #fff; } body > .umw-helpful-links a { color: #fff; }' );
	add_action( 'wp_print_styles', array( $this, 'do_header_bar_styles' ) );
  }

  /**
   * Set up the array of icons that go in the toolbar
   * @uses UMW_Online_Tools::$icons to store the array of icons
   * @uses apply_filters() to apply the 'umw-online-tools-icons' filter to the array, allowing other plugins to modify the icon list
   */
  function gather_icons() {
    $this->icons = apply_filters( 'umw-online-tools-icons', array(
      0 => array(
		'icon' => 'myumw',
		'link' => '//umw.edu/myumw', // PrettyLink
		'name' => 'myUMW',
	  ),
      1 => array(
        'icon' => 'banner',
        'link' => '//umw.edu/banner', // PrettyLink
        'name' => 'Banner',
      ),
      2 => array(
        'icon' => 'canvas',
        'link' => '//umw.edu/canvas', // PrettyLink
        'name' => 'Canvas',
      ),
      3 => array(
        'icon' => 'email',
        'link' => '//umw.edu/email', // PrettyLink
        'name' => 'Email',
      ),
      4 => array(
        'icon' => 'library',
        'link' => '//umw.edu/library', // Custom redirect?
        'name' => 'Library',
      ),
      5 => array(
        'icon' => 'eagleone',
        'link' => '//umw.edu/eagleone', // PrettyLink
        'name' => 'EagleOne',
      ),
      6 => array(
        'icon' => 'mytime',
        'link' => '//www.umw.edu/mytime/', // Redirected page
        'name' => 'MyTime',
      ),
      7 => array(
        'icon' => 'eagleeye',
        'link' => '//umw.edu/eagleeye', // PrettyLink
        'name' => 'EagleEye',
      ),
      8 => array(
        'icon' => 'password',
        'link' => '//umw.edu/password', // PrettyLink
        'name' => 'Passwords',
      ),
      9 => array(
        'icon' => 'directory',
        'link' => '//umw.edu/directory', // Standard link
        'name' => 'Directory',
      ),
      10 => array(
        'icon' => 'starfish',
        'link' => '//umw.edu/starfish', // PrettyLink
        'name' => 'Starfish',
      ),
      11 => array(
        'icon' => 'links',
        'link' => '//www.umw.edu/resources/', // Standard link
        'name' => 'Helpful Links',
      ),
    ) );
  }

  /**
   * Output the global toolbar
   */
  function do_toolbar() {
    $output = '';
    $format = '<li><a href="%1$s" class="%5$s">%4$s</a></li>';
    foreach ( $this->icons as $i ) {
      /*if ( ! stristr( '//', $i['icon'] ) ) {
        $i['icon'] = plugins_url( sprintf( 'images/icons/svg-online-tools/24px-white/umwicon-%1$s.png', $i['icon'] ), dirname( __FILE__ ) );
      }*/
      $output .= sprintf( $format, esc_url( $i['link'] ), esc_url( $i['icon'] ), $this->v, $i['name'], 'icon-umwicon-' . $i['icon'] );
    }
    printf( '<aside class="umw-helpful-links"><ul class="umw-tools" id="umw-online-tools">%s</ul><br style="clear:both;"/></aside>', $output );
  }

	/**
	 * Output the header bar
	 * @todo remove the reliance on the umw_is_full_header() function
	 */
	function do_header_bar() {
		if ( ! has_action( 'umw-main-header-bar' ) )
			return false;

		$c = array( 'umw-header-bar', 'no-global-header' );
		if ( function_exists( 'umw_is_full_header' ) && umw_is_full_header() ) {
			array_pop( $c );
		}
		echo '
<aside class="' . implode( ' ', $c ) . '">
	<div class="wrap">';
		do_action( 'umw-main-header-bar' );
		echo '
		<br class="desktop-clear"/>
	</div>
</aside>';
	}

	/**
	 * Output any additional styles that need to be applied by other plugins
	 * @uses do_action() to initiate the 'umw-main-header-bar-styles' action, allowing other plugins to hook into this
	 */
	function do_header_bar_styles() {
		do_action( 'umw-main-header-bar-styles' );
	}

	/**
	 * Output the wordmark logo in the global toolbar
	 */
	function do_wordmark() {
		if ( false === $this->options['global-bar'] || false === $this->options['wordmark'] )
			return;

		if ( function_exists( 'get_mnetwork_transient' ) ) {
			$logo = get_mnetwork_transient( 'umw-global-logo', false );
		} else {
			$logo = get_site_transient( 'umw-global-logo', false );
		}

		if ( false === $logo ) {
			/*$logo = get_bloginfo('stylesheet_directory') . '/images/logo_global.png';*/
			$logo = str_replace( '</svg>', sprintf( '<image src="%1$s" alt="%2$s" xlink:href=""/></svg>', plugins_url( '/images/umw-wordmark.png', dirname( __FILE__ ) ), __( 'University of Mary Washington' ) ), file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . '/images/umw-wordmark.svg' ) );
			if ( function_exists( 'set_mnetwork_transient' ) ) {
				set_mnetwork_transient( 'umw-global-logo', $logo, HOUR_IN_SECONDS );
			} else {
				set_site_transient( 'umw-global-logo', $logo, HOUR_IN_SECONDS );
			}
		}
?>
<a id="umw-global-logo" href="<?php $this->get_site_url( 1, true ); ?>" title="Home"><?php echo $logo ?></a>
<?php
	}

	/**
	 * Output the audience menu
	 */
	function do_audience_menu() {
		if ( false === $this->options['global-bar'] || false === $this->options['audience-menu'] )
			return;

		$h = gethostname();
		if ( stristr( $h, '.wtf' ) || stristr( $h, 'testumw.local' ) ) {
			$host = 'http://%2$s.umw.wtf%1$s';
		} else if ( stristr( $h, '.red' ) || stristr( $h, 'umw.local' ) ) {
			$host = 'http://%2$s.umw.red%1$s';
        } else if ( stristr( $h, '.wpengine.com' ) ) {
            $host = 'http://%2$s.umw.red%1$s';
		} else {
			$host = 'http://%2$s.umw.edu%1$s';
		}

		$menu_items = apply_filters( 'umw-global-audience-menu-items', array(
			'students' => array(
				'url' => sprintf( $host, '/students/', 'www' ), // Standard link
				'label' => __( 'Students' )
			),
			'faculty' => array(
				'url'   => sprintf( $host, '/in/', 'www' ), // Standard link
				'label' => __( 'Faculty &amp; Staff' )
			),
			'alumni' => array(
				'url'   => sprintf( $host, '/alumni/', 'www' ), // Redirected page
				'label' => __( 'Alumni' ),
			),
			'give'   => array(
				'url'   => sprintf( $host, '/give/', 'www' ), // Redirected page
				'label' => __( 'Give' ),
			),
		), $host );
?>
<ul class="umw-audience-menu">
<?php
		foreach ( $menu_items as $item ) {
?>
	<li><a href="<?php echo $item['url'] ?>"><?php echo $item['label'] ?></a></li>
<?php
		}
?>
	<li style="width: 0; height: 0; line-height: 0; font-size: 0; margin: 0; padding: 0; overflow: hidden; float: none; clear: both; display: block;"></li>
</ul>
<?php
	}

	/**
	 * Retrieve any options associated with this plugin
	 */
	function get_options() {
		$options = get_option( 'umw-toolbar-settings', array( 'global-bar' => false ) );
		if ( $this->is_main_umw_theme() ) {
			$options = apply_filters( 'umw-toolbar-default-settings-main', array(
				'global-bar' => true,
				'wordmark' => true,
				'audience-menu' => true
			) );
		}
		$this->options = $options;
		return;
	}

	/**
	 * Perform any actions that need to happen in admin areas
	 */
	function admin_init() {
		/* This site is using the standard UMW theme, so no need to add options right now */
		if ( $this->is_main_umw_theme() ) {
			return;
		}

		register_setting( 'general', 'umw-toolbar-settings', array( $this, 'sanitize_settings' ) );
		add_settings_section( 'umw-toolbar-settings-section', __( 'UMW Global Toolbar Settings' ), array( $this, 'settings_section' ), 'general' );
		$cbfields = apply_filters( 'umw-global-toolbar-settings-checkbox-fields', array(
			'global-bar'    => array(
				'id'    => 'umw-enable-global-bar',
				'title' => __( 'Global Menu Bar' ),
				'label' => __( 'Enable the global menu bar below the online tools bar?' ),
				'note'  => __( 'If this is disabled, none of the other options below will have any effect.' ),
			),
			'wordmark'      => array(
				'id'    => 'umw-enable-global-wordmark',
				'title' => __( 'Logo' ),
				'label' => __( 'Enable the wordmark logo in the global menu bar?' ),
			),
			'audience-menu' => array(
				'id'    => 'umw-enable-audience-menu',
				'title' => __( 'Audience Menu' ),
				'label' => __( 'Enable the global audience menu?' ),
			),
		) );

		foreach ( $cbfields as $name=>$field ) {
			$args = array(
				'id'    => $field['id'],
				'name'  => $name,
				'label' => $field['label']
			);
			if ( array_key_exists( 'note', $field ) && ! empty( $field['note'] ) ) {
				$args['note'] = $field['note'];
			}
			add_settings_field( $field['id'], $field['title'], array( $this, 'settings_field_checkbox' ), 'general', 'umw-toolbar-settings-section', $args );
		}
	}

	/**
	 * Output anything that needs to go at the head of our settings area
	 */
	function settings_section() {
		do_action( 'umw-online-tools-settings-section' );
		return;
	}

	/**
	 * Output a settings checkbox
	 * @param array $args an array containing the field ID, the label and possibly a note to be displayed below the checkbox
	 */
	function settings_field_checkbox( $args=array() ) {
		$vals = get_option( 'umw-toolbar-settings', array( $args['name'] => false ) );
?>
<p><input type="checkbox" name="umw-toolbar-settings[<?php echo $args['name'] ?>]" id="<?php echo $args['id'] ?>" value="1"<?php checked( $vals[$args['name']] ) ?>/>
	<label for="<?php echo $args['id'] ?>"><?php echo $args['label'] ?></label></p>
<?php
		if ( array_key_exists( 'note', $args ) ) {
?>
<p><em><?php echo $args['note'] ?></em></p>
<?php
		}
	}

	/**
	 * Sanitize our settings before they're added to the database
	 */
	function sanitize_settings( $input=array() ) {
		$output = array();
		$output['global-bar'] = isset( $input['global-bar'] );
		$output['wordmark'] = isset( $input['wordmark'] );
		$output['audience-menu'] = isset( $input['audience-menu'] );
		$output = apply_filters( 'validate-umw-global-toolbar-settings', $output, $input );
		return $output;
	}

	/**
	 * Add a class indicating that this site is not using the main UMW theme
	 */
	function non_main_body_class( $classes=array() ) {
		$classes[] = 'custom-umw-theme';
		return $classes;
	}
}

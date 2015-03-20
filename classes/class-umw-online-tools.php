<?php
/**
 * Implements the UMW Online Tools class
 */

if ( ! defined( 'ABSPATH' ) ) {
  die( 'You should not access this file directly' );
}

class UMW_Online_Tools {
  public $v = '0.2.35';
  public $icons = array();

  function __construct() {
    add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
    add_action( 'init', array( $this, 'gather_icons' ) );
  }

  function after_setup_theme() {
    if ( ! function_exists( 'genesis' ) ) {
      return false;
    }

    if ( has_action( 'genesis_before_header', 'umw_do_help_section' ) ) {
      remove_action( 'genesis_before_header', 'umw_do_help_section' );
    }

    add_action( 'genesis_before', array( $this, 'do_toolbar' ), 1 );
	add_action( 'genesis_before', array( $this, 'do_header_bar' ), 5 );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	add_action( 'umw-main-header-bar', array( $this, 'do_test_menu' ), 11 );
	
	add_action( 'umw-main-header-bar', array( $this, 'do_wordmark' ), 5 );
	if ( has_action( 'genesis_header', 'umw_do_global_header' ) ) {
		remove_action( 'genesis_header', 'umw_do_global_header' );
	}
	
	/*if ( function_exists( 'umw_is_full_header' ) && umw_is_full_header() ) {
		remove_action( 'umw-main-header-bar', array( $this, 'do_wordmark' ), 5 );
	}*/
	
	if ( ! function_exists( 'umw_is_full_header' ) ) {
		remove_action( 'genesis_before', array( $this, 'do_header_bar' ), 5 );
	}
  }

  function enqueue_styles() {
    wp_enqueue_style( 'umw-online-tools', plugins_url( 'umw-online-tools.css', dirname( __FILE__ ) ), array(), $this->v, 'all' );
	add_action( 'wp_print_styles', array( $this, 'do_header_bar_styles' ) );
  }

  function gather_icons() {
    $this->icons = apply_filters( 'umw-online-tools-icons', array(
      0 => array(
        'icon' => 'eaglenet',
        'link' => 'https://eaglenet.umw.edu/',
        'name' => 'EagleNet',
      ),
      1 => array(
        'icon' => 'banner',
        'link' => 'http://technology.umw.edu/hss/banner/',
        'name' => 'Banner',
      ),
      2 => array(
        'icon' => 'canvas',
        'link' => 'https://canvas.umw.edu/',
        'name' => 'Canvas',
      ),
      3 => array(
        'icon' => 'email',
        'link' => 'http://webmail.umw.edu/',
        'name' => 'Email',
      ),
      4 => array(
        'icon' => 'library',
        'link' => 'http://libraries.umw.edu/',
        'name' => 'Library',
      ),
      5 => array(
        'icon' => 'eagleone',
        'link' => 'https://eagleone-sp.blackboard.com/eaccounts',
        'name' => 'EagleOne',
      ),
      6 => array(
        'icon' => 'mytime',
        'link' => 'https://umw.kronos.net/wfc/navigator/logon',
        'name' => 'MyTime',
      ),
      7 => array(
        'icon' => 'eagleeye',
        'link' => 'http://eagleeye.umw.edu/',
        'name' => 'EagleEye',
      ),
      8 => array(
        'icon' => 'password',
        'link' => 'http://password.umw.edu/',
        'name' => 'Passwords',
      ),
      9 => array(
        'icon' => 'directory',
        'link' => 'http://umw.edu/directory',
        'name' => 'Directory',
      ),
      10 => array(
        'icon' => 'starfish',
        'link' => 'https://umw.starfishsolutions.com/starfish-ops/session/casLogin.html',
        'name' => 'Starfish',
      ),
      11 => array(
        'icon' => 'links',
        'link' => 'http://www.umw.edu/resources/',
        'name' => 'Helpful Links',
      ),
    ) );
  }

  function do_toolbar() {
    $output = '';
    $format = '<li><a href="%1$s"><img src="%2$s?v=%3$s" alt=""/>%4$s</a></li>';
    foreach ( $this->icons as $i ) {
      if ( ! stristr( '//', $i['icon'] ) ) {
        $i['icon'] = plugins_url( sprintf( 'images/icons/svg-online-tools/24px-blue/umwicon-%1$s.png', $i['icon'] ), dirname( __FILE__ ) );
      }
      $output .= sprintf( $format, esc_url( $i['link'] ), esc_url( $i['icon'] ), $this->v, $i['name'] );
    }
    printf( '<aside class="umw-helpful-links"><ul class="umw-tools">%s</ul><br style="clear:both;"/></aside>', $output );
  }
  
	/**
	 * Output the header bar
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
	</div>
</aside>';
	}
	
	function do_header_bar_styles() {
		do_action( 'umw-main-header-bar-styles' );
	}
	
	function do_wordmark() {
		$logo = get_mnetwork_transient( 'umw-global-logo', false );
		if ( false === $logo ) {
			/*$logo = get_bloginfo('stylesheet_directory') . '/images/logo_global.png';*/
			$logo = str_replace( '<svg ', '<svg id="umw-global-logo-img" ', file_get_contents( get_stylesheet_directory() . '/images/umw-linear-wordmark-optimized.svg' ) );
			set_mnetwork_transient( 'umw-global-logo', $logo, HOUR_IN_SECONDS );
		}
?>
<a id="umw-global-logo" href="<?php echo get_site_url(1); ?>" title="Home"><?php echo $logo ?></a>
<?php
	}
	
	function do_test_menu() {
		$h = gethostname();
		if ( stristr( $h, '.wtf' ) || stristr( $h, 'testumw.local' ) ) {
			$host = 'http://%2$s.umw.wtf%1$s';
		} else if ( stristr( $h, '.red' ) || stristr( $h, 'umw.local' ) ) {
			$host = 'http://%2$s.umw.red%1$s';
		} else {
			$host = 'http://%2$s.umw.edu%1$s';
		}
?>
<ul class="umw-audience-menu">
	<li><a href="<?php printf( $host, '/faculty/', 'www' ) ?>">Faculty &amp; Staff</a></li>
	<li><a href="<?php printf( $host, '/students/', 'www' ) ?>">Students</a></li>
	<li><a href="<?php printf( $host, '/', 'alumni' ) ?>">Alumni</a></li>
	<li><a href="<?php printf( $host, '/', 'giving' ) ?>">Give</a></li>
	<li style="width: 0; height: 0; line-height: 0; font-size: 0; margin: 0; padding: 0; overflow: hidden; float: none; clear: both; display: block;"></li>
</ul>
<?php
	}
}

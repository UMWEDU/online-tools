<?php
/**
 * Implements the UMW Online Tools class
 */

if ( ! defined( 'ABSPATH' ) ) {
  die( 'You should not access this file directly' );
}

class UMW_Online_Tools {
  public $v = '0.2.2';
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
			
		echo '
<aside class="umw-header-bar">
	<div class="wrap">';
		do_action( 'umw-main-header-bar' );
		echo '
	</div>
</aside>';
	}
	
	function do_header_bar_styles() {
		do_action( 'umw-main-header-bar-styles' );
	}
}

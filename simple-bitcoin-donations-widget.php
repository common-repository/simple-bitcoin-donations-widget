<?php

/*
Plugin Name: Simple Bitcoin donations widget
Plugin URI: https://profiles.wordpress.org/rynald0s
Description: This plugin adds a simple Bitcioin donation widget to your WordPress site. 
Author: rynald0s
Author URI: http:rynaldo.com
Version: 1.0
License: GPLv3 or later License
URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

  class simple_btc_donate_widget extends WP_Widget {

    function __construct() {
      parent::__construct(false, $name = 'Simple Bitcoin Donations'); 
    }

    function widget($args, $instance) { 
      extract( $args );
      $title           = apply_filters('widget_title', @$instance['title']);
      $textAbove = strip_tags(@$instance['btc_donate_above']);
      $bitcoinAddress = strip_tags(@$instance['btc_Address']);
      $makeLink = strip_tags(@$instance['btc_create_link']);

      if ( !preg_match('/^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$/', $bitcoinAddress) ) {
        echo "This is an invalid bitcoin address!";
        return;
      }
  
      function qr_code_for_bitcoin_donation($data, $width = 200, $height = 200, $charset = 'utf-8', $error = 'H'){

        $uri = 'https://chart.googleapis.com/chart?';
        $error = 'L|1';
        $query = array( 'cht' => 'qr', 'chs' => $width .'x'. $height, 'choe' => $charset, 'chld' => $error, 'chl' => $data );
        $uri = $uri .= http_build_query($query);

        return $uri;
      }

        echo $before_widget;

        echo "<p style='border: 1px solid #e0dadf; padding: 20px; margin: 2em 0 2em 0; text-align: left; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; text-align: center '>";

          if ( $title ) {
            echo $before_title . $title . $after_title; 
          } 

          if ( $textAbove ) {
            echo $textAbove; 
          } 

          if ( $bitcoinAddress ) {

            $qrLinkShort = "bitcoin:".$bitcoinAddress;

            $image = qr_code_for_bitcoin_donation( $qrLinkShort, 300, 300 );
            if ($makeLink == 'yes') {
              echo "<a href='$qrLinkShort' target='_blank'><img style='display: block; margin-left: auto; margin-right: auto' src='$image'/></a><br>";
              } else {
              echo "<img src='$image' style='display: block; margin-left: auto; margin-right: auto' />";
              echo "</p>";
            }
          }

        echo $after_widget;
    }
   
    function update($new_instance, $old_instance) {   
      $instance = $old_instance;
      $instance['title']          = strip_tags($new_instance['title']);
      $instance['btc_donate_above']      = strip_tags($new_instance['btc_donate_above']);
      $instance['btc_Address'] = strip_tags($new_instance['btc_Address']);
      $instance['btc_create_link']       = strip_tags($new_instance['btc_create_link']);
      return $instance;
    }
   
    function form($instance) {  


      $title          = esc_attr(@$instance['title']);
      $textAbove      = esc_attr(@$instance['btc_donate_above']);
      $bitcoinAddress = esc_attr(@$instance['btc_Address']);
      $makeLink       = esc_attr(@$instance['btc_create_link']);
      ?>
       <p>

        <label for="<?php echo $this->get_field_id('title'); ?>"><b><?php _e('Title:', 'simple-bitcoin-donate-widget'); ?></b></label> 
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /><br>
        <span class='helptext'><?php _e("The title of your widget.", 'simple-bitcoin-donate-widget'); ?></span>
      </p>
       <p>
        <label for="<?php echo $this->get_field_id('btc_donate_above'); ?>"><b><?php _e('Your custom message above the QR Code:', 'simple-bitcoin-donate-widget'); ?></b></label> 
        <input class="widefat" id="<?php echo $this->get_field_id('btc_donate_above'); ?>" name="<?php echo $this->get_field_name('btc_donate_above'); ?>" type="text" value="<?php echo $textAbove; ?>" /><br>
        <span class='helptext'><?php _e("This is a custom message to your site visitors asking for donations.", 'easy-bitcoin-donate-widget'); ?></span>
      </p>

       <p>
        <label for="<?php echo $this->get_field_id('btc_Address'); ?>"><b><?php _e('Bitcoin address:', 'simple-bitcoin-donate-widget'); ?></b></label> 
        <input class="widefat" id="<?php echo $this->get_field_id('btc_Address'); ?>" name="<?php echo $this->get_field_name('btc_Address'); ?>" type="text" value="<?php echo $bitcoinAddress; ?>" /><br>
        <span class='helptext'><?php _e("Your valid Bitcoin address. This is required to receive donations.", 'simple-bitcoin-donate-widget'); ?></span>
      </p>


       <p>
        <label for="<?php echo $this->get_field_id('btc_create_link'); ?>"><b><?php _e('Enable this to make the QR clickable:', 'simple-bitcoin-donate-widget'); ?></b></label><br> 
        <input class="widefat" id="<?php echo $this->get_field_id('btc_create_link'); ?>" name="<?php echo $this->get_field_name('btc_create_link'); ?>" type="checkbox" value="yes" <?php checked( $makeLink, 'yes' ); ?> /> 
        <span class='helptext'><?php _e("This is good for those on mobile devices with wallets.", 'simple-bitcoin-donate-widget'); ?></span>
      </p>

      <span class='helptext'><span style='color:black'><?php _e( "Please send me a Bitcoin donation if you like this widget"); ?></span></span><br></br>
      <span class='helptext'><span style='color:#96588a'><?php _e( "1BEsm8VMkYhSFJ92cvUYwxCtsfsB2rBfiG"); ?></span></span><br></br>

      <?php
    }
  } 

  function register_btc_donate_widget() {
  register_widget( 'simple_btc_donate_widget' );
}
add_action( 'widgets_init', 'register_btc_donate_widget' );

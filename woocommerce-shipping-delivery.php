<?php
/*
Plugin Name: WooCommerce Delivery Shipping Method
Plugin URI: http://www.garmantech.com/wordpress-plugins/woocommerce-extensions/delivery-shipping-method/
Description: Extends WooCommerce with a delivery shipping method.
Version: 1.0.1
Author: Garman Technical Services
Author URI: http://www.garmantech.com/wordpress-plugins/
License: GPLv2
*/

/*  Copyright 2011  Garman Technical Services  (email : contact@garmantech.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('plugins_loaded', 'woocommerce_delivery_shipping_init', 0);

function woocommerce_delivery_shipping_init() {

	if (!class_exists('woocommerce_shipping_method')) return;

		class delivery extends woocommerce_shipping_method {

			function __construct() { 
				$this->id 			= 'delivery';
				$this->method_title 	= __('Delivery', 'woothemes');
				$this->enabled		= get_option('woocommerce_delivery_enabled');
				$this->title 		= get_option('woocommerce_delivery_title');
				$this->fee 			= get_option('woocommerce_delivery_fee');
				$this->type 		= get_option('woocommerce_delivery_type');
				add_action('woocommerce_update_options_shipping_methods', array(&$this, 'process_admin_options'));
				add_option('woocommerce_delivery_availability', 'all');
				add_option('woocommerce_delivery_title', 'Delivery');
				add_option('woocommerce_delivery_fee', '5.00');
				add_option('woocommerce_delivery_type', 'fixed');
			} 

			 function calculate_shipping() {
				global $woocommerce;
				$_tax = &new woocommerce_tax();
				if ($this->type=='percent') 	$this->shipping_total 	= $woocommerce->cart->cart_contents_total * ($this->fee/100);
				if ($this->type=='fixed') 	$this->shipping_total 	= $this->fee;
				$this->shipping_tax = 0;
			}

			function admin_options() {
				global $woocommerce;
				?>
				<h3><?php _e('Delivery', 'woothemes'); ?></h3>
				<div style="position:fixed; top:25%; right:5px;"><a href="#" onClick="script: Zenbox.show(); return false;"><img src="https://apps.garmantech.com/files/support_right.png" /></a></div>
				<table class="form-table">
					<tr valign="top">
						<th scope="row" class="titledesc"><?php _e('Enable/disable', 'woothemes') ?></th>
						<td class="forminp">
							<fieldset><legend class="screen-reader-text"><span><?php _e('Enable/disable', 'woothemes') ?></span></legend>
									<label for="woocommerce_delivery_enabled>">
									<input name="woocommerce_delivery_enabled" id="woocommerce_delivery_enabled" type="checkbox" value="1" <?php checked(get_option('woocommerce_delivery_enabled'), 'yes'); ?> /> <?php _e('Enable Delivery', 'woothemes') ?></label><br>
								</fieldset>
						</td>
					    </tr>
					    <tr valign="top">
						<th scope="row" class="titledesc"><?php _e('Method Title', 'woothemes') ?></th>
						<td class="forminp">
							<input type="text" name="woocommerce_delivery_title" id="woocommerce_delivery_title" style="min-width:50px;" value="<?php if ($value = get_option('woocommerce_delivery_title')) echo $value; else echo 'Delivery'; ?>" /> <span class="description"><?php _e('This controls the title which the user sees during checkout.', 'woothemes') ?></span>
						</td>
					    </tr>
					    <tr valign="top">
						<th scope="row" class="titledesc"><?php _e('Delivery Fee', 'woothemes') ?></th>
						<td class="forminp">
							<input type="text" name="woocommerce_delivery_fee" id="woocommerce_delivery_fee" style="min-width:50px;" value="<?php if ($value = get_option('woocommerce_delivery_fee')) echo $value; else echo '5.00'; ?>" /> <span class="description"><?php _e('What fee would you like to charge your customers to deliver? Please use only dollar amounts, no percents.... yet!', 'woothemes') ?></span>
						</td>
					    </tr>
					    <tr valign="top">
						<th scope="row" class="titledesc"><?php _e('Fee Type', 'woothemes') ?></th>
						<td class="forminp">
							<select name="woocommerce_delivery_type">
								<option value="percent"<?php if (get_option('woocommerce_delivery_type')=='percent') echo 'selected="selected"'; ?>>Percent</option>
								<option value="fixed" <?php if (get_option('woocommerce_delivery_type')=='fixed') echo 'selected="selected"'; ?>>Fixed Value</option>
							</select>
							<span class="description"><?php _e('Do you want your fee to be a percentage of the total, or a fixed amount?', 'woothemes') ?></span>
						</td>
					    </tr>
				</table>
				<script type="text/javascript" src="//asset0.zendesk.com/external/zenbox/v2.3/zenbox.js"></script>
				<style type="text/css" media="screen, projection">
				  @import url(//asset0.zendesk.com/external/zenbox/v2.3/zenbox.css);
				</style>
				<script type="text/javascript">
				  if (typeof(Zenbox) !== "undefined") {
				    Zenbox.init({
				      dropboxID:	"20029372",
				      url:		"https://garmantech.zendesk.com",
				      tabID:		"support",
				      tabColor:	"black",
				      tabPosition:	"Right",
				      hide_tab:	true,
				    });
				  }
				</script>				<?php
			}

			function process_admin_options() {
				if(isset($_POST['woocommerce_delivery_enabled'])) update_option('woocommerce_delivery_enabled', 'yes'); else update_option('woocommerce_delivery_enabled', 'no');
				if(isset($_POST['woocommerce_delivery_title'])) update_option('woocommerce_delivery_title', woocommerce_clean($_POST['woocommerce_delivery_title'])); else delete_option('woocommerce_delivery_title');
				if(isset($_POST['woocommerce_delivery_fee'])) update_option('woocommerce_delivery_fee', woocommerce_clean($_POST['woocommerce_delivery_fee'])); else delete_option('woocommerce_delivery_fee');
				if(isset($_POST['woocommerce_delivery_type'])) update_option('woocommerce_delivery_type', woocommerce_clean($_POST['woocommerce_delivery_type'])); else delete_option('woocommerce_delivery_type');
			}

		}

	function add_delivery_method( $methods ) {
		$methods[] = 'delivery'; return $methods;
	}

	add_filter('woocommerce_shipping_methods', 'add_delivery_method' );

}
<?php

/*
--------------------------------------------------------------
Plugin Name: Tripadvisor Stream
Plugin URI: http://pasqualemangialavori.netsons.org/tripadvisor-stream-wordpress-plugin
Description: Tripadvisor Stream let you easily insert Tripadvisor stream review for restaurants and hotels. All you need is the the html activity address from Tripadvisor!You can edit the settings from Settings->Tripadvisor Stream
Version: 0.1.1
Author: Pasquale Mangialavori
Author URI: https://twitter.com/p_mangialavori
Twitter: @p_mangialavori
License: GPL2
----------------------------------------------------------------
*/

/*This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
_______________________________________________________________
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
_________________________________________________________________
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/



// set up menu and options page.
function tripadvisorsc_menu() {
	add_options_page( 'tripadvisorsc', 'Tripadvisor Stream', 'manage_options', 'tripadvisorsc', 'tripadvisorsc_options' );
}

function tripadvisorsc_register(){

	register_setting('tripadvisorsc_options', 'tripadvisor_url');
	register_setting('tripadvisorsc_options', 'tripadvisor_name');
	register_setting('tripadvisorsc_options', 'tripadvisor_id');
	register_setting('tripadvisorsc_options', 'tripadvisor_minrate');
	register_setting('tripadvisorsc_options', 'tripadvisor_limit');
	register_setting('tripadvisorsc_options', 'tripadvisor_buff');
	register_setting('tripadvisorsc_options', 'tripadvisor_lang');
	register_setting('tripadvisorsc_options', 'tripadvisor_sortby');
}

function tripadvisorsc_options() {

	if ( !current_user_can( 'manage_options' ) )  {

		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

	}

	?>

	<div class="wrap">

	<h2>TripAdvisor Stream</h2>

	<p><form method="post" action="options.php">	</p>	
	<?php

	settings_fields( 'tripadvisorsc_options' );
?>

<p>
	Add business url from TripAdvisor (exclude the http://tripadvisor.co.uk/ <br />for example 'Restaurant_Review-g3334523-d3568537-Reviews-Lo_Stuzzichino-Guamo_Province_of_Lucca_Tuscany.html)': 
	<input type="text" size="80" name="tripadvisor_url" value="<?php echo get_option('tripadvisor_url'); ?>" />
</p>

<p>
	Add business name to display at top of feed (ie Reviews of BUSINESS NAME ): 
	<input type="text" size="80" name="tripadvisor_name" value="<?php echo get_option('tripadvisor_name'); ?>" />
</p>

<p>
	Add Tripadvisor ID (the dxxxxxx number in your url - do not include d. Example:3568537): 
	<input type="text" size="80" name="tripadvisor_id" value="<?php echo get_option('tripadvisor_id'); ?>" />
</p>

<p>
	Insert minimum rate for the reviews to show. N.B. If the minrate is 4 but there's not 4 or 5 reviews nothing will be show <br> 
	<input type="number" min="0" max="4" name="tripadvisor_minrate" value="<?php echo get_option('tripadvisor_minrate'); ?>" />
</p>

<p>
	Insert the limit of reviews to show <br>
	<input type="number" min="0" max="10" name="tripadvisor_limit" value="<?php echo get_option('tripadvisor_limit'); ?>" />
</p>

					<p>
						Use buffering (if shortcode displays at top of content and not where you place it - only use if needed):  
						<select name='tripadvisor_buff'>
							<option value='No' <?php selected('No',get_option('tripadvisor_buff')); ?>>No</option>

							<option value='Yes' <?php selected('Yes', get_option('tripadvisor_buff')); ?>>Yes</option>
						</select>
					</p>			

<p>
	Select Language:
	<select name='tripadvisor_lang'>
		<option value='it' <?php selected('it',get_option('tripadvisor_lang')); ?>>it</option>
		<option value='en' <?php selected('en', get_option('tripadvisor_lang')); ?>>en</option>
	</select>
</p>

<p>
	Select Sorting (MOST RECENT | BEST):
	<select name='tripadvisor_sortby'>
		<option value='recent' <?php selected('recent',get_option('tripadvisor_sortby')); ?>>recent</option>
		<option value='best' <?php selected('best', get_option('tripadvisor_sortby')); ?>>best</option>
	</select>
</p>
 <?php

 submit_button();

	echo '</form>';
	echo '</div>';
}

function tripadvisorscode($atts) {

	$name = get_option('tripadvisor_name');
	$url = get_option('tripadvisor_url');
	$id = get_option('tripadvisor_id');
	$minrate = get_option('tripadvisor_minrate');
	$limit = get_option('tripadvisor_limit');
	$lang = get_option('tripadvisor_lang');
	$sortby = get_option('tripadvisor_sortby');

	extract( shortcode_atts( array( 

	    'name' => $name,
	    'url' => $url,
		'id' => $id,
		'minrate' => $minrate,
		'limit' => $limit,
		'lang' => $lang,
		'sortby' => $sortby 
	), $atts ) ); 

	$buffering = get_option('tripadvisor_buff');

	if ($buffering == "Yes") {

		ob_start();	

	}
?>

<h3 id="TA_Header"><?= get_option('tripadvisor_name') ?></h3>
<div class="overlayloader"></div>
<div id="TA_Container">

</div>


<?php

$buffering = get_option('tripadvisor_buff');

if ($buffering == "Yes") {

  return ob_get_clean();

  }
}

	
//SCRIPTS
function tripadvisor_plugin_scripts_style(){

// wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
	
	//registro il mio script
    wp_register_script('tripadvisor_script',plugin_dir_url( __FILE__ ).'js/tripadvisorstream.0.2.js',array('jquery'),'0.1',true);
 	
 	//localizzo il mio script con le opzioni
	$options = array('minrate' => isset($atts['minrate']) ? $atts['minrate'] : get_option('tripadvisor_minrate'), 
					  'limit' =>  isset($atts['limit']) ? $atts['limit'] : get_option('tripadvisor_limit'),
					  'id' => isset($atts['id']) ? $atts['id'] : get_option('tripadvisor_id'),
					  'lang' =>  isset($atts['lang']) ? $atts['lang'] : get_option('tripadvisor_lang'),
					  'sortby' =>  isset($atts['sortby']) ? $atts['sortby'] : get_option('tripadvisor_sortby')
					  );
	wp_localize_script( 'tripadvisor_script', 'options', $options );	
	
	wp_enqueue_script('tripadvisor_script');

    wp_register_style('tripadvisor_style',plugin_dir_url( __FILE__ ).'css/tripadvisor.css');
    wp_enqueue_style('tripadvisor_style');
}

add_action( 'admin_menu', 'tripadvisorsc_menu' );
add_action ('admin_init', 'tripadvisorsc_register');

add_action('wp_enqueue_scripts','tripadvisor_plugin_scripts_style');
add_shortcode('tripadvisorsc', 'tripadvisorscode');  
add_filter( 'widget_text', 'shortcode_unautop'); add_filter( 'widget_text', 'do_shortcode');
?>
<?php
/**
 * Plugin Name: Selva plim plim
 * Plugin URI: http://github.com/barrabinfc
 * Description: Notificate visitors of new published posts, for realtime blogging. SESC - na selva das cidades.
 * Version: 0.0.1
 * Author: bbfc
 * Author URI: http://github.com/barrabinfc
 * License: GPL2
 */

add_action( 'wp_enqueue_scripts' , 'selva_plimplim_enqueue_scripts' );
function selva_plimplim_enqueue_scripts() {
  // Import these files
  wp_enqueue_style( 'selva', plugins_url('/selva.css', __FILE__ ));

  wp_enqueue_script( 'selva', plugins_url('./selva.js', __FILE__) , array('jquery','wp-api'), '1.0', true );

  // Populate dictionary 'selva' for the scripts
  $settings = get_option( 'selva_settings' );
  wp_localize_script('selva', 'selva', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'bleep_url' => plugins_url('/audio/bell-square.mp3', __FILE__),
    'interval' => $settings['selva_interval'],
    'autoreload_enabled' => $settings['selva_autoreload_enabled'],
    'autoload_enabled' => $settings['selva_autoload_enabled'],
    'sound_enabled' => $settings['selva_sound_enabled'],
    'insert_top' => $settings['selva_insert_top']
  ));
}







/*
 * Settings page
 */
add_action( 'admin_menu', 'selva_add_admin_menu' );
add_action( 'admin_init', 'selva_settings_init' );


function selva_add_admin_menu(  ) {

	add_menu_page( 'Realtime Posts', 'Realtime Posts', 'manage_options', 'Notificações', 'selva_options_page' );

}


function selva_settings_init(  ) {

	register_setting( 'pluginPage', 'selva_settings' );

	add_settings_section(
		'selva_pluginPage_section',
		__( 'My section description', 'wordpress' ),
		'selva_settings_section_callback',
		'pluginPage'
	);

  add_settings_field(
		'selva_interval',
		__( 'Rhythm of updates', 'wordpress' ),
		'selva_interval_render',
		'pluginPage',
		'selva_pluginPage_section'
	);

  add_settings_field(
		'selva_sound_enabled',
		__( 'Ring bell on new post', 'wordpress' ),
		'selva_sound_render',
		'pluginPage',
		'selva_pluginPage_section'
	);


	add_settings_field(
		'selva_autoreload_enabled',
		__( 'Use old school auto-reload', 'wordpress' ),
		'selva_autoreload_render',
		'pluginPage',
		'selva_pluginPage_section'
	);

  add_settings_field(
    'selva_autoload_enabled',
    __( "Load automatically new posts", 'wordpress'),
    'selva_autoload_render',
    'pluginPage',
    'selva_pluginPage_section'
  );

  add_settings_field(
		'selva_insert_top',
		__( 'Insert new posts at the top', 'wordpress' ),
		'selva_insert_top_render',
		'pluginPage',
		'selva_pluginPage_section'
	);


}


function selva_interval_render(  ) {

	$options = get_option( 'selva_settings' );
	?>
	<input type='text' name='selva_settings[selva_interval]' value="<?php echo $options['selva_interval'] ?>">
	<?php

}

function selva_sound_render( ) {
  $options = get_option( 'selva_settings' );
	?>
	<input type='checkbox' name='selva_settings[selva_sound_enabled]' <?php checked( $options['selva_sound_enabled'], 1 ); ?> value='1'>
	<?php

}


function selva_autoreload_render(  ) {

	$options = get_option( 'selva_settings' );
	?>
	<input type='checkbox' name='selva_settings[selva_autoreload_enabled]' <?php checked( $options['selva_autoreload_enabled'], 1 ); ?> value='1'>
	<?php

}

function selva_autoload_render( ) {

  $options = get_option( 'selva_settings' );
	?>
	<input type='checkbox' name='selva_settings[selva_autoload_enabled]' <?php checked( $options['selva_autoload_enabled'], 1 ); ?> value='1'>
	<?php

}

function selva_insert_top_render( ) {

  $options = get_option( 'selva_settings' );
	?>
	<input type='checkbox' name='selva_settings[selva_insert_top]' <?php checked( $options['selva_insert_top'], 1 ); ?> value='1'>
	<?php

}


function selva_radio_field_2_render(  ) {

	$options = get_option( 'selva_settings' );
	?>
	<input type='radio' name='selva_settings[selva_radio_field_2]' <?php checked( $options['selva_radio_field_2'], 1 ); ?> value='1'>
	<?php

}


function selva_select_field_3_render(  ) {

	$options = get_option( 'selva_settings' );
	?>
	<select name='selva_settings[selva_select_field_3]'>
		<option value='1' <?php selected( $options['selva_select_field_3'], 1 ); ?>>Option 1</option>
		<option value='2' <?php selected( $options['selva_select_field_3'], 2 ); ?>>Option 2</option>
	</select>

<?php

}


function selva_settings_section_callback(  ) {

	echo __( 'Configure como os visitantes vão receber notificações de novos posts', 'wordpress' );

}


function selva_options_page(  ) {

	?>
	<form action='options.php' method='post'>

		<h2>Realtime Posts : Notifiçações </h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}

?>

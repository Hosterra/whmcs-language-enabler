<?php

if ( ! defined( 'WHMCS' ) ) {
    die( 'This file cannot be accessed directly' );
}

use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\LanguageEnabler\Admin\AdminDispatcher;

/**
 * Define addon module configuration parameters.
 *
 * @return array
 */
function language_enabler_config()
{
    return array(
        'name'          => 'Language Enabler',
        'description'   => 'This module allows to enable or disable available languages.',
        'author'        => 'Hosterra',
        'language'      => 'english',
        'version'       => '1.0.0',
        'fields'        => []
    );
}

/**
 * Activate.
 *
 * @return array Optional success/failure message
 */
function language_enabler_activate()
{
	$LANG = $vars['_lang'];

	try {
		if ( ! Capsule::schema()->hasTable( 'mod_language_enabler' ) ) {
			Capsule::schema()->create( 'mod_language_enabler', function ( $table ) {
				$table->increments( 'id' );
				$table->json( 'enabled' )->nullable();
            });
            
            Capsule::table( 'mod_language_enabler' )->insert([
                'enabled' => json_encode( array() ),
            ]);
		}
	} catch ( Exception $e ) {
		return [
			'status'        => 'error',
			'description'   => 'Cannot create table! (' . $e->getMessage() , ')'
		];
    }

	return [
		'status'        => 'success',
		'description'   => 'The module is activated successfully.'
	];
}

/**
 * Deactivate.
 *
 * @return array Optional success/failure message
 */
function language_enabler_deactivate()
{
	try {
        Capsule::schema()->dropIfExists( 'mod_language_enabler' );

		return [
			'status'        => 'success',
			'description'   => 'Module deactivated successfully!'
		];
	}
	catch ( Exception $e ) {
		return [
			'status'        => 'error',
			'description'   => 'Unable to drop table! (' . $e->getMessage() .')'
		];
	}
}

/**
 * Admin Area Output.
 *
 * @return string
 */
function language_enabler_output( $vars )
{
    $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';

    $dispatcher = new AdminDispatcher();

    $response = $dispatcher->dispatch( $action, $vars );

    echo $response;
}

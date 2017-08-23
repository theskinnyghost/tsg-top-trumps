<?php

namespace TSG\WordPress\Plugin;

class Top_Trumps {
	/**
	 * Custom post type name
	 */
	const CPT_NAME = 'fttptr_card';

	/**
	 * Custom fields prefix
	 */
	const FIELDS_PREFIX = '_ftlltp_';

	/**
	 * Return custom meta fields attributes
	 */
	public static function get_attributes()
	{
		return array(
			self::FIELDS_PREFIX . 'height'    => array(
				'label'         => 'Height (cm)',
				'description'   => __( "Insert player's height value in cm.", 'tsg_top_trumps' ),
			),
			self::FIELDS_PREFIX . 'apps'      => array(
				'label'         => 'Apps',
				'description'   => __( 'Insert the number of appearances for the player.', 'tsg_top_trumps' ),
			),
			self::FIELDS_PREFIX . 'goals'     => array(
				'label'         => 'Goals',
				'description'   => __( 'Insert the numbers of goals scored by the player.', 'tsg_top_trumps' ),
			),
			self::FIELDS_PREFIX . 'trophies'  => array(
				'label'         => 'Trophies',
				'description'   => __( 'Insert the number of trophies won by the player.', 'tsg_top_trumps' ),
			),
			self::FIELDS_PREFIX . 'yob'       => array(
				'label'         => 'Year of Birth',
				'description'   => __( 'Insert a date of birth for the player.', 'tsg_top_trumps' ),
			),
			self::FIELDS_PREFIX . 'estvl'     => array(
				'label'         => "Est'd value",
				'description'   => __( 'Insert the estimated value for the player.', 'tsg_top_trumps' ),
			),
		);
	}

	/**
	 * Register custom post type
	 */
	public function register_post_type()
	{
		$labels = array(
			'name'               => _x( 'Cards', 'Post Type General Name', 'tsg_top_trumps' ),
			'singular_name'      => _x( 'Card', 'Post Type Singular Name', 'tsg_top_trumps' ),
			'menu_name'          => __( 'Cards', 'tsg_top_trumps' ),
			'name_admin_bar'     => __( 'Card', 'tsg_top_trumps' ),
			'all_items'          => __( 'All Cards', 'tsg_top_trumps' ),
			'add_new_item'       => __( 'Add New Card', 'tsg_top_trumps' ),
			'new_item'           => __( 'New Card', 'tsg_top_trumps' ),
			'edit_item'          => __( 'Edit Card', 'tsg_top_trumps' ),
			'update_item'        => __( 'Update Card', 'tsg_top_trumps' ),
			'view_item'          => __( 'View Card', 'tsg_top_trumps' ),
			'view_items'         => __( 'View Cards', 'tsg_top_trumps' ),
			'search_items'       => __( 'Search Card', 'tsg_top_trumps' ),
			'not_found'          => __( 'No cards found', 'tsg_top_trumps' ),
			'not_found_in_trash' => __( 'No cards found in Trash', 'tsg_top_trumps' ),
		);

		$args = array(
			'label'               => __( 'Card', 'tsg_top_trumps' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-index-card',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => false,
		);

		register_post_type( self::CPT_NAME, $args );
	}

	/**
	 * Add our metabox to our CPT screen
	 *
	 */
	public function add_metabox()
	{
		add_meta_box(
			'fttp_metabox',
			__( 'Card Details', 'tsg_top_trumps' ),
			[ $this, 'draw_metabox' ],
			self::CPT_NAME,
			'normal',
			'high'
		);
	}

	/**
	 * Print HTML for the metabox
	 *
	 */
	public function draw_metabox( $post )
	{
		wp_nonce_field( FTLLTP_NONCE_NAME, FTLLTP_NONCE_ACTION );
		?>
        <table class="form-table">
            <tbody>
			<?php foreach( self::get_attributes() as $field_name => $fields ) :
				$value = get_post_meta( $post->ID, $field_name, true );
				?>
                <tr>
                    <th scope="row">
                        <label for="<?php echo $field_name; ?>"><?php echo $fields['label']; ?></label>
                    </th>
                    <td>
                        <input
                                class="code large-text"
                                type="text"
                                name="<?php echo $field_name; ?>"
                                id="<?php echo $field_name; ?>"
                                value="<?php echo $value; ?>">
                        <p class="description"><?php echo $fields['description']; ?></p>
                    </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
		<?php
	}

	/**
	 * Sanitize and save custom fields to DB when form is submitted
	 *
	 */
	public function save_post( $post_ID )
	{
		// Check if our nonce is set and verify that the nonce is valid.
		if ( ! isset( $_POST[ FTLLTP_NONCE_ACTION ] )
		     || ! wp_verify_nonce( $_POST[ FTLLTP_NONCE_ACTION ], FTLLTP_NONCE_NAME ) ) {
			return $post_ID;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_ID;
		}

		/**
		 * If this is an Ajax request, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return $post_ID;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_ID ) ) {
			return $post_ID;
		}

		// Get all custom fields keys
		$fields = array_keys( self::get_attributes() );

		// Sanitize field and save it to DB
		foreach( $fields as $field ) {
			$data = absint( $_POST[ $field ] );

			update_post_meta( $post_ID, $field, $data );
		}
	}
}
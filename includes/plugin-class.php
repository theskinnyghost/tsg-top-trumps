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
	 * Custom endpoint namespace
	 */
	const REST_ENDPOINT_NAMESPACE = 'top_trumps/';

	/**
	 * Custom endpoint name
	 */
	const REST_ENDPOINT_NAME = 'cards/';

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

	/**
	 * Add our new route to the WP REST API
	 */
	public function register_custom_endpoint()
	{
		$args = array(
			'methods' => 'GET',
			'callback' => array( $this, 'get_cards_api' ),
		);

		// Add our custom route to the WP REST API
		register_rest_route( self::REST_ENDPOINT_NAMESPACE, self::REST_ENDPOINT_NAME, $args );
	}

	/**
	 * Callback for WP REST API custom endpoint registration
	 */
	public function get_cards_api( \WP_REST_Request $request )
	{
		// Prepare the returnable array
		$json_return = array();

		// Make WP_Query call
		$args = array(
			'post_type'      => self::CPT_NAME,
			'posts_per_page' => 2,
			'orderby'        => 'rand',
		);
		$query = new \WP_Query( $args );

		// If there are posts, build the return array
		if( $query->have_posts() ) :
			while( $query->have_posts() ) : $query->the_post();
				// Prepare an array for card attributes
				$attributes = array();

				// Push all the attributes into the array
				foreach( self::get_attributes() as $key => $entries ) {
					$value = get_post_meta( get_the_ID(), $key, true );
					$attributes[] = array(
						'key'   => $key,
						'label' => $entries['label'],
						'value' => absint( $value )
					);
				}

				// Build the return array
				$json_return[] = array(
					'id'          => get_the_ID(),
					'name'        => get_the_title(),
					'image'       => get_the_post_thumbnail_url( get_the_ID(), 'full' ),
					'attributes'  => $attributes,
					'description' => get_the_content()
				);
			endwhile;
		endif;

		// Return the required information
		return $json_return;
	}

	/**
	 * Show our custom template for the game page
	 */
	public function view_plugin_template( $template )
	{
		// Get global post
		global $post;

		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}

		// Return our template if the post slug is equal to game
		if( "game" === $post->post_name ) {

			$file = plugin_dir_path( dirname( __FILE__ ) ) . "views/template-top-trumps.php";

			// Just to be safe, we check if the file exist first
			if ( file_exists( $file ) ) {
				return $file;
			} else {
				return $template;
			}
		}

		return $template;
	}

	/**
	 * Enqueue custom scripts and styles
	 */
	public function enqueue_scripts()
	{
		wp_register_style( '_ftlltp_style', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/top-trumps.css' );
		wp_register_script(
			'_ftlltp_knockout',
			plugin_dir_url( dirname( __FILE__ ) ) . 'assets/knockout.js',
			false,
			false,
			true
		);
		wp_register_script(
			'_ftlltp_script',
			plugin_dir_url( dirname( __FILE__ ) ) . 'assets/top-trumps.js',
			[ 'jquery', '_ftlltp_knockout' ],
			false,
			true
		);

		wp_deregister_style( 'twentyseventeen-style' );
		wp_enqueue_style( '_ftlltp_style' );
		wp_enqueue_script( '_ftlltp_script' );
	}
}
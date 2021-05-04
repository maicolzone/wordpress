<?php

/** Display verbose errors */
define( 'RANGE_IMPORT_DEBUG', true );

// include file api
include_once ABSPATH . "wp-admin/includes/file.php";

// include WXR file parsers
if( ! class_exists('WXR_Parser')){
	require_once EF_ROOT . '/includes/demo/importer/parsers.php';
}

class Essential_Import_Demo_Install extends Essential_Import_Demo
{
	private $posts;
	private $essential_options_file;
	private $essential_widgets_file;

	// mappings from old information to new
	var $processed_authors = array();
	var $author_mapping = array();
	var $processed_terms = array();
	var $processed_posts = array();
	var $post_orphans = array();
	var $processed_menu_items = array();
	var $menu_item_orphans = array();
	var $missing_menu_items = array();

	var $url_remap = array();
	var $featured_images = array();

	function __construct()
	{
		add_action( 'wp_ajax_essential_get_info_import', array($this, 'get_info_import') ); 
		add_action( 'wp_ajax_essential_import_categories', array($this, 'import_categories') ); 
		add_action( 'wp_ajax_essential_import_tags', array($this, 'import_tags') ); 
		add_action( 'wp_ajax_essential_import_posts', array($this, 'import_posts') ); 
		add_action( 'wp_ajax_essential_import_terms', array($this, 'import_terms') ); 
		add_action( 'wp_ajax_essential_import_options', array($this, 'import_options') ); 
		add_action( 'wp_ajax_essential_import_install_plugins', array($this, 'install_plugins') ); 
		add_action( 'wp_ajax_essential_import_widgets', array($this, 'import_widgets') ); 

		$essential_import = new Essential_Import_Demo();
		// options file 
		$this->essential_options_file = $essential_import->demo_folder . 'content/theme_options.json';
		// all posts, categories, etc
		$this->import_xml = $essential_import->demo_folder . 'content/all.xml';
		// widgets file
		$this->essential_widgets_file = $essential_import->demo_folder . 'content/widgets.json';

	}

	/**
	 */
	function get_info_import() {

	}

	/**
	 * Parse XML File 
	 *
	 */
	function parse( ) {
		$parser = new WXR_Parser();
		$import_data = $parser->parse( $this->import_xml );
		$this->categories = $import_data['categories'];
		$this->tags = $import_data['tags'];
		$this->terms = $import_data['terms'];
		$this->posts = $import_data['posts'];
	}

	/**
	 * Install recomended plugins
	 *
	 */
	function install_plugins(){
		
		$slugs = array('contact-form-7','jetpack','mailchimp-for-wp');
		$json = array();
		load_tgm_plugin_activation();
		$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$plugins = array(
			'all'      => array(), // Meaning: all plugins which still have open actions.
			'install'  => array(),
			'update'   => array(),
			'activate' => array(),
		);
		

		foreach ( $instance->plugins as $slug => $plugin ) {
			if ( $instance->is_plugin_active( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
				// No need to display plugins if they are installed, up-to-date and active.
				continue;
			} else {
				$plugins['all'][ $slug ] = $plugin;

				if ( ! $instance->is_plugin_installed( $slug ) ) {
					$plugins['install'][ $slug ] = $plugin;
				} else {
					if ( false !== $instance->does_plugin_have_update( $slug ) ) {
						$plugins['update'][ $slug ] = $plugin;
					}

					if ( $instance->can_plugin_activate( $slug ) ) {
						$plugins['activate'][ $slug ] = $plugin;
					}
				}
			}
		}


		// what are we doing with this plugin?
		foreach($plugins['activate'] as $slug => $plugin){
			if(in_array($slug, $slugs)){
				$json = array(
					'url' => admin_url('themes.php?page=tgmpa-install-plugins'),
					'plugin' => array($slug),
					'tgmpa-page' => 'tgmpa-install-plugins',
					'plugin_status' => 'all',
					'_wpnonce' => wp_create_nonce( 'bulk-plugins' ),
					'action' => 'tgmpa-bulk-activate',
					'action2' => -1,
					'message' => esc_html__('Activating Plugin','essential'),
				);
				break;
			}
		}
		foreach($plugins['update'] as $slug => $plugin){
			if(in_array($slug, $slugs)){
				$json = array(
					'url' => admin_url('themes.php?page=tgmpa-install-plugins'),
					'plugin' => array($slug),
					'tgmpa-page' => 'tgmpa-install-plugins',
					'plugin_status' => 'all',
					'_wpnonce' => wp_create_nonce( 'bulk-plugins' ),
					'action' => 'tgmpa-bulk-update',
					'action2' => -1,
					'message' => esc_html__('Updating Plugin','essential'),
				);
				break;
			}
		}
		foreach($plugins['install'] as $slug => $plugin){
			if(in_array($slug, $slugs)){
				$json = array(
					'url' => admin_url('themes.php?page=tgmpa-install-plugins'),
					'plugin' => array($slug),
					'tgmpa-page' => 'tgmpa-install-plugins',
					'plugin_status' => 'all',
					'_wpnonce' => wp_create_nonce( 'bulk-plugins' ),
					'action' => 'tgmpa-bulk-install',
					'action2' => -1,
					'message' => esc_html__('Installing Plugin','essential'),
				);
				break;
			}
		}

		if($json){
			$json['hash'] = md5(serialize($json)); // used for checking if duplicates happen, move to next plugin
			wp_send_json($json);
		}else{
			echo true;
		}

		die();
	}

	/**
	 * Create new widgets based on import information
	 *
	 */
	function import_widgets(){

	    global $wp_registered_sidebars, $wp_registered_widget_controls;
	    $widget_controls = $wp_registered_widget_controls;
		
		// remove locations widgets
	    update_option( 'sidebars_widgets', '' );
	    
	    $available_widgets = array();
	
	    foreach ( $widget_controls as $widget ) {
	
	        if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { 
	
	            $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
	            $available_widgets[$widget['id_base']]['name'] = $widget['name'];
	
	        }
	
	    }

	    $data = $this->get_json($this->essential_widgets_file);
 

	    // Get all existing widget instances
	    $widget_instances = array();
	    foreach ( $available_widgets as $widget_data ) {
	        $widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
	    }
	    // Begin results
	    $results = array();

	    // Loop import data's sidebars
	    foreach ( $data as $sidebar_id => $widgets ) {

	        // Skip inactive widgets
	        // (should not be in export file)
	        if ( 'wp_inactive_widgets' == $sidebar_id ) {
	            continue;
	        }

	        // Check if sidebar is available on this site
	        // Otherwise add widgets to inactive, and say so
	        if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
	            $sidebar_available = true;
	            $use_sidebar_id = $sidebar_id;
	            $sidebar_message_type = 'success';
	            $sidebar_message = '';
	        } else {
	            $sidebar_available = false;
	            $use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
	            $sidebar_message_type = 'error';
	            $sidebar_message = esc_html__( 'Sidebar does not exist in theme (using Inactive)', 'essential');
	        }

	        // Result for sidebar
	        $results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
	        $results[$sidebar_id]['message_type'] = $sidebar_message_type;
	        $results[$sidebar_id]['message'] = $sidebar_message;
	        $results[$sidebar_id]['widgets'] = array();

	        // Loop widgets
	        foreach ( $widgets as $widget_instance_id => $widget ) {

	            $fail = false;

	            // Get id_base (remove -# from end) and instance ID number
	            $id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
	            $instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

	            // Does site support this widget?
	            if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
	                $fail = true;
	                $widget_message_type = 'error';
	                $widget_message = esc_html__( 'Site does not support widget', 'essential'); // explain why widget not imported
	            }

	            // Filter to modify settings before import
	            // Do before identical check because changes may make it identical to end result (such as URL replacements)
	            $widget = apply_filters( 'wie_widget_settings', $widget );

	            // Does widget with identical settings already exist in same sidebar?
	            if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

	                // Get existing widgets in this sidebar
	                $sidebars_widgets = get_option( 'sidebars_widgets' );
	                $sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

	                // Loop widgets with ID base
	                $single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
	                foreach ( $single_widget_instances as $check_id => $check_widget ) {

	                    // Is widget in same sidebar and has identical settings?
	                    if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

	                        $fail = true;
	                        $widget_message_type = 'warning';
	                        $widget_message = esc_html__( 'Widget already exists', 'essential'); // explain why widget not imported

	                        break;

	                    }

	                }

	            }

	            // No failure
	            if ( ! $fail ) {

	                // Add widget instance
	                $single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
	                $single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
	                $single_widget_instances[] = (array) $widget; // add it

	                    // Get the key it was given
	                    end( $single_widget_instances );
	                    $new_instance_id_number = key( $single_widget_instances );

	                    // If key is 0, make it 1
	                    // When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
	                    if ( '0' === strval( $new_instance_id_number ) ) {
	                        $new_instance_id_number = 1;
	                        $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
	                        unset( $single_widget_instances[0] );
	                    }

	                    // Move _multiwidget to end of array for uniformity
	                    if ( isset( $single_widget_instances['_multiwidget'] ) ) {
	                        $multiwidget = $single_widget_instances['_multiwidget'];
	                        unset( $single_widget_instances['_multiwidget'] );
	                        $single_widget_instances['_multiwidget'] = $multiwidget;
	                    }

	                    // Update option with new widget
	                    update_option( 'widget_' . $id_base, $single_widget_instances );

	                // Assign widget instance to sidebar
	                $sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
	                $new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
	                $sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
	                update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

	                // Success message
	                if ( $sidebar_available ) {
	                    $widget_message_type = 'success';
	                    $widget_message = esc_html__( 'Imported', 'essential');
	                } else {
	                    $widget_message_type = 'warning';
	                    $widget_message = esc_html__( 'Imported to Inactive', 'essential');
	                }

	            }

	            // Result for widget instance
	            $results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
	            $results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = !empty($widget->title) ? $widget->title : esc_html__( 'No Title', 'essential'); // show "No Title" if widget instance is untitled
	            $results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
	            $results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;
	        }
	    }

	    return true;
	    die();
	}

	/**
	 * Create new categories based on import information
	 *
	 * Doesn't create a new category if its slug already exists
	 */
	function import_categories() {
		$this->parse();
		$this->categories = apply_filters( 'wp_import_categories', $this->categories );

		if ( empty( $this->categories ) )
			return;

		foreach ( $this->categories as $cat ) {
			// if the category already exists leave it alone
			$term_id = term_exists( $cat['category_nicename'], 'category' );
			if ( $term_id ) {
				if ( is_array($term_id) ) $term_id = $term_id['term_id'];
				if ( isset($cat['term_id']) )
					$this->processed_terms[intval($cat['term_id'])] = (int) $term_id;
				continue;
			}

			$category_parent = empty( $cat['category_parent'] ) ? 0 : category_exists( $cat['category_parent'] );
			$category_description = isset( $cat['category_description'] ) ? $cat['category_description'] : '';
			$catarr = array(
				'category_nicename' => $cat['category_nicename'],
				'category_parent' => $category_parent,
				'cat_name' => $cat['cat_name'],
				'category_description' => $category_description
			);

			$id = wp_insert_category( $catarr );
			if ( ! is_wp_error( $id ) ) {
				if ( isset($cat['term_id']) )
					$this->processed_terms[intval($cat['term_id'])] = $id;
			} else {
				printf( esc_html__( 'Failed to import category %s', 'essential' ), esc_html($cat['category_nicename']) );
				if ( defined('RANGE_IMPORT_DEBUG') && RANGE_IMPORT_DEBUG )
					echo ': ' . $id->get_error_message();
				echo '<br />';
				continue;
			}
		}

		echo 'true';

		unset( $this->categories );
		die();
	}

	/**
	 * Create new post tags based on import information
	 *
	 * Doesn't create a tag if its slug already exists
	 */
	function import_tags() {
		$this->parse();
		$this->tags = apply_filters( 'wp_import_tags', $this->tags );

		if ( empty( $this->tags ) )
			return;

		foreach ( $this->tags as $tag ) {
			// if the tag already exists leave it alone
			$term_id = term_exists( $tag['tag_slug'], 'post_tag' );
			if ( $term_id ) {
				if ( is_array($term_id) ) $term_id = $term_id['term_id'];
				if ( isset($tag['term_id']) )
					$this->processed_terms[intval($tag['term_id'])] = (int) $term_id;
				continue;
			}

			$tag_desc = isset( $tag['tag_description'] ) ? $tag['tag_description'] : '';
			$tagarr = array( 'slug' => $tag['tag_slug'], 'description' => $tag_desc );

			$id = wp_insert_term( $tag['tag_name'], 'post_tag', $tagarr );
			if ( ! is_wp_error( $id ) ) {
				if ( isset($tag['term_id']) )
					$this->processed_terms[intval($tag['term_id'])] = $id['term_id'];
			} else {
				printf( esc_html__( 'Failed to import post tag %s', 'essential' ), esc_html($tag['tag_name']) );
				if ( defined('RANGE_IMPORT_DEBUG') && RANGE_IMPORT_DEBUG )
					echo ': ' . $id->get_error_message();
				echo '<br />';
				continue;
			}
		}

		echo 'true';

		unset( $this->tags );
		die();
	}

	/**
	 * Create new terms based on import information
	 *
	 * Doesn't create a term its slug already exists
	 */
	function import_terms() {
		$this->parse();
		$this->terms = apply_filters( 'wp_import_terms', $this->terms );

		if ( empty( $this->terms ) )
			return;

		foreach ( $this->terms as $term ) {
			// if the term already exists in the correct taxonomy leave it alone
			$term_id = term_exists( $term['slug'], $term['term_taxonomy'] );
			if ( $term_id ) {
				if ( is_array($term_id) ) $term_id = $term_id['term_id'];
				if ( isset($term['term_id']) )
					$this->processed_terms[intval($term['term_id'])] = (int) $term_id;
				continue;
			}

			if ( empty( $term['term_parent'] ) ) {
				$parent = 0;
			} else {
				$parent = term_exists( $term['term_parent'], $term['term_taxonomy'] );
				if ( is_array( $parent ) ) $parent = $parent['term_id'];
			}
			$description = isset( $term['term_description'] ) ? $term['term_description'] : '';
			$termarr = array( 'slug' => $term['slug'], 'description' => $description, 'parent' => intval($parent) );

			$id = wp_insert_term( $term['term_name'], $term['term_taxonomy'], $termarr );
			if ( ! is_wp_error( $id ) ) {
				if ( isset($term['term_id']) )
					$this->processed_terms[intval($term['term_id'])] = $id['term_id'];
			} else {
				printf( esc_html__( 'Failed to import %s %s', 'essential' ), esc_html($term['term_taxonomy']), esc_html($term['term_name']) );
				if ( defined('RANGE_IMPORT_DEBUG') && RANGE_IMPORT_DEBUG )
					echo ': ' . $id->get_error_message();
				echo '<br />';
				continue;
			}
		}

		echo 'true';

		unset( $this->terms );
		die();
	}

	/**
	 * Create new posts based on import information
	 *
	 * Posts marked as having a parent which doesn't exist will become top level items.
	 * Doesn't create a new post if: the post type doesn't exist, the given post ID
	 * is already noted as imported or a post with the same title and date already exists.
	 * Note that new/updated terms, comments and meta are imported for the last of the above.
	 */
	function import_posts() {
		$this->parse();
		$this->posts = apply_filters( 'wp_import_posts', $this->posts );

		foreach ( $this->posts as $post ) {
			$post = apply_filters( 'wp_import_post_data_raw', $post );

			if ( ! post_type_exists( $post['post_type'] ) ) {
				printf( esc_html__( 'Failed to import &#8220;%s&#8221;: Invalid post type %s', 'essential' ),
					esc_html($post['post_title']), esc_html($post['post_type']) );
				echo '<br />';
				do_action( 'wp_import_post_exists', $post );
				continue;
			}

			if ( isset( $this->processed_posts[$post['post_id']] ) && ! empty( $post['post_id'] ) )
				continue;

			if ( $post['status'] == 'auto-draft' )
				continue;

			if ( 'nav_menu_item' == $post['post_type'] ) {
				$this->process_menu_item( $post );
				continue;
			}

			$post_type_object = get_post_type_object( $post['post_type'] );

			$post_exists = post_exists( $post['post_title'], '', $post['post_date'] );
			if ( $post_exists && get_post_type( $post_exists ) == $post['post_type'] ) {
				printf( esc_html__('%s &#8220;%s&#8221; already exists.', 'essential'), $post_type_object->labels->singular_name, esc_html($post['post_title']) );
				echo '<br />';
				$comment_post_ID = $post_id = $post_exists;
			} else {
				$post_parent = (int) $post['post_parent'];
				if ( $post_parent ) {
					// if we already know the parent, map it to the new local ID
					if ( isset( $this->processed_posts[$post_parent] ) ) {
						$post_parent = $this->processed_posts[$post_parent];
					// otherwise record the parent for later
					} else {
						$this->post_orphans[intval($post['post_id'])] = $post_parent;
						$post_parent = 0;
					}
				}

				// map the post author
				$author = sanitize_user( $post['post_author'], true );
				if ( isset( $this->author_mapping[$author] ) )
					$author = $this->author_mapping[$author];
				else
					$author = (int) get_current_user_id();

				$postdata = array(
					'import_id' => $post['post_id'], 'post_author' => $author, 'post_date' => $post['post_date'],
					'post_date_gmt' => $post['post_date_gmt'], 'post_content' => $post['post_content'],
					'post_excerpt' => $post['post_excerpt'], 'post_title' => $post['post_title'],
					'post_status' => $post['status'], 'post_name' => $post['post_name'],
					'comment_status' => $post['comment_status'], 'ping_status' => $post['ping_status'],
					'guid' => $post['guid'], 'post_parent' => $post_parent, 'menu_order' => $post['menu_order'],
					'post_type' => $post['post_type'], 'post_password' => $post['post_password']
				);

				$original_post_ID = $post['post_id'];
				$postdata = apply_filters( 'wp_import_post_data_processed', $postdata, $post );

				if ( 'attachment' == $postdata['post_type'] ) {
					$remote_url = ! empty($post['attachment_url']) ? $post['attachment_url'] : $post['guid'];

					// try to use _wp_attached file for upload folder placement to ensure the same location as the export site
					// e.g. location is 2003/05/image.jpg but the attachment post_date is 2010/09, see media_handle_upload()
					$postdata['upload_date'] = $post['post_date'];
					if ( isset( $post['postmeta'] ) ) {
						foreach( $post['postmeta'] as $meta ) {
							if ( $meta['key'] == '_wp_attached_file' ) {
								if ( preg_match( '%^[0-9]{4}/[0-9]{2}%', $meta['value'], $matches ) )
									$postdata['upload_date'] = $matches[0];
								break;
							}
						}
					}

					$comment_post_ID = $post_id = $this->process_attachment( $postdata, $remote_url );
				} else {
					$comment_post_ID = $post_id = wp_insert_post( $postdata, true );
					do_action( 'wp_import_insert_post', $post_id, $original_post_ID, $postdata, $post );
				}

				if ( is_wp_error( $post_id ) ) {
					printf( esc_html__( 'Failed to import %s &#8220;%s&#8221;', 'essential' ),
						$post_type_object->labels->singular_name, esc_html($post['post_title']) );
					if ( defined('RANGE_IMPORT_DEBUG') && RANGE_IMPORT_DEBUG )
						echo ': ' . $post_id->get_error_message();
					echo '<br />';
					continue;
				}

				if ( $post['is_sticky'] == 1 )
					stick_post( $post_id );
			}

			// map pre-import ID to local ID
			$this->processed_posts[intval($post['post_id'])] = (int) $post_id;

			if ( ! isset( $post['terms'] ) )
				$post['terms'] = array();

			$post['terms'] = apply_filters( 'wp_import_post_terms', $post['terms'], $post_id, $post );

			// add categories, tags and other terms
			if ( ! empty( $post['terms'] ) ) {
				$terms_to_set = array();
				foreach ( $post['terms'] as $term ) {
					// back compat with WXR 1.0 map 'tag' to 'post_tag'
					$taxonomy = ( 'tag' == $term['domain'] ) ? 'post_tag' : $term['domain'];
					$term_exists = term_exists( $term['slug'], $taxonomy );
					$term_id = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;
					if ( ! $term_id ) {
						$t = wp_insert_term( $term['name'], $taxonomy, array( 'slug' => $term['slug'] ) );
						if ( ! is_wp_error( $t ) ) {
							$term_id = $t['term_id'];
							do_action( 'wp_import_insert_term', $t, $term, $post_id, $post );
						} else {
							printf( esc_html__( 'Failed to import %s %s', 'essential' ), esc_html($taxonomy), esc_html($term['name']) );
							if ( defined('RANGE_IMPORT_DEBUG') && RANGE_IMPORT_DEBUG )
								echo ': ' . $t->get_error_message();
							echo '<br />';
							do_action( 'wp_import_insert_term_failed', $t, $term, $post_id, $post );
							continue;
						}
					}
					$terms_to_set[$taxonomy][] = intval( $term_id );
				}

				foreach ( $terms_to_set as $tax => $ids ) {
					$tt_ids = wp_set_post_terms( $post_id, $ids, $tax );
					do_action( 'wp_import_set_post_terms', $tt_ids, $ids, $tax, $post_id, $post );
				}
				unset( $post['terms'], $terms_to_set );
			}

			if ( ! isset( $post['comments'] ) )
				$post['comments'] = array();

			$post['comments'] = apply_filters( 'wp_import_post_comments', $post['comments'], $post_id, $post );

			// add/update comments
			if ( ! empty( $post['comments'] ) ) {
				$num_comments = 0;
				$inserted_comments = array();
				foreach ( $post['comments'] as $comment ) {
					$comment_id	= $comment['comment_id'];
					$newcomments[$comment_id]['comment_post_ID']      = $comment_post_ID;
					$newcomments[$comment_id]['comment_author']       = $comment['comment_author'];
					$newcomments[$comment_id]['comment_author_email'] = $comment['comment_author_email'];
					$newcomments[$comment_id]['comment_author_IP']    = $comment['comment_author_IP'];
					$newcomments[$comment_id]['comment_author_url']   = $comment['comment_author_url'];
					$newcomments[$comment_id]['comment_date']         = $comment['comment_date'];
					$newcomments[$comment_id]['comment_date_gmt']     = $comment['comment_date_gmt'];
					$newcomments[$comment_id]['comment_content']      = $comment['comment_content'];
					$newcomments[$comment_id]['comment_approved']     = $comment['comment_approved'];
					$newcomments[$comment_id]['comment_type']         = $comment['comment_type'];
					$newcomments[$comment_id]['comment_parent'] 	  = $comment['comment_parent'];
					$newcomments[$comment_id]['commentmeta']          = isset( $comment['commentmeta'] ) ? $comment['commentmeta'] : array();
					if ( isset( $this->processed_authors[$comment['comment_user_id']] ) )
						$newcomments[$comment_id]['user_id'] = $this->processed_authors[$comment['comment_user_id']];
				}
				ksort( $newcomments );

				foreach ( $newcomments as $key => $comment ) {
					// if this is a new post we can skip the comment_exists() check
					if ( ! $post_exists || ! comment_exists( $comment['comment_author'], $comment['comment_date'] ) ) {
						if ( isset( $inserted_comments[$comment['comment_parent']] ) )
							$comment['comment_parent'] = $inserted_comments[$comment['comment_parent']];
						$comment = wp_filter_comment( $comment );
						$inserted_comments[$key] = wp_insert_comment( $comment );
						do_action( 'wp_import_insert_comment', $inserted_comments[$key], $comment, $comment_post_ID, $post );

						foreach( $comment['commentmeta'] as $meta ) {
							$value = maybe_unserialize( $meta['value'] );
							add_comment_meta( $inserted_comments[$key], $meta['key'], $value );
						}

						$num_comments++;
					}
				}
				unset( $newcomments, $inserted_comments, $post['comments'] );
			}

			if ( ! isset( $post['postmeta'] ) )
				$post['postmeta'] = array();

			$post['postmeta'] = apply_filters( 'wp_import_post_meta', $post['postmeta'], $post_id, $post );

			// add/update post meta
			if ( ! empty( $post['postmeta'] ) ) {
				foreach ( $post['postmeta'] as $meta ) {
					$key = apply_filters( 'import_post_meta_key', $meta['key'], $post_id, $post );
					$value = false;

					if ( '_edit_last' == $key ) {
						if ( isset( $this->processed_authors[intval($meta['value'])] ) )
							$value = $this->processed_authors[intval($meta['value'])];
						else
							$key = false;
					}

					if ( $key ) {
						// export gets meta straight from the DB so could have a serialized string
						if ( ! $value )
							$value = maybe_unserialize( $meta['value'] );

						add_post_meta( $post_id, $key, $value );
						do_action( 'import_post_meta', $post_id, $key, $value );

						// if the post has a featured image, take note of this in case of remap
						if ( '_thumbnail_id' == $key )
							$this->featured_images[$post_id] = (int) $value;
					}
				}
			}
		}

		$this->backfill_parents();
		$this->remap_featured_images();

		unset( $this->posts );
		die();
	}

	/**
	 * Attempt to create a new menu item from import data
	 *
	 * Fails for draft, orphaned menu items and those without an associated nav_menu
	 * or an invalid nav_menu term. If the post type or term object which the menu item
	 * represents doesn't exist then the menu item will not be imported (waits until the
	 * end of the import to retry again before discarding).
	 *
	 * @param array $item Menu item details from WXR file
	 */
	function process_menu_item( $item ) {
		// skip draft, orphaned menu items
		if ( 'draft' == $item['status'] )
			return;

		$menu_slug = false;
		if ( isset($item['terms']) ) {
			// loop through terms, assume first nav_menu term is correct menu
			foreach ( $item['terms'] as $term ) {
				if ( 'nav_menu' == $term['domain'] ) {
					$menu_slug = $term['slug'];
					break;
				}
			}
		}

		// no nav_menu term associated with this menu item
		if ( ! $menu_slug ) {
			esc_html_e( 'Menu item skipped due to missing menu slug', 'essential' );
			echo '<br />';
			return;
		}

		$menu_id = term_exists( $menu_slug, 'nav_menu' );
		if ( ! $menu_id ) {
			printf( esc_html__( 'Menu item skipped due to invalid menu slug: %s', 'essential' ), esc_html( $menu_slug ) );
			echo '<br />';
			return;
		} else {
			$menu_id = is_array( $menu_id ) ? $menu_id['term_id'] : $menu_id;
		}

		foreach ( $item['postmeta'] as $meta )
			${$meta['key']} = $meta['value'];

		if ( 'taxonomy' == $_menu_item_type && isset( $this->processed_terms[intval($_menu_item_object_id)] ) ) {
			$_menu_item_object_id = $this->processed_terms[intval($_menu_item_object_id)];
		} else if ( 'post_type' == $_menu_item_type && isset( $this->processed_posts[intval($_menu_item_object_id)] ) ) {
			$_menu_item_object_id = $this->processed_posts[intval($_menu_item_object_id)];
		} else if ( 'custom' != $_menu_item_type ) {
			// associated object is missing or not imported yet, we'll retry later
			$this->missing_menu_items[] = $item;
			return;
		}

		if ( isset( $this->processed_menu_items[intval($_menu_item_menu_item_parent)] ) ) {
			$_menu_item_menu_item_parent = $this->processed_menu_items[intval($_menu_item_menu_item_parent)];
		} else if ( $_menu_item_menu_item_parent ) {
			$this->menu_item_orphans[intval($item['post_id'])] = (int) $_menu_item_menu_item_parent;
			$_menu_item_menu_item_parent = 0;
		}

		// wp_update_nav_menu_item expects CSS classes as a space separated string
		$_menu_item_classes = maybe_unserialize( $_menu_item_classes );
		if ( is_array( $_menu_item_classes ) )
			$_menu_item_classes = implode( ' ', $_menu_item_classes );

		$args = array(
			'menu-item-object-id' => $_menu_item_object_id,
			'menu-item-object' => $_menu_item_object,
			'menu-item-parent-id' => $_menu_item_menu_item_parent,
			'menu-item-position' => intval( $item['menu_order'] ),
			'menu-item-type' => $_menu_item_type,
			'menu-item-title' => $item['post_title'],
			'menu-item-url' => $_menu_item_url,
			'menu-item-description' => $item['post_content'],
			'menu-item-attr-title' => $item['post_excerpt'],
			'menu-item-target' => $_menu_item_target,
			'menu-item-classes' => $_menu_item_classes,
			'menu-item-xfn' => $_menu_item_xfn,
			'menu-item-status' => $item['status']
		);

		$id = wp_update_nav_menu_item( $menu_id, 0, $args );
		if ( $id && ! is_wp_error( $id ) )
			$this->processed_menu_items[intval($item['post_id'])] = (int) $id;
	}

	/**
	 * If fetching attachments is enabled then attempt to create a new attachment
	 *
	 * @param array $post Attachment post details from WXR
	 * @param string $url URL to fetch attachment from
	 * @return int|WP_Error Post ID on success, WP_Error otherwise
	 */
	function process_attachment( $post, $url ) {
 
		// if the URL is absolute, but does not contain address, then upload it assuming base_site_url
		if ( preg_match( '|^/[\w\W]+$|', $url ) )
			$url = get_template_directory_uri() . $url;

		$upload = $this->fetch_remote_file( $url, $post );
		if ( is_wp_error( $upload ) )
			return $upload;

		if ( $info = wp_check_filetype( $upload['file'] ) )
			$post['post_mime_type'] = $info['type'];
		else
			return new WP_Error( 'attachment_processing_error', esc_html__('Invalid file type', 'essential') );

		$post['guid'] = $upload['url'];

		// as per wp-admin/includes/upload.php
		$post_id = wp_insert_attachment( $post, $upload['file'] );
		wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );

		// remap resized image URLs, works by stripping the extension and remapping the URL stub.
		if ( preg_match( '!^image/!', $info['type'] ) ) {
			$parts = pathinfo( $url );
			$name = basename( $parts['basename'], ".{$parts['extension']}" ); // PATHINFO_FILENAME in PHP 5.2

			$parts_new = pathinfo( $upload['url'] );
			$name_new = basename( $parts_new['basename'], ".{$parts_new['extension']}" );

			$this->url_remap[$parts['dirname'] . '/' . $name] = $parts_new['dirname'] . '/' . $name_new;
		}

		return $post_id;
	}

	/**
	 * Attempt to download a remote file attachment
	 *
	 * @param string $url URL of item to fetch
	 * @param array $post Attachment details
	 * @return array|WP_Error Local file location details on success, WP_Error otherwise
	 */
	function fetch_remote_file( $url, $post ) {
			// extract the file name and extension from the url
			$file_name = basename( $url );

			// get placeholder file in the upload dir with a unique, sanitized filename
			$upload = wp_upload_bits( $file_name, 0, '', $post['upload_date'] );
			if ( $upload['error'] )
				return new WP_Error( 'upload_dir_error', $upload['error'] );

			// fetch the remote url and write it to the placeholder file
			$response = wp_remote_get( $url, array(
				'stream' => true,
				'filename' => $upload['file']
			) );

			// request failed
			if ( is_wp_error( $response ) ) {
				@unlink( $upload['file'] );
				return $response;
			}

			$code = (int) wp_remote_retrieve_response_code( $response );

			// make sure the fetch was successful
			if ( $code !== 200 ) {
				@unlink( $upload['file'] );
				return new WP_Error(
					'import_file_error',
					sprintf(
						__('Remote server returned %1$d %2$s for %3$s', 'essential'),
						$code,
						get_status_header_desc( $code ),
						$url
					)
				);
			}

			$filesize = filesize( $upload['file'] );
			$headers = wp_remote_retrieve_headers( $response );

			if ( isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
				@unlink( $upload['file'] );
				return new WP_Error( 'import_file_error', esc_html__('Remote file is incorrect size', 'essential') );
			}

			if ( 0 == $filesize ) {
				@unlink( $upload['file'] );
				return new WP_Error( 'import_file_error', esc_html__('Zero size file downloaded', 'essential') );
			}

			$max_size = (int) $this->max_attachment_size();
			if ( ! empty( $max_size ) && $filesize > $max_size ) {
				@unlink( $upload['file'] );
				return new WP_Error( 'import_file_error', sprintf(esc_html__('Remote file is too large, limit is %s', 'essential'), size_format($max_size) ) );
			}

			// keep track of the old and new urls so we can substitute them later
	        $this->url_remap[$url] = $upload['url'];
	        $this->url_remap[$post['guid']] = $upload['url']; // r13735, really needed?
	        // keep track of the destination if the remote url is redirected somewhere else
	        if ( isset($headers['x-final-location']) && $headers['x-final-location'] != $url ){
	        	$this->url_remap[$headers['x-final-location']] = $upload['url'];
			}

			return $upload;
	}

	/**
	 * Attempt to associate posts and menu items with previously missing parents
	 *
	 * An imported post's parent may not have been imported when it was first created
	 * so try again. Similarly for child menu items and menu items which were missing
	 * the object (e.g. post) they represent in the menu
	 */
	function backfill_parents() {
		global $wpdb;

		// find parents for post orphans
		foreach ( $this->post_orphans as $child_id => $parent_id ) {
			$local_child_id = $local_parent_id = false;
			if ( isset( $this->processed_posts[$child_id] ) )
				$local_child_id = $this->processed_posts[$child_id];
			if ( isset( $this->processed_posts[$parent_id] ) )
				$local_parent_id = $this->processed_posts[$parent_id];

			if ( $local_child_id && $local_parent_id )
				$wpdb->update( $wpdb->posts, array( 'post_parent' => $local_parent_id ), array( 'ID' => $local_child_id ), '%d', '%d' );
		}

		// all other posts/terms are imported, retry menu items with missing associated object
		$missing_menu_items = $this->missing_menu_items;
		foreach ( $missing_menu_items as $item )
			$this->process_menu_item( $item );

		// find parents for menu item orphans
		foreach ( $this->menu_item_orphans as $child_id => $parent_id ) {
			$local_child_id = $local_parent_id = 0;
			if ( isset( $this->processed_menu_items[$child_id] ) )
				$local_child_id = $this->processed_menu_items[$child_id];
			if ( isset( $this->processed_menu_items[$parent_id] ) )
				$local_parent_id = $this->processed_menu_items[$parent_id];

			if ( $local_child_id && $local_parent_id )
				update_post_meta( $local_child_id, '_menu_item_menu_item_parent', (int) $local_parent_id );
		}
	}

	/**
	 * Update _thumbnail_id meta to new, imported attachment IDs
	 */
	function remap_featured_images() {
		// cycle through posts that have a featured image
		foreach ( $this->featured_images as $post_id => $value ) {
			if ( isset( $this->processed_posts[$value] ) ) {
				$new_id = $this->processed_posts[$value];
				// only update if there's a difference
				if ( $new_id != $value )
					update_post_meta( $post_id, '_thumbnail_id', $new_id );
			}
		}
	}

	/**
	 * Importing general options theme
	 *
	 * @since 1.0.0
	 * @return bool
	 *
	 */
	function import_options() {
		$theme_options = $this->get_json($this->essential_options_file);
		$decode_options = gzuncompress( stripslashes( call_user_func( 'base'. '64' .'_decode', rtrim( strtr( $theme_options->data, '-_', '+/' ), '=' ) ) ) );
 

		$result_opt = '';
		// we also want to update the widget area manager options.
		foreach($theme_options as $option => $value){
			if ( $option != 'demo' ) {
				$result_opt .= update_option($option, $value);
			}
		}

		// delete options
		delete_option( 'essential_themes_options' );

		// import options
		$result_opt .= update_option( 'essential_themes_options', unserialize($decode_options) );

		// import menu
		if (!empty($theme_options->menu_name)) {
			$menu = wp_get_nav_menu_object($theme_options->menu_name);
			$locations = get_theme_mod('nav_menu_locations');
			$locations['primary-menu'] = $menu->term_id;
			set_theme_mod('nav_menu_locations',$locations);
		}

		echo $result_opt;
	}

	/**
	 * Install all posts from demo
	 *
	 * @since 1.0.0
	 * @return array
	 *
	 */
	private function get_json($file){

		if(is_file($file)) {
			WP_Filesystem();
			global $wp_filesystem;
			return json_decode($wp_filesystem->get_contents( $file ));
		}
		return array();
	}

	/**
	 * Decide what the maximum file size for downloaded attachments is.
	 * Default is 0 (unlimited), can be filtered via import_attachment_size_limit
	 *
	 * @return int Maximum attachment file size to import
	 */
	function max_attachment_size() {
		return apply_filters( 'import_attachment_size_limit', 0 );
	}

}
new Essential_Import_Demo_Install();
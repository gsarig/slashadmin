<?php

namespace SlashAdmin;

class TaxonomyOrder {

	/**
	 * Respect the order by which non-hierarchical terms are being added to a post.
	 *
	 * @param array $post_types
	 * @param array $taxonomies
	 *
	 */

	public $taxonomies = [];
	public $post_types = [];
	private $wpdb;

	public function __construct( $post_types = [], $taxonomies = [] ) {
		if ( ! Settings::option( 'taxonomy_order' ) ) {
			return;
		}
		global $wpdb;
		$this->wpdb       = $wpdb;
		$this->post_types = $post_types;
		$this->taxonomies = $taxonomies;
		add_action( 'init', array( $this, 'applyOrder' ) );
		if ( $this->getPostTypes() ) {
			foreach ( $this->getPostTypes() as $post_type ) {
				add_action( 'rest_after_insert_' . $post_type, array( $this, 'setOrder' ), 10, 2 );
			}
		}
	}

	public function applyOrder() {
		global $wp_taxonomies;
		foreach ( $this->getTaxonomies() as $taxonomy ) {
			$wp_taxonomies[ $taxonomy ]->sort = true;
			$wp_taxonomies[ $taxonomy ]->args = array( 'orderby' => 'term_order' );
		}
	}

	public function setOrder( $post, $request ) {
		$params = $request->get_params();
		foreach ( $this->getTaxonomies() as $taxonomy ) {
			$tax_name = $this->getBackendTaxName( $taxonomy );
			if ( isset( $params[ $tax_name ] ) && $params[ $tax_name ] ) {
				foreach ( $params[ $tax_name ] as $index => $term_id ) {
					$where = [
						'object_id'        => $post->ID,
						'term_taxonomy_id' => $term_id,
					];
					$data  = [
						'term_order' => (int) $index,
					];
					$this->wpdb->update( $this->wpdb->prefix . 'term_relationships', $data, $where );
				}
			}
		}
	}

	private function getPostTypes() {
		$post_types = [];
		if ( $this->post_types ) {
			$post_types = $this->post_types;
		} else {
			$all_post_types = get_post_types();
			$excludes       = [
				'attachment',
				'revision',
				'nav_menu_item',
				'custom_css',
				'customize_changeset',
				'oembed_cache',
				'user_request',
				'wp_block',
			];
			if ( $all_post_types ) {
				foreach ( $all_post_types as $post_type ) {
					if ( ! in_array( $post_type, $excludes ) ) {
						$post_types[] = $post_type;
					}
				}
			}

		}

		return $post_types;
	}

	private function getTaxonomies() {
		$non_hierarchical = [];
		if ( $this->taxonomies ) {
			$non_hierarchical = $this->taxonomies;
		} else {
			$taxonomies = get_taxonomies();
			$excluded   = [ 'nav_menu', 'link_category', 'post_format' ];
			if ( $taxonomies ) {
				foreach ( $taxonomies as $taxonomy ) {
					if ( ! is_taxonomy_hierarchical( $taxonomy ) && ! in_array( $taxonomy, $excluded ) ) {
						$non_hierarchical[] = $taxonomy;
					}
				}
			}
		}

		return $non_hierarchical;
	}

	private function getBackendTaxName( $taxonomy ) {
		$name    = $taxonomy;
		$matches = [
			'post_tag' => 'tags',
		];
		if ( isset( $matches[ $taxonomy ] ) ) {
			$name = $matches[ $taxonomy ];
		}

		return $name;
	}
}
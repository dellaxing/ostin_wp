<?php namespace fmwp\ajax;


if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'fmwp\ajax\Forum_Category' ) ) {


	/**
	 * Class Forum_Category
	 *
	 * @package fmwp\ajax
	 */
	class Forum_Category {


		/**
		 * Forum_Category constructor.
		 */
		function __construct() {
		}


		/**
		 * Get categories list
		 */
		function get_list() {
			FMWP()->ajax()->check_nonce( 'fmwp-frontend-nonce' );

			$child_offset = ! empty( $_POST['child_offset'] ) ? (int) $_POST['child_offset'] : 0;
			$offset = ! empty( $_POST['offset'] ) ? (int) $_POST['offset'] : 0;

			$search = ! empty( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';

			$skip_parent = false;
			if ( ! empty( $child_offset ) ) {
				$offset--;
				$skip_parent = true;
			}

			$data = [
				'taxonomy'  => 'fmwp_forum_category',
				'get'       => 'all',
				'fields'    => 'id=>parent',
			];

			if ( ! empty( $search ) ) {
				$data['search'] = $search;
			}

			$terms_all = get_terms( $data );

			$data = [
				'taxonomy'      => 'fmwp_forum_category',
				'number'        => FMWP()->options()->get_variable( 'forum_categories_per_page' ),
				'offset'        => $offset,
				'parent'        => 0,
				'hide_empty'    => false,
				'orderby'       => 'name',
				'order'         => 'ASC',
			];

			if ( ! empty( $search ) ) {
				$data['search'] = $search;
			}

			$terms = get_terms( $data );

			$parent_enabled = true;

			if ( ! empty( $search ) && empty( $terms ) && ! empty( $terms_all ) ) {
				$data = [
					'taxonomy'  => 'fmwp_forum_category',
					'number'    => FMWP()->options()->get_variable( 'forum_categories_per_page' ),
					'offset'    => $offset,
					'include'   => array_values( $terms_all ),
					'orderby'   => 'name',
					'order'     => 'ASC',
				];

				$terms = get_terms( $data );

				$parent_enabled = false;
			}

			$response = [];
			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {

					if ( count( $response ) >= FMWP()->options()->get_variable( 'forum_categories_per_page' ) ) {
						break;
					}

					if ( ! $skip_parent ) {
						$term_args = [
							'id'                => $term->term_id,
							'title'             => $term->name,
							'content'           => $term->description,
							'permalink'         => get_term_link( $term, 'fmwp_forum_category' ),
							'child'             => false,
							'has_children'      => false,
							'dropdown_actions'  => [],
							'forums'            => $term->count,
							'topics'            => FMWP()->common()->forum_category()->get_topics_count( $term->term_id ),
							'replies'           => FMWP()->common()->forum_category()->get_replies_count( $term->term_id ),
							'enabled'           => $parent_enabled,
						];

						$response[ $term->term_id ] = $term_args;

						$child_offset = 0;
					}

					$offset++;


					$data = [
						'taxonomy'      => 'fmwp_forum_category',
						'number'        => FMWP()->options()->get_variable( 'forum_categories_per_page' ),
						'offset'        => $child_offset,
						'parent'        => $term->term_id,
						'hide_empty'    => false,
						'orderby'       => 'name',
						'order'         => 'ASC',
					];

					if ( ! empty( $search ) ) {
						$data['search'] = $search;
					}

					$child_terms = get_terms( $data );

					if ( ! empty( $child_terms ) ) {

						if ( ! $skip_parent ) {
							$response[ $term->term_id ]['has_children'] = true;
						}

						foreach ( $child_terms as $child_term ) {
							if ( count( $response ) >= FMWP()->options()->get_variable( 'forum_categories_per_page' ) ) {
								break;
							}

							$term_args = [
								'id'                => $child_term->term_id,
								'title'             => $child_term->name,
								'content'           => $child_term->description,
								'permalink'         => get_term_link( $child_term, 'fmwp_forum_category' ),
								'forums'            => $child_term->count,
								'topics'            => FMWP()->common()->forum_category()->get_topics_count( $child_term->term_id ),
								'replies'           => FMWP()->common()->forum_category()->get_replies_count( $child_term->term_id ),
								'child'             => true,
								'has_children'      => false,
								'dropdown_actions'  => [],
							];

							$response[ $child_term->term_id ] = $term_args;
							$child_offset++;
						}
					}

					$skip_parent = false;
				}
			}

			wp_send_json_success( [
				'categories'    => array_values( $response ),
				'pagination'    => [
					'offset'        => $offset,
					'child_offset'  => $child_offset,
				],
			] );
		}

	}
}
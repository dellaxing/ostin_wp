<?php
namespace fmwp\ajax;


if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'fmwp\ajax\Forum' ) ) {


	/**
	 * Class Forum
	 *
	 * @package fmwp\ajax
	 */
	class Forum extends Post {


		/**
		 * Forum constructor.
		 */
		function __construct() {
			parent::__construct();
		}


		/**
		 * @param int|\WP_Post $forum
		 *
		 * @return array
		 */
		function response_data( $forum ) {
			if ( is_numeric( $forum ) ) {
				$forum = get_post( $forum );

				if ( empty( $forum ) || is_wp_error( $forum ) ) {
					return [];
				}
			}

			$author = get_userdata( $forum->post_author );

			$topics_count = FMWP()->common()->forum()->get_statistics( $forum->ID, 'topics' );
			$replies_count = FMWP()->common()->forum()->get_statistics( $forum->ID, 'posts' );

			$last_topic = FMWP()->common()->forum()->get_last_topic( $forum->ID );
			$last_topic = ! empty( $last_topic ) && ! is_wp_error( $last_topic ) ? $last_topic : false;

			$last_topic_url = '';
			if ( $last_topic ) {
				$last_topic_url = get_permalink( $last_topic );
				$last_topic = $last_topic->post_title;
			}

			$dropdown_actions = FMWP()->common()->forum()->actions_list( $forum );

			$thumbnail = get_the_post_thumbnail( $forum, 'thumbnail' );
			$icon = false;
			$icon_color = false;
			$icon_bgcolor = false;

			if ( empty( $thumbnail ) ) {
				$icon = get_post_meta( $forum->ID, 'fmwp_icon', true );
				if ( ! empty( $icon ) ) {
					$icon_bgcolor = get_post_meta( $forum->ID, 'fmwp_icon_bgcolor', true );
					$icon_bgcolor = empty( $icon_bgcolor ) ? '#000' : $icon_bgcolor;

					$icon_color = get_post_meta( $forum->ID, 'fmwp_icon_color', true );
					$icon_color = empty( $icon_color ) ? '#fff' : $icon_color;
				}
			}

			$last_upgrade = get_post_meta( $forum->ID, 'fmwp_last_update', true );
			$default_last_upgrade = ( ! empty( $forum->post_modified_gmt ) && $forum->post_modified_gmt !== '0000-00-00 00:00:00' ) ? human_time_diff( strtotime( $forum->post_modified_gmt ) ) : '';
			$last_upgrade = ! empty( $last_upgrade ) ? human_time_diff( $last_upgrade ) : $default_last_upgrade;

			$strip_content = '';
			if ( ! post_password_required( $forum ) ) {
				$strip_content = wp_strip_all_tags( apply_filters( 'the_content', $forum->post_content, $forum->ID ) );
			}

			$forum_args = [
				'forum_id'          => $forum->ID,
				'title'             => $forum->post_title,
				'permalink'         => get_permalink( $forum->ID ),
				'author'            => FMWP()->user()->display_name( $author ),
				'author_url'        => FMWP()->user()->get_profile_link( $author->ID ),
				'author_avatar'     => FMWP()->user()->get_avatar( $author->ID, 'inline', 60 ),
				'last_upgrade'      => $last_upgrade,
				'strip_content'     => $strip_content,
				'topics'            => $topics_count,
				'replies'           => $replies_count,
				'thumbnail'         => $thumbnail,
				'icon'              => $icon,
				'icon_color'        => $icon_color,
				'icon_bgcolor'      => $icon_bgcolor,
				'dropdown_actions'  => $dropdown_actions,
				'latest_topic'      => $last_topic,
				'latest_topic_url'  => $last_topic_url,
				'is_locked'         => FMWP()->common()->forum()->is_locked( $forum->ID ),
			];

			if ( FMWP()->options()->get( 'forum_categories' ) ) {
				$forum_categories = FMWP()->common()->forum()->get_categories( $forum->ID );

				$categories_data = [];
				foreach ( $forum_categories as $tag ) {
					$categories_data[] = [
						'href' => get_term_link( $tag->term_id, 'fmwp_forum_category' ),
						'name' => $tag->name,
					];
				}
				$forum_args['categories'] = $categories_data;
			}

			$forum_args = apply_filters( 'fmwp_ajax_response_forum_args', $forum_args, $forum );
			return $forum_args;
		}


		/**
		 * Get forums for the list
		 */
		function get_forums() {
			FMWP()->ajax()->check_nonce( 'fmwp-frontend-nonce' );

			$response = [
				'actions'   => false,
				'forums'    => [],
			];

			$query_args = [
				'post_type'         => 'fmwp_forum',
				'post_status'       => FMWP()->common()->forum()->post_status,
				'posts_per_page'    => FMWP()->options()->get_variable( 'forums_per_page' ),
				'paged'             => absint( $_REQUEST['page'] ),
			];

			$query_args['meta_query'] = [
				'relation'  => 'AND',
			];

			if ( ! is_user_logged_in() || ! current_user_can( 'manage_fmwp_forums_all' ) ) {
				$query_args['meta_query'][] = [
					'key'       => 'fmwp_visibility',
					'value'     => 'public',
					'compare'   => '=',
				];
			}

			if ( ! empty( $_POST['search'] ) ) {
				$query_args['s'] = sanitize_text_field( $_POST['search'] );
			}

			if ( ! empty( $_POST['category'] ) ) {
				$query_args['tax_query'] = [
					[
						'taxonomy'          => 'fmwp_forum_category',
						'field'             => 'id',
						'terms'             => absint( $_POST['category'] ),
						'include_children'  => isset( $_POST['with_sub'] ) ? (bool) $_POST['with_sub'] : true,
					],
				];
			}

			$query_args['orderby'] = [ 'date' => 'desc' ];

			if ( ! empty( $_POST['order'] ) ) {
				list( $orderby, $order ) = explode( '_', sanitize_key( $_POST['order'] ) );

				if ( $orderby === 'order' ) {
					$query_args['meta_query'][] = [
						'relation'  => 'OR',
						[
							'key'       => 'fmwp_order',
							'compare'   => 'EXISTS',
						],
						'order_meta'    => [
							'key'       => 'fmwp_order',
							'compare'   => 'NOT EXISTS',
						],
					];

					// sort by date if the same order
					$query_args['orderby'] = [
						'order_meta'    => $order,
						'date'          => $order
					];
				} elseif ( $orderby === 'date' ) {
					$query_args['orderby'] = [ $orderby => $order ];
				}
			}

			$query_args['suppress_filters'] = false;
			$query_args = apply_filters( 'fmwp_get_forums_arguments', $query_args );

			$forums = get_posts( $query_args );
			if ( ! empty( $forums ) ) {
				foreach ( $forums as $forum ) {
					$response_data = $this->response_data( $forum );
					$response['forums'][] = $response_data;

					if ( count( $response_data['dropdown_actions'] ) ) {
						$response['actions'] = true;
					}
				}
			}

			wp_send_json_success( $response );
		}


		/**
		 * AJAX handler for Delete Forum
		 *
		 */
		function trash() {
			FMWP()->ajax()->check_nonce( 'fmwp-frontend-nonce' );

			if ( empty( $_POST['forum_id'] ) ) {
				wp_send_json_error( __( 'Invalid data', 'forumwp' ) );
			}

			$forum_id = absint( $_POST['forum_id'] );
			$forum = get_post( $forum_id );

			if ( empty( $forum ) || is_wp_error( $forum ) ) {
				wp_send_json_error( __( 'Forum ID is invalid', 'forumwp' ) );
			}

			if ( ! FMWP()->user()->can_delete_forum( get_current_user_id(), $forum ) ) {
				wp_send_json_error( __( 'You do not have the ability to move this forum to trash', 'forumwp' ) );
			}

			FMWP()->common()->forum()->move_to_trash( $forum_id );

			$forum = get_post( $forum_id );
			wp_send_json_success( [
				'dropdown_actions' => FMWP()->common()->forum()->actions_list( $forum ),
			] );
		}


		/**
		 *
		 */
		function lock() {
			FMWP()->ajax()->check_nonce( 'fmwp-frontend-nonce' );

			if ( empty( $_POST['forum_id'] ) ) {
				wp_send_json_error( __( 'Invalid data', 'forumwp' ) );
			}

			$forum_id = absint( $_POST['forum_id'] );

			$forum = get_post( $forum_id );
			if ( empty( $forum ) || is_wp_error( $forum ) ) {
				wp_send_json_error( __( 'Forum ID is invalid', 'forumwp' ) );
			}

			FMWP()->common()->forum()->lock( $forum_id );

			$forum = get_post( $forum_id );
			wp_send_json_success( [
				'dropdown_actions' => FMWP()->common()->forum()->actions_list( $forum ),
			] );
		}


		/**
		 *
		 */
		function unlock() {
			FMWP()->ajax()->check_nonce( 'fmwp-frontend-nonce' );

			if ( empty( $_POST['forum_id'] ) ) {
				wp_send_json_error( __( 'Invalid data', 'forumwp' ) );
			}

			$forum_id = absint( $_POST['forum_id'] );

			$forum = get_post( $forum_id );
			if ( empty( $forum ) || is_wp_error( $forum ) ) {
				wp_send_json_error( __( 'Forum ID is invalid', 'forumwp' ) );
			}

			FMWP()->common()->forum()->unlock( $forum_id );

			$forum = get_post( $forum_id );
			wp_send_json_success( [
				'dropdown_actions' => FMWP()->common()->forum()->actions_list( $forum ),
			] );
		}
	}
}
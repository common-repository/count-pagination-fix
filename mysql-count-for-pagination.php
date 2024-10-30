<?php
/**
 * iSide Count Pagination Fix for MySQL plugin
 *
 * @author      Team iSide
 * @copyright   Copyright (C) 2023, iSide BV - dev@iside.be
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name: Count Pagination Fix for MySQL
 * Version:     1.1.1
 * Description: Simple fix for pagination issues under MySQL v8.0 and later where `SQL_CALC_FOUND_ROWS` and `FOUND_ROWS` are no longer supported.
 * Author:      Team iSide
 * Author URI:  https://www.iside.be
 * Text Domain: iside
 * License:     GPL v3
 * Requires at least: 6.0
 * Requires PHP: 7.0
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this plugin.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Iside_Pagination_Fix {

	public function __construct() {
		add_filter( 'found_posts_query', array( $this, 'edit_found_posts_query' ), 99, 2 );
		add_filter( 'found_comments_query', array( $this, 'edit_found_posts_query' ), 99, 2 );
	}

	/**
	 * Function to edit the query to support for newer MySQL versions
	 * because SQL_CALC_FOUND_ROWS and FOUND_ROWS are deprecated as of MySQL v8.0.17
	 *
	 * @param string   $found_posts_query The query to run to find the found posts.
	 * @param WP_Query $query             The WP_Query instance (passed by reference).
	 */
	public function edit_found_posts_query( $found_posts_query = '', $wp_query_obj = null ) {
		if ( strpos( $found_posts_query, 'FOUND_ROWS' ) !== false ) {
			$request = $wp_query_obj->request;

			// Replace SQL_CALC_FOUND_ROWS with COUNT method.
			$pattern = '/SQL_CALC_FOUND_ROWS\s+([^\s]+)/i';
			if ( preg_match( $pattern, $request, $matches ) ) {
				$match    = $matches[1];
				$replaced = "COUNT( DISTINCT( $match ) )";
				$request  = preg_replace( $pattern, $replaced, $request, 1 );

				if ( $request ) {
					// Remove GROUP BY from query to get the correct query for counting.
					$groupby_pos = strrpos( $request, 'GROUP BY' );
					if ( $groupby_pos !== false ) {
						$orderby_pos = strrpos( $request, 'ORDER BY' );
						if ( $orderby_pos !== false ) {
							$request = substr( $request, 0, $groupby_pos ) . substr( $request, $orderby_pos );
						} else {
							$request = substr( $request, 0, $groupby_pos );
						}
					}

					// Remove LIMIT from query to get the full amount of posts.
					$limit_pos = strrpos( $request, 'LIMIT' );
					if ( $limit_pos !== false ) {
						$request = substr( $request, 0, $limit_pos );
					}
					$found_posts_query = $request;
				}
			}
		}
		return $found_posts_query;
	}
}

$iside_pagination_fix = new Iside_Pagination_Fix();

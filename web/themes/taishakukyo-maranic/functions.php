<?php
/**
 * テーマ用関数、定数
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package jun
 */

// CSSやJavascriptを読み込む
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'theme',
			get_static_file_path( '/styles/style.css' ),
			array(),
			null
		);

		if ( ! is_admin() ) {
			wp_deregister_script( 'jquery' );
		}
		wp_enqueue_script(
			'theme',
			get_static_file_path( '/scripts/script.js' ),
			array(),
			null,
			true
		);
	}
);
add_action(
	'wp_head',
	function() {
		echo (
			implode(
				"\n",
				array(
					// phpcs:disable Generic.Files.LineLength
					'<link rel="preconnect" href="https://fonts.googleapis.com">',
					'<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>',
					'<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Red+Hat+Display:wght@900&display=swap" rel="stylesheet">', // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
					'<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url( get_stylesheet_directory_uri() ) . '/assets/favicons/apple-touch-icon.png" />',
					'<link rel="icon" type="image/png" sizes="32x32" href="' . esc_url( get_stylesheet_directory_uri() ) . '/assets/favicons/favicon-32x32.png" />',
					'<link rel="icon" type="image/png" sizes="16x16" href="' . esc_url( get_stylesheet_directory_uri() ) . '/assets/favicons/favicon-16x16.png" />',
					'<link rel="manifest" href="' . esc_url( get_stylesheet_directory_uri() ) . '/assets/favicons/site.webmanifest" />',
					'<link rel="mask-icon" href="' . esc_url( get_stylesheet_directory_uri() ) . '/assets/favicons/safari-pinned-tab.svg" color="#5bbad5" />',
					'<meta name="msapplication-TileColor" content="#ffffff" />',
					'<meta name="theme-color" content="#ffffff" />',
					// phpcs:enable Generic.Files.LineLength
				)
			)
		);
	}
);

// GutenbergにCSSを適用
add_action(
	'enqueue_block_editor_assets',
	function () {
		wp_enqueue_style(
			'theme-editor',
			get_static_file_path( '/styles/editor-style.css' ),
			array(),
			null
		);
	}
);

// p/br要素の自動挿入を停止
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

// postのスラッグ、ラベルを設定
define( 'POST_TYPE_DEFAULT_SLUG', 'information' );
define( 'POST_TYPE_DEFAULT_LABEL', 'お知らせ' );

// ページタイトル
add_filter(
	'jun_page_title',
	function( $title ) {
		if ( is_page() ) {
			$parent_post_id = get_post( get_the_ID() )->post_parent;
			if ( ! empty( $parent_post_id ) ) {
				return get_the_title( $parent_post_id );
			}
		}

		return $title;
	}
);

// テンプレートへ渡す変数
add_filter(
	'timber/context',
	function ( $context ) {
		if ( is_page() ) {
			$parent_post_id = get_post( get_the_ID() )->post_parent;
			if ( ! empty( $parent_post_id ) ) {
				$parent_post_name = get_post( $parent_post_id )->post_name;
				$context['title_en'] = $parent_post_name;

				if ( array_search( $parent_post_name, array( 'gallery', 'result' ) ) !== false ) {
					$siblings = get_pages(
						array(
							'parent' => $parent_post_id,
							'sort_column' => 'post_name',
						)
					);
					$context['years'] = array_map(
						function( $item ) {
							return preg_replace( '/y-(\d{4})/', '$1', $item->post_name );
						},
						$siblings
					);

					if ( 'gallery' === $parent_post_name ) {
						$post_name = get_post( get_the_ID() )->post_name;
						$photos = array_filter(
							array_map(
								function( $path ) {
									if ( ! exif_imagetype( $path ) ) {
										return false;
									}

									return str_replace(
										get_stylesheet_directory(),
										get_stylesheet_directory_uri(),
										$path
									);
								},
								glob(
									get_stylesheet_directory()
									. "/assets/images/gallery/$post_name/photo_*.*"
								)
							)
						);
						$context['images'] = array_map(
							function( $photo ) {
								return array(
									'photo' => $photo,
									'thumb' =>
										file_exists(
											str_replace(
												array( get_stylesheet_directory_uri(), 'photo_' ),
												array( get_stylesheet_directory(), 'thumb_' ),
												$photo
											)
										)
											? str_replace( 'photo_', 'thumb_', $photo )
											: null,
								);
							},
							$photos
						);
					} else if ( 'result' === $parent_post_name ) {
						$result_path = get_stylesheet_directory() . '/assets/result.json';
						$result = file_exists( $result_path )
							? json_decode( file_get_contents( $result_path ), true )
							: array();
						$context['result'] = $result[ get_post( get_the_ID() )->post_name ];
					}
				}
			} else {
				$context['title_en'] = str_replace(
					array( '_', '-' ),
					array( ' & ', ' ' ),
					get_post( get_the_ID() )->post_name
				);
			}
		} elseif ( is_archive() || is_single() ) {
			$context['title_en'] = POST_TYPE_DEFAULT_SLUG;
		} elseif ( is_post_type_archive() || is_singular() ) {
			$post_type = get_post_type_object();
			$context['title_en'] = $post_type->name;
		} elseif ( is_404() ) {
			$context['title_en'] = 'Page not found';
		}

		return $context;
	},
	11,
	2
);

// クエリを変更
add_action(
	'pre_get_posts',
	function( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( is_home() ) {
			$query->set( 'posts_per_page', 5 );

			return;
		}
	}
);

// OGP画像パスを変更
add_filter(
	'inc2734_wp_ogp_image',
	function() {
		return get_stylesheet_directory_uri() . '/assets/images/common/ogp.jpg';
	}
);

add_action(
	'template_redirect',
	function() {
		if ( is_page( array( 'gallery', 'result' ) ) ) {
			$year = gmdate( 'Y' );
			$slug = get_post( get_the_ID() )->post_name;
			if ( ! get_page_by_path( "$slug/y-$year" ) ) {
				$year = strval( intval( $year ) - 1 );
			}

			wp_redirect( home_url( "$slug/y-$year" ) );
			exit;
		}
	}
);

add_filter(
	'inc2734_wp_breadcrumbs',
	function( $items ) {
		if ( empty( $items ) ) {
			return $items;
		}

		// singleページでカテゴリアーカイブへのリンクを削除
		if ( is_single() ) {
			$items = array_filter(
				$items,
				function( $item ) {
					return strpos( $item['link'], '/category/' ) === false;
				}
			);
		}

		return $items;
	}
);

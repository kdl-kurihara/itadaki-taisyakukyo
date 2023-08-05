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
			mix( '/styles/style.css' ),
			array(),
			null
		);

		if ( ! is_admin() ) {
			wp_deregister_script( 'jquery' );
		}
		wp_enqueue_script(
			'theme',
			mix( '/scripts/script.js' ),
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
			mix( '/styles/editor-style.css' ),
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

// テンプレートへ渡す変数
add_filter(
	'timber/context',
	function ( $context ) {
		// メニュー
		$menu = $context['menu'];
		foreach ( $menu->items as $i => $item ) {
			if ( 'page' === $item->object ) {
				preg_match( '/page_id=(\d+)/', $item->url, $matches );
				if ( $matches ) {
					$page_id = $matches[1];
					$page = get_post( $page_id );
				} else {
					$page = get_page_by_path( str_replace( get_home_url(), '', $item->url ) );
				}
				$menu->items[ $i ]->disabled = 'publish' !== $page->post_status;
			} else {
				$menu->items[ $i ]->disabled = false;
			}
		}
		$context['menu'] = $menu;

		// 下層ページタイトル
		if ( is_page() ) {
			$context['title_en'] = str_replace(
				array( '_', '-' ),
				array( ' & ', ' ' ),
				get_post( get_the_ID() )->post_name
			);
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

/**
 * Laravel Mixでビルドしたファイルをパラメータ付きで呼び出し
 *
 * @param   String $path  assetsより下のパス
 *
 * @return  String         絶対パスのURL
 */
function mix( $path ) {
	if ( ! WP_DEBUG ) {
		$manifest_path = get_stylesheet_directory() . '/assets/mix-manifest.json';
		$manifest = file_exists( $manifest_path )
			? json_decode( file_get_contents( $manifest_path ), true )
			: array();
		$path = ! empty( $manifest ) && isset( $manifest[ $path ] ) ? $manifest[ $path ] : $path;
	}

	return get_stylesheet_directory_uri() . '/assets' . $path;
}
add_filter(
	'timber/twig',
	function( $twig ) {
		$twig->addFunction( new Timber\Twig_Function( 'mix', 'mix' ) );

		return $twig;
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

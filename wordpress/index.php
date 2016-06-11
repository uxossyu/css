// header.phpを読み込む
<?php get_header(); ?> 

// footer.phpを読み込む
<?php get_footer(); ?>

// xxx-yyy.phpのテンンプレを読み込む
<?php get_template_part('xxx','yyy');  ?>
    

// 投稿の固有のクラスの付与
// 独自のクラスの追加の場合は('drawer drawer--right')のような形にする
<?php post_class('drawer drawer--right'); ?>


// ページのループ
<?php if (have_posts()) :
while (have_posts()) : the_post(); ?>
ここから内容を記述
<?php the_content(); ?> // 投稿内容を表示する
<?php the_time('Y.m.d.'); ?>  // 投稿日時を表示する
<?php previous_post_link('%link','← %title'); ?>　// 前の記事を表示する
<?php next_post_link('%link','→ %title'); ?>　// 次の記事を表示する
<?php $cat = get_the_category(); $cat = $cat[0]; { echo $cat->cat_name; } ?> //　カテゴリー名のみを表示する
<?php echo get_the_title(); ?>　// タイトルを表示
<?php the_permalink(); ?> // 記事へのリンクの表示

// スラッグ名を出力　カテゴリ名を出力
// 下記の場合の出力は　<span class="スラッグ名" />カテゴリ名</span>　になる
<?php $cat = get_the_category(); $cat = $cat[0]; { echo '<span class="' . $cat->category_nicename . '" />'; } ?>
<?php $cat = get_the_category(); $cat = $cat[0]; { echo $cat->cat_name; } ?></span>

<?php the_excerpt(); ?>　// 記事本文の抜粋を表示

<?php endwhile; // 繰り返し処理終了
else : // ここから記事が見つからなかった場合の処理 ?>
<div class="post">
<h2>記事はありません</h2>
<p>お探しの記事は見つかりませんでした。</p>
</div>
<?php endif; ?>


// 固有の固定ページへの処理
<?php if ( is_page(15) ) : //id15の場合の処理 ?>
ここにHTMLを書く
<?php else: ?>
<?php endif; ?>



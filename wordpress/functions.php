<?php
/**
 * @package WordPress
 * @subpackage il-responsive 
 **/
?>
<?php
//img srcの記述を拾って画像を取得し表示する
//以下を記述
/* <img src="<?php echo catch_that_image(); ?>" alt="<?php the_title(); ?>" /> */
function catch_that_image() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    $first_img = $matches [1] [0];
  
if(empty($first_img)){ //Defines a default image
        $first_img = "/images/default.jpg";
    }
    return $first_img;
}
?>
<?php
function is_mobile(){
  $useragents = array(
    'iPhone', // iPhone
    'iPod', // iPod touch
    'Android.*Mobile', // 1.5+ Android *** Only mobile
    'Windows.*Phone', // *** Windows Phone
    'dream', // Pre 1.5 Android
    'CUPCAKE', // 1.5+ Android
    'blackberry9500', // Storm
    'blackberry9530', // Storm
    'blackberry9520', // Storm v2
    'blackberry9550', // Storm v2
    'blackberry9800', // Torch
    'webOS', // Palm Pre Experimental
    'incognito', // Other iPhone browser
    'webmate' // Other iPhone browser
  );
  $pattern = '/'.implode('|', $useragents).'/i';
  return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}
?>
<?php
##タイトルの文字数を制限してＸ文字以上を…にする方法
function trim_str_by_chars( $str, $len, $echo = true, $suffix = '…',
$encoding = 'UTF-8' ) {
if ( ! function_exists( 'mb_substr' ) || ! function_exists( 'mb_strlen' ) ) {
return $str;
}
$len = (int)$len;
if ( mb_strlen( $str = wp_specialchars_decode( strip_tags( $str ),
ENT_QUOTES, $encoding ), $encoding ) > $len ) {
$str = wp_specialchars( mb_substr( $str, 0, $len, $encoding ) . $suffix );
}
if ( $echo ) {
echo $str;
} else {
return $str;
}
}
?>
<?php
// パンくずリスト
function breadcrumb(){
    global $post;
    $str ='';
    if(!is_home()&&!is_admin()){ /* !is_admin は管理ページ以外という条件分岐 */
        $str.= '<div class="breadcrumb">';
        $str.= '<ul>';
        $str.= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . home_url('/') .'" class="home" itemprop="url" ><span itemprop="title">HOME</span></a></li>';
        
        /* 投稿のページ */
        if(is_single()){
            $categories = get_the_category($post->ID);
            $cat = $categories[0];
            if($cat -> parent != 0){
                $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
                foreach($ancestors as $ancestor){
                    $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_category_link($ancestor).'"  itemprop="url" ><span itemprop="title">'. get_cat_name($ancestor). '</span></a></li>';
                                    }
            }
            $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_category_link($cat -> term_id). '" itemprop="url" ><span itemprop="title">'. $cat-> cat_name . '</span></a></li>';
            $str.= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'. $post -> post_title .'</span></li>';
        } 
        
        /* 固定ページ */
        elseif(is_page()){
            if($post -> post_parent != 0 ){
                $ancestors = array_reverse(get_post_ancestors( $post->ID ));
                foreach($ancestors as $ancestor){
                    $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_permalink($ancestor).'" itemprop="url" ><span itemprop="title">'. get_the_title($ancestor) .'</span></a></li>';
                                    }
            }
            $str.= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'. $post -> post_title .'</span></li>';
        }
        
        /* カテゴリページ */   
        elseif(is_category()) {
            $cat = get_queried_object();
            if($cat -> parent != 0){
                $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
                foreach($ancestors as $ancestor){
                    $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_category_link($ancestor) .'" itemprop="url" ><span itemprop="title">'. get_cat_name($ancestor) .'</span></a></li>';
                }
            }
            $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'. $cat -> name . '</span></li>';
        }
        
        /* タグページ */
        elseif(is_tag()){
            $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'. single_tag_title( '' , false ). '</span></li>';
        } 
        
        /* 時系列アーカイブページ */
        elseif(is_date()){
            if(get_query_var('day') != 0){
                $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_year_link(get_query_var('year')). '" itemprop="url" ><span itemprop="title">' . get_query_var('year'). '年</span></a></li>';
                $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_month_link(get_query_var('year'), get_query_var('monthnum')). '" itemprop="url" ><span itemprop="title">'. get_query_var('monthnum') .'月</span></a></li>';
                $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'. get_query_var('day'). '</span>日</li>';
            } elseif(get_query_var('monthnum') != 0){
                $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_year_link(get_query_var('year')) .'" itemprop="url" ><span itemprop="title">'. get_query_var('year') .'年</span.</a></li>';
                $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'. get_query_var('monthnum'). '</span>月</li>';
            } else {
                $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'. get_query_var('year') .'年</span></li>';
            }
        }   

        /* 投稿者ページ */
        elseif(is_author()){
            $str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">投稿者 : '. get_the_author_meta('display_name', get_query_var('author')).'</span></li>';
        }   
        
        /* 添付ファイルページ */
        elseif(is_attachment()){
            if($post -> post_parent != 0 ){
                $str.= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_permalink($post -> post_parent).'" itemprop="url" ><span itemprop="title">'. get_the_title($post -> post_parent) .'</span></a></li>';
            }
            $str.= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' . $post -> post_title . '</span></li>';
        }

        /* 検索結果ページ */
        elseif(is_search()){
            $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">「'. get_search_query() .'」で検索した結果</span></li>';
        } 
        
        /* 404 Not Found ページ */
        elseif(is_404()){
            $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">お探しの記事は見つかりませんでした。</span></li>';
        } 
                
        /* その他のページ */
        else{
            $str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'. wp_title('', false) .'</span></li>';
        }
        $str.='</ul>';
        $str.='</div>';
    }
    echo $str;
}
?>

<?php
## p br自動挿入削除
remove_filter ( 'the_content', 'wpautop' );
remove_filter ( 'the_excerpt', 'wpautop' );
?>


<?php
## カテゴリーをひとつのみ選択
function limit_checkbox_amount() {
echo '<script type="text/javascript">
  //<![CDATA[
jQuery("ul#categorychecklist").before("<p>1つしか選択できません</p>");
    var count = jQuery("ul#categorychecklist li input[type=checkbox]:checked").length;
    var not = jQuery("ul#categorychecklist li input[type=checkbox]").not(":checked");
    if(count >= 1) { not.attr("disabled",true);}else{ not.attr("disabled",false);}

jQuery("ul#categorychecklist li input[type=checkbox]").click(function(){
    var count = jQuery("ul#categorychecklist li input[type=checkbox]:checked").length;
    var not = jQuery("ul#categorychecklist li input[type=checkbox]").not(":checked");
    if(count >= 1) { not.attr("disabled",true);}else{ not.attr("disabled",false);}
});
  //]]>
  </script>';
}
add_action('admin_footer', 'limit_checkbox_amount');
?>
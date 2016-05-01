# css
## 見出し
これはcss管理用のリポジトリの予定  
ご自由にお使いください

## gulpの設定
こちらの記事を参考にしています。
http://liginc.co.jp/web/tutorial/117900

### gulpfileとフォルダ
一つ上の階層にwwwフォルダを作成して、
gulpfile.jsを作成しています。

インストールしているものはgulpfile.jsを参考に。

実行にしたらguideフォルダが作成されて、
その中にスタイルのガイドラインが作成されています。

フォルダ構成はまた変更するかもしれません。

var gulp = require("gulp");
var sass = require("gulp-sass");
var autoprefixer = require("gulp-autoprefixer");
var frontnote = require("gulp-frontnote");
var browser = require("browser-sync");
 
gulp.task("server", function() {
    browser({
        server: {
            baseDir: "guide"
        }
    });
});

gulp.task("default", function() {
    gulp.watch("www/sass/**/*.scss",["sass"]);
});
 
gulp.task("sass", function() {
    gulp.src("www/sass/**/*scss")
    .pipe(frontnote({
            css: '../css/style.css'
        }))
        .pipe(sass())
        .pipe(autoprefixer())
        .pipe(gulp.dest("www/css"))
        .pipe(browser.reload({stream:true}));
});

gulp.task("default",['server'], function() {
    gulp.watch("www/sass/**/*.scss",["sass"]);
});



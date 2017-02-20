<?php $this->start('static') ?>
<!-- ueditor -->
<link href="/lib/ueditor/third-party/SyntaxHighlighter/styles/shThemeDjango.css" rel="stylesheet" type="text/css" />  
<!--<link href="/lib/highlight/styles/monokai-sublime.css" rel="stylesheet" type="text/css" />-->  
<script src="/lib/ueditor/third-party/SyntaxHighlighter/shCore.js" ></script>
<script src="/lib/highlight/highlight.pack.js" ></script>
<link href="/lib/lightbox/css/lightbox.css" rel="stylesheet">
<link rel="stylesheet" href="/wpadmin/lib/editor.md/css/editormd.min.css" />
<style>
    code{
        background: none;
        font-size:100%;
    }
    .syntaxhighlighter .gutter{
        color: white;
    }
</style>
<?php $this->end('static') ?>
<?php $this->start('seo')?>
<meta name="keywords" content="<?=$blog->keywords?>"/>
<meta name="description" content="<?=$blog->description?>"/>
<?php $this->end('seo') ?>
<div class="container" ng-app="blogApp" ng-controller="blogCont">
    <div class="det_pic">
        <h1 class="text-center"><?= $blog->title ?></h1>
    </div>
    <div id="blog" class="det_text">
        <?= $blog->content ?>
    </div>
    <ul class="links">
        <li><i class="date"> </i><span class="icon_text"><?=  _($blog->ctime)?></span></li>
        <li><a href="#"><i class="admin"> </i><span class="icon_text">Admin</span></a></li>
        <li class="last"><a href="#"><i class="permalink"> </i><span class="icon_text">Permalink</span></a></li>
    </ul>
    <ul class="links_middle">
        <li><a href="#"><i class="title-icon"> </i><span class="icon_text">Lorem Ipsum Dolore</span></a></li>
        <li><i class="tags"> </i><span class="icon_text">No tags</span></li>
    </ul>
    <ul class="links_bottom">
        <li><a href="#"><i class="comments"> </i><span class="icon_text">5 Comments</span></a></li>
        <li><i class="views"> </i><span class="icon_text">49</span></li>
        <li><i class="likes"> </i><span class="icon_text">12</span></li>
    </ul>
    <div class="comments1">
        <h4>COMMENTS</h4>
        <div class="comments-main">
            <div class="col-md-2 cmts-main-left">
                <img src="/home/images/avatar.png" alt="">
            </div>
            <div class="col-md-10 cmts-main-right">
                <h5>TOM BROWN</h5>
                <p>Vivamus congue turpis in augue pellentesque scelerisque. Pellentesque aliquam laoreet sem nec ultrices. Fusce blandit nunc vehicula massa vehicula tincidunt. Nam venenatis cursus urna sed gravida. Ut tincidunt elit ut quam malesuada consequat. Sed semper purus sit amet lorem elementum faucibus.</p>
                <div class="cmts">
                    <div class="cmnts-left">
                        <p>On April 14, 2014, 18:01</p>
                    </div>
                    <div class="cmnts-right">
                        <a href="#">Reply</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="lev">
        <div class="leave">
            <h4>Leave a comment</h4>
        </div>
        <form id="commentform">
            <label for="author">Name</label>
            <input id="author" name="author" type="text" value="" size="30" aria-required="true">
            <label for="email">Email</label>
            <input id="email" name="email" type="text" value="" size="30" aria-required="true">
            <label for="url">Website</label>
            <input id="url" name="url" type="text" value="" size="30">
            <label for="comment">Comment</label>
            <textarea></textarea>
            <div class="clearfix"></div>
            <input name="submit" type="submit" id="submit" value="Send">
            <div class="clearfix"></div>
        </form>
    </div>
</div>
<?php $this->start('script'); ?>
<script src="/lib/imagesloaded.pkgd.min.js"></script>
<script src="/lib/lightbox/js/lightbox.js"></script>
<script>
    var app = angular.module('blogApp', []);
    app.controller('blogCont', function ($scope) {
        $scope.blog = <?= json_encode($blog) ?>;
    });
    SyntaxHighlighter.all();
    $('#blog').imagesLoaded()
            .always(function (instance) {
                // console.log('all images loaded');
            })
            .done(function (instance) {
            })
            .fail(function () {
                //console.log('all images loaded, at least one is broken');
            })
            .progress(function (instance, image) {
                var result = image.isLoaded ? 'loaded' : 'broken';
                var href = image.img.src;
                var title = image.img.title;
                var alt = image.img.alt;
                var $img = $(image.img);
                $img.replaceWith('<a href="' + href + '" data-lightbox="' + title + '" data-title="' + title + '"><img src="' +
                        href + '" title="' + title + '" alt="' + alt + '"/></a>');
                //console.log('image is ' + result + ' for ' + image.img.src);
            });

</script>
<?php $this->end('script'); ?>
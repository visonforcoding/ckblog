<div class="container" ng-app="blogApp">
    <div id="left_box" class="pull-left" ng-controller="blogCont">	
        <div id="masonry" class="l_g">
            <div class="blog-box card" ng-repeat="blog in blogs">
                <div class="l_g_r">
                    <div class="dapibus">
                        <h2>{{ blog.title}}</h2>
                        <p class="adm">Posted by <a href="#">{{blog.admin.nick}}</a>  |  {{ blog.ctime_str }}</p>
                        <a href="/blog/{{ blog.id }}"><img ng-src="{{ blog.cover}}" class="img-responsive" alt=""></a>
                        <p>{{ blog.guide}}</p>
                        <a href="/blog/{{ blog.id }}" class="link">Read More</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <!----//--->
        <div id="loadMore" ng-click="loadMore()">查看更多</div>
        <div id="showLess">Show less</div>
    </div>
    <div id="right_box" class="pull-right">
        <div class=" integ">
            <div class="l_g_r">
                <div class="posts">
                    <h4>阅读排行</h4>
                    <?php foreach ($rankBlogs as $item): ?>
                    <h6><a href="/blog/<?=$item->id?>"><?=$item->title?></a></h6>
                    <?php endforeach;?>
                </div>
                <div class="comments">
                    <h4>最新评论</h4>
                    <h6><a href="#">Amada Doe <span>on</span> Hello World!</a></h6>
                    <h6><a href="#">Peter Doe <span>on</span> Photography</a></h6>
                    <h6><a href="#">Steve Roberts <span>on</span> HTML5/CSS3</a></h6>
                    <h6><a href="#">Doe Peter<span>on</span> Photography</a></h6>
                </div>
                <div class="categories" ng-controller="categoryCont">
                    <h4>分类</h4>
                    <h6 ng-repeat="category in categories"><a href="/blog/list-{{category.catid}}"> {{ category.name}} ({{category.counts}})</a></h6>
                </div>
                <div class="archievs" ng-controller="archievsCont">
                    <h4>Archives</h4>
                    <h6 ng-repeat="archive in archives"><a href="#">{{ archive.cmonth }} {{archive.cyear}}</a></h6>
                </div>

            </div>
        </div>
    </div>
</div>
<nav id="page-nav">
  <a href="<?=$navLink?>"></a>
</nav>
<?php $this->start('script'); ?>
<script src="/lib/jquery.infinitescroll.min.js"></script>
<script src="/lib/masonry/masonry.js"></script>
<script src="/lib/imagesloaded.pkgd.min.js"></script>
<script>
    var app = angular.module('blogApp', []);
    app.controller('blogCont', function ($scope,$http) {
        $scope.page = 2;
        $scope.blogs = <?= json_encode($blogs) ?>;
        $scope.loadMore = function(){
            $http.get("/index/load-more/"+$scope.page+'.json').success(function(response){
                console.log(response);
                //$scope.newslist = response.articles;
            });
        };
    });
    app.controller('archievsCont',function($scope){
        $scope.archives = <?= json_encode($archives) ?>;
    });
    app.controller('categoryCont',function($scope){
        $scope.categories = <?= json_encode($blogCats) ?>;
    });
    setTimeout(function(){$('#masonry').imagesLoaded()
            .always(function (instance) {
                 console.log('all images loaded');
            })
            .done(function (instance) {
                $('#masonry').masonry({
                    // options
                    itemSelector: '.blog-box',
                    columnWidth: 0
                });
                //console.log('all images successfully loaded');
            })
            .fail(function () {
                //console.log('all images loaded, at least one is broken');
            })
            .progress(function (instance, image) {
                var result = image.isLoaded ? 'loaded' : 'broken';
                //console.log('image is ' + result + ' for ' + image.img.src);
            });},500);
        var _renderItem = function(data) {
                return   '<div class="blog-box card">'+
                '<div class="l_g_r">'+
                    '<div class="dapibus">'+
                        '<h2>'+ data.title+'</h2>'+
                        '<p class="adm">Posted by <a href="#">'+data.admin.nick+'</a>  | '+ data.ctime_str +'</p>'+
                        '<a href="/blog/'+ data.id +' "><img src='+data.cover+' " class="img-responsive" alt=""></a>'+
                        '<p>'+ data.guide+'</p>'+
                        '<a href="/blog/'+data.id +' " class="link">Read More</a>'+
                    '</div>'+
                '</div>'+
            '</div>';
        }
	
        $('#masonry').infinitescroll({
                navSelector  	: "#page-nav",
                nextSelector 	: "#page-nav a",
                itemSelector 	: "#masonry .blog-box",
                debug		: true,
                dataType                  :'json',
                animate      : true,    
                bufferPx     : 40,
                loading: {
                     finishedMsg: '没有更多了.',
                     img: 'http://i.imgur.com/6RMhx.gif',
                     msgText: "<em>Loading the next set of posts...</em>",
                    },
//                 behavior		: 'twitter',
                 appendCallback	: false, // USE FOR PREPENDING
                // pathParse     	: function( pathStr, nextPage ){ return pathStr.replace('2', nextPage ); }
            }, function(res){
                var jsonData = res.blogs;
                //console.log(items);
                $container = $('#masonry');
                var newElements = "";
                $.each(jsonData,function(i,n){
                    var item = _renderItem(n);
                    newElements += item;
                });
                    var $newElems = $( newElements ).css({ opacity: 0 });
                    // ensure that images load before adding to masonry layout
                    $newElems.imagesLoaded(function(){
//                     // show elems now they're ready
                    $newElems.animate({ opacity: 1 });
                    $container.prepend($newElems).masonry( 'appended', $newElems, true ); 
                 });
//    	window.console && console.log('context: ',this);
//    	window.console && console.log('returned: ', newElements);
    });
    	
        
        

</script>
<?php $this->end('script'); ?>
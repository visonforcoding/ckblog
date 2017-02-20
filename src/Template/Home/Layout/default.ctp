<!DOCTYPE HTML>
<html>
    <head>
        <title><?= $pageTitle ?>| 麦穗博客</title>
        <link href="/home/css/bootstrap.css" rel="stylesheet" type="text/css" media="all"/>
        <link href="/home/css/style.css" rel="stylesheet" type="text/css" media="all" />
        <link href="/home/css/base.css" rel="stylesheet" type="text/css" media="all" />
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php if ($this->fetch('seo')): ?>
            <?= $this->fetch('seo') ?>
        <?php else: ?>
        <meta name="keywords" content="php,java,python,jquery,nodejs,开发,程序员"/>
        <meta name="description" content="程序员博客，技术分享"/>
        <?php endif; ?>
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <script src="/home/js/jquery.min.js"></script>
        <script src="/lib/angular.min.js"></script>
        <?= $this->fetch('static') ?>
    </head>
    <body>
        <!-- header -->
        <div class="banner">
            <div class="container">
                <div class="header">
                    <div class="logo">
                        <a href="index.html"><img src="/home/images/avatar/batman.jpg" style="width:120px;" class="img-circle img-thumbnail" alt="" /></a>
                    </div>
                    <div class="header-right">
                        <ul>
                            <li><a href="#"><i class="fb"> </i></a></li>
                            <li><a href="#"><i class="twt"> </i></a></li>
                            <li>
                                <div class="search2">
                                    <form>
                                        <input type="text" value="Search.." onfocus="this.value = '';" onblur="if (this.value == '') {
                                                    this.value = 'Search..';
                                                }">
                                        <input type="submit" value="">
                                    </form>
                                </div></li>
                            <div class="clearfix"></div>
                        </ul>

                    </div>
                    <div class="clearfix"> </div>
                </div>
                <div class="head-nav">
                    <?= $this->element('menu',['active'=>$active]); ?>
                </div>

                <!-- script-for-nav -->
                <script>
                    $("span.menu").click(function () {
                        $(".head-nav ul").slideToggle(300, function () {
                            // Animation complete.
                        });
                    });
                </script>
                <!-- script-for-nav --> 					 
            </div> 
        </div>
        <!-- header -->

        <!-- content -->
        <div class="content">
            <?= $this->fetch('content') ?>
        </div>
        <!-- content -->	
        <!-- footer -->
        <div class="footer">
            <div class="container">
                <div class="col-md-3 copy">
                    <div class="top1">
                        <i class="ham"></i>
                    </div>
                    <div class="top2">
                        <h6>Copyrights © 曹麦穗 </h6>
                        <p>Cakephp 3+ 驱动</p>
                    </div>
                    <div class="clearfix"> </div>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <!-- footer -->
        <!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//piwik.rc5j.cn/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//piwik.rc5j.cn/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
        <?= $this->fetch('script') ?>
    </body>
</html>
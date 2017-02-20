<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>swoole聊天demo - 麦穗博客</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="swoole,php,websocket,vue,聊天系统,webim,redis"/>
        <meta name="description" content="基于swoole+vue+redis+websocket的聊天室"/>
        <script src="/static/js/jquery.js"></script>
        <link href="/static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/static/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="/static/css/chat.css" rel="stylesheet">
    </head>
    <body>
        <div id="chat" class="bootstrap snippet">
            <div class="tile tile-alt" id="messages-main">
                <div class="ms-menu">
                    <div id="profile" class="ms-user clearfix">
                        <img v-bind:src="profile.avatar" alt="" class="img-avatar pull-left">
                        <div>今天天气不错 <br> {{profile.nick}}</div>
                    </div>

                    <div class="p-15">
                        <div class="dropdown">
                            <a class="btn btn-primary btn-block" href="" data-toggle="dropdown">Messages <i class="caret m-l-5"></i></a>

                            <ul class="dropdown-menu dm-icon w-100">
                                <li><a href=""><i class="fa fa-envelope"></i> Messages</a></li>
                                <li><a href=""><i class="fa fa-users"></i> Contacts</a></li>
                                <li><a href=""><i class="fa fa-format-list-bulleted"> </i>Todo Lists</a></li>
                            </ul>
                        </div>
                    </div>

                    <div id="user-list" class="list-group lg-alt">
                        <a class="list-group-item media" href="">
                            <div class="pull-left">
                                <img src="http://chat.rc5j.cn/resource/avatar/avatar_1.jpg" alt="" class="img-avatar">
                            </div>
                            <div class="media-body">
                                <div class="list-group-item-heading">曹麦穗</div>
                                <small class="list-group-item-text c-gray">我是管理员</small>
                            </div>
                        </a>
                        <a class="list-group-item media" href="" v-for="user in users">
                            <div class="pull-left">
                                <img v-bind:src="user.avatar" alt="" class="img-avatar">
                            </div>
                            <div class="media-body">
                                <div class="list-group-item-heading">{{user.nick}}</div>
                                <small class="list-group-item-text c-gray">这家伙很懒,什么都没写</small>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="ms-body">
                    <div class="action-header clearfix">
                        <div class="visible-xs" id="ms-menu-trigger">
                            <i class="fa fa-bars"></i>
                        </div>

                        <div id="room" class="pull-left hidden-xs">
                            <img src="http://bootdey.com/img/Content/avatar/avatar2.png" alt="" class="img-avatar m-r-10">
                            <div class="lv-avatar pull-left">

                            </div>
                            <span>swoole聊天室</span>
                            <small>创建于{{room.create_time}}</small>
                        </div>

                        <ul class="ah-actions actions">
                            <li>
                                <a href="">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <i class="fa fa-check"></i>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <i class="fa fa-clock-o"></i>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-sort"></i>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a href="">Latest</a>
                                    </li>
                                    <li>
                                        <a href="">Oldest</a>
                                    </li>
                                </ul>
                            </li>                             
                            <li class="dropdown">
                                <a href="" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-bars"></i>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a href="">Refresh</a>
                                    </li>
                                    <li>
                                        <a href="">Message Settings</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="msg-content" id="msg-content">
                        <div class="message-feed media" v-for="msg in msgs">
                            <template v-if="(msg.msgtype == 'newuser')||(msg.msgtype == 'userout') ">
                                <div class="text-center">
                                    系统消息:{{msg.body}}
                                </div>
                            </template>
                            <template v-if="(msg.msgtype=='msg')||(msg.msgtype == 'sys')">
                                <div class="pull-left">
                                    <img v-bind:src="msg.user.avatar" alt="" class="img-avatar">
                                </div>
                                <div class="media-body">
                                    <div class="nick">{{msg.user.nick}}</div>
                                    <div class="mf-content">
                                        <template v-if="msg.msgtype=='msg'">
                                            {{msg.body}}
                                        </template>
                                        <template v-else="msg.msgtype=='sys'">
                                            {{{msg.body}}}
                                        </template>
                                    </div>
                                    <small class="mf-date"><i class="fa fa-clock-o"></i> {{new Date(parseInt(msg.timestamp) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ')}}</small>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="msb-reply">
                        <textarea  placeholder="[Ctrl+enter 发送消息]"></textarea>
                        <button id="send"><i class="fa fa-paper-plane-o"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <script src="/static/bootstrap/js/bootstrap.min.js"></script>
        <script src="/static/vue/vue.js"></script>
        <script type="text/javascript">
var vm = new Vue({
    el: '#chat',
    data: {
        msgs: [],
        users: [],
        profile: [],
        room: {
            nick: '',
            create_time: ''
        },
        computed: {
            // 一个计算属性的 getter
        }
    }
});
$(function () {
    var ws = new WebSocket("ws://120.24.245.100:9502");
//    var ws = new WebSocket("ws://192.168.33.10:9502");
    setHeight();
    $(window).bind('resize', function () {
        setHeight();
    });
    $(document).keydown(function (e) {
        if (e.which === 13 && e.ctrlKey) {
            $('#send').trigger('click');
        }
    });
    ws.onopen = function (event) {
        console.log(event);
        //ws.send("接入服务器");
    };
    ws.onmessage = function (event) {
        console.log(event.data);
        var data = JSON.parse(event.data);
        if (data.msgtype === 'userlist') {//第一次进入聊天室 初始化信息
            data.profile.avatar = 'http://chat.rc5j.cn/resource/avatar/' + data.profile.avatar;
            vm.profile = data.profile;
            vm.room.create_time = data.create_time;
            $.each(data.items, function (i, n) {
                data.items[i]['avatar'] = 'http://chat.rc5j.cn/resource/avatar/' + n.avatar;
            });
            var userlist = data.items;
            vm.users = vm.users.concat(userlist);
        }
        if (data.msgtype === 'newuser') {//新加入了用户 更新用户列表
            var item = data.user;
            item.avatar = 'http://chat.rc5j.cn/resource/avatar/' + item.avatar;
            vm.users.push(item);
        }
        if (data.msgtype === 'userout') {//用户退出 更新用户列表
            var item = data.user;
            vm.users.$remove(vm.users.find(function (a) {
                return a.fd === item.fd;
            }));
        }
        if(data.msgtype !=='userlist'){
            if(data.msgtype === 'msg'||data.msgtype ==='sys'){
                data.user.avatar = 'http://chat.rc5j.cn/resource/avatar/' + data.user.avatar;
            }
            vm.msgs.push(data);
        }
        $('.msg-content').animate({scrollTop: $(".msg-content").get(0).scrollHeight}, 'slow');  //滚动条自动到底部
    };
    $('#send').click(function () {
        var content = $(this).prev().val();
        if (content) {
            ws.send(content);
            $(this).prev().val('');
        }
    });
    if ($('#ms-menu-trigger')[0]) {
        $('body').on('click', '#ms-menu-trigger', function () {
            $('.ms-menu').toggleClass('toggled');
        });
    }
});
function setHeight() {
    $('.ms-body').height($(window).height());
    $('.ms-menu').height($(window).height() - 67);
    $('#user-list').height($(window).height() - 67 - 67 - 62);
    $('.msg-content').height($(window).height() - 60 - 120);
}
        </script>
    </body>
</html>
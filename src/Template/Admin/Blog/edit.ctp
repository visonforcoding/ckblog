<?php $this->start('static') ?>   
<link href="/wpadmin/lib/jqupload/uploadfile.css" rel="stylesheet">
<link href="/wpadmin/lib/jqvalidation/css/validationEngine.jquery.css" rel="stylesheet">
<?php $this->end() ?> 
<div class="work-copy">
    <?= $this->Form->create($blog, ['class' => 'form-horizontal']) ?>
    <div class="form-group">
        <label class="col-md-2 control-label">category_id</label>
        <div class="col-md-8">
            <?php
            echo $this->Form->input('category_id', ['label' => false, 'options' => $blogcat,
                'empty' => true, 'class' => 'form-control']);
            ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">admin_id</label>
        <div class="col-md-8">
            <?php
            echo $this->Form->input('admin_id', ['label' => false, 'options' => $admin,
                'empty' => true, 'class' => 'form-control']);
            ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">标题</label>
        <div class="col-md-8">
            <?php
            echo $this->Form->input('title', ['label' => false, 'class' => 'form-control']);
            ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">引言</label>
        <div class="col-md-8">
            <?php
            echo $this->Form->input('guide', ['label' => false, 'class' => 'form-control']);
            ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">封面</label>
        <div class="col-md-8">
            <div  class="img-thumbnail input-img"  single>
                <img  alt="请上传宽为690，高小于388的封面图" src="<?=$blog->cover?>"/>
            </div>
            <div style="color:red">请上传宽为690，高小于388的封面图</div>
            <input name="cover" value="<?=$blog->cover?>"  type="hidden"/>
            <div  class="choiceImg btn btn-primary">上传</div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">内容</label>
        <div class="col-md-8">
            <script name="content" id="content"><?=$blog->content?></script>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">seo关键字</label>
        <div class="col-md-8">
            <?php
            echo $this->Form->input('keywords', ['label' => false, 'class' => 'form-control']);
            ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">seo描述</label>
        <div class="col-md-8">
            <?php
            echo $this->Form->input('description', ['label' => false, 'class' => 'form-control']);
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-2 col-md-10">
            <input type='submit' id='submit' class='btn btn-primary' value='保存' data-loading='稍候...' /> 
        </div>
    </div>
<?= $this->Form->end() ?>
</div>

<?php $this->start('script'); ?>
<script type="text/javascript" src="/wpadmin/lib/jqform/jquery.form.js"></script>
<script type="text/javascript" src="/wpadmin/lib/jqupload/jquery.uploadfile.js"></script>
<script type="text/javascript" src="/wpadmin/lib/jqvalidation/js/languages/jquery.validationEngine-zh_CN.js"></script>
<script type="text/javascript" src="/wpadmin/lib/jqvalidation/js/jquery.validationEngine.js"></script>
<script src="/wpadmin/lib/ueditor/ueditor.config.js" ></script>
<script src="/wpadmin/lib/ueditor/ueditor.all.js" ></script>
<script href="/wpadmin/lib/ueditor/lang/zh-cn/zh-cn.js" ></script>    
<script>
    $(function () {
         initJqupload('cover', '/wpadmin/util/doUpload?dir=posts', 'jpg,png,gif,jpeg'); //初始化图片上传
        var ue = UE.getEditor('content'); //初始化富文本编辑器
        $('form').validationEngine({focusFirstField: true, autoPositionUpdate: true, promptPosition: "bottomRight"});
        $('form').submit(function () {
            var form = $(this);
            $.ajax({
                type: $(form).attr('method'),
                url: $(form).attr('action'),
                data: $(form).serialize(),
                dataType: 'json',
                success: function (res) {
                    if (typeof res === 'object') {
                        if (res.status) {
                            layer.alert(res.msg, function () {
                                window.location.href = '/admin/blog/index';
                            });
                        } else {
                            layer.alert(res.msg, {icon: 5});
                        }
                    }
                }
            });
            return false;
        });
    });
</script>
<?php
$this->end();

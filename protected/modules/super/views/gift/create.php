<?php 
cs()->registerCssFile(resBu("kissy_editor/cssreset/reset.css"), 'screen');
cs()->registerCssFile(resBu("kissy_editor/editor/theme/cool/editor-pkg-min-datauri.css"), 'screen');
?>
		
<?php echo CHtml::beginForm('','post',array('name'=>'add', 'enctype'=>'multipart/form-data'));?>
        <table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
        <tr><th colspan="2" class="al f14px indent10px"><h3>发布礼品</h3></th></tr>
            <tr>
                <td width="120" class="ar pa-r10px">礼品名：</td>
                <td><p><?php echo CHtml::activeTextField($model, 'name', array('class'=>'txt', 'style'=>'width:500px')); ?><span class="cred">*</span></p>
                 </td>
            </tr>
            <tr>
                <td class="ar pa-r10px" >礼品图片：</td>
                <td ><?php if ($model->small_pic) { echo '<div>'.$model->smallPicHtml.'</div>'; }?>
			    <?php echo CHtml::activeFileField($model, 'small_pic');?>
			    <input type="hidden" name="Gift[picOriginal]" value="<?php echo $model->small_pic?>"><span class="cred">*</span>
			    <span class="cblue">比例为1:1且尺寸大于150x150的png、jpg、gif格式图片</span>
            </td>                
            </tr>         
            <tr>
                <td class="ar pa-r10px">兑换积分：</td>
                <td ><?php echo CHtml::activeTextField($model, 'integral', array('class'=>'txt')); ?><span class="cred">*</span>
                </td>                
            </tr>
            <tr>
                <td class="ar pa-r10px">礼品详情：</td>
                <td ><?php echo CHtml::activeTextArea($model, 'content', array('style'=>'width:700px;height:300px;margin:0 auto;', 'tabindex'=>"1")); ?><span class="cred">*</span>
                </td>                
            </tr>
        </table>
        <?php
		$this->widget('zii.widgets.jui.CJuiButton',
			array(
				'name' => 'submit',
				'caption' => '提 交',
			)
		);
		?>
      <?php echo CHtml::errorSummary($model); ?>
<?php echo CHtml::endForm();?>
<?php
cs()->registerScriptFile(resBu('kissy_editor/kissy.js'), CClientScript::POS_HEAD);
cs()->registerScriptFile(resBu('kissy_editor/editor/editor-all-pkg.js'), CClientScript::POS_HEAD);
?>
<!--<script src="http://a.tbcdn.cn/s/kissy/1.1.5/kissy-min.js"></script>-->
<!--<script src="http://a.tbcdn.cn/s/kissy/1.1.5/editor/editor-all-pkg-min.js"></script>-->
<script>
    KISSY.ready(function(S) {
        // just for test

        S.use('Gift_content', function() {

            var KE = S.Editor;

            window.editor = KE("#Gift_content", {
                 attachForm:true,
                //编辑器内弹窗z-index底线，防止互相覆盖
                baseZIndex:10000,
                pluginConfig: {
                    "image":{
                        upload:{
                            //返回格式
                            //正确：{imgUrl:""}
                            //错误：{error:""}
                            //中文 \uxxxx 转义
                            serverUrl:"<?php echo url('super/gift/upload')?>",
                            serverParams:{
                                watermark:function() {
                                    return S.one("#ke_img_up_watermark_1")[0].checked;
                                }
                            },
                            fileInput:"pic",
                            sizeLimit:1000,//k
                            extraHtml:"<p style='margin-top:5px;'><input type='checkbox' id='ke_img_up_watermark_1'> 图片加水印，防止别人盗用</p>"
                        }
                    }
                }
            }).use("elementpaths,sourcearea,preview," +
                    "separator," +
                    "undo,separator,removeformat,font,format,forecolor,bgcolor,separator," +
                    "list,indent,justify,separator,link,image,smiley," +
                    "separator,table,resize,pagebreak,separator,maximize"
                    );
        });
    });

</script>
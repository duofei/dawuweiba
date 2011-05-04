<div class="pa-10px conflict" style="width:500px">
    <h3 class="f18px lh30px f20px bg-pic"> 购物车中包含<?php echo $cart->goods->shop->nameLinkHtml;?>的美食</h3>
    <p class="lh30px f14px pa-l30px ma-l5px">您需要先清空购物车才能继续挑选其他餐厅的美食哦</p> 
    <p class="ma-t10px pa-l30px"><a class="button-yellow" href="javascript:void(0);" onclick="$.colorbox.close();"><span>我先看看</span></a>  
    <a class="button-yellow ma-l20px" href="<?php echo url('cart/clear');?>"><span>清空购物车</span></a>  
    </p>
</div>
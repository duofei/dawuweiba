<?php
/**
* API错误码文件
*/

/**
 * API错误码提示类
 */
class ApiError
{
	/**
	 * 用户验证失败
	 */
	const USER_CHECK_INVALID = 1;

	/**
	 * 缺少method参数
	 */
    const METHOD_NO_PARAM = 100;

    /**
     * 参数不正确
     */
    const METHOD_INVALID = 101;

    /**
     * 缺少apikey参数
     */
    const APIKEY_NO_EXIST = 200;

    /**
     * apikey不能用
     */
    const APIKEY_INVALID = 201;

    /**
     * 缺少输出方法
     */
    const RENDER_NO_METHOD = 300;

    /**
     * 系统参数错误
     */
    const BASE_PARAM_INVALID = 301;

    /**
     * 参数错误
     */
    const ARGS_INVALID = 400;

    /**
     * 不是美食商铺
     */
    const NOT_FOOD_SHOP = 601;

    /**
     * 不是蛋糕商铺
     */
    const NOT_CAKE_SHOP = 602;

    /**
     * 不是鲜花商铺
     */
    const NOT_FLOWER_SHOP = 603;

    /**
     * 积分不够
     */
    const INTEGRAL_NOT_ENOUGH = 701;

    /**
     * 购物车里已存在其它商家的商品
     */
    const CART_OTHERSHOP_GOODS = 801;

    /**
     * 购物车是空的
     */
    const CART_IS_EMPTY = 802;

}
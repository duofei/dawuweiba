<?php
/**
 * 此处的过滤规则为CHtmlPurifier的过滤规则，全部规则都是作用在读取的时候
 */

return array(
    // 文章内容过滤规则
    'content' => array(
        'Core.Encoding' => app()->charset,
        'HTML.Doctype' => 'XHTML 1.0 Transitional',
        'HTML.Allowed' => "*[title], p[style|align], img[src|alt|class], a[href|target|class], span[style], i, em, u, b, strong, strike, ul, ol, li, blockquote, hr, table, th, tr, td, br, font[size]",
        'HTML.Trusted' => true,
        'Attr.AllowedFrameTargets' => "_blank, _self",
        'AutoFormat.AutoParagraph' => true,
        'AutoFormat.RemoveEmpty' => true,
        'AutoFormat.RemoveEmpty.RemoveNbsp' => true,
        'HTML.SafeObject' => true,
        'HTML.SafeEmbed' => true,
        'Filter.YouTube' => true,
    ),
    // wap文章内容过滤规则
    'wapContent' => array(
        'Core.Encoding' => app()->charset,
        'HTML.Allowed' => "p[align], img[src|alt], a[href|target], i, em, u, b, strong, table, th, tr, td, br",
        'Attr.AllowedFrameTargets' => "_blank, _self",
        'AutoFormat.AutoParagraph' => true,
        'AutoFormat.RemoveEmpty' => true,
        'AutoFormat.RemoveEmpty.RemoveNbsp' => true,
    ),
    // 文章概述过滤规则
    'summary' => array(
        'Core.Encoding' => app()->charset,
        'HTML.Allowed' => "*[title], img[src|alt|class], a[href|target|class], b, strong, i, u, strike, span, p",
        'Attr.AllowedFrameTargets' => "_blank, _self",
        'AutoFormat.RemoveEmpty' => true,
        'AutoFormat.RemoveEmpty.RemoveNbsp' => true,
    ),
    // 热门文章在显示概述时的过滤规则
    'hotSummary' => array(
        'Core.Encoding' => app()->charset,
        'HTML.Allowed' => "",
    ),
    // 评论内容过滤规则
    'comment' => array(
        'Core.Encoding' => app()->charset,
        'HTML.Allowed' => "fieldset[class],i, legend",
        'HTML.Trusted' => true,
    	'AutoFormat.RemoveEmpty' => true,
        'AutoFormat.RemoveEmpty.RemoveNbsp' => true,
    ),
);
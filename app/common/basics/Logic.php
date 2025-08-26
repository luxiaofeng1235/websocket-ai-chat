<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\common\basics;


/**
 * 逻辑层基类
 * Class Logic
 * @Author FZR
 * @package app\common\basics
 */
abstract class Logic
{
    /**
     * 错误信息
     * @var string
     */
    protected static $error;

    /**
     * 返回错误信息
     * @access public
     * @return string|array
     */
    public static function getError()
    {
        return self::$error;
    }

    /**
     * 生成uuid-v4
     */
    public static function createUUID()
    {
        // 生成uuid
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * 薅元力过多拉黑名单
     */
    public static $blacklist = [
        // 3370,
        // 8653,
        0
    ];
}
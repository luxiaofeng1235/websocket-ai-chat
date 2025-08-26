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


namespace app\common\server;

use app\common\server\storage\Driver;


/**
 * URL转换 服务类
 * Class UrlServer
 * @package app\common\server
 */
class UrlServer
{
    /**
     * Notes: 获取文件全路径
     * @param string $uri
     * @author 张无忌(2021/1/29 9:42)
     * @return string
     */
    public static function getFileUrl($uri = '', $type = '')
    {
        $uri = (string) $uri; //防止PHP8报错修改句兼容deprecated废弃声明
        if (strstr($uri, 'http://'))
            return $uri;
        if (strstr($uri, 'https://'))
            return $uri;

        $engine = ConfigServer::get('storage', 'default', 'local');
        if (empty($engine) || $engine === 'local') {
            //图片分享处理
            if ($type && $type == 'share') {
                return ROOT_PATH . $uri;
            }
            $domain = request()->domain(true);
        } else {
            $config = ConfigServer::get('storage_engine', $engine);
            $domain = $config['domain'];
        }
        return self::format($domain, $uri);
    }

    /**
     * NOTE: 设置文件路径转相对路径
     * @author: 张无忌
     * @param string $uri
     * @return mixed
     */
    public static function setFileUrl($uri = '')
    {
        $engine = ConfigServer::get('storage', 'default', 'local');
        if (empty($engine) || $engine === 'local') {
            $domain = request()->domain();
            return str_replace($domain . '/', '', $uri);
        } else {
            $config = ConfigServer::get('storage_engine', $engine);
            return str_replace($config['domain'], '', $uri);
        }
    }


    /**
     * @notes 处理域名
     * @param $domain
     * @param $uri
     * @return string
     * @author 段誉
     * @date 2022/6/6 15:41
     */
    public static function format($domain, $uri)
    {
        // 处理域名
        $domainLen = strlen($domain);
        $domainRight = substr($domain, $domainLen - 1, 1);
        if ('/' == $domainRight) {
            $domain = substr_replace($domain, '', $domainLen - 1, 1);
        }

        // 处理uri
        $uriLeft = substr($uri, 0, 1);
        if ('/' == $uriLeft) {
            $uri = substr_replace($uri, '', 0, 1);
        }

        return trim($domain) . '/' . trim($uri);
    }

    public static function getFileSignUrl($uri)
    {
        $config = [
            'default' => ConfigServer::get('storage', 'default', 'local'),
            'engine' => ConfigServer::get('storage_engine') ?? ['local' => []]
        ];
        $StorageDriver = new Driver($config);
        $path = str_replace('https://static.jsss999.com/', '', $uri);
        $url = $StorageDriver->getSignUrl($path);
        $url = str_replace('+', '%2B', $url);
        return $url;
    }
}
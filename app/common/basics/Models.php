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


use app\common\server\UrlServer;
use think\Model;

/**
 * 模型 基类
 * Class Models
 * @Author FZR
 * @package app\common\basics
 */
abstract class Models extends Model
{
    // 定义公共操作 如  删除  切换状态等

    /**
     * NOTE: 修改器-图片转相对路径
     * @author: 张无忌
     * @param $value
     * @return mixed|string
     */
    public function setImageAttr($value)
    {
        return $value ? UrlServer::setFileUrl($value) : '';
    }

    /**
     * NOTE: 获取器-补全图片路径
     * @author: 张无忌
     * @param $value
     * @return string
     */
    public function getImageAttr($value,$data)
    {
        if(!$value && isset($data['goods_snap'])){
            return UrlServer::getFileUrl($data['goods_snap']['image']);
        }
        return $value ? UrlServer::getFileUrl($value) : '';
    }
    
    /**
     * @notes 统一处理用户nickname
     * @param $nickname
     * @return string
     * @author lbzy
     * @datetime 2023-09-06 10:35:17
     */
    function getNicknameAttr($nickname)
    {
        if (in_array(app('http')->getName(), [ 'admin', 'shop' ]) && request()->isAjax()) {
            $nickname = htmlspecialchars($nickname);
        }
    
        return $nickname;
        
    }
}
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


use app\common\server\JsonServer;
use think\response\Json;

/**
 * 验证器基类
 * Class Validate
 * @Author FZR
 * @package app\common\basics
 */
abstract class Validate extends \think\Validate
{
    /**
     * 切面验证接收到的参数
     * @param null $scene (场景验证)
     * @param array $validate_data 验证参数，可追加和覆盖掉接收的参数
     * @author FZR
     * @return mixed|Json
     */
    public function goCheck($scene = null, $validate_data = [])
    {
        // 1.接收参数
        $params = request()->param();
        //合并验证参数
        $params = array_merge($params, $validate_data);

        // 2.验证参数
        if (!($scene ? $this->scene($scene)->check($params) : $this->check($params))) {
            $exception = is_array($this->error)
                ? implode(';', $this->error) : $this->error;
            JsonServer::throw($exception);
        }
        // 3.成功返回数据
        return $params;
    }
}
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


use think\App;
use think\Validate;

/**
 * API接口基类
 * Class Api
 * @Author FZR
 * @package app\common\basics
 */
abstract class Api
{
    /**
     * Request实例
     */
    protected $request;

    /**
     * 应用实例
     */
    protected $app;

    /**
     * 用户ID
     * @var int
     */
    protected $user_id = null;

    /**
     * 用户信息
     * @var array
     */
    public $user_info = [];

    /**
     * 客户端
     * @var null
     */
    public $client = null;

    /**
     * 页码
     * @var int
     */
    public $page_no = 1;

    /**
     * 每页显示条数
     * @var int
     */
    public $page_size = 15;

    /**
     * 无需登录即可访问的方法
     * @var array
     */
    public $like_not_need_login = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app = app();
        $this->request = request();


        // 控制器初始化
        $this->initialize();
        $this->__initialize();
    }

    /**
     * 初始化
     */
    protected function initialize()
    {
        //用户信息
        $this->user_info = $this->request->user_info ?? [];
        if (boolval($this->user_info)) {
            $this->user_id = $this->user_info['id'] ?? null;
            $this->client = $this->user_info['client'] ?? null;
        }

        //分页参数
        $page_no = (int) $this->request->get('page_no');
        $this->page_no = $page_no && is_numeric($page_no) ? $page_no : $this->page_no;
        $page_size = (int) $this->request->get('page_size');
        $this->page_size = $page_size && is_numeric($page_size) ? $page_size : $this->page_size;
        $this->page_size = min($this->page_size, 100);
    }

    protected function __initialize()
    {

    }

    /**
     * @auth 蔡志聪
     * @notes 简化验证器
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
}
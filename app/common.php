<?php
// 应用公共文件
/**
 * Notes: 生成随机长度字符串
 * @param $length
 * @author FZR(2021/1/28 10:36)
 * @return string|null
 */
function getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;

    for ($i = 0;
         $i < $length;
         $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}


/**
 * Notes: 生成密码
 * @param $plaintext
 * @param $salt
 * @author FZR(2021/1/28 15:30)
 * @return string
 */
function generatePassword($plaintext, $salt)
{
    $salt = md5('y' . $salt . 'x');
    $salt .= '2021';
    return md5($plaintext . $salt);
}

/**
 * Notes: 大写字母
 * @author 段誉(2021/4/15 15:55)
 * @return array
 */
function getCapital()
{
    return  range('A','Z');
}

/**
 * note 生成验证码
 * @param int $length 验证码长度
 * @return string
 */
function create_sms_code($length = 4)
{
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= rand(0, 9);
    }
    return $code;
}


/**
 * 是否在cli模式
 */
if (!function_exists('is_cli')) {
    function is_cli()
    {
        return preg_match("/cli/i", php_sapi_name()) ? true : false;
    }
}

/**
 * Notes: 获取文件扩展名
 * @param $file
 */
if (!function_exists('get_extension')) {
    function get_extension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }
}
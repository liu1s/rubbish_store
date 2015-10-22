<?php
/**
 * email隐藏
 *
 * @param $email
 * @param int $preRetain @符号前保留长度
 * @return string
 */
function hideEmail($email, $preRetain = 3)
{
    if (strpos($email, '@') === false) {
        return false;
    }

    $emailArray = explode('@',$email);
    if (strlen($emailArray[0]) >= $preRetain) {
        $prefix = substr($emailArray[0], 0, $preRetain) . '******';
    } else {
        $prefix = substr($emailArray[0], 0, strlen($emailArray[0])) . '******';
    }

    return $prefix . '@' . $emailArray[1];
}

echo hideEmail('12@email.com');
?>

<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2020/12/21
 * Time: 15:00
 */

namespace HyperfHttpAuth;


interface IdentityInterface
{
    public static function findIdentityById($id);
    public function getId();

}

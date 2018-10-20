<?php

namespace app\view;

interface View
{
    public function response();
    public function getUserAgent(): string;
}

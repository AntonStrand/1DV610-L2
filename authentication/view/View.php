<?php

namespace authentication\view;

interface View
{
    public function response();
    public function getUserAgent(): string;
}

<?php

namespace view;

interface IView
{
    public function response();
    public function getUserAgent(): string;
}

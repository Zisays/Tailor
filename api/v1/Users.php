<?php

namespace Api\v1;

use Frame\Api;
use Frame\DB;

class Users
{
    public array $data = ['1', '2', '3'];

    public function select(): void
    {
        Api::success($this->data, '请求成功!');
    }

    public function insert(): void
    {
        Api::success($this->data, '请求成功!');
    }

    public function update(): void
    {
        Api::success($this->data, '请求成功!');
    }

    public function delete(): void
    {
        Api::success($this->data, '请求成功!');
    }
}
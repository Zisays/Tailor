<?php

/**
 * 输出数据
 * @param $data
 * @return void
 */
function x(...$data): void
{
    \Frame\Api::error($data);
}
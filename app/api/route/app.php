<?php

use think\facade\Route;

Route::get(':version/banner/:id', ':version.Banner/getBanner');
Route::get(':version/theme', ':version.Theme/getSimpleList');
Route::get(':version/theme/:id', ':version.Theme/getComplexOne');
Route::get(':version/product/recent', ':version.Product/getRecent');

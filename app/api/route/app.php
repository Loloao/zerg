<?php

use think\facade\Route;

Route::get(':version/banner/:id', ':version.Banner/getBanner');
Route::get(':version/theme', ':version.Theme/getSimpleList');

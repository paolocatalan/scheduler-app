<?php

namespace App\Services;

use Illuminate\Support\Facades\Cookie;

class Timezone
{
  public function select()
  {
    $timezone = (Cookie::has('timezone')) ? Cookie::get('timezone') : 'Europe/Kyiv';

    return $timezone;
  }
}
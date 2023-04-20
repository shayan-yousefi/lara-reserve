<?php

namespace ShayanYS\LaraReserve\Traits\ReservesData;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

trait GetReserves
{

    public function activeReserves(): MorphMany{
        return $this->reserves()->where([[DB::raw('STR_TO_DATE(concat(`reserved_date`," ",`reserved_time`),"%Y-%m-%d %H:%i:%s")'), '>=', now()]])->with(['customer','reservable']);
    }
    public function allReserves():  MorphMany{
        return $this->reserves()->with(['customer','reservable']);
    }

}

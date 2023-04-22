<?php

namespace ShayanYS\LaraReserve\Traits\ReservesData;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;

trait GetReserves
{

    public function activeReserves(): MorphMany{

        if(DB::connection() instanceof SQLiteConnection)
        {
            return $this->reserves()->where([[DB::raw('strftime("%Y-%m-%d %H:%M:%S",strftime("%Y-%m-%d",`reserved_date`) || " " || `reserved_time`)'), '>=', now()]])->with(['customer','reservable']);
        }
        return $this->reserves()->where([[DB::raw('STR_TO_DATE(concat(`reserved_date` , " " , `reserved_time`),"%Y-%m-%d %H:%i:%s")'), '>=', now()]])->with(['customer','reservable']);

    }
    public function allReserves():  MorphMany{
        return $this->reserves()->with(['customer','reservable']);
    }

}

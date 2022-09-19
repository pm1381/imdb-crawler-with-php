<?php

namespace App\Models;

use MyCLabs\Enum\Enum;

class WatchableType extends Enum {
    private const MOVIE = 'Movie';
    private const TV_SEIRES = "TVSeries";
    private const TV_SHOW = "TVShow";
    private const DOCUMENT = "document";
}
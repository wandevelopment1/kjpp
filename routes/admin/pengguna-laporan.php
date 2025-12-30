<?php

foreach (glob(__DIR__ . '/pengguna-laporan/*.php') as $routeFile) {
    require $routeFile;
}

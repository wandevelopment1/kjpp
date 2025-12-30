<?php

foreach (glob(__DIR__ . '/penanggung-jawab/*.php') as $routeFile) {
    require $routeFile;
}

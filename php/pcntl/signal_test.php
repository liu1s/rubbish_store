<?php
declare ( ticks = 1 );
pcntl_signal ( SIGTERM, "sigHandler" );
pcntl_signal ( SIGHUP, "sigHandler" );

$pid = posix_getpid ();

while (true) {
    sleep(1);
}

posix_kill($pid, SIGHUP);

// 信号处理
function sigHandler($iSigno)
{   
    echo $iSigno;
}

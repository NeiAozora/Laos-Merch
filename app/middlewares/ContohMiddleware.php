<?php

class ContohMiddleware extends Middleware 
{
    public function handle()
    {
        echo "<h3>Contoh Middleware Telah terpannggil</h3>";
    }
}
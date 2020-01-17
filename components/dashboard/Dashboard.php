<?php

class Dashboard
{
    public function display()
    {
        ob_start();
        require 'dashboard.html';
        ob_end_flush();
    }
}
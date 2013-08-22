<?php
include_once 'redbeans.php';

R::setup('mysql:host='.DATABASE_HOSTNAME.';dbname='.DATABASE_NAME, DATABASE_USERNAME,DATABASE_PASSWORD);
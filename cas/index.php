<?php
error_reporting(-1);
session_save_path('tmp');
include_once 'includes.php';
include_once 'SPINE/Spine_Core/SpineFrontController.php';
$spine	=	new Spine_FrontController();
$spine->init();
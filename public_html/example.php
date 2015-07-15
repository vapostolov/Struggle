<?php

    /* Set application paths' constants */
    $self = pathinfo(__FILE__);
    if (DIRECTORY_SEPARATOR === "\\") {
        $selfDirname = str_replace(DIRECTORY_SEPARATOR, "/", $self["dirname"]);
    } else {
        $selfDirname = $self["dirname"];
    }
    preg_match("/^(.*)\/(.+)$/", $selfDirname, $matches);
    $rootParentDirectory = str_replace($matches[2], "", $matches[0]);

    define("ROOT_PARENT_DIRECTORY", $rootParentDirectory);
    define("FRAMEWORK_DIRECTORY", $rootParentDirectory . "framework_dir");
    define("CONTROLLERS_DIRECTORY", $rootParentDirectory . "controllers");
    define("MODELS_DIRECTORY", $rootParentDirectory . "models");
    define("VIEWS_DIRECTORY", $rootParentDirectory . "views");

    if (!is_dir(FRAMEWORK_DIRECTORY)) die ("Missing framework directory.");

    require_once(FRAMEWORK_DIRECTORY . "/Struggle.php");
    require_once(FRAMEWORK_DIRECTORY . "/ApiController.php");

    /* Temporary includes */
    require_once(MODELS_DIRECTORY . "/User.php");
    require_once(MODELS_DIRECTORY . "/Storage.php");
    require_once(MODELS_DIRECTORY . "/FileStorage.php");
    require_once(MODELS_DIRECTORY . "/UserRepository.php");

    Struggle($_GET["request"]);

?>
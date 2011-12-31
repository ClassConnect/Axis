<?php
class Axis_URLInterpreter
      {

      var $Command;

      function Axis_URLInterpreter()
            {
            // get rid of any appended GET params (ex: ?_pjax=true)
            if (strpos($_SERVER['REQUEST_URI'], '?') === false) {
                  $reqURL = $_SERVER['REQUEST_URI'];
            } else {
                  $reqURL = substr($_SERVER['REQUEST_URI'], 0, (strpos($_SERVER['REQUEST_URI'], '?')));
            }
            $requestURI = explode('/', $reqURL);
            $scriptName = explode('/',$_SERVER['SCRIPT_NAME']);
            $commandArray = array_diff_assoc($requestURI,$scriptName);
            $commandArray = array_values($commandArray);
            $controllerName = $commandArray[0];
            $controllerFunction = $commandArray[1];
            $parameters = array_slice($commandArray,2);

            // Check if the url is the root.
            // if it is then set the command to the root controller.
            // and _default function.
            if($controllerName == '')
                  {
                  $controllerName = 'root';
                  }

            $this->Command = new Axis_Command($controllerName,$controllerFunction,$parameters);
            }

      function getCommand()
            {
            return $this->Command;
            }
      }
?>
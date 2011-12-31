<?php
class Axis_CommandDispatcher
      {
      var $Command;

      function Axis_CommandDispatcher(&$command)
            {
            $this->Command = $command;
            }

      function isController($controllerName)
            {
            if(file_exists('axis/controllers/'.$controllerName.'/controller.'.$controllerName.'.php'))
                  {
                  return true;
                  }
            else
                  {
                  return false;
                  }
            }


      function Dispatch()
            {
            $controllerName = $this->Command->getControllerName();

            if($this->isController($controllerName) == false)
                  {
                  $controllerName = 'error';
                  }
            include('axis/controllers/'.$controllerName.'/controller.'.$controllerName.'.php');
            $controllerClass = $controllerName."Controller";
            $controller = new $controllerClass($this->Command);
            $controller->execute();
            }
      }
?>
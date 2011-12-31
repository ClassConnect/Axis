<?php
class Axis_Controller
      {
      var $Command;
      function Axis_Controller(&$command)
            {
            $this->Command = $command;
            }

      function _default()
            {

            }
      function _error()
            {

            }
      function execute()
            {
            $functionToCall = $this->Command->getFunction();
            if($this->Command->getFunction() == '')
                  {
                  $functionToCall = 'default';
                  }

            if(!is_callable(array(&$this,'_'.$functionToCall)))
                  {
                  $functionToCall = 'error';
                  }


            call_user_func(array(&$this,'_'.$functionToCall));
            }
      }
?>
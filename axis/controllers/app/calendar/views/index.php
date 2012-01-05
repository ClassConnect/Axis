<?php
$scripts = "<link rel='stylesheet' type='text/css' href='/assets/app/js/calendar/calendar.css' />
<link rel='stylesheet' type='text/css' href='/assets/app/js/calendar/calendar.print.css' media='print' />
<script type='text/javascript' src='/assets/app/js/calendar/fullcalendar.js'></script>";
appHeader('Calendar', $scripts, 3);


$csvSecs = '';
$secArr = getSections();
foreach ($secArr as $tsec) {
  $csvSecs .= $tsec['section_id'] . ',';
}
$csvSecs = substr($csvSecs, 0, strlen($csvSecs) - 1);

?>
<script type='text/javascript'>

  $(document).ready(function() {
  
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    
    $('#calInit').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicWeek,basicDay'
      },

      selectable: true,

      loading: function(isLoading, view) {
        if (isLoading == true) {
          initAsyncBar('<img src="/assets/app/img/box/miniload.gif" style="margin-right:10px;margin-bottom:-1px" /> <span style="font-weight:bolder">Loading calendar entries...</span>', 'yellowBox', 200, 622);
        } else {
          destroyAsyncBar();
        }
      },

      eventClick: function(calEvent, jsEvent, view) {
        $('.twipsy').remove();

        jQuery.facebox({ 
            ajax: '/app/calendar/view/' + calEvent.id
          });

      },

      // me = personal, courses = csv, colleagues = bool, networks = csv
      events: '/app/calendar/feed/?me=1&courses=<?= $csvSecs; ?>',

      select: function(startDate, endDate, allDay, jsEvent, view) {
        var start1 = new Date(startDate);
        var start_hour = start1.getHours();
        var start_min = start1.getMinutes();
        var start_day = start1.getDate();
        var start_year = start1.getFullYear();
        var start_month = start1.getMonth() + 1;
        
        var end1 = new Date(endDate);
        var end_hour = end1.getHours();
        var end_min = end1.getMinutes();
        var end_day = end1.getDate();
        var end_year = end1.getFullYear();
        var end_month = end1.getMonth() + 1;
          jQuery.facebox({ 
            ajax: "/app/calendar/write/add?start=" + escape(start_month + "/" + start_day + "/" + start_year + " " + start_hour + ":" + start_min) + "&end=" + escape(end_month + "/" + end_day + "/" + end_year + " " + end_hour + ":" + end_min)
          });
      },


      eventDragStart: function( event, jsEvent, ui, view ) {
        $('.twipsy').remove();
      },


      eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
        initAsyncBar('<img src="/assets/app/img/box/miniload.gif" style="margin-right:10px;margin-bottom:-1px" /> <span style="font-weight:bolder">Updating calendar entry...</span>', 'yellowBox', 200, 619);
        $('.twipsy').remove();
        $.ajax({
          type: "GET",
          url: "/app/calendar/write/malleable/shift/" + event.id + "/" + dayDelta,
          success: function(data) {
                 // successful write
                 destroyAsyncBar();
          }

        });
      },


      eventResize: function(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
        initAsyncBar('<img src="/assets/app/img/box/miniload.gif" style="margin-right:10px;margin-bottom:-1px" /> <span style="font-weight:bolder">Updating calendar entry...</span>', 'yellowBox', 200, 619);
        $('.twipsy').remove();
        $.ajax({
          type: "GET",
          url: "/app/calendar/write/malleable/resize/" + event.id + "/" + dayDelta,
          success: function(data) {
                 // successful write
                 destroyAsyncBar();
          }

        });
      },


      eventMouseover: function(calEvent,jsEvent) {
        $('.twipsy').remove();
        $(this).twipsy({
          placement: 'below',
          html: true,
          title: function() { return calEvent.body; },
          trigger: 'manual'
        });
        $(this).twipsy('show');
        $('#element').twipsy(true);
    },
    eventMouseout: function(calEvent,jsEvent) {
      $(this).twipsy('hide');
    }
   /*   .twipsy({
    live: true,
    placement: 'right',
    html: true
  });*/


    });
    
  });

</script>
 
      <div class="content"> 
        <div class="row" style="clear:both"> 
          <div class="sectionLeft" style="height:600px"> 
            <button class="btn success" style="margin-left:12px;font-weight:bolder" onClick="jQuery.facebox({ 
    ajax: '/app/calendar/write/add'
  });
  return false;"><img src="/assets/app/img/calendar/calendar.png" style="height:14px;margin-right:7px;margin-bottom:-2px" />Add entry to calendar</button>

  <div style="font-size:11px;color:#666;padding:15px;line-height:1.2em">
    You can also add calendar entries by simply clicking and dragging anywhere on the calendar.
  </div>

          </div> 
          <div class="sectionRight"> 
            <div id="calInit" style="width:690px;margin-left:20px"></div>
          </div> 
        </div> 
      </div>
<?php
appFooter();
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var calendarE2 = document.getElementById('report_cal');
  var calendar2 = new FullCalendar.Calendar(calendarE2, {
    initialView: 'dayGridMonth',
    locale: 'zh-tw',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth timeGridWeek timeGridDay'
    },
    // allDayDefault: false,
    slotMinTime:"08:00:00",
    slotMaxTime:"18:00:00",
    navLinks: true,
    selectable: true, // 點選格子會變色
    // 呈現 events
    eventSources: [
      {   // 全部門日報紀錄 : 背景藍色
        events: function(info, successCallback, failureCallback) {
            $.ajax({
              url: 'report_cal_all',
              type: 'get',
              cache: false,
              dataType: 'json',
              success: function(data, textStatus, jqXHR) {
                // console.log(data);
                var events = [];
                var x = 0;
                for (x in data) {
                  events.push(
                    {
                      id: data[x]['id'],
                      start: data[x]['start'],
                      end: data[x]['end'],
                      title: data[x]['userno_mandarin'],
                      // allDay: data[x]['allDay'],
                      allDay: 1, // 要設定 1 ， 才有背景色
                    }
                  );
                }
                successCallback(events);
              }
            });
        }, // 這裡結束
      },
      {   // 全部門特休紀錄 : 背景灰色
        events: function(info, successCallback, failureCallback) {
            $.ajax({
              url: 'pal_all',
              type: 'get',
              cache: false,
              dataType: 'json',
              success: function(data, textStatus, jqXHR) {
                console.log(data);
                var events = [];
                var x = 0;
                for (x in data) {
                  var startTime = data[x]['start']
                  if(data.status != false){
                    events.push(
                      {
                        id: data[x]['id'],
                        start: data[x]['start'],
                        end: data[x]['end'],
                        title: startTime.substr(11, 5) + data[x]['userno_mandarin'],
                        // allDay: data[x]['allDay'],
                        allDay: 1, // 要設定 1 ， 才有背景色
                      }
                    );
                  }
                }
                successCallback(events);
              }
            });
        }, // 這裡結束
        color: 'gray',
        textColor: 'black',
      },
    ],
  })
  calendar2.render();
});
</script>
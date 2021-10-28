<script>
//** fullcalendar 初始化 JS : 行事曆功能 ** //
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridWeek',
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
      {   // 日報紀錄
        events: function(info, successCallback, failureCallback) {
            $.ajax({
              url: 'event',
              type: 'get',
              cache: false,
              dataType: 'json',
              success: function(data, textStatus, jqXHR) {
                // console.log(data);
                $("#save").val(data['id']); // 清空 value 資料
                var events = [];
                var x = 0;
                for (x in data) {
                  if(data[x]['hospname_utf8'] == null && data[x]['allDay'] == null){ // 如果沒有機關名會顯示 undefine，所以要把 title 拿掉
                    events.push(
                      {
                        id: data[x]['id'],
                        start: data[x]['start'],
                        end: data[x]['end'],
                        allDay: data[x]['allDay']
                      }
                    );
                  } else if(data[x]['hospname_utf8'] == null && data[x]['allDay'] != null) { 
                    events.push(
                      {
                        id: data[x]['id'],
                        //title: data[x]['title'],
                        title: '(全天)' + data[x]['title'],
                        start: data[x]['start'],
                        end: data[x]['end'],
                        allDay: data[x]['allDay']
                      }
                    );
                  } else { // 如果有機關名就顯示 title
                    events.push(
                      {
                        id: data[x]['id'],
                        //title: data[x]['title'],
                        title: data[x]['hospname_utf8'],
                        start: data[x]['start'],
                        end: data[x]['end'],
                        allDay: data[x]['allDay']
                      }
                    );
                  }
                }
                successCallback(events);
              }
            });
        }, // 這裡結束
      },
      {   // 個人特休紀錄
        events: function(info, successCallback, failureCallback) {
          $.ajax({
            url: 'pal',
            type: 'get',
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR) {
              console.log(data);
              var events = [];
              if (data.status != false) {
                var x = 0;
                for (x in data) {
                  var startTime = data[x]['start']
                  events.push(
                    {
                      id: data[x]['id'],
                      title: startTime.substr(11, 5) + data[x]['title'],
                      // title: data[x]['hospname_utf8'],
                      start: data[x]['start'],
                      end: data[x]['end'],
                      allDay: 1
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
      }
    ],
    // 點選舊有的 events 出現視窗
    eventClick: function(info) {
      // console.log(info);
      // console.log('Event: ' + info.event.title);
      // console.log('id: ' + info.event.id); // id 為 String

      // 對照 PalController.php 回傳的 api (個人特休) 設定 id 為 99+
      // 隨意設定一個 id 值(依據 events 資料表)，若 id 非特休事件才開啟視窗
      if( Number(info.event.id) > 305000 ){ 
        $('#form-event-modal').modal('show');
        $.ajax({
          url: 'event/' + info.event.id,
          type: 'get',
          cache: false,
          dataType: 'json',
          success: function(data, textStatus, jqXHR) {
            console.log(data);
            $('#form-event-modal').modal('show');
            $("form")[0].reset();  //清除form資料
            $("#customers option").remove();  //清除客戶清單(假定前一動作點選event)
            //重新填入資料
            // $("#isEventClick").val("eventClick");
            // $("#id").val(data['id']);
            $("#save").val(data['id']); // 塞入 value 資料
            $("#title").val(data['title']);
    
          
            $("#location1").select2({
                tags: true,
                placeholder: data['hospname']
            }).select2('val', data['location1']);
            $("#location1").val(data['location1']);

            if (data['customers'] != null) {
                var customers = data['customers'].split(","); // 字串轉陣列
            } else {
                var customers = null;
            }

            // 介接下一個 AJAX
            $.ajax({
              url: 'cdrcus/' + data['location1'],
              type: 'get',
              cache: false,
              dataType: 'json',
              success: function(cus_data, cus_textStatus, cus_jqXHR) {
                // console.log(cus_data);
                // console.log(customers); // 為一陣列

                var $setMulti  = $("#customers").select2({
                  multiple: true, // 設定多選
                  allowClear: true, // 設定標籤有"叉叉"圖案
                  data: cus_data // 所有機關裡的客戶
                })
                $setMulti.val(customers).trigger("change");
              }
            });

            $("#location2").val(data['location2']);

            for(x in data) {
              if (x == 'jobs') {
                var jobs = data[x].split(",");
                for(i=0; i<jobs.length; i++) {
                  $("#jobs_" + jobs[i]).prop("checked", true);
                }
              }
            }
            $("#start").val(moment(data['start']).format('YYYY-MM-DD HH:mm')).datetimepicker({
              step: 30,
              format: 'Y-m-d H:i',
              allowTimes: [
                '08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00',
                '12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00'
              ]
            });
            $("#end").val(moment(data['end']).format('YYYY-MM-DD HH:mm')).datetimepicker({
              step: 30,
              format: 'Y-m-d H:i',
              allowTimes: [
                '08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00',
                '12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00'
              ]
            });
            if (data['allDay'] == true) {
              $("#allDay").prop("checked",true);
            } else {
              $("#allDay").prop("checked",false);
            }

            $("#description").val(data['description']);
            $("#sales").val(data['sales']);
            $("#sales_sc").val(data['sales_sc']);
            $("#sales_un").val(data['sales_un']);

          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
          }
        });
      }
    },

    // modal 從這裡導入 (等於舊版的 select 功能)
    dateClick: function(info) {
      // alert('Clicked on: ' + info.date.toString());
      // alert('Clicked on: ' + info.dateStr);
      // alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
      // alert('Current view: ' + info.view.type);

      $("#save").val(""); // 清空 value 資料
      $("form")[0].reset();  //清除form資料
      $("#location1").select2({
        tags: true
      });
      $("#customers").select2();

      // 設定 timeGridWeek 的結束時間
      /*
      let getDayTime = info.date;
      let strGetDayTime = moment(getDayTime).format('YYYY-MM-DD HH:mm'); // 得到 "2021-10-14 10:00"
      let sliceTime = strGetDayTime.slice(-2); // 得到 "00" 或 "30"
      let sliceDay = strGetDayTime.substr(0, 14); // 得到 "2021-09-27 00:"
      console.log(moment(getDayTime).format('YYYY-MM-DD HH:mm'));
      console.log(sliceTime);
      console.log(sliceDay);

      let realTime = "";
      if(sliceTime == "00"){ // 假設開始時間是 "00"
        realTime =  sliceDay.concat("30"); // 那結束時間就是 "30"
        console.log(realTime);
      } else { // 假設開始時間是 "30"
        realTime =  sliceDay.concat("00"); // 那結束時間就是 "00"
        console.log(realTime);
      }
      */
     
      let getDayTime = info.date;
      // console.log('getDayTime' + getDayTime); // Tue Aug 10 2021 00:00:00 GMT+0800 (台北標準時間)
      $("#start").val(moment(getDayTime).format('YYYY-MM-DD HH:mm')).datetimepicker({
        step: 30,
        format: 'Y-m-d H:i',
        allowTimes: [
          '08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00',
          '12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00'
        ]
      });
      $("#end").val(moment(getDayTime).format('YYYY-MM-DD HH:mm')).datetimepicker({
        step: 30,
        format: 'Y-m-d H:i',
        allowTimes: [
          '08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00',
          '12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00'
        ]
      });
    
      $('#form-event-modal').modal('show');
      
    },

  })
  calendar.render();
});

// **************************
// 依員工代號帶出 "機關"
$.ajax({
  url: 'cdrcus',
  type: 'get',
  cache: false,
  dataType: 'json',
  success: function(data, cus_textStatus, cus_jqXHR) {
    const locationData = data;
    // let str = '<option selected disabled>無機關代號</option>';
    let str = '<option value="00">無機關代號</option>';
    locationData.forEach(function(item){
      str += `<option value="${item.hos_no}">${item.hospname_utf8}</option>`;
    });
    // console.log(str);
    location1.innerHTML = str;
  }
});

// 依 "機關" 代號帶出客戶名稱
$("#location1").on('change', function() { // 當變動選取欄位 "機關" 時觸發
  $("#customers option").remove();  //清除客戶清單選項
  let getLocation = $("#location1").val(); // 得到選取的 value
  // alert(getLocation);
  $.ajax({
    url: 'cdrcus/' + getLocation,
    type: 'get',
    cache: false,
    dataType: 'json',
    success: function(cus_data, cus_textStatus, cus_jqXHR) {
      $("#customers").select2({
        data: cus_data
      });
    }
  });

});

// (新增) 表單驗證寫入資料庫
$("#formEvent").submit(function(e) {
  e.preventDefault();

  const start_date = ($("#start").val()).substr(8, 2); // 取得日期
  const end_date = ($("#end").val()).substr(8, 2);
  // console.log(start_date);
  if (start_date != end_date) {
    alert("開始與結束時間必須為同一天");
    return;
  }

  const start_time = ($("#start").val()).substr(11, 2); // 取得時間
  const end_time = ($("#end").val()).substr(11, 2);
  if (start_time > end_time) {
    alert("開始時間不得晚於結束時間");
    return;
  }

  let jobsLen = 0; // 計算 "工作項目" 勾選數
  $("input[name='jobs[]']").each(function(){ // 選取 DOM 元素
    if( this.checked ){
      jobsLen++;
    }
  });
  // console.log(jobsLen);
  if (jobsLen==0) {
    alert("工作項目至少勾選一項");
    return;
  }
  
  // POST 入資料庫
  $.ajaxSetup({
      headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
      cache: false,
      dataType: 'json',
      data: $("#formEvent").serialize(),
  });
  console.log($("#formEvent").serialize());

  var id = document.querySelector("#save").value;
  console.log(id);
  if(id != ""){
    $.ajax({
      url: 'event/' + id,
      type: 'put',
      success: function(data, cus_textStatus, cus_jqXHR) {
        //console.log(data);
        $("#form-event-modal").modal("hide");
        window.location.reload(); // 重新整理頁面
      },
      error: function(jqXHR, textStatus, errorThrown) {
          //console.log(textStatus);
      }
    });
  } else {
    // 新增 event
    $.ajax({
      url: 'event',
      type: 'post',
      success: function(data, cus_textStatus, cus_jqXHR) {
        console.log(data);
        $("#form-event-modal").modal("hide");
        window.location.reload(); // 重新整理頁面新增資料才會出現
      },
      error: function(jqXHR, textStatus, errorThrown) {
        //console.log(textStatus);
      }
    });
  }
});

$("#delete").click(function() {
  var id = $("#save").val();
  var isDelete = false;
  isDelete = confirm('確定刪除');
  if (!isDelete) {
      return;
  }
  $.ajax({
      headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
      url: 'event/' + id,
      type: 'delete',
      dataType: 'json',
      success: function(data, textStatus, jqXHR) {
        window.location.reload(); // 重新整理頁面
      },
      error: function(jqXHR, textStatus, errorThrown) {
          //console.log(textStatus);
      }
  });
});

// ***************

</script>

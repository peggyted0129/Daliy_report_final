@extends('layouts/layout') <!--繼承母模板 layouts/layout-->

@section('title', '管理報表')

<!-- 篩選條件表格 : 只有主管才能查詢 $utype='G' | $pdepno='MSA' -->
@section('main')
  @if($utype=='G')
    @include('layouts/filterForm')
  @endif
@endsection

<!-- 查詢結果表格 -->
@section('eventForm')
  <div class="container-fluid mt-6">
    <div class="card" style="font-size:14px">
      <div class="card-header p-5">
        查詢結果 
      </div>
      <div class="card-body p-5">
        @if(isset($events) && !$events->isEmpty())
          <div class="table-responsive">
            <table class="table table-bordered">
              <!-- 補上陣列第一筆的抬頭 -->
              <tr class="font-weight-bolder">
                <td class="table-primary" align="center">日期 {{ date('Y-m-d', strtotime($events[0]->start)) }}</td>
                <td class="table-primary" align="center">部門 {{ $events[0]->pdepno }}</td>
                <td class="table-primary" align="center" colspan="4">業務代號 {{ $events[0]->userno }}</td>
                <td class="table-primary" align="center" colspan="2">業務代表 {{ $events[0]->userno_mandarin }}</td>
              </tr>
              <tr class="font-weight-bolder">
                <td class="table-primary" width="10%">拜訪時間</td>
                <td class="table-primary" width="18%">點名稱</td>
                <td class="table-primary" width="13%">單位</td>
                <td class="table-primary" width="6%">小點業績</td>
                <td class="table-primary" width="8%">客戶名稱</td>
                <td class="table-primary" width="16%">工作項目</td>
                <td class="table-primary" width="23%">備註說明</td>
                <td class="table-primary" width="6%">收款</td>
              </tr>
              <!-- 使用迴圈，設定同日期的在同一抬頭之下 -->
              @foreach($events as $key => $event)   
              <?php $pre = $key - 1; ?>
                @if( isset($events[$pre]) && ($event->sales_date != $events[$pre]->sales_date) )    
                  <tr class="font-weight-bolder">
                    <td class="table-primary" align="center">日期 {{ date('Y-m-d', strtotime($event->start)) }}</td>
                    <td class="table-primary" align="center">部門 {{ $event->pdepno }}</td>
                    <td class="table-primary" align="center" colspan="4">業務代號 {{ $event->userno }}</td>
                    <td class="table-primary" align="center" colspan="2">業務代表 {{ $event->userno_mandarin }}</td>
                  </tr>
                  <tr class="font-weight-bolder">
                    <td class="table-primary" width="10%">拜訪時間</td>
                    <td class="table-primary" width="18%">點名稱</td>
                    <td class="table-primary" width="13%">單位</td>
                    <td class="table-primary" width="6%">小點業績</td>
                    <td class="table-primary" width="8%">客戶名稱</td>
                    <td class="table-primary" width="16%">工作項目</td>
                    <td class="table-primary" width="23%">備註說明</td>
                    <td class="table-primary" width="6%">收款</td>
                  </tr>
                @endif
                <tr>
                  <td>
                    @if($event->allDay == 1) 
                      全天事件
                    @else
                      {{ date('H:i', strtotime($event->start)) }} ~ {{ date('H:i', strtotime($event->end)) }}
                    @endif
                  </td>
                  <td>{{ $event->location1_mandarin}}</td>
                  <td>
                    @if(isset($event->location2))
                        <?php $locations = explode(',', $event->location2); ?>
                        @foreach($locations as $location)
                            <div>{{ $location }}</div>
                        @endforeach
                    @endif
                  </td>
                  <td>
                    {{ $event->sales }}
                  </td>
                  <td>
                    @if(isset($event->customers_mandarin))
                        <?php $customers = explode(',', $event->customers_mandarin); ?>
                        @foreach($customers as $customer)
                            <div>{{ $customer }}</div>
                        @endforeach
                    @endif
                  </td>
                  <td>
                    @if(isset($event->jobs_mandarin))
                        {{ $event->jobs_mandarin }}
                    @endif
                  </td>
                  <td>
                    @if($event->description)
                        <span style="color: #0000C2">
                            {!! nl2br($event->description) !!}
                        </span>
                    @endif
                  </td>
                  <td align="center">--</td>
                </tr>

                <!-- 做業績的總計表格 -->
                <?php $last_userno = $event->userno; ?>
                <?php $last_date = $event->sales_date; ?>
                <?php $next = $key + 1; ?>
                @if(isset($events[$next]) && $event->sales_id!=$events[$next]->sales_id)
                  <tr>
                    <td colspan="7">
                      <table class="table table-bordered">
                        <tr>
                          <td width="10%" align="center"></td>
                          <td width="10%" align="center">本日達成</td>
                          <td width="10%" align="center">累計達成</td>
                          <td width="10%" align="center">本月目標</td>
                          <td width="10%" align="center">達成率</td>
                          <td width="50%" align="center"></td>
                        </tr>
                        <tr>
                          <td>施巴業績</td>
                          <td align="center">{{ number_format($event->sales_seba, 0) }}</td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>SC業績</td>
                          <td align="center">{{ number_format($event->sales_sc, 0) }}</td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>總計</td>
                          <td align="center">{{ number_format($event->sales_seba+$event->sales_sc, 0) }}</td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                      </table>
                    </td>
                    <td></td>
                  </tr>
                @endif
              @endforeach

              <!-- 最後一位業務的總計資料 -->
              <tr>
                <td colspan="7">
                  <table class="table table-bordered">
                    <tr>
                      <td width="10%" align="center"></td>
                      <td width="10%" align="center">本日達成</td>
                      <td width="10%" align="center">累計達成</td>
                      <td width="10%" align="center">本月目標</td>
                      <td width="10%" align="center">達成率</td>
                      <td width="50%" align="center"></td>
                    </tr>
                    <tr>
                      <td>施巴業績</td>
                      <td align="center">{{ number_format($event->sales_seba, 0) }}</td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>SC業績</td>
                      <td align="center">{{ number_format($event->sales_sc, 0) }}</td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>總計</td>
                      <td align="center">{{ number_format($event->sales_seba+$event->sales_sc, 0) }}</td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </table>
                </td>
                <td></td>
              </tr> 
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('eventjs')
<script>
  var reportToday = new Date(); // Mon Oct 25 2021 09:05:47 GMT+0800 (台北標準時間)
  var getreportToday = moment(reportToday).format('YYYY-MM-DD HH:mm') // 2021-10-25 09:05
  var reportDate = getreportToday.substr(0, 10) // 2021-10-25
  // console.log(reportToday + '|' + getreportToday + '|' + reportDate );
  var startDateTime = reportDate + ' ' + '00:00';
  var endDateTime = reportDate + ' ' + '23:59';

  $("#start").val(startDateTime).datetimepicker({
    format: 'Y-m-d H:i',
    timepicker: false,
  });
  $("#end").val(endDateTime).datetimepicker({
      timepicker: false,
      format: 'Y-m-d H:i'
  });

  // ** 選出自己的管轄部門名稱，並掛至節點 **
  const filterPdepno = document.querySelector("#filterPdepno");
  const psr = document.querySelector("#psr");
  $.ajax({
    url: 'getPdepno',
    type: 'get',
    cache: false,
    dataType: 'json',
    success: function(data, cus_textStatus, cus_jqXHR) {
      console.log(data.data.getPdepno);
      const getPdepno = data.data.getPdepno;
      let str = '<option selected disabled>請選擇</option>';
      getPdepno.forEach(function(item){
        str += `<option value="${item.pdepno}">${item.pdepno}</option>`;
      });
      filterPdepno.innerHTML = str;
    }
  });

  
  // ** 監聽 : 依部門得到員工名字 **
  filterPdepno.addEventListener('change', function(e){
    console.log(e.target.value);
    let getChoose = e.target.value; // 得到 'MSC'

    $.ajax({
      url: `psr/${getChoose}`,
      type: 'get',
      cache: false,
      dataType: 'json',
      success: function(data, cus_textStatus, cus_jqXHR) {
        // console.log(data.data.getPSRs);
        let strUser = '<option selected disabled>請選擇</option>';
        const getUser = data.data.getPSRs;
        getUser.forEach(item => {
          strUser += `<option value="${item.username_utf8}">${item.username_utf8}</option>`;
        })
        psr.innerHTML = strUser;
      }
    });
  });
  

</script>
@endsection


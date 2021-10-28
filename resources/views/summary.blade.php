@extends('layouts/layout') <!--繼承母模板 layouts/layout-->

@section('title', '彙整總表')

<!-- 篩選條件表格 : 只有主管才能查詢 $utype='G' | $pdepno='MSA' -->
@section('main')
  @if($utype=='G')
    @include('layouts/filterSummary')
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
        @if(isset($titles))
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr class="font-weight-bolder">
              <td class="table-primary" align="center">業務人員</td>
              <td class="table-primary" align="center">拜訪機關</td>
              <td class="table-primary" align="center">工作細項</td>
            </tr>
           
            <?php // 設定全域變數 
              $current_userno = '';
            ?>
            @foreach($titles as $index => $title)  

            <tr>
              <td>
                @if($title->userno!=$current_userno)
                    {{ $title->userno_mandarin}}
                @endif
                <?php // 記錄當下的 $current_userno 來比對下次的員工姓名是否相同
                  $current_userno = $title->userno;
                ?>
              </td>
              <td>
                @if( $title->location1 == '00' )
                  無機關代號 * {{ $title->count }} 次
                @else
                  {{ $title->location1_mandarin }} * {{ $title->count }} 次
                @endif
              </td>
              <td>
                <?php
                  foreach( $results as $key => $result ){ 
                    if( ($result['userno'] == $title->userno) && ($result['location1'] == $title->location1) ){
                      $jobs = $result['jobs'];
                      foreach( $jobs as $key => $job ){ 
                        echo "$jobs_map[$key] * $job 次";
                        echo "<br>";
                      }
                    }
                  }
                ?>
              </td>
            </tr>

            @endforeach
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
  const summaryPdepno = document.querySelector("#summaryPdepno");
  $.ajax({
    url: 'getPdepno',
    type: 'get',
    cache: false,
    dataType: 'json',
    success: function(data, cus_textStatus, cus_jqXHR) {
      console.log(data.data.getPdepno);
      const getPdepno = data.data.getPdepno;
      let str = '<option value="">請選擇</option>';
      getPdepno.forEach(function(item){
        str += `<option value="${item.pdepno}">${item.pdepno}</option>`;
      });
      summaryPdepno.innerHTML = str;
    }
  });
</script>
@endsection
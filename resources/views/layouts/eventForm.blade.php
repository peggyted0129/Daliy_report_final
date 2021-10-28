<div class="modal fade" id="form-event-modal" data-remote="{{ route('event.create') }}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">工作日報</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('event.store') }}" id="formEvent" name="formEvent">
          @csrf
          <div class="form-group">
            <label for="title">主題 : </label>
            <select class="form-control" id="title" name="title" value="{{ old('title') }}">
              <option value="拜訪">拜訪</option>
              <option value="說明會">說明會</option>
              <option value="展售會">展售會</option>
              <option value="開會">開會</option>
              <option value="訓練">訓練</option>
              <option value="訓練">假日支援半天</option>
              <option value="訓練">假日支援全天</option>
            </select>
          </div>
          <div class="form-group">
            <label for="location1">機關 : </label>
            <select class="form-control" id="location1" name="location1" style="width:100%;">
              {{-- 由 eventjs.blade.php 帶入迴圈字串 --}}
            </select>
          </div>
          <div class="form-group">
            <label for="location2">機關小點 : </label>
            <input type="text" class="form-control" id="location2" name="location2" value="{{ old('location2') }}">
          </div>
          <div class="form-group">
            <label for="customers">客戶 : </label>
            <select class="form-control" id="customers" name="customers[]" multiple style="width:100%">
              {{-- 由 eventjs.blade.php 帶入迴圈字串 --}}
            </select>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="allDay" name="allDay" value="{{ old('allDay') }}">
            <label class="form-check-label" for="allDay">全天事件</label>
          </div>
          <div class="form-group row">
            <label for="start" class="col-sm-3 col-form-label">開始時間 : </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="start" name="start" value="{{ old('start') }}" placeholder="開始時間">
            </div>
          </div>
          <div class="form-group row">
            <label for="end" class="col-sm-3 col-form-label">結束時間 : </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="end" name="end" value="{{ old('end') }}" placeholder="結束時間">
            </div>
          </div>
          <p class="mb-3">工作項目 : <span class="text-danger font-weight-bolder">必填</span></p>
          @if(!empty($jobs) && count($jobs) > 0) 
            @foreach($jobs as $key => $job)
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="jobs_{{$key + 1}}" name="jobs[]" value="{{ $key + 1}}">
                <label class="form-check-label" for="jobs_{{$key + 1}}">{{ $job->name }}</label>
              </div>
            @endforeach
          @endif
          <div class="form-group row mt-5">
            <label for="sales_un" class="col-sm-3 col-form-label">小點業績 : </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="sales_un" name="sales_un">
            </div>
          </div>
          <div class="form-group row">
            <label for="sales" class="col-sm-4 col-form-label">施巴當日業績 : </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="sales" name="sales">
            </div>
          </div>
          <div class="form-group row">
            <label for="sales_sc" class="col-sm-4 col-form-label">SC 當日業績 : </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="sales_sc" name="sales_sc">
            </div>
          </div>
          <div class="form-group">
            <label for="description">工作備註 : </label>
            <textarea class="form-control" id="description" name="description" value="{{ old('description') }}" rows="3"></textarea>
          </div>
          <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-danger mr-3" id="delete" data-dismiss="modal">刪除</button>
            <button type="button" class="btn btn-outline-secondary mr-3" id="close" data-dismiss="modal">關閉</button>
            <button type="submit" id="save" class="btn btn-primary">儲存</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


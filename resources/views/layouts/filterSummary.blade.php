<div class="container-fluid">
  <div class="card" style="font-size:14px">
    <div class="card-header p-5">
      篩選條件
    </div>
    <div class="card-body p-5">
      <form method="POST" action="{{ url('summary') }}">
        @csrf
        <div class="form-group">
          <label for="start">開始日期 :</label>
          <input type="text" class="form-control" id="start" name="start">
        </div>
        <div class="form-group">
          <label for="end">結束日期 :</label>
          <input type="text" class="form-control" id="end" name="end">
        </div>
        <div class="form-group">
          <label for="summaryPdepno">部門 :</label>
          <select class="form-control" id="summaryPdepno" name="summaryPdepno">
            {{-- <option>請選擇</option> --}}
          </select>
        </div>
        <div>
          <button type="submit" id="summarySubmit" class="btn btn-primary" style="font-size:14px">查詢</button>
        </div>
      </form>
    </div>
  </div>
</div>


  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <body>
    <div class="container mt-3">
      <form id="saveform" action="{{ url('/postzipcode') }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
        @csrf
        <input type="hidden" name="mode" value="search" />
        <input type="text" value="" placeholder="郵便番号入力" class="form-control mb-3" name="zipcode" />
        <input type="submit" value="検索" class="btn btn-primary" />
      </form>
      <div class="card rounded p-3">
      </div>
    </div>
  </body>

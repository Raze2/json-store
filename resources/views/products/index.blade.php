
@extends('layouts.app')


@section('content')
<form id="form">
    <div id="error" class="alert alert-danger" style="display:none"></div>
    <div id="success" class="alert alert-success" style="display:none">Product Added!</div>
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" aria-describedby="name" placeholder="name">
    </div>
    <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" class="form-control" id="quantity" aria-describedby="quantity" placeholder="quantity">
    </div>
    <div class="form-group">
      <label for="price">Price</label>
      <input type="number" step="0.01" class="form-control" id="price" aria-describedby="price" placeholder="price">
  </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>


<table class="table" id="product_table">
    <thead>
      <tr>
        <th scope="col">Name</th>
        <th scope="col">Quantity</th>
        <th scope="col">Price</th>
        <th scope="col">Created At</th>
        <th scope="col">Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($products as $product)
      <tr>
        <th scope="row">{{$product->name}}</th>
        <th scope="row">{{$product->quantity}}</th>
        <th scope="row">{{$product->price}}</th>
        <th scope="row">{{$product->created_at}}</th>
        <th scope="row">{{$product->total}}</th>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th colspan="4">
        <th id="totalSum">{{$total_sum}}<th>
      </tr>
    </tfoot>
  </table>
@endsection

@section('script')
  <script>
    $(document).ready(function(){
      $('#error').text('');
      $('#error').hide();
      $('#form').on('submit',function(e){
        e.preventDefault();
        $.ajax({
          url: "{{route('products.store')}}",
          type: "POST",
          data: {
            "_token": "{{csrf_token()}}",
            "name": $('#name').val(),
            "quantity": $('#quantity').val(),
            "price": $('#price').val(),
          },
          success:function(response){
            $('#error').hide();
            $('#product_table tbody').append(
              `<tr>
                <th scope="row">${response.name}</th>
                <th scope="row">${response.quantity}</th>
                <th scope="row">${response.price}</th>
                <th scope="row">${response.created_at}</th>
                <th scope="row">${response.total}</th>
              </tr>`
            );
            $('#totalSum').text(Number($('#totalSum').text()) + Number(response.total));
            $('#success').show();
            setTimeout(() => {
              $('#success').hide(); 
            }, 3000); 
          },
          error: function(response) {
            $('#error').text(response.responseJSON.message);
            $('#error').show();
          },
        });
      });
    });
  </script>
@endsection
    @extends('layouts.admin.app')
    @section('title', 'Purchase Product')

    @section('css')
    <link rel="stylesheet" href="{{ asset('admin') }}/plugins/select2/select2.min.css">
    @endsection

    @section('admin_content')
        <section class="card">
            <x-purchase></x-purchase>
            <div class="card-body">
                <form action="@route('purchase.store')" method="POST">
                    <div class="form-gorup">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="">Select Supplier</label>
                                    <select class="form-control select2" name="supplier_id" id="">
                                        <option value="">--select supplier</option>
                                        @foreach ($suppliers as $item)
                                        <option value="{{ $item->id }}">{{ $item->prefix .' '. $item->f_name .' '. $item->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="">Reference no</label>
                                    <input type="number" class="form-control" name="reference_no" placeholder="Enter Reference Number" id="">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="">Date</label>
                                    <input type="date" name="purchase_date" class="form-control" id="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Attech File</label>
                            <input type="file" class="form-control" name="file" id="">
                        </div>
                        <div class="form-gorup">
                            <label for="">Note</label>
                            <textarea name="note" id="" cols="10" rows="4" class="form-control" placeholder="note"></textarea>
                        </div>
                    </div>
                    @csrf
                    <div>
                        <label for="">Product Select</label>
                        <select name="product_id" class="form-control select2" id="product_id">
                            <option value="">Product select</option>
                            @foreach ($products as $pro)
                            <option value="{{ $pro->id }}">{{ $pro->product_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="my-4">
                        <table class="table table-bordered">
                            <thead class="bg-success">
                                <tr>
                                <th scope="col">Product Name</th>
                                <th scope="col">Purchase Quantity</th>
                                <th scope="col">Unite Cose<br>(before disceout)</th>
                                <th scope="col">Discount percent</th>
                                <th scope="col">Unite Cose<br>(before tax)</th>
                                <th scope="col">Tax</th>
                                <th scope="col">Line Total</th>
                                <th scope="col">Profit Margin %</th>
                                <th scope="col">Unit Selling Price</th>
                                <th scope="col">X</th>
                                </tr>
                            </thead>
                            <tbody id="table_value">
                                {{-- dynamic value added in jquery --}}
                            </tbody>
                            </table>
                    </div>

                    <div class="card my-4">
                        <div class="card-header">
                            <h4 class="card-title">Payment Info.</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <label for="">Amount. <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" id="">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <label for="">Paid on. <span class="text-danger">*</span></label>
                                    <input type="date" name="paid_amount_date" class="form-control" id="">
                                </div>
                            </div>
                            <div class="form-control">
                                <label for="">Payment Methods</label>
                                <select class="form-control select2" name="payment_method" id="">
                                    <option value="">--select payment method--</option>
                                    <option value="Bkash">Bkash</option>
                                    <option value="Bkash">Nagud</option>
                                    <option value="Bkash">Rocket</option>
                                    <option value="Bkash">Bank</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    @endsection

    @section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('admin') }}/plugins/select2/select2.full.min.js"></script>
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //end of ajax heaer setup

        $(function () {
            $('.select2').select2()
        })
        //end of select2

        $(document).ready(function(){
            $('#product_id').on('change', function(e){
                var id = e.target.value;
                if(id){
                    $.ajax({
                        url:'/product_purchase_search/'+id,
                        type: 'GET',
                        dataType: 'json',
                        success:function(data){
                            console.log(data);
                            $('#table_value').append('<tr>   <td>'+data.product_name+'</td>    <td> <input class="form-control form-control-sm" type="number" value="0" name="purchase_quantity" id="purchase_quantity"> </td>      <td> <input class="form-control form-control-sm" type="number" value="50" name="unit_cost_before_discount" id="unit_cost_before_discount"> </td>        <td> <input class="form-control form-control-sm" type="number" name="discrount_percent" id="discrount_percent"> </td>        <td> <input class="form-control form-control-sm" type="number" name="unit_cost_before_tax" id="unit_cost_before_tax"> </td>         <td> <input class="form-control form-control-sm" type="number" name="tax" id="tax" value="5"> </td>          <td> <input type="number" class="line_total" id="line_total">  </td>         <td> <input class="form-control form-control-sm" type="number" name="profit_margin" id="profit_margin" value=""> </td>       <td> <input class="form-control form-control-sm" type="number" value="" name="selling_price" id="selling_price"> </td>                </tr>')



                            $("input").keyup(function(){
                                var quantity = $('#purchase_quantity').val();
                                var unit_cost_before_discount = $('#unit_cost_before_discount').val();
                                var tax = $('#tax').val();
                                var total = quantity * unit_cost_before_discount + tax
                                $('#line_total').val(total)

                                var profit_margin = $('#profit_margin').val();
                                var profitSellingTotal = total + profit_margin;
                                $('#selling_price').val(profitSellingTotal)

                            });








                        }//return success function
                    })//this is ajax end
                }


            })
        });


    </script>
    @endsection

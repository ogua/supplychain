<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{ asset('storage') }}/{{ $company?->logo }}" />
    <title>{{$company?->name ?? ""}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="stylesheet" href="{{ URL::to('css/bootstrap.mins.css')}}">

    <style type="text/css">
        * {
            font-size: 14px;
            line-height: 24px;
            font-family: 'Ubuntu', sans-serif;
            text-transform: capitalize;
        }
        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor:pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }
    
        small{font-size:11px;}
    </style>
  </head>
<body>


<div class="card col-md-8 col-md-offset-2">
	<div class="card-body">
		<div class="card-body table-responsive p-0">
            <table class="table" style="border: none !important;">
				<tbody>
                    <tr class="hidden-print">
                        <td colspan="4" class="text-center"><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i>Print</button></td>
                    </tr>
                     <tr>
						<th colspan="2" style="font-size: 13px;">{{ $company?->name ?? "" }} <br> {{ $company?->address ?? "" }}</th>
						<th style="font-size: 13px;">INVOICE NO</th>
                        <th style="font-size: 13px;">{{ $data->order_reff }}</th>
					</tr>
 
                    <tr>
						<th colspan="2" style="font-size: 13px;"></th>
						<th style="font-size: 13px;">Date</th>
                        <th style="font-size: 13px;">{{$data->created_at}}</th>
					</tr>

                    <tr>
						<th colspan="2" style="font-size: 13px;"><b>Customer</b> 
                        @if ($data->client?->company_name)
                            <br> {{$data->client?->company_name}}
                        @else
                            <br> {{$data->client?->name}} 
                        @endif
                        
                        <br> {{$data->client?->email}} <br> {{$data->client?->phone_number}} </th>
						<th colspan="2" style="font-size: 13px;"><b> Shipping Address: </b> <br> {{ $data->delivery?->shipping_address ?? ""}}</th>
					</tr>
				</tbody>
			</table>
			<table class="table table-table table-bordered">
				<thead>
                    <tr class="bg-info">
						<th>#</th>
						<th>Product</th>
                        @if (!$packing)
                            <th>Price</th>
                        @endif
                        <th>Quantity</th>
                        @if (!$packing)
                            <th>Total</th>
                        @endif
					</tr>
				</thead>
				<tbody>
                    @php
                        $count = count($data->items);
                    @endphp
                    @if ($count > 10)

                        @foreach ($data->items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->product->name }}</td>
                                @if (!$packing)
                                    <td style="font-size: 12px;text-align: left">{{ $item->price }}</td>
                                @endif
                                <td style="font-size: 12px;text-align: left">{{ $item->quantity }}</td>
                                @if (!$packing)
                                    <td style="font-size: 12px;text-align: left">{{ $item->total }}</td>
                                @endif
                            </tr>
                        @endforeach

                        @else

                        @for ($x = 0; $x <= 9; $x++)

                            <tr>
                                <td>
                                    @if (isset($data->items[$x]->product->name))
                                        {{ $x + 1 }}
                                    @endif
                                </td>
                                <td style="font-size: 12px;">{{ $data->items[$x]->product->name ?? "" }}</td>

                                @if (!$packing)
                                    <td style="font-size: 12px;text-align: left">{{ $data->items[$x]->price ?? "" }}</td>
                                @endif
                                <td style="font-size: 12px;text-align: left">{{ $data->items[$x]->quantity ?? "" }}</td>
                                @if (!$packing)
                                <td style="font-size: 12px;text-align: left">{{ $data->items[$x]->total ?? "" }}</td>
                                 @endif
                            </tr>

                               
                            
                        @endfor
                        
                    @endif

                    @if (!$packing)

                    <tr>
                        <td colspan="3"></td>
                        <td>Sub Total</td>
                        <td>GHC {{ $data->total }}</td>
                    </tr>

                    <tr>
                        <td colspan="3"></td>
                        <td>Tax</td>
                        <td>GHC {{ $data->order_tax ?? 0 }}</td>
                    </tr>

                    <tr>
                        <td colspan="3"></td>
                        <td>Discount</td>
                        <td>GHC {{ $data->total_discount ?? 0 }}</td>
                    </tr>

                    <tr>
                        <td colspan="3"></td>
                        <td>Shipping</td>
                        <td>GHC {{ $data->shipping_cost ?? 0}}</td>
                    </tr>

                    <tr class="bg-info">
                        <td colspan="3"></td>
                        <td>Grand Total</td>
                        <td>GHC {{ $data->grand_total }}</td>
                    </tr>

                @endif

                    <tr>
                    
                    <td colspan="{{ $packing ? 3 : 5 }}" class="text-center">
                        Thank you for your puchase, come back another time 
                    </td>
                  </tr>
				</tbody>
			</table>
            <div class="text-center" style="margin:30px 0 50px;">
            <small>Generated By {{ $company?->name ?? "" }}</strong></small>
        </div>
		</div>
	</div>
</div>

<script type="text/javascript">
    function auto_print() {     
        window.print()
    }
    setTimeout(auto_print, 1000);
</script>

</body>
</html>
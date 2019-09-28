<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Venta</title>
    <style>

        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif; 
        font-size: 14px;
        /*font-family: SourceSansPro;*/
        }
 
        #logo{
            float: left;
            margin-top: 1%;
            margin-left: 2%;
            margin-right: 2%;
        }
 
        #image{
            width: 100px;
        }
 
        #data{
            float: left;
            margin-top: 0%;
            margin-left: 2%;
            margin-right: 2%;
        /*text-align: justify;*/
        }
 
        #header{
            text-align: center;
            margin-left: 10%;
            margin-right: 35%;
            font-size: 15px;
        }
 
        #invoice{
            /*position: relative;*/
            float: right;
            margin-top: 2%;
            margin-left: 2%;
            margin-right: 2%;
            font-size: 20px;
        }
 
        section{
            clear: left;
        }
 
        #client{
            text-align: left;
        }
 
        #invoice-client{
            width: 40%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px;
        }
 
        #inv, #inv=sell, #inv-prod{
            color: #FFFFFF;
            font-size: 15px;
        }
 
        #invoice-client thead{
            padding: 20px;
            background: #2183E3;
            text-align: left;
            border-bottom: 1px solid #FFFFFF;  
        }
 
        #invoice-seller{
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px;
        }
 
        #invoice-seller thead{
            padding: 20px;
            background: #2183E3;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;  
        }

        #invoice-seller tbody td{
            text-align: center;
        }
 
        #invoice-product{
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px;
            border:#2C7AB8 1px solid;
        }

        #invoice-product thead{
            padding: 20px;
            background: #2183E3;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;  
        }
        
        #invoice-product tbody td{
            border:#2C7AB8 1px solid;
        }

        .total{
            border:#2C7AB8 1px solid;
            text-align: right; 
            padding-right: 15px;
        }

        #thanks{
            text-align: center; 
        }
    </style>
</head>
<body>
    <header>
        <div id="logo">
            <img src="img/logo.png" alt="JT-code" id="image">
        </div>
        <div id="data">
            <p id="header">
                <b>JT-Code</b>
                <br>Santa Elena La Libertad Av 22, Ecuador
                <br>Teléfono: +5930000000
            </p>
        </div>
        <div id="invoice">
            <p>
                <strong style="text-align: center">{{ $sale->voucher_type }}</strong><br>
                {{ $sale->voucher_serie.'-'. $sale->voucher_number }}
            </p>
        </div>
    </header>
    <br>
    <section>
        <div>
            <table id="invoice-client">
                <thead>
                    <tr>
                        <td id="inv">Cliente</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>
                            <p id="client">
                                Sr(a). {{ $sale->name }} <br>
                                {{$sale->voucher_type}}: {{ $sale->document_number }} <br>
                                Dirección: {{ $sale->address }} <br>
                                Teléfono: {{ $sale->phone }} <br>
                                Email: {{ $sale->email }} <br>
                            </p>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    <br>
    <section>
        <div>
            <table id="invoice-seller">
                <thead>
                    <tr id="inv=sell">
                        <th>VENDEDOR</th>
                        <th>FECHA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> {{$sale->username}}</td>
                        <td> {{$sale->date}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    <br>
    <section>
        <div>
            <table id ="invoice-product">
                <thead>
                    <tr id="inv-prod">
                        <th>CANT</th>
                        <th>DESCRIPCION</th>
                        <th>PRECIO UNITARIO</th>
                        <th>DESC.</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $detail)
                    <tr>
                        <td style="text-align: center">{{ $detail->quantity}}</td>
                        <td >{{ $detail->product}}</td>
                        <td style="text-align: center">{{ $detail->price}}</td>
                        <td style="text-align: center">{{ $detail->discount}}</td>
                        <td class="total">{{ $detail->price * $detail->quantity - $detail->discount }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>SUBTOTAL <strong style="float: right">&nbsp;$&nbsp;</strong></th>
                        <th class="total">{{ round($sale->total -($sale->total * $sale->tax), 2) }}</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>IVA <strong style="float: right">&nbsp;$&nbsp;</strong></th>
                        <th class="total">{{ round($sale->total * $sale->tax, 2) }}</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>TOTAL <strong style="float: right">&nbsp;$&nbsp;</strong></th>
                        <th class="total">{{ $sale->total }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
    <br>
    <br>
    <footer>
        <div id="thanks">
            <p><b>Gracias por su compra!</b></p>
        </div>
    </footer>

</body>
</html>
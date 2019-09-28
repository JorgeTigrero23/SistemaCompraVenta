<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Listado de Productos</title>
    <style>

        table th{ 
            background-color:#18BC9C; 
            color:#000;
            text-align:center; 
            font-size:14px; 
            padding: 10px 2px 10px 2px;
        } 
        table{ 
            border-collapse:collapse; 
        } 
        table td{ 
            font-size:13px; 
            padding-right:8px; 
            padding-left:8px;
            padding-bottom:4px;
            padding-top:4px;
            text-align:left; 
            border:#2C7AB8 1px solid; 
        } 
        table thead td { 
            font-size:10px; 
                
            } 
        table tr{ 
            border:1px solid #2C7AB8; 
        } 

        .table-parts{
            padding-top: 50px;
        }

        .right{
            float: right;
        }
        .left{
            float: left;
        }
    </style>
</head>
<body> 
    <div>
        <h3>Sistema de Compra y Venta <span class="right"> {{ now() }} </span></h3>
    </div>
    <!-- HEADER -->
    <div class="header coverpage">

            <div style="width: 100%; height: 10px; background: green; margin-bottom: 2px;"></div>
            <div style="position:relative; height: 63px;">
                <div style="float:left; width: 25%; text-align: center;">
                    {{-- <img src="{{ public_path() }}/images/company/{{ $company->logo }}" width="90px" height="60px"> --}}
                </div>
                <div style="float:left; text-align: center; width: 50%; font-size: 20px; margin-top: 17px;">
                    <strong>Listado de Productos</strong> 
                </div>
            </div>
    
                <div style="width: 100%; height: 1px; background: black; margin-bottom: 10px;">
    
        </div>
    <div>
        <table style="width: 100%">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Precio Venta</th>
                    <th>Stock</th>
                    <th>Stock Mínimo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td> {{ $product->barcode}}</td>
                    <td> {{ $product->name}}</td>
                    <td> {{ $product->description}}</td>
                    <td> {{ $product->category}}</td>
                    <td style="text-align: right"> {{ $product->price_sale}}</td>
                    <td style="text-align: center"> {{ $product->stock}}</td>
                    <td style="text-align: center"> {{ $product->stock_min}}</td>
                    <td style="text-align: center"> {{ $product->deleted_at? 'Desactivado':'Activado' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="left">
    <p><strong>Total de Registros: </strong>{{ $total_records }}</p>
    </div>
    
</body>
</html>
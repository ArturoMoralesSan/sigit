<!DOCTYPE html>
<html>
<head>
    <title>Vale de material y/o equipo</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            background-image: url('{{ asset("img/material.png") }}');
            background-repeat: no-repeat;
            background-size:contain;
            position: relative; 
            font-family: Arial, sans-serif; /* Cambiar la familia de fuentes a Arial */
            font-size: 15px; 
        }

        .container-solicita {
            position: absolute;
            top: 122px; /* Posición superior */
            left: 95px; /* Posición izquierda */
            
        }
        .container-teacher {
            position: absolute;
            top: 122px; /* Posición superior */
            left: 385px; /* Posición izquierda */
            
        }

        .container-date {
            position: absolute;
            top: 122px; /* Posición superior */
            left: 655px; /* Posición izquierda */
        }

        .container-group {
            position: absolute;
            top: 144px; /* Posición superior */
            left: 140px; /* Posición izquierda */
            
        }
        .container-subject {
            position: absolute;
            top: 144px; /* Posición superior */
            left: 395px; /* Posición izquierda */
            
        }
        .container-lab {
            position: absolute;
            top: 144px; /* Posición superior */
            left: 575px; /* Posición izquierda */
        }
        .container-products {
            position: absolute;
            top: 212px; /* Posición superior */
            left: 40px; /* Posición izquierda */
        }
        .container-product {
            height:21px;
        }
        
        .product-qty {
            display: inline-block;
            width: 80px; /* Set the width as needed */
            text-align:center;
        }
        .product-list {
            display: inline-block;
            width: 520px; /* Set the width as needed */
        }
        .return-date {
            display: inline-block;
            width: 40px; /* Set the width as needed */
        }

        

    </style>
</head>
<body>
    <div class="container-solicita">
        <span class="solicita">
            {{ $voucher->req }}
        </span>
    </div>
    <div class="container-teacher">
        <span class="patient">
            {{ $voucher->created_at }}
        </span>
    </div>
    <div class="container-date">
        <span class="patient">
        {{ $voucher->created_at }}
        </span>
    </div>
    <div class="container-group">
        <span class="group">
        {{ $voucher->group }}
        </span>
    </div>
    <div class="container-subject">
        <span class="subject">
        {{ $voucher->subject }}
        </span>
    </div>
    <div class="container-lab">
        <span class="lab">
        {{ $voucher->laboratory }}
        </span>
    </div>
    <div class="container-products">
        <div class="products-relative">
            @foreach($voucher->equipments as $key => $equipment)
                <div class="container-product">
                    <span class="product-qty">
                        {{ $equipment->pivot->quantity }}
                    </span>
                    <span class="product-list">
                        {{ $equipment->product }}
                    </span>
                    <span class="return-date">
                        -
                    </span>
                </div>
            @endforeach
        </div>
        
    </div>
    <div class="container-letter">
       <div class="letter-cost">
            
       </div>
       <div class="container-total">
            <div class="total-subtotal">
                
            </div>
            <div class="total-total">
                
            </div>
       </div>
      
       
    </div>
</body>
</html>
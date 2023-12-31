@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('subirEntrada', ['id' => $pelicula->id, 'id_cliente' => $cliente->id]) }}" method="POST" enctype="multipart/form-data" class="row g-3">

        <h2>Película: {{ $pelicula->titulo }}</h2>

        <h4>Precio: {{ $pelicula->precio }}</h4>

        <h4>Escoge el asiento:</h4>
        <input class="form-control" type="text" name="n°_asiento" placeholder="Agrege el número de asiento" required>
        <br>

        <h4>Horarios Disponibles:</h4>

            @foreach($horario as $horario)
                @if($horario->pelicula_id == $pelicula->id)
                    
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="horario_id" value="{{ $horario->id }}" required>
                    <label class="form-check-label">
                        Fecha: {{ $horario->fecha }}, Hora: {{ $horario->hora }}, Sala: {{ $horario->sala->numero_sala }}
                    </label>
                </div>
                    
                @endif
            @endforeach
        
        <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">

        <button type="submit" class="btn btn-primary">Subir</button>
    </form>
</div>
    <br>
    <div class="container">
        <!-- Agrega el contenedor con el ID 'paypal-button-container' -->
        <div id="paypal-button-container"></div>

        <!-- Incluye la biblioteca de PayPal -->
        <script src="https://www.paypal.com/sdk/js?client-id=Ab6DjbvVBBuBe3kx-TzswrZMpww5_4TIEWxaR-V2QWJYlUFtAdUf4Y94RQlpT8fiB71NBIEpq5uPOAHv&components=buttons,funding-eligibility&locale=es_PE" currency="USD"></script>

        <!-- Agrega tu código JavaScript -->
        <script>

            paypal.Buttons({
                fundingSources: paypal.FUNDING.CARD,
                createOrder: function(data, actions) {
                    return actions.order.create({
                        application_context: {
                            shipping_preference: "NO_SHIPPING"
                        },
                        payer: {
                            email_address: '{{ $cliente->email }}',
                            name: {
                                given_name: '{{ $cliente->nombre }}',
                                surname: '{{ $cliente->apellido }}'
                            },
                            address: {
                                country_code: "PE"
                            }
                        },
                        purchase_units: [{
                            amount: {
                                value: '{{ $pelicula->precio }}'
                            }
                        }],
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        alert('Transaction completed by ' + details.payer.name.given_name + '!');
                    });
                },
                onError: function (err) {
                    // For example, redirect to a specific error page
                    window.location.href = "/your-error-page-here";
                }
            }).render('#paypal-button-container');
        </script>
    </div>

@endsection
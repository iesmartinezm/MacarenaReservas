# MacarenaReservas

## Explicación de las Relaciones
    1. Rol - Usuario → Un rol puede tener muchos usuarios (1:N).

    2. Usuario - Reserva → Un usuario puede hacer muchas reservas (1:N).

    3. Zona - Mesa → Una zona puede tener muchas mesas (1:N).

    4. Mesa - Reserva → Una mesa puede estar en muchas reservas (1:N).

    5. Reserva - Mesa y Usuario → Cada reserva pertenece a una mesa y a un usuario (N:1).
# Macarena Reservas

Este proyecto es una aplicación web de gestión de reservas para un restaurante. Permite a los usuarios hacer reservas en mesas disponibles, ver las reservas realizadas y gestionar los horarios y las mesas.

## Descripción

La aplicación está diseñada para que los usuarios puedan:

1. **Realizar reservas**: Seleccionando la mesa, la fecha y la hora.
2. **Ver las reservas realizadas**: Una lista de todas las reservas existentes con la posibilidad de eliminarlas.
3. **Gestionar las mesas**: Las mesas están organizadas por zonas y pueden ser asignadas a una reserva.
4. **Administrar roles de usuario**: Los usuarios pueden tener diferentes roles definidos en el sistema.

## Funcionalidades

- **Gestión de reservas**: Los usuarios pueden reservar mesas para una fecha y hora específicas.
- **Zonas y mesas**: Las mesas están organizadas en diferentes zonas, permitiendo asignar una mesa a una reserva de forma eficiente.
- **Notificación de disponibilidad**: Si una mesa ya está reservada en el horario seleccionado, el sistema mostrará un mensaje de error y no permitirá realizar la reserva.
- **Control de roles**: Los usuarios tienen diferentes roles, y los administradores pueden gestionar las reservas y las mesas.

## Relaciones entre los Modelos

- **Rol - Usuario**: Un rol puede tener muchos usuarios (1:N).
- **Usuario - Reserva**: Un usuario puede hacer muchas reservas (1:N).
- **Zona - Mesa**: Una zona puede tener muchas mesas (1:N).
- **Mesa - Reserva**: Una mesa puede estar en muchas reservas (1:N).
- **Reserva - Mesa y Usuario**: Cada reserva pertenece a una mesa y a un usuario (N:1).

## Estructura de la Base de Datos

La aplicación tiene las siguientes tablas principales en la base de datos:

- **Usuarios**: Guarda la información de los usuarios que realizan reservas. Cada usuario tiene un correo electrónico y un rol.
- **Roles**: Define los roles disponibles en el sistema (por ejemplo, administrador, cliente).
- **Mesas**: Representa las mesas disponibles en el restaurante, asociadas a una zona.
- **Zonas**: Las zonas son grupos de mesas, cada zona puede tener varias mesas.
- **Reservas**: Contiene las reservas realizadas por los usuarios, asociadas a una mesa y un usuario.

## Flujo de la Aplicación

1. **Creación de un Usuario**: Los usuarios deben introducir su nombre y correo electrónico para poder realizar reservas.
2. **Creación de una Reserva**: Los usuarios seleccionan una mesa, definen la hora de inicio y fin, y el número de comensales. Si la mesa ya está reservada para el horario solicitado, el sistema mostrará un mensaje de error.
3. **Listado de Reservas**: Los administradores pueden ver todas las reservas realizadas y tienen la opción de eliminar alguna de ellas.

## Requisitos del sistema

Requisitos del entorno para la puesta en marcha de la aplicación:

1. **PHP**: Versión 8.0 o superior.

2. **Laravel**: Versión 8.x o superior.

3. **Base de Datos**: MySQL 5.7 o superior.

4. **Servidor web**: Apache o Nginx.

5. **Composer**

## Metodología de desarrollo

1. **Principales tecnologías utilizadas**: Laravel, Blade, Eloquent ORM, Bootstrap, etc.

2. **Enfoque del diseño**: MVC (Modelo-Vista-Controlador).

## Estructura del Proyecto

- **app/Http/Controllers**: Contiene los controladores que gestionan la lógica de la aplicación, como la creación, visualización y eliminación de reservas.

- **app/Models**: Contiene los modelos de la base de datos, como Reserva, Mesa, Usuario, etc.

- **resources/views**: Contiene las vistas Blade que se renderizan para mostrar las páginas al usuario, como la creación de reservas y el listado de reservas.

- **database/migrations**: Contiene las migraciones que definen la estructura de las tablas de la base de datos.

- **database/seeders**: Contiene los seeders para agregar datos de prueba a la base de datos.

## Validaciones
- **Fecha de inicio y fin**: La fecha de fin debe ser posterior a la fecha de inicio.

- **Número de comensales**: Se valida que el número de comensales sea un valor positivo.
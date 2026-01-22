# Project Context

## Objetivo
Plataforma web para cotización de mudanzas usando Google Maps API.
La plataforma web permitirá buscar proveedores cerca de mi ubicación punto A
Se debe almacenar la direccion base del camion punto C
En base a la busqueda del cliente, se deberá buscar un proveedor cerca del punto A
Calcular la Ruta desde el punto C al punto A, luego al Punto B el cual es el destino.
Luego calcular la ruta de retonro desde el punto C al punto A.



## Flujo principal
1. Usuario ingresa dirección origen (A)
2. Usuario ingresa dirección destino (B)
3. Se buscan proveedores cercanos almacenados en la base de datos punto (C)
4. Se calcula ruta: C → A → B → C
5. Se genera cotización


## Reglas importantes
- No exponer API Keys en frontend
- Cachear geocoding
- Backend es la fuente de verdad

## Archivos clave
- `/api/geocode.php` → geocoding
- `/api/cotizar.php` → lógica principal
- `/lib/googleMaps.php` → wrapper APIs

## Qué NO hacer
- No llamar Google APIs desde JS
- No recalcular direcciones repetidas